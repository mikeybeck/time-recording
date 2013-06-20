<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<style>
h1 {
display: inline-block;
margin-right: 20px;
width: 300px;
}
</style>

<script type="text/javascript">
/* Mikey Beck's  Javascript time recorder using HTML5 storage.   Say hi to me at mikeybeck.com.

Version 0.8. 

*/

var c=parseInt(localStorage.c);
var a;
var t;
var timer_is_on=0;

var started = true;


// Add leading-zeros to numbers less than 10[000...]
function padZ(num, n) {
    n = n || 1; // Default assume 10^1
    return num < Math.pow(10, n) ? "0" + num : num;
}

function getDate() {
	var d = new Date();
    var day = d.getDate();
    var month = d.getMonth() + 1; // Note the `+ 1` -- months start at zero.
    var year = d.getFullYear();
    var hour = d.getHours();
    var min = d.getMinutes();
    var sec = d.getSeconds();
    return day+"/"+month+"/"+year+" "+hour+":"+padZ(min)+":"+padZ(sec);
}



function clearAll() {
	localStorage.clear();
	localStorage.notfirsttime = "false";
	started = false;
	startstop();
	alert("cleared!");
}

function stop() {
	clearTimeout(t);
	if (timer_is_on) {
		timer_is_on=0;
		//date = new Date();
		date = getDate();
		activity = getActivity(1);
		
		var d = new Date();
		var time = d.getTime();
		var elapsed_time = time - localStorage.time;
		/*
		x = ms / 1000
		seconds = x % 60
		x /= 60
		minutes = x % 60
		x /= 60
		hours = x % 24
		x /= 24
		days = x
		*/
		var ms = elapsed_time;
		var x = ms/1000; //x becomes a few things, hence the stupid variable name
		x = Math.floor(x);
		var seconds = x % 60;
		x = x / 60;
		x = Math.floor(x);
		var minutes = x % 60;
		x = x / 60;
		x = Math.floor(x);
		var hours = x % 24;
		x = x / 24;
		x = Math.floor(x);
		var days = x;
		
		elapsed_time = hours + " hours, " + minutes + " minutes, " + seconds + " seconds";
		
		localStorage.setItem(activity, localStorage.getItem(activity) + " Stopped: " + date + " Time taken: " + elapsed_time + "</div>");
	}
}

function counter() {
	//if (!c in window) {
	if (isNaN(c)) {
		c = 0;
	}
	document.getElementById('count').value=c;
	t=setTimeout("counter()",1000);
	//localStorage.setItem(getActivityName(), c);
	localStorage.c = c;
	c=c+1;
}

function timer() {
if (!timer_is_on) {
	timer_is_on=1;
	counter();
  }
}

function getActivityName() {
	return document.getElementById('activity').value;
}

function getID(x) {
	var i=parseInt(localStorage.i);
	if (isNaN(i)) {
		i = 0;
	}
	if (x != 1) {
		i+=1;
	}
	localStorage.i = i;
	return i;
}

function getActivity(x) {
	return '<div class="entry">'+'<h1 class="ID_Activity">'+getID(x) + ":" + getActivityName()+"</h1>"; //this appends a number to the activity name and therefore prevents overwriting
}

function start(x) { //if x is 1, the page has been refreshed or something AND the timer has started, so carry on and don't reset anything

	var d = new Date();
    var time = d.getTime();
	
	if (x != 1) { // x is not 1, therefore the timer is stopped, so carry on and feel free to reset things etc
		localStorage.time = time;
		var activity;
		activity = getActivity(0);
		date = getDate();
		localStorage.setItem(activity, 'Started: ' + date);
	} else {
		replaceButtonText('startstopbutton', 'stop'); //make sure the button says stop, as the timer is currently going
	}
	timer();
}

function display() {
	for (var i = 0; i < localStorage.length-7; i++){ //length-7 in order to not display the extra stuff (i & j values etc)
		if (document.getElementById(i) == null) {
			if (i == 0) {
				localStorage.j = -1;
			}
			creatediv();
		}
		document.getElementById(i).innerHTML=(localStorage.key(i) + localStorage.getItem(localStorage.key(i)));
	}
}


function replaceButtonText(buttonId, text)
{
  if (document.getElementById)
  {
    var button=document.getElementById(buttonId);
    if (button)
    {
      if (button.childNodes[0])
      {
        button.childNodes[0].nodeValue=text;
      }
      else if (button.value)
      {
        button.value=text;
      }
      else //if (button.innerHTML)
      {
        button.innerHTML=text;
      }
    }
  }
}

function startstop() {
	var text_box = document.getElementById('activity');
	localStorage.text_box = text_box.value;
	if( localStorage.started == "true" ){
        text_box.setAttribute('readonly', 'readonly');   
  		replaceButtonText('startstopbutton', 'stop');
		started = false;
		localStorage.started = started;
		start();
		creatediv();
		display();
  }else{
	text_box.removeAttribute('readonly');
	replaceButtonText('startstopbutton', 'start');
	started = true;
	localStorage.started = started;
	stop();
	display();
  }
}

function creatediv() {
	var j=parseInt(localStorage.j);
	var notfirsttime = localStorage.notfirsttime;
	if (notfirsttime != "true") {
		j = -1;
		localStorage.notfirsttime = "true";
	}
	
	if (isNaN(j)) {
		j = -1;
	}
	j+=1;
	localStorage.j = j;
	
	
    var newdiv = document.createElement('div');
	newdiv.id = j;
    document.body.appendChild(newdiv);
}

window.onload = function () {
// Whatever you put in here will be executed on page load..
	display();
	if (localStorage.started == "false") { //dont ask why this is false rather than true.  I'm not really sure. Perhaps i have the values round the wrong way
		var text_box = document.getElementById('activity');
		text_box.value=localStorage.text_box;
		text_box.setAttribute('readonly', 'readonly');  
		start(1);
	}
};

</script>
</head>
<body>
<form>

<input type="button" value="Start!" onclick="startstop()" id="startstopbutton" />

<input type="button" value="Display!" onclick="display()" />
<input type="text" id="count" />
<input type="button" value="reset!" onclick="clearAll()" />

<input type="text" id="activity" />

</form>


</body>
</html>
