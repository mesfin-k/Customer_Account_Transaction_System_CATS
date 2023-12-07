<?php
include 'header.php';
include 'setup.php';

// Connect to SQLite database
$db = new SQLite3('database.db');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gather form data
    $customer_id = $_POST['customer_id'];
    $account_number = $_POST['account_number'];
    $balance = $_POST['balance'];
    $date_opened = $_POST['date_opened'];
    $status = $_POST['status'];
    $account_id = $_POST['account_id'];

    $account_type_id = 1; // Assuming a default account type ID

    // Validate form data (you may want to add more validation)
    if (empty($customer_id) || empty($account_number) || empty($balance) || empty($date_opened) || empty($status)) {
        echo "All fields are required.";
    } else {
        $updateQuery = "UPDATE Accounts SET 
                            Balance = '$balance', 
                            DateOpened = '$date_opened', 
                            Status = '$status' 
                            WHERE AccountID = $account_id";

        $result = $db->exec($updateQuery);

        if ($result) {
            echo "Account Updated Successfully!";
        } else {
            echo "Error updating account: " . $db->lastErrorMsg();
        }
    }
}

// Load account details for editing
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM Accounts WHERE AccountID = $id";
    $result = $db->querySingle($query, true);
}

// Close the database connection
$db->close();
?>

<div id="main-content">
    <h2>Add/Update Account</h2>
    <form class="post-form" action="#" method="post">
        <div class="form-group">
            <label>Customer ID</label>
            <input type="number" name="customer_id" value="<?php echo isset($result['CustomerID']) ? $result['CustomerID'] : ''; ?>" required>
            <input type="hidden" name="account_id" value="<?php echo isset($result['AccountID']) ? $result['AccountID'] : ''; ?>">
        </div>
        <div class="form-group">
            <label>Account Number</label>
            <input type="text" name="account_number" value="<?php echo isset($result['AccountNumber']) ? $result['AccountNumber'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label>Balance</label>
            <input type="text" name="balance" value="<?php echo isset($result['Balance']) ? $result['Balance'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label>Date Opened</label>
            <input type="date" name="date_opened" value="<?php echo isset($result['DateOpened']) ? $result['DateOpened'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <input type="text" name="status" value="<?php echo isset($result['Status']) ? $result['Status'] : ''; ?>" required>
        </div>
        <input class="submit" type="submit" value="Update" />
    </form>
</div>
</div>
</body>

</html>