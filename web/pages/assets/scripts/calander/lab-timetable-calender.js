$(document).ready(function() {
    var script = document.currentScript ||
        /*Polyfill*/ Array.prototype.slice.call(document.getElementsByTagName('script')).pop();
    var params = (script.getAttribute('data-params') || '').split(/- */);
    var val1 = params[0];
    var val2 = params[1];
   alert(val1);
    alert(val2);
     var calendar;
     calendar = $('#mcalendar').fullCalendar({

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
         maxTime: '22:00:00',
         navLinks: true, // can click day/week names to navigate views

         weekNumbers: true,
         weekNumbersWithinDays: true,
         weekNumberCalculation: 'ISO',
	slotEventOverlap : false,
	weekends : false,
         businessHours: {
             dow: [ 1, 2, 3, 4, 5 ],
             start: '8:00',
             end: '22:00'
         },
         eventConstraint: "businessHours",
         selectHelper: true,
         eventSources: [
             {
                 url: 'assets/scripts/backend/load_holidays.php',
                 color: 'lightcoral',
                 textColor: 'black'
                //rendering: 'background',
                // allDay:true
             }
         ],
         selectable: false,
         editable: false,
     });
  });
