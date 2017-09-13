#!/usr/bin/python
# encoding=utf-8
# Copyright (c) 2017 - pengfei06 <pengfei06@baidu.com>
"""This is a one line docstring."""

import web
import json

import urllib
import urllib2

import os

import gzip

import shutil

urls = (
    '/', 'Hello',
    '/favicon.ico', 'Icon'
)
app = web.application(urls, globals())

openModuleName = "dot_module_all.json"  # 模板数据保存的文件
rootDirName = os.path.dirname(os.path.realpath(__file__))
jsonDirName = rootDirName + "/json"  # 数据保存的文件夹目录


class Hello(object):
    """This is a one line docstring."""

    def GET(self):
        """This is a one line docstring."""
        # web.header('content-type','text/json')
        # print "hello~~~~"
        print web.input()

        # 解决跨域访问问题
        web.header("Access-Control-Allow-Origin", "*")
        web.header("Access-Control-Allow-Methods", "POST, GET, OPTIONS, PUT, DELETE")
        web.header("Access-Control-Allow-Headers", "*")

        revInput = web.input()
        print revInput
        print type(revInput)

        # revType = revInput['type']
        revType = revInput.get('type')
        revId = revInput.get('id')
        revPlatform = revInput.get('playform')
        revModule = revInput.get('module')
        revDelete = revInput.get('delete')
        print revType
        print revId
        print revPlatform
        print revModule
        print revDelete

        # 删除所有文件
        if revDelete == '1':
            print "need delete all"
            print jsonDirName
            delete_all_files(jsonDirName)

            return "Complete delete all files!"

        if not revType:
            print "type is null"
            return "type is null"
        elif not revId:
            print "id is null"
            return "id is null"
        else:
            if (revModule == '1') and (revType == 'ubc'):  # 传来的module字段是1，代表请求的是模板数据
                # print type(get_dot_module(revId, revPlatform))
                print "返回模板数据~~~~~~~~~~~~~~~"
                resultStr = get_dot_module(revId, revPlatform)
                print type(resultStr)
                print resultStr
                return resultStr
            if (revModule == '1') and (revType == 'cmd'):  # 传来的module字段是1，代表请求的是模板数据
                return "暂时不支持查询cmd模板数据"
            else:
                # 将要打开的文件名
                openName = "json/" + revType.encode('utf-8') + "_" + revId.encode('utf-8') + ".json"

                if "cmd" in revType.encode('utf-8'):  # 打开的cmd文件
                    cmdGzipName = "json/" + revType.encode('utf-8') + "_" + revId.encode('utf-8') + ".txt.gz"
                    gzip_cmd(cmdGzipName, openName)

                if os.path.exists(openName):
                    with open(openName, 'r') as load_f:
                        load_dict = json.load(load_f)
                        resultStr = json.dumps(load_dict)
                        print "返回实时数据~~~~~~~~~~~~~~~"
                        print resultStr  # 将dict转成str
                        print type(resultStr)
                        return resultStr
                else:
                    return "___Server___result is not exist!"

    def POST(self):
        """This is a one line docstring."""
        print web.input()
        return "POST hello world"


# Process favicon.ico requests
class Icon(object):
    """This is a one line docstring."""

    def GET(self):
        """This is a one line docstring."""
        return " hello icon"
        # raise web.seeother("/static/favicon.ico")


# 获取打点标准数据，并且返回指定id、平台的模板json字符串
def get_dot_module(id, platform):
    """This is a one line docstring."""
    # 将要打开的文件名
    # openModuleName = "dot_module_all.json"

    if os.path.exists(openModuleName):
        with open(openModuleName, 'r') as load_f:
            load_dict = json.load(load_f)

        list = load_dict["data"]

        for index in range(len(list)):
            item_id = list[index]["item_id"]
            item_platform = list[index]["platform"]

            if item_id.encode() == id:
                if (item_platform.encode() == '') or (item_platform.encode() == platform):  # 没有playform字段，默认会返回
                    # print type(json.dumps(list[index]))
                    return json.dumps(list[index])

    return "result is null"


# 获取全部打点标准数据，并且保存入文件
def get_totol_dot_module():
    """This is a one line docstring."""
    url = "http://ucenter.mbd.baidu.com/ucenter?" \
          "model=ubc&action=outside&starttime=1479007145&prod=searchbox"
    req = urllib2.Request(url)
    # print req
    res_data = urllib2.urlopen(req)
    res = res_data.read()
    # print res
    print type(res)  # 打印返回数据的格式

    obj = json.loads(res)
    # print type(obj)

    # 以字典的形式存入文件，打开文件并写入，不存在则创建文件
    with open("dot_module_all.json", 'w') as f:
        json.dump(obj, f)


# 解压缩cmd打点数据，并返回
def gzip_cmd(fn_in, fn_out):
    if os.path.exists(fn_in):
        f_in = gzip.open(fn_in, 'rb')
        f_out = open(fn_out, 'wb')
        file_content = f_in.read()
        f_out.write(file_content)
        f_out.close()
        f_in.close()
        print "解压缩cmd打点数据"

    return ""


# 删除指定文件夹下所有文件（不删除此文件夹）
def delete_all_files(dirName):
    shutil.rmtree(dirName)
    os.mkdir(dirName)


if __name__ == '__main__':
    get_totol_dot_module()  # 请求模板数据并写入文件
    app.run()
