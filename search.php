<?php 
include 'header.php';
include 'setup.php'; // Ensure this file sets up the SQLite3 database connection
$database = new SQLite3('database.db');

$searchQuery = $_GET['searchQuery'] ?? ''; // Added a null coalescing operator for when the searchQuery is not set

$query = "SELECT Customers.CustomerID, Customers.FirstName, Customers.LastName, Accounts.AccountNumber, Accounts.Balance 
          FROM Customers 
          LEFT JOIN Accounts ON Customers.CustomerID = Accounts.CustomerID 
          WHERE Customers.FirstName LIKE :searchQuery OR Customers.LastName LIKE :searchQuery OR Customers.CustomerID LIKE :searchQuery";
$stmt = $database->prepare($query);
$stmt->bindValue(':searchQuery', '%'.$searchQuery.'%', SQLITE3_TEXT);
$result = $stmt->execute();

if ($result) {
    echo "<table cellpadding='7px'>";
    echo "<thead>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Account Number</th>
            <th>Balance</th>
          </thead>
          <tbody>";

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        echo "<tr>
                <td>{$row['CustomerID']}</td>
                <td>{$row['FirstName']}</td>
                <td>{$row['LastName']}</td>
                <td>{$row['AccountNumber']}</td>
                <td>{$row['Balance']}</td>
              </tr>";
    }

    echo "</tbody></table>";
} else {
    echo "No records found.";
}
?>
</div>
</body>
</html>
