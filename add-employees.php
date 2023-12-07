<?php 
include 'header.php'; 
include 'setup.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gather form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $position = $_POST['position'];
    $branch_ID  = $_POST['branch_ID'];
    $date_hired = $_POST['date_hired'];
    $termination_date = $_POST['termination_date'];
    $role_ID = 1;


    $db = new SQLite3('database.db');

    // Get AddressID based on the selected address (assuming Addresses table has AddressID and Address columns)
    // $addressQuery = "SELECT AddressID FROM Addresses WHERE Address = '$address'";
    // $addressResult = $db->querySingle($addressQuery);
    // $addressId = $addressResult;

    // Execute INSERT query
    $query = "INSERT INTO Employees (FirstName, LastName, Position, BranchID, DateHired, TerminationDate, RoleID) 
                  VALUES ('$first_name', '$last_name', '$position', '$branch_ID', '$date_hired', '$termination_date', '$role_ID')";
    $result = $db->exec($query);

    if ($result) {
        echo "Employe added successfully!";
    } else {
        echo "Error adding Employe: " . $db->lastErrorMsg();
    }

    // Close the database connection
    $db->close();
}
?>
<div id="main-content">
    <h2>Add New Employees</h2>
    <form class="post-form" action="#" method="post">
        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" />
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" />
        </div>
        <div class="form-group">
            <label>Position</label>
            <input type="text" name="position" />
        </div>

        <div class="form-group">
            <label>BranchID</label>
            <input type="number" name="branch_ID" />
        </div> 

        <div class="form-group">
            <label>DateHired</label>
            <input type="date" name="date_hired" />
        </div>

        <div class="form-group">
            <label>TerminationDate </label>
            <input type="date" name="termination_date" />
        </div>

        <!-- <div class="form-group">
            <label>RoleID</label>
            <input type="text" name="role_ID" />
        </div> -->

         
        <input class="submit" type="submit" value="Add" />
    </form>
</div>
</div>
</body>

</html>