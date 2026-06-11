# Timetable Management Module - Complete Analysis

## Executive Summary

This document provides a comprehensive analysis of the timetable management system, including:
- File locations and structure
- SQL queries used for class/module list display
- Filtering conditions and status logic
- Where new classes are inserted
- Data flow from creation through display

---

## 1. KEY FILES STRUCTURE

### Frontend Pages (Display/Management)
```
web/pages/
├── registry_class.php           ← Main CLASS REGISTRY page
├── add_class.php                ← Add new class form UI
├── confirm_module.php           ← Confirm timetable (show completed modules to approve)
├── confirm_module_reservations.php ← Confirm lab reservations
├── timetable.php                ← Main timetable calendar display
├── timetableMy.php              ← User's personal timetable
├── publish_tentative.php        ← Publish tentative schedule
├── history-module-calendar.php  ← Historical module calendar
├── import_class.php             ← Import classes from previous batch
└── init_module.php              ← Initialize module
```

### Backend Scripts (Database/Processing)
```
web/pages/assets/scripts/backend/
├── add_class.php                ← INSERT new classes & classschedules
├── add_class1.php               ← Alternative class insertion handler
├── add_class - Copy (2).php     ← Backup version
├── get_val.php                  ← Dynamic form data loader (KEY QUERIES)
├── load_timetable.php           ← MAIN CLASS LIST QUERY
├── load_timetableMyBg.php       ← Personal timetable loader
├── Lab-load_timetable.php       ← Lab-specific class loader
├── database.php                 ← Database connection
└── select_val.php               ← Select value helpers
```

---

## 2. DATABASE TABLES

### classtopics - Class Definition
```sql
-- Stores class information/topics
topic_id          INT PRIMARY KEY AUTO_INCREMENT
b_no              INT (Batch Number)
m_code            VARCHAR (Module Code)
activity          INT (Activity Type ID)
activity_no       INT (Activity Number for groups)
class_topic       VARCHAR (Class Title/Description)
class_group       VARCHAR (Group Name - 'All' for single)
dep_code          INT (Department Code)
staff             INT (Primary Lecturer Staff ID)
```

### classschedules - Class Scheduling & Status
```sql
-- Stores class schedule and STATUS information
class_id          INT PRIMARY KEY AUTO_INCREMENT
class_topic_id    INT FK → classtopics.topic_id
class_start       DATETIME (Schedule start)
class_end         DATETIME (Schedule end)
lab_code          INT (Lab reservation ID)
class_status      INT ← **KEY STATUS FIELD** (1=ACTIVE, 0/other=HIDDEN)
class_remark      TEXT
add_staff         VARCHAR (Staff who added class)
```

### classtopics_staff - Additional Lecturers
```sql
-- Stores additional lecturers assigned to class
topic_id          INT FK → classtopics.topic_id
class_group       VARCHAR
staff             INT (Additional Staff ID)
```

### classtopics_new - New Classes Table
```sql
-- Parallel table for new class insertion
topic_id          INT
b_no              INT
m_code            VARCHAR
activity          INT
class_topic       VARCHAR
dep_code          INT
```

### batchmodule - Module Schedule Progress
```sql
-- Stores module workflow progress
b_no              INT
m_code            VARCHAR
ttprogress        INT (Progress stage: 1=Planning, 2=Ready, 3=Published, 4=Complete)
st_dt             DATETIME (Module start date)
en_dt             DATETIME (Module end date)
comp_on           DATETIME (Completion timestamp)
conf_on           DATETIME (Confirmation timestamp)
cordi             INT (Coordinator staff ID)
cordi2            INT (Secondary Coordinator)
ttmng1-6          INT (Timetable managers)
```

---

## 3. CRITICAL SQL QUERIES

### 🔴 MAIN CLASS LIST DISPLAY QUERY
**File:** `web/pages/assets/scripts/backend/load_timetable.php`

```sql
-- DISPLAYS CLASS LIST WITH STATUS FILTER
SELECT 
    CONCAT(class_id, '-', class_topic_id) as class_reserve_id, 
    class_topic, 
    class_start, 
    class_end, 
    classtopics_staff.class_group,
    GROUP_CONCAT(CONCAT(staff.t_nm, '. ', staff.firstname, ' ', staff.surname) SEPARATOR ', ') AS stVAL,
    a_name, 
    a_type, 
    lab_nm, 
    class_remark, 
    COALESCE(div_color, '#2E7D32') as div_color 
FROM classschedules
JOIN classtopics ON class_topic_id = topic_id
LEFT JOIN classtopics_staff ON classtopics_staff.topic_id = classtopics.topic_id
LEFT JOIN staff ON classtopics_staff.staff = staff.st_id
LEFT JOIN divisions ON staff.dep_code = div_id
JOIN activity ON a_id = activity
LEFT JOIN lab ON lab.lab_code = classschedules.lab_code
WHERE class_status = $status AND b_no = $bno AND TRIM(m_code) = TRIM('$mod')
GROUP BY class_id, class_topic_id
ORDER BY class_id;
```

