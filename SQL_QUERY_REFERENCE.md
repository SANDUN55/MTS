# SQL Query Reference - Timetable Management System

## ALL IMPORTANT QUERIES BY PURPOSE

### 1. CLASS LIST DISPLAY QUERIES

#### 1A. Load Timetable - Main Query (load_timetable.php)
**Purpose:** Display classes in timetable calendar
**Location:** `web/pages/assets/scripts/backend/load_timetable.php`

```sql
-- Part 1: Cross-departmental classes
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
WHERE class_status = 1 AND b_no = 33 AND TRIM(m_code) = TRIM('ALNU1')
GROUP BY class_id, class_topic_id
ORDER BY class_id;

-- Part 2: Same-department classes (UNION)
SELECT 
    CONCAT(class_id, '-', class_topic_id) as class_reserve_id, 
    class_topic, 
    class_start, 
    class_end, 
    classtopics_staff.class_group as class_group,
    GROUP_CONCAT(CONCAT(staff.t_nm, '. ', staff.firstname, ' ', staff.surname) SEPARATOR ', ') AS stVAL,
    div_nm, 
    a_name, 
    a_type, 
    lab_nm, 
    class_remark, 
    COALESCE(div_color, '#2E7D32') as div_color
FROM classschedules
JOIN classtopics ON class_topic_id = topic_id
LEFT JOIN classtopics_staff ON classtopics_staff.topic_id = classtopics.topic_id
LEFT JOIN staff ON classtopics_staff.staff = staff.st_id
LEFT JOIN divisions ON div_id = dep_code 
JOIN activity ON a_id = activity
LEFT JOIN lab on lab.lab_code = classschedules.lab_code
WHERE classtopics.dep_code = classtopics.staff AND class_status = 1 AND b_no = 33 AND TRIM(m_code) = TRIM('ALNU1')
GROUP BY class_id, class_topic_id
ORDER BY class_id;
```

**Parameters:**
- `class_status = 1` - Filter active classes
- `b_no = 33` - Batch number
- `m_code = 'ALNU1'` - Module code

**Returns:** Classes with schedule, lecturers, activity type, lab, remarks

---

#### 1B. Lab Timetable Load (Lab-load_timetable.php)
```sql
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
WHERE class_status = 1 AND lab_code = $labCode
GROUP BY class_id, class_topic_id
ORDER BY class_start;
```

---

### 2. MODULE MANAGEMENT QUERIES

#### 2A. Registry Class List (registry_class.php)
**Purpose:** Show batch-module combinations managed by logged-in user
```sql
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
   OR ttmng6 IN (SELECT st_id FROM staff WHERE username='$user')
ORDER BY b_no DESC;
```

**Returns:** Batch-module combinations where user is coordinator or timetable manager

---

#### 2B. Confirm Module List (confirm_module.php)
**Purpose:** Show modules ready for timetable confirmation
```sql
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

**Filters:**
- `comp_on = '0000-00-00 00:00:00' OR comp_on IS NULL` - Not composed
- `conf_on = '0000-00-00 00:00:00' OR conf_on IS NULL` - Not confirmed
- `ttprogress = 2` - At progress stage 2 (Ready/Tentative)
- `cordi` or `cordi2` must be current user

---

### 3. ACTIVITY & CLASS COUNT QUERIES

#### 3A. Activity Summary (get_val.php - Case 3)
**Purpose:** Count classes by activity type for a module
```sql
SELECT a_id, a_name, activity, count(*) AS ct 
FROM classschedules 
JOIN classtopics ON class_topic_id = topic_id 
JOIN activity ON activity = a_id 
WHERE b_no = $bno 
  AND m_code = '$mod' 
  AND class_status = 1
GROUP BY activity 
ORDER BY a_name;
```

**Important:** Hardcoded to `class_status = 1` - Only active classes

---

#### 3B. Class Count by Activity & Group (get_val.php - Case 5)
```sql
SELECT activity, COUNT(DISTINCT classtopics.topic_id) as cnt 
FROM classschedules 
JOIN classtopics ON class_topic_id = topic_id
WHERE b_no = $bno 
  AND m_code = '$mod'
  AND class_status = 1
GROUP BY activity;
```

---

### 4. MODULE DATE QUERIES

#### 4A. Get Module Start & End Dates (get_val.php - Case 2)
**Purpose:** Retrieve module schedule boundaries
```sql
SELECT `st_dt`, `en_dt` 
FROM `batchmodule` 
WHERE `b_no` = $bno 
  AND `m_code` = '$mod';
```

**Returns:** Module start date and end date

---

### 5. CLASS INSERTION QUERIES

#### 5A. Insert Class Topic (add_class.php)
```sql
INSERT INTO classtopics (b_no, m_code, activity, class_topic, class_group, dep_code, staff) 
VALUES ($bno, '$module', '$activity', '$topic', 'All', $dep, $staff);
```

**Result:** Returns `topic_id` via `mysqli_insert_id()`

---

#### 5B. Insert into New Classes Table (add_class.php)
```sql
INSERT INTO classtopics_new (topic_id, b_no, m_code, activity, class_topic, dep_code) 
VALUES ($submitID, $bno, '$module', '$activity', '$topic', $dep);
```

---

#### 5C. Insert Class Schedule with STATUS (add_class.php)
```sql
INSERT INTO classschedules 
    (class_topic_id, class_start, class_end, lab_code, class_status, add_staff, class_remark) 
VALUES 
    ($submitID, '$classStTime', '$classEnTime', $lab, 1, '$addStaff', '$classremark');
