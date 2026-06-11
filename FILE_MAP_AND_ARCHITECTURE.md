# Timetable Management - File Map & Architecture

## COMPLETE FILE STRUCTURE MAP

```
c:\xampp\htdocs\mts-2026\
├── web/pages/
│   ├── registry_class.php                    [MAIN CLASS REGISTRY]
│   ├── add_class.php                         [CLASS ADD FORM]
│   ├── confirm_module.php                    [MODULE CONFIRMATION]
│   ├── confirm_module_reservations.php       [LAB CONFIRMATION]
│   ├── timetable.php                         [CALENDAR VIEW]
│   ├── timetableMy.php                       [PERSONAL TIMETABLE]
│   ├── publish_tentative.php                 [PUBLISH SCHEDULE]
│   ├── history-module-calendar.php           [HISTORICAL VIEW]
│   ├── import_class.php                      [IMPORT FROM BATCH]
│   ├── init_module.php                       [INIT MODULE]
│   ├── init_module-2chairs.php               [MULTI-CHAIR INIT]
│   ├── import_batchClass.php                 [BATCH IMPORT]
│   ├── navbar-left.php                       [SIDEBAR NAV]
│   ├── header-top.php                        [TOP HEADER]
│   ├── footer.php                            [FOOTER]
│   ├── headtag.php                           [HEAD TAGS]
│   │
│   └── assets/scripts/
│       ├── add_class.js                      [CLASS ADD FORM HANDLER]
│       ├── timetable.js                      [CALENDAR LOGIC]
│       ├── timetableMy-calender.js           [PERSONAL CALENDAR]
│       ├── history-module-calender.js        [HISTORY CALENDAR]
│       ├── main.js                           [MAIN JS]
│       ├── module-calendar.php               [MODULE CALENDAR]
│       │
│       └── backend/
│           ├── add_class.php                 [⭐ CLASS INSERTION]
│           ├── add_class - Copy.php          [BACKUP]
│           ├── add_class - Copy (2).php      [BACKUP]
│           ├── add_class1.php                [ALTERNATIVE INSERT]
│           ├── get_val.php                   [⭐ DATA PROVIDER]
│           ├── load_timetable.php            [⭐ MAIN DISPLAY QUERY]
│           ├── load_timetableMyBg.php        [PERSONAL TIMETABLE LOADER]
│           ├── Lab-load_timetable.php        [LAB TIMETABLE LOADER]
│           ├── database.php                  [⭐ DB CONNECTION]
│           ├── select_val.php                [SELECT HELPERS]
│           └── [other utility scripts]
│
└── [other files]
```

---

## DATA FLOW ARCHITECTURE

### Diagram: From Class Creation to Display

```
┌─────────────────────────────────────────────────────────────────┐
│                      USER CREATES CLASS                         │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│   add_class.php (Frontend Form)                                 │
│   - Select batch-module from registry_class.php                 │
│   - Fill in class details: date, time, lecturer, etc            │
│   - Click Submit                                                │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│   add_class.js (JavaScript Handler)                             │
│   - Validate form using Bootstrap validation                    │
│   - POST to assets/scripts/backend/add_class.php               │
│   - Pass: batch, module, topic, time, lecturer                 │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│   ⭐ assets/scripts/backend/add_class.php (Backend)             │
│   LOCK TABLES                                                   │
│   INSERT INTO classtopics (topic_id, class...)                 │
│   INSERT INTO classtopics_new (...)                            │
│   INSERT INTO classschedules (..., class_status=1, ...)   ←─┐  │
│   INSERT INTO classtopics_staff (topic_id, staff)           │  │
│   UNLOCK TABLES                                               │  │
│   Result: New class created with status=1 (ACTIVE)          │  │
└─────────────────────────────────────────────────────────────────┘
                              ↓
                   [DATABASE: classschedules]
                   class_status = 1 (ACTIVE)
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│   timetable.php / calendar view (Display)                       │
│   - User views timetable                                        │
│   - Load classes via JavaScript                                 │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│   timetable.js (JavaScript)                                     │
│   - Call fullCalendar library                                   │
│   - AJAX fetch: load_timetable.php                              │
│   - Pass: batch, module, status=1                               │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│   ⭐ assets/scripts/backend/load_timetable.php (Query)          │
│   SELECT * FROM classschedules                                  │
│   JOIN classtopics, classtopics_staff, staff, activity, lab     │
│   WHERE class_status = 1 AND b_no = ? AND m_code = ?          │
│   UNION (same-department classes)                               │
│   GROUP BY class_id                                             │
│   ORDER BY class_id                                             │
└─────────────────────────────────────────────────────────────────┘
                              ↓
                   [DATABASE QUERY RESULT]
                   All classes with status=1
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│   timetable.js (Render)                                         │
│   - Receive JSON array of classes                               │
│   - Create calendar events                                      │
│   - Assign colors by department (div_color)                     │
│   - Display with lecturer names, activity, lab, remarks         │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│            ✓ CLASS APPEARS IN TIMETABLE CALENDAR                 │
└─────────────────────────────────────────────────────────────────┘
```

