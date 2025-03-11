<?php if (!defined("XLITE_INCLUSION")) die(); ?><script type="text/javascript" src="gz.php?f=js/jquery.updater.js"></script><script type="text/javascript">
 $(function(){   
	$.PeriodicalUpdater({
			url : '<?php echo($HOME); ?>main/notify/',
			method: 'post',
			maxTimeout: 36000,
			minTimeout: 36000
		},
		function(data){
			var myHtml = data ;
			if(data.length > 0 )
				$.growlUI( myHtml);
	}); 
	
	$('a#stop').click(function(e){
		e.preventDefault();
		clearTimeout(PeriodicalTimer);
	})
	
 })
</script>