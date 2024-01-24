<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CSS Image Map - With Tooltip</title>
<style type="text/css">
ul#continents {
	list-style: none;
	background: url(images/NEW_Uster_Layout.png);
	position: relative;
	width: 1550px;
	height: 700px;	
	margin: 0;
	padding: 0;
}

ul#continents li {
	position: absolute;
}

ul#continents li a{
	display: block;
	height: 100%;
	text-decoration: none;
}

#northamerica {
	width: 227px;
	height: 142px;
	top: 2px;
	left: 0px;
}

#southamerica {
	width: 108px;
	height: 130px;
	top: 131px;
	left: 76px;
}

#africa {
	width: 120px;
	height: 140px;
	top: 83px;
	left: 209px;
}

#europe {
	width: 120px;
	height: 84px;
	top: 1px;
	left: 211px;
}

#asia {
	width: 215px;
	height: 175px;
	top: 1px;
	left: 283px;
}

#australia {
	width: 114px;
	height: 95px;
	top: 152px;
	left: 432px;
}

ul#continents li a:hover {
	background: url(images/NEW_Uster_Layout.png);
}

ul#continents li#northamerica a:hover {
	background-position: 0 -270px;
}

ul#continents li#southamerica a:hover {
	background-position: -226px -273px;
}

ul#continents li#africa a:hover {
	background-position: -209px -417px;
}

ul#continents li#europe a:hover {
	background-position: -22px -427px;
}

ul#continents li#asia a:hover {
	background-position: -363px -268px;
}

ul#continents li#australia a:hover {
	background-position: -412px -455px;
}

ul#continents li a span {
	display: none;
}

ul#continents li a:hover span {
	display: block;
}

ul#continents li a:hover span {
	display: block;
	padding: 5px;
	width: 150px;
	background: #000;
	position: relative;
	top: 50%;
	font: 11px Arial, Helvetica, sans-serif;
	opacity: .75;
	filter:alpha(opacity=75);
	color: #FFF;
}

ul#continents li a:hover span strong {
	display: block;
	margin-bottom: 2px;
	font-size: 12px;
	text-decoration: underline;
}

</style>
</head>

<body>
<ul id="continents">
    <li id="northamerica">
        <a href="http://en.wikipedia.org/wiki/North_America">
            <span>
                <strong>North America</strong> 
                Population: 528,720,588
            </span>
        </a>
    </li>
    <li id="southamerica">
   		<a href="http://en.wikipedia.org/wiki/South_America">
            <span>
                <strong>South America</strong>
                Population: 385,742,554
            </span>
        </a>
    </li>    
    <li id="asia">
    	<a href="http://en.wikipedia.org/wiki/Asia">
            <span>
                <strong>Asia</strong>
                Population: 3,879,000,000
            </span>
        </a>
    </li>
    <li id="australia">
    	<a href="http://en.wikipedia.org/wiki/Australia">
            <span>
                <strong>Australia</strong>
                Population: 21,807,000
            </span>
        </a>
    </li>
    <li id="africa">
        <a href="http://en.wikipedia.org/wiki/Africa">
        <span>
            <strong>Africa</strong>
            Population: 922,011,000
        </span>
        </a>
    </li>
    <li id="europe">
        <a href="http://en.wikipedia.org/wiki/Europe">
            <span>
                <strong>Europe</strong>
                Population: 731,000,000
            </span>
        </a>
    </li>    
</ul>
</body>
</html>