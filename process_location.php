


<?php

        date_default_timezone_set('Asia/Colombo');
        $lat = $_GET["lat"];
        $lng = $_GET["lng"];
        $dt = date("Y/m/d h:i:sa");
         $to = 'gayathri@kln.ac.lk, chamika@kln.ac.lk' ;
        $subject = "Complain Portal, Faculty of Medicine " . $dt;
        $message = "Date : " . $dt ."<br>". "Locatopn : latitude - " . $lat . ", longitude - " .   $lng . " <br> ";
        //$message.= "<br> https://www.google.com/maps?q=" . $lat.",".$lng;
        $message .= "<a href='https://www.google.com/maps?q=$lat,$lng' >view location in map</a><br><br>";
        $message .= "Incident Reported . <br>This is an automatically generated email";
        $headers = 'From: "Complain Portal, FoM" <gayathri@kln.ac.lk>'. "\r\n" ;
        $headers .= "Content-Type: text/html;";
        mail($to, $subject, $message, $headers,"-f gayathri@kln.ac.lk");
       // echo $message;
       // echo "We will attend to this incident immediately";
        $fl=fopen("loc.csv", "a+");
        fwrite($fl, "\r\n".$_GET["lat"].",".$_GET["lng"]);
        fclose($fl);

?>