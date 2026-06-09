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
// function loadAcademicDepartments(){
//     database_conectivity();
//     global $conn;
//     $result = mysqli_query($conn,"SELECT st_id, t_nm, firstname, surname, div_nm, dep_code, IF(onleave>0, ' (Leave)' , '') as onlv FROM staff WHERE st_cat IN (1,2,4) ORDER BY div_nm, firstname ASC;");
//     $output='';
//     $outgroup = '';
//     $output .= '<select name="selectAcademicDep" id="selectAcademicDep" class="form-control" required>';
//     $output .= '<option value="" class="form-control">select lecturer</option>';
//     while($row = mysqli_fetch_array($result)) {
//         $group[$row['div_nm']][] = $row;
//     }
//     foreach ($group as $key => $values){
//         $output .= '<optgroup label="'.$key.'">';
//         foreach ($values as $value)
//         {
//             $output .= '<option value="' . $value["dep_code"].'-'.$value["st_id"]. '">' . $value["t_nm"].'. '.$value["firstname"] .' '.$value["surname"]. $value["onlv"] . '</option>';
//         }
//         $output .= '<option value="' . $value["dep_code"].'-'.$value["dep_code"] . '">' . $key .'</option>';
//         $output .= '</optgroup>';
//     }
//     $output .= '<option value="0-0">Other</option>';
//     $output .= '</select>
//          <div class="invalid-feedback">
//                      Please select Academic Staff.
//                  </div>';
//     echo $output;
// }
/////////////////////////////////////////////////////////////////////////////////

function loadAcademicDepartments($a){
    database_conectivity();
    global $conn;
    
    $result = mysqli_query($conn,"SELECT st_id, t_nm, firstname, surname, div_nm, dep_code, IF(onleave>0, ' (Leave)' , '') as onlv FROM staff WHERE st_cat IN (1,2,4) ORDER BY div_nm, firstname ASC;");
    $output = '';
    
    if($a==1){
        $output .= '<select name="selectAcademicDep1" id="selectAcademicDep1" onChange="tempEdit(1)" class="form-control" multiple>';
        $output .= '<option value="" class="form-control">Select Lecturer</option>';
    } else {
        $output .= '<select name="selectAcademicDep2" id="selectAcademicDep2" onChange="tempEdit(2)" class="form-control" multiple>';
        $output .= '<option value="" class="form-control">Select Lecturer</option>';
    }

    // Group by division
    while($row = mysqli_fetch_array($result)) {
        $group[$row['div_nm']][] = $row;
    }

    // Loop through the grouped staff and create optgroups
    foreach ($group as $key => $values){
        $output .= '<optgroup label="'.$key.'">';
        foreach ($values as $value)
        {
            $output .= '<option value="' . $value["dep_code"].'-'.$value["st_id"]. '">' . $value["t_nm"].'. '.$value["firstname"] .' '.$value["surname"]. $value["onlv"] . '</option>';
        }
        $output .= '</optgroup>';
    }

   // $output .= '<option value="0-0">Other</option>';
    $output .= '</select>';

    // Use unique IDs for selected lecturers container
    $output .= '<div id="selectedLecturers'.$a.'" style="margin-top: 10px; padding: 10px; border: 1px solid #ddd; min-height: 30px; background-color: #f9f9f9; display: flex; flex-wrap: wrap; gap: 5px;"></div>';

    $output .= '<div class="invalid-feedback">Please select an Academic Staff.</div>';

    $output .= '
<script>
    var selectedLecturers1 = [];
    var selectedLecturers2 = [];

    function tempEdit(n){
        addSelectedLecturer(n);
    }
function selectOptionByText(selectId, textArr) {
    const select = document.getElementById(selectId);
    if (!select) return false;
    for (let option of select.options) {
        if (Array.isArray(textArr) ? textArr.includes(option.text) : option.text === textArr) {
            select.value = option.value;
            return option;
        }
    }
    return false; // Not found
}


    function addSelectedLecturer(n) {
        var select = document.getElementById("selectAcademicDep" + n);
        var selectedBox = document.getElementById("selectedLecturers" + n);
        var selectedLecturers = (n === 1) ? selectedLecturers1 : selectedLecturers2;

        // Clear the selectedBox before adding again (optional)
        // selectedBox.innerHTML = "";

        for (var i = 0; i < select.options.length; i++) {
            var option = select.options[i];
            var selectedValue = option.value;
            var selectedText = option.text;

            if (option.selected && selectedValue !== "" && selectedLecturers.indexOf(selectedValue) === -1) {
                selectedLecturers.push(selectedValue);

                var lecturerTag = document.createElement("span");
                lecturerTag.textContent = selectedText;
                lecturerTag.style.padding = "5px 10px";
                lecturerTag.style.margin = "5px";
                lecturerTag.style.border = "1px solid #ccc";
                lecturerTag.style.backgroundColor = "#e0f7fa";
                lecturerTag.style.borderRadius = "20px";
                lecturerTag.style.display = "inline-block";
                lecturerTag.style.fontSize = "14px";
                lecturerTag.style.whiteSpace = "nowrap";

                // Create hidden input
                var hiddenInput = document.createElement("input");
                hiddenInput.type = "hidden";
                hiddenInput.name = "selectedLecturers[]";
                hiddenInput.value = selectedValue;
                lecturerTag.appendChild(hiddenInput);

                var removeButton = document.createElement("button");
                removeButton.textContent = "x";
                removeButton.style.marginLeft = "10px";
                removeButton.style.background = "none";
                removeButton.style.border = "none";
                removeButton.style.color = "red";
                removeButton.style.cursor = "pointer";
                removeButton.onclick = (function(value, tag, arr) {
                    return function() {
                        removeLecturer(value, tag, arr);
                    };
                })(selectedValue, lecturerTag, selectedLecturers);

                lecturerTag.appendChild(removeButton);
                selectedBox.appendChild(lecturerTag);
            }
        }
    }
        

    function removeLecturer(lecturerValue, lecturerTag, arr) {
        var index = arr.indexOf(lecturerValue);
        if (index > -1) {
            arr.splice(index, 1);
        }
        lecturerTag.parentNode.removeChild(lecturerTag);
    }
</script>
';

    echo $output;
}



