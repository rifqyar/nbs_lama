<script src="<?=HOME?>js_chart/js/highcharts.js"></script>
<?php

$db=getDb('pyma');
/*$q1="select m.tgl, m.jumlah,
(
select round((SUM(a.JUMLAH)/1000000000),2) AMOUNT_SIMOP
from rka_tab_all a left join ar_simkeu b on trim(a.NO_NOTA)=trim(b.TRX_NUMBER) where to_number(b.year_up)=2013 and a.status_akhir='I' and 
to_char(a.date_created,'dd-mm-yyyy')<=m.tgl
) as jml_2
 from 
(select to_char(f.date_created,'dd-mm-yyyy') as tgl, 
round((sum (f.jumlah)/1000000),2) as jumlah,(count (1)) as jumlah_nota
from rka_tab_exist_hist f where f.date_created>=to_date('03/15/2013','mm/dd/yyyy') and f.user_id not in('SUWARTONO','admin') group by to_char(f.date_created,'dd-mm-yyyy') order by to_char(f.date_created,'dd-mm-yyyy')) m";*/
$q1="select m.tgl, m.jumlah,
(
select round((SUM(a.JUMLAH)/1000000000),2) AMOUNT_SIMOP
from rka_tab_all a left join ar_simkeu b on trim(a.NO_NOTA)=trim(b.TRX_NUMBER) where to_number(b.year_up)=2013 and a.status_akhir='I' and 
to_char(a.date_created,'dd-mm-yyyy')<=m.tgl
) as jml_2
 from 
(select to_char(f.date_created,'dd-mm-yyyy') as tgl, 
round((sum (f.jumlah)/1000000),2) as jumlah,(count (1)) as jumlah_nota
from rka_tab_exist_hist f where f.date_created>=sysdate-3 and f.user_id not in('SUWARTONO','admin') group by to_char(f.date_created,'dd-mm-yyyy') order by to_char(f.date_created,'dd-mm-yyyy')) m";

$rtg=$db->query($q1);

$h1=$rtg->getAll();
foreach($h1 as $row)
{
	$t1[]=$row[TGL];
	$t2[]=(float)$row[JUMLAH];	
	$t3[]=(float)$row[JML_2];	
}

?>

<script>
var g_array=new Array();
var g_array2=new Array();
var g_array3=new Array();
g_array=<?echo json_encode($t1);?>;
g_array2=<?echo json_encode($t2);?>;
g_array3=<?echo json_encode($t3);?>;
 $(document).ready(function() {
	Highcharts.theme = {
   colors: ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
   chart: {
      backgroundColor: {
         linearGradient: [0, 0, 500, 500],
         stops: [
            [0, 'rgb(255, 255, 255)'],
            [1, 'rgb(240, 240, 255)']
         ]
      },
      borderWidth: 2,
      plotBackgroundColor: 'rgba(255, 255, 255, .9)',
      plotShadow: true,
      plotBorderWidth: 1
   },
   title: {
      style: {
         color: '#000',
         font: 'bold 16px "Trebuchet MS", Verdana, sans-serif'
      }
   },
   subtitle: {
      style: {
         color: '#666666',
         font: 'bold 12px "Trebuchet MS", Verdana, sans-serif'
      }
   },
   xAxis: {
      gridLineWidth: 1,
      lineColor: '#000',
      tickColor: '#000',
      labels: {
         style: {
            color: '#000',
            font: '11px Trebuchet MS, Verdana, sans-serif'
         }
      },
      title: {
         style: {
            color: '#333',
            fontWeight: 'bold',
            fontSize: '12px',
            fontFamily: 'Trebuchet MS, Verdana, sans-serif'

         }
      }
   },
   yAxis: {
      minorTickInterval: 'auto',
      lineColor: '#000',
      lineWidth: 1,
      tickWidth: 1,
      tickColor: '#000',
      labels: {
         style: {
            color: '#000',
            font: '11px Trebuchet MS, Verdana, sans-serif'
         }
      },
      title: {
         style: {
            color: '#333',
            fontWeight: 'bold',
            fontSize: '12px',
            fontFamily: 'Trebuchet MS, Verdana, sans-serif'
         }
      }
   },
   legend: {
      itemStyle: {
         font: '9pt Trebuchet MS, Verdana, sans-serif',
         color: 'black'

      },
      itemHoverStyle: {
         color: '#039'
      },
      itemHiddenStyle: {
         color: 'gray'
      }
   },
   labels: {
      style: {
         color: '#99b'
      }
   }
};

// Apply the theme
var highchartsOptions = Highcharts.setOptions(Highcharts.theme);
var dd= g_array;

var ad= g_array2;

 
 
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'containerdc',
                type: 'bar'
            },
            title: {
                text: 'History AR - PYMA SIMOP BARANG'
            },
            subtitle: {
                text: 'Source: simop.pelindo2.com ; ictprod.inaport2.co.id;'
            },
            xAxis: {
                categories: dd,
                title: {
                    text: null
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Amount (Rp.)',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                formatter: function() {
					var cmt=' milyar';
					if(this.series.name=='PYMA Uninvoice')
					{
						cmt=' juta';
					}
                    return ''+
                        this.series.name +': '+ this.y +cmt;
                }
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -100,
                y: 100,
                floating: true,
                borderWidth: 1,
                backgroundColor: '#FFFFFF',
                shadow: true
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'PYMA Uninvoice',
                data: ad
            }, {
                name: 'PYMA Invoice 2013',
                data: g_array3
            }]
        });
    });
</script>

				
<br>
<div align='center'>
<div id="containerdc" style="min-width: 400px; height: 400px; margin: 0 auto"></div>

</div>