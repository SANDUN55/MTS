# 📚 TIMETABLE MANAGEMENT DOCUMENTATION INDEX

## Welcome!
This folder contains comprehensive documentation of the timetable management system for the MTS 2026 application.

---

## 📄 AVAILABLE DOCUMENTS

### 1. **QUICK_REFERENCE.md** ⭐ START HERE
**Best for:** Quick answers and common lookups
**Contains:**
- Key file locations
- Most important SQL queries
- Filtering conditions
- Quick test queries
- Summary of critical findings

**Time to read:** 5-10 minutes

---

### 2. **TIMETABLE_MANAGEMENT_ANALYSIS.md** 📊 COMPREHENSIVE GUIDE
**Best for:** Understanding the complete system architecture
**Contains:**
- Complete file structure and purposes
- All database tables with field descriptions
- All SQL queries explained
- Class status values and workflow
- Complete data flow from insertion to display
- Debugging takeaways

**Time to read:** 15-20 minutes

---

### 3. **SQL_QUERY_REFERENCE.md** 💾 SQL ONLY
**Best for:** Copy-paste ready SQL queries
**Contains:**
- All SQL queries organized by purpose
- Class list queries
- Module management queries
- Insertion queries
- Status check queries
- Diagnostic queries
- Status value mapping

**Time to read:** 5 minutes (for reference)

---

### 4. **FILE_MAP_AND_ARCHITECTURE.md** 🏗️ SYSTEM ARCHITECTURE
**Best for:** Understanding file relationships and data flow
**Contains:**
- Complete file structure map
- Data flow diagrams
- Critical file relationships
- Database connection chain
- Multiple views and how they work
- Common paths through the code

**Time to read:** 10-15 minutes

---

### 5. **DEBUGGING_GUIDE.md** 🔧 TROUBLESHOOTING
**Best for:** Finding and fixing problems
**Contains:**
- Root cause analysis
- Step-by-step debugging process
- Common issues and solutions
- Quick diagnosis flowchart
- Test checklist
- Debugging helpers

**Time to read:** 10-15 minutes (when needed)

---

## ❓ WHICH DOCUMENT SHOULD I READ?

### "I need a quick overview of the system"
→ Read: **QUICK_REFERENCE.md** (5 min)

### "I want to understand the complete architecture"
→ Read: **TIMETABLE_MANAGEMENT_ANALYSIS.md** (20 min)

### "I need to write or modify a SQL query"
→ Read: **SQL_QUERY_REFERENCE.md** (copy-paste)

### "I need to understand how files connect"
→ Read: **FILE_MAP_AND_ARCHITECTURE.md** (15 min)

### "Newly added classes aren't appearing in the list"
→ Read: **DEBUGGING_GUIDE.md** (15 min)

### "I need everything"
→ Read all documents in order (1→5)

---

## 🎯 KEY FINDINGS AT A GLANCE

### Main Question: "Where is the timetable management list loaded/displayed?"
**Answer:** `web/pages/assets/scripts/backend/load_timetable.php`

### Main Question: "What SQL query fetches the classes?"
**Answer:** 
```sql
SELECT ... FROM classschedules
JOIN classtopics ON class_topic_id = topic_id
WHERE class_status = $status AND b_no = $bno AND m_code = '$mod'
```

### Main Question: "What filtering conditions exist?"
**Answer:**
- `class_status = 1` (active classes, 0 = hidden)
- `ttprogress = 2` (module ready/tentative)
- User must be coordinator (cordi/cordi2) or timetable manager (ttmng1-6)
- Module dates must be set

### Main Question: "Where are newly added classes inserted?"
**Answer:** `web/pages/assets/scripts/backend/add_class.php`
- Inserts to classtopics table
- Inserts to classschedules table with `class_status = 1`
- Inserts to classtopics_staff table

---

## 🔴 CRITICAL INSIGHTS

1. **The KEY field is `class_status` in classschedules table**
   - 1 = Visible/Active
   - 0 or other = Hidden
   - All queries filter by this

2. **New classes are always created with `class_status = 1`**
   - In `add_class.php` backend at line ~60

