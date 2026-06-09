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
         minTime: '06:00:00',
         maxTime: '22:00:00',
         navLinks: true,
         weekNumbers: true,
         weekNumbersWithinDays: true,
         weekNumberCalculation: 'ISO',
         businessHours: {
             dow: [ 1, 2, 3, 4, 5 ],
             start: '6:00',
             end: '22:00'
         },
         eventConstraint: "businessHours",
         selectHelper: true,
         selectAllow: function(info) {
             if (event.start <  Date.now())
                 return false;
             return true;
         },
         eventSources: [
             {
                 url: 'assets/scripts/backend/load_timetableMyBg.php',
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
                 url: 'assets/scripts/backend/load_timetableMy.php',
                 data: {
                     custom_param1: val1,
                     custom_param2: val2
                 },
                 color: '#16aaff',
                 textColor: 'black',
                 borderColor: 'grey'

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
        // selectable: true,
        editable: true,
         eventOverlap: false,
//EVENT UPDATE
         eventResize: function (event, delta, revertFunc) {
             if(event.start <  Date.now()) {
                 alert('You are not allowed to change passed dates');
                 revertFunc();
             }else {
                 var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                 var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                 var title = event.title;
                 var id = event.id;
                 var titleDate = $.fullCalendar.formatDate(event.start, "MMMM Do YYYY");
                 var titleStart = $.fullCalendar.formatDate(event.start, "h:mm a");
                 var titleEnd = $.fullCalendar.formatDate(event.end, "h:mm a");
                 var modalTitle = titleDate + '  :  ' + titleStart + ' to ' + titleEnd;
                 var alertText = 'Do you need to change the class to ' + titleDate + ' from '+ titleStart + ' to ' + titleEnd + '?';
                 var r = confirm(alertText);
                 if(r == true)
                 {
                     //alert("class changed  "+event.title +"on date of "+event.start.format("YYYY-MM-DD") +  titleStart + ' to ' + titleEnd );
                     $.ajax({
                         url: "assets/scripts/backend/timetable.php",
                         type: "POST",
                         data: { start: start, end: end, id: id, type: 3},
                         success: function () {
                             alert("Data updated successfully !");
                             calendar.fullCalendar('refetchEvents');
                         }
                     });
                 }
                 else
                 {
                     revertFunc();
                 }
             }
         },
//EVENT UPDATE ON RESIZE THE DATE
         eventDrop: function (event, delta, revertFunc) {
             //restrict to drop to passed dates

             if(event.start < Date.now()) {
                 alert('You are not allowed to change class into passed dates');
                 revertFunc();

             } else
                 {
                  var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                  var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                  var title = event.title;
                  var id = event.id;
                  var titleDate=$.fullCalendar.formatDate(event.start, "MMMM Do YYYY");
                  var titleStart=$.fullCalendar.formatDate(event.start, "h:mm");
                  var titleEnd = $.fullCalendar.formatDate(event.end, "h:mm");
                  var alertText = 'Do you need to change the class to ' + titleDate + ' from '+ titleStart + ' to ' + titleEnd + '?';
                  var r = confirm(alertText);
                  if(r == true)
                     {
                         //alert("class changed  "+event.title +"on date of "+event.start.format("YYYY-MM-DD") +  titleStart + ' to ' + titleEnd );
                         $.ajax({
                             url: "assets/scripts/backend/timetable.php",
                             type: "POST",
                             data: { start: start, end: end, id: id, type: 3},
                             success: function () {
                                alert("Data updated successfully !");
                                calendar.fullCalendar('refetchEvents');
                             }
                         });
                     }
                     else
                     {
                         revertFunc();
                     }
             }

         }
     });
  });
