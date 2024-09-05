<?php
require 'config/database.php';

if (isset($_POST['submit'])) {
    $username_email = filter_var($_POST['username_email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (!$username_email) {
        $_SESSION['signin'] = "Username or Email required";
    } elseif (!$password) {
        $_SESSION['signin'] = "Password required";
    } else {
        $fetch_user_query = "SELECT * FROM users WHERE username='$username_email' OR email='$username_email'";
        $fetch_user_result = mysqli_query($connection, $fetch_user_query);

        if (mysqli_num_rows($fetch_user_result) == 1) {
            $user_record = mysqli_fetch_assoc($fetch_user_result);
            $db_password = $user_record['password'];
            if (password_verify($password, $db_password)) {
                $_SESSION['user-id'] = $user_record['id'];
                if ($user_record['is_admin'] == 1) {
                    $_SESSION['user_is_admin'] = true;
                }
                setcookie('username', $username_email, time() + (86400 * 30), "/");
                header('Location: ' . ROOT_URL . 'admin/');
                exit();
            } else {
                $_SESSION['signin'] = "Please check your credentials";
            }
        } else {
            $_SESSION['signin'] = "User Not Found";
        }
    }
    if (isset($_SESSION['signin'])) {
        $_SESSION['signin-data'] = $_POST;
        header('Location: ' . ROOT_URL . 'signin.php');
        die();
    }
} else {
    header('Location: ' . ROOT_URL . 'signin.php');
    exit();
}
?>