3. **The display query uses a `$status` parameter**
   - Passed as `custom_param3` in URL
   - If not = 1, classes won't appear

4. **Multiple views use the same query**
   - load_timetable.php (general)
   - load_timetableMyBg.php (personal)
   - Lab-load_timetable.php (lab)
   - All use `WHERE class_status = $status`

5. **Module must be in correct progress stage**
   - `ttprogress = 2` for ready modules
   - Other stages may not show classes

---

## 📁 DOCUMENT FILE SIZES

| Document | Approx Length |
|----------|--------------|
| QUICK_REFERENCE.md | ~400 lines |
| TIMETABLE_MANAGEMENT_ANALYSIS.md | ~650 lines |
| SQL_QUERY_REFERENCE.md | ~500 lines |
| FILE_MAP_AND_ARCHITECTURE.md | ~550 lines |
| DEBUGGING_GUIDE.md | ~450 lines |

---

## 🔍 QUICK SEARCH GUIDE

### Find information about...

**Class insertion:**
- TIMETABLE_MANAGEMENT_ANALYSIS.md (section 5)
- SQL_QUERY_REFERENCE.md (section 5)
- DEBUGGING_GUIDE.md (section 1)

**Database tables:**
- TIMETABLE_MANAGEMENT_ANALYSIS.md (section 2)
- SQL_QUERY_REFERENCE.md (last section)

**Data flow:**
- FILE_MAP_AND_ARCHITECTURE.md (data flow diagram)
- QUICK_REFERENCE.md (section 3)

**Status values:**
- QUICK_REFERENCE.md (section 1)
- TIMETABLE_MANAGEMENT_ANALYSIS.md (section 4)
- DEBUGGING_GUIDE.md (section 1)

**User permissions:**
- TIMETABLE_MANAGEMENT_ANALYSIS.md (section 3, queries)
- DEBUGGING_GUIDE.md (issue 5)

**File locations:**
- FILE_MAP_AND_ARCHITECTURE.md (file structure)
- QUICK_REFERENCE.md (quick table)

**Module progress:**
- TIMETABLE_MANAGEMENT_ANALYSIS.md (section 4, status values)
- DEBUGGING_GUIDE.md (step 3)

---

## ✅ VERIFICATION CHECKLIST

When adding a new class, use this to verify it appears:

- [ ] Class inserted to `classtopics` table
- [ ] Class inserted to `classschedules` table with `class_status = 1`
- [ ] Additional lecturers inserted to `classtopics_staff`
- [ ] Class appears in activity summary (get_val.php case 3)
- [ ] Direct SQL query returns class
- [ ] `$status` parameter = 1 in load_timetable.php
- [ ] AJAX call includes `custom_param3=1`
- [ ] Module has `ttprogress = 2`
- [ ] User is coordinator/timetable manager
- [ ] Class appears in timetable calendar

---

## 🛠️ TROUBLESHOOTING QUICK LINKS

| Problem | Document | Section |
|---------|----------|---------|
| New class not appearing | DEBUGGING_GUIDE.md | Complete guide |
| Wrong status value | DEBUGGING_GUIDE.md | Step 3 |
| Wrong parameters | DEBUGGING_GUIDE.md | Step 5 |
| Class deleted/hidden | DEBUGGING_GUIDE.md | Step 6 |
| Permission issues | DEBUGGING_GUIDE.md | Issue 5 |
| Module not ready | DEBUGGING_GUIDE.md | Issue 4 |

---

## 📞 HELP & SUPPORT

### For understanding...
- **What files exist:** See FILE_MAP_AND_ARCHITECTURE.md
- **How queries work:** See SQL_QUERY_REFERENCE.md
- **How data flows:** See FILE_MAP_AND_ARCHITECTURE.md (data flow)
- **How to fix issues:** See DEBUGGING_GUIDE.md

### For debugging...
- **Step 1:** Read DEBUGGING_GUIDE.md "Step-by-Step Debugging"
- **Step 2:** Use queries from SQL_QUERY_REFERENCE.md
- **Step 3:** Check file locations in FILE_MAP_AND_ARCHITECTURE.md
- **Step 4:** Review SQL syntax in TIMETABLE_MANAGEMENT_ANALYSIS.md

