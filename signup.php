<?php

require 'config/constants.php';

$firstname = $_SESSION['signup-data']['firstname'] ?? '';
$lastname = $_SESSION['signup-data']['lastname'] ?? '';
$username = $_SESSION['signup-data']['username'] ?? '';
$email = $_SESSION['signup-data']['email'] ?? '';
$createpassword = $_SESSION['signup-data']['createpassword'] ?? '';
$confirmpassword = $_SESSION['signup-data']['confirmpassword'] ?? '';
unset($_SESSION['signup-data']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SilverScreenSociety</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Text:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="<?= ROOT_URL ?>css/style.css">
</head>

<body>
    <section class="form__section">
        <div class="container form__section-container">
            <h2>Sign Up</h2>
            <?php if (isset($_SESSION['signup'])) : ?>
                <div class="alert__message error">
                    <p>
                        <?= $_SESSION['signup']; ?>
                        <?php unset($_SESSION['signup']); ?>
                    </p>
                </div>
            <?php endif; ?>
            <form action="<?= ROOT_URL ?>signup-logic.php" enctype="multipart/form-data" method="post">
                <input type="text" name="firstname" value="<?= htmlspecialchars($firstname) ?>" placeholder="First Name">
                <input type="text" name="lastname" value="<?= htmlspecialchars($lastname) ?>" placeholder="Last Name">
                <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" placeholder="Username" autocomplete="off">
                <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="Mail-ID">
                <input type="password" name="createpassword" value="<?= htmlspecialchars($createpassword) ?>" placeholder="Create Password" autocomplete="off">
                <input type="password" name="confirmpassword" value="<?= htmlspecialchars($confirmpassword) ?>" placeholder="Confirm Password">
                <div class="form__control">
                    <label for="avatar">User Profile</label>
                    <input type="file" name="avatar" id="avatar">
                </div>
                <button type="submit" name="submit" class="btn">Sign Up</button>
                <small>Already have an Account? <a href="signin.php">Sign In</a></small>
            </form>
        </div>
    </section>
</body>
</html>
