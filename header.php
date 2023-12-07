<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>MESFIN_CATS</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">

  <style>
    /* Navigation styles */
    nav ul {
        list-style-type: none;
        display: flex;
        justify-content: center; /* Horizontal alignment */
    }

    nav ul li {
        margin: 0 10px;
    }
</style>


</head>
<body>
    <div id="wrapper">
        <div id="header">
            <h1>Customer Account and Transaction Service</h1>
        </div>
        <div id="menu">
            <ul>
                <li class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
                    <a href="index.php">Home</a>
                </li>
                <li class="<?= basename($_SERVER['PHP_SELF']) == 'customers.php' ? 'active' : '' ?>">
                    <a href="customers.php">Customers</a>
                </li>
                <li class="<?= basename($_SERVER['PHP_SELF']) == 'employees.php' ? 'active' : '' ?>">
                    <a href="employees.php">Employees</a>
                </li>
                <li class="<?= basename($_SERVER['PHP_SELF']) == 'accounts.php' ? 'active' : '' ?>">
                    <a href="accounts.php">Accounts</a>
                </li>
                <li class="<?= basename($_SERVER['PHP_SELF']) == 'branches.php' ? 'active' : '' ?>">
                    <a href="branches.php">Branches</a>
                </li>
                <li class="<?= basename($_SERVER['PHP_SELF']) == 'transactions.php' ? 'active' : '' ?>">
                    <a href="transactions.php">Transactions</a>
                </li>
            </ul>
        </div>
        <!-- Start of page content -->
