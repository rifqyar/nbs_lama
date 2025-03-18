<html>
	<head>
		<title>Menampilkan Tabel Dengan ExtJs Grid</title>

		<!-- sesuaikan http://static.eprofile.web.id/ dengan lokasi instalasi extjsmu ya -->
		<link rel="stylesheet" type="text/css" href="http://static.eprofile.web.id/ext-3.2.1/resources/css/ext-all.css" />
		<script type="text/javascript" src="http://static.eprofile.web.id/ext-3.2.1/adapter/ext/ext-base.js"></script>
		<script type="text/javascript" src="http://static.eprofile.web.id/ext-3.2.1/ext-all.js"></script>

		<script type="text/javascript" src="TableGrid.js"></script>

		<script type="text/javascript">
			Ext.onReady(function(){
				var tableGrid1 = new MyApp.TableGrid({
					title:'Grid 1'
				});
				tableGrid1.render('grid-example1');

				var tableGrid2 = new MyApp.TableGrid({
					title:'Yang ini Grid kedua'
				});
				tableGrid2.render('grid-example2');
			});
		</script>
	</head>
	<body>
		<div id="grid-example1"></div>
		<br/>
		<div id="grid-example2"></div>
	</body>
</html>
