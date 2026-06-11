# Quick Reference - Timetable Management SQL Queries

## 1. WHERE TIMETABLE MANAGEMENT LIST IS LOADED/DISPLAYED

**File:** `web/pages/assets/scripts/backend/load_timetable.php`

This is the main query that fetches classes for display in timetable management.

---

## 2. SQL QUERY THAT FETCHES THE CLASS LIST

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
WHERE class_status = $status AND b_no = $bno AND TRIM(m_code) = TRIM('$mod')
GROUP BY class_id, class_topic_id
ORDER BY class_id;
```

---

## 3. FILTERING & STATUS CONDITIONS

### Main Filter (Load Timetable):
- **`class_status = $status`** - Status parameter from URL
- **`b_no = $bno`** - Batch number
- **`m_code = '$mod'`** - Module code

### Activity Summary (Used in Class Management):
```sql
SELECT a_id, a_name, activity, count(*) AS ct 
FROM classschedules 
JOIN classtopics ON class_topic_id = topic_id 
JOIN activity ON activity = a_id 
WHERE b_no = $bno AND m_code = '$mod' AND class_status = 1
GROUP BY activity ORDER BY a_name;
```

**HARDCODED TO STATUS = 1** - Only shows active classes

### Module Confirmation Filters:
- `comp_on = '0000-00-00 00:00:00' OR comp_on IS NULL` - NOT composed
- `conf_on = '0000-00-00 00:00:00' OR conf_on IS NULL` - NOT confirmed
- `ttprogress = 2` - Progress stage 2 (Ready)
- User must be coordinator: `cordi IN (SELECT st_id FROM staff WHERE username='$user')`

---

## 4. WHERE NEWLY ADDED CLASSES ARE INSERTED

**File:** `web/pages/assets/scripts/backend/add_class.php`

### Insertion Process:

1. **Create Class Topic:**
   ```sql
   INSERT INTO classtopics (b_no, m_code, activity, class_topic, class_group, dep_code, staff) 
   VALUES ($bno, '$module', '$activity', '$topic', 'All', $dep, $staff);
   ```

2. **Create New Class Record:**
   ```sql
   INSERT INTO classtopics_new (topic_id, b_no, m_code, activity, class_topic, dep_code) 
   VALUES ($submitID, $bno, '$module', '$activity', '$topic', $dep);
   ```

3. **Create Class Schedule with STATUS = 1:**
   ```sql
   INSERT INTO classschedules (class_topic_id, class_start, class_end, lab_code, class_status, add_staff, class_remark) 
   VALUES ($submitID, '$classStTime', '$classEnTime', $lab, 1, '$addStaff', '$classremark');
   ```
   **Note:** `class_status` is set to **1 (ACTIVE)** on creation

4. **Add Additional Lecturers:**
   ```sql
   INSERT INTO classtopics_staff (topic_id, class_group, staff) 
   VALUES ($submitID, 'All', $lecturerStaff);
   ```

---

## 5. DATA FLOW - FROM CREATION TO DISPLAY

```
1. User Adds Class (add_class.php form)
                ↓
2. Backend inserts into:
   - classtopics
   - classtopics_new
   - classschedules (class_status = 1)
   - classtopics_staff
                ↓
3. Class created with status = 1 (ACTIVE)
                ↓
4. Display Query (load_timetable.php):
   WHERE class_status = $status
                ↓
5. IF $status = 1 → CLASS SHOWS
   IF $status ≠ 1 → CLASS HIDDEN
```

---

## 6. KEY TABLES & FIELDS

### classschedules (Most Important)
- `class_id` - Schedule ID
- `class_topic_id` - Reference to classtopics.topic_id
- `class_start` - Start datetime
- `class_end` - End datetime
- `class_status` - **STATUS FIELD (1=Active, 0=Hidden)**
- `lab_code` - Lab reservation
- `add_staff` - Who added it
- `class_remark` - Remarks

### classtopics
- `topic_id` - Class topic ID
- `b_no` - Batch number
- `m_code` - Module code
- `activity` - Activity type ID
- `class_topic` - Class name/title
- `class_group` - Group designation
- `dep_code` - Department
- `staff` - Primary lecturer

### batchmodule
- `b_no` - Batch number
- `m_code` - Module code
- `ttprogress` - Progress stage (1=Planning, 2=Ready, 3=Published, 4=Complete)
- `st_dt` - Module start date
- `en_dt` - Module end date
- `comp_on` - Composition completion timestamp
- `conf_on` - Confirmation timestamp

---

## 7. KEY FILES TO CHECK FOR DEBUGGING

| Issue | File to Check | Query/Field |
|-------|---------------|------------|
| Class doesn't appear in list | load_timetable.php | `class_status` parameter |
| Activity summary wrong count | get_val.php (case 3) | WHERE `class_status = 1` |
| Module not showing | confirm_module.php | `ttprogress = 2` |
| New class status | classschedules | `class_status` value |
| User permission | registry_class.php | Coordinator/ttmng check |
| Module dates | get_val.php (case 2) | `st_dt`, `en_dt` fields |

---

## 8. CRITICAL DISCOVERY: STATUS IS THE KEY

**The main class list visibility depends on:**

1. **`class_status` field in classschedules table**
   - Value 1 = Visible
   - Other values = Hidden

2. **`$status` parameter passed to load_timetable.php**
   - Controls which classes are displayed
   - Must match class_status value

3. **Activity Summary hardcoded to status = 1**
   - Only shows classes with class_status = 1
   - This determines what appears in class management

4. **Module progress stage (ttprogress)**
   - Must be in correct stage for classes to be relevant
   - `ttprogress = 2` for ready/tentative classes

---

## 9. QUICK TEST QUERIES

### Check if class was inserted correctly:
```sql
SELECT class_id, class_topic_id, class_status, class_start, class_end 
FROM classschedules 
WHERE class_topic_id = (SELECT topic_id FROM classtopics WHERE class_topic = 'YOUR_CLASS_NAME');
```

### Check activity summary (what will appear in class management):
```sql
SELECT a_id, a_name, COUNT(*) AS ct 
FROM classschedules 
JOIN classtopics ON class_topic_id = topic_id 
JOIN activity ON activity = a_id 
WHERE b_no = 33 AND m_code = 'ALNU1' AND class_status = 1
GROUP BY activity;
```

### Check module stage:
```sql
SELECT b_no, m_code, ttprogress, st_dt, en_dt, comp_on, conf_on 
FROM batchmodule 
WHERE b_no = 33 AND m_code = 'ALNU1';
```

### Check if class_status was updated elsewhere:
```sql
SELECT COUNT(*), class_status 
FROM classschedules 
WHERE b_no = 33 AND m_code = 'ALNU1' 
GROUP BY class_status;
```

---

## 10. SUMMARY

**Main Backend File:** `web/pages/assets/scripts/backend/load_timetable.php`

**Key SQL Filter:** `WHERE class_status = $status AND b_no = $bno AND TRIM(m_code) = TRIM('$mod')`

**New Classes Inserted With:** `class_status = 1`

**If Classes Don't Show:** Check if $status parameter = 1 and class_status field = 1

**Related Files to Review:**
- add_class.php - Class creation
- get_val.php - Data loading (case 3 for activity summary)
- publish_tentative.php - May update status
- confirm_module.php - Module confirmation logic