**KEY PARAMETERS:**
- `$status` - GET parameter passed as `custom_param3` (determines which classes appear!)
- `$bno` - Batch Number
- `$mod` - Module Code
- **CRITICAL FILTER:** `WHERE class_status = $status`

**Plus also queries classes where:**
```sql
-- SECOND PART OF QUERY (UNION)
WHERE classtopics.dep_code = classtopics.staff 
  AND class_status = $status 
  AND b_no = $bno 
  AND TRIM(m_code) = TRIM('$mod')
```

---

### 🔴 ACTIVITY SUMMARY QUERY (Used in Class Management)
**File:** `web/pages/assets/scripts/backend/get_val.php` (Case 3)

```sql
-- SHOWS CLASS COUNT BY ACTIVITY TYPE
SELECT a_id, a_name, activity, count(*) AS ct 
FROM classschedules 
JOIN classtopics ON class_topic_id = topic_id 
JOIN activity ON activity = a_id 
WHERE b_no = $bno 
  AND m_code = '$mod' 
  AND class_status = 1           ← **HARDCODED TO STATUS 1**
GROUP BY activity 
ORDER BY a_name;
```

**IMPORTANT:** This query HARDCODES `class_status = 1`, meaning it only shows active classes (status 1).

---

### 🔴 MODULE CONFIRMATION QUERY
**File:** `web/pages/confirm_module.php` (Lines 50-56)

```sql
-- SHOWS MODULES READY FOR TIMETABLE CONFIRMATION
SELECT b_no, batchmodule.m_code, CONCAT(m.m_name, ' ', m.m_phase) AS modnm
FROM `batchmodule` 
JOIN module m ON m.m_code = batchmodule.m_code
WHERE (comp_on = '0000-00-00 00:00:00' OR comp_on IS NULL) 
  AND (conf_on = '0000-00-00 00:00:00' OR conf_on IS NULL) 
  AND st_dt <> '0000-00-00 00:00:00' 
  AND en_dt <> '0000-00-00 00:00:00' 
  AND ttprogress = 2
  AND (cordi IN (SELECT st_id FROM staff WHERE username='$user') 
       OR cordi2 IN (SELECT st_id FROM staff WHERE username='$user'))
ORDER BY b_no DESC;
```

**FILTERING CONDITIONS:**
- `comp_on = '0000-00-00 00:00:00' OR comp_on IS NULL` - Module NOT yet composed
- `conf_on = '0000-00-00 00:00:00' OR conf_on IS NULL` - Module NOT yet confirmed
- `ttprogress = 2` - Module at progress stage 2 (Ready/Tentative)
- Must be coordinator (cordi or cordi2)

---

### 🟢 CLASS INSERTION QUERIES
**File:** `web/pages/assets/scripts/backend/add_class.php` (Lines 35-90)

#### 1. Insert into classtopics
```sql
INSERT INTO classtopics (b_no, m_code, activity, class_topic, class_group, dep_code, staff) 
VALUES ( $bno, '$module', '$activity', '$topic', 'All', $dep, $staff);
```

#### 2. Insert into classtopics_new (parallel table)
```sql
INSERT INTO classtopics_new (topic_id, b_no, m_code, activity, class_topic, dep_code) 
VALUES ($submitID, $bno, '$module', '$activity', '$topic', $dep);
```

#### 3. Insert into classschedules with STATUS
```sql
INSERT INTO classschedules (class_topic_id, class_start, class_end, lab_code, class_status, add_staff, class_remark) 
VALUES ($submitID, '$classStTime', '$classEnTime', $lab, 1, '$addStaff', '$classremark');
                                                                      ↑
                                                        **STATUS SET TO 1 (ACTIVE)**
```

#### 4. Insert additional lecturers
```sql
INSERT INTO classtopics_staff (topic_id, class_group, staff) 
VALUES ($submitID, 'All', $lecturerStaff);
```

---

## 4. CLASS STATUS VALUES & WORKFLOW

### Status Values in classschedules.class_status
```
1 = ACTIVE (Visible in class management)
0 = HIDDEN/CANCELLED
2 = POSTPONED (possibly)
3 = DRAFT (possibly - used in timetable.php comment)
```

