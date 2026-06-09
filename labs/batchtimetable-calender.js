$('#lab').on('change', function () {
    var val = this.value;
    var getSelected = $("#lab").find("option:selected").text();

    if (val && val !== "0") {
        // Destroy previous calendar
        $('#mcalendar').fullCalendar('destroy');

        // Create new calendar
        $('#mcalendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,listWeek,listDay'
            },
            views: {
                listDay: { buttonText: 'list day' },
                listWeek: { buttonText: 'list week' }
            },
            defaultView: 'month',
            slotDuration: '00:15:00',
            minTime: '07:00:00',
            maxTime: '19:00:00',
            navLinks: true,
            weekNumbers: true,
            weekNumbersWithinDays: true,
            weekNumberCalculation: 'ISO',
            slotEventOverlap: false,
            weekends: false,
            businessHours: {
                dow: [1, 2, 3, 4, 5],
                start: '8:00',
                end: '18:00'
            },
            eventConstraint: "businessHours",
            selectHelper: true,
            eventSources: [
                {
                    url: 'load_batchtimetable.php?custom_param1=' + val,
                    color: 'lightblue',
                    textColor: 'black',
                    borderColor: 'blue',
                    error: function (xhr, textStatus, errorThrown) {
                        console.error('Error loading lab reservations:', textStatus, errorThrown);
                    }
                },
                {
                    url: 'load_holidays.php',
                    color: 'lightcoral',
                    textColor: 'black',
                    error: function (xhr, textStatus, errorThrown) {
                        console.error('Error loading holidays:', textStatus, errorThrown);
                    }
                }
            ],
            selectable: false,
            editable: false,
            error: function (error) {
                console.error('Calendar error:', error);
            }
        });
    }
});