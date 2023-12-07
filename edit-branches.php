<?php
include 'header.php';
include 'setup.php';

$database = new SQLite3('database.db');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gather form data
    $id = $_POST['branch_ID'];
    $name = $_POST['name'];
    $address_ID = $_POST['address_ID'];
    $phone_number = $_POST['phone_number'];
    
    $db = new SQLite3('database.db');

    if (empty($name) || empty($address_ID) || empty($phone_number)){
        echo "All fields are required.";
    } else {
        // Execute UPDATE query
        $updateQuery = "UPDATE Branches SET 
                        Name = '$name', 
                        AddressID = '$address_ID', 
                        PhoneNumber  = '$phone_number'
                        WHERE BranchID = $id";
        $result = $database->exec($updateQuery);

        if ($result) {
            echo "Branches updated successfully!";
        } else {
            echo "Error updating Branches: " . $database->lastErrorMsg();
        }
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM Branches WHERE BranchID = $id";
    $result = $database->querySingle($query, true);
}
?>
<div id="main-content">
    <h2>Add New Branches</h2>
    <form class="post-form" action="#" method="post">
            <input type="hidden" name="branch_ID" value="<?php echo $result['BranchID']; ?>" required>
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="<?php echo $result['Name']; ?>" required>
        </div>
        <div class="form-group">
            <label>Address ID </label>
            <input type="text" name="address_ID" value="<?php echo $result['AddressID']; ?>" required>
        </div>
       
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone_number" value="<?php echo $result['PhoneNumber']; ?>" required>
        </div>
        <input class="submit" type="submit" value="Update" />
    </form>
</div>
</div>
</body>

</html>