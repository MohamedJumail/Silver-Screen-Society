<?php
include 'db.php';

$id = $_GET["id"];
$sql = "SELECT * FROM users WHERE id=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];

    $sql = "UPDATE users SET name='$name', email='$email' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Update User</title>
</head>

<body>
    <h2>Update User</h2>
    <form method="post" action="update.php?id=<?php echo $id; ?>">
        Name: <input type="text" name="name" value="<?php echo $row['name']; ?>" required><br>
        Email: <input type="email" name="email" value="<?php echo $row['email']; ?>" required><br>
        <input type="submit" value="Update">
    </form>
    <a href="index.php">Back to Home</a>
</body>

</html>