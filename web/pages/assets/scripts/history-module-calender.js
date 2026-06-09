$(document).ready(function() {
    var script = document.currentScript ||
        /*Polyfill*/ Array.prototype.slice.call(document.getElementsByTagName('script')).pop();
    var params = (script.getAttribute('data-params') || '').split(/- */);
    var val1 = params[0]; // -> 1
    var val2 = params[1]; //params[1]; // -> 3
  //  alert(val1);
   // alert(val2);
    $(window).focus();

    let ctrlIsPressed = false;







    var calendar;


    calendar = $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,listWeek,listDay'
        },
        views: {
            listDay: { buttonText: 'list day' },
            listWeek: { buttonText: 'list week' }
        },
        defaultView: 'agendaWeek',
        slotDuration: '00:15:00',
        minTime: '07:00:00',
        maxTime: '19:00:00',
        navLinks: true, // can click day/week names to navigate views
        weekNumbers: true,
        weekNumbersWithinDays: true,
        weekNumberCalculation: 'ISO',
        slotEventOverlap : false,
        //weekends : false,
        businessHours: {
            dow: [ 1, 2, 3, 4, 5  ],
            start: '7:00',
            end: '18:00'
        },
        eventConstraint: "businessHours",
        selectHelper: true,
        selectAllow: function(info) {
            if (info.start < Date.now())
                return false;
            return true;
        },
        editable: true,
        droppable: true,
        validRange: function() {
            var bmod = val1 + ',' + val2;
            var paramDates=   $.ajax({
                url: "backend/get_val.php",
                type: "POST",
                data: { fid: 2, fval: bmod},
                async: false,
                success: function (dataResult) {
                    return dataResult;
                }
            }).responseText;
            var vals = paramDates.split(",");
            stDt = vals[0];
            enDt = vals[1];
            return {
                start: stDt,
                end: enDt
            };
        },
        eventSources: [
            // your event source
            {
                url: 'backend/load_timetable.php', // use the `url` property
                data: {
                    custom_param1: val1,
                    custom_param2: val2,
                    custom_param3: 1
                },
                color: 'beige',
                textColor: 'black',
                borderColor: 'grey'
            },
            {
                url: 'backend/load_holidays.php', // use the `url` property
                color: 'red',
                textColor: 'black',
                draggable: false,
                editable: false,
                selectable: false
                //rendering: 'background',
                //allDay:true
            },
            {
                url: 'backend/load_timetable.php', // use the `url` property
                data: {
                    custom_param1: val1,
                    custom_param2: val2,
                    custom_param3: 0
                },
                color: '#ff0066',
                textColor: 'black',
                borderColor: 'grey',
                draggable: true,
                editable: true,
                selectable: true
            },
            {
                url: 'backend/load_timetable.php', // use the `url` property
                data: {
                    custom_param1: val1,
                    custom_param2: val2,
                    custom_param3: 3
                },
                color: '#0099ff',
                textColor: 'black',
                borderColor: 'grey'
            }
        ],
        eventColor: '#378006',
        eventTextColor: '#fff',
        selectable: true,
        editable: true,
        eventRender: function(event, element) {
            var todayDt = moment().format('YYYY-MM-DD HH:mm:ss');
            var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
            if (start <  todayDt)
            {
                event.editable = false;
            }
        },

    });
});