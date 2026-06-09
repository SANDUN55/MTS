<?php
include 'database.php';
database_conectivity();

// Test for confirmed classes (status=1)
$bno = 34;
$mod = 'ALIM2';

echo "<h3>Testing Status Values for Batch $bno, Module $mod</h3>";

// Test status 1 (confirmed)
$query1 = "SELECT COUNT(*) as count FROM classschedules 
          JOIN classtopics ON class_topic_id = topic_id
          WHERE class_status = 1 AND b_no = $bno AND TRIM(m_code) = TRIM('$mod')";
$result1 = mysqli_query($conn, $query1);
$row1 = mysqli_fetch_assoc($result1);
echo "<p>Status 1 (Confirmed): {$row1['count']} classes</p>";

// Test status 0 (cancelled)
$query2 = "SELECT COUNT(*) as count FROM classschedules 
          JOIN classtopics ON class_topic_id = topic_id
          WHERE class_status = 0 AND b_no = $bno AND TRIM(m_code) = TRIM('$mod')";
$result2 = mysqli_query($conn, $query2);
$row2 = mysqli_fetch_assoc($result2);
echo "<p>Status 0 (Cancelled): {$row2['count']} classes</p>";

// Test status 3 (postponed)
$query3 = "SELECT COUNT(*) as count FROM classschedules 
          JOIN classtopics ON class_topic_id = topic_id
          WHERE class_status = 3 AND b_no = $bno AND TRIM(m_code) = TRIM('$mod')";
$result3 = mysqli_query($conn, $query3);
$row3 = mysqli_fetch_assoc($result3);
echo "<p>Status 3 (Postponed): {$row3['count']} classes</p>";

// Show all classes regardless of status
$query_all = "SELECT class_status, COUNT(*) as count FROM classschedules 
              JOIN classtopics ON class_topic_id = topic_id
              WHERE b_no = $bno AND TRIM(m_code) = TRIM('$mod')
              GROUP BY class_status";
$result_all = mysqli_query($conn, $query_all);
echo "<h4>All Classes by Status:</h4>";
while($row = mysqli_fetch_assoc($result_all)) {
    echo "<p>Status {$row['class_status']}: {$row['count']} classes</p>";
}

// Show actual class details for confirmed classes
$query_details = "SELECT cs.class_id, cs.class_start, cs.class_end, ct.class_topic, ct.activity 
                  FROM classschedules cs
                  JOIN classtopics ct ON cs.class_topic_id = ct.topic_id
                  WHERE cs.class_status = 1 AND cs.b_no = $bno AND TRIM(ct.m_code) = TRIM('$mod')
                  ORDER BY cs.class_start";
$result_details = mysqli_query($conn, $query_details);
echo "<h4>Confirmed Class Details:</h4>";
if(mysqli_num_rows($result_details) > 0) {
    while($row = mysqli_fetch_assoc($result_details)) {
        echo "<p>ID: {$row['class_id']}, Topic: {$row['class_topic']}, Start: {$row['class_start']}</p>";
    }
} else {
    echo "<p>No confirmed classes found</p>";
}
?>