```

**CRITICAL:** `class_status = 1` (Active/Visible)

---

#### 5D. Insert Additional Lecturers (add_class.php)
```sql
INSERT INTO classtopics_staff (topic_id, class_group, staff) 
VALUES ($submitID, 'All', $lecturerStaff);
```

---

### 6. CLASS STATUS & CONFLICT CHECK QUERIES

#### 6A. Disabled/Cancelled Classes (timetable.php)
**Purpose:** Load classes with status 0 or 3 (disabled/postponed)
```sql
SELECT class_topic, class_group, a_name, CONCAT(class_id, '-', class_topic_id) as tids 
FROM classschedules s
JOIN classtopics t ON t.topic_id = s.class_topic_id
LEFT JOIN activity ON activity = a_id
WHERE b_no = $batch 
  AND m_code = '$mod'
  AND class_status IN (0, 3);
```

**Status Codes:**
- 0 = Cancelled
- 3 = Postponed/Draft

---

#### 6B. Lecturer Conflict Check (get_val.php - Case 6)
**Purpose:** Find staff that are already scheduled at a given time
```sql
SELECT DISTINCT CONCAT(dep_code,'-',staff) as staff  
FROM classschedules
JOIN classtopics ON class_topic_id = topic_id
WHERE (class_start < '$classStTime' AND class_end > '$classStTime')
   OR (class_start <= '$classStTime' AND class_end >= '$classStTime')
  AND class_status = 1;
```

---

### 7. DIAGNOSTIC QUERIES

#### 7A. Check All Statuses for a Module
```sql
SELECT class_status, COUNT(*) as cnt 
FROM classschedules 
WHERE b_no = 33 
  AND m_code = 'ALNU1'
GROUP BY class_status;
```

**Output:**
- Shows count of classes in each status
- Helps identify where classes are distributed

---

#### 7B. Check Module Progress
```sql
SELECT b_no, m_code, ttprogress, st_dt, en_dt, comp_on, conf_on
FROM batchmodule
WHERE b_no = 33 
  AND m_code = 'ALNU1';
```

**ttprogress Values:**
- 1 = Planning
- 2 = Ready/Tentative
- 3 = Published
- 4 = Completed

---

#### 7C. Verify New Class Insertion
```sql
SELECT 
    t.topic_id, 
    t.class_topic,
    c.class_id,
    c.class_status,
    c.class_start,
    c.class_end
FROM classtopics t
JOIN classschedules c ON c.class_topic_id = t.topic_id
WHERE t.b_no = 33 
  AND t.m_code = 'ALNU1'
ORDER BY c.class_id DESC
LIMIT 5;
```

---

#### 7D. Check Class with All Details
```sql
SELECT 
    c.class_id,
    c.class_status,
    t.class_topic,
    t.activity,
    GROUP_CONCAT(CONCAT(s.t_nm, '. ', s.firstname) SEPARATOR ', ') as lecturers,
    c.class_start,
    c.class_end,
    a.a_name,
    l.lab_nm,
    c.add_staff,
    c.class_remark
FROM classschedules c
JOIN classtopics t ON c.class_topic_id = t.topic_id
LEFT JOIN classtopics_staff cs ON t.topic_id = cs.topic_id
LEFT JOIN staff s ON cs.staff = s.st_id
LEFT JOIN activity a ON t.activity = a.a_id
LEFT JOIN lab l ON c.lab_code = l.lab_code
WHERE t.b_no = 33 
  AND t.m_code = 'ALNU1'
GROUP BY c.class_id;
```

---

## STATUS VALUE MAPPING

| Status | Meaning | Visible in List | File |
|--------|---------|-----------------|------|
| 1 | Active | YES | load_timetable.php |
| 0 | Cancelled | NO | timetable.php (disabled list) |
| 2 | Rescheduled | NO | (check update queries) |
| 3 | Postponed | NO | timetable.php (disabled list) |

---

## TABLE RELATIONSHIPS

```
batchmodule (b_no, m_code)
     ↓
classtopics (b_no, m_code, topic_id)
     ↓
classschedules (class_topic_id → topic_id) ← **class_status here**
     ↓
classtopics_staff (topic_id → topic_id) ← Additional lecturers
```

---

## EXECUTION CONTEXT

**Parameters that get passed around:**

```javascript
// From frontend to load_timetable.php
load_timetable.php?custom_param1=33&custom_param2=ALNU1&custom_param3=1
                   (b_no)              (m_code)          (status)

// PHP extracts as:
$bno = $_GET['custom_param1'];       // 33
$mod = $_GET['custom_param2'];       // 'ALNU1'
$status = $_GET['custom_param3'];    // 1
```

---

## PERFORMANCE NOTES

- **load_timetable.php:** Uses UNION for 2 queries (dep_code = staff vs. dep_code ≠ staff)
- **GROUP_CONCAT:** Aggregates multiple lecturers into single field
- **Key Indexes Should Be:** 
  - classschedules (class_status, b_no, m_code)
  - classtopics (b_no, m_code, topic_id)
  - classtopics_staff (topic_id)
  - batchmodule (b_no, m_code, ttprogress)

---

## COMMON FILTERS

```sql
-- Active classes only
WHERE class_status = 1

-- For a specific batch-module
WHERE b_no = $bno AND m_code = '$mod'

-- User is coordinator
WHERE cordi IN (SELECT st_id FROM staff WHERE username='$user')

-- Module not yet confirmed
WHERE conf_on = '0000-00-00 00:00:00' OR conf_on IS NULL

-- Time conflict check
WHERE class_start < $endTime AND class_end > $startTime
```
