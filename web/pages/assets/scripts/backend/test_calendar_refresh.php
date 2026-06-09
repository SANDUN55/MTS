<!DOCTYPE html>
<html>
<head>
    <title>Test Calendar Refresh</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
</head>
<body>
    <h3>Test Calendar - Should show time ranges only</h3>
    <div id="calendar"></div>
    
    <script>
        $(document).ready(function() {
            var calendar;
            calendar = $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'month'
                },
                defaultView: 'month',
                eventSources: [
                    {
                        url: 'load_timetable.php',
                        data: {
                            custom_param1: 34,
                            custom_param2: 'ALIM2',
                            custom_param3: 1,
                            _: new Date().getTime()
                        },
                        color: '#2E7D32',
                        textColor: 'white'
                    }
                ]
            });
        });
    </script>
</body>
</html>
