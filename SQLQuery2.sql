
USE Leave;

-- Employees Table
CREATE TABLE Employees (
    EmployeeID INT PRIMARY KEY IDENTITY(1,1),
    FullName NVARCHAR(100) NOT NULL,
    Role NVARCHAR(50),
    Department NVARCHAR(50)
);

-- Leaves Table
CREATE TABLE Leaves (
    LeaveID INT PRIMARY KEY IDENTITY(1,1),
    EmployeeID INT FOREIGN KEY REFERENCES Employees(EmployeeID),
    LeaveType NVARCHAR(50),
    StartDate DATE,
    EndDate DATE
);

-- Missions Table
CREATE TABLE Missions (
    MissionID INT PRIMARY KEY IDENTITY(1,1),
    EmployeeID INT FOREIGN KEY REFERENCES Employees(EmployeeID),
    MissionType NVARCHAR(50),
    StartDate DATE,
    EndDate DATE
);

-- OrganChart
CREATE TABLE OrganizationChart (
    RoleID INT PRIMARY KEY IDENTITY(1,1),
    RoleName NVARCHAR(50),
    Approver NVARCHAR(50)
);

CREATE TABLE ApprovalChart (
    ApprovalID INT PRIMARY KEY IDENTITY(1,1),
    LeaveCategory NVARCHAR(50), -- Type Of Leave
    ApproverRole NVARCHAR(50), -- ApporverRole
    ApproverName NVARCHAR(100) -- ApproverName
);
ALTER TABLE ApprovalChart
ADD CONSTRAINT CHK_ApprovalCategory CHECK (LeaveCategory IN ('entitlement', 'illness', 'Missions'));

INSERT INTO ApprovalChart (LeaveCategory, ApproverRole, ApproverName)
VALUES
('entitlement', 'DirectManager', NULL), 
('illness', 'HumanResource', NULL), 
('Missions', 'SeniorManager', 'AliAhmadi'); 

CREATE PROCEDURE AddLeave
    @EmployeeID INT,
    @LeaveType NVARCHAR(50),
    @StartDate DATE,
    @EndDate DATE
AS
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM Leaves
        WHERE EmployeeID = @EmployeeID 
          AND LeaveType = @LeaveType
          AND StartDate = @StartDate
    )
    BEGIN
        INSERT INTO Leaves (EmployeeID, LeaveType, StartDate, EndDate)
        VALUES (@EmployeeID, @LeaveType, @StartDate, @EndDate);
    END
    ELSE
    BEGIN
        RAISERROR ('This leave already exists.', 16, 1);
    END
END;

---------------------------------Limitations-------------------------------------------------------------------
ALTER TABLE Leaves
ADD Approved int
CONSTRAINT CHK_LeaveCategory CHECK (LeaveCategory IN ('EH', 'EJ', 'M'));

CREATE TRIGGER CheckAnnualLeave
ON Leaves
INSTEAD OF INSERT
AS
BEGIN
    DECLARE @EmployeeID INT, @StartDate DATE, @EndDate DATE, @Days INT;

    SELECT @EmployeeID = EmployeeID, @StartDate = StartDate, @EndDate = EndDate
    FROM inserted;

    SET @Days = DATEDIFF(DAY, @StartDate, @EndDate) + 1;

    -- Count how many days are uesd for entitlement leave
    DECLARE @UsedDays INT;
    SELECT @UsedDays = ISNULL(SUM(DATEDIFF(DAY, StartDate, EndDate) + 1), 0)
    FROM Leaves
    WHERE EmployeeID = @EmployeeID AND LeaveCategory = 'EH' AND YEAR(StartDate) = YEAR(@StartDate);

    IF (@UsedDays + @Days > 30)
    BEGIN
        RAISERROR ('The amount of leaves were more than 30.', 16, 1);
    END
    ELSE
    BEGIN
        INSERT INTO Leaves (EmployeeID, LeaveType, StartDate, EndDate, LeaveCategory)
        SELECT EmployeeID, LeaveType, StartDate, EndDate, LeaveCategory
        FROM inserted;
    END
END;

---------------------------------Limitions for missions-----------------------------------
CREATE TRIGGER CheckMissionLeave
ON Leaves
INSTEAD OF INSERT
AS
BEGIN
    DECLARE @EmployeeID INT, @StartDate DATE, @EndDate DATE, @Days INT;

    SELECT @EmployeeID = EmployeeID, @StartDate = StartDate, @EndDate = EndDate
    FROM inserted;

    SET @Days = DATEDIFF(DAY, @StartDate, @EndDate) + 1;

    -- Count how many days are used in the mounth
    DECLARE @UsedDays INT;
    SELECT @UsedDays = ISNULL(SUM(DATEDIFF(DAY, StartDate, EndDate) + 1), 0)
    FROM Leaves
    WHERE EmployeeID = @EmployeeID AND LeaveCategory = 'M' AND MONTH(StartDate) = MONTH(@StartDate);

    IF (@UsedDays + @Days > 10)
    BEGIN
        RAISERROR ('The amount of leaves are more than 10.', 16, 1);
    END
    ELSE
    BEGIN
        INSERT INTO Leaves (EmployeeID, LeaveType, StartDate, EndDate, LeaveCategory)
        SELECT EmployeeID, LeaveType, StartDate, EndDate, LeaveCategory
        FROM inserted;
    END
