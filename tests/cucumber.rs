use std::{convert::Infallible, net::TcpListener};

use async_trait::async_trait;
use cucumber::{given, then, when, WorldInit};
use reqwest::StatusCode;

#[derive(Default, Debug, WorldInit)]
struct World {
    addr: String,
    last_response: Option<LastResponse>,
}
#[derive(Debug)]
struct LastResponse {
    code: StatusCode,
    body: String,
}

impl World {
    fn body_contains(&self, pat: &str) -> bool {
        let body = &self.last_response.as_ref().unwrap().body;
        body.contains(pat)
    }
}

#[given("the app is started")]
async fn the_app_is_started(world: &mut World) {
    let listener = TcpListener::bind("127.0.0.1:0").expect("bind random port");
    let port = listener.local_addr().unwrap().port();

    let server = hnotes::startup::run(listener).expect("start server");
    tokio::spawn(server);

    world.addr = format!("http://127.0.0.1:{port}");
}

#[given("I am not authenticated")]
async fn i_am_not_authenticated(_: &mut World) {}

#[when(regex = r#"^I visit "([^"]+)"$"#)]
async fn i_visit(world: &mut World, path: String) {
    let url = format!("{}{}", world.addr, path);
    let res = reqwest::get(&url)
        .await
        .unwrap_or_else(|err| panic!("GET {}: {}", &url, err));

    let code = res.status();
    let body = res.text().await.unwrap();

    world.last_response = Some(LastResponse { code, body })
}

#[then("the request should be successful")]
async fn the_request_should_be_successful(world: &mut World) {
    let response = world.last_response.as_ref().unwrap();
    assert_eq!(response.code, 200);
}

#[then(regex = r#"^the title should be "([^"]+)"$"#)]
async fn the_title_should_be(world: &mut World, expected: String) {
    assert!(world.body_contains(&format!("<title>{expected}</title>")));
}

#[async_trait(?Send)]
impl cucumber::World for World {
    type Error = Infallible;

    async fn new() -> Result<Self, Self::Error> {
        Ok(Self::default())
    }
}

#[tokio::main]
async fn main() {
    World::run("features/public_pages.feature").await;
}
