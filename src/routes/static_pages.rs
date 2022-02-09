use actix_web::HttpResponse;

pub async fn about() -> HttpResponse {
    HttpResponse::Ok().body("About hNotes")
}

pub async fn impressum() -> HttpResponse {
    HttpResponse::Ok().body("Impressum")
}
