var xmlHttp
var textContainer;

function stateChanged() 
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
		document.getElementById( textContainer ).innerHTML=xmlHttp.responseText 
		document.getElementById( textContainer ).style.visibility = 'visible';
	} 
}

function closeAjaxContainer() {
	document.getElementById( textContainer ).style.visibility = 'hidden';	
}

function GetXmlHttpObject()
{
	var xmlHttp=null;
	try
	 {
	 xmlHttp=new XMLHttpRequest();
	 }
	catch (e)
	 {
	 try
	  {
	  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
	  }
	 catch (e)
	  {
	  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	 }
	return xmlHttp;
}
