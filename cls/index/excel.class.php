<?php

!defined('LEM') && exit('Forbidden');

class LEM_excel extends mysqlDBA {
    
    function export($arr_data){		
            $arr_letter  = range("A", "Z");
            $arr_letter1 = array();
            foreach($arr_letter as $v){
                    $arr_letter1[] = 'A'.$v;
            }
            $arr_letter=array_merge($arr_letter,$arr_letter1);

            require_once S_ROOT.'lib/php_excel/PHPExcel.php';
            $obj_php_excel = new PHPExcel();
            $obj_php_excel->getProperties()->setCreator("hengan")
                            ->setLastModifiedBy("hengan")
                            ->setTitle("Office 2007 XLSX order lists")
                            ->setSubject("Office 2007 XLSX order lists")
                            ->setDescription("order lists for Office 2007 XLSX, generated using PHP classes.")
                            ->setKeywords("office 2007 openxml php")
                            ->setCategory("order lists");

            foreach ($arr_data['label'] as $k => $v) {
                    $obj_php_excel->setActiveSheetIndex(0)->setCellValue($arr_letter[$k].'1', $v);
            }
            $y = 2;
            $x = 0;
            $arr_data['content'] = $arr_data['content'] ? $arr_data['content'] : array();
            foreach ($arr_data['content'] as $k => $v) {
                    $x = 0;
                    foreach ($v as $k2 => $v2) {
                            $pattern="/^[0-9]{8,}$/";//手机  
                            if (preg_match($pattern,$v2) || isset($arr_data['text_type'][$arr_letter[$x]])){   
                                    $obj_php_excel->setActiveSheetIndex(0)->setCellValueExplicit($arr_letter[$x].$y, $v2, PHPExcel_Cell_DataType::TYPE_STRING);   
                            }else{   
                                    $obj_php_excel->setActiveSheetIndex(0)->setCellValue($arr_letter[$x].$y, $v2);   
                            }
                            strstr($v2,"\n") && $obj_php_excel->getActiveSheet()->getStyle($arr_letter[$x].$y)->getAlignment()->setWrapText(true);
                            $x++;
                    }
                    $y++;
            }
	    $arr_data['cell_width'] = $arr_data['cell_width'] ? $arr_data['cell_width'] : array();
	    foreach($arr_data['cell_width'] as $k=>$v){
		$obj_php_excel->getActiveSheet()->getColumnDimension($k)->setWidth($v);
	    }
            $obj_php_excel->getActiveSheet()->setTitle('list');
            $obj_php_excel->setActiveSheetIndex(0);
            $objWriter = PHPExcel_IOFactory::createWriter($obj_php_excel, 'Excel5');
            ob_start();
            $objWriter->save('php://output');
            $return = ob_get_contents();
            ob_end_clean();
            echo $return;
    }


        function export_main($arr_data){     
            $arr_letter  = range("A", "Z");
            $arr_letter1 = array();
            foreach($arr_letter as $v){
                    $arr_letter1[] = 'A'.$v;
            }
            $arr_letter=array_merge($arr_letter,$arr_letter1);

            require_once S_ROOT.'lib/php_excel/PHPExcel.php';
            $obj_php_excel = new PHPExcel();
            $obj_php_excel->getProperties()->setCreator("hengan")
                            ->setLastModifiedBy("hengan")
                            ->setTitle("Office 2007 XLSX order lists")
                            ->setSubject("Office 2007 XLSX order lists")
                            ->setDescription("order lists for Office 2007 XLSX, generated using PHP classes.")
                            ->setKeywords("office 2007 openxml php")
                            ->setCategory("order lists");

            foreach ($arr_data['label'] as $k => $v) {
                    $obj_php_excel->setActiveSheetIndex(0)->setCellValue($arr_letter[$k].'3', $v);
            }
            $y = 4;
            $x = 0;
            $arr_data['content'] = $arr_data['content'] ? $arr_data['content'] : array();
            foreach ($arr_data['content'] as $k => $v) {
                    $x = 0;
                    foreach ($v as $k2 => $v2) {
                            $pattern="/^[0-9]{8,}$/";//手机  
                            if (preg_match($pattern,$v2) || isset($arr_data['text_type'][$arr_letter[$x]])){   
                                    $obj_php_excel->setActiveSheetIndex(0)->setCellValueExplicit($arr_letter[$x].$y, $v2, PHPExcel_Cell_DataType::TYPE_STRING);   
                            }else{   
                                    $obj_php_excel->setActiveSheetIndex(0)->setCellValue($arr_letter[$x].$y, $v2);   
                            }
                            strstr($v2,"\n") && $obj_php_excel->getActiveSheet()->getStyle($arr_letter[$x].$y)->getAlignment()->setWrapText(true);
                            $x++;
                    }
                    $y++;
            }
        $arr_data['cell_width'] = $arr_data['cell_width'] ? $arr_data['cell_width'] : array();
        foreach($arr_data['cell_width'] as $k=>$v){
        $obj_php_excel->getActiveSheet()->getColumnDimension($k)->setWidth($v);
        }   
            $obj_php_excel->getActiveSheet()->mergeCells('A1:'.$arr_letter[count($arr_data['label'])-1].'1');
            $obj_php_excel->getActiveSheet()->mergeCells('A1:C1');
            $obj_php_excel->setActiveSheetIndex(0)->setCellValue('A1', $arr_data['title']); 
            $obj_php_excel->setActiveSheetIndex(0)->setCellValue('A2', '————全站————'); 
            $obj_php_excel->getActiveSheet()->setTitle('list');
            $obj_php_excel->setActiveSheetIndex(0);
            $objWriter = PHPExcel_IOFactory::createWriter($obj_php_excel, 'Excel5');
            ob_start();
            $objWriter->save('php://output');
            $return = ob_get_contents();
            ob_end_clean();
            echo $return;
    }
    
    
    public function readExcel($path)  
    {      
        //引用PHPexcel 类  
        require_once S_ROOT.'lib/php_excel/PHPExcel.php';
        require_once S_ROOT.'lib/php_excel/PHPExcel/IOFactory.php';
        $type = 'Excel5';//设置为Excel5代表支持2003或以下版本，Excel2007代表2007版  
        $xlsReader = PHPExcel_IOFactory::createReader($type);    
        $xlsReader->setReadDataOnly(true);  
        $xlsReader->setLoadSheetsOnly(true);  
        $Sheets = $xlsReader->load($path);  
            //开始读取上传到服务器中的Excel文件，返回一个二维数组  
        $dataArray = $Sheets->getSheet(0)->toArray();  
        return $dataArray;  
    }  
    
    
}
