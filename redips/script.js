/*jslint white: true, browser: true, undef: true, nomen: true, eqeqeq: true, plusplus: false, bitwise: true, regexp: true, strict: true, newcap: true, immed: true, maxerr: 14 */
/*global window: false, REDIPS: true */

/* enable strict mode */
"use strict";

// define redips_init variable
var redips_init;

// redips initialization
redips_init = function () {
	// reference to the REDIPS.drag lib
	var rd = REDIPS.drag;
	var stat = document.getElementById('status');
	// initialization
	rd.init();
	// dragged elements can be placed to the empty cells only
	rd.drop_option = 'single';
	// elements could be cloned with pressed SHIFT key
	rd.clone_shiftKey = true;
	// define dropped handler
	rd.myhandler_dropped = function (target_cell) {
		var tbl,	// table reference of dropped element
			id,		// id of scrollable container
			msg;	// message
		// find table of target cell
		tbl = rd.find_parent('TABLE', target_cell);
		// test if table belongs to scrollable container
		if (tbl.sca !== undefined) {
			// every table has defined scrollable container (if table belongs to scrollable container)
			// scrollable container has reference to the DIV container and DIV container has Id :)
			id = tbl.sca.div.id;
			// prepare message according to container Id
			// here can be called handler_dropped for scrollable containers
			switch (id) {
			case 'left':
				msg = 'Posisi CY';
				break;
			case 'right':
				msg = 'Posisi Kapal';
				break;
			default:
				msg = 'Container without Id';
			}
		}
		// table does not belong to any container
		else {
			msg = 'Table does not belong to any container!';
		}
		// display message
		document.getElementById('message').innerHTML = msg;
	};
	
	rd.myhandler_clicked = function () {
		stat.innerHTML = 'Clicked';
	};
	
	rd.myhandler_moved  = function () {
		stat.innerHTML = 'Moved';
	};
	
	rd.myhandler_notmoved = function () {
		stat.innerHTML = 'Not moved';
	};
	
	rd.myhandler_dropped = function () {
		stat.innerHTML = 'Dropped';
	};
	
	rd.myhandler_changed = function () {
			
		// get target and source position (method returns positions as array)
		var pos = rd.get_position();
		// display current row and current cell
		stat.innerHTML = 'Position Changed: Row ' + pos[1] + ' Cell ' + pos[2];
	};
};
 

// add onload event listener
if (window.addEventListener) {
	window.addEventListener('load', redips_init, false);
}
else if (window.attachEvent) {
	window.attachEvent('onload', redips_init);
}