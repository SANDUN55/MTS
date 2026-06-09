$(document).ready(function() {
    var script = document.currentScript ||
        /*Polyfill*/ Array.prototype.slice.call(document.getElementsByTagName('script')).pop();
    var params = (script.getAttribute('data-params') || '').split(/- */);
   // var today = new Date().toISOString().slice(0,10);
    var val1 = params[0]; // -> 1
    var val2 = params[1]; //params[1]; // -> 3
     var calendar;
     calendar = $('#calendar').fullCalendar({
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
                url: 'assets/scripts/backend/load_timetable.php', // use the `url` property
                data: {
                    custom_param1: val1,
                    custom_param2: val2,
                    custom_param3: 1,
                    _: new Date().getTime() // Cache-busting parameter
                },
                color: '#2E7D32', // Dark green for confirmed classes
                textColor: 'white',
                borderColor: '#1B5E20'
            },
             {
                 url: 'assets/scripts/backend/load_holidays.php', // use the `url` property
                 color: 'red',
                 textColor: 'black',
                 draggable: false,
                 editable: false,
                 selectable: false
                 //rendering: 'background',
                 //allDay:true
             },
             {
                 url: 'assets/scripts/backend/load_timetable.php', // use the `url` property
                 data: {
                     custom_param1: val1,
                     custom_param2: val2,
                     custom_param3: 0,
                     _: new Date().getTime() // Cache-busting parameter
                 },
                 color: '#ff0066',
                 textColor: 'black',
                 borderColor: 'grey',
                 draggable: true,
                 editable: true,
                 selectable: true
             },
             {
                 url: 'assets/scripts/backend/load_timetable.php', // use the `url` property
                 data: {
                     custom_param1: val1,
                     custom_param2: val2,
                     custom_param3: 3,
                     _: new Date().getTime() // Cache-busting parameter
                 },
                 color: '#0099ff',
                 textColor: 'black',
                 borderColor: 'grey'
             }
         ],
         eventRender: function(event, element) {
             // Allow \n in event.title to render as multiple lines
             element.find('.fc-title').css('white-space', 'pre-line');
         },
     });
  });
