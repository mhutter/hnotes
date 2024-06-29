use std::collections::HashMap;

use argon2::{
    password_hash::{rand_core::OsRng, SaltString},
    Argon2, PasswordHash, PasswordHasher, PasswordVerifier,
};
use askama_axum::IntoResponse;
use axum::{
    extract::{FromRef, State},
    http::header,
    response::{Redirect, Response},
    routing::{get, post},
    Form, Router,
};
use axum_extra::{extract::cookie::Key, response::Css};
use hnotes::web::view::{AboutView, LoginView, SignupView};
use mongodb::{
    bson::{doc, Uuid},
    Database,
};
use serde::{Deserialize, Serialize};
use tokio::net::TcpListener;
use tower_http::trace::{DefaultMakeSpan, DefaultOnRequest, DefaultOnResponse, TraceLayer};
use tracing::Level;

#[tokio::main]
async fn main() {
    tracing_subscriber::fmt::init();

    let db = connect_mongodb().await;
    let key = Key::generate();

    let state = AppState { db, key };

    let app = Router::new()
        .route("/", get(|| async { AboutView }))
        .route("/signup", get(users_new))
        .route("/signup", post(users_create))
        .route("/login", get(session_new))
        .route("/login", post(session_create))
        .route(
            "/favicon.ico",
            get(|| async {
                (
                    [(header::CONTENT_TYPE, "image/x-icon")],
                    include_bytes!("../static/favicon.ico"),
                )
            }),
        )
        .route(
            "/s/style.css",
            get(|| async { Css(include_str!("../static/style.css")) }),
        )
        .layer(
            TraceLayer::new_for_http()
                .make_span_with(DefaultMakeSpan::new().level(Level::INFO))
                .on_request(DefaultOnRequest::new().level(Level::TRACE))
                .on_response(DefaultOnResponse::new().level(Level::INFO)),
        )
        .with_state(state);

    #[cfg(feature = "livereload")]
    let app = {
        tracing::info!("enabling LiveReload");
        app.layer(tower_livereload::LiveReloadLayer::new())
    };

    let listener = TcpListener::bind("127.0.0.1:8080")
        .await
        .expect("bind TCP listener");

    tracing::info!(
        url = format!("http://localhost:{}", listener.local_addr().unwrap().port()),
        "server started"
    );
    axum::serve(listener, app).await.expect("start server");
}

async fn connect_mongodb() -> Database {
    let uri = "mongodb://localhost:27017/hnotes_dev?replicaSet=rs0&connectTimeoutMS=5000&serverSelectionTimeoutMS=5000";
    tracing::debug!(%uri, "connecting to MongoDB");
    let client = mongodb::Client::with_uri_str(uri)
        .await
        .expect("connect to MongoDB");
    let db = client.default_database().expect("default database");

    tracing::debug!(db = db.name(), "connected to MongoDB");
    db.run_command(doc! {"ping": 1}, None)
        .await
        .expect("ping MongoDB");
    db
}

#[derive(Clone)]
struct AppState {
    db: Database,
    key: Key,
}

impl FromRef<AppState> for Database {
    fn from_ref(input: &AppState) -> Self {
        input.db.clone()
    }
}
impl FromRef<AppState> for Key {
    fn from_ref(input: &AppState) -> Self {
        input.key.clone()
    }
}

async fn users_new<'a>() -> SignupView<'a> {
    SignupView::default()
}

#[derive(Deserialize)]
struct SignupParams {
    username: String,
    password: String,
    password_confirmation: String,
    email: String,
}

async fn users_create(State(db): State<Database>, Form(form): Form<SignupParams>) -> Response {
    // check for possibly automated signups
    if !form.email.is_empty() {
        return Redirect::to("/login").into_response();
    }

    let mut errors = HashMap::new();

    let username = form.username.trim();
    if username.is_empty() {
        errors.insert("username", "cannot be empty");
    }

    let password = form.password;
    if password.len() < 8 {
        errors.insert("password", "must be at least 8 characters long");
    }

    if password != form.password_confirmation {
        errors.insert("password_confirmation", "Passwords do not match");
    }

    // abort early if we already have errors
    if !errors.is_empty() {
        return SignupView::new(Some(username), errors).into_response();
    }

    let coll = db.collection::<User>("users");
    let res = coll
        .find_one(doc! {"username": username}, None)
        .await
        .expect("check username for existence");

    if res.is_some() {
        errors.insert("username", "already taken");
    }

    if !errors.is_empty() {
        return SignupView::new(Some(username), errors).into_response();
    }

    let hashed_password = Argon2::default()
        .hash_password(password.as_bytes(), &SaltString::generate(OsRng))
        .expect("hash password")
        .to_string();

    let user = User {
        id: Uuid::new(),
        username: username.to_string(),
        hashed_password,
    };

    coll.insert_one(&user, None).await.expect("create user");
    Redirect::to("/login").into_response()
}

#[derive(Deserialize, Serialize)]
struct User {
    #[serde(rename = "_id")]
    id: Uuid,
    username: String,
    hashed_password: String,
}

async fn session_new<'a>() -> LoginView<'a> {
    LoginView::default()
}

#[derive(Deserialize)]
struct LoginParams {
    username: String,
    password: String,
}

async fn session_create(State(db): State<Database>, Form(form): Form<LoginParams>) -> Response {
    let username = form.username.trim();

    let res = db
        .collection::<User>("users")
        .find_one(doc! {"username": username}, None)
        .await
        .expect("find user");

    let Some(user) = res else {
        // no user found
        return LoginView::new(Some(username), Some("User not found or password invalid"))
            .into_response();
    };

    let hash = PasswordHash::new(&user.hashed_password).expect("valid password hash");
    if Argon2::default()
        .verify_password(form.password.as_bytes(), &hash)
        .is_err()
    {
        return LoginView::new(Some(username), Some("User not found or password invalid"))
            .into_response();
    }

    LoginView::default().into_response()
}
