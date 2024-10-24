<?php
// add_book.php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $published_year = $_POST['published_year'];
    $available = isset($_POST['available']) ? 1 : 0;

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO books (title, author, isbn, published_year, available) VALUES (?, ?, ?, ?, ?)");

    if ($stmt) {
        // Bind parameters (s = string, i = integer)
        $stmt->bind_param("sssii", $title, $author, $isbn, $published_year, $available);

        // Execute statement and check for success
        if ($stmt->execute()) {
            header("Location: home.php");
            exit();
        } else {
            $error = "Failed to add book. " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        $error = "Failed to prepare the SQL statement. " . $conn->error;
    }
}

// Close the connection
$conn->close();
?>

<?php include 'templatess/header.php'; ?>

<h2>Add New Book</h2>
<?php if (isset($error)): ?>
    <p style="color:red;"><?= $error ?></p>
<?php endif; ?>

<!-- Form to add new book -->
<form method="POST" action="add_book.php">
    <label>Title:</label><br>
    <input type="text" name="title" required><br><br>

    <label>Author:</label><br>
    <input type="text" name="author" required><br><br>

    <label>ISBN:</label><br>
    <input type="text" name="isbn" required><br><br>

    <label>Published Year:</label><br>
    <input type="number" name="published_year" min="1000" max="<?= date("Y") ?>" required><br><br>

    <label>Available:</label>
    <input type="checkbox" name="available" checked><br><br>

    <button type="submit">Add Book</button>
</form>

<a href="home.php">Back to List</a>

<?php include 'templatess/footer.php'; ?>
