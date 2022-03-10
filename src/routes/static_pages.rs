use actix_web::HttpResponse;

pub async fn about() -> HttpResponse {
    HttpResponse::Ok().body("<title>About - hNotes</title>")
}

pub async fn impressum() -> HttpResponse {
    HttpResponse::Ok().body("<title>Impressum - hNotes</title>")
}
