<?php
include 'header.php';
include 'setup.php';

$database = new SQLite3('database.db');
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // SQL DELETE statement
    $query = "DELETE FROM Employees WHERE EmployeeID = :id";

    // Prepare the statement
    $stmt = $database->prepare($query);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

    // Execute the query
    $result = $stmt->execute();

    if ($result) {
        // Deletion successful
        header("Location: employees.php?message=success");
        exit; // Important: terminate the script after redirection
    } else {
        // Deletion failed
        header("Location: employees.php?message=error");
        exit; // Important: terminate the script after redirection
    }
}
