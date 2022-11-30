<?php

require_once('includes/config.php');
require_once('includes/classes/Account.php');
require_once('includes/classes/Constants.php');
require_once('includes/classes/FormSanitizer.php');

$account = new Account($connection);

if (isset($_POST['submitButton'])) {
    $firstName = FormSanitizer::sanitizeFormString($_POST['firstName']);
    $lastName = FormSanitizer::sanitizeFormString($_POST['lastName']);
    $username = FormSanitizer::sanitizeFormUsername($_POST['username']);
    $email = FormSanitizer::sanitizeFormEmail($_POST['email']);
    $email2 = FormSanitizer::sanitizeFormEmail($_POST['email2']);
    $password = FormSanitizer::sanitizeFormPassword($_POST['password']);
    $password2 = FormSanitizer::sanitizeFormPassword($_POST['password2']);

    $success = $account->register($firstName, $lastName, $username, $email, $email2, $password, $password2);

    if ($success) {
        $_SESSION['userLoggedIn'] = $username;
        header('Location: index.php');
    }
}

function getInputValue(string $name): void
{
    if (isset($_POST[$name]))
        echo $_POST[$name];
}

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset='utf-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <title>Welcome to HenriFlix</title>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <link rel='stylesheet' type='text/css' media='screen' href='assets/style/style.css' />
        <!-- <script src='main.js'></script> -->
    </head>

    <body>
        <div class="signInContainer">
            <div class="column">
                <div class="header">
                    <img src="assets/images/logo.png" title="Logo" alt="Site logo">
                    <h3>Sign Up</h3>
                    <span>to continue to HenriFlix</span>
                </div>

                <form method="POST">
                    <?php echo $account->getError(Constants::$firstNameCharacters); ?>
                    <input type="text" name="firstName" placeholder="First name"
                        value="<?= getInputValue('firstName'); ?>" required />

                    <?php echo $account->getError(Constants::$lastNameCharacters); ?>
                    <input type="text" name="lastName" placeholder="Last name" value="<?= getInputValue('lastName'); ?>"
                        required />

                    <?php echo $account->getError(Constants::$usernameCharacters); ?>
                    <?php echo $account->getError(Constants::$usernameTaken); ?>
                    <input type="text" name="username" placeholder="Username" value="<?= getInputValue('username'); ?>"
                        required />

                    <?php echo $account->getError(Constants::$emailsDontMatch); ?>
                    <?php echo $account->getError(Constants::$emailInvalid); ?>
                    <?php echo $account->getError(Constants::$emailTaken); ?>
                    <input type="email" name="email" placeholder="Email" value="<?= getInputValue('email'); ?>"
                        required />
                    <input type="email" name="email2" placeholder="Confirm email"
                        value="<?= getInputValue('email2'); ?>" required />

                    <?php echo $account->getError(Constants::$passwordsDontMatch); ?>
                    <?php echo $account->getError(Constants::$passwordLength); ?>
                    <input type="password" name="password" placeholder="Password" required />
                    <input type="password" name="password2" placeholder="Confirm password" required />

                    <input type="submit" name="submitButton" value="SUBMIT" />
                </form>

                <a class="signInMessage" href="login.php">Already have an account? Sign in here!</a>
            </div>
        </div>
    </body>

</html>