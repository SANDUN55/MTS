$(document).ready(function() {
    var script = document.currentScript ||
        /*Polyfill*/ Array.prototype.slice.call(document.getElementsByTagName('script')).pop();
    var params = (script.getAttribute('data-params') || '').split(/- */);
    var val1 = params[0]; // -> 1
    var val2 = params[1]; //params[1]; // -> 3
   //alert(val1);
   // alert(val2);
    $(window).focus();
    let ctrlIsPressed = false;

    function setEventsCopyable(isCopyable) {
        ctrlIsPressed = !ctrlIsPressed;
        $("#calendar").fullCalendar("option", "eventStartEditable", !isCopyable);
        $(".fc-event").draggable("option", "disabled", !isCopyable);
    }

    $(document).keydown(function(e) {
        if (e.ctrlKey && !ctrlIsPressed) {
            setEventsCopyable(true);
        }
    });

    // if control has been released stop events being copyable
    $(document).keyup(function(e) {
        if (ctrlIsPressed) {
            setEventsCopyable(false);
        }
    });




     var calendar;
    $('#external-events .fc-event').each(function() {

        // store data so the calendar knows to render an event upon drop
        $(this).data('event', {
            id : $(this).attr("data-event"),
            title: $.trim($(this).text()), // use the element's text as the event title
            stick: true // maintain when user navigates (see docs on the renderEvent method)
        });

        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 999, revert: false,      // will cause the event to go back to its
           // revertDuration: 0  //  original position after the drag
        });
    });
    $('#external-events2 .fc-event').each(function() {
        $(this).data('event', {
            id : $(this).attr("data-event"),
            title: $.trim($(this).text()),
            stick: true
        });
        $(this).draggable({
            zIndex: 999, revert: false,
        });
    });
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
         weekends : false,
         businessHours: {
             dow: [ 1, 2, 3, 4, 5 ],
             start: '8:00',
             end: '18:00'
         },
         eventConstraint: "businessHours",
         selectHelper: true,
         selectAllow: function(info) {
             if (info.start < Date.now())
                 return false;
             return true;
         },
         droppable: true,
         validRange: function() {
             var bmod = val1 + ',' + val2;
            var paramDates=   $.ajax({
                 url: "assets/scripts/backend/get_val.php",
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
                 url: 'assets/scripts/backend/load_timetable.php', // use the `url` property
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
                 url: 'assets/scripts/backend/load_timetable.php', // use the `url` property
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

         drop: function(event, delta, revertFunc) {
             // is the "remove after drop" checkbox checked?
            // alert(event);
             endtime = $.fullCalendar.moment(end).format('h:mm');
             starttime = $.fullCalendar.moment(start).format('MMMM Do YYYY, h:mm');

             start = moment(start).format();
             end = moment(end).format();
            // var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
            // var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
            // var title = event.title;
            // var id = event.id;
            alert(start);
            alert(end);
             //alert(title);
             //alert(id);
             if ($('#drop-remove').is(':checked')) {
                 // if so, remove the element from the "Draggable Events" list
                 $(this).remove();
             }
         },

//EVENT ADD
 select: function (start, end, allDay) {
         endtime = $.fullCalendar.moment(end).format('h:mm');
         starttime = $.fullCalendar.moment(start).format('MMMM Do YYYY, h:mm');
         var mywhen = starttime + ' - ' + endtime;
         start = moment(start).format();
         end = moment(end).format();
     //check for holidays
         $.ajax({
             url: "assets/scripts/backend/timetable.php",
             type: "POST",
             data: { start: start, type: 4},
             success: function (dataResult) {
                 var dataResult = JSON.parse(dataResult);
                 if (dataResult.statusCode == 0) {
                     $('#createEventModal #stTime').val(start);
                     $('#createEventModal #enTime').val(end);
                     $('#createEventModal #modalWhen').text(mywhen);
                     $('#createEventModal').modal('toggle');
                 }
                 else if (dataResult.statusCode == 1) {
                     alert('You are not allowed to schedule class on Holidays!');
                     calendar.fullCalendar('refetchEvents');
                 }
             }
         });
 },
//EVENT UPDATE
         eventResize: function (event, delta, revertFunc) {
             if(event.start <  Date.now()) {
                 alert('You are not allowed to change passed dates');
                 revertFunc();
             }else {
                 var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                 var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                 //var title = event.title;
                 var id = event.id;
                 //alert(start);
                 //alert(end);
                 //alert(title);
                 //alert(id);
                 var titleDate = $.fullCalendar.formatDate(event.start, "MMMM Do YYYY");
                 var titleStart = $.fullCalendar.formatDate(event.start, "h:mm a");
                 var titleEnd = $.fullCalendar.formatDate(event.end, "h:mm a");
                 //var modalTitle = titleDate + '  :  ' + titleStart + ' to ' + titleEnd;
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
             if(event.start <  Date.now()) {
                 alert('You are not allowed to change class into passed dates');
                 revertFunc();
             }else
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

         },
         eventClick: function (event,delta, revertFunc) {
             if(event.start <  Date.now()) {
                 alert('You are not allowed to Edit / Delete on passed dates !');
                 revertFunc();
             }else {
                 var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                 var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                 var title = event.title;
                 var id = event.id;
                 var descp = event.description;
                 //alert(descp);
                 //alert(title);
                 var titleDate = $.fullCalendar.formatDate(event.start, "dddd, MMMM Do YYYY");
                 var titleStart = $.fullCalendar.formatDate(event.start, "h:mm a" );
                 var titleEnd = $.fullCalendar.formatDate(event.end, "h:mm a");
                 var modalTitle = titleDate + '  :  ' + titleStart + ' to ' + titleEnd;
                 //alert(start);
                 $('#editEventModal #startTime').val(start);
                 $('#editEventModal #endTime').val(end);
                 $('#editEventModal #classID').val(id);
                 $('#editEventModal #classTitle').val(title);
                 $('#editEventModal #classDescription').val(descp);
                 $('#editEventModal #modalWhen').text(modalTitle);
                 $('#deleteClassModal #id_d').val(id);
                 $('#deleteClassModal #delmodalWhen').text(modalTitle);
                 $('#cancelClassModal #id_c').val(id);
                 $('#cancelClassModal #cnclmodalWhen').text(modalTitle);
                 $('#postponeClassModal #id_p').val(id);
                 $('#postponeClassModal #postpndmodalWhen').text(modalTitle);
                 $('#rescheduleEventModal #id_r').val(id);
                 $('#rescheduleEventModal #resmodalWhen').text(titleDate);
                 $('#editDeleteEventModal #modalWhen').text(modalTitle);
                 $('#editDeleteEventModal').modal('toggle');
             }
         }


     });
  });