<link rel="stylesheet" type="text/css" href="css/jui/alto_v2/jquery-ui-1.10.3.custom.min.css" />
<link rel="stylesheet" type="text/css" href="css/jqgrid/ui.jqgrid.css" />
<script type="text/javascript" src="js/jqgrid/jquery-1.9.0.min.js"></script>
<script src="js/jui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
<script src="js/jqgrid/i18n/grid.locale-en.js" type="text/javascript"></script>
<script src="js/jqgrid/jquery.jqGrid.min.js" type="text/javascript"></script>
<?php outputRaw();
$NOTANAME = $_POST['NOTANAME'];
$STATUS	  = $_POST['STATUS'];
$YEAR	  = $_POST['YEAR'];
IF($STATUS=='Fail'){
	$STATUS = "F";
}ELSEIF($STATUS=='Success'){
	$STATUS = "S";
}
?>
<style>
.myButton {
	-moz-box-shadow:inset 0px 0px 0px 0px #ffffff;
	-webkit-box-shadow:inset 0px 0px 0px 0px #ffffff;
	box-shadow:inset 0px 0px 0px 0px #ffffff;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #f9f9f9), color-stop(1, #e9e9e9));
	background:-moz-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
	background:-webkit-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
	background:-o-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
	background:-ms-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
	background:linear-gradient(to bottom, #f9f9f9 5%, #e9e9e9 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#f9f9f9', endColorstr='#e9e9e9',GradientType=0);
	background-color:#f9f9f9;
	-moz-border-radius:6px;
	-webkit-border-radius:6px;
	border-radius:6px;
	border:1px solid #dcdcdc;
	display:inline-block;
	color:#3590cc;
	font-family:Trebuchet MS;
	font-size:13px;
	font-weight:bold;
	padding:6px 24px;
	text-decoration:none;
	text-shadow:2px 1px 3px #ebeef0;
	width:150px;
	transition:width 1s;
	-webkit-transition:width 1s; /* Safari */
	text-align:center;
	
}
.myButton:hover {
	width:150px;
	border:1px solid #00C4F3;
	text-decoration:none;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #e9e9e9), color-stop(1, #f9f9f9));
	background:-moz-linear-gradient(top, #e9e9e9 5%, #f9f9f9 100%);
	background:-webkit-linear-gradient(top, #e9e9e9 5%, #f9f9f9 100%);
	background:-o-linear-gradient(top, #e9e9e9 5%, #f9f9f9 100%);
	background:-ms-linear-gradient(top, #e9e9e9 5%, #f9f9f9 100%);
	background:linear-gradient(to bottom, #e9e9e9 5%, #f9f9f9 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#e9e9e9', endColorstr='#f9f9f9',GradientType=0);background-color:#e9e9e9;
	cursor: pointer;
	}
.myButton:active {
	position:relative;
	top:1px;
}
</style>
<script type="text/javascript"> 
jQuery(function() {
 jQuery("#l_user").jqGrid({
	url:'pymad_uster.monpyma_usterptk.data/?notaname=<?php echo $NOTANAME;?>&status=<?php echo $STATUS;?>&year=<?php echo $YEAR;?>',
	mtype : "post",
	datatype: "json",
	colNames:['NAMA NOTA','NO PRANOTA','TGL PRANOTA','NAMA PELANGGAN','AMOUNT','TRANSFER STATUS','TRANSFER MESSAGE','STATUS AR','TGL AR'], 
	colModel:[
		{name:'NOTA_NAME', width:130, align:"center",sortable:true,search:false,hidden:true},
		{name:'TRX_NUMBER', width:140, align:"left",sortable:true,search:false,summaryType:'count', summaryTpl : 'Total {0} Nota'},
		{name:'TRX_DATE', width:90, align:"center",sortable:true,search:false},
		{name:'CUSTOMER_NAME', width:200, align:"left",sortable:true,search:false},
		{name:'AMOUNT', width:110, align:"right",search:false,formatter:'integer', thousandsSeparator:','},
		{name:'CONFIRMATION_STATUS', width:150, align:"center",search:false},
		{name:'TRANSFER_STATUS', width:150, align:"center",search:false},
		{name:'STATUS_AR', width:150, align:"center",search:false},
		{name:'AR_DATE', width:150, align:"center",search:false}
		
		
	],
	rowNum:500,
	width: 1200,
	height: 300,//250
	rowList:[1000,2000,3000,4000,5000,10000],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_user',
	sortname: 'TRX_NUMBER',
	viewrecords: true,
	shrinkToFit: false,
	addurl: "maintenance.master.user/add/",
	caption:"&nbsp;&nbsp;&nbsp;&nbsp;DETAIL PYMA STAGING USAHA TERMINAL PONTIANAK, NOTA NAME : <?php echo $NOTANAME;?>",
	/*grouping: true,
	multiselect:true,
	groupingView : { 
				groupField : ['NOTA_NAME'], 
				groupColumnShow : [true], 
				groupText : ['<b>{0}</b>'], 
				groupCollapse : true, 
				groupOrder: ['asc'], 
				groupSummary : [true], 
				showSummaryOnHide: true, 
				groupDataSorted : true 
				},*/
	beforeSelectRow: function (rowid, e) {
    var $myGrid = $(this),
        i = $.jgrid.getCellIndex($(e.target).closest('td')[0]),
        cm = $myGrid.jqGrid('getGridParam', 'colModel');
    return (cm[i].name === 'cb');
	},
	gridview: true,
	rowattr: function (rd) {
    if (rd.CONFIRMATION_STATUS === "X") { // verify that the testing is correct in your case
        return {"class": "myAltRowClass"};
    }
	}
	
 }).navGrid('#pg_user',{
	search:false,
	add:false,
	edit:false,
	del:false
 });
	 /*jQuery("#l_user").jqGrid('setGroupHeaders', {
	  useColSpanStyle: true,   
	  groupHeaders:[{startColumnName: 'RADIO1', numberOfColumns: 2, titleText: 'PYMA'}]
	});*/
	 jQuery("#l_user").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

	 jQuery('#print').click(function(){
		 window.open("<?=HOME?>pymad_uster.monpyma_usterptk.data/?notaname=<?php echo $NOTANAME;?>&status=<?php echo $STATUS;?>&year=<?php echo $YEAR;?>&print=ok",'_blank');
	 });
	});
</script>
<table id='l_user'></table> <div id='pg_user'></div>
<div align="right" style="margin-top:10px">
<input type="button" id="print" value="Save To Excel" class="myButton">
<div>