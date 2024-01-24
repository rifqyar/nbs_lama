// JavaScript Document
/********************************************
* 	Filename:	js/init.js
*	Author:		Ahmet Oguz Mermerkaya
*	E-mail:		ahmetmermerkaya@hotmail.com
*	Begin:		Sunday, April 20, 2008  16:22
***********************************************/


/**
 * initialization script 
 */


function languageManager() {
	this.lang = "en";
		
	this.load = function(lang) {
		this.lang = lang
		this.url = location.href.substring(0, location.href.lastIndexOf('/'));
		document.write("<script language='javascript' src='/js/jqtree/langs/"+this.lang+".js'></script>");
	}
	
	this.addIndexes= function() {
		for (var n in arguments[0]) { 
			this[n] = arguments[0][n]; 
		}
	}	
}

var langManager = new languageManager();

langManager.addIndexes({
	error_1:'An error occured in the operation',
	error_2:'The element with the same name already exists',
	willReload:'Page will be reloaded.',
	deleteConfirm:'Are you sure you want to delete this item?',
	doOneOperationAtATime:'Only one operation can be active at any time.',
	operationInProcess:'<img src="images/ajax-loader.gif" align="absmiddle"> Completing your request.',
	selectNode2MakeOperation:'To make an operation please click an item to activate it.',
	addDocMenu:'Add file',
	addFolderMenu:'Add folder',
	editMenu:'Edit',
	deleteMenu:'Delete',
	missionCompleted:'Request is completed succesfully.'
});

//langManager.load("en");  

var treeOps = new TreeOperations();

$(document).ready(function() {
	
	// binding menu functions
	$('#myMenu1 .addDoc').click(function()  {  treeOps.addElementReq(); });									   						    
	$('#myMenu1 .addFolder').click(function()  {  treeOps.addElementReq(true); });	
	$('#myMenu1 .edit, #myMenu2 .edit').click(function() {  treeOps.updateElementNameReq(); });
	$('#myMenu1 .delete, #myMenu2 .delete').click(function() {  treeOps.deleteElementReq(); });
	
	
	// setting menu texts 
	$('#myMenu1 .addDoc').append(langManager.addDocMenu);
	$('#myMenu1 .addFolder').append(langManager.addFolderMenu);
	$('#myMenu1 .edit, #myMenu2 .edit').append(langManager.editMenu);
	$('#myMenu1 .delete, #myMenu2 .delete').append(langManager.deleteMenu);
		
	// initialization of tree
	simpleTree = $('.simpleTree').simpleTree({
		autoclose: false,
		
		/**
		 * Callback function is called when one item is clicked
		 */	
		afterClick:function(node){
			$('#propmenu').show();
			$('#prop_link').html($('span:first',node).text());
			$('#prop_id').val($('span:first',node).parent().attr('id'));
			//modifikasi
			$.get("acl.menu/tree_menu_post",{act:'getID',id:$('span:first',node).parent().attr('id')},function(data){
				$('#prop_url').html(data);       	
			})	

				//alert($('span:first', node).text() + " clicked");
				//alert($('span:first',node).parent().attr('id'));
		},
		/**
		 * Callback function is called when one item is double-clicked
		 */	
		afterDblClick:function(node){
			
			//alert($('span:first',node).text() + " double clicked");		

		},
		afterMove:function(destination, source, pos) {
		//	alert("destination-"+destination.attr('id')+" source-"+source.attr('id')+" pos-"+pos);	
			if (dragOperation == true) 
			{
				var params = "action=changeOrder&elementId="+source.attr('id')+"&destOwnerEl="+destination.attr('id')+"&position="+pos;
				treeOps.ajaxReq(params, structureManagerURL, null, function(result)
				{						
					treeOps.treeBusy = false;
					treeOps.showInProcessInfo(false);
					try {
						var t = eval(result);
						// if result is less than 0, it means an error occured														
						if (treeOps.isInt(t) == true  && t < 0) { 
							alert(eval("langManager.error_" + Math.abs(t)) + "\n"+langManager.willReload);									
							window.location.reload();							
						}
						else {
							var info = eval("(" + result + ")");
							$('#' + info.oldElementId).attr("id", info.elementId);
						}
					}
					catch(ex) {	
							var info = eval("(" + result + ")");
							$('#' + info.oldElementId).attr("id", info.elementId);	
					}	
				});
			}
		},
		afterAjax:function(node)
		{			
			if (node.html().length == 1) {
				node.html("<li class='line-last'></li>");
			}
		},		
		afterContextMenu: function(element, event)
		{
			var className = element.attr('class');
			if (className.indexOf('doc') >= 0) {
				$('#myMenu2').css('top',event.pageY).css('left',event.pageX).show();				
			}
			else {
				if (className.indexOf('root') >= 0) {
					$('#myMenu1 .edit, #myMenu1 .delete').hide();
				}
				$('#myMenu1').css('top',event.pageY).css('left',event.pageX).show();
			}
			
			$('*').click(function() { $('#myMenu1, #myMenu2').hide(); $('#myMenu1 .edit, #myMenu1 .delete').show(); });
			
		},
		animate:true
		//,docToFolderConvert:true		
	});		
});