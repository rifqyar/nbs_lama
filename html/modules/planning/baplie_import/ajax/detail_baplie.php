<script type="text/javascript">
jQuery(function() {
 jQuery("#det_baplie").jqGrid({	url:'<?=HOME?>datanya/data_2?q=list_cont_im&kg=I&no_ukks=<?=$_GET['id_vessel']?>',	
 mtype : "post",	
 datatype: "json",	
 colNames:['BAY','BAYPLAN','Container Numb.','Size','Type','Status','Height','Hz','ISO CODE','Carrier','POD','POL'], 	
 colModel:[		{name:'bay',index:'bay', width:80, align:"center"},		
 {name:'bp',index:'bp', width:100, align:"center"},		
 {name:'cnt',index:'cnt', width:100, align:"center"},	
 {name:'sz',index:'sz', width:50, align:"center"},	
 {name:'ty',index:'ty', width:50, align:"center"},	
 {name:'st',index:'st', width:50, align:"center"},	
 {name:'hg',index:'hg', width:50, align:"center"},	
 {name:'hz',index:'hz', width:50, align:"center"},	
 {name:'ic',index:'ic', width:70, align:"center"},
 {name:'cr',index:'cr', width:100, align:"center"},	
 {name:'pod',index:'pod', width:80, align:"center"},
 {name:'pol',index:'pol', width:80, align:"center"}	],
 rowNum:10,	width: 865,	height: 238,//250	
 
 rowList:[10,20,30,40,50,60],	
 loadonce:true,	
 rownumbers: true,	
 rownumWidth: 15,	
 gridview: true,	
 pager: '#pg_det_baplie',	
 viewrecords: true,	
 shrinkToFit: false,	
 caption:"Data Container" });  
 jQuery("#det_baplie").jqGrid('navGrid','#pg_det_baplie',{del:false,add:false,edit:false,search:false});  
 jQuery("#det_baplie").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false}); });
 </script>
 <div>	
 <div align="left">	
 <h2>        
 <font color="#0378C6">Detail</font> Container
 </h2>		
 </div>		
 <br>
 <br>    
 <table id='det_baplie' width="100%"></table> <div id='pg_det_baplie'></div></div>