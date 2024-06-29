use core::fmt;

use argon2::{
    password_hash::{rand_core::OsRng, SaltString},
    Argon2, PasswordHasher,
};
use chrono::{DateTime, Utc};
use uuid::Uuid;

pub const MIN_PASSWORD_LENGTH: usize = 8;

/// An user interacting with this application
pub struct User {
    pub id: Uuid,
    pub username: Username,
    pub password: Password,
    pub created_at: DateTime<Utc>,
}

impl User {
    /// Construct a new user with the given attributes.
    ///
    /// Will generate an ID and set `created_at` to the current datetime.
    pub fn new(username: Username, password: Password) -> Self {
        Self {
            id: Uuid::new_v4(),
            username,
            password,
            created_at: Utc::now(),
        }
    }
}

/// A non-empty username containing only valid characters
#[derive(Debug)]
pub struct Username(String);

impl Username {
    /// Trim & validate the input
    pub fn new(name: &str) -> Result<Self, UsernameError> {
        let name = name.trim();

        if name.is_empty() {
            return Err(UsernameError::Empty);
        }

        if name.chars().any(|c| !c.is_ascii_alphanumeric()) {
            return Err(UsernameError::InvalidCharacters);
        }

        Ok(Self(name.into()))
    }
}

impl AsRef<str> for Username {
    fn as_ref(&self) -> &str {
        self.0.as_str()
    }
}

#[derive(Debug, thiserror::Error, PartialEq, Eq)]
pub enum UsernameError {
    #[error("the username is empty")]
    Empty,

    #[error("contains non-alphanumeric characters")]
    InvalidCharacters,
}

/// A securely hashed password that conforms to password complexity rules
pub struct Password(String);

impl Password {
    /// Validate & hash the given password
    pub fn new(password: &str) -> Result<Self, PasswordError> {
        if password.len() < MIN_PASSWORD_LENGTH {
            return Err(PasswordError::TooShort);
        }

        let salt = SaltString::generate(OsRng);
        let password = Argon2::default()
            .hash_password(password.as_bytes(), &salt)?
            .to_string();
        Ok(Self(password))
    }
}

impl fmt::Debug for Password {
    fn fmt(&self, f: &mut fmt::Formatter<'_>) -> fmt::Result {
        "[hashed]".fmt(f)
    }
}

#[derive(Debug, PartialEq, Eq, thiserror::Error)]
pub enum PasswordError {
    #[error(
        "the password is too short, it must be at least {MIN_PASSWORD_LENGTH} characters long"
    )]
    TooShort,

    #[error("failed to hash the password: {0}")]
    HashError(#[from] argon2::password_hash::Error),
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn username_is_trimmed() {
        assert_eq!("foo", Username::new(" foo").unwrap().0);
        assert_eq!("foo", Username::new("foo ").unwrap().0);
        assert_eq!("foo", Username::new(" foo ").unwrap().0);
        assert_eq!("foo", Username::new(" foo  ").unwrap().0);
    }

    #[test]
    fn username_cannot_be_empty() {
        assert_eq!(UsernameError::Empty, Username::new("").unwrap_err());
        assert_eq!(UsernameError::Empty, Username::new("  ").unwrap_err());
    }

    #[test]
    fn username_invalid_characters_are_rejected() {
        let err = Username::new("smørrebrød").unwrap_err();
        assert_eq!(UsernameError::InvalidCharacters, err);
    }

    #[test]
    fn password_is_encrypted() {
        const PW: &str = "sekr1tpassw0rd";
        let pw = Password::new(PW).unwrap();
        assert_ne!(PW, pw.0);
    }

    #[test]
    fn password_must_adhere_to_minimum_complexity() {
        let err = Password::new("short").unwrap_err();
        assert_eq!(PasswordError::TooShort, err);
    }
}
