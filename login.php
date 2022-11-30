<?php

require_once('includes/config.php');
require_once('includes/classes/Account.php');
require_once('includes/classes/Constants.php');
require_once('includes/classes/FormSanitizer.php');

$account = new Account($connection);

if (isset($_POST['submitButton'])) {
    $username = FormSanitizer::sanitizeFormUsername($_POST['username']);
    $password = FormSanitizer::sanitizeFormPassword($_POST['password']);

    $success = $account->login($username, $password);

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
                    <h3>Sign In</h3>
                    <span>to continue to HenriFlix</span>
                </div>

                <form method="POST">
                    <?php echo $account->getError(Constants::$loginFailed); ?>
                    <input type="text" name="username" placeholder="Username" value="<?= getInputValue('username'); ?>"
                        required />
                    <input type="password" name="password" placeholder="Password" required />
                    <input type="submit" name="submitButton" value="SUBMIT" />
                </form>

                <a class="signInMessage" href="register.php">Need an account? Sign up here!</a>
            </div>
        </div>
    </body>

</html>