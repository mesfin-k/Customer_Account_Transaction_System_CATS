import sqlite3
from sqlite3 import Error
import os

def openConnection(_dbFile):
    print("++++++++++++++++++++++++++++++++++")
    print("Open database: ", _dbFile)
    conn = None
    try:
        conn = sqlite3.connect(_dbFile)
        print("success")
    except Error as e:
        print(e)
    print("++++++++++++++++++++++++++++++++++")
    return conn

def closeConnection(_conn, _dbFile):
    print("++++++++++++++++++++++++++++++++++")
    print("Close database: ", _dbFile)
    try:
        _conn.close()
        print("success")
    except Error as e:
        print(e)
    print("++++++++++++++++++++++++++++++++++")

def dropTable(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print("Drop table")

    drop_table_sql = "DROP TABLE IF EXISTS Districts;"

    try:
        cursor = _conn.cursor()
        cursor.execute(drop_table_sql)
        _conn.commit()
        print("Table dropped successfully")
    except Exception as e:
        print("Error occurred:", e)
        _conn.rollback()
    finally:
        cursor.close()

    print("++++++++++++++++++++++++++++++++++")

def dropAndRecreateTables(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print("Drop and recreate tables")

    # List of table names to drop and recreate
    tables = ["Addresses", "Customers", "Branches", "EmployeeRoles", "Accounts"]

    try:
        cursor = _conn.cursor()
        
        # Drop tables if they exist
        for table in tables:
            drop_table_sql = f"DROP TABLE IF EXISTS {table};"
            cursor.execute(drop_table_sql)
        
        # Recreate tables
        createTables(_conn)  # Recreate the Districts table
        populateAddresses(_conn)
        populateCustomers(_conn)
        populateBranches(_conn)
        populateEmployeeRoles(_conn)
        populateAccounts(_conn)
        populateDistricts(_conn)
        
        _conn.commit()
        print("Tables dropped and recreated successfully")
    except Exception as e:
        print("Error occurred:", e)
        _conn.rollback()
    finally:
        cursor.close()

    print("++++++++++++++++++++++++++++++++++")

def createDistrictTable(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print("Create District table")

    create_district_table_sql = """
    CREATE TABLE IF NOT EXISTS Districts (
        DistrictID INTEGER PRIMARY KEY,
        DistrictName TEXT NOT NULL,
        Region TEXT NOT NULL,
        ManagerID INTEGER
    );
    """

    try:
        cursor = _conn.cursor()
        cursor.execute(create_district_table_sql)
        _conn.commit()
        print("District table created successfully")
    except Exception as e:
        print("Error occurred:", e)
        _conn.rollback()
    finally:
        cursor.close()

    print("++++++++++++++++++++++++++++++++++")

def createTables(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print("Create tables if not exists")

    create_addresses_table_sql = """
    CREATE TABLE IF NOT EXISTS Addresses (
        AddressID INTEGER PRIMARY KEY,
        Street TEXT NOT NULL,
        City TEXT NOT NULL,
        State TEXT NOT NULL,
        Country TEXT NOT NULL,
        PostalCode TEXT NOT NULL
    );
    """

    create_customers_table_sql = """
    CREATE TABLE IF NOT EXISTS Customers (
        CustomerID INTEGER PRIMARY KEY,
        FirstName TEXT NOT NULL,
        LastName TEXT NOT NULL,
        SSN TEXT NOT NULL,
        AddressID INTEGER,
        PhoneNumber TEXT,
        Email TEXT
    );
    """

    create_branches_table_sql = """
    CREATE TABLE IF NOT EXISTS Branches (
        BranchID INTEGER PRIMARY KEY,
        Name TEXT NOT NULL,
        AddressID INTEGER,
        PhoneNumber TEXT
    );
    """

    create_employee_roles_table_sql = """
    CREATE TABLE IF NOT EXISTS EmployeeRoles (
        RoleID INTEGER PRIMARY KEY,
        RoleName TEXT NOT NULL,
        Description TEXT
    );
    """

    create_accounts_table_sql = """
    CREATE TABLE IF NOT EXISTS Accounts (
        AccountID INTEGER PRIMARY KEY,
        CustomerID INTEGER NOT NULL,
        AccountNumber TEXT NOT NULL,
        Balance REAL NOT NULL,
        AccountTypeID INTEGER,
        DateOpened TEXT,
        Status TEXT
    );
    """
    

    try:
        cursor = _conn.cursor()
        cursor.execute(create_addresses_table_sql)
        cursor.execute(create_customers_table_sql)
        cursor.execute(create_branches_table_sql)
        cursor.execute(create_employee_roles_table_sql)
        cursor.execute(create_accounts_table_sql)
        _conn.commit()
        print("Tables created successfully")
    except Exception as e:
        print("Error occurred:", e)
        _conn.rollback()
    finally:
        cursor.close()

    print("++++++++++++++++++++++++++++++++++")


def clearTable(_conn, table_name):
    print(f"Clearing table: {table_name}")
    try:
        cursor = _conn.cursor()
        cursor.execute(f"DELETE FROM {table_name};")
        _conn.commit()
    except Exception as e:
        print(f"Error occurred when clearing {table_name}: {e}")
        _conn.rollback()
    finally:
        cursor.close()
    print(f"Table {table_name} cleared successfully")
    print("++++++++++++++++++++++++++++++++++")

def populateAddresses(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print("Populate Addresses table")
    clearTable(_conn, "Addresses")
    address_data = [
        (100, "12321 free St", "Summerfield", "CA", "USA", "92704"),
        
    ]
    try:
        cursor = _conn.cursor()
        cursor.executemany("""
            INSERT INTO Addresses (AddressID, Street, City, State, Country, PostalCode)
            VALUES (?, ?, ?, ?, ?, ?)
        """, address_data)
        _conn.commit()
        print("Addresses table populated successfully")
    except Exception as e:
        print("Error occurred:", e)
        _conn.rollback()
    finally:
        cursor.close()
    print("++++++++++++++++++++++++++++++++++")



def populateCustomers(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print("Populate Customers table")

    customer_data = [
        # (CustomerID, FirstName, LastName, SSN, AddressID, PhoneNumber, Email)
        (1, "John", "Doe", "123-45-6789", 1, "555-1234", "johndoe@email.com"),
        (2, "Jane", "Smith", "987-65-4321", 2, "555-5678", "janesmith@email.com"),
    ]

    try:
        cursor = _conn.cursor()
        cursor.executemany("""
            INSERT INTO Customers (CustomerID, FirstName, LastName, SSN, AddressID, PhoneNumber, Email)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        """, customer_data)
        _conn.commit()
        print("Customers table populated successfully")
    except Exception as e:
        print("Error occurred:", e)
        _conn.rollback()
    finally:
        cursor.close()

    print("++++++++++++++++++++++++++++++++++")

def populateBranches(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print("Populate Branches table")

    branch_data = [
        # (BranchID, Name, AddressID, PhoneNumber)
        (1, "Main Branch", 1, "555-0001"),
        (2, "East Branch", 2, "555-0002"),
        
    ]

    try:
        cursor = _conn.cursor()
        cursor.executemany("""
            INSERT INTO Branches (BranchID, Name, AddressID, PhoneNumber)
            VALUES (?, ?, ?, ?)
        """, branch_data)
        _conn.commit()
        print("Branches table populated successfully")
    except Exception as e:
        print("Error occurred:", e)
        _conn.rollback()
    finally:
        cursor.close()

    print("++++++++++++++++++++++++++++++++++")

def populateEmployeeRoles(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print("Populate EmployeeRoles table")

    role_data = [
        # (RoleID, RoleName, Description)
        (1, "Manager", "Responsible for overseeing branch operations."),
        (2, "Teller", "Handles customer transactions at a branch."),
        
    ]

    try:
        cursor = _conn.cursor()
        cursor.executemany("""
            INSERT INTO EmployeeRoles (RoleID, RoleName, Description)
            VALUES (?, ?, ?)
        """, role_data)
        _conn.commit()
        print("EmployeeRoles table populated successfully")
    except Exception as e:
        print("Error occurred:", e)
        _conn.rollback()
    finally:
        cursor.close()

    print("++++++++++++++++++++++++++++++++++")

def populateAccounts(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print("Populate Accounts table")

    account_data = [
        # (AccountID, CustomerID, AccountNumber, Balance, AccountTypeID, DateOpened, Status)
        (1, 1, "ACC1234", 1000.00, 1, "2023-01-01", "Active"),
        (2, 2, "ACC5678", 500.00, 1, "2023-01-02", "Active"),
       
    ]

    try:
        cursor = _conn.cursor()
        cursor.executemany("""
            INSERT INTO Accounts (AccountID, CustomerID, AccountNumber, Balance, AccountTypeID, DateOpened, Status)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        """, account_data)
        _conn.commit()
        print("Accounts table populated successfully")
    except Exception as e:
        print("Error occurred:", e)
        _conn.rollback()
    finally:
        cursor.close()

    print("++++++++++++++++++++++++++++++++++")

def populateDistricts(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print("Populate Districts table")


    district_data = [
        (1, "North District", "Northern Region", 1),  
        (2, "South District", "Southern Region", 2),  
        
    ]

    try:
        cursor = _conn.cursor()
        cursor.executemany("""
            INSERT INTO Districts (DistrictID, DistrictName, Region, ManagerID)
            VALUES (?, ?, ?, ?)
        """, district_data)
        _conn.commit()
        print("Districts table populated successfully")
    except Exception as e:
        print("Error occurred:", e)
        _conn.rollback()
    finally:
        if cursor:
            cursor.close()

    print("++++++++++++++++++++++++++++++++++")


def create_View1(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print("Create V1")
    create_view_sql = """
    CREATE VIEW V1 AS
    SELECT 
        c.CustomerID, 
        c.FirstName, 
        c.LastName, 
        a.AccountID,
        a.AccountNumber,
        a.Balance AS AccountBalance,
        b.BranchID,
        b.Name AS BranchName
    FROM 
        Customers c
        JOIN CustomerAccounts ca ON c.CustomerID = ca.CustomerID
        JOIN Accounts a ON ca.AccountID = a.AccountID
        JOIN Branches b ON a.BranchID = b.BranchID;
    """
    try:
        cursor = _conn.cursor()
        cursor.execute(create_view_sql)
        _conn.commit()
        print("View V1 created successfully.")
    except Error as e:
        print(e)
    finally:
        cursor.close()
    print("++++++++++++++++++++++++++++++++++")



def create_View2(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print("Create V2")
    create_view_sql = """
    CREATE VIEW V2 AS
    SELECT 
        a.AccountID, 
        a.Balance, 
        b.BranchID, 
        b.Name AS BranchName, 
        SUM(t.Amount) AS TotalTransactionsAmount,
        COUNT(t.TransactionID) AS NumberOfTransactions
    FROM 
        Accounts a
        JOIN Branches b ON a.BranchID = b.BranchID
        LEFT JOIN Transactions t ON a.AccountID = t.AccountID
    GROUP BY 
        a.AccountID;
    """
    try:
        cursor = _conn.cursor()
        cursor.execute(create_view_sql)
        _conn.commit()
        print("View V2 created successfully.")
    except Error as e:
        print(e)
    finally:
        cursor.close()
    print("++++++++++++++++++++++++++++++++++")



def Q1(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print("Retrieves and outputs customer details with account numbers and branch names.")

    try:
        with open('output/Q1.out', 'w') as output:
            header = "{:<20} {:<20} {:<20} {:<20} {:<20}"
            output.write(header.format("CustomerID", "FirstName", "LastName", "AccountNumber", "BranchName") + '\n')

            query = """
                SELECT CustomerID, FirstName, LastName, AccountNumber, BranchName
                FROM V1
                ORDER BY CustomerID;
            """

            cursor = _conn.cursor()
            cursor.execute(query)
            rows = cursor.fetchall()

            for row in rows:
                output.write(header.format(row[0], row[1], row[2], row[3], row[4]) + '\n')

        print(f"Q1 results written to output/Q1.out")

    except Exception as e:
        print("Error occurred:", e)
    finally:
        if cursor:
            cursor.close()

    print("++++++++++++++++++++++++++++++++++")


def Q1_1(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print("Q1")

    try:
        with open('output/Q1.out', 'w') as output:
            header = "{:<20} {:<20} {:<20} {:<20} {:<20}"
            output.write(header.format("FirstName", "LastName", "BranchName", "Address", "TotalBalance") + '\n')

            query = """
                SELECT c.FirstName, c.LastName, 
                       b.Name AS BranchName, 
                       ad.Street || ', ' || ad.City || ', ' || ad.State AS Address, 
                       COALESCE(SUM(a.Balance), 0) AS TotalBalance
                FROM Customers c
                LEFT JOIN CustomerAccounts ca ON c.CustomerID = ca.CustomerID
                LEFT JOIN Accounts a ON ca.AccountID = a.AccountID
                LEFT JOIN Branches b ON a.BranchID = b.BranchID
                LEFT JOIN Addresses ad ON c.AddressID = ad.AddressID
                GROUP BY c.CustomerID
                ORDER BY TotalBalance DESC;
            """

            cursor = _conn.cursor()
            cursor.execute(query)
            rows = cursor.fetchall()

            for row in rows:
                output.write(header.format(row[0], row[1], row[2] if row[2] else 'NULL', row[3] if row[3] else 'NULL', row[4]) + '\n')

        print(f"Q1 results written to output/Q1.out")

    except Exception as e:
        print("Error occurred:", e)
    finally:
        if cursor:
            cursor.close()

    print("++++++++++++++++++++++++++++++++++")


def Q2(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print("Joins views to provide customer transaction details including balances and transaction counts.")

    try:
        with open('output/Q2.out', 'w') as output:
            header = "{:<20} {:<20} {:<20} {:<20} {:<20} {:<20} {:<20}"
            output.write(header.format("CustomerID", "FirstName", "LastName", "BranchName", "AccountBalance", "TotalTransactionsAmount", "NumberOfTransactions") + '\n')

            query = """
                SELECT 
                    v1.CustomerID, 
                    v1.FirstName, 
                    v1.LastName, 
                    v1.BranchName, 
                    v1.AccountBalance,
                    v2.TotalTransactionsAmount,
                    v2.NumberOfTransactions
                FROM V1
                JOIN V2 ON V1.AccountID = V2.AccountID;
            """

            cursor = _conn.cursor()
            cursor.execute(query)
            rows = cursor.fetchall()

            for row in rows:
                formatted_row = [str(item) if item is not None else 'NULL' for item in row]
                output.write(header.format(*formatted_row) + '\n')

        print(f"Q2 results written to output/Q2.out")

    except Exception as e:
        print("Error occurred:", e)
    finally:
        if cursor:
            cursor.close()

    print("++++++++++++++++++++++++++++++++++")




def Q3(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print("Outputs district names with counts of employees and branches in each district.")

    try:
        with open('output/Q3.out', 'w') as output:
            header = "{:<20} {:<20} {:<20}"
            output.write(header.format("DistrictName", "EmployeeCount", "BranchCount") + '\n')

            query = """
                SELECT d.DistrictName, COUNT(e.EmployeeID) as EmployeeCount, COUNT(DISTINCT b.BranchID) as BranchCount
                FROM Districts d
                LEFT JOIN Branches b ON d.DistrictID = b.DistrictID
                LEFT JOIN Employees e ON b.BranchID = e.BranchID
                GROUP BY d.DistrictID;
            """

            cursor = _conn.cursor()
            cursor.execute(query)
            rows = cursor.fetchall()

            for row in rows:
                output.write(header.format(row[0], row[1], row[2]) + '\n')

        print(f"Q3 results written to output/Q3.out")

    except Exception as e:
        print("Error occurred:", e)
    finally:
        if cursor:
            cursor.close()

    print("++++++++++++++++++++++++++++++++++")


def Q4(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print("Gathers and outputs employee names, branch names, roles, and addresses.")

    try:
        with open('output/Q4.out', 'w') as output:
            header = "{:<20} {:<20} {:<20} {:<20} {:<20}"
            output.write(header.format("EmployeeName", "BranchName", "Role", "Street", "City") + '\n')

            query = """
                SELECT COALESCE(e.FirstName || ' ' || e.LastName, 'NULL') AS EmployeeName, 
                       COALESCE(b.Name, 'NULL') AS BranchName, 
                       COALESCE(er.RoleName, 'NULL') AS Role, 
                       COALESCE(a.Street, 'NULL') AS Street, 
                       COALESCE(a.City, 'NULL') AS City
                FROM Employees e
                LEFT JOIN Branches b ON e.BranchID = b.BranchID
                LEFT JOIN EmployeeRoles er ON e.RoleID = er.RoleID
                LEFT JOIN Addresses a ON b.AddressID = a.AddressID;
            """

            cursor = _conn.cursor()
            cursor.execute(query)
            rows = cursor.fetchall()

            for row in rows:
                output.write(header.format(row[0], row[1], row[2], row[3], row[4]) + '\n')

        print(f"Q4 results written to output/Q4.out")

    except Exception as e:
        print("Error occurred:", e)
    finally:
        if cursor:
            cursor.close()

    print("++++++++++++++++++++++++++++++++++")



def Q5(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print("Provides detailed district information including transactions, account types, and counts of branches, customers, and employees.")

    try:
        with open('output/Q5.out', 'w') as output:
            header = "{:<20} {:<20} {:<20} {:<20} {:<20} {:<20} {:<20}"
            output.write(header.format("DistrictName", "TotalTransactions", "TotalAmount", "AccountTypesInvolved", "NumberOfBranches", "NumberOfCustomers", "NumberOfEmployees") + '\n')

            query = """
                SELECT d.DistrictName,
                       COALESCE(COUNT(t.TransactionID), 0) AS TotalTransactions,
                       COALESCE(SUM(t.Amount), 0) AS TotalAmount,
                       COALESCE(COUNT(DISTINCT a.AccountTypeID), 0) AS AccountTypesInvolved,
                       COALESCE(COUNT(DISTINCT b.BranchID), 0) AS NumberOfBranches,
                       COALESCE(COUNT(DISTINCT c.CustomerID), 0) AS NumberOfCustomers,
                       COALESCE(COUNT(DISTINCT e.EmployeeID), 0) AS NumberOfEmployees
                FROM Districts d
                LEFT JOIN Branches b ON d.DistrictID = b.DistrictID
                LEFT JOIN Transactions t ON b.BranchID = t.BranchID
                LEFT JOIN Accounts a ON t.AccountID = a.AccountID
                LEFT JOIN CustomerAccounts ca ON a.AccountID = ca.AccountID
                LEFT JOIN Customers c ON ca.CustomerID = c.CustomerID
                LEFT JOIN Employees e ON b.BranchID = e.BranchID
                GROUP BY d.DistrictID;
            """

            cursor = _conn.cursor()
            cursor.execute(query)
            rows = cursor.fetchall()

            for row in rows:
                output.write(header.format(row[0], str(row[1]), str(row[2]), str(row[3]), str(row[4]), str(row[5]), str(row[6])) + '\n')

        print(f"Q5 results written to output/Q5.out")

    except Exception as e:
        print("Error occurred:", e)
    finally:
        if cursor:
            cursor.close()

    print("++++++++++++++++++++++++++++++++++")


def Q5_1(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print("Q5")

    try:
        with open('output/Q5.out', 'w') as output:
            header = "{:<20} {:<20} {:<20} {:<20} {:<20} {:<20} {:<20}"
            output.write(header.format("DistrictName", "TotalTransactions", "TotalAmount", "AccountTypesInvolved", "NumberOfBranches", "NumberOfCustomers", "NumberOfEmployees") + '\n')

            query = """
                SELECT d.DistrictName,
                       COALESCE(COUNT(t.TransactionID), 0) AS TotalTransactions,
                       COALESCE(SUM(t.Amount), 0) AS TotalAmount,
                       COALESCE(COUNT(DISTINCT a.AccountTypeID), 0) AS AccountTypesInvolved,
                       COALESCE(COUNT(DISTINCT b.BranchID), 0) AS NumberOfBranches,
                       COALESCE(COUNT(DISTINCT c.CustomerID), 0) AS NumberOfCustomers,
                       COALESCE(COUNT(DISTINCT e.EmployeeID), 0) AS NumberOfEmployees
                FROM Districts d
                LEFT JOIN Branches b ON d.DistrictID = b.DistrictID
                LEFT JOIN Transactions t ON b.BranchID = t.BranchID
                LEFT JOIN Accounts a ON t.AccountID = a.AccountID
                LEFT JOIN CustomerAccounts ca ON a.AccountID = ca.AccountID
                LEFT JOIN Customers c ON ca.CustomerID = c.CustomerID
                LEFT JOIN Employees e ON b.BranchID = e.BranchID
                GROUP BY d.DistrictID;
            """

            cursor = _conn.cursor()
            cursor.execute(query)
            rows = cursor.fetchall()

            for row in rows:
                output.write(header.format(row[0], str(row[1]), str(row[2]), str(row[3]), str(row[4]), str(row[5]), str(row[6])) + '\n')

        print(f"Q5 results written to output/Q5.out")

    except Exception as e:
        print("Error occurred:", e)
    finally:
        if cursor:
            cursor.close()

    print("++++++++++++++++++++++++++++++++++")



def Q6(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print("Calculates and outputs the average account balance for a specific branch.")

    # Read BranchID from the input file
    try:
        with open("input/6.in", "r") as input_file:
            branch_id = input_file.readline().strip()
    except Exception as e:
        print("Error reading input file:", e)
        return

    try:
        with open('output/Q6.out', 'w') as output:
            header = "{:<20} {:<20}"
            output.write(header.format("BranchID", "AverageBalance") + '\n')

            query = """
                SELECT b.BranchID, AVG(a.Balance) AS AvgBalance
                FROM branches b
                JOIN Accounts a ON b.BranchID = a.BranchID
                WHERE b.BranchID = ?
                GROUP BY b.BranchID;
            """

            cursor = _conn.cursor()
            cursor.execute(query, (branch_id,))
            rows = cursor.fetchall()

            for row in rows:
                output.write(header.format(row[0], row[1]) + '\n')

        print(f"Q6 results written to output/Q6.out")

    except Exception as e:
        print("Error occurred:", e)
    finally:
        if cursor:
            cursor.close()

    print("++++++++++++++++++++++++++++++++++")


def Q7(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print("Outputs branches meeting certain deposit and withdrawal criteria.")

    # Read BranchName, TotalDeposits, and TotalWithdrawals criteria from the input file
    try:
        with open("input/7.in", "r") as input_file:
            branch_name = input_file.readline().strip()
            total_deposits = float(input_file.readline().strip())
            total_withdrawals = float(input_file.readline().strip())
    except Exception as e:
        print("Error reading input file:", e)
        return

    try:
        with open('output/Q7.out', 'w') as output:
            header = "{:<20} {:<20} {:<20}"
            output.write(header.format("BranchName", "TotalDeposits", "TotalWithdrawals") + '\n')

            query = """
                SELECT b.Name, 
                       SUM(CASE WHEN t.Type = 'Deposit' THEN t.Amount ELSE 0 END) as TotalDeposits,
                       SUM(CASE WHEN t.Type = 'Withdrawal' THEN t.Amount ELSE 0 END) as TotalWithdrawals
                FROM Branches b
                JOIN Transactions t ON b.BranchID = t.BranchID
                GROUP BY b.BranchID
                HAVING b.Name = ? OR 
                       SUM(CASE WHEN t.Type = 'Deposit' THEN t.Amount ELSE 0 END) >= ? OR 
                       SUM(CASE WHEN t.Type = 'Withdrawal' THEN t.Amount ELSE 0 END) >= ?;
            """

            cursor = _conn.cursor()
            cursor.execute(query, (branch_name, total_deposits, total_withdrawals))
            rows = cursor.fetchall()

            for row in rows:
                output.write(header.format(row[0], row[1], row[2]) + '\n')

        print(f"Q7 results written to output/Q7.out")

    except Exception as e:
        print("Error occurred:", e)
    finally:
        if cursor:
            cursor.close()

    print("++++++++++++++++++++++++++++++++++")



def Q8(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print(" Lists customers with total balances exceeding a specified threshold.")

    # Read the balance threshold from the input file
    try:
        with open("input/8.in", "r") as input_file:
            balance_threshold = float(input_file.readline().strip())
    except Exception as e:
        print("Error reading input file:", e)
        return

    try:
        with open('output/Q8.out', 'w') as output:
            header = "{:<20} {:<20} {:<20} {:<20} {:<20}"
            output.write(header.format("FirstName", "LastName", "TotalBalance", "CardNumber", "BranchName") + '\n')

            query = """
                SELECT c.FirstName, c.LastName, SUM(a.Balance) as TotalBalance, 
                       cd.CardNumber, b.Name as BranchName
                FROM Customers c
                JOIN Accounts a ON c.CustomerID = a.CustomerID
                JOIN Cards cd ON a.AccountID = cd.AccountID
                JOIN Branches b ON a.BranchID = b.BranchID
                GROUP BY c.CustomerID, cd.CardNumber
                HAVING SUM(a.Balance) >= ?
                ORDER BY TotalBalance DESC;
            """

            cursor = _conn.cursor()
            cursor.execute(query, (balance_threshold,))
            rows = cursor.fetchall()

            for row in rows:
                output.write(header.format(row[0], row[1], row[2], row[3], row[4]) + '\n')

        print(f"Q8 results written to output/Q8.out")

    except Exception as e:
        print("Error occurred:", e)
    finally:
        if cursor:
            cursor.close()

    print("++++++++++++++++++++++++++++++++++")



def Q9(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print("Q9. Outputs average balances for specific account types.")

    # Read account types from the input file and handle duplicates
    try:
        with open("input/9.in", "r") as input_file:
            account_types = input_file.read().splitlines()
            # Remove duplicates
            unique_account_types = list(set(account_types))
            if len(account_types) != len(unique_account_types):
                print("Change account type - duplicates found.")
                return
    except Exception as e:
        print("Error reading input file:", e)
        return

    try:
        with open('output/Q9.out', 'w') as output:
            header = "{:<20} {:<20}"
            output.write(header.format("AccountType", "AverageBalance") + '\n')

            query = """
                SELECT at.TypeName, AVG(a.Balance) as AverageBalance
                FROM AccountTypes at
                JOIN Accounts a ON at.AccountTypeID = a.AccountTypeID
                WHERE at.TypeName IN ({})
                GROUP BY at.AccountTypeID;
            """.format(",".join(["?"] * len(unique_account_types)))  # Use placeholders for each account type

            cursor = _conn.cursor()
            cursor.execute(query, unique_account_types)
            rows = cursor.fetchall()

            for row in rows:
                output.write(header.format(row[0], row[1]) + '\n')

        print(f"Q9 results written to output/Q9.out")

    except Exception as e:
        print("Error occurred:", e)
    finally:
        if cursor:
            cursor.close()

    print("++++++++++++++++++++++++++++++++++")


def Q10(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print("Provides a summary of each district including branch, customer, and employee counts.")

    try:
        with open('output/Q10.out', 'w') as output:
            header = "{:<20} {:<20} {:<20} {:<20}"
            output.write(header.format("DistrictName", "NumberOfBranches", "NumberOfEmployees", "NumberOfCustomers") + '\n')

            query = """
                SELECT d.DistrictName, 
                       COUNT(DISTINCT b.BranchID) AS NumberOfBranches, 
                       COUNT(DISTINCT e.EmployeeID) AS NumberOfEmployees,
                       COUNT(DISTINCT c.CustomerID) AS NumberOfCustomers
                FROM Districts d
                LEFT JOIN Branches b ON d.DistrictID = b.DistrictID
                LEFT JOIN Employees e ON b.BranchID = e.BranchID
                LEFT JOIN CustomerAccounts ca ON b.BranchID = ca.BranchID
                LEFT JOIN Customers c ON ca.CustomerID = c.CustomerID
                GROUP BY d.DistrictID;
            """

            cursor = _conn.cursor()
            cursor.execute(query)
            rows = cursor.fetchall()

            for row in rows:
                output.write(header.format(row[0], row[1], row[2], row[3]) + '\n')

        print(f"Q10 results written to output/Q10.out")

    except Exception as e:
        print("Error occurred:", e)
    finally:
        if cursor:
            cursor.close()

    print("++++++++++++++++++++++++++++++++++")



def Q11(_conn):
    print("++++++++++++++++++++++++++++++++++")
    print("Outputs the number of branches and total customer balances in each district.")

    try:
        with open('output/11.out', 'w') as output:
            header = "{:<20} {:<20} {:<20}"
            output.write(header.format("DistrictName", "NumberOfBranches", "TotalCustomerBalance") + '\n')

            query = """
                SELECT d.DistrictName, COUNT(b.BranchID) AS NumberOfBranches, 
                       SUM(a.Balance) AS TotalCustomerBalance
                FROM Districts d
                LEFT JOIN Branches b ON d.DistrictID = b.DistrictID
                LEFT JOIN Accounts a ON b.BranchID = a.BranchID
                GROUP BY d.DistrictID;
            """

            cursor = _conn.cursor()
            cursor.execute(query)
            rows = cursor.fetchall()

            for row in rows:
                district_name = row[0]
                number_of_branches = row[1]
                total_customer_balance = row[2] if row[2] is not None else 0.0
                output.write(header.format(district_name, number_of_branches, total_customer_balance) + '\n')

        print(f"Q11 results written to output/Q11.out")

    except Exception as e:
        print("Error occurred:", e)
    finally:
        if cursor:
            cursor.close()

    print("++++++++++++++++++++++++++++++++++")



def main():
    database = r"tpch.sqlite"
    
    conn = openConnection(database)
    if conn:
        try:

            # Populate Tables
            populateAddresses(conn)

            # Create views 
            create_View1(conn)
            create_View2(conn)

            Q1(conn)
            Q2(conn)
            Q3(conn)
            Q4(conn)
            Q5(conn)
            Q6(conn)
            Q7(conn)
            Q8(conn)
            Q9(conn)
            Q10(conn)
          # Q11(conn)
            
            
        finally:
            closeConnection(conn, database)

if __name__ == '__main__':
    main()

