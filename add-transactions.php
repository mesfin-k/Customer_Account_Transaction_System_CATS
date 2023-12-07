<?php
include 'header.php';
include 'setup.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gather form data
    $account_ID = $_POST['account_ID'];
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $branch_id = $_POST['branch_id'];

    
    $db = new SQLite3('database.db');

    // Execute INSERT query
    $query = "INSERT INTO Transactions (AccountID,BranchID, Type, Amount, Description) 
                  VALUES ('$account_ID','$branch_id', '$type', '$amount', '$description')";
    $result = $db->exec($query);

    if ($result) {
        echo "Transactions added successfully!";
    } else {
        echo "Error adding Transactions: " . $db->lastErrorMsg();
    }

    // Close the database connection
    $db->close();
}
?>
<div id="main-content">
    <h2>Add New Branches</h2>
    <form class="post-form" action="#" method="post">
        <div class="form-group">
            <label>Account ID</label>
            <input type="text" name="account_ID" required>
        </div>
        <div class="form-group">
            <label>Type </label>
            <input type="text" name="type" required>
        </div>
        <div class="form-group">
            <label>Amount</label>
            <input type="text" name="amount" required>
        </div>

        <div class="form-group">
            <label>Branch ID </label>
            <input type="text" name="branch_id" required>
        </div>

        <div class="form-group">
            <label>Description </label>
            <input type="text" name="description" required>
        </div>

        <input class="submit" type="submit" value="Add" />
    </form>
</div>
</div>
</body>

</html>