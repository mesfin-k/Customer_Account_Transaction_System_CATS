

-- 1. Retrieve a list of customers along with their account balances.

SELECT Customers.FirstName, Customers.LastName, Accounts.Balance
FROM Customers
JOIN Accounts ON Customers.CustomerID = Accounts.CustomerID;

-- 2.Find all transactions for a specific customer, including the transaction type and amount.

SELECT Transactions.Type, Transactions.Amount
FROM Transactions
JOIN Accounts ON Transactions.AccountID = Accounts.AccountID
JOIN Customers ON Accounts.CustomerID = Customers.CustomerID
WHERE Customers.FirstName = 'John' AND Customers.LastName = 'Doe';

-- 3.Retrieve a list of customers who have accounts with a balance greater than $10,000.

SELECT Customers.FirstName, Customers.LastName
FROM Customers
JOIN Accounts ON Customers.CustomerID = Accounts.CustomerID
WHERE Accounts.Balance > 10000.00;

-- 4. Update the email address of a customer and their associated accounts.

UPDATE Customers
SET Email = 'new.email@email.com'
WHERE FirstName = 'John' AND LastName = 'Doe';

UPDATE Accounts
SET Status = 'Inactive'
WHERE CustomerID = (SELECT CustomerID FROM Customers WHERE FirstName = 'John' AND LastName = 'Doe');

-- 5. Delete a specific transaction and update the account balance accordingly.

-- Retrieve the account and amount for the transaction to be deleted.
SELECT Transactions.AccountID, Transactions.Amount
FROM Transactions
WHERE TransactionID = 1;

-- 6. Update the account balance by subtracting the transaction amount.
UPDATE Accounts
SET Balance = Balance - (SELECT Amount FROM Transactions WHERE TransactionID = 1)
WHERE AccountID = (SELECT AccountID FROM Transactions WHERE TransactionID = 1);

-- Delete the transaction.
DELETE FROM Transactions
WHERE TransactionID = 1;

-- 7. Retrieve the total balance of all active accounts for customers whose last name starts with "S."

SELECT SUM(Accounts.Balance) AS TotalBalance
FROM Accounts
JOIN Customers ON Accounts.CustomerID = Customers.CustomerID
WHERE Customers.LastName LIKE 'S%' AND Accounts.Status = 'Active';

-- 8.Find the customer(s) with the highest account balance.

SELECT Customers.FirstName, Customers.LastName
FROM Customers
JOIN Accounts ON Customers.CustomerID = Accounts.CustomerID
WHERE Accounts.Balance = (SELECT MAX(Balance) FROM Accounts);

-- 9. List all customers who have at least one account with a balance greater than the average 
-- balance of all accounts.

SELECT DISTINCT Customers.FirstName, Customers.LastName
FROM Customers
JOIN Accounts ON Customers.CustomerID = Accounts.CustomerID
WHERE Accounts.Balance > (SELECT AVG(Balance) FROM Accounts);

-- 10. Retrieve a list of customers who have more than one account.

SELECT Customers.FirstName, Customers.LastName
FROM Customers
JOIN (
    SELECT CustomerID
    FROM Accounts
    GROUP BY CustomerID
    HAVING COUNT(*) > 1
) AS MultiAccountCustomers ON Customers.CustomerID = MultiAccountCustomers.CustomerID;


-- 11. Find the average transaction amount for each account.

SELECT Accounts.AccountNumber, AVG(Transactions.Amount) AS AvgTransactionAmount
FROM Accounts
LEFT JOIN Transactions ON Accounts.AccountID = Transactions.AccountID
GROUP BY Accounts.AccountNumber;

-- 12. Retrieve a list of customers who have never made a withdrawal.

SELECT Customers.FirstName, Customers.LastName
FROM Customers
WHERE Customers.CustomerID NOT IN (
    SELECT DISTINCT Customers.CustomerID
    FROM Customers
    JOIN Accounts ON Customers.CustomerID = Accounts.CustomerID
    JOIN Transactions ON Accounts.AccountID = Transactions.AccountID
    WHERE Transactions.Type = 'Withdrawal'
);

-- 13. Retrieve the customers who have at least one account opened before 2020 and at least one account opened after 2021.

