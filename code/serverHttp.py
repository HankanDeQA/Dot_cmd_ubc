#!/usr/bin/python
# encoding=utf-8

import web
import json
import urllib

urls = (
    '/.*', 'hello'
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

        # print revInput.split('&')

        with open("json/demo.json", 'r') as load_f:
            load_dict = json.load(load_f)
            resultStr = json.dumps(load_dict)
            print resultStr #将dict转成str


        return resultStr


    def POST(self):
        print web.input()
        return "POST hello world"


def print_dict(k, v):
    if isinstance(v, dict):
        print k, v
        for kk in v.keys():
            print_dict(kk, v[kk])
    else:
        print k, v

if __name__ == '__main__':
    app.run()

    # from BaseHTTPServer import BaseHTTPRequestHandler, HTTPServer
    # import io, shutil
    # import urllib
    # import os, sys


    # class MyRequestHandler(BaseHTTPRequestHandler):
    #     def do_GET(self):
    #         mpath, margs = urllib.splitquery(self.path)  # ?分割
    #         print self.path
    #         self.do_action(mpath, margs)

    #     def do_POST(self):
    #         mpath, margs = urllib.splitquery(self.path)
    #         datas = self.rfile.read(int(self.headers['content-length']))
    #         print datas
    #         self.do_action(mpath, datas)

    #     def do_action(self, path, args):
    #         self.outputtxt(path + args)

    #     def outputtxt(self, content):
    #         # 指定返回编码
    #         enc = "UTF-8"
    #         content = content.encode(enc)
    #         f = io.BytesIO()
    #         f.write(content)
    #         f.seek(0)
    #         self.send_response(200)
    #         self.send_header("Content-type", "text/html; charset=%s" % enc)
    #         self.send_header("Content-Length", str(len(content)))
    #         self.end_headers()
    #         shutil.copyfileobj(f, self.wfile)
