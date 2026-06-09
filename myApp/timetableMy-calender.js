$(document).ready(function() {
    var script = document.currentScript ||
        /*Polyfill*/ Array.prototype.slice.call(document.getElementsByTagName('script')).pop();

    var params = (script.getAttribute('data-params') || '').split(/- */);
    var today = new Date().toISOString().slice(0,10);
    var val1 = params[0]; // -> 1
    var val2 = params[1]; //params[1]; // -> 3
    var val3 = params[3];
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
         defaultView: 'month',
         slotDuration: '00:15:00',
         minTime: '07:00:00',
         maxTime: '19:00:00',
         navLinks: true,
         weekNumbersWithinDays: true,
         weekNumberCalculation: 'ISO',
         businessHours: {
             dow: [ 1, 2, 3, 4, 5 ],
             start: '8:00',
             end: '18:00'
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
                 url: 'load_timetableMyBg.php',
                 data: {
                     custom_param1: val1,
                     custom_param2: val2,
                     custom_param3: val3
                 },
                 color: 'beige',
                 textColor: 'black',
                 borderColor: 'grey',
                 draggable: false,
                 editable: false,
                 selectable: false
             },
             {
                 url: 'load_timetableMy.php',
                 data: {
                     custom_param1: val1,
                     custom_param2: val2,
                     custom_param3: val3
                 },
                 color: '#16aaff',
                 textColor: 'black',
                 borderColor: 'grey'


             },
             {
                 url: 'load_holidays.php',
                 color: 'red',
                 textColor: 'black',
                 draggable: false,
                 editable: false,
                 selectable: false,
             }
         ],
        // selectable: true,
       editable: false,
         eventOverlap: false,
         eventRender: function(event, element) {
             var todayDt = moment().format('YYYY-MM-DD HH:mm:ss');
             var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
             if (start <  todayDt)
             {
                 event.editable = false;
             }
         },

//EVENT UPDATE
         eventResize: function (event, delta, revertFunc) {
             alert('You are not allowed to make changes');
             revertFunc();
            /* if(event.start <  Date.now()) {
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
                 var alertText = 'Do you need to reshedule the class to ' + titleDate + ' from '+ titleStart + ' to ' + titleEnd + '?';
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
             }*/
         },

//EVENT UPDATE ON RESIZE THE DATE
         eventDrop: function (event, delta, revertFunc) {
             //restrict to drop to passed dates
             alert('You are not allowed to make changes');
             revertFunc();
     /*        if(event.start < Date.now()) {
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
                  var alertText = 'Do you need to postpone the class to ' + titleDate + ' from '+ titleStart + ' to ' + titleEnd + '?';
                  var r = confirm(alertText);
                  if(r == true)
                     {
                         //alert("class changed  "+event.title +"on date of "+event.start.format("YYYY-MM-DD") +  titleStart + ' to ' + titleEnd );
                         $.ajax({
                             url: "timetable.php",
                             type: "POST",
                             data: { start: start, end: end, id: id, type: 13},
                             success: function (dataR) {
                                 alert(dataR);
                                alert("Data updated successfully !");
                                calendar.fullCalendar('refetchEvents');
                             }
                         });
                     }
                     else
                     {
                         revertFunc();
                     }
             }*/

         },
         eventClick: function (event,delta, revertFunc) {

/*           if(event.start <  Date.now()) {
                 alert('You are not allowed to Edit / Delete on passed dates !');
                 revertFunc();
             }else {
                 var id = event.id;
                 //alert(id);
                 //var alertText = 'Do you need to CANCEL  the class ?\n \n Please click on OK to cancel the class or click on cancel to exit from the message';
                 var alertText = 'Do you need to REQUEST CHANGE to Module Convenor ?\n \n Please click on OK to make a request or click on cancel to exit from the message';

                 var r = confirm(alertText);
                 if(r == true)
                 {


                   $.ajax({
                         url: "timetable.php",
                         type: "POST",
                         data: { id: id, type: 14},
                         success: function (dataR) {
                             //alert(dataR);
                             alert("Class canceled successfully !");
                             calendar.fullCalendar('refetchEvents');
                         }
                     });
                 }

          }*/
         }
     });
  });