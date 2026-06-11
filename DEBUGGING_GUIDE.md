# Debugging Guide - "Newly Added Classes Not Appearing in List"

## THE PROBLEM
Newly added classes don't appear in the timetable management list, even though they were successfully inserted into the database.

---

## ROOT CAUSE ANALYSIS

### 🔴 Primary Cause: Status Filter Mismatch

The class display query filters by `class_status` field:
```sql
WHERE class_status = $status AND b_no = $bno AND m_code = '$mod'
```

**When newly added classes don't appear, it's because:**

1. **New classes are inserted with `class_status = 1`** (ACTIVE)
2. **The display query filters by `$status` parameter**
3. **If `$status ≠ 1`, the class won't appear**

### Example:
```sql
-- Class was created with:
INSERT INTO classschedules (..., class_status = 1, ...)

-- But display query runs with:
SELECT * FROM classschedules 
WHERE class_status = 0  ← ❌ DOESN'T MATCH!

-- Result: Class not displayed
```

---

## STEP-BY-STEP DEBUGGING

### Step 1: Verify Class Was Inserted
```sql
-- Check if class exists in database
SELECT c.class_id, c.class_status, t.class_topic, c.class_start, c.class_end
FROM classschedules c
JOIN classtopics t ON c.class_topic_id = t.topic_id
WHERE t.b_no = 33 AND t.m_code = 'ALNU1'
ORDER BY c.class_id DESC
LIMIT 1;
```

**Expected Result:**
```
class_id | class_status | class_topic | class_start         | class_end
---------|--------------|-------------|---------------------|-------------------
    125  |      1       | New Class   | 2024-01-15 10:00:00 | 2024-01-15 11:30:00
```

**If NO results:** Class insertion failed - check add_class.php backend

**If status ≠ 1:** Class exists but has wrong status - check Step 3

---

### Step 2: Verify Activity Summary Shows New Class

Activity summary uses hardcoded `class_status = 1`:
```sql
-- From get_val.php (case 3)
SELECT a_id, a_name, activity, count(*) AS ct 
FROM classschedules 
JOIN classtopics ON class_topic_id = topic_id 
JOIN activity ON activity = a_id 
WHERE b_no = 33 AND m_code = 'ALNU1' AND class_status = 1
GROUP BY activity;
```

**Test:**
1. In registry_class.php, select your batch-module
2. Look at activity list - should show count of classes
3. If new class was added, count should increase

**If count unchanged:**
- New class may have `class_status ≠ 1`
- Or class has wrong batch/module

---

### Step 3: Check class_status Value Distribution

```sql
-- See how many classes at each status
SELECT class_status, COUNT(*) as cnt 
FROM classschedules c
JOIN classtopics t ON c.class_topic_id = t.topic_id
WHERE t.b_no = 33 AND t.m_code = 'ALNU1'
GROUP BY class_status;
```

**Expected:**
```
class_status | cnt
-------------|-----
      1      |  45  ← Most should be here
      0      |   2  ← Some cancelled
      3      |   1  ← Maybe postponed
```

**If most classes have status ≠ 1:** Something is updating class_status after insertion

---

### Step 4: Check Backend Insertion Code

**File:** `web/pages/assets/scripts/backend/add_class.php`

**Look for this line:**
```php
$sql = "INSERT INTO classschedules (..., class_status, ...) 
        VALUES (..., 1, ...)";
```

**Verify:**
- ✓ `class_status` is set to `1`
- ✓ No logic changes it afterward
- ✓ No UPDATE statements after INSERT

**Search for:**
```php
UPDATE classschedules SET class_status
```

If found, this might be changing new classes!

---

### Step 5: Check Display Query Parameters

**File:** `web/pages/assets/scripts/backend/load_timetable.php`

**Look for:**
```php
$status = $_GET['custom_param3'];
```

**Check what value is being passed:**
```javascript
// In timetable.js, look for load_timetable.php call:
url: 'backend/load_timetable.php?custom_param1=33&custom_param2=ALNU1&custom_param3=1'
                                                                                    ↑
                                                                                 STATUS
```

