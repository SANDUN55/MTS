$(document).ready(function() {
    var script = document.currentScript ||
        /*Polyfill*/ Array.prototype.slice.call(document.getElementsByTagName('script')).pop();
    var params = (script.getAttribute('data-params') || '').split(/- */);

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
         minTime: '06:30:00',
         maxTime: '19:00:00',
         navLinks: true, // can click day/week names to navigate views

         weekNumbers: true,
         weekNumbersWithinDays: true,
         weekNumberCalculation: 'ISO',
	slotEventOverlap : false,
	weekends : true,
         businessHours: {
             dow: [ 1, 2, 3, 4, 5 ],
             start: '6:00',
             end: '18:00'
         },
         eventConstraint: "businessHours",
         selectHelper: true,
         eventSources: [
             {
                 url: 'load_batchtimetable.php?custom_param1=' + params,
                 color: 'ghostwhite',
                 textColor: 'black',
                 borderColor: 'grey'
             }
             ,
             {
                 url: 'load_holidays.php',
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
