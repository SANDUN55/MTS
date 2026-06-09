<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complain Portal, Faculty of Medicine</title>
</head>
<body>
    <h1>Complain Portal, Faculty of Medicine</h1>
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
            window.location.href = "process_location.php?lat=" + position.coords.latitude + "&lng=" + position.coords.longitude;
        }
    </script>
</body>
</html>