**Test manually:**
1. Open browser developer tools (F12)
2. Go to timetable page
3. Open Network tab
4. Look for `load_timetable.php` request
5. Check URL parameters
6. Verify `custom_param3=1`

**If `custom_param3 ≠ 1`:**
- This is why new classes don't show!
- Fix: Change status parameter in JavaScript

---

### Step 6: Check if Status is Updated Later

Search entire project for status update:
```bash
# In terminal, search for UPDATE statements
grep -r "UPDATE classschedules" web/pages/
```

**Common places status might be changed:**
- publish_tentative.php
- Any "reschedule" or "cancel" function
- Workflow progression logic

**Example problem:**
```php
// In publish_tentative.php
UPDATE classschedules SET class_status = 0 WHERE status != 'published'
// This might hide all unpublished classes!
```

---

### Step 7: Test Direct Query

Run the exact query that load_timetable.php uses:

```sql
SELECT 
    CONCAT(class_id, '-', class_topic_id) as class_reserve_id, 
    class_topic, 
    class_start, 
    class_end, 
    GROUP_CONCAT(CONCAT(staff.t_nm, '. ', staff.firstname) SEPARATOR ', ') AS stVAL,
    a_name, 
    lab_nm, 
    class_remark
FROM classschedules
JOIN classtopics ON class_topic_id = topic_id
LEFT JOIN classtopics_staff ON classtopics_staff.topic_id = classtopics.topic_id
LEFT JOIN staff ON classtopics_staff.staff = staff.st_id
JOIN activity ON a_id = activity
LEFT JOIN lab ON lab.lab_code = classschedules.lab_code
WHERE class_status = 1 AND b_no = 33 AND TRIM(m_code) = TRIM('ALNU1')
GROUP BY class_id, class_topic_id
ORDER BY class_id;
```

**If new class appears here:**
- Problem is with $status parameter (Step 5)
- Or JavaScript isn't calling the right endpoint

**If new class doesn't appear:**
- Problem is with class_status value (Step 3)

---

## QUICK DIAGNOSIS FLOWCHART

```
NEWLY ADDED CLASS NOT APPEARING
    ↓
1. Is it in classschedules table?
    ├─ NO  → Check add_class.php backend (INSERT error)
    │
    └─ YES → Go to Step 2

2. What is class_status value?
    ├─ 1   → Go to Step 3
    │
    └─ ≠1  → Status is wrong (check Step 6 for updates)

3. Does it appear in activity summary? (get_val.php)
    ├─ NO  → Batch/module wrong (check t.b_no, t.m_code)
    │
    └─ YES → Go to Step 4

4. Check display query parameters
    ├─ custom_param3 = 1  → Database query works
    │                        Check Step 5 (JavaScript)
    │
    └─ custom_param3 ≠ 1  → FIX: Change status parameter

5. Does direct SQL query show class?
    ├─ YES → JavaScript not calling correctly
    │        Check timetable.js AJAX parameters
    │
    └─ NO  → Database filtering out class
             Check WHERE conditions
```

---

## COMMON ISSUES & SOLUTIONS

### Issue 1: Status Parameter is 0
**Symptom:** No classes appear at all
**Cause:** `custom_param3=0` passed to load_timetable.php
**Solution:** Change to `custom_param3=1` in JavaScript

**File to check:** `timetable.js`
```javascript
// Change from:
'custom_param3': 0

// To:
'custom_param3': 1
```

---

### Issue 2: Class Status Changed After Insert
**Symptom:** Class appears briefly, then disappears
**Cause:** Something is updating `class_status` to 0
**Solution:** Find the UPDATE statement

**Search in:**
- publish_tentative.php
- confirm_module.php
- Any workflow or approval logic

---

### Issue 3: Wrong Batch or Module
**Symptom:** Added to wrong batch-module
**Cause:** Form selected wrong batch/module
**Solution:** Check add_class.php form values

**Verify:**
```sql
-- Check the batch/module you added to
SELECT b_no, m_code FROM classtopics 
WHERE class_topic = 'YOUR_CLASS_NAME';
```

---