////////////////////////////////////////////////////////////////////////////////////////////

// function loadAcademicDepartments() {
//     database_conectivity();
//     global $conn;

//     $result = mysqli_query($conn, "SELECT st_id, t_nm, firstname, surname, div_nm, dep_code, IF(onleave>0, ' (Leave)', '') as onlv FROM staff WHERE st_cat IN (1,2,4) ORDER BY div_nm, firstname ASC;");
//     $output = '';

//     // Start select dropdown
//     $output .= '<input type="text" id="searchBox" class="form-control" placeholder="Search departments..." onkeyup="filterOptions()">';
//     $output .= '<select name="selectAcademicDep" id="selectAcademicDep" multiple required onchange="addSelectedLecturer()" class="form-control">';
//     $output .= '<option value="">Select Lecturer</option>';

//     // Group by division
//     while ($row = mysqli_fetch_array($result)) {
//         $group[$row['div_nm']][] = $row;
//     }

//     // Loop through groups to build dropdown with optgroups
//     foreach ($group as $key => $values) {
//         $output .= '<optgroup label="' . $key . '">';
//         foreach ($values as $value) {
//             $output .= '<option value="' . $value["dep_code"] . '-' . $value["st_id"] . '">' . $value["t_nm"] . '. ' . $value["firstname"] . ' ' . $value["surname"] . $value["onlv"] . '</option>';
//         }
//         $output .= '</optgroup>';
//     }

//     $output .= '<option value="0-0">Other</option>';
//     $output .= '</select>';

//     // Selected lecturers box
//     $output .= '
//         <div id="selectedLecturers" style="margin-top: 10px; padding: 10px; border: 1px solid #ddd; min-height: 30px; background-color: #f9f9f9; display: flex; flex-wrap: wrap; gap: 5px;"></div>';
//     $output .= '<input type="hidden" name="selectedLecturers[]" id="selectedLecturersInput">';
//     $output .= '<div class="invalid-feedback">Please select an Academic Staff.</div>';

//     // Script to handle selection, filtering, and hidden inputs
//     $output .= '
//     <script>
//         var selectedLecturers = [];

//         // Filter options based on search input
//         function filterOptions() {
//             var input = document.getElementById("searchBox").value.toLowerCase();
//             var options = document.getElementById("selectAcademicDep").options;

