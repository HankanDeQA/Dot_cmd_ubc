<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"  />
    <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.0.js"></script>
</head>

<?php

//header("Content-type: text/html; charset=utf-8");

//$a = $argv[1];

$a = file_get_contents("php://input");

print_r($a);
//$a = $_POST['select'];

//$a = json_encode($_POST);
//var_dump($a);exit;

//$a="�\bmUmo�6�/�O�G�I0w���>m֪ �j	��$�NZ���HJ�S�H�{��=w���q���N����1a� �Ci���yr��Üܧw�yt`���o�S��sn4U��*��׸���Zr��p0�H8�? �18\b�s:%�	82�z!͌��X��J�e����E��d^�ۢ��z]a �)�9,�ՁBg4�I	�\e\b�\"p����8d��ʚN�be�s�|_U�/��5ݩ�(�K �^ �&�u6bR��5F��[D۸�C�K�'������9@�c�v��� >������岗�ы���:��JJ	D��+c)A�0\f i�eH��XM�@�лyR�#�x?�N��G5CJP	t� �L��$ǖ�&?�����O��Y�f�? ���T���u������WEQ���W����������\"-hZ�����\fֱ���%��0j�hZ�W�3��f�S5����^����az�� �L���ہZ�azZ�Q���a\fI_%�&s�t� _Kk����mȼp�[��W�67��Q_c�+�`8�i��-�|��2��(w��V�VÓ�_Rizm_� i� ��9� I����Ӡ+���u� �O��ͅ��m�NT\,�Q�~^�+�B��J	\v����X��<<��gg�����	r��6V�������?��=��4��^�t׏+\f�/��x\vAj�k���b��8+I�y��K�q�rx�p���\v�H�Pၞ�eR[3�J(wc�[ӟ\ ���\"7���v���-��w�|{��!N��g������Ç�A����a~#�B_�L6 )Ͱ.�!D\"x�E��gWs�B �9���t_�S�~!�r�G���گ �� D���0�Q��7E��4�����ZA�4�D����,C����ת\eo3��<�\e*�_�";

//$a = base64_decode($a);

// 解压
//$a = gzdecode($a);

// 写入文件
file_put_contents('/Users/pengfei06/Downloads/ubc_script/cmdLog/' . time() . '.txt', $a);


//$streamData = isset($GLOBALS['HTTP_RAW_POST_DATA'])? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
//
//if(empty($streamData)){
//    $streamData = file_get_contents('php://input');
//}
//
//$myfile = fopen("/Users/pengfei06/Downloads/ubc_script/cmdLog/newfile.txt", "w");
//
//fwrite($myfile, $streamData);

//echo $a;

//$a="�\bmUmo�6�/�O�G�I0w���>m֪ �j	��$�NZ���HJ�S�H�{��=w���q���N����1a� �Ci���yr��Üܧw�yt`���o�S��sn4U��*��׸���Zr��p0�H8�? �18\b�s:%�	82�z!͌��X��J�e����E��d^�ۢ��z]a �)�9,�ՁBg4�I	�\e\b�\"p����8d��ʚN�be�s�|_U�/��5ݩ�(�K �^ �&�u6bR��5F��[D۸�C�K�'������9@�c�v��� >������岗�ы���:��JJ	D��+c)A�0\f i�eH��XM�@�лyR�#�x?�N��G5CJP	t� �L��$ǖ�&?�����O��Y�f�? ���T���u������WEQ���W����������\"-hZ�����\fֱ���%��0j�hZ�W�3��f�S5����^����az�� �L���ہZ�azZ�Q���a\fI_%�&s�t� _Kk����mȼp�[��W�67��Q_c�+�`8�i��-�|��2��(w��V�VÓ�_Rizm_� i� ��9� I����Ӡ+���u� �O��ͅ��m�NT\,�Q�~^�+�B��J	\v����X��<<��gg�����	r��6V�������?��=��4��^�t׏+\f�/��x\vAj�k���b��8+I�y��K�q�rx�p���\v�H�Pၞ�eR[3�J(wc�[ӟ\ ���\"7���v���-��w�|{��!N��g������Ç�A����a~#�B_�L6 )Ͱ.�!D\"x�E��gWs�B �9���t_�S�~!�r�G���گ �� D���0�Q��7E��4�����ZA�4�D����,C����ת\eo3��<�\e*�_�";
//$json = gzdecode($a);
//$myfile = fopen("/Users/pengfei06/Downloads/ubc_script/cmdLog/newfile.txt", "w");
//$txt = "Bill Gates\n";
//fwrite($myfile, $json);
//gzdecode($txt);

//$a = bin2hex($a);

// 二进制转为字符串
//$a = BinToStr($a);

// 解压缩
//$json = gzdecode($a);
//gzdecode()；

// 解析后的json字符串写入json文件
//file_put_contents('/Users/pengfei06/Downloads/ubc_script/cmdLog/' . time() . '.json', $json);
//
//file_put_contents('/Users/pengfei06/Downloads/ubc_script/cmdLog/' . time(), $json);


//stream2File($a);

// test：文件保存的目录名称
const ROOT_PATH = './';
const FAIL_WRITE_DATA = 'Fail to write data';
//没有数据流
const NO_STREAM_DATA = 'The post data is empty';
//图片类型不正确
const NOT_CORRECT_TYPE = 'Not a correct image type';
//不能创建文件
const CAN_NOT_CREATE_FILE = 'Can not create file';
$save_fullpath = '/Users/pengfei06/Downloads/ubc_script/cmdLog/' . time() . '.json';
function stream2File($a)
{
    //二进制数据流
//    $data = file_get_contents('php://input') ? file_get_contents('php://input') : gzuncompress($GLOBALS ['HTTP_RAW_POST_DATA']);
    $data = $a;
    //数据流不为空，则进行保存操作
    if (!empty ($data)) {
        file_put_contents('/Users/pengfei06/Downloads/ubc_script/cmdLog/' . time() . '.txt', $data);
//        file_put_contents('/Users/pengfei06/Downloads/ubc_script/cmdLog/' . time(), $data);

        //创建并写入数据流，然后保存文件
        if (@$fp = fopen(save_fullpath, 'w+')) {
            fwrite($fp, $data);

            fclose($fp);
//            $baseurl = "http://" . $_SERVER ["SERVER_NAME"] . ":" . $_SERVER ["SERVER_PORT"] . dirname($_SERVER ["SCRIPT_NAME"]) . '/' . $this->save_name;
//            if ($this->getimageInfo($baseurl)) {
//                echo $baseurl;
//            } else {
//                echo(self::NOT_CORRECT_TYPE);
//            }
        } else {

        }
    } else {
        file_put_contents('/Users/pengfei06/Downloads/ubc_script/cmdLog/' . time() . '_error', $data);
        //没有接收到数据流
        echo(self::NO_STREAM_DATA);
    }
}


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
 * 讲二进制转换成字符串
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

?>
