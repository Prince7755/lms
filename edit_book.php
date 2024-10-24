<?php
// edit_book.php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'config.php';

// Check if the book ID is provided
if (!isset($_GET['id'])) {
    header("Location: home.php");
    exit();
}

$id = $_GET['id'];

// Fetch book details
$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->bind_param("i", $id); // Bind the ID as an integer
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();
$stmt->close();

if (!$book) {
    echo "Book not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $published_year = $_POST['published_year'];
    $available = isset($_POST['available']) ? 1 : 0;

    // Prepare the SQL update query
    $stmt = $conn->prepare("UPDATE books SET title = ?, author = ?, isbn = ?, published_year = ?, available = ? WHERE id = ?");
    $stmt->bind_param("sssiii", $title, $author, $isbn, $published_year, $available, $id);

    if ($stmt->execute()) {
        header("Location: home.php");
        exit();
    } else {
        $error = "Failed to update book: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

<?php include 'templatess/header.php'; ?>

<h2>Edit Book</h2>
<?php if (isset($error)): ?>
    <p style="color:red;"><?= $error ?></p>
<?php endif; ?>

<!-- Edit Book Form -->
<form method="POST" action="edit_book.php?id=<?= $id ?>">
    <label>Title:</label><br>
    <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required><br><br>

    <label>Author:</label><br>
    <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required><br><br>

    <label>ISBN:</label><br>
    <input type="text" name="isbn" value="<?= htmlspecialchars($book['isbn']) ?>" required><br><br>

    <label>Published Year:</label><br>
    <input type="number" name="published_year" min="1000" max="<?= date("Y") ?>" value="<?= htmlspecialchars($book['published_year']) ?>"><br><br>

    <label>Available:</label>
    <input type="checkbox" name="available" <?= $book['available'] ? 'checked' : '' ?>><br><br>

    <button type="submit">Update Book</button>
</form>

<a href="home.php">Back to List</a>

<?php include 'templatess/footer.php'; ?>

