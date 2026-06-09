$(document).ready(function() {
    var script = document.currentScript ||
        /*Polyfill*/ Array.prototype.slice.call(document.getElementsByTagName('script')).pop();

    var params = (script.getAttribute('data-params') || '').split(/- */);
    var today = new Date().toISOString().slice(0,10);
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
         minTime: '07:00:00',
         maxTime: '19:00:00',
         navLinks: true,
         weekNumberCalculation: 'ISO',
         businessHours: {
             dow: [ 1, 2, 3, 4, 5 ],
             start: '8:00',
             end: '18:00'
         },
         eventConstraint: "businessHours",
         selectHelper: true,
         eventSources: [
             {
                 url: 'assets/scripts/backend/load_timetable.php',
                 data: {
                     custom_param1: val1,
                     custom_param2: val2
                 },
                 color: 'beige',
                 textColor: 'black',
                 borderColor: 'grey',
                 draggable: false,
                 editable: false,
                 selectable: false
             },
             {
                 url: 'assets/scripts/backend/load_holidays.php',
                 color: 'red',
                 textColor: 'black',
                 draggable: false,
                 editable: false,
                 selectable: false,
             }
         ],
     });
  });