### Status Transitions
```
Initial Creation:  class_status = 1 (ACTIVE)
     ↓
[CLASS MANAGEMENT & SCHEDULING]
     ↓
Status = 1 → Appears in activity summary (get_val.php case 3)
Status ≠ 1 → Hidden from management list
```

---

## 5. WHERE NEWLY ADDED CLASSES ARE INSERTED

### File Path: `web/pages/assets/scripts/backend/add_class.php`

#### Step-by-step insertion process:

1. **Lock Tables** (Line 24)
   ```php
   mysqli_query($conn,'LOCK TABLES classtopics WRITE, classtopics_new WRITE, classtopics_staff WRITE, classschedules WRITE;');
   ```

2. **Extract Input Data** (Lines 6-19)
   ```php
   $bno = $bmod[0];              // Batch Number
   $module = trim($bmod[1]);      // Module Code
   $activity = $_POST['selectActivity'];  // Activity Type
   $topic = addslashes(trim($_POST['classTopic']));  // Class Name
   $classDate = $_POST['classDate'];
   $classStTime = $classDate . ' ' . $_POST['classStTime'];
   $classEnTime = $classDate . ' ' . $_POST['classEnTime'];
   $lab = $labArray[0];           // Lab Code
   $staff = $staffDepts[1];       // Lecturer Staff ID
   $addStaff = $_POST["staffID"]; // User who added
   ```

3. **Insert into classtopics** (Lines 32-40)
   ```php
   $sql = "INSERT INTO classtopics (b_no, m_code, activity, class_topic, class_group, dep_code, staff) 
           VALUES ( $bno, '$module', '$activity', '$topic', 'All', $dep, $staff);";
   mysqli_query($conn, $sql);
   $submitID = mysqli_insert_id($conn);  // Get new topic_id
   ```

4. **Insert into classtopics_new** (Parallel table) (Lines 42-44)
   ```php
   $sqlNew = "INSERT INTO classtopics_new (topic_id, b_no, m_code, activity, class_topic, dep_code) 
              VALUES ($submitID, $bno, '$module', '$activity', '$topic', $dep);";
   mysqli_query($conn, $sqlNew);
   ```

5. **Insert Additional Lecturers** (Lines 46-53)
   ```php
   foreach ($selectedLecturers as $lecturerValue) {
       $lecturerStaff = $lecturerParts[1];
       $sqlStaff = "INSERT INTO classtopics_staff (topic_id, class_group, staff) 
                    VALUES ($submitID, 'All', $lecturerStaff);";
       mysqli_query($conn, $sqlStaff);
   }
   ```

6. **Insert into classschedules** (Lines 56-60)
   ```php
   $sql = "INSERT INTO classschedules (class_topic_id, class_start, class_end, lab_code, class_status, add_staff, class_remark) 
           VALUES ($submitID, '$classStTime', '$classEnTime', $lab, 1, '$addStaff', '$classremark');";
           //                                                           ↑ STATUS = 1 (ACTIVE)
   mysqli_query($conn, $sql);
   ```

7. **Unlock Tables** (Line 72)
   ```php
   mysqli_query($conn,'UNLOCK TABLES;');
   ```

---

## 6. FILTERING LOGIC - WHY CLASSES MAY NOT APPEAR

### Critical Issue: Status Parameter Dependency

The main class list query in `load_timetable.php` uses:
```sql
WHERE class_status = $status ...
```

**The $status parameter is passed as a GET parameter:**
```
load_timetable.php?custom_param1=33&custom_param2=ALNU1&custom_param3=1
                                                                       ↑
                                                                   STATUS PARAM
```

### Possible Reasons Newly Added Classes Don't Appear:

1. **Wrong Status Parameter Passed**
   - If `$status = 0` instead of `1`, no active classes will show
   - If `$status = NULL`, the WHERE clause fails

2. **Activity Summary Only Shows Status = 1**
   - `get_val.php` (case 3) hardcodes `class_status = 1`
   - This is ALWAYS active classes only

3. **Module Not in Correct Progress Stage**
   - Module must have `ttprogress = 2` (Ready) to show in confirm list
   - Classes depend on module being in correct stage

4. **Class Status May Be Updated Elsewhere**
   - Check publish_tentative.php for status updates
   - Check timetable.php for status change logic

---

## 7. RELATED QUERIES & FILTERS

