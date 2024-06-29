use std::future::Future;

use crate::domain::{create_user::CreateUserError, entities::User};

#[cfg(test)]
pub mod inmemory;

pub trait UserRepository: Send + Sync {
    fn insert(&self, user: User) -> impl Future<Output = Result<User, CreateUserError>>;
}
