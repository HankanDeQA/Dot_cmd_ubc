<?php
$a=$argv[1];

$a =  urldecode($a);
$a = str_replace(' ','+',$a);
$a =  base64_decode($a);
$a = bin2hex($a);

$a = str_replace("\n", "", $a);
$a = "1f8b".substr($a, 4);
$a = hex2bin($a);
$json = gzdecode($a);
file_put_contents('log/ubc/' . time() . '.json', $json);

/*
 * 解析ubc格式的json字符串，解析出id，并保存数据至json文件
 * */
// 2、解析json
$obj = json_decode($json);
//var_dump($obj);

// 3、解析json中'data'数据段
$obj2 = $obj->{'data'};

// 4、将解析出的array转为json_string
$json2 = json_encode($obj2);

// 5、解析为PHP array
$obj3 = json_decode($json2,true);

$count_json = count($obj3);
//print $count_json;
//echo '</br>';
for ($i = 0; $i < $count_json; $i++)
{
    //echo var_dump($de_json);
    $id = $obj3[$i]['id'];
    echo $id;
    echo '</br>';

    $obj_item = $obj3[$i];
    // echo $obj_item;
    echo '</br>';

    // 转化为json_str
    $obj_item_str = json_encode($obj_item);
    echo $obj_item_str;
    file_put_contents('log_ubc/ubc_' . $id . '.json', $obj_item_str, FILE_APPEND);
}


$json = json_decode($json, true);
echo $json = jsonFormat($json);

/** Json数据格式化 
* @param  Mixed  $data   数据 
* @param  String $indent 缩进字符，默认4个空格 
* @return JSON 
*/  
function jsonFormat($data, $indent=null){  
  
    // 对数组中每个元素递归进行urlencode操作，保护中文字符  
    array_walk_recursive($data, 'jsonFormatProtect');  
  
    // json encode  
    $data = json_encode($data);  
  
    // 将urlencode的内容进行urldecode  
    $data = urldecode($data);  
  
    // 缩进处理  
    $ret = '';  
    $pos = 0;  
    $length = strlen($data);  
    $indent = isset($indent)? $indent : '    ';  
    $newline = "\n";  
    $prevchar = '';  
    $outofquotes = true;  
  
    for($i=0; $i<=$length; $i++){  
  
        $char = substr($data, $i, 1);  
  
        if($char=='"' && $prevchar!='\\'){  
            $outofquotes = !$outofquotes;  
        }elseif(($char=='}' || $char==']') && $outofquotes){  
            $ret .= $newline;  
            $pos --;  
            for($j=0; $j<$pos; $j++){  
                $ret .= $indent;  
            }  
        }  
  
        $ret .= $char;  
          
        if(($char==',' || $char=='{' || $char=='[') && $outofquotes){  
            $ret .= $newline;  
            if($char=='{' || $char=='['){  
                $pos ++;  
            }  
  
            for($j=0; $j<$pos; $j++){  
                $ret .= $indent;  
            }  
        }  
  
        $prevchar = $char;  
    }  
  
    return $ret;  
}  
/** 将数组元素进行urlencode 
* @param String $val 
*/  
function jsonFormatProtect(&$val){  
    if($val!==true && $val!==false && $val!==null){  
        $val = urlencode($val);  
    }  
}  
?>
