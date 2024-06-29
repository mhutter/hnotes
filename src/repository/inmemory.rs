use std::{
    collections::HashMap,
    sync::{Arc, Mutex},
};

use crate::domain::{create_user::CreateUserError, entities::User};

use super::UserRepository;

#[derive(Default)]
pub struct InMemoryRepo {
    users: Arc<Mutex<HashMap<String, User>>>,
}

impl UserRepository for &InMemoryRepo {
    async fn insert(&self, entity: User) -> Result<User, CreateUserError> {
        todo!()
    }
}
