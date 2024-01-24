
<style>
.content{
	width:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;
}
.main_side{
	width:100%;
	float:left;
	text-align:left;
}
.rightside{ 
	width:25%;
	float:right;
	text-align:center;
}
.content{
	width:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;
}
.main_side{
	width:100%;
	float:left;
	text-align:left;
}

</style>
<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
$backuri                      = str_replace('&_saveok=1', '', $_SERVER['REQUEST_URI']);
$_SESSION['__uriback'][APPID] = $backuri . (strpos($_SERVER['REQUEST_URI'], '?') === false ? '?' : '');

?>


<script type="text/javascript">	
/*
jQuery(function() {
 jQuery("#booking").jqGrid({
	url:'<?=HOME?>datanya/data_diskon?q=data_pyma',
	mtype : "post",
	datatype: "json",
	colNames:['Terminal','No. Nota','Kegiatan','Tgl Input','Tgl. Awal Keg.','Tgl Akhir Keg','Tgl. Pranota','Status Awal','Status Akhir','Amount', 'Tgl Update','User'], 
	colModel:[
		{name:'t',index:'t' , width:80, align:"center"},
		{name:'n',index:'n', width:80, height:40, align:"center"},
		{name:'kg',index:'kg', width:100, align:"center"},
		{name:'ti',index:'ti', width:70, align:"center",search:false},
		{name:'ta',index:'ta', width:70, align:"center",search:false},
		{name:'tak',index:'tak', width:70, align:"center",search:false},
		{name:'tp',index:'tp', width:70, align:"center",search:false},
		{name:'sa',index:'sa', width:60, align:"center"},
		{name:'sk',index:'sk', width:60, align:"center"},
		{name:'am',index:'am', width:80, align:"center"},
		{name:'tu',index:'tu', width:70, align:"center",search:false},
		{name:'us',index:'us', width:80, align:"center",search:false}
	],
	rowNum:20,
	width: 865,
	height: "100%",//250

	//rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_booking',
	viewrecords: true,
	shrinkToFit: false,
	//grid.setRowData ( id, false, {height: 30} ),
	caption:"Monitoring PYMA"
 });
  jQuery("#booking").jqGrid('navGrid','#pg_l_booking',{del:false,add:false,edit:false,search:false}); 
  //jQuery("#booking").grid.setGridHeight('50px');
  
 jQuery("#booking").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
});
*/
var categoriesStr =":All;BONGKAR MUAT:BONGKAR MUAT;PENUMPUKAN:PENUMPUKAN;LOLO:LOLO;PAKET LOLO:PAKET LOLO;STUF_STRIP:STUFFING STRIPPING;OVERSTAGE:OVERSTAGE;SHARING GUD/LAP:SHARING GUD/LAP";
var terminal = ":All;1:TERMINAL 1;2:TERMINAL 2;3:TERMINAL 3;NON TERMINAL:NON TERMINAL";
var st = ":All;N:N;R:R;C:C;I:I;X:X";
var v_target1='';
var v_target2='';
var v_target3='';
var url;
 $(document).ready(function() 
 {

	url="<?=HOME?>monitoring.pyma.auto/pic";
	$.post(url,{t:v_target1,kg:v_target2,sk:v_target3},function(data) 
	{
		console.log(data);
		$('#ket').html(data);
	});	
	grid = $("#booking"),
	
                setSearchSelect = function(columnName) 
				{
					if (columnName=='kg')
					{
						var categoriesStr2=categoriesStr;
						
					}
					else if (columnName=='t')
					{
						var categoriesStr2=terminal;
					}
					else if (columnName=='sk')
					{
						var categoriesStr2=st;
					}
                    grid.jqGrid('setColProp', columnName,
                    {
					    stype: 'select',
                        searchoptions: 
						{
							value:categoriesStr2,
                            sopt:['eq'],
							dataEvents: [
											{ type: 'change', fn: function(e) { console.log($(e.target).val());console.log(e.target.name);
												if (((e.target.name=='t') && ($(e.target).val()!='all')) || ((e.target.name=='kg') && ($(e.target).val()!='all')) || ((e.target.name=='sk') && ($(e.target).val()!='all')))
												{
													if(e.target.name=='t')
													{
														v_target1=$(e.target).val();
													}
													else if(e.target.name=='kg')
													{
														v_target2=$(e.target).val();
													}
													else if(e.target.name=='sk')
													{
														v_target3=$(e.target).val();
													}
													
												
													url="<?=HOME?>monitoring.pyma.auto/pic";
													$.post(url,{t:v_target1,kg:v_target2,sk:v_target3},function(data) 
													{
														console.log(data);
														
														
														$('#ket').html(data);
														//
													});	
												}
											}}]
                                    }
                                }
                    );
                };

            grid.jqGrid({
                url:'<?=HOME?>datanya/data_diskon?q=data_pyma',
	mtype : "post",
	datatype: "json",
	colNames:['Terminal','No. Nota','No. Uper/BPRP','Kegiatan','Tgl Input','Tgl. Awal Keg.','Tgl Akhir Keg','Tgl. Pranota','Status','Amount Uper','Amount Non PPN', 'Tgl Update','User'], 
	colModel:[
		{name:'t',index:'t' , width:120, align:"center"},
		{name:'n',index:'n', width:80, height:40, align:"center",search:false},
		{name:'nU',index:'nU', width:80, height:40, align:"center",search:false},
		{name:'kg',align:"center"},
		{name:'ti',index:'ti', width:70, align:"center",search:false},
		{name:'ta',index:'ta', width:70, align:"center",search:false},
		{name:'tak',index:'tak', width:70, align:"center",search:false},
		{name:'tp',index:'tp', width:70, align:"center",search:false},

		{name:'sk',index:'sk', width:60, align:"center"},
		{name:'aU',index:'aU', width:100, align:"right"},
		{name:'am',index:'am', width:100, align:"right"},
		{name:'tu',index:'tu', width:70, align:"center",search:false},
		{name:'us',index:'us', width:100, align:"center",search:false}
	],
                rowNum:20,
	width: 865,
	height: "100%",//250

	//rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_booking',
	viewrecords: true,
	shrinkToFit: false,
	//grid.setRowData ( id, false, {height: 30} ),
	caption:"Monitoring PYMA"
    }).jqGrid('navGrid','#pg_l_booking',
                      {edit:false, add:false, del:false, search:false, refresh:false});

            setSearchSelect('t');
            setSearchSelect('kg');
			setSearchSelect('sk');
            grid.jqGrid('filterToolbar',
                        {stringResult:true, searchOnEnter:false});
	
        
		
		});
	
</script>

<div class="content">
	<div class="main_side">
	<p>
	<h2> <img src="<?=HOME?>images/cc.png" height="7%" width="7%" style="vertical-align:middle"> <font color="#81cefa">Monitoring </font>
	<font size="3px" color="#606263">PYMA</font></h2></p>
	
	<p><br/></p>
	<table id='booking' width="100%"></table> <div id='pg_l_booking'></div>
	<br><br>
	<label id="ket" name="ket" width=100></label>  
<div id="dialog-form">
	<form>
		<div id="table_profil"></div>
		<div id="pic_ba"></div>
		<div id="add_ba"></div>
		<div id="insert_remarks"></div>
	</form>
	</div>
	<br/>
	<br/>
	</div>
</div>