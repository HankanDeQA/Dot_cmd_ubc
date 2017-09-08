#!/usr/bin/python
# encoding=utf-8


import web
import json

import urllib
import urllib2

urls = (
    '/', 'hello',
    '/favicon.ico', 'icon'
)
app = web.application(urls, globals())


class hello:
    def GET(self):
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

        revType = revInput['type']
        revId = revInput['id']
        revPlatform = revInput['platform']
        revModule = revInput['module']
        print type(revId)
        print revModule
        print type(revModule)

        if not revType:
            print "type is null"
            return "type is null"
        elif not revId:
            print "id is null"
            return "id is null"
        else:
            if revModule == '1':  # 传来的module字段是1，代表请求的是模板数据
                # print type(get_dot_module(revId, revPlatform))
                print "返回模板数据~~~~~~~~~~~~~~~"
                resultStr = get_dot_module(revId, revPlatform)
                print type(resultStr)
                print resultStr
                return resultStr
            else:
                # 将要打开的文件名
                openName = "json/" + revType.encode('utf-8') + "_" + revId.encode('utf-8') + ".json"
                print openName
                with open(openName, 'r') as load_f:
                    load_dict = json.load(load_f)
                    resultStr = json.dumps(load_dict)
                    print "返回实时数据~~~~~~~~~~~~~~~"
                    print resultStr  # 将dict转成str
                    print type(resultStr)
                    return resultStr


def POST(self):
    print web.input()
    return "POST hello world"


# Process favicon.ico requests
class icon:
    def GET(self):
        return " hello icon"
        # raise web.seeother("/static/favicon.ico")


# 获取打点标准数据，并且返回指定id、平台的模板json字符串
def get_dot_module(id, platform):
    url = "http://ucenter.mbd.baidu.com/ucenter?model=ubc&action=outside&starttime=1479007145&prod=searchbox"
    req = urllib2.Request(url)
    # print req
    res_data = urllib2.urlopen(req)
    res = res_data.read()
    # print res
    # print type(res)

    resJson = json.loads(res)
    # print type(resJson)

    list = resJson["data"]

    for index in range(len(list)):
        item_id = list[index]["item_id"]
        item_platform = list[index]["platform"]

        if item_id.encode() == id:
            if (item_platform.encode() == '') or (item_platform.encode() == platform):  # 没有playform字段，默认会返回
                # print type(json.dumps(list[index]))
                return json.dumps(list[index])


def print_dict(k, v):
    if isinstance(v, dict):
        print k, v
        for kk in v.keys():
            print_dict(kk, v[kk])
    else:
        print k, v


if __name__ == '__main__':
    app.run()
