<?php

/**
 * Class that represents an account.
 */
class Account
{
    private $connection;
    private $errorArray = [];

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function register(
        string $firstName,
        string $lastName,
        string $username,
        string $email,
        string $email2,
        string $password,
        string $password2
    ): bool
    {
        $this->validateFirstName($firstName);
        $this->validateLastName($lastName);
        $this->validateUsername($username);
        $this->validateEmails($email, $email2);
        $this->validatePasswords($password, $password2);

        if (empty($this->errorArray))
            return $this->insertUserDetails($firstName, $lastName, $username, $email, $password);

        return false;
    }

    public function login(string $username, string $password): bool
    {
        $password = hash('sha512', $password);

        $query = $this->connection->prepare('SELECT * FROM users WHERE username = :username AND password = :password');
        $query->bindValue(':username', $username);
        $query->bindValue(':password', $password);
        $query->execute();

        if ($query->rowCount() === 1)
            return true;

        $this->errorArray[] = Constants::$loginFailed;

        return false;
    }

    private function insertUserDetails(string $firstName, string $lastName, string $username, string $email, string $password): bool
    {
        $password = hash('sha512', $password);

        $query = $this->connection->prepare(
            'INSERT INTO users (firstName, lastName, username, email, password) VALUES (:firstName, :lastName, :username, :email, :password)'
        );
        $query->bindValue(':firstName', $firstName);
        $query->bindValue(':lastName', $lastName);
        $query->bindValue(':username', $username);
        $query->bindValue(':email', $email);
        $query->bindValue(':password', $password);

        return $query->execute();
    }

    private function validateFirstName(string $firstName): void
    {
        if (strlen($firstName) < 2 || strlen($firstName) > 25)
            $this->errorArray[] = Constants::$firstNameCharacters;
    }

    private function validateLastName(string $lastName): void
    {
        if (strlen($lastName) < 2 || strlen($lastName) > 25)
            $this->errorArray[] = Constants::$lastNameCharacters;
    }

    private function validateUsername(string $username): void
    {
        if (strlen($username) < 2 || strlen($username) > 25) {
            $this->errorArray[] = Constants::$usernameCharacters;
            return;
        }

        $query = $this->connection->prepare('SELECT * FROM users WHERE username=:username');
        $query->bindValue(':username', $username);
        $query->execute();

        if ($query->rowCount() !== 0)
            $this->errorArray[] = Constants::$usernameTaken;
    }

    private function validateEmails(string $email, string $email2): void
    {
        if ($email !== $email2) {
            $this->errorArray[] = Constants::$emailsDontMatch;
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errorArray[] = Constants::$emailInvalid;
            return;
        }

        $query = $this->connection->prepare('SELECT * FROM users WHERE email=:email');
        $query->bindValue(':email', $email);
        $query->execute();

        if ($query->rowCount() !== 0)
            $this->errorArray[] = Constants::$emailTaken;
    }

    private function validatePasswords(string $password, string $password2): void
    {
        if ($password !== $password2) {
            $this->errorArray[] = Constants::$passwordsDontMatch;
            return;
        }

        if (strlen($password) < 5 || strlen($password) > 25)
            $this->errorArray[] = Constants::$passwordLength;
    }

    public function getError(string $error)
    {
        if (in_array($error, $this->errorArray))
            return "<span class='errorMessage'>$error</span>";
    }
}