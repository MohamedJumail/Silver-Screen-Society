<?php
include 'db.php';

$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>

<head>
    <title>List</title>
    <style>
        /* Style for anchor tags */
        a {
            color: blue;
            /* Set the color */
            text-decoration: none;
            /* Remove underline */
            margin-right: 10px;
            /* Add some spacing between links */
        }

        a:hover {
            text-decoration: underline;
            /* Underline on hover */
        }
    </style>
</head>

<body>
    <h2>List</h2>
    <a href="create.php">Create New User</a><br><br>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
                <tr>
                    <td><?php $row["id"]  ?></td>
                    <td><?php $row["name"]  ?></td>
                    <td><?php $row["email"]  ?></td>
                    <td>
                        <a href="update.php?id=<?php $row["id"] ?>">Edit</a> |
                        <a href="delete.php?id=<?php $row["id"] ?> ">Delete</a>
                    </td>
                </tr>
        <?php
            }
        } else {
            echo "<tr><td colspan='4'>No records found</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>

</html>