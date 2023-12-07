<?php
include 'header.php';
include 'setup.php';

$database = new SQLite3('database.db');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gather form data
    $id = $_POST['employee_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $position = $_POST['position'];
    $branch_ID = $_POST['branch_ID'];
    $date_hired = $_POST['date_hired'];
    $termination_date = $_POST['termination_date'];
    $role_ID = $_POST['role_ID'];

    $db = new SQLite3('database.db');

    if (empty($first_name) || empty($last_name) || empty($position) || empty($branch_ID) || empty($date_hired) || empty($termination_date) || empty($role_ID)){
        echo "All fields are required.";
    } else {
        // Execute UPDATE query
        $updateQuery = "UPDATE Employees SET 
                        FirstName = '$first_name', 
                        LastName = '$last_name', 
                        Position  = '$position', 
                        BranchID  = '$branch_ID', 
                        DateHired  = '$date_hired',
                        TerminationDate  = '$termination_date', 
                        RoleID   = '$role_ID'
                        WHERE EmployeeID = $id";

        $result = $database->exec($updateQuery);

        if ($result) {
            echo "Employee updated successfully!";
        } else {
            echo "Error updating Employees: " . $database->lastErrorMsg();
        }
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM Employees WHERE EmployeeID = $id";
    $result = $database->querySingle($query, true);
}
?>


<div id="main-content">
    <h2>Edit Employees Record</h2>
    <form class="post-form" action="#" method="post">
    <input type="hidden" name="employee_id" value="<?php echo $result['EmployeeID']; ?>">

        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" value="<?php echo $result['FirstName']; ?>"/>
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" value="<?php echo $result['LastName']; ?>"/>
        </div>
        <div class="form-group">
            <label>Position</label>
            <input type="text" name="position" value="<?php echo $result['Position']; ?>"/>
        </div>

        <div class="form-group">
            <label>BranchID</label>
            <input type="number" name="branch_ID" value="<?php echo $result['BranchID']; ?>"/>
        </div> 

        <div class="form-group">
            <label>DateHired</label>
            <input type="text" name="date_hired" value="<?php echo $result['DateHired']; ?>"/>
        </div>

        <div class="form-group">
            <label>TerminationDate </label>
            <input type="text" name="termination_date" value="<?php echo $result['TerminationDate']; ?>"/>
        </div>

        <div class="form-group">
            <label>RoleID</label>
            <input type="text" name="role_ID" value="<?php echo $result['RoleID']; ?>"/>
        </div>

         
        <input class="submit" type="submit" value="Update" />
    </form>
</div>
</div>
</body>

</html>