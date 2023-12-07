<?php
include 'setup.php';
include 'header.php';
?>

<div id="main-content">
    <h2>MANAGE EMPLOYEES</h2>
    <a href="add-employees.php" class="btn"> Add new Employees</a>
    <?php
    // include 'setup.php';

    // Connect to SQLite database
    $database = new SQLite3('database.db');

    // Query to get all customer records
    $query = "SELECT * FROM Employees";
    $result = $database->query($query);

    // Check if there are any records
    if ($result) {
        echo "<table cellpadding='7px'>";
        echo "<thead>
        <thead>
            <th>Id</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Position</th>
            <th>BranchID</th>
            <th>Date Hired </th>
            <th>Termination Date</th>
            <th>RoleID</th>
            <th>Action</th>
        </thead>
        <tbody>";
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo "<tr>
                    <td>{$row['EmployeeID']}</td>
                    <td>{$row['FirstName']} {$row['LastName']}</td>
                    <td>{$row['LastName']}</td>
                    <td>{$row['Position']}</td>
                    <td>{$row['BranchID']}</td>
                    <td>{$row['DateHired']}</td>
                    <td>{$row['TerminationDate']}</td>
                    <td>{$row['RoleID']}</td>
                    <td>
                        <a href='edit-employe.php?id={$row['EmployeeID']}'>Edit</a>
                        <a href='delete-employe.php?id={$row['EmployeeID']}'  onclick='confirmDelete(event)'>Delete</a>
                    
                        </td>
                </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "No records found.";
    }
    if (isset($_GET['message'])) {
        $message = $_GET['message'];

        // Display different messages based on the 'message' value
        if ($message === 'success') {
            echo "Record deleted successfully";
        } elseif ($message === 'error') {
            echo "Error deleting record";
        }
    }

    // Close the database connection
    $database->close();
    ?>
</div>
</div>

</body>

</html>
<script>
    function confirmDelete(event) {
        if (confirm("Are you sure you want to delete this item?")) {
            window.location.href = event.target.href;
        } else {
            event.preventDefault(); // Prevents the default behavior of the anchor tag (deletion)
        }
    }
</script>