---

## CRITICAL FILE RELATIONSHIPS

### File: registry_class.php
**Purpose:** Main entry point - list of batch-modules
**Connects To:**
- database.php (DB connection)
- navbar-left.php (sidebar)
- header-top.php (header)
- SQL: SELECT batch-modules where user is coordinator/ttmng

---

### File: add_class.php
**Purpose:** Form to add new class
**Connects To:**
- add_class.js (form submission)
- backend/select_val.php (dropdown values)
- backend/get_val.php (module dates, lecturers)
**Action:** Submits to backend/add_class.php

---

### File: ⭐ assets/scripts/backend/add_class.php
**Purpose:** INSERT new class into database
**Connects To:**
- database.php (DB connection)
- classshedules table (INSERT with class_status=1)
- classtopics table (INSERT)
- classtopics_staff table (INSERT)
**Result:** New class with status=1 (ACTIVE)

---

### File: ⭐ assets/scripts/backend/get_val.php
**Purpose:** Provide dynamic form data
**Contains:** 6+ AJAX handlers for different data requests
**Case 2:** Get module dates
**Case 3:** Get activity summary (class_status=1)
**Case 6:** Check lecturer conflicts

---

### File: ⭐ assets/scripts/backend/load_timetable.php
**Purpose:** MAIN QUERY - Fetch class list for display
**Connects To:**
- database.php (DB connection)
- Used by: timetable.js, timetableMy-calender.js, history-module-calender.js
**Query:** SELECT classes WHERE class_status=$status AND b_no=$bno AND m_code=$mod
**Key Parameter:** $status (determines which classes appear!)

---

### File: timetable.js
**Purpose:** Frontend calendar logic
**Connects To:**
- load_timetable.php (fetch classes)
- load_timetableMyBg.php (personal view)
- fullcalendar library
**Action:** AJAX calls to backend with status parameter

---

### File: confirm_module.php
**Purpose:** Show modules ready for confirmation
**Connects To:**
- database.php (DB connection)
- Uses: batchmodule table
**Filter:** ttprogress=2 (ready), not yet confirmed

---

### File: timetable.php
**Purpose:** Calendar view page
**Connects To:**
- timetable.js (render logic)
- load_timetable.php (get classes)
- Includes status checking logic

---

## CLASS STATUS WORKFLOW

```
┌─────────────────────────────────────┐
│     ADD CLASS (add_class.php)       │
│     INSERT class_status = 1         │
└──────────────┬──────────────────────┘
               ↓
    [CLASS IS ACTIVE/VISIBLE]
               ↓
    ┌─────────┴─────────┐
    ↓                   ↓
CLASS APPEARS IN:   ACTIVITY SUMMARY:
- timetable.js      - get_val.php (case 3)
- load_timetable    - Counts by activity
- Personal view     - Filters by status=1
    ↓                   ↓
[USER CAN SEE IT]    [APPEARS IN FORMS]
    ↓
┌────────────────────────────────────┐
│  May UPDATE status via:            │
│  - publish_tentative.php           │
│  - reschedule logic                │
│  - cancel/postpone actions         │
└────────────────┬───────────────────┘
                 ↓
        [STATUS CHANGED]
                 ↓
    ┌─────────────┴────────────────┐
    ↓                              ↓
Status = 0          Status = 3 (other)
HIDDEN from         DISABLED/POSTPONED
normal views        In separate list
```

---

## DATABASE CONNECTION CHAIN

```
All PHP files
    ↓
include 'database.php'
    ↓
function database_conectivity()
    ↓
$conn = mysqli_connect(...)
    ↓
[Access to database]
```

**Files using database.php:**
- add_class.php
- get_val.php
- load_timetable.php
- load_timetableMyBg.php
- Lab-load_timetable.php
- registry_class.php
- confirm_module.php
- timetable.php
- All backend scripts

