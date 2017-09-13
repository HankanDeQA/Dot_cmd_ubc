/**
 * @file 通过anyproxy截取请求响应数据，并做处理
 * @author pengfei06<pengfei06@baidu.com>
 */
// rule scheme :
/* global window: false */
var UBCPath = 'tcbox?action=ubc';
var fs = require('fs');
var exec = require('child_process').exec;
var path = __dirname + '/';
var logPath = path + 'log/';
var logPathCMD = path + 'log/cmd/';
var pathModule = require('path');
var logName = 'ubc_result.html';
var logNameCMD = 'cmd_result.html';

var CMDPath = '&cmd=';

var cmdJsonPath = path + 'json/'; // 保存cmd文件的路径

var isInitServer = false; // 标识是否初始化启动webPy server

var zlib = require('zlib');
var gunzipStream = zlib.createGunzip();

module.exports = {


    replaceServerResDataAsync: function (req, res, serverResData, callback) {
        if (req.headers.host === 'github.com') {
            serverResData += 'hello github';
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
            // var testData = 'mbd.baidu.com/searchbox?action=feed&cmd=185&imgtype=webp&network=1_0&uid=3E5FB9DC20A757632A3B3BBA58336FD854C77E5BEORIRFJKQRJ&ua=1242_2208_iphone_9.3.0.0_0&ut=iPhone7%2C1_10.3.2&from=1099a&osname=baiduboxapp&osbranch=i0&cfrom=1099a&service=bdbox&network=1_0&sid=156_266-583_1193-448_926-494_1028-654_1303-397_819-574_1177-446_922-374_777-489_1014-664_1339-584_1194-665_1340-667_1359-1001596_4460-1001994_5610-1001543_4291-1001943_5486-1001926_5438-1001635_4581-1001462_4018-1001553_4322-1001830_5158&puid=gO258gadviAImqqqB&zid=';
            var cmdId = getCmdId(url);

            var headers = req.headers;
            // console.log('headers====', headers);
            // console.log('headers typeof====', typeof headers);

            var contentEncoding = headers['content-encoding'];
            // console.log('contentEncoding====', contentEncoding);
            if (contentEncoding !== null
                && contentEncoding !== undefined
                && contentEncoding.indexOf('gzip') > -1) { // 编码方式为gzip，请求数据需要解压缩
                gzipBinary(data, cmdId);

                initWebpyServer(); // 开启webPy服务器
            }
            return;

            /*// 读取数据
            fs.readFile(finalPath, function (err, data) {
                if (err) throw err;
                console.log('----读取数据：' + data.toString());
            });*/

            // var buf = new Buffer(data);
            //
            // var postBody = buf.toString('utf-8');
            //
            //
            // console.log("frankieTest " + postBody);
            //
            // // php脚本执行，存入本地json文件
            // var cmd_php = "php " + path + "decodeCMDLog.php ";
            // cmd_php += postBody;
            // exec(cmd_php);
        }

        // 二、拦截UBC请求
        if (url.indexOf(UBCPath) > -1) {
            initWebpyServer(); // 开启webPy服务器

            var postBody = data.toString('utf-8');
            // data=xxxx
            var postValue = postBody.substr(5);
            var cmd = 'php ' + path + 'decodeAuncelLog.php ';
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
            var cmdMd5 = 'php ' + path + 'md5Log.php ';
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

}
;

/**
 * 获取url中cmd的id
 * @param  cmdUrl cmdUrl字符串数据
 * @return cmdId 获取的cmd id
 * */
function getCmdId(cmdUrl) {
    var indexCMD = cmdUrl.indexOf('&cmd=');
    // console.log('----getCmdId--indexCMD:' + indexCMD);
    var dataDel = cmdUrl.substr(indexCMD + 5);
    // console.log('----getCmdId--dataDel:' + dataDel);
    var indexAnd = dataDel.indexOf('&');
    // console.log('----getCmdId--indexAnd:' + indexAnd);
    var cmdId = dataDel.substr(0, indexAnd);
    console.log('----getCmdId--cmdId:' + cmdId);
    return cmdId;
}

// cmd 二进制流解压缩操作
function gzipBinary(data, cmdId) {
    // var finalPath = cmdJsonPath + 'cmd_' + cmdId + '_' + getNowFormatDate() + '.txt.gz';
    var finalPath = cmdJsonPath + 'cmd_' + cmdId + '.txt.gz';
    var finalPath2 = cmdJsonPath + 'cmd_' + cmdId + '_' + getNowFormatDate() + '.json';

    // if (fs.existsSync(finalPath)) {
    //     // 存在解压前数据文件，删除之
    //     fs.unlinkSync(finalPath);
    // }

    //读取文件
    // var jsonContent = fs.readFileSync(finalPath2, 'utf-8');
    // console.log('json 1 原来数据:' + jsonContent);

    // 写入数据并解压缩处理
    fs.writeFileSync(finalPath, data);

    return;

    // 解压缩函数，并将数据存入json文件中
    var read1 = fs.createReadStream(finalPath);
    // var z = zlib.createGzip();
    var w = fs.createWriteStream(finalPath2);
    console.log('写入111。。。。');
    // r.pipe(gunzipStream).pipe(w, {end: true});
    read1.pipe(gunzipStream).pipe(w);
    console.log('写入2222。。。。');
    // gunzipStream.close();

    setTimeout(function () {
        console.log('frankieTest sdasdsadsadasdasd');
        // read1.end();
        // w.end();
        gunzipStream.end();
        // cmdJsonAppend(jsonContent, finalPath2);
    }, 2000);
    // console.log('json 2:' + r.pipe(gunzipStream).pipe(w));
    // var jsonContent2 = fs.readFileSync(finalPath2, 'utf-8');
    // console.log('json 2:' + jsonContent2);
    return;
}

function cmdJsonAppend(jsonContent, path2) {
    console.log('cmdJsonAppend :' + jsonContent);
    var jsonContent2 = fs.readFileSync(path2, 'utf-8');
    console.log('cmdJsonAppend 2:' + jsonContent2);
    return '';
}

// cmd命令开启web.py服务器
function initWebpyServer() {
    if (isInitServer) {
        console.log('frankieTest 已经开启过webPy服务器');
        return;
    }

    console.log('frankieTest 准备开启webPy服务器');
    // python web.py服务器开启
    /* jshint camelcase: false */
    var cmdPy = 'python ' + path + 'serverHttp.py ' + '5050';
    exec(cmdPy);
    isInitServer = true;
}

function getNowFormatDate() {
    var date = new Date();
    var seperator1 = '-';
    var seperator2 = ':';
    var month = date.getMonth() + 1;
    var strDate = date.getDate();
    if (month >= 1 && month <= 9) {
        month = '0' + month;
    }
    if (strDate >= 0 && strDate <= 9) {
        strDate = '0' + strDate;
    }
    var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate
        + '_' + date.getHours() + seperator2 + date.getMinutes()
        + seperator2 + date.getSeconds() + seperator2 + date.getMilliseconds();
    return currentdate;
}

