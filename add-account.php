<?php
include 'header.php';
include 'setup.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gather form data
    $customer_id = $_POST['customer_id'];
    $account_number = $_POST['account_number'];
    $balance = $_POST['balance'];
    $date_opened = $_POST['date_opened'];
    $status = $_POST['status'];
    $account_type_id = 1;

    $db = new SQLite3('database.db');

    // Execute INSERT query
    $query = "INSERT INTO Accounts (CustomerID, AccountNumber,AccountTypeID , Balance, DateOpened, Status) 
              VALUES ('$customer_id', '$account_number', '$balance','$account_type_id', '$date_opened', '$status')";
    $result = $db->exec($query);

    if ($result) {
        echo "Account Created Successfully!";
    } else {
        echo "Error creatig account: " . $db->lastErrorMsg();
    }

    // Close the database connection
    $db->close();
}
?>

<div id="main-content">
    <h2>Add New Customers</h2>
    <form class="post-form" action="#" method="post">
        <div class="form-group">
            <label>Customer ID</label>
            <input type="number" name="customer_id" required>
        </div>
        <div class="form-group">
            <label>Account Number</label>
            <input type="text" name="account_number" required>
        </div>
        <div class="form-group">
            <label>Balance</label>
            <input type="text" name="balance" required>
        </div>
        <div class="form-group">
            <label>Date Opened</label>
            <input type="date" name="date_opened" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <input type="text" name="status" required>
        </div>
        <input class="submit" type="submit" value="Add" />
    </form>
</div>
</div>
</body>

</html>