END;
---------------------------------Limitions for illness---------------------------
ALTER TABLE Leaves
ADD MedicalApproval BIT DEFAULT 0;
CREATE TRIGGER CheckSickLeave
ON Leaves
INSTEAD OF INSERT
AS
BEGIN
    DECLARE @LeaveCategory NVARCHAR(50), @MedicalApproval BIT;

    SELECT @LeaveCategory = LeaveCategory, @MedicalApproval = MedicalApproval
    FROM inserted;

    IF (@LeaveCategory = 'ES' AND @MedicalApproval = 0)
    BEGIN
        RAISERROR ('This type of leave need the doctor apporval.', 16, 1);
    END
    ELSE
    BEGIN
        INSERT INTO Leaves (EmployeeID, LeaveType, StartDate, EndDate, LeaveCategory, MedicalApproval)
        SELECT EmployeeID, LeaveType, StartDate, EndDate, LeaveCategory, MedicalApproval
        FROM inserted;
    END
END;
------------------------------------insert data with limit-----------------------------------
EXEC AddLeave
    @EmployeeID = 1,
    @LeaveType = 'entitlement',
    @StartDate = '2025-01-20',
    @EndDate = '2025-01-25',
    @LeaveCategory = 'entitlement';

EXEC AddLeave
    @EmployeeID = 2,
    @LeaveType = 'illness',
    @StartDate = '2025-01-10',
    @EndDate = '2025-01-12',
    @LeaveCategory = 'illness',
    @MedicalApproval = 1;

EXEC AddLeave
    @EmployeeID = 3,
    @LeaveType = 'Missions',
    @StartDate = '2025-01-05',
    @EndDate = '2025-01-08',
    @LeaveCategory = 'Missions';

---------------------------------------Getting report-----------------------------------
SELECT e.FullName, SUM(DATEDIFF(DAY, l.StartDate, l.EndDate) + 1) AS UsedDays
FROM Leaves l
JOIN Employees e ON l.EmployeeID = e.EmployeeID
WHERE LeaveCategory = 'entitlement' AND YEAR(l.StartDate) = YEAR(GETDATE())
GROUP BY e.FullName;

SELECT e.FullName, l.StartDate, l.EndDate
FROM Leaves l
JOIN Employees e ON l.EmployeeID = e.EmployeeID
WHERE LeaveCategory = 'Missions'
GROUP BY e.FullName, l.StartDate, l.EndDate
HAVING SUM(DATEDIFF(DAY, l.StartDate, l.EndDate) + 1) > 10;
-----------------------------------------Confirmation report--------------------------------------
SELECT e.FullName, l.LeaveCategory, l.StartDate, l.EndDate, l.Approver
FROM Leaves l
JOIN Employees e ON l.EmployeeID = e.EmployeeID;
--------------------------------------------Confirmation report for any type of leave----------------------------
SELECT LeaveCategory, ApproverRole, ApproverName
FROM ApprovalChart;
-------------------------------------------------------------------------------------------
---------------------Add confirmation column------------------------------------------
ALTER TABLE Leaves
ADD Approver NVARCHAR(100) DEFAULT NULL;
------------------------------Determinants----------------------------------------------
CREATE TRIGGER SetLeaveApprover
ON Leaves
AFTER INSERT
AS
BEGIN
    DECLARE @LeaveCategory NVARCHAR(50), @EmployeeID INT, @ApproverRole NVARCHAR(50), @ApproverName NVARCHAR(100);

    SELECT @LeaveCategory = LeaveCategory, @EmployeeID = EmployeeID
    FROM inserted;

    -- Finding the right approver from the organizational chart table
    SELECT @ApproverRole = ApproverRole, @ApproverName = ApproverName
    FROM ApprovalChart
    WHERE LeaveCategory = @LeaveCategory;

    -- Update the leave schedule with confirming information
    UPDATE Leaves
    SET Approver = COALESCE(@ApproverName, @ApproverRole)
    WHERE EmployeeID = @EmployeeID AND LeaveCategory = @LeaveCategory;
END;
----------------------------------------------------------------------------------------

select * from Employees
select * from Leaves
select * from Missions
select * from OrganizationChart
select * from ApprovalChart
