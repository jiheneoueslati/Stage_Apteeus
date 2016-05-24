<?php

require_once 'PHPExcel/IOFactory.php';
$objPHPExcel = PHPExcel_IOFactory::load("Incell/MAP TEE1.xls");

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'txt');
$objWriter->setDelimiter("\t");
$objWriter->save('myOutputFile.csv');

?>