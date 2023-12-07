<?php
include 'header.php';
?>

<div id="main-content">
    <h2>MANAGE BRANCHES</h2>
    <a href="add-branches.php" class="btn"> Add Braches</a>
    <?php
    // include 'setup.php';

    // Connect to SQLite database
    $database = new SQLite3('database.db');

    // Query to get all customer records
    $query = "SELECT * FROM Branches";
    $result = $database->query($query);

    // Check if there are any records
    if ($result) {
        echo "<table cellpadding='7px'>";
        echo "<thead>
        <thead>
            <th>Id</th>
            <th>Name</th>
            <th>Address ID </th>
            <th>PhoneNumber</th>
            <th>Action</th>
        </thead>
        <tbody>";
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo "<tr>
                    <td>{$row['BranchID']}</td>
                    <td>{$row['Name']}</td>
                    <td>{$row['AddressID']}</td>
                    <td>{$row['PhoneNumber']}</td>
                    <td>
                        <a href='edit-branches.php?id={$row['BranchID']}'>Edit</a>
                        <a href='delete-branches.php?id={$row['BranchID']}'  onclick='confirmDelete(event)'>Delete</a>

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