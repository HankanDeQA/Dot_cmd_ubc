//rule scheme :
/*global window: false */
var UBCPath = "tcbox?action=ubc";
var fs = require('fs');
var exec = require("child_process").exec;
var path = __dirname + "/";
var logPath = path + "log/";
var logPathCMD = path + "log/cmd/";
var pathModule = require('path');
var logName = "ubc_result.html";
var logNameCMD = "cmd_result.html";

var CMDPath = "&cmd=";

var isInitServer = false; // 标识是否初始化启动webPy server

module.exports = {


    replaceServerResDataAsync: function (req, res, serverResData, callback) {
        if (req.headers.host == "github.com") {
            serverResData += "hello github";
        }
        callback(serverResData);
    },

    shouldInterceptHttpsReq: function (req) {
        // if (req.headers.host.indexOf("baidu.com") > 0) {
        //     return true;
        // } else {
        //     return false;
        // }
        return true;
    },


    replaceRequestData: function (req, data) {
        var url = req.url;

        // 一、拦截cmd请求
        if (url.indexOf(CMDPath) > -1) {
            // req.headers['Content-Type'] = 'application/json;charset=utf-8';
            // var postBody = data.toString('utf-8');
            initWebpyServer(); // 开启webPy服务器

            console.log('data====', data);
            console.log('typeof====', typeof(data));

            var buf = new Buffer(data);

            var postBody = buf.toString('utf-8');


            console.log("frankieTest " + postBody);

            // php脚本执行，存入本地json文件
            var cmd_php = "php " + path + "decodeCMDLog.php ";
            cmd_php += postBody;
            exec(cmd_php);

//            // python web.py服务器开启
//            var cmd_py = "python " + path + "serverHttp.py " + "5050";
//            exec(cmd_py);

            // SubmitArticle(postBody); // 请求

            // js传输数据至php
            // $.ajax({
            //     type: "post",
            //     // dataType: "json",
            //     url: "decodeCMDLog.php",
            //     data: postBody,
            //     // data: {'cmdPostBody': postBody},
            //     success: function (msg) {
            //         if (msg) {
            //             alert(msg);
            //         }
            //     }
            // });


            // var postBody = data;

            // var fso, tf; // 仅支持IE
            // fso = new ActiveXObject("Scripting.FileSystemObject");
            // // 创建新文件
            // tf = fso.CreateTextFile("/Users/pengfei06/Downloads/ubc_script/cmdLog/text.txt", true);
            // // 填写数据，并增加换行符
            // tf.WriteLine("Testing 1, 2, 3.");
            // // 增加3个空行
            // tf.WriteBlankLines(3);
            // // 填写一行，不带换行符
            // tf.Write("This is a test.");
            // // 关闭文件
            // tf.Close();

        }

        // 二、拦截UBC请求
        if (url.indexOf(UBCPath) > -1) {
            initWebpyServer(); // 开启webPy服务器

            var postBody = data.toString('utf-8');
            //data=xxxx
            var postValue = postBody.substr(5);
            var cmd = "php " + path + "decodeAuncelLog.php ";
            cmd += postValue;
            var logDate = getNowFormatDate();
            var fileName = logDate + '_' + logName;
            var filePath = logPath + fileName;
            exec(cmd, function (error, stdout, stderr) {
                var out = stdout;
                fs.appendFile(filePath, out, function (err) {
                    if (err) {
                        console.log(err);
                    }
                });
                var htmlFile = path + logName;
                fs.appendFile(htmlFile, '<a href="view-source:file://' + filePath + '">' + fileName + '</a></br></br>', function (err) {
                    if (err) {
                        console.log(err);
                    }
                });
            });
            var cmdMd5 = "php " + path + "md5Log.php ";
            cmdMd5 += postValue;
            var fileMd5 = logPath + 'md5.log';
            exec(cmdMd5, function (error, stdout, stderr) {
                var outMd5 = stdout;
                fs.appendFile(fileMd5, outMd5 + ' ' + postValue + '\n', function (err) {
                    if (err) {
                        console.log(err);
                    }
                });
            });
        }

        return data;
    }
    ,
}
;

// cmd命令开启web.py服务器
function initWebpyServer() {
    if(isInitServer)
    return;

    // python web.py服务器开启
    var cmd_py = "python " + path + "serverHttp.py " + "5050";
    exec(cmd_py);
    isInitServer = true;
}

// 请求
var xmlobj; //定义XMLHttpRequest对象
function CreateXMLHttpRequest() {
    if (window.ActiveXObject)
    //如果当前浏览器支持Active Xobject，则创建ActiveXObject对象
    {
        //xmlobj = new ActiveXObject("Microsoft.XMLHTTP");
        try {
            xmlobj = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                xmlobj = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (E) {
                xmlobj = false;
            }
        }
    }
    else if (window.XMLHttpRequest)
    //如果当前浏览器支持XMLHttp Request，则创建XMLHttpRequest对象
    {
        xmlobj = new XMLHttpRequest();
    }
}

// 请求函数
function SubmitArticle(postBody)
{
    CreateXMLHttpRequest(); //创建对象
    //var parm = "act=firstweather" ;//构造URL参数
    //antique = escape(antique);
    // var parm = "act=" + act + "&cityname=" + cityname + "&antique=" + antique;//构造URL参数
    console.log(postBody);
    var postbody = postBody;
    //xmlobj.open("POST", "{dede:global.cfg_templeturl/}/../include/weather.php", true); //调用weather.php
    // xmlobj.open("POST", "/weather/include/weather.php", true); //调用weather.php
    xmlobj.open("POST", "decodeCMDLog.php", true); // 调用decodeCMDLog.php
    // xmlobj.setRequestHeader("cache-control", "no-cache");
    // xmlobj.setRequestHeader("contentType", "text/html;charset=uft-8") //指定发送的编码
    // xmlobj.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");  //设置请求头信息
    // xmlobj.onreadystatechange = StatHandler;  //判断URL调用的状态值并处理
    xmlobj.send(postbody); //设置为发送给服务器数据
}

// 将数据存入本地——frankie
function SaveInfoToFile(folder, fileName, fileInfo) {
    var filePath = folder + fileName;
    // var fileInfo = "hahahaha";
    var fso = new ActiveXObject("Scripting.FileSystemObject");
    var file = fso.CreateTextFile(filePath, true);
    file.Write(fileInfo);
    file.Close();
}

function getNowFormatDate() {
    var date = new Date();
    var seperator1 = "-";
    var seperator2 = ":";
    var month = date.getMonth() + 1;
    var strDate = date.getDate();
    if (month >= 1 && month <= 9) {
        month = "0" + month;
    }
    if (strDate >= 0 && strDate <= 9) {
        strDate = "0" + strDate;
    }
    var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate
        + "_" + date.getHours() + seperator2 + date.getMinutes()
        + seperator2 + date.getSeconds() + seperator2 + date.getMilliseconds();
    return currentdate;
}

