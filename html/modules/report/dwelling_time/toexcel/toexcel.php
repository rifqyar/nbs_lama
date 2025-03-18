<?php
		include('PHPExcel/Classes/PHPExcel.php');
		$excel = new PHPExcel();
        $excel->setActiveSheetIndex(0);
        $worksheet = $excel->getActiveSheet();
        $worksheet->getCell('A1')->setValue('tet');
        $worksheet->getCell('B1')->setValue('tet');
        
        ob_end_clean();
        
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
         header("Cache-Control: post-check=0, pre-check=0", false);
         header("Pragma: no-cache");
         header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
       header('Content-Disposition: attachment;filename="Report.xlsx"');

        $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        ob_end_clean();
        $objWriter->save('excel/report/output.xls');
        $excel->disconnectWorksheets();
        unset($excel);
?>