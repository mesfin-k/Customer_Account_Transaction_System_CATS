<?php 
include 'header.php';
include 'setup.php'; // Ensure this file sets up the SQLite3 database connection
$database = new SQLite3('database.db');
?>

<div id="main-content" class="home">
    <h1>Home</h1>

    <h2>Search Customers</h2>
    <form action="search.php" method="GET">
        <input type="text" name="searchQuery" placeholder="Search by name or ID">
        <input type="submit" value="Search">
    </form>

    <h2>List of customers who have accounts with a balance greater than $25.</h2>
    
    <?php
    $query = "SELECT Customers.CustomerID, Accounts.Balance, Customers.FirstName, Customers.LastName FROM Customers JOIN Accounts ON Customers.CustomerID = Accounts.CustomerID WHERE Accounts.Balance > 25";
    $result = $database->query($query);

    if ($result) {
        echo "<table cellpadding='7px'>";
        echo "<thead>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Balance</th>
              </thead>
              <tbody>";

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo "<tr>
                    <td>{$row['CustomerID']}</td>
                    <td>{$row['FirstName']}</td>
                    <td>{$row['LastName']}</td>
                    <td>{$row['Balance']}</td>
                  </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "No records found.";
    }
    ?>

    <h2>Customer(s) with the highest account balance</h2>

    <?php
    $query = "SELECT Customers.CustomerID,Accounts.Balance, Customers.FirstName, Customers.LastName
 FROM Customers
JOIN Accounts ON Customers.CustomerID = Accounts.CustomerID
WHERE Accounts.Balance = (SELECT MAX(Balance) FROM Accounts)";


    $result = $database->query($query);

    if ($result) {
        echo "<table cellpadding='7px'>";
        echo "<thead>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Balance</th>
            </thead>
            <tbody>";

        // Loop through the result set
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo "<tr>
            <td>{$row['CustomerID']}</td>
            <td>{$row['FirstName']}</td>
            <td>{$row['LastName']}</td>
            <td>{$row['Balance']}</td>
                </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "No records found.";
    }


    // Query to find the three customers with the highest average transaction amounts
    $query = "SELECT Customers.FirstName, Customers.LastName, AVG(TransactionAmounts.AvgAmount) AS HighestAvgTransaction
FROM Customers
JOIN (
    SELECT Accounts.CustomerID, AVG(Transactions.Amount) AS AvgAmount
    FROM Accounts
    JOIN Transactions ON Accounts.AccountID = Transactions.AccountID
    GROUP BY Accounts.CustomerID
    ORDER BY AvgAmount DESC
    LIMIT 3
) AS TransactionAmounts ON Customers.CustomerID = TransactionAmounts.CustomerID";

    // Execute the query
    $result = $database->query($query);

    // Check if there are records
    if ($result) {
        echo "<h2>Customers with the highest average transaction amounts</h2>";
        echo "<table cellpadding='7px'>";
        echo "<thead>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Highest Avg Transaction</th>
        </thead>
        <tbody>";

        // Loop through the result set
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo "<tr>
        <td>{$row['FirstName']}</td>
        <td>{$row['LastName']}</td>
        <td>{$row['HighestAvgTransaction']}</td>
            </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "No records found.";
    }

    // Query to find the three customers with the highest average transaction amounts
    $query = "WITH TransactionAmounts AS (
    SELECT Accounts.CustomerID, AVG(Transactions.Amount) AS AvgAmount
    FROM Accounts
    JOIN Transactions ON Accounts.AccountID = Transactions.AccountID
    GROUP BY Accounts.CustomerID
    ORDER BY AvgAmount DESC
    LIMIT 3
)
SELECT Customers.FirstName, Customers.LastName, TransactionAmounts.AvgAmount AS HighestAvgTransaction
FROM Customers
JOIN TransactionAmounts ON Customers.CustomerID = TransactionAmounts.CustomerID";

    // Execute the query
    $result = $database->query($query);

    // Check if there are records
    if ($result) {
        echo "<h2>Three Customers with the highest average transaction amounts</h2>";
        echo "<table cellpadding='7px'>";
        echo "<thead>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Highest Avg Transaction</th>
    </thead>
    <tbody>";

        // Loop through the result set
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo "<tr>
    <td>{$row['FirstName']}</td>
    <td>{$row['LastName']}</td>
    <td>{$row['HighestAvgTransaction']}</td>
        </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "No records found.";
    }


    // Query to retrieve the names of customers who have made transactions in all branches of the bank
$query = "
SELECT DISTINCT Customers.FirstName, Customers.LastName
FROM Customers
WHERE (
    SELECT COUNT(DISTINCT Branches.BranchID)
    FROM Branches
) = (
    SELECT COUNT(DISTINCT Transactions.BranchID)
    FROM Transactions
    WHERE Customers.CustomerID = Transactions.AccountID
);
";

// Execute the query
$result = $database->query($query);

// Check if there are records
if ($result) {
echo "<h2>Customers who have made transactions in all branches</h2>";
echo "<table cellpadding='7px'>";
echo "<thead>
    <th>First Name</th>
    <th>Last Name</th>
</thead>
<tbody>";

// Loop through the result set
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    echo "<tr>
        <td>{$row['FirstName']}</td>
        <td>{$row['LastName']}</td>
    </tr>";
}

echo "</tbody></table>";
} else {
echo "No records found.";
}


// Query to retrieve the top 5 customers with the highest total transaction amounts
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
echo "<table cellpadding='7px'>";
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
</body>

</html>