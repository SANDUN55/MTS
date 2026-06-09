<?php
if(isset($_GET)){
    date_default_timezone_set('Asia/Colombo');
    $lat = $_GET["lat"];
    $lng = $_GET["lng"];

    if(isset($lat) && isset($lng)){
        $dt = date("Y/m/d h:i:sa");
        $to = 'gayathri@kln.ac.lk,chamika@kln.ac.lk,mnchandratilake@kln.ac.lk' ;
        $subject = "Complain Portal, Faculty of Medicine, UoK " . $dt;
        $message = "Date : " . $dt ."<br>". "Locatopn : latitude - " . $lat . ", longitude - " .   $lng . " <br> ";
        //$message.= "<br> https://www.google.com/maps?q=" . $lat.",".$lng;
        $message .= "<a href='https://www.google.com/maps?q=$lat,$lng' >view location in map</a><br><br>";
        $message .= "Incident Reported . <br>This is an automatically generated email";
        $headers = 'From: "Complain Portal, FoM" <gayathri@kln.ac.lk>'. "\r\n" ;
        $headers .= "Content-Type: text/html;";
        mail($to, $subject, $message, $headers,"-f gayathri@kln.ac.lk");
        header('Location: https://medicine.kln.ac.lk/index.php/for-students.html');
    }
    // echo $message;
    // echo "We will attend to this incident immediately";
    $fl=fopen("loc.csv", "a+");
    fwrite($fl, "\r\n".$_GET["lat"].",".$_GET["lng"]);
    fclose($fl);
}
?>
<button onclick="getLocation()">Report My Location</button>
<script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }
    function showPosition(position) {
        // Send the latitude and longitude to a PHP script
        window.location.href = "geo.php?lat=" + position.coords.latitude + "&lng=" + position.coords.longitude;
    }
</script>


