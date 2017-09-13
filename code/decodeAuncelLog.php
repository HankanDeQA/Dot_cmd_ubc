<?php
$a = $argv[1];

$a = urldecode($a);
$a = str_replace(' ', '+', $a);
$a = base64_decode($a);
$a = bin2hex($a);

$a = str_replace("\n", "", $a);
$a = "1f8b" . substr($a, 4);
$a = hex2bin($a);
$json = gzdecode($a);
//file_put_contents('log/ubc/' . time() . '.json', $json);

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
$obj3 = json_decode($json2, true);

$count_json = count($obj3);
//print $count_json;
//echo '</br>';
for ($i = 0; $i < $count_json; $i++) {
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

    // 存入解析的数据
    jsonWriteFile('json/ubc_' . $id . '.json', $obj_item_str);

//    file_put_contents('json/ubc_' . $id . '.json', $obj_item_str, FILE_APPEND);

}

/**
 * @param  $fileName 文件目录
 * @param  $strToWrite要写入的json字符串数据
 * @return true false
 * */
function jsonWriteFile($fileName, $strToWrite)
{
    if (is_file($fileName)) {
        print "文件存在！\n";
        // 从文件中读取数据，为字符串类型
        $json_orgin_string = file_get_contents($fileName);

        if (empty($json_orgin_string)) {
            print "string 为空！\n";
            return false;
        } else {
            print "string 不为空！！\n";

            // 解析为PHP array
            $json_array = json_decode($json_orgin_string, true);
            $count_json = count($json_array);
            if ($count_json < 40) {
                // 从末尾截取
                // $json_del = substr($json_orgin_string, 0, -1);
                // 从开头截取
                $json_del = substr($json_orgin_string, 1);
                $json_last = "[" . $strToWrite . "," . $json_del;
                print $json_last;
                file_put_contents($fileName, $json_last); // 最终数据写入
                print "\n数据写入成功！\n";
                return true;
            } else {
                print "json array数目已到40个！！\n";
                return false;
            }
        }
    } else { // 文件不存在，则创建文件，并将json字符串写入
        print "文件不存在！\n";
        $json = "[" . $strToWrite . "]";
        file_put_contents($fileName, $json);
        return true;
    }
}


$json = json_decode($json, true);
echo $json = jsonFormat($json);

/** Json数据格式化
 * @param  Mixed $data 数据
 * @param  String $indent 缩进字符，默认4个空格
 * @return JSON
 */
function jsonFormat($data, $indent = null)
{

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
    $indent = isset($indent) ? $indent : '    ';
    $newline = "\n";
    $prevchar = '';
    $outofquotes = true;

    for ($i = 0; $i <= $length; $i++) {

        $char = substr($data, $i, 1);

        if ($char == '"' && $prevchar != '\\') {
            $outofquotes = !$outofquotes;
        } elseif (($char == '}' || $char == ']') && $outofquotes) {
            $ret .= $newline;
            $pos--;
            for ($j = 0; $j < $pos; $j++) {
                $ret .= $indent;
            }
        }

        $ret .= $char;

        if (($char == ',' || $char == '{' || $char == '[') && $outofquotes) {
            $ret .= $newline;
            if ($char == '{' || $char == '[') {
                $pos++;
            }

            for ($j = 0; $j < $pos; $j++) {
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
function jsonFormatProtect(&$val)
{
    if ($val !== true && $val !== false && $val !== null) {
        $val = urlencode($val);
    }
}

?>
