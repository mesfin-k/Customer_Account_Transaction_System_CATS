<?php
include 'header.php';
include 'setup.php';
?>

<div id="main-content">
    <h2>MANAGE ACCOUNTS</h2>
    <a href="add-account.php" class="add-btn">Add new account</a>

    <?php
    // Connect to SQLite database
    $database = new SQLite3('database.db');

    // Query to get customer and account information by joining Customers and Accounts tables
    $query = "SELECT Customers.CustomerID, Customers.FirstName, Customers.LastName, Customers.Email, Customers.SSN, Customers.PhoneNumber, Accounts.AccountID, Accounts.AccountNumber, Accounts.Balance
              FROM Accounts 
              LEFT JOIN Customers ON Customers.CustomerID = Accounts.CustomerID";

    $result = $database->query($query);

    // Check if there are any records
    if ($result) {
        echo "<table>";
        echo "<thead>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>SSN</th>
                <th>Phone Number</th>
                <th>Account Number</th>
                <th>Balance</th>
                <th>Action</th>
            </thead>
            <tbody>";

        // Loop through the result set
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo "<tr>
                    <td>{$row['AccountID']}</td>
                    <td>{$row['FirstName']}</td>
                    <td>{$row['LastName']}</td>
                    <td>{$row['Email']}</td>
                    <td>{$row['SSN']}</td>
                    <td>{$row['PhoneNumber']}</td>
                    <td>{$row['AccountNumber']}</td>
                    <td>{$row['Balance']}</td>
                    <td>
                        <a href='edit-account.php?id={$row['AccountID']}' class='edit-btn'>Edit</a>
                        <a href='delete-account.php?id={$row['AccountID']}' class='delete-btn' onclick='confirmDelete(event)'>Delete</a>
                    </td>
                </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "No records found.";
    }
    

    $query = "
    SELECT C.FirstName, C.LastName, SUM(T.Amount) AS TotalTransactionAmount
    FROM Customers C
    JOIN Accounts A ON C.CustomerID = A.CustomerID
    JOIN Transactions T ON A.AccountID = T.AccountID
    GROUP BY C.CustomerID, C.FirstName, C.LastName
    ORDER BY TotalTransactionAmount DESC
    LIMIT 5;
    ";
    
    // Execute the query
    $result = $database->query($query);
    
    // Display the result in a table
    echo "<h2>Top 5 Customers with Highest Total Transaction Amounts</h2>";
    echo "<table>";
    echo "<thead>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Total Transaction Amount</th>
    </thead>
    <tbody>";
    
    // Loop through the result set and display each row in a table row
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        echo "<tr>
        <td>{$row['FirstName']}</td>
        <td>{$row['LastName']}</td>
        <td>{$row['TotalTransactionAmount']}</td>
    </tr>";
    }
    
    echo "</tbody></table>";
    ?>
    
    </div>
    <script>
        function confirmDelete(event) {
            if (!confirm("Are you sure you want to delete this item?")) {
                event.preventDefault();
            }
        }
    </script>
    </body>
    </html>