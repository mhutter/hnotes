use actix_web::HttpResponse;

pub async fn login() -> HttpResponse {
    HttpResponse::Ok().finish()
}

pub async fn signup() -> HttpResponse {
    HttpResponse::Ok().finish()
}
