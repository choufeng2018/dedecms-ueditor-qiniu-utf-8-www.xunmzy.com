<?php

/*
 * @description   文件上传方法
 * 原作者 widuu  http://www.widuu.com
 * 修改人 寻梦资源网 http://www.xunmzy.com
 */
 
global $QINIU_ACCESS_KEY;
global $QINIU_SECRET_KEY;

$QINIU_UP_HOST	= 'http://up-z2.qiniu.com';
$QINIU_RS_HOST	= 'http://rs.qbox.me';
$QINIU_RSF_HOST	= 'http://rsf.qbox.me';

//配置$QINIU_ACCESS_KEY和$QINIU_SECRET_KEY 为你自己的key
$QINIU_ACCESS_KEY	= '你自己的access';
$QINIU_SECRET_KEY	= '你自己的secret';

//配置bucket为你的bucket
$BUCKET = "你的bucket";

//配置你的域名访问地址
$HOST  = "你的域名";

//上传超时时间
$TIMEOUT = "3600";

//保存规则
$SAVETYPE = "date";

//开启水印
$USEWATER = true;
$WATERIMAGEURL = "水印地址，七牛云图片地址"; //七牛上的图片地址
//水印透明度
$DISSOLVE = 100;
//水印位置
$GRAVITY = "SouthEast";
//边距横向位置
$DX  = 10;
//边距纵向位置
$DY  = 10;

function urlsafe_base64_encode($data){
	$find = array('+', '/');
	$replace = array('-', '_');
	return str_replace($find, $replace, base64_encode($data));
}


