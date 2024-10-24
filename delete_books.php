<?php
// delete_book.php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Optionally, check if the book exists
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->bind_param("i", $id); // Bind the ID as an integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If book exists, proceed with deletion
        $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
        $stmt->bind_param("i", $id); // Bind the ID again

        if ($stmt->execute()) {
            header("Location: home.php");
            exit();
        } else {
            echo "Failed to delete book: " . $stmt->error;
        }
    } else {
        echo "Book not found.";
    }

    $stmt->close(); // Close the statement
} else {
    header("Location: home.php");
    exit();
}

$conn->close(); // Close the connection
?>
