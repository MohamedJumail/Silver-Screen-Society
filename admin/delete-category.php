<?php
include "config/database.php";
if (!isset($_SESSION['user_is_admin'])) {
    header("location: " . ROOT_URL . "logout.php");
    session_destroy();
}
if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $update_query = "UPDATE posts SET category_id=3 WHERE category_id=$id";
    $update_result = mysqli_query($connection, $update_query);
    if (!mysqli_errno($connection)) {
        $query = "DELETE FROM categories WHERE id='$id' LIMIT 1";
        $result = mysqli_query($connection, $query);
        $_SESSION['edit-category-success'] = "Genre was deleted successfuly";
        header("location: " . ROOT_URL . "admin/manage-categories.php");
        die();
    }
} else {
    header("location: " . ROOT_URL . "admin/manage-categories.php");
    die();
}