### Issue 4: Module Not at Right Progress Stage
**Symptom:** Class exists but doesn't show in confirm list
**Cause:** `batchmodule.ttprogress ≠ 2`
**Solution:** Check module progress

**Query:**
```sql
SELECT ttprogress FROM batchmodule 
WHERE b_no = 33 AND m_code = 'ALNU1';
```

---

### Issue 5: User Permission Issue
**Symptom:** Class shows in one view but not another
**Cause:** User isn't coordinator/ttmng for that module
**Solution:** Add user to batchmodule assignments

---

## TEST CHECKLIST

- [ ] Class exists in classschedules table
- [ ] class_status = 1
- [ ] Batch number correct (b_no)
- [ ] Module code correct (m_code)
- [ ] Activity summary shows count
- [ ] Direct SQL query returns class
- [ ] $status parameter = 1 in load_timetable.php
- [ ] AJAX call passes custom_param3=1
- [ ] No UPDATE statements changing status
- [ ] Module is at ttprogress = 2
- [ ] User is coordinator/ttmng

---

## QUICK FIX ATTEMPTS

### Fix 1: Manual Database Update
If class_status is wrong:
```sql
UPDATE classschedules SET class_status = 1
WHERE class_id = 125;
```

### Fix 2: Force Status Parameter
In timetable.js, hardcode status=1:
```javascript
// Before fixing root cause
data: {
    custom_param3: 1  ← Force to 1
}
```

### Fix 3: Clear and Re-add
1. Delete the class via SQL
2. Add it again via UI
3. Check if it appears

---

## DEBUGGING HELPERS

### 1. Browser Console - Check AJAX calls
```javascript
// Open F12 → Network tab
// Filter by "load_timetable.php"
// Check URL parameters

// Or in Console:
// Add logging to timetable.js
console.log('Calling load_timetable with status:', customParam3);
```

### 2. Database Logging
Add to load_timetable.php:
```php
$logFile = fopen("debug_load_timetable.txt", "a");
fwrite($logFile, "Status: $status, Batch: $bno, Module: $mod\n");
fwrite($logFile, $query . "\n");
fclose($logFile);
```

### 3. Debug Output
```php
// In load_timetable.php before returning JSON
error_log("Query returned " . count($dataArray) . " classes");
error_log("Status filter: $status");
```

### 4. Check MySQL Error Log
```sql
-- If query fails silently
SELECT * FROM mysql.general_log 
WHERE argument LIKE '%load_timetable%'
ORDER BY event_time DESC LIMIT 10;
```

---

## FINAL VERIFICATION

Once you've made changes, verify with this complete test:

```sql
-- 1. Class exists
SELECT class_id, class_status FROM classschedules 
WHERE class_topic_id = (SELECT topic_id FROM classtopics 
WHERE class_topic = 'TEST_CLASS' AND b_no = 33 AND m_code = 'ALNU1');

-- 2. Activity summary includes it
SELECT COUNT(*) FROM classschedules c
JOIN classtopics t ON c.class_topic_id = t.topic_id
WHERE t.b_no = 33 AND t.m_code = 'ALNU1' AND c.class_status = 1;

-- 3. Main query includes it
SELECT COUNT(*) FROM classschedules c
JOIN classtopics t ON c.class_topic_id = t.topic_id
WHERE class_status = 1 AND b_no = 33 AND TRIM(m_code) = TRIM('ALNU1');
```

All three should return results with count ≥ 1 for new class.

---

## ESCALATION CHECKLIST

If still not working:

1. ✓ Verified class_status = 1 in database
2. ✓ Verified direct SQL query returns it
3. ✓ Verified $status parameter = 1
4. ✓ Checked no UPDATE statements change status
5. ✓ Checked module has ttprogress = 2
6. ✓ Checked user permissions
7. ✓ Cleared browser cache (Ctrl+Shift+Delete)
8. ✓ Restarted Apache/PHP service

**If still failing:** Check server error logs
- Apache error log: `C:\xampp\apache\logs\error.log`
- MySQL error log: `C:\xampp\mysql\data\[hostname].err`
