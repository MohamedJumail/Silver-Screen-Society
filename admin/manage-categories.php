<?php
include "partials/header.php";
if (!isset($_SESSION['user_is_admin'])) {
    header("location: " . ROOT_URL . "logout.php");
    session_destroy();
}
$query = "SELECT * FROM categories ORDER BY title";
$categories = mysqli_query($connection, $query)

?>
<section class="dashboard">
    <?php if (isset($_SESSION['add-category-success'])) : ?>
        <div class="alert__message success container">
            <p>
                <?= $_SESSION['add-category-success'];
                unset($_SESSION['add-category-success']);
                ?>
            </p>
        </div>
    <?php
    elseif (isset($_SESSION['add-category'])) : ?>
        <div class="alert__message error container">
            <p>
                <?= $_SESSION['add-category'];
                unset($_SESSION['add-category']);
                ?>
            </p>

        </div>
    <?php endif ?>
    <?php if (isset($_SESSION['edit-category-success'])) : ?>
        <div class="alert__message success container">
            <p>
                <?= $_SESSION['edit-category-success'];
                unset($_SESSION['edit-category-success']);
                ?>
            </p>

        </div>
    <?php
    elseif (isset($_SESSION['edit-category'])) : ?>
        <div class="alert__message error container">
            <p>
                <?= $_SESSION['edit-category'];
                unset($_SESSION['edit-category']);
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
                <li><a href="index.php"><i class="uil uil-postcard"></i>
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
                    <li><a href="manage-categories.php" class="active"><i class="uil uil-list-ul"></i>
                            <h5>Manage genres</h5>
                        </a>
                    </li>
                <?php endif ?>
            </ul>
        </aside>
        <main>
            <h2>Manage Genres</h2>
            <?php if (mysqli_num_rows($categories) > 0) : ?>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($category = mysqli_fetch_assoc($categories)) : ?>
                            <tr>
                                <td><?= $category['title'] ?></td>
                                <td><a href="<?= ROOT_URL ?>admin/edit-category.php?id=<?= $category['id'] ?>" class="btn sm">Edit</a></td>
                                <td><a href="<?= ROOT_URL ?>admin/delete-category.php?id=<?= $category['id'] ?>" class="btn sm danger">Delete</a></td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                <?php else : ?>
                    <div class="alert__message error">
                        No categories found
                    </div>
                <?php endif ?>
                </table>
        </main>
    </div>
</section>
<?php
require '../partials/footer.php'
?>