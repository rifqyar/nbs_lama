<html>
<head>
<script type="text/javascript">
// really really simple to get the time from the server

// this is for an ASP server:
//var serverTime = <%= DateDiff("s",#1/1/1970#,Now())%>;

// PHP Servers: 
 var serverTime = <?php echo time(); ?>;

// serverTime will hold the number of seconds since 1/1/1970 0:00:00 as it is on the server
// we must adjust it to compensate for GMT offset
// My server is on Pacific Daylight Time, so 7 hours offset:
serverTime += 0 * 3600; // 7 hours, 3600 seconds per hour
// NOTE:  This adjustment MAY NOT be needed for PHP servers...I dunno.


//
// so calculate how far off the client is from the server:
var now = new Date();
var clientTime = now.getTime(); // number of *milliseconds* since 1/1/1970 0:00:00 on client
serverTime *= 1000; // convert server time to milliseconds, as well

// the client and server disagree by this much:
var clockOff = serverTime - clientTime; // could well be negative!  
// hopefully you can see, from basic algebra, that
//    serverTime = clientTime + clockOff

// we want target to be 0:00:00 of next day
var target = new Date( serverTime ); // serverTime in JS form
// so bump the day by one and leave time as 0,0,0:
target = new Date( target.getFullYear(), target.getMonth(), target.getDate() + 1 );

// and now ready for our timer countdown:
var ticker = null; // the interval timer

function countdown( )
{
    var clientNow = ( new Date() ).getTime();
    var serverNow = clientNow + clockOff; // see? this is where we use that offset!
    var millisLeft = target.getTime() - serverNow; // milliseconds until server target time
    var secsLeft = Math.round( millisLeft / 1000 );
    var timeLeft;
    var timeLeftWords;
    if ( secsLeft <= 0 )
    {
        timeLeftWords = "Expired!";
        timeLeft = "0 days 00:00:00";
        clearInterval( ticker );
    } else {
        var secs = secsLeft % 60;
        var minsLeft = Math.floor( secsLeft / 60 );
        var mins = minsLeft % 60;
        var hrsLeft = Math.floor( minsLeft / 60 );
        var hrs = hrsLeft % 24;
        var days = Math.floor( hrsLeft / 24 );
        timeLeftWords = days + " days, " + hrs + " hours, " + mins + " minutes, " + secs + " seconds";
        timeLeft =
                 + (hrs < 10 ? "0" : "" ) + hrs + ":"
                 + (mins < 10 ? "0" : "" ) + mins + ":"
                 + (secs < 10 ? "0" : "" ) + secs;
 
    }
    
    document.getElementById("ticktock").innerHTML = timeLeft;
}

function startup( )
{
    countdown(); // first time
    setInterval( countdown, 1000 ); // then once a second
}
</script>
</head>
<body onload="startup()">

Time: <span id="ticktock"></span><br/>

</body>
</html>