<?php
#the simple test case
define('ROOT',dirname( __FILE__));

include 'array2xml.php';
$xmlOpr = new array2xml();
$array = array(
	array('name'=>'sun','age'=>23),
	array('name'=>'yang','age'=>21)
);
$xmlOpr->transform($array);
$xmlOpr->saveAs('test.xml');
