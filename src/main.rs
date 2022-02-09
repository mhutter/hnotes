use std::{io, net::TcpListener};

use actix_web::{dev::Server, web, App, HttpServer};
use hnotes::routes::{about, impressum, login, signup};

const ADDR: &str = "localhost:8080";

#[tokio::main]
async fn main() -> std::io::Result<()> {
    let listener = TcpListener::bind(ADDR)?;
    println!("Listening to http://{}/", ADDR);
    run(listener)?.await
}

fn run(listener: TcpListener) -> io::Result<Server> {
    let server = HttpServer::new(move || {
        App::new()
            .route("/about", web::get().to(about))
            .route("/impressum", web::get().to(impressum))
            .route("/login", web::get().to(login))
            .route("/signup", web::get().to(signup))
    })
    .listen(listener)?
    .run();

    Ok(server)
}
