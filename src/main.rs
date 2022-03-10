use std::{env, net::TcpListener};

use hnotes::startup;

const DEFAULT_BIND_ADDR: &str = "127.0.0.1:8080";

#[tokio::main]
async fn main() -> std::io::Result<()> {
    let addr = env::var("HNOTES_BIND_ADDR").unwrap_or_else(|_| String::from(DEFAULT_BIND_ADDR));
    let listener = TcpListener::bind(&addr)?;
    println!("Listening to http://{addr}/");
    startup::run(listener)?.await
}
