
/**
 * Document   : Auto Logout Script
 * Author     : josephtinsley
 * Description: Force a logout automatically after a certain amount of time using HTML/JQuery/PHP. 
 * http://twitter.com/josephtinsley 
*/
$(function()
{
    function timeChecker()
    {
        setInterval(function()
        {
            var storedTimeStamp = sessionStorage.getItem("lastTimeStamp");  
            timeCompare(storedTimeStamp);
        },6000);
    }
    function timeCompare(timeString)
    {

        var timeDiff = 0;
		var minPast = 0;
		var maxMinutes  = 5;  //GREATER THEN 1 MIN.
        var currentTime = new Date();
        var pastTime    = new Date(timeString);
        var timeDiff    = currentTime - pastTime;
        var minPast     = Math.floor( (timeDiff/60000) );
        //console.log(timeDiff +" -timeDiff- "+ minPast+" -minPast- ");
        if( minPast > maxMinutes)
        {
            sessionStorage.setItem("lastTimeStamp",'');
			sessionStorage.removeItem("lastTimeStamp");
			localStorage.clear();
			sessionStorage.clear();
            window.location = "../logout.php";
            return false;
        }else
        {
            //JUST ADDED AS A VISUAL CONFIRMATION
           // console.log(currentTime +" - "+ pastTime+" - "+minPast+" min past");
            console.log(minPast+" min past");
        }
    }
    if(typeof(Storage) !== "undefined") 
    {
        localStorage.clear();
		sessionStorage.clear();
		sessionStorage.removeItem("lastTimeStamp");
        sessionStorage.setItem("lastTimeStamp",'');
		$(document).mousemove(function()
        {
            var timeStamp = new Date();
            sessionStorage.setItem("lastTimeStamp",timeStamp);
        });
        timeChecker();
    } else {
			alert(typeof(Storage));
	}		
});


