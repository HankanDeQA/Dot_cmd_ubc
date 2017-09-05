<?php

// 1、读取json文件内容
$filename = "./json/demo.json";
$json_string = file_get_contents($filename);
//echo $json_string;

// 2、解析json
$obj = json_decode($json_string);
var_dump($obj);

// 3、解析json中'data'数据段
$obj2 = $obj->{'data'};

// 4、将解析出的array转为json
$json2 = json_encode($obj2);

// 5、解析为array
$obj3 = json_decode($json2,true);

$count_json = count($obj3);
print $count_json;
echo '</br>';

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
   file_put_contents('json/ubc_' . $id . '.json', $obj_item_str, FILE_APPEND);
}

echo '</br>';

// $a = $argv[1];

// $json = gzdecode($a);

// echo $json;

//header("Content-type: text/html; charset=utf-8");
//$a = $argv[1];

/**
 * 将字符串转换成二进制
 * @param type $str
 * @return type
 */
function StrToBin($str)
{
    //1.列出每个字符
    $arr = preg_split('/(?<!^)(?!$)/u', $str);
    //2.unpack字符
    foreach ($arr as &$v) {
        $temp = unpack('H*', $v);
        $v = base_convert($temp[1], 16, 2);
        unset($temp);
    }

    return join(' ', $arr);
}

/**
 * 将二进制转换成字符串
 * @param type $str
 * @return type
 */
function BinToStr($str)
{
    $arr = explode(' ', $str);
    foreach ($arr as &$v) {
        $v = pack("H" . strlen(base_convert($v, 2, 16)), base_convert($v, 2, 16));
    }

    return join('', $arr);
}

//echo StrToBin("php二次开发：www.php2.cc");;
//echo '<br/>';
//echo BinToStr("1110000 1101000 1110000 111001001011101010001100 111001101010110010100001 111001011011110010000000 111001011000111110010001 111011111011110010011010 1110111 1110111 1110111 101110 1110000 1101000 1110000 110010 101110 1100011 1100011");
//echo '<br/>';
//echo BinToStr($a);


///**
// * Created by PhpStorm.
// * User: pengfei06
// * Date: 17/8/15
// * Time: 下午9:07
// */
//for ($i = 0; $i < $count_json; $i++)
//{
//    //echo var_dump($de_json);
//
//    $content = $obj3[$i]['content'];
//    print $content;
//    echo '</br>';
//
//}

//print $obj3[0]['content'];

//$json = '{"students":[{"name":"Jack","age":12}, {"name":"Vista","age":23}, {"name":"Kaka","age":22}, {"name":"Hony","age":31}],"test":"dasda"}';

//$json = '{"data":[{"id":"18","starttime":"1502796432118","endtime":"1502796463343","type":"1"}],"metadata":{"mintime":"1502796432118","maxtime":"1502796463343","createtime":"1502796463360","uploadtime":"1502796463360","md5":"7770C5181E0A8D276A6FEB4F390DAEE8"},"isAbtest":"1","isreal":"1"}';
////$json = str_replace('\\','',$json);
//
////print $json;
//
//$obj = json_decode($json);
//var_dump($obj);


//$obj2 = $obj->{'data'};
//
//$json2 = json_encode($obj2);
//
//// 解析3
//$obj3 = json_decode($json2,true);
//
//print $obj3[0]['starttime'];

//var_dump($obj3);


//echo json_encode($obj2);

//print $obj2[0]['name'];

//$de_json = json_decode($obj2,TRUE);
//$count_json = count($de_json);
//for ($i = 0; $i < $count_json; $i++)
//{
//    //echo var_dump($de_json);
//
//    $name = $de_json[$i]['name'];
//
//}
//var_dump($obj2);
//var_dump($obj->{'students'});

?>