---

## 🚀 GETTING STARTED

### If you have 5 minutes:
1. Read QUICK_REFERENCE.md

### If you have 15 minutes:
1. Read QUICK_REFERENCE.md
2. Skim TIMETABLE_MANAGEMENT_ANALYSIS.md

### If you have 30 minutes:
1. Read QUICK_REFERENCE.md
2. Read TIMETABLE_MANAGEMENT_ANALYSIS.md
3. Scan FILE_MAP_AND_ARCHITECTURE.md

### If you need to debug an issue:
1. Go directly to DEBUGGING_GUIDE.md
2. Follow the "Step-by-Step Debugging" process
3. Use queries from SQL_QUERY_REFERENCE.md as needed

---

## 📋 DOCUMENT ORGANIZATION

### QUICK_REFERENCE.md
```
1. Key files
2. Main SQL query
3. Filtering conditions
4. Insertion process
5. Data flow
6. Tables & fields
7. Key files to check
8. Status is key
9. Quick test queries
10. Summary
```

### TIMETABLE_MANAGEMENT_ANALYSIS.md
```
Executive Summary
1. Files structure
2. Database tables
3. SQL queries (detailed)
4. Status workflow
5. Class insertion process
6. Filtering logic
7. Related queries
8. Data flow
9. Key takeaways
10. File summary table
```

### SQL_QUERY_REFERENCE.md
```
1. Class list display
2. Module management
3. Activity & count queries
4. Module date queries
5. Class insertion
6. Status & conflict
7. Diagnostic queries
8. Status mapping
9. Table relationships
10. Common filters
```

### FILE_MAP_AND_ARCHITECTURE.md
```
1. File structure map
2. Data flow architecture
3. File relationships
4. Status workflow diagram
5. Database connection chain
6. File purposes
7. Testing & debugging paths
8. Key insights
9. Common paths through code
10. Modules & dependencies
```

### DEBUGGING_GUIDE.md
```
Problem statement
Root cause analysis
1-7. Step-by-step debugging
Quick diagnosis flowchart
Common issues & solutions
Test checklist
Quick fix attempts
Debugging helpers
Final verification
Escalation checklist
```

---

## 💡 PRO TIPS

1. **Bookmark the main query** (load_timetable.php SQL) in SQL_QUERY_REFERENCE.md
2. **Keep the file map** (FILE_MAP_AND_ARCHITECTURE.md) open for reference
3. **Use the debugging flowchart** (DEBUGGING_GUIDE.md) to diagnose issues
4. **Copy queries from SQL_QUERY_REFERENCE.md** directly into your test environment
5. **When stuck, check the quick diagnosis flowchart** (DEBUGGING_GUIDE.md)

---

## 🎓 LEARNING PATH

### For beginners:
1. QUICK_REFERENCE.md
2. FILE_MAP_AND_ARCHITECTURE.md
3. TIMETABLE_MANAGEMENT_ANALYSIS.md

### For experienced devs:
1. SQL_QUERY_REFERENCE.md
2. FILE_MAP_AND_ARCHITECTURE.md
3. DEBUGGING_GUIDE.md

### For architects/designers:
1. FILE_MAP_AND_ARCHITECTURE.md
2. TIMETABLE_MANAGEMENT_ANALYSIS.md
3. SQL_QUERY_REFERENCE.md

---

## 📝 NOTES

- All file paths are relative to: `c:\xampp\htdocs\mts-2026\`
- All queries are MySQL 5.7+ compatible
- All PHP files are procedural style (not OOP)
- Status values are stored as INT in classschedules.class_status
- The system uses AJAX for asynchronous class loading

---

## ✨ SUMMARY

**These documents provide:**
- ✓ Complete list of all timetable management files
- ✓ All SQL queries used in the system
- ✓ Complete data flow from creation to display
- ✓ Filtering conditions and status logic
- ✓ How newly added classes are inserted
- ✓ How to debug missing classes
- ✓ Architecture and file relationships
- ✓ Quick reference for common tasks

**Total information:** ~2,500+ lines of detailed documentation

---

Last updated: 2024
For questions or updates: Check the individual documents for detailed explanations.

