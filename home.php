<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'config.php'; // Include the database connection

// Fetch all books from the database
$result = $conn->query("SELECT * FROM books");

if ($result) {
    $books = $result->fetch_all(MYSQLI_ASSOC); // Fetch all results as an associative array
} else {
    die("Error fetching books: " . $conn->error); // Handle query failure
}
?>

<?php include 'templatess/header.php'; ?>

<h2>Library Books</h2>
<a href="add_book.php">Add New Book</a> | <a href="logout.php">Logout</a>

<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Author</th>
        <th>ISBN</th>
        <th>Published Year</th>
        <th>Available</th>
        <th>Actions</th>
    </tr>

    <?php if (!empty($books)): ?>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><?= htmlspecialchars($book['id']) ?></td>
                <td><?= htmlspecialchars($book['title']) ?></td>
                <td><?= htmlspecialchars($book['author']) ?></td>
                <td><?= htmlspecialchars($book['isbn']) ?></td>
                <td><?= htmlspecialchars($book['published_year']) ?></td>
                <td><?= $book['available'] ? 'Yes' : 'No' ?></td>
                <td>
                    <a href="edit_book.php?id=<?= $book['id'] ?>">Edit</a> | 
                    <a href="delete_books.php?id=<?= $book['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="7">No books found.</td></tr>
    <?php endif; ?>
</table>

<?php include 'templatess/footer.php'; ?>
