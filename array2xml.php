<?php
/**
 *  Array 2 xml
 *
 *  Copyright (c) gaoyang 2011
 *  Licensed under the MIT:
 *  http://www.opensource.org/licenses/mit-license.php
 *	@author gaoyang（高阳）qq:243853184 email:yunnysunny@gmail.com
 *	@email yunnysunny@gmail.com
 *	@version 1.0
 *
 * @var xmlDOM $doc xml文档
 * @var boolean $bom 是否生成带有BOM的utf8文件
**/
class Array2xml {
    private $doc = null;
    private $bom = false;
    public function __construct() {

    }
    /**
    * transform
    *
    * 数组到xml的转化函数(构造函数),数组键值为'content' 'title' 'info' 'caption'的生成cdata元素，
    * 键值为attr_开头的生成属性元素，键值为数字的生成名字为$item的元素，将在下面中介绍
    *
    * @param array $array 要转化的数组
    * @param int $deep 当前转化的层次，递归时用
    * @param String $root 当前的父层xml
    * @param String $item 键值为数字的数组元素对应的XML名称。例如,如果一个数组的下标是数字，
    * 假设数组的每个元素存储的是文章的信息，那么我们定义每篇文章在XML中显示的元素标签为news，
    * 即设置$item='news'，就可以生成了类似如下结构的XML：
    *  <root>
    *     <news>
    *         <param1 />
    *         <param2 />
    *     </news>
    *     <news>
    *         <param1 />
    *         <param2 />
    *     </news>
    *  </root>
    * @return null
    **/
    public function transform($array = array(),$deep = 0,$root = 'root',$item = 'item',$withBOM=0) {
        if(!is_array($array))   return ;
        if(count($array)==0)    return ;//递归结束条件
        if($deep==0) {
            $this->doc = new DOMDocument("1.0",'utf-8');
            $this->doc->formatOutput = true;
            $root = $this->doc->createElement($root);
            $this->doc->appendChild( $root );
            if($withBOM!=0) {
                $this->bom = true;
            }
        }
        $deep++;
        foreach($array as $key => $value) {
            //echo $key.'<br />';
            if(is_null($value)) {
                $value = '';
            }
            if(preg_match ("/^[0-9]+$/",$key)) {//是数字节点
                $child = $this->doc->createElement($item);
            } else {//不是数字节点
                if(strpos($key,'attr_')===FALSE) {//不是属性，是普通节点
                    $child = $this->doc->createElement($key);
                } else {//是属性节点
                    $key = substr($key,5);
                    $child = $this->doc->createAttribute($key);
                }
            }
            //if(is_array($value)&&count($value)>0) {
            if(is_array($value)) {
                $this->transform($value,$deep,$child,$item);
            } else {
                if($key=='content'||$key=='title'||$key=='info'||$key=='caption') {
                    if($value!='') {
                        $child->appendChild($this->doc->createCDATASection($value));
                    }
                } else {
                    if($value!='') {
                        $child->appendChild($this->doc->createTextNode($value));
                    }
                }
            }

            $root->appendChild($child);      //添加到父层节点
        }
    }
    /**
    * domToSimple
    *
    * 将DOM类型的xml转化为simple类型的XML
    *
    * @param null
    * @return simpleXML xml 生成的simpleXML
    *
    **/
    public function domToSimple() {
        return simplexml_import_dom($this->doc);
    }
    /**
    * printXML
    *
    * 打印当前xml
    *
    * @param null
    * @return null
    **/
    public function printXML() {
        echo $this->doc->saveXML();
    }
    /**
    * saveAs
    *
    * 将xml保存为xml文件
    *
    * @param String $name 保存文件名
    * @param String $path 保存路径，保存在该路径下的cache文件夹中，默认为根路径
    * @return Boolean result 是否生成成功
    **/
    public function saveAs($name,$path="") {
        if($path=='')   $path = ROOT.'/cache/';
        if(!file_exists($path)) {
            echo 'the channel path '.$path.' doesnot exists<br />';
            return false;
        }
        ob_start();
        $this->printXML();
        $xml = ob_get_contents();
        ob_end_clean();
        $fileName = $path.$name;

        $file=fopen($fileName,'w+'); //打开文件
        if($this->bom==true) {
            fwrite($file,"/xEF/xBB/xBF");
        }
        $result = fwrite($file,$xml); //写入信息到文件
        @chmod($fileName,0777);//##########################更改权限##############
        fclose($file); //关闭文件
        return $result==false ? false : true;
    }


    public function setXML($xmlDoc) {
        $this->doc = $xmlDoc;
    }

    public function setBOM($hasBOM) {
        $this->bom = $hasBOM;
    }
}
