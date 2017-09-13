<?php

//header("Content-type: text/html; charset=utf-8");

$origin = $argv[1];

echo '----origin:' . $origin;

$str_del = substr($origin, 5);

echo '----str_del:' . $str_del;

$urlDecode = urldecode($str_del);

echo '----urlDecode:' . $urlDecode;



// 写入文件
//file_put_contents('/Users/pengfei06/Downloads/ubc_script/cmdLog/' . time() . '.txt', $a);

?>
