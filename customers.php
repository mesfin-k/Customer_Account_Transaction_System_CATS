<?php
include 'header.php';
?>

<div id="main-content">
    <h2> MANAGE CUSTOMERS</h2>
    <a href="add-customer.php" class="btn"> Add new Customer</a>

    <?php
    // include 'setup.php';

    // Connect to SQLite database
    $database = new SQLite3('database.db');

    // Query to get all customer records
    $query = "SELECT * FROM Customers";
    $result = $database->query($query);

    // Check if there are any records
    if ($result) {
        echo "<table cellpadding='7px'>";
        echo "<thead>
                <th>S.no</th>
                <th>First Name</th>
                <th>Email</th>
                <th>SSN</th>
                <th>Phone Number</th>
                <th>Action</th>
            </thead>
            <tbody>";

        // Loop through the result set
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo "<tr>
                    <td>{$row['CustomerID']}</td>
                    <td>{$row['FirstName']} {$row['LastName']}</td>
                    <td>{$row['Email']}</td>
                    <td>{$row['SSN']}</td>
                    <td>{$row['PhoneNumber']}</td>
                    <td>
                        <a href='edit-customer.php?id={$row['CustomerID']}'>Edit</a>
                        <a href='delete-customer.php?id={$row['CustomerID']}'  onclick='confirmDelete(event)'>Delete</a>

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