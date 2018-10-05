<?php
/**
 * 上传附件和上传视频
 * User: Jinqn
 * Date: 14-04-09
 * Time: 上午10:17
 */
//include "Uploader.class.php";
include "Qiniu_upload.php";
include "conf.php";
/* 上传配置 */
$base64 = "upload";
switch (htmlspecialchars($_GET['action'])) {
    case 'uploadimage':
        $config = array(
            "pathFormat" => $CONFIG['imagePathFormat'],
            "maxSize" => $CONFIG['imageMaxSize'],
            "allowFiles" => $CONFIG['imageAllowFiles']
        );
        $fieldName = $CONFIG['imageFieldName'];
        break;
    case 'uploadscrawl':
        $config = array(
            "pathFormat" => $CONFIG['scrawlPathFormat'],
            "maxSize" => $CONFIG['scrawlMaxSize'],
            "allowFiles" => $CONFIG['scrawlAllowFiles'],
            "oriName" => "scrawl.png"
        );
        $fieldName = $CONFIG['scrawlFieldName'];
        $base64 = "base64";
        break;
    case 'uploadvideo':
        $config = array(
            "pathFormat" => $CONFIG['videoPathFormat'],
            "maxSize" => $CONFIG['videoMaxSize'],
            "allowFiles" => $CONFIG['videoAllowFiles']
        );
        $fieldName = $CONFIG['videoFieldName'];
        break;
    case 'uploadfile':
    default:
        $config = array(
            "pathFormat" => $CONFIG['filePathFormat'],
            "maxSize" => $CONFIG['fileMaxSize'],
            "allowFiles" => $CONFIG['fileAllowFiles']
        );
        $fieldName = $CONFIG['fileFieldName'];
        break;
}


/* 生成上传实例对象并完成上传 */
$config = array(
        'secrectKey'     => $QINIU_SECRET_KEY, 
        'accessKey'      => $QINIU_ACCESS_KEY, 
        'domain'         => $HOST, 
        'bucket'         => $BUCKET, 
        'timeout'        => $TIMEOUT, 
);

$qiniu = new Qiniu($config);
//命名规则
if($SAVETYPE == 'date'){
    $key = 'uploads/'.date('Ymd').'/'.time().mt_rand(0,10).'.'.pathinfo($_FILES[$fieldName]["name"], PATHINFO_EXTENSION);  
}else{
    $key = 'uploads/'.date('Ymd').'/'.$_FILES[$fieldName]['name'];
}

$upfile = array(
        'name'=>'file',
        'fileName'=>$key,
        'fileBody'=>file_get_contents($_FILES[$fieldName]['tmp_name'])
    );
if($base64 == "base64"){
    $upfile['fileName'] = $key.'png';
    $upfile['fileBody'] = base64_decode( $_POST[$fieldName] );
}
$config = array();
$result = $qiniu->upload($config, $upfile);
// var_dump($upfile);
// var_dump($result);
if(!empty($result['hash'])){
    $url = '';
    if(htmlspecialchars($_GET['action']) == 'uploadimage'){
        if($USEWATER){
            $waterBase = urlsafe_base64_encode($WATERIMAGEURL);
            $url  =  $qiniu->downlink($result['key'])."?watermark/1/image/{$waterBase}/dissolve/{$DISSOLVE}/gravity/{$GRAVITY}/dx/{$DX}/dy/{$DY}";
        }else{
            $url  =  $qiniu->downlink($result['key']);
        }
    }else{
            $url  =  $qiniu->downlink($result['key']);
    }
    /*构建返回数据格式*/
    $FileInfo = array(
                      "state" => "SUCCESS",         
                      "url"   => $url,           
                      "title" => $result['key'],         
                      "original" => $_FILES[$fieldName]['name'],       
                      "type" => $_FILES[$fieldName]['type'],            
                      "size" => $_FILES[$fieldName]['size'],           
                  );
    /* 返回数据 */
    return json_encode($FileInfo);
}