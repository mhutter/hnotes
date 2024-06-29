use crate::{
    domain::entities::{Password, Username},
    repository::UserRepository,
};

use super::entities::User;

/// Create and persist a new user with the given attributes
pub async fn create_user<R: UserRepository>(
    users: R,
    username: &str,
    password: &str,
) -> Result<User, CreateUserError> {
    let username = Username::new(username).unwrap();
    let password = Password::new(password).unwrap();

    let user = User::new(username, password);
    users.insert(user).await
}

#[derive(Debug, thiserror::Error)]
pub enum CreateUserError {
    #[error("An user with this username already exists")]
    Conflict,

    #[error("Unknown error while saving the user: {0}")]
    Unknown(#[from] Box<dyn std::error::Error>),
}

#[cfg(test)]
mod tests {
    use uuid::Uuid;

    use crate::repository::inmemory::InMemoryRepo;

    use super::*;

    const VALID_USERNAME: &str = "jdoe";
    const VALID_PASSWORD: &str = "h3lp1mtrapp3d1nap@sswordg3n3rat0r";

    #[tokio::test]
    async fn should_create_valid_user() {
        let repo = InMemoryRepo::default();
        let user = create_user(&repo, VALID_USERNAME, VALID_PASSWORD)
            .await
            .expect("create user");

        assert_eq!(VALID_USERNAME, user.username.as_ref());
        assert_ne!(Uuid::nil(), user.id);
    }
}