SELECT Customers.FirstName, Customers.LastName
FROM Customers
WHERE Customers.CustomerID IN (
    SELECT CustomerID
    FROM Accounts
    WHERE (strftime('%Y', DateOpened) < '2020' OR strftime('%Y', DateOpened) > '2021')
    GROUP BY CustomerID
    HAVING COUNT(*) >= 2
);


-- 14. Find the most common transaction type among all accounts.

SELECT Transactions.Type, COUNT(*) AS TransactionCount
FROM Transactions
GROUP BY Transactions.Type
ORDER BY TransactionCount DESC
LIMIT 1;

-- 15. Retrieve the average balance of accounts for each branch.

SELECT Branches.Name, AVG(Accounts.Balance) AS AvgBalance
FROM Branches
LEFT JOIN Employees ON Branches.BranchID = Employees.BranchID
LEFT JOIN Accounts ON Employees.EmployeeID = Accounts.CustomerID
GROUP BY Branches.Name;

-- 16. Retrieve the names of customers who have made transactions in all branches of the bank.


SELECT DISTINCT Customers.FirstName, Customers.LastName
FROM Customers
WHERE (
    SELECT COUNT(DISTINCT Branches.BranchID)
    FROM Branches
    ) = (
    SELECT COUNT(DISTINCT Transactions.BranchID)
    FROM Transactions
    WHERE Customers.CustomerID = Transactions.AccountID
);

-- 17. Find the three customers with the highest average transaction amounts across all of their accounts.

SELECT Customers.FirstName, Customers.LastName, AVG(TransactionAmounts.AvgAmount) AS HighestAvgTransaction
FROM Customers
JOIN (
    SELECT Accounts.CustomerID, AVG(Transactions.Amount) AS AvgAmount
    FROM Accounts
    JOIN Transactions ON Accounts.AccountID = Transactions.AccountID
    GROUP BY Accounts.CustomerID
    ORDER BY AvgAmount DESC
    LIMIT 3
) AS TransactionAmounts ON Customers.CustomerID = TransactionAmounts.CustomerID;


-- 18. Find the three customers with the highest average transaction amounts across all of their accounts.

SELECT Customers.FirstName, Customers.LastName, AVG(TransactionAmounts.AvgAmount) AS HighestAvgTransaction
FROM Customers
JOIN (
    SELECT Accounts.CustomerID, AVG(Transactions.Amount) AS AvgAmount
    FROM Accounts
    JOIN Transactions ON Accounts.AccountID = Transactions.AccountID
    GROUP BY Accounts.CustomerID
    ORDER BY AvgAmount DESC
    LIMIT 3
) AS TransactionAmounts ON Customers.CustomerID = TransactionAmounts.CustomerID;


-- 19. List the accounts that have had transactions with at least five other accounts. Each transaction should involve a unique account pair, and the accounts should be from different branches.

SELECT DISTINCT Transactions.AccountID
FROM Transactions
JOIN (
    SELECT T1.AccountID AS Account1, T2.AccountID AS Account2
    FROM Transactions T1
    JOIN Transactions T2 ON T1.AccountID < T2.AccountID
    WHERE T1.BranchID != T2.BranchID
    GROUP BY Account1, Account2
    HAVING COUNT(*) >= 5
) AS AccountPairs ON Transactions.AccountID IN (AccountPairs.Account1, AccountPairs.Account2);

-- 20. Find the top 3 customers who have made the most significant total withdrawals across all their accounts in the last month. Consider both the number of transactions and the total amount withdrawn as criteria.

-- 21. Retrieve the total number of customers who have more than one active account.

SELECT COUNT(*) AS TotalCustomersWithMultipleActiveAccounts
FROM (
    SELECT CA.CustomerID
    FROM CustomerAccounts CA
    JOIN Accounts A ON CA.AccountID = A.AccountID
    WHERE A.Status = 'Active'
    GROUP BY CA.CustomerID
    HAVING COUNT(CA.AccountID) > 1
) AS Subquery;


-- 22. Retrieve the top 5 customers with the highest total transaction amounts (sum of all their transactions).

