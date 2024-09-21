<?php
// Include the book class
require_once 'book.php';

// Start the session so that the book list is stored for display
session_start();

// Initialize or retrieve the book list from the session
if (!isset($_SESSION['bookList'])) {
    $_SESSION['bookList'] = [];
}

// Function to display booklist
function displayBooks($books) {
    if (empty($books)) {
        echo "<p>No books have been added yet.</p>";
        return;
    }

    echo '<table>';
    echo '<tr><th>Title</th><th>Author</th><th>Year</th></tr>';
    foreach ($books as $book) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($book->getTitle()) . '</td>';
        echo '<td>' . htmlspecialchars($book->getAuthor()) . '</td>';
        echo '<td>' . htmlspecialchars($book->getYear()) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
}

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['clear'])) {
        // Clear the session so that all books are removed
        session_unset();
        session_destroy();
        $_SESSION['bookList'] = []; // Reinitialize the book list
        $message = "All books cleared!";
    } else {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $year = $_POST['year'];

        // Check if all fields are non-empty
        if (!empty($title) && !empty($author) && !empty($year)) {
            try {
                // new book object
                $book = new Book($title, $author, $year);
                $_SESSION['bookList'][] = $book;
                $message = "Book added successfully!";
            } catch (Exception $e) {
                $message = "Error adding book: " . $e->getMessage();
            }
        } else {
            $message = "Empty fields! Please fill all fields.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        form {
            background: #fff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"][name="clear"] {
            background-color: #d9534f;
        }
        input[type="submit"]:not([name="clear"]) {
            background-color: #5cb85c;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
        }
        .success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .error {
            background-color: #f2dede;
            color: #a94442;
        }
    </style>
</head>
<body>
    <h2>Book Management System</h2>

    <!-- Display messages -->
    <?php if (isset($message)): ?>
        <div class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <!-- Book form -->
    <form method="POST">
        Title: <input type="text" name="title"><br>
        Author: <input type="text" name="author"><br>
        Year: <input type="number" name="year"><br>
        <input type="submit" value="Add Book">
    </form>

    <!-- Clear books button -->
    <form method="POST">
        <input type="submit" name="clear" value="Clear All Books">
    </form>

    <h2>Book List</h2>
    <?php displayBooks($_SESSION['bookList']); ?>
</body>
</html>
