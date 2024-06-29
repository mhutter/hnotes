use std::collections::HashMap;

use askama::Template;

#[derive(Template)]
#[template(path = "about.html")]
pub struct AboutView;

#[derive(Default, Template)]
#[template(path = "signup.html")]
pub struct SignupView<'a> {
    username: Option<&'a str>,
    errors: HashMap<&'a str, &'a str>,
}
impl<'a> SignupView<'a> {
    pub fn new(username: Option<&'a str>, errors: HashMap<&'a str, &'a str>) -> Self {
        Self { username, errors }
    }
}

#[derive(Default, Template)]
#[template(path = "login.html")]
pub struct LoginView<'a> {
    username: Option<&'a str>,
    error: Option<&'a str>,
}
impl<'a> LoginView<'a> {
    pub fn new(username: Option<&'a str>, error: Option<&'a str>) -> Self {
        Self { username, error }
    }
}
