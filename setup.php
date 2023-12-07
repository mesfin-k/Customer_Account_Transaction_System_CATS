<?php

require_once('functions.php');

// Connect to SQLite database
$database = new SQLite3('database.db');

// Function to check if a table exists
function tableExists($database, $tableName)
{
    $query = "SELECT name FROM sqlite_master WHERE type='table' AND name='$tableName'";
    $result = $database->query($query);
    $table = $result->fetchArray(SQLITE3_ASSOC);
    return ($table !== false);
}

// Create Addresses table if not exists
if (!tableExists($database, 'Addresses')) {
    $queryAddresses = "
        CREATE TABLE Addresses (
            AddressID INTEGER PRIMARY KEY,
            Street TEXT NOT NULL,
            City TEXT NOT NULL,
            State TEXT,
            Country TEXT NOT NULL,
            PostalCode TEXT
        )";
    $database->exec($queryAddresses);
}

if (!tableExists($database, 'Accounts')) {

    $queryAccounts = "
CREATE TABLE Accounts (
    AccountID INTEGER PRIMARY KEY,
    CustomerID INTEGER,
    AccountNumber TEXT UNIQUE NOT NULL,
    Balance REAL DEFAULT 0,
    AccountTypeID INTEGER NOT NULL,
    DateOpened TEXT,
    Status TEXT NOT NULL,
    FOREIGN KEY(CustomerID) REFERENCES Customers(CustomerID),
    FOREIGN KEY(AccountTypeID) REFERENCES AccountTypes(AccountTypeID)
)";
$database->exec($queryAccounts);

}

// Create Customers table if not exists
if (!tableExists($database, 'Customers')) {
    $queryCustomers = "
        CREATE TABLE Customers (
            CustomerID INTEGER PRIMARY KEY,
            FirstName TEXT NOT NULL,
            LastName TEXT NOT NULL,
            SSN TEXT UNIQUE NOT NULL,
            AddressID INTEGER,
            PhoneNumber TEXT,
            Email TEXT UNIQUE,
            FOREIGN KEY(AddressID) REFERENCES Addresses(AddressID)
        )";
    $database->exec($queryCustomers);
}

// Create Branches table if not exists
if (!tableExists($database, 'Branches')) {
    $queryBranches = "
        CREATE TABLE Branches (
            BranchID INTEGER PRIMARY KEY,
            Name TEXT NOT NULL,
            AddressID INTEGER NOT NULL,
            PhoneNumber TEXT,
            FOREIGN KEY(AddressID) REFERENCES Addresses(AddressID)
        )";
    $database->exec($queryBranches);
}




if (!tableExists($database, 'Transactions')) {
    $queryTransactions = "
    CREATE TABLE Transactions (
    TransactionID INTEGER PRIMARY KEY,
    AccountID INTEGER,
    Type TEXT NOT NULL,
    Amount REAL NOT NULL,
    Timestamp TEXT DEFAULT CURRENT_TIMESTAMP,
    Description TEXT,
    BranchID INTEGER,
    FOREIGN KEY(AccountID) REFERENCES Accounts(AccountID)
    )";
    $database->exec($queryTransactions);
}

if (!tableExists($database, 'Employees')) {
    $queryEmployees = "CREATE TABLE Employees (
        EmployeeID INTEGER PRIMARY KEY,
        FirstName TEXT NOT NULL,
        LastName TEXT NOT NULL,
        Position TEXT NOT NULL,
        BranchID INTEGER,
        DateHired TEXT NOT NULL,
        TerminationDate TEXT,
        RoleID INTEGER, Role TEXT,
        FOREIGN KEY(BranchID) REFERENCES Branches(BranchID),
        FOREIGN KEY(RoleID) REFERENCES EmployeeRoles(RoleID)
    )";
    $database->exec($queryEmployees);
}
// ... Repeat the same pattern for other tables ...

// Close the database connection
$database->close();

// echo "Tables created or already existed successfully!";