### Registry Class Query (registry_class.php - Lines 51-59)
```sql
-- SHOWS BATCH-MODULES FOR LOGGED-IN USER
SELECT CONCAT(b_no, '-', m.m_code) as val, b_no, CONCAT(m.m_name, ' ', m.m_phase) as val2  
FROM `batchmodule` 
JOIN module m ON m.m_code = batchmodule.m_code
WHERE cordi IN (SELECT st_id FROM staff WHERE username='$user')
   OR cordi2 IN (SELECT st_id FROM staff WHERE username='$user')
   OR ttmng1 IN (SELECT st_id FROM staff WHERE username='$user')
   OR ttmng2 IN (SELECT st_id FROM staff WHERE username='$user')
   OR ttmng3 IN (SELECT st_id FROM staff WHERE username='$user')
   OR ttmng4 IN (SELECT st_id FROM staff WHERE username='$user')
   OR ttmng5 IN (SELECT st_id FROM staff WHERE username='$user')
   OR ttmng6 IN (SELECT st_id FROM staff WHERE username='$user');
```

### Batch Module Start/End Query (get_val.php - case 2)
```sql
SELECT `st_dt`, `en_dt` FROM `batchmodule` 
WHERE `b_no` = $bno AND `m_code` = '$mod';
```

### Staff Availability Check (get_val.php - case 6)
```sql
SELECT DISTINCT CONCAT(dep_code,'-',staff) as staff  
FROM classschedules
JOIN classtopics ON class_topic_id = topic_id
WHERE (class_start < '$classStTime' AND class_end > '$classStTime')
   OR (class_start <= '$classStTime' AND class_end >= '$classStTime');
```

---

## 8. DATA FLOW DIAGRAM

```
USER ADDS CLASS
    ↓
add_class.php (Frontend Form)
    ↓
POST to assets/scripts/backend/add_class.php
    ↓
INSERT INTO classtopics (topic_id, b_no, m_code, activity, class_topic, ...)
INSERT INTO classtopics_new (...)
INSERT INTO classschedules (..., class_status = 1, ...)
INSERT INTO classtopics_staff (topic_id, staff, ...)
    ↓
CLASS CREATED WITH STATUS = 1 (ACTIVE)
    ↓
[DISPLAY LOGIC]
    ↓
GET request to load_timetable.php?custom_param3=$status
    ↓
Query: WHERE class_status = $status AND b_no = ... AND m_code = ...
    ↓
IF $status = 1 → CLASS APPEARS
IF $status ≠ 1 → CLASS HIDDEN
    ↓
RENDERED IN CALENDAR/TABLE VIEW
```

---

## 9. KEY TAKEAWAYS

### For Debugging "Newly Added Classes Not Showing":

1. **Check class_status value** in classschedules table
   ```sql
   SELECT class_id, class_status FROM classschedules 
   WHERE class_topic_id = (SELECT topic_id FROM classtopics WHERE class_topic = 'YOUR_CLASS');
   ```

2. **Verify $status parameter** being passed to load_timetable.php
   - Look at JavaScript calling the function
   - Check if status = 1 is being passed

3. **Check module progress stage** (batchmodule.ttprogress)
   ```sql
   SELECT ttprogress FROM batchmodule WHERE b_no = 33 AND m_code = 'ALNU1';
   ```

4. **Look for UPDATE statements** that might change class_status
   - Search for `UPDATE classschedules SET class_status`
   - Check publish_tentative.php, confirm_module.php

5. **Verify user permissions**
   - User must be coordinator, ttmng, or appropriate staff
   - Check batchmodule assignments

---

## 10. IMPORTANT NOTES

- **Tables Used Consistently:**
  - `classtopics` - Define class
  - `classschedules` - Schedule + STATUS
  - `classtopics_staff` - Additional lecturers
  - `batchmodule` - Module workflow

- **Status is the KEY FILTER** - All list queries filter on class_status

- **Newly inserted classes get status = 1** automatically

- **Module must be in correct progress stage** for classes to be relevant

- **User permissions** are checked at module level (coordinator/ttmng)

---

## File Summary Table

| File | Purpose | Key Query/Function |
|------|---------|-------------------|
| registry_class.php | Batch-Module list | Lists managed batch-modules |
| add_class.php | Add class form | Frontend for class creation |
| add_class.php (backend) | Insert class | INSERT into classtopics, classschedules |
| get_val.php | Dynamic form data | Load activities, dates, staff (case 3 = activity summary) |
| load_timetable.php | Display class list | MAIN QUERY with status filter |
| confirm_module.php | Module confirmation | Shows modules ready to confirm |
| timetable.php | Calendar view | Display classes with status checks |
| publish_tentative.php | Publish schedule | May update class status |

