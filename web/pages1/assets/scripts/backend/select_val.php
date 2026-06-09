<?php
//include 'database.php';
//database_conectivity();
function loadPhase(){
    database_conectivity();
    global $conn;
    $result = mysqli_query($conn,"SELECT * FROM phase WHERE p_st=1 ORDER BY p_id ASC;");
    $output='';
    $output .= '<select name="selectPhase" id="selectPhase" class="form-control" required>';
    while($row = mysqli_fetch_array($result)) {
        $output .= '<option value="' . $row["p_id"] . '">' . $row["p_nm"] . '</option>';
    }
    $output .= '</select>';
    echo $output;
}
function loadStrand(){
    database_conectivity();
    global $conn;
    $result = mysqli_query($conn,"SELECT * FROM strand WHERE sn_st=1 ORDER BY sn_nm ASC;");
    $output='';
    $output .= '<select name="selectStrand" id="selectStrand" class="form-control" required>';
    while($row = mysqli_fetch_array($result)) {
        $output .= '<option value="' . $row["sn_id"] . '">' . $row["sn_nm"] . '</option>';
    }
    $output .= '</select>
                <div class="invalid-feedback">
                     Please select a Strand.
                 </div>';
    echo $output;
}
function loadBatch(){
    database_conectivity();
    global $conn;
    $result = mysqli_query($conn,"SELECT * FROM batch WHERE batchstatus=1 ORDER BY b_no DESC;");
    $output='';
    $output .= '<select name="selectBatch" id="selectBatch" class="form-control" required>';
    $output .= '<option value="" class="form-control">select Batch</option>';
    while($row = mysqli_fetch_array($result)) {
        $output .= '<option value="' . $row["b_no"] . '">' . $row["b_no"] . '</option>';
    }
    $output .= '</select>
                <div class="invalid-feedback">
                     Please select a Batch.
                 </div>';
    echo $output;
}
function loadModule(){
    database_conectivity();
    global $conn;
    $result = mysqli_query($conn,"SELECT * FROM module WHERE m_st=1 ORDER BY m_phase,m_code ASC;");
    $output='';
    $output .= '<select name="selectModule" id="selectModule" class="form-control" required>';
    $output .= '<option value="" class="form-control">select Module</option>';
    while($row = mysqli_fetch_array($result)) {
        $output .= '<option value="' . $row["m_code"] . '">' . $row["m_name"].', Phase'.$row["m_phase"] . '</option>';
    }
    $output .= '</select> 
                <div class="invalid-feedback">
                     Please select a Module.
                 </div> ';
    echo $output;
}
function loadActivity(){
    database_conectivity();
    global $conn;
    $result = mysqli_query($conn,"SELECT * FROM activity ORDER BY a_name ASC;");
    $output='';
    $output .= '<select name="selectActivity" id="selectActivity" class="form-control" required>';
    $output .= '<option value="" class="form-control">select Activity</option>';
    while($row = mysqli_fetch_array($result)) {
        $output .= '<option value="' . $row["a_id"] . '">' . $row["a_name"].'</option>';
    }
    $output .= '</select>
                    <div class="invalid-feedback">
                     Please select a Activity.
                 </div>';
    echo $output;
}
function loadAcademic(){
    database_conectivity();
    global $conn;
    $result = mysqli_query($conn,"SELECT st_id, t_nm, firstname, surname, div_nm FROM staff WHERE st_cat=1 ORDER BY div_nm, firstname ASC;");
    $output='';
    $outgroup = '';
    $output .= '<select name="selectAcademic[]" id="selectAcademic" class="form-control" required>';
    $output .= '<option value="" class="form-control">select lecturer</option>';
    while($row = mysqli_fetch_array($result)) {
        $group[$row['div_nm']][] = $row;
    }
    foreach ($group as $key => $values){
        $output .= '<optgroup label="'.$key.'">';
        foreach ($values as $value)
        {
            $output .= '<option value="' . $value["st_id"] . '">' . $value["t_nm"].'. '.$value["firstname"] .' '.$value["surname"]. '</option>';
        }
        $output .= '</optgroup>';
    }
    $output .= '</select>
         <div class="invalid-feedback">
                     Please select Academic Staff.
                 </div>';
    echo $output;
}
function loadAcademicDepartments(){
    database_conectivity();
    global $conn;
    $result = mysqli_query($conn,"SELECT st_id, t_nm, firstname, surname, div_nm, dep_code FROM staff WHERE st_cat IN (1,4) ORDER BY div_nm, firstname ASC;");
    $output='';
    $outgroup = '';
    $output .= '<select name="selectAcademicDep" id="selectAcademicDep" class="form-control" required>';
    $output .= '<option value="" class="form-control">select lecturer</option>';
    while($row = mysqli_fetch_array($result)) {
        $group[$row['div_nm']][] = $row;
    }
    foreach ($group as $key => $values){
        $output .= '<optgroup label="'.$key.'">';
        foreach ($values as $value)
        {
            $output .= '<option value="' . $value["dep_code"].'-'.$value["st_id"]. '">' . $value["t_nm"].'. '.$value["firstname"] .' '.$value["surname"]. '</option>';
        }
        $output .= '<option value="' . $value["dep_code"].'-'.$value["dep_code"] . '">' . $key. '</option>';
        $output .= '</optgroup>';
    }
    $output .= '<option value="0-0">Other</option>';
    $output .= '</select>
         <div class="invalid-feedback">
                     Please select Academic Staff.
                 </div>';
    echo $output;
}
function loadLabs(){
    database_conectivity();
    global $conn;
    $result = mysqli_query($conn,"SELECT * FROM lab WHERE lab_status=1 ORDER BY lab_nm  ASC;");
    $output='';
    $output .= '<select name="selectLab[]" id="selectLab" class="form-control" required>';
    $output .= '<option value="" class="form-control">select venue</option>';
    while($row = mysqli_fetch_array($result)) {
        $output .= '<option value="' . $row["lab_code"] . '">' . $row["lab_nm"].'</option>';
    }
    $output .= '<option value="NULL">Other</option>';
    $output .= '</select>
                    <div class="invalid-feedback">
                     Please select a Venue.
                 </div>';
    echo $output;
}
function loadBatchModule(){
    database_conectivity();
    global $conn;
    $result = mysqli_query($conn,"SELECT `b_no`, m.`m_code`, CONCAT( m.m_name, ' ', m.m_phase) AS mname FROM batchmodule 
JOIN  module m ON m.m_code = batchmodule.m_code
WHERE `st_dt` <> '0000-00-00' AND `en_dt` <> '0000-00-00' AND `ttprogress` = 0");
    $output='';
    $output .= '<select name="selectBatchMo" id="selectBatchMo" class="form-control" required>';
    $output .= '<option value="" class="form-control">select Batch - Module</option>';
    while($row = mysqli_fetch_array($result)) {
        $output .= '<option value="' . $row["b_no"] . '-' . $row["m_code"] . '">' . $row["b_no"] . ' - ' . $row["mname"].'</option>';
    }
    $output .= '</select>
                    <div class="invalid-feedback">
                     Please select the Batch - Module.
                 </div>';
    echo $output;
}
function loadBatchModule1(){
    database_conectivity();
    global $conn;
    $result = mysqli_query($conn,"SELECT b_no, b.m_code, m.m_name, m.m_phase, s.st_id as c1_st_id, s.t_nm as c1_t_nm, s.firstname as c1_firstname, s.surname as c1_surname, s.div_nm as c1_div_nm,
                                              s2.st_id as c2_st_id, s2.t_nm as c2_t_nm, s2.firstname as c2_firstname, s2.surname as c2_surname, s2.div_nm as c2_div_nm,
                                               ini_on, comp_on FROM batchmodule b
                                                   LEFT JOIN staff s ON cordi=s.st_id
                                                    LEFT JOIN staff s2 ON cordi2=s2.st_id 
                                                    JOIN module m ON b.m_code=m.m_code
                                                    WHERE  	comp_on='0000-00-00 00:00:00'
                                                    AND b_no=$batch[$a]
                                                    ORDER BY b_no DESC , m.m_code ASC");
    $output='';
    $output .= '<select name="selectActivity" id="selectActivity" class="form-control" required>';
    $output .= '<option value="" class="form-control">select venue</option>';
    while($row = mysqli_fetch_array($result)) {
        $output .= '<option value="' . $row["lab_code"] . '">' . $row["lab_nm"].'</option>';
    }
    $output .= '</select>
                    <div class="invalid-feedback">
                     Please select a Venue.
                 </div>';
    echo $output;
}
function loadUserCat(){
    database_conectivity();
    global $conn;
    $result = mysqli_query($conn,"SELECT * FROM usercat WHERE usercat_id>1 ORDER BY usercat_nm;");
    $output='';
    $output .= '<select name="selectUserCat" id="selectUserCat" class="form-control" required>';
    $output .= '<option value="" class="form-control">select user category</option>';
    while($row = mysqli_fetch_array($result)) {
        $output .= '<option value="' . $row["usercat_id"] . '">' . $row["usercat_nm"].'</option>';
    }
    $output .= '</select>
                    <div class="invalid-feedback">
                     Please select a User Category.
                 </div>';
    echo $output;
}
function loadNonAcademic(){
    database_conectivity();
    global $conn;
    $result = mysqli_query($conn,"SELECT st_id, CONCAT (t_nm, ' ', firstname, ' ', surname ) as staffNm, div_nm  FROM staff WHERE st_cat=3 ORDER BY div_nm, firstname ASC;");
    $output='';
    $outgroup = '';
    $output .= '<select name="selectNonAcademic[]" id="selectNonAcademic" class="form-control" required>';
    $output .= '<option value="" class="form-control">select Timetable Manager</option>';
    while($row = mysqli_fetch_array($result)) {
        $group[$row['div_nm']][] = $row;
    }
    foreach ($group as $key => $values){
        $output .= '<optgroup label="'.$key.'">';
        foreach ($values as $value)
        {
            $output .= '<option value="' . $value["st_id"] . '">' . $value["staffNm"]. '</option>';
        }
        $output .= '</optgroup>';
    }
    $output .= '</select>
         <div class="invalid-feedback">
                     Please select Timetable Manager.
                 </div>';
    echo $output;
}
function loadAcademicDepartmentsVal(){
    database_conectivity();
    global $conn;

    $result = mysqli_query($conn,"SELECT st_id, t_nm, firstname, surname, div_nm, dep_code FROM staff WHERE st_cat=1 ORDER BY div_nm, firstname ASC;");
    $output='';
    $outgroup = '';
    $output .= '<select name="selectAcademicDepVal[]" id="selectAcademicDepVal" class="form-control" required>';
    $output .= '<option value="" class="form-control">select lecturer</option>';
    while($row = mysqli_fetch_array($result)) {
        $group[$row['div_nm']][] = $row;
    }
    foreach ($group as $key => $values){
        $output .= '<optgroup label="'.$key.'">';
        foreach ($values as $value)
        {
            $output .= '<option value="' . $value["dep_code"].'-'.$value["st_id"]. '">' . $value["t_nm"].'. '.$value["firstname"] .' '.$value["surname"]. '</option>';
        }
        $output .= '<option value="' . $value["dep_code"].'-'.$value["dep_code"] . '">' . $key. '</option>';
        $output .= '</optgroup>';
    }
    $output .= '<option value="0-0">Other</option>';
    $output .= '</select>
         <div class="invalid-feedback">
                     Please select Academic Staff.
                 </div>';
    echo $output;
}
function loadBatchModuleMy(){
    database_conectivity();
    global $conn;
    $user = $_SESSION["userMtsFom"];
    $result = mysqli_query($conn,"SELECT `b_no`, m.`m_code`, CONCAT( m.m_name, ' ', m.m_phase) AS mname FROM batchmodule 
JOIN  module m ON m.m_code = batchmodule.m_code
WHERE (comp_on = '0000-00-00 00:00:00' OR comp_on IS NULL) AND (conf_on = '0000-00-00 00:00:00' OR conf_on IS NULL) AND st_dt <> '0000-00-00 00:00:00' AND 	en_dt <> '0000-00-00 00:00:00' AND
( cordi IN (SELECT st_id FROM staff WHERE username='$user')
 OR cordi2 IN (SELECT st_id FROM staff WHERE username='$user')
 OR ttmng1  IN (SELECT st_id FROM staff WHERE username='$user')
 OR ttmng2  IN (SELECT st_id FROM staff WHERE username='$user')
 OR ttmng3  IN (SELECT st_id FROM staff WHERE username='$user')
 OR ttmng4  IN (SELECT st_id FROM staff WHERE username='$user')
 OR ttmng5  IN (SELECT st_id FROM staff WHERE username='$user')
 OR ttmng6  IN (SELECT st_id FROM staff WHERE username='$user')) ORDER BY b_no DESC;");
    $output='';
    $output .= '<select name="selectBatchMo" id="selectBatchMo" class="form-control" required>';
    $output .= '<option value="" class="form-control">select Batch - Module</option>';
    while($row = mysqli_fetch_array($result)) {
        $output .= '<option value="' . $row["b_no"] . '-' . $row["m_code"] . '">' . $row["b_no"] . ' - ' . $row["mname"].'</option>';
    }
    $output .= '</select>
                    <div class="invalid-feedback">
                     Please select the Batch - Module.
                 </div>';
    echo $output;
}
?>