//             for (var i = 0; i < options.length; i++) {
//                 var optionText = options[i].text.toLowerCase();
//                 options[i].style.display = optionText.includes(input) ? "" : "none";
//             }
//         }

//         // Add selected lecturer to the selected box
//         function addSelectedLecturer() {
//             var select = document.getElementById("selectAcademicDep");
//             var selectedValue = select.options[select.selectedIndex].text;
//             var selectedCode = select.value;
//             var selectedBox = document.getElementById("selectedLecturers");

//             if (selectedValue !== "Select Lecturer" && selectedLecturers.indexOf(selectedValue) === -1) {
//                 selectedLecturers.push(selectedValue);

//                 var lecturerTag = document.createElement("span");
//                 lecturerTag.textContent = selectedValue;
//                 lecturerTag.style.padding = "5px 10px";
//                 lecturerTag.style.margin = "5px";
//                 lecturerTag.style.border = "1px solid #ccc";
//                 lecturerTag.style.backgroundColor = "#e0f7fa";
//                 lecturerTag.style.borderRadius = "20px";
//                 lecturerTag.style.display = "inline-block";
//                 lecturerTag.style.fontSize = "14px";
//                 lecturerTag.style.whiteSpace = "nowrap";

//                 // Remove button
//                 var removeButton = document.createElement("button");
//                 removeButton.textContent = "x";
//                 removeButton.style.marginLeft = "10px";
//                 removeButton.style.background = "none";
//                 removeButton.style.border = "none";
//                 removeButton.style.color = "red";
//                 removeButton.style.cursor = "pointer";
//                 removeButton.onclick = function() {
//                     removeLecturer(selectedValue, lecturerTag);
//                 };

//                 lecturerTag.appendChild(removeButton);

//                 // Create hidden input for submission
//                 var hiddenInput = document.createElement("input");
//                 hiddenInput.type = "hidden";
//                 hiddenInput.name = "selectedLecturers[]"; // name as array
//                 hiddenInput.value = selectedCode;
//                 hiddenInput.setAttribute("data-lecturer", selectedValue);

//                 // Attach to tag and box
//                 lecturerTag.appendChild(hiddenInput);
//                 selectedBox.appendChild(lecturerTag);

//                 select.selectedIndex = 0;
//             }
//         }

//         // Remove selected lecturer from the list
//         function removeLecturer(lecturerName, lecturerTag) {
//             var index = selectedLecturers.indexOf(lecturerName);
//             if (index > -1) {
//                 selectedLecturers.splice(index, 1);
//             }

//             lecturerTag.parentNode.removeChild(lecturerTag);
//         }
//     </script>
//     ';

//     echo $output;
// }


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function loadLabs(){
    database_conectivity();
    global $conn;
    $result = mysqli_query($conn,"SELECT * FROM lab WHERE lab_status=1 AND lab_dep <> 77 ORDER BY lab_nm  ASC;");
    $output='';
    $output .= '<select name="selectLab[]" id="selectLab" class="form-control" required>';
    $output .= '<option value="" class="form-control">select venue</option>';
    while($row = mysqli_fetch_array($result)) {
        $output .= '<option value="' . $row["lab_code"] . '">' . $row["lab_nm"]. " (seats ". $row["lab_seat"] . ")". '</option>';
    }

   // $output .= '<option value="NULL">Other</option>';
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
    $result = mysqli_query($conn,"SELECT st_id, CONCAT (t_nm, ' ', firstname, ' ', surname ) as staffNm, div_nm  FROM staff WHERE st_cat=3 or st_cat=6 ORDER BY div_nm, firstname ASC;");
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
    $result = mysqli_query($conn,"SELECT st_id, t_nm, firstname, surname, div_nm, dep_code, IF(onleave>0, ' (Leave)' , '') as onlv FROM staff WHERE st_cat IN (1,2,4) ORDER BY div_nm, firstname ASC;");
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
            $output .= '<option value="' . $value["dep_code"].'-'.$value["st_id"]. '">' . $value["t_nm"].'. '.$value["firstname"] .' '.$value["surname"]. $value["onlv"] . '</option>';
        }
        $output .= '<option value="' . $value["dep_code"].'-'.$value["dep_code"] . '">' . $key. '</option>';
        $output .= '</optgroup>';
    }
   // $output .= '<option value="0-0">Other</option>';
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

