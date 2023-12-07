<?php
include 'setup.php';
include 'header.php';
?>

<div id="main-content">
    <h2> TRANSACTIONS</h2>
    <a href="add-transactions.php" class="btn"> Add Transactions </a>
    <?php
    // include 'setup.php';

    // Connect to SQLite database
    $database = new SQLite3('database.db');

    // Query to get all customer records
    $query = "SELECT * FROM Transactions";
    $result = $database->query($query);

    // Check if there are any records
    if ($result) {
        echo "<table cellpadding='7px'>";
        echo "<thead>
        <thead>
            <th>ID</th>
            <th>Account ID</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Timestamp </th>
            <th>Description</th>
        </thead>
        <tbody>";
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo "<tr>
                    <td>{$row['TransactionID']}</td>
                    <td>{$row['AccountID']}</td>
                    <td>{$row['Type']}</td>
                    <td>{$row['Amount']}</td>
                    <td>{$row['Timestamp']}</td>
                    <td>{$row['Description']}</td>

                </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "No records found.";
    }

    // Close the database connection
    $database->close();
    ?>
</div>
</div>

</body>

</html>