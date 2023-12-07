<?php

// Connect to SQLite database
$database = new SQLite3('usersdb');

// Get user input
$username = $_POST['delete_username'];

// Delete user from the 'users' table
$query = "DELETE FROM users WHERE username = '$username'";
$result = $database->exec($query);

if ($result) {
    echo "User deleted successfully!";
} else {
    echo "Error deleting user: " . $database->lastErrorMsg();
}

// Close the database connection
$database->close();

?>
