<?php
include 'header.php';
include 'setup.php';

$database = new SQLite3('database.db');



// Handle form submission for updating record
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['customer_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $ssn = $_POST['ssn'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];

    // Validate form data (you may want to add more validation)
    if (empty($first_name) || empty($last_name) || empty($ssn) || empty($phone_number) || empty($email)) {
        echo "All fields are required.";
    } else {
        // Execute UPDATE query
        $updateQuery = "UPDATE Customers SET 
                        FirstName = '$first_name', 
                        LastName = '$last_name', 
                        SSN = '$ssn', 
                        PhoneNumber = '$phone_number', 
                        Email = '$email' 
                        WHERE CustomerID = $id";

        $result = $database->exec($updateQuery);

        if ($result) {
            echo "Customer updated successfully!";
        } else {
            echo "Error updating customer: " . $database->lastErrorMsg();
        }
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM Customers WHERE CustomerID = $id";
    $result = $database->querySingle($query, true);
}
?>

<div id="main-content">
    <h2>Edit Customer</h2>
    <form class="post-form" action="#" method="post">
        <input type="hidden" name="customer_id" value="<?php echo $result['CustomerID']; ?>">

        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" value="<?php echo $result['FirstName']; ?>" required>
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" value="<?php echo $result['LastName']; ?>" required>
        </div>
        <div class="form-group">
            <label>SSN</label>
            <input type="text" name="ssn" value="<?php echo $result['SSN']; ?>" required>
        </div>
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone_number" value="<?php echo $result['PhoneNumber']; ?>" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo $result['Email']; ?>" required>
        </div>
        <input class="submit" type="submit" value="Update" />
    </form>
</div>
</div>
</body>

</html>