SELECT C.FirstName, C.LastName, SUM(T.Amount) AS TotalTransactionAmount
FROM Customers C
JOIN Accounts A ON C.CustomerID = A.CustomerID
JOIN Transactions T ON A.AccountID = T.AccountID
GROUP BY C.CustomerID, C.FirstName, C.LastName
ORDER BY TotalTransactionAmount DESC
LIMIT 5;

-- 23. Provides a summary of transactions made by employees and customers in each branch within the last 6 months

SELECT
    B.BranchID,
    B.Name AS BranchName,
    E.EmployeeID,
    E.FirstName || ' ' || E.LastName AS EmployeeName,
    C.CustomerID,
    C.FirstName || ' ' || C.LastName AS CustomerName,
    COUNT(T.TransactionID) AS TotalTransactions
FROM
    Branches B
LEFT JOIN
    Employees E ON B.BranchID = E.BranchID
LEFT JOIN
    CustomerAccounts CA ON B.BranchID = CA.BranchID
LEFT JOIN
    Accounts A ON CA.AccountID = A.AccountID
LEFT JOIN
    Transactions T ON A.AccountID = T.AccountID
LEFT JOIN
    Customers C ON CA.CustomerID = C.CustomerID
WHERE
    T.Timestamp >= DATE('now', '-6 months')
GROUP BY
    B.BranchID, E.EmployeeID, C.CustomerID
ORDER BY
    TotalTransactions DESC;



-- 24. Provide total transactions for employees and customers within each branch.
WITH EmployeeTransactionCounts AS (
    SELECT
        B.BranchID,
        B.Name AS BranchName,
        E.EmployeeID,
        E.FirstName || ' ' || E.LastName AS EmployeeName,
        COUNT(DISTINCT T.TransactionID) AS EmployeeTransactionCount
    FROM
        Branches B
    LEFT JOIN
        Employees E ON B.BranchID = E.BranchID
    LEFT JOIN
        EmployeeRoles ER ON E.RoleID = ER.RoleID
    LEFT JOIN
        CustomerAccounts CA ON B.BranchID = CA.BranchID
    LEFT JOIN
        Accounts A ON CA.AccountID = A.AccountID
    LEFT JOIN
        Transactions T ON A.AccountID = T.AccountID
    WHERE
        T.Timestamp >= DATE('now', '-6 months')
    GROUP BY
        B.BranchID, E.EmployeeID
),

CustomerTransactionCounts AS (
    SELECT
        B.BranchID,
        B.Name AS BranchName,
        C.CustomerID,
        C.FirstName || ' ' || C.LastName AS CustomerName,
        COUNT(DISTINCT T.TransactionID) AS CustomerTransactionCount
    FROM
        Branches B
    LEFT JOIN
        CustomerAccounts CA ON B.BranchID = CA.BranchID
    LEFT JOIN
        Accounts A ON CA.AccountID = A.AccountID
    LEFT JOIN
        Transactions T ON A.AccountID = T.AccountID
    LEFT JOIN
        Customers C ON CA.CustomerID = C.CustomerID
    WHERE
        T.Timestamp >= DATE('now', '-6 months')
    GROUP BY
        B.BranchID, C.CustomerID
)

SELECT
    E.BranchID,
    E.BranchName,
    E.EmployeeID,
    E.EmployeeName,
    C.CustomerID,
    C.CustomerName,
    E.EmployeeTransactionCount + IFNULL(C.CustomerTransactionCount, 0) AS TotalTransactions
FROM
    EmployeeTransactionCounts E
LEFT JOIN
    CustomerTransactionCounts C ON E.BranchID = C.BranchID AND E.EmployeeID IS NOT NULL
UNION ALL
SELECT
    C.BranchID,
    C.BranchName,
    NULL AS EmployeeID,
    NULL AS EmployeeName,
    C.CustomerID,
    C.CustomerName,
    C.CustomerTransactionCount AS TotalTransactions
FROM
    CustomerTransactionCounts C
WHERE
    NOT EXISTS (
        SELECT 1
        FROM EmployeeTransactionCounts E
        WHERE E.BranchID = C.BranchID
    )
ORDER BY
    TotalTransactions DESC;



