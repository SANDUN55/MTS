$(document).ready(function() {
    var script = document.currentScript ||
        /*Polyfill*/ Array.prototype.slice.call(document.getElementsByTagName('script')).pop();

    var params = (script.getAttribute('data-params') || '').split(/- */);

    var val1 = params[0]; // -> 1
    var val2 = params[1]; //params[1]; // -> 3
   // alert(val1);
    //alert(val2);
     var calendar;
     calendar = $('#calendar').fullCalendar({

         header: {
             left: 'prev,next today',
             center: 'title',
             right: 'month,agendaWeek,agendaDay'
         },
         defaultView: 'agendaWeek',
         slotDuration: '00:15:00',
         minTime: '07:00:00',
         maxTime: '19:00:00',
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


         /*
         selectAllow: function(select) {
             return moment().diff(select.start) <= 0
         },
*/

 /*        eventRender: function(eventObj, $el) {
             $el.popover({
                 title: eventObj.title,
                 content: eventObj.description,
                 trigger: 'hover',
                 html: true,
                 placement: 'top',
                 container: 'body'
             });
             $el.find('span.fc-event-title').html($el.find('span.fc-event-title').text());
         },*/
         //events: 'assets/scripts/backend/load_calander.php',
         eventSources: [
             // your event source
             {
                 url: 'assets/scripts/backend/load_calander.php', // use the `url` property
                 data: {
                     custom_param1: val1,
                     custom_param2: val2
                 },
                 color: 'yellow',
                 textColor: 'black'
             },
             {
                 url: 'assets/scripts/backend/load_holidays.php', // use the `url` property
                 color: 'red',
                 textColor: 'black',
                 draggable: false,
                 editable: false,
                 selectable: false,
                 //rendering: 'background',
                 //allDay:true
             }
         ],
         eventColor: '#378006',
         eventTextColor: '#fff',
         selectable: true,
         editable: true,
         eventOverlap: false,
         /*
         eventRender: function(event, element, view)
         {
             if (event.allDay == true)
             {
                 element.css("color", "red");
                 element.css("font-weight", "bold");
                 element.css("font-size", "1.8em");
                 element.text(event.start.format("HH:mm"));
             }
         },*/

//ADD EVENT
         /* ----working propwerly, edite to check holidays
          select: function (start, end, allDay) {
          endtime = $.fullCalendar.moment(end).format('h:mm');
          starttime = $.fullCalendar.moment(start).format('MMMM Do YYYY, h:mm');
          var mywhen = starttime + ' - ' + endtime;
          start = moment(start).format();
          end = moment(end).format();
          $('#createEventModal #stTime').val(start);
          $('#createEventModal #enTime').val(end);
          $('#createEventModal #modalWhen').text(mywhen);
          $('#createEventModal').modal('toggle');

          },*/

         select: function (start, end, allDay) {
                 endtime = $.fullCalendar.moment(end).format('h:mm');
                 starttime = $.fullCalendar.moment(start).format('MMMM Do YYYY, h:mm');
                 var mywhen = starttime + ' - ' + endtime;
                 start = moment(start).format();
                 end = moment(end).format();
             //check for holidays
             $.ajax({
                 url: "assets/scripts/backend/view_class.php",
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
                 var title = event.title;
                 var id = event.id;
                 var titleDate = $.fullCalendar.formatDate(event.start, "MMMM Do YYYY");
                 var titleStart = $.fullCalendar.formatDate(event.start, "h:mm");
                 var titleEnd = $.fullCalendar.formatDate(event.end, "h:mm");
                 var modalTitle = titleDate + '  :  ' + titleStart + ' to ' + titleEnd;
                 $('#editEventModal #stTime').val(start);
                 $('#editEventModal #enTime').val(end);
                 $('#editEventModal #classID').val(id);
                 $('#editEventModal #classDescription').val(title);
                 $('#editEventModal #modalWhen').text(modalTitle);
                 $('#editEventModal').modal('toggle');
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
                  //Do this function through Dialog box condidering postpone
                     //if postpone, current class id status=3
                     //and new class id
                  var r = confirm(alertText);
                  if(r == true)
                     {
                         //alert("class changed  "+event.title +"on date of "+event.start.format("YYYY-MM-DD") +  titleStart + ' to ' + titleEnd );
                         $.ajax({
                             url: "assets/scripts/backend/view_class.php",
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
//EVENT DELETE
         eventClick: function (event,delta, revertFunc) {
             if(event.start <  Date.now()) {
                 alert('You are not allowed to delete on passed dates');
                 revertFunc();
             }else {
                 if (confirm("Are you sure you want to remove it?")) {
                     var id = event.id;
                     $.ajax({
                         url: "assets/scripts/backend/view_class.php",
                         type: "POST",
                         data: {id: id, type: 5},
                         success: function () {
                             calendar.fullCalendar('refetchEvents');
                             alert("Event Removed");
                         }
                     })
                 }
             }
         },

     });
  });