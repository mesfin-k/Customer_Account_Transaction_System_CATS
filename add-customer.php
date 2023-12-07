<?php
include 'header.php';
include 'setup.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gather form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $ssn = $_POST['ssn'];
    // $address = $_POST['s_class'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];

    $db = new SQLite3('database.db');

    // Get AddressID based on the selected address (assuming Addresses table has AddressID and Address columns)
    // $addressQuery = "SELECT AddressID FROM Addresses WHERE Address = '$address'";
    // $addressResult = $db->querySingle($addressQuery);
    // $addressId = $addressResult;

    // Execute INSERT query
    $query = "INSERT INTO Customers (FirstName, LastName, SSN, PhoneNumber, Email) 
                  VALUES ('$first_name', '$last_name', '$ssn', '$phone_number', '$email')";
    $result = $db->exec($query);

    if ($result) {
        echo "Customer added successfully!";
    } else {
        echo "Error adding customer: " . $db->lastErrorMsg();
    }

    // Close the database connection
    $db->close();
}
?>
<div id="main-content">
    <h2>Add New Customers</h2>
    <form class="post-form" action="#" method="post">
        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" required>
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" required>
        </div>
        <div class="form-group">
            <label>SSN</label>
            <input type="text" name="ssn" required>
        </div>
        <!-- <div class="form-group">
                <label>Address</label>
                <select name="s_class">
                    <option value="" selected disabled>Select Address</option>
                    <option value="2" selected >Abc</option>
                </select>
            </div> -->
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone_number" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <input class="submit" type="submit" value="Add" />
    </form>
</div>
</div>
</body>

</html>