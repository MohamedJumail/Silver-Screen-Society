<?php
include "partials/header.php";
$current_user_id = $_SESSION['user-id'];
$query = "SELECT  id , title ,category_id FROM posts WHERE author_id=$current_user_id ORDER BY id DESC";
$posts = mysqli_query($connection, $query);
?>
<section class="dashboard">
    <?php if (isset($_SESSION['signin-success'])) : ?>

        <div class="alert__message success container">
            <p>
                <?= $_SESSION['signin-success'];
                unset($_SESSION['signin-success']);
                ?>
            </p>
        </div>
    <?php elseif (isset($_SESSION['add-post'])) : ?>

        <div class="alert__message error container">
            <p>
                <?= $_SESSION['add-post'];
                unset($_SESSION['add-post']);
                ?>
            </p>
        </div>
    <?php elseif (isset($_SESSION['add-post-success'])) : ?>

        <div class="alert__message success container">
            <p>
                <?= $_SESSION['add-post-success'];
                unset($_SESSION['add-post-success']);
                ?>
            </p>
        </div>
    <?php elseif (isset($_SESSION['edit-post'])) : ?>

        <div class="alert__message error container">
            <p>
                <?= $_SESSION['edit-post'];
                unset($_SESSION['edit-post']);
                ?>
            </p>
        </div>
    <?php elseif (isset($_SESSION['edit-post-success'])) : ?>

        <div class="alert__message success container">
            <p>
                <?= $_SESSION['edit-post-success'];
                unset($_SESSION['edit-post-success']);
                ?>
            </p>
        </div>
    <?php endif ?>
    <div class="conatiner dashboard__container">
        <aside>
            <ul>
                <li><a href="add-posts.php"><i class="uil uil-pen"></i>
                        <h5>Add Reviews</h5>
                    </a>
                </li>
                <li><a href="index.php" class="active"><i class="uil uil-postcard"></i>
                        <h5>Manage Reviews</h5>
                    </a>
                </li>
                <?php if (isset($_SESSION['user_is_admin'])) : ?>
                    <li><a href="add-user.php"><i class="uil uil-user-plus"></i>
                            <h5>Add Users</h5>
                        </a>
                    </li>
                    <li><a href="manage-users.php"><i class="uil uil-users-alt"></i>
                            <h5>Manage Users</h5>
                        </a>
                    </li>
                    <li><a href="add-category.php"><i class="uil uil-edit-alt"></i>
                            <h5>Add Genres</h5>
                        </a>
                    </li>
                    <li><a href="manage-categories.php"><i class="uil uil-list-ul"></i>
                            <h5>Manage genres</h5>
                        </a>
                    </li>
                <?php endif ?>
            </ul>
        </aside>
        <main>
            <h2>Manage Reviews</h2>
            <table>
                <?php if ((mysqli_num_rows($posts)) > 0) : ?>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Genre</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($post = mysqli_fetch_assoc($posts)) : ?>

                            <?php
                            $category_id = $post['category_id'];
                            $category_query = "SELECT title FROM categories WHERE id=$category_id";
                            $category_result = mysqli_query($connection, $category_query);
                            $category = mysqli_fetch_assoc($category_result);

                            ?>
                            <tr>
                                <td><?= $post['title'] ?></td>
                                <td><?= $category['title'] ?></td>
                                <td><a href="<?= ROOT_URL ?>admin/edit-post.php?id=<?= $post['id'] ?>" class="btn sm">Edit</a></td>
                                <td><a href="<?= ROOT_URL ?>admin/delete-post.php?id=<?= $post['id'] ?>" class="btn sm danger">Delete</a></td>
                            </tr>
                        <?php endwhile ?>

                    </tbody>
                <?php else : ?>
                    <div class="alert alert__message error"><?= "No posts found" ?></div>
                <?php endif ?>
            </table>
        </main>
    </div>
</section>
<?php
require '../partials/footer.php'
?>