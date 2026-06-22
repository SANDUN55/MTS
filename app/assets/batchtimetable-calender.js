$(document).ready(function () {
    var script = document.currentScript ||
        Array.prototype.slice.call(document.getElementsByTagName('script')).pop();

    var batchIdentifier = script.getAttribute('data-batch') || '';
    batchIdentifier = batchIdentifier.trim();

    console.log('=== Calendar Initialized ===');
    console.log('Batch:', batchIdentifier);

    if (!batchIdentifier) {
        console.error("No batch identifier found!");
        alert("Error: Batch information is missing.");
        return;
    }

    $('#mcalendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,listMonth'
        },

        defaultView: 'agendaWeek',
        defaultDate: new Date(),

        slotDuration: '00:15:00',
        minTime: '06:30:00',
        maxTime: '19:00:00',
        navLinks: true,
        weekNumbers: true,
        weekNumberCalculation: 'ISO',
        slotEventOverlap: false,
        weekends: true,

        businessHours: {
            dow: [1, 2, 3, 4, 5],
            start: '06:00',
            end: '18:00'
        },

        selectable: false,
        editable: false,
        timezone: 'local',

        events: function (start, end, timezone, callback) {
            console.log('Fetching events for batch:', batchIdentifier);

            $.ajax({
                url: 'load_batchtimetable.php',
                type: 'GET',
                data: { batch: batchIdentifier },
                success: function (response) {
                    console.log('Events loaded:', response.count || 0);
                    if (response.error) {
                        console.error("Server Error:", response.error);
                        callback([]);
                        return;
                    }
                    callback(response.events || []);
                },
                error: function (xhr, status, err) {
                    console.error('AJAX Error:', status, err);
                    callback([]);
                }
            });
        },

        eventRender: function (event, element) {
            element.css({
                'white-space': 'normal',
                'font-size': '13px',
                'line-height': '1.3'
            });
        }
    });
});