-- 25. Generate a detailed report for each bank branch that includes the following information: the total number of customers, the sum of all account balances, the average transaction amount for the past year, details of the top three customers by account balance, and a summary of employee details including their roles


WITH BranchSummary AS (
    SELECT
        Br.BranchID,
        Br.Name AS BranchName,
        COUNT(DISTINCT Cu.CustomerID) AS TotalCustomers,
        SUM(Ac.Balance) AS TotalBalances,
        AVG(Tr.Amount) AS AverageTransactionAmount,
        COUNT(DISTINCT Em.EmployeeID) AS TotalEmployees
    FROM
        Branches Br
    LEFT JOIN Employees Em ON Br.BranchID = Em.BranchID
    LEFT JOIN CustomerAccounts CA ON Br.BranchID = CA.BranchID
    LEFT JOIN Accounts Ac ON CA.AccountID = Ac.AccountID AND Ac.Status = 'Active'
    LEFT JOIN Transactions Tr ON Ac.AccountID = Tr.AccountID AND 
        strftime('%Y-%m-%d', Tr.Timestamp) >= strftime('%Y-%m-%d', 'now', '-1 year')
    LEFT JOIN Customers Cu ON CA.CustomerID = Cu.CustomerID
    GROUP BY
        Br.BranchID
),
TopCustomers AS (
    SELECT
        CA.BranchID,
        Cu.CustomerID,
        Cu.FirstName || ' ' || Cu.LastName AS CustomerName,
        Ac.Balance,
        RANK() OVER (PARTITION BY CA.BranchID ORDER BY Ac.Balance DESC) AS BalanceRank
    FROM
        CustomerAccounts CA
    JOIN Accounts Ac ON CA.AccountID = Ac.AccountID
    JOIN Customers Cu ON CA.CustomerID = Cu.CustomerID
    WHERE
        Ac.Status = 'Active'
),
BranchTopCustomers AS (
    SELECT
        TC.BranchID,
        TC.CustomerName,
        TC.Balance
    FROM
        TopCustomers TC
    WHERE
        TC.BalanceRank <= 3
),
EmployeeDetails AS (
    SELECT
        Em.BranchID,
        Em.EmployeeID,
        Em.FirstName || ' ' || Em.LastName AS EmployeeName,
        ER.RoleName
    FROM
        Employees Em
    JOIN EmployeeRoles ER ON Em.RoleID = ER.RoleID
)

SELECT
    BS.BranchID,
    BS.BranchName,
    BS.TotalCustomers,
    BS.TotalBalances,
    BS.AverageTransactionAmount,
    BTC.CustomerName AS TopCustomerName,
    BTC.Balance AS TopCustomerBalance,
    ED.EmployeeName,
    ED.RoleName
FROM
    BranchSummary BS
LEFT JOIN BranchTopCustomers BTC ON BS.BranchID = BTC.BranchID
LEFT JOIN EmployeeDetails ED ON BS.BranchID = ED.BranchID
ORDER BY
    BS.BranchID, BTC.Balance DESC;


-- Data Integrity Check

-- 26. Check for any accounts without customers (assuming foreign key relationship exists):

-- sql
SELECT * FROM Accounts WHERE CustomerID NOT IN (SELECT CustomerID FROM Customers);
-- Balance Verification

-- 27. Verify that no accounts have negative balances where they shouldn't (assuming accounts shouldn't be negative):

-- sql

SELECT * FROM Accounts WHERE Balance < 0 AND AccountTypeID NOT IN (SELECT AccountTypeID FROM AccountTypes WHERE Overdraft = 'Yes');
-- Constraints Check


-- 28. Check the total balance across all accounts:

--sql
-- Replace <AccountID> with an actual account ID.
SELECT * FROM Transactions WHERE AccountID = <AccountID>;

-- 29. List transactions for a given account:
-- sql
-- Replace <CustomerID> with an actual customer ID.
SELECT * FROM Accounts WHERE CustomerID = <CustomerID>;

-- 30. To back up your database:
-- bash
-- sqlite3 your_database.db .dump > backup.sql
-- To restore your database:

-- bash
-- sqlite3 your_database.db < backup.sql

