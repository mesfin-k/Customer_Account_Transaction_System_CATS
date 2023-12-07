<?php
include 'header.php';
include 'setup.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gather form data
    $name = $_POST['name'];
    $address_ID = $_POST['address_ID'];
    $phone_number = $_POST['phone_number'];
    
    $db = new SQLite3('database.db');

    // Execute INSERT query
    $query = "INSERT INTO Branches (Name, AddressID, PhoneNumber) 
                  VALUES ('$name', '$address_ID', '$phone_number')";
    $result = $db->exec($query);

    if ($result) {
        echo "Branche added successfully!";
    } else {
        echo "Error adding Branches: " . $db->lastErrorMsg();
    }

    // Close the database connection
    $db->close();
}
?>
<div id="main-content">
    <h2>Add New Branches</h2>
    <form class="post-form" action="#" method="post">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-group">
            <label>Address ID </label>
            <input type="number" value="1" name="address_ID" required>
        </div>
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone_number" required>
        </div>
        <input class="submit" type="submit" value="Add" />
    </form>
</div>
</div>
</body>

</html>