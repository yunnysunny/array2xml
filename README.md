array2xml
=========

Change any php array to xml object,and you can save it as a xml file.	 
> Before using array2xml,you muse define the const ROOT first,elsewise error will occurs.In other words,you can write some code before you use array2xml like that:  
`define('ROOT', dirname(__FILE__));`  
   
Example    
`$xmlOpr = new array2xml();  
$array = (	  
    array('name'=>'sun','age'=>23),	  
    array('name'=>'yang','age'=>21)	  
);	  
$xmlOpr->transform($array);	  
$xmlOpr->saveAs('test.xml');`	  
It will create a file saved in ROOT/cache/test.xml .


