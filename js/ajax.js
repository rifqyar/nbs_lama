function getObject( oid ) {
	if ( document.getElementById ) { 
		return document.getElementById( oid );	
	} else if ( document.all ) {
		return document.all[ oid ];		// old IE
	} else if ( document.layers ) {
		return document.layers[ oid ];	// old NS
	} 
	return false;
}

function submitForm(f) {
	var o = getObject(f);
	if (o) o.submit();
}

function resetForm(f) {
	var o = getObject(f);
	if (o) o.reset();		
}

function cancelForm(f) {
	window.history.back();
}

function moveContent( source, target ) {
	var o = getObject( source );
	var p = getObject( target );
	
	if (o && p) {
		p.innerHTML = o.innerHTML;
		o.innerHTML = '';
	}
}

function decSelect( oid ) {
	var o = getObject( oid );
	if (o) {
		o.selectedIndex--;
		o.form.submit();
	}
}

function incSelect( oid ) {
	var o = getObject( oid );
	if (o) {
		o.selectedIndex++;	
		o.form.submit();
	}
}

function loadPage( url ) {
	window.location.href = url;	
}

function setVisible( oid, v ) {
	var o = getObject( oid );
	if (o) {
		if (v) {
			o.style.visibility  = 'visible';
			o.style.display		= 'block';
		} else {
			o.style.visibility  = 'hidden';
			o.style.display		= 'none';
		}
	}
}

function setEnable( oid, v ) {
	var o = getObject( oid );
	
	if (o) {
		if (o.tagName=='FORM' || o.tagName=='form') {
			for (var i=0;i<o.elements.length;i++)
				if (o.elements[i].disabled) o.elements[i].disabled = !v;
		} else {
			if (o.disabled) o.disabled = !v;
		}
	}	
}

function setProperty( oid, prop, v ) {
	var o = getObject( oid );
	if (o) {
		o.setAttribute( prop, v );
	}			
}

function setCSSProperty( oid, prop, v ) {
	var o = getObject( oid );
	if (o) {
		o.style.setProperty( prop, v , '');
	}	
}

var xmlbank = new Array();

function GetXmlHttpObject( uid )
{	
	if (uid && xmlbank[uid]) return xmlbank[uid];
	
	var xmlHttp=null;
	try {
	  xmlHttp=new XMLHttpRequest();
	} catch (e) {
	  try {
	    xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
	  } catch (e) {
	    xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	}

	xmlbank[uid] = xmlHttp;
	
	return xmlHttp;
}

//=========== LOADER ===========
function loadContent( oid, url ) {
	
	var xml = GetXmlHttpObject();
	if (xml) {
		var o = new Object();
		
		o.url 		    		 = url;
		o.elementId     		 = oid;
		o.xml 					 = xml;
		o.xml.onreadystatechange = function() {	
				if (o.xml) {
					if (o.xml.readyState==1 || o.xml.readyState=="loading")
					{
						// todo.. mask object..
						old = getObject( o.elementId ).innerHTML; 
						getObject( o.elementId ).innerHTML = 
							"<table border='0'  style='width:100%;height:100%;background-image:url(images/yellow-60.png);'><tr><td style='background-image:url(images/anim-load.gif); background-repeat:no-repeat; background-position:center center;'>" + old + "</td></tr></table>";
					} else if (o.xml.readyState==2 || o.xml.readyState=="loaded")
					{
					} else if (o.xml.readyState==4 || o.xml.readyState=="complete")
					{ 						
						try {
							// JSON..
							var response = eval( o.xml.responseText );
							getObject( o.elementId ).innerHTML = unescape(response[0]);
							if (response[1]) eval(unescape(response[1]));
						} catch (e) {
							// raw..
							var response = o.xml.responseText;
							getObject( o.elementId ).innerHTML = response;							
						}
					} 			
					
				} else {
					window.alert('no xmlhttp object!'+o);	
				}			
			};
			
		o.xml.open("GET",url,true);
		o.xml.send(null);
		
	} else {
		// todo: kasih tau gak punya XmlHttp..	
		window.alert('tidak dapat membuat XMLHttp Object');
	}
}


// preload..
function preloadImage( images ) {
	var pimg = new Array();
	var i;
	for (i=0;i<images.length;i++) {
		pimg[i] 	= new Image();
		pimg[i].src = images[i];		
	}
}

preloadImage( [] );


function initMenus() {
	$('ul.menu ul').hide();
	$.each($('ul.menu'), function(){
		$('#' + this.id + '.expandfirst ul:first').show();
	});
	$('ul.menu li a').click(
		function() {
			var checkElement = $(this).next();
			var parent = this.parentNode.parentNode.id;

			if($('#' + parent).hasClass('noaccordion')) {
				$(this).next().slideToggle('normal');
				return false;
			}
			if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
				if($('#' + parent).hasClass('collapsible')) {
					$('#' + parent + ' ul:visible').slideUp('normal');
				}
				return false;
			}
			if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
				$('#' + parent + ' ul:visible').slideUp('normal');
				checkElement.slideDown('normal');
				return false;
			}
		}
	);
}
