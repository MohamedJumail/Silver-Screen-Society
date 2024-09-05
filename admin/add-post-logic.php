<?php
require "config/database.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['submit'])) {
    $author_id = $_SESSION['user-id'];
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $body = filter_var($_POST['body'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_id = filter_var($_POST['category_id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $is_featured = filter_var($_POST['is_featured'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $thumbnail = $_FILES['thumbnail'];

    $is_featured = $is_featured == 1 ? 1 : 0;

    if (!$title) {
        $_SESSION['add-post'] = "Enter Movie Title";
    } elseif (!$category_id) {
        $_SESSION['add-post'] = "Select Movie Genre";
    } elseif (!$body) {
        $_SESSION['add-post'] = "Type Your Review";
    } elseif (!$thumbnail['name']) {
        $_SESSION['add-post'] = "Choose Movie Poster";
    } else {
        $time = time();
        $thumbnail_name = $time . $thumbnail['name'];
        $thumbnail_tmp_name = $thumbnail['tmp_name'];
        $thumbnail_destination_path = "../images/" . $thumbnail_name;

        $allowed_files = ['jpg', 'png', 'jpeg'];
        $extension = explode('.', $thumbnail_name);
        $extension = end($extension);
        if (in_array($extension, $allowed_files)) {
            if ($thumbnail['size'] < 2000000) {
                move_uploaded_file($thumbnail_tmp_name, $thumbnail_destination_path);
            } else {
                $_SESSION['add-post'] = "File size too big. Should be less than 2mb";
            }
        } else {
            $_SESSION['add-post'] = "File should be png, jpg or jpeg";
        }
    }

    if (isset($_SESSION['add-post'])) {
        $_SESSION['add-post-data'] = $_POST;
        header('location: ' . ROOT_URL . 'admin/add-posts.php');
        die();
    } else {
        if ($is_featured == 1) {
            $zero_all_is_featured_query = "UPDATE posts SET is_featured=0";
            $zero_all_is_featured_result = mysqli_query($connection, $zero_all_is_featured_query);
        }
        $query = "INSERT INTO posts (title, body, thumbnail, category_id, author_id, is_featured) VALUES ('$title', '$body', '$thumbnail_name', $category_id, $author_id, $is_featured)";
        $result = mysqli_query($connection, $query);
        if (mysqli_errno($connection)) {
            $_SESSION['add-post'] = "Failed to add post";
            header("location: " . ROOT_URL . 'admin/index.php');
            die();
        } else {
            $_SESSION['add-post-success'] = "Your Review added successfully";

            $user_email = $_SESSION['email'];
            $subject = "Review Added Successfully";
            $message = "Your review for the movie '$title' is added successfully on the Silver Screen Society website.";
            $headers = "From: mhmdjumail@ptuniv.edu.in";

            mail($user_email, $subject, $message, $headers);

            header("location: " . ROOT_URL . 'admin/index.php');
            die();
        }
    }
}

header("location: " . ROOT_URL . 'admin/index.php');
die();
?>