---

## FILE PURPOSES QUICK REFERENCE

| File | Type | Purpose |
|------|------|---------|
| registry_class.php | Page | List managed batch-modules |
| add_class.php | Page | Form to add new class |
| timetable.php | Page | Display calendar |
| confirm_module.php | Page | Confirm timetable |
| publish_tentative.php | Page | Publish schedule |
| add_class.js | Script | Handle form submission |
| timetable.js | Script | Calendar event handling |
| timetable-calender.js | Script | Backup calendar |
| ⭐ add_class.php | Backend | INSERT class into DB |
| ⭐ add_class1.php | Backend | Alt INSERT handler |
| ⭐ get_val.php | Backend | Provide form data |
| ⭐ load_timetable.php | Backend | MAIN SELECT query |
| ⭐ load_timetableMyBg.php | Backend | Personal view query |
| ⭐ Lab-load_timetable.php | Backend | Lab view query |
| ⭐ database.php | Backend | DB connection |
| select_val.php | Backend | Helper functions |
| navbar-left.php | Layout | Sidebar navigation |
| header-top.php | Layout | Top header |
| footer.php | Layout | Footer |

---

## TESTING & DEBUGGING PATH

### To trace a newly added class:

1. **Check insertion:**
   ```php
   // In add_class.php backend
   // Look for: INSERT INTO classtopics, classschedules
   // Verify: class_status = 1
   ```

2. **Verify in database:**
   ```sql
   SELECT * FROM classschedules WHERE class_status = 1 ORDER BY class_id DESC LIMIT 1;
   ```

3. **Test display query:**
   ```sql
   -- From load_timetable.php
   SELECT * FROM classschedules 
   WHERE class_status = 1 AND b_no = 33 AND m_code = 'ALNU1';
   ```

4. **Check JavaScript parameters:**
   - Open browser dev console
   - Check AJAX call to load_timetable.php
   - Verify status parameter = 1

5. **Verify calendar rendering:**
   - Check timetable.js for class rendering logic
   - Check fullcalendar event parsing

---

## KEY INSIGHTS

### ✓ Class Insertion Flow
- add_class.php → add_class.js → backend/add_class.php
- Always creates class_status = 1

### ✓ Class Display Flow
- timetable.js → load_timetable.php
- Query filters by: class_status = $status

### ✓ The Status Parameter
- Passed as: `custom_param3` in URL
- Typical value: 1 (for active classes)
- If not 1: Classes won't display

### ✓ Multiple Views
- load_timetable.php (general view)
- load_timetableMyBg.php (personal view)
- Lab-load_timetable.php (lab view)
- All use same filtering logic

### ✓ Status Visibility
- status = 1 → Visible
- status ≠ 1 → Hidden
- Can be changed via update queries

---

## COMMON PATHS THROUGH THE CODE

### User Creates a Class
1. User in registry_class.php
2. Click on batch-module
3. Shown add_class.php form
4. Fill details → Click Add
5. add_class.js validates & POSTs
6. backend/add_class.php inserts with status=1
7. Class created (class_status=1)

### User Views Timetable
1. User in timetable.php
2. JavaScript loads on page
3. timetable.js calls AJAX
4. Calls load_timetable.php with status=1
5. Query returns classes with status=1
6. timetable.js renders with fullCalendar
7. Calendar displays classes

### Admin Confirms Module
1. User in confirm_module.php
2. See modules with ttprogress=2
3. Click to confirm
4. May trigger status updates
5. Reflect in subsequent queries

---

## MODULES & DEPENDENCIES

```
Registry Module (registry_class.php)
    ├── navbar-left.php
    ├── header-top.php
    ├── footer.php
    └── database.php

Add Class Module (add_class.php)
    ├── add_class.js
    ├── backend/select_val.php
    ├── backend/get_val.php
    └── backend/add_class.php
        ├── database.php
        ├── classtopics (INSERT)
        ├── classschedules (INSERT)
        └── classtopics_staff (INSERT)

Calendar View Module (timetable.php)
    ├── timetable.js
    ├── fullcalendar library
    ├── backend/load_timetable.php
    │   ├── database.php
    │   └── classschedules (SELECT with status filter)
    └── backend/load_timetableMyBg.php

Confirmation Module (confirm_module.php)
    ├── database.php
    ├── navbar-left.php
    └── batchmodule table (ttprogress filter)
```
