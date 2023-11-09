
# 基于 laravel swoole 的tpcserver 实现telnet 交互demo 

## 系统需求：

ubuntu 22

php 7.4

laravel 8

swoole

composer 2


## 启动/测试服务 

service:

进入laravel 目录 

php artisan  telnet:start

client
任意命令行 

telent 127.0.0.1 8765


1）  mul命令：输入 mul x y 输出相乘的结果，例如 mul 3  4 , 得出 12；

2）  incr命令：输入 incr x  输出自增的结果， 例如 incr 10 ， 输出 11;

3）  div命令： 输入 div x y 输出x/y的结果，例如 div 9 4,   输出 2.25; 


 conv_tree


 ## 主要代码

 服务入口和函数实现
 
 app/Console/Commands/telnetServer.php
 
 
 基础类 生成随机数
 
 app/Library/Helper.php  

 


 

 

