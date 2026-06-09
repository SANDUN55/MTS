<?php
session_start();
if(!isset($_SESSION["userLoggedIn"]) || $_SESSION["userLoggedIn"] !== true)
{
    header('Location:../login.php');
    die();
} else {
?>
    <title>Module Timetable Calender - Fixed</title>
    <link rel="stylesheet" href="assets/scripts/calander/fullcalendar3.4.0.css" />
    <script src="assets/scripts/calander/jquery3.2.1.min.js"></script>
    <script src="assets/scripts/calander/jquery1.12.1-ui.min.js"></script>
    <script src="assets/scripts/calander/moment2.18.1.min.js"></script>
    <script src="assets/scripts/calander/fullcalendar3.4.0.min.js"></script>
    <script src="assets/scripts/calander/bootstrap3.4.1.min.js"></script>
  
    <div class="container" style="background-color:white;">
        <div style="margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 8px;">
            <h3>Fixed Timetable Display</h3>
            <p><strong>Issue:</strong> Calendar was showing full topic names instead of time ranges due to browser caching.</p>
            <p><strong>Solution:</strong> This version forces fresh data loading with cache-busting.</p>
            <p><a href="timetableDisplay.php?id=<?php echo $_GET['id']; ?>&v=<?php echo time(); ?>" style="background: #2E7D32; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px;">Go to Original Timetable</a></p>
        </div>
        <div id="calendar"></div>
    </div>
    
    <script>
        // Force cache-busting and fresh data loading
        const timestamp = new Date().getTime();
        const params = '<?php echo isset($_GET['id']) ? $_GET['id'] : '34-ALIM2-Alimentary%202%20Module'; ?>';
        
        $(document).ready(function() {
            var calendar = $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'month,listWeek,listDay'
                },
                views: {
                    listDay: { buttonText: 'list day' },
                    listWeek: { buttonText: 'list week' }
                },
                defaultView: 'month',
                slotDuration: '00:15:00',
                minTime: '06:30:00',
                maxTime: '19:00:00',
                navLinks: true,
                weekNumberCalculation: 'ISO',
                businessHours: {
                    dow: [ 1, 2, 3, 4, 5 ],
                    start: '6:00',
                    end: '18:00'
                },
                eventConstraint: "businessHours",
                selectHelper: true,
                eventSources: [
                    {
                        url: 'assets/scripts/backend/load_timetable.php',
                        data: {
                            custom_param1: '<?php echo explode('-', $_GET['id'])[0]; ?>',
                            custom_param2: '<?php echo explode('-', $_GET['id'])[1]; ?>',
                            custom_param3: 1,
                            _: timestamp // Force fresh data
                        },
                        color: '#2E7D32',
                        textColor: 'white',
                        borderColor: '#1B5E20'
                    }
                ]
            });
        });
    </script>
<?php } ?>
