use std::{io, net::TcpListener};

use actix_web::{dev::Server, web, App, HttpServer};

use crate::routes::*;

pub fn run(listener: TcpListener) -> io::Result<Server> {
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
