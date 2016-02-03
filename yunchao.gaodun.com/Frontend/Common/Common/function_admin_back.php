<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
/**
* 创建目录
* 
* @param    string  $path   路径
* @param    string  $mode   属性
* @return   string  如果已经存在则返回true，否则为flase
* @ 致远
*/
function dir_create($path, $mode = 0777) {
    if(is_dir($path)) return TRUE;
    $ftp_enable = 0;
    $path = dir_path($path);
    $temp = explode('/', $path);
    $cur_dir = '';
    $max = count($temp) - 1;
    for($i=0; $i<$max; $i++) {
        $cur_dir .= $temp[$i].'/';
        if (@is_dir($cur_dir)) continue;
        @mkdir($cur_dir, 0777,true);
        @chmod($cur_dir, 0777);
    }
    return is_dir($path);
}

/**
* 转化 \ 为 /
* 
* @param    string  $path   路径
* @return   string  路径
* @ 致远
*/
function dir_path($path) {
    $path = str_replace('\\', '/', $path);
    if(substr($path, -1) != '/') $path = $path.'/';
    return $path;
}

/**
 * 字符截取 支持UTF8/GBK
 * @param $string
 * @param $length
 * @param $dot
 */
function str_cut($string, $length=45, $dot = '...') {
    $strlen = strlen($string);
    if($strlen <= $length) return $string;
    $string = str_replace(array(' ','&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array('∵',' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
    $strcut = '';
    if(strtolower(CHARSET) == 'utf-8') {
        $length = intval($length-strlen($dot)-$length/3);
        $n = $tn = $noc = 0;
        while($n < strlen($string)) {
            $t = ord($string[$n]);
            if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1; $n++; $noc++;
            } elseif(194 <= $t && $t <= 223) {
                $tn = 2; $n += 2; $noc += 2;
            } elseif(224 <= $t && $t <= 239) {
                $tn = 3; $n += 3; $noc += 2;
            } elseif(240 <= $t && $t <= 247) {
                $tn = 4; $n += 4; $noc += 2;
            } elseif(248 <= $t && $t <= 251) {
                $tn = 5; $n += 5; $noc += 2;
            } elseif($t == 252 || $t == 253) {
                $tn = 6; $n += 6; $noc += 2;
            } else {
                $n++;
            }
            if($noc >= $length) {
                break;
            }
        }
        if($noc > $length) {
            $n -= $tn;
        }
        $strcut = substr($string, 0, $n);
        $strcut = str_replace(array('∵', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), array(' ', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), $strcut);
    } else {
        $dotlen = strlen($dot);
        $maxi = $length - $dotlen - 1;
        $current_str = '';
        $search_arr = array('&',' ', '"', "'", '“', '”', '—', '<', '>', '·', '…','∵');
        $replace_arr = array('&amp;','&nbsp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;',' ');
        $search_flip = array_flip($search_arr);
        for ($i = 0; $i < $maxi; $i++) {
            $current_str = ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
            if (in_array($current_str, $search_arr)) {
                $key = $search_flip[$current_str];
                $current_str = str_replace($search_arr[$key], $replace_arr[$key], $current_str);
            }
            $strcut .= $current_str;
        }
    }
    return $strcut.$dot;
}

/**
* 将年月日转为年月
* @ param    
* @ return 
* @ 致远
*/
function ymdTOym($date) {
    if($date){
        $arr = explode('-',$date);
        return $arr[0].'-'.$arr[1];
    }
}

/**
* 简历投递类型转换文本
* @ param    
* @ return 
* @ 致远
*/
function resumeType($key){
    switch($key){
        case 0:
            return '测试';
            break;
        case 1:
            return '自投';
            break;
        case 2:
            return '邀请投递';
            break;
        default:
            return '未知';
    }
}
/**
* 简历投递状态转换文本
* @ param    
* @ return 
* @ 致远
*/
function resumeStatus($key){
    switch($key){
        case 0:
            return '测试';
            break;
        case 1:
            return '未查看';
            break;
        case 2:
            return '未处理';
            break;
        case 3:
            return '允许面试';
            break;
        case 4:
            return '拒绝面试';
            break;
        case 5:
            return '待定';
            break;
        default:
            return '未知';
    }
}

//意见反馈状态
function feedStatus($key){
     switch(intval($key)){
        case 0:
            return '测试';
            break;
        case 1:
            return '未查看';
            break;
        case 2:
            return '未处理';
            break;
        case 3:
            return '已处理';
            break;
        default:
            return '未知';
    }   
}

function deleteStatus($key){
    switch(intval($key)){
        case 0:
            return '测试';
            break;
        case 1:
            return '已删除';
            break;
        case 2:
            return '未删除';
            break;
        default:
            return '未知';
    }    
}

/**
 * 字符截取 支持UTF8/GBK
 * @param $string
 * @param $length
 * @param $dot
 */
function string_cute($string, $length, $dot = '...') {
    $strlen = strlen($string);
    if($strlen <= $length) return $string;
    $string = str_replace(array(' ','&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array('∵',' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
    $strcut = '';
    if(strtolower(CHARSET) == 'utf-8') {
        $length = intval($length-strlen($dot)-$length/3);
        $n = $tn = $noc = 0;
        while($n < strlen($string)) {
            $t = ord($string[$n]);
            if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1; $n++; $noc++;
            } elseif(194 <= $t && $t <= 223) {
                $tn = 2; $n += 2; $noc += 2;
            } elseif(224 <= $t && $t <= 239) {
                $tn = 3; $n += 3; $noc += 2;
            } elseif(240 <= $t && $t <= 247) {
                $tn = 4; $n += 4; $noc += 2;
            } elseif(248 <= $t && $t <= 251) {
                $tn = 5; $n += 5; $noc += 2;
            } elseif($t == 252 || $t == 253) {
                $tn = 6; $n += 6; $noc += 2;
            } else {
                $n++;
            }
            if($noc >= $length) {
                break;
            }
        }
        if($noc > $length) {
            $n -= $tn;
        }
        $strcut = substr($string, 0, $n);
        $strcut = str_replace(array('∵', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), array(' ', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), $strcut);
    } else {
        $dotlen = strlen($dot);
        $maxi = $length - $dotlen - 1;
        $current_str = '';
        $search_arr = array('&',' ', '"', "'", '“', '”', '—', '<', '>', '·', '…','∵');
        $replace_arr = array('&amp;','&nbsp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;',' ');
        $search_flip = array_flip($search_arr);
        for ($i = 0; $i < $maxi; $i++) {
            $current_str = ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
            if (in_array($current_str, $search_arr)) {
                $key = $search_flip[$current_str];
                $current_str = str_replace($search_arr[$key], $replace_arr[$key], $current_str);
            }
            $strcut .= $current_str;
        }
    }
    return $strcut.$dot;
}

function majorChecked($key,$data){
    if(in_array($key,$data)){
        return 'checked=checked';
    }else{
        return '';
    }
}

/*
 * @function 加密及解密 仅用于id的加密与解密
 * @param int
 * @return
 * @author allen
 */
function enIntFunc($param){
    $Crypt = new \Think\Crypt();
    if(is_numeric($param)){
        return $Crypt::encrypt($param,md5(C('DATA_AUTH_KEY')));
    }else{
        return $Crypt::decrypt($param,md5(C('DATA_AUTH_KEY')));
    }
}

//用于企业招募列表
//如果account_id不为空，则判断为注册会员
function emptyAccountRegin($account_id){
    if($account_id){
        return '注册会员';
    }else{
        return '未注册';
    }
}

//招募企业的联系状态
function recruitContact($key){
    if($key==1){
        return '已联系';
    }
    if($key==2){
        return '未联系';
    }
}


//邮箱状态
function emailStatus($key){
    if($key==1){
        return '已验证';
    }
    if($key==2 || $key==0){
        return '未验证';
    }
}

function time2Unit($date){
    $pretime = strtotime($date);
    $nowTime = time();
    $time = $nowTime - $pretime;
    $arr = array('minute' => '分钟前','hour' => '小时前');
    if($time < 60 * 60){
        $unitStr = floor($time / 60) > 0 ? floor($time / 60) . $arr['minute'] : '1' . $arr['minute'];
    }elseif($time < 60 * 60 * 24){
        $unitStr = floor($time / 60 / 60) > 0 ? floor($time / 60 / 60) . $arr['hour'] : '1' . $arr['hour'];
    }else{
        $unitStr = substr($date,0,10);
    }
    return $unitStr;
}

function date4ToFormate($date){     
    $data = explode('-',$date);    
    if(is_array($data)){
        $a = explode(' ', $data[2]);
        $b = explode(':', $a[1]);
        return $data[0].'年'.$data[1].'月'.$a[0].'日   '.$b[0].':'.$b[1];
    }else{
        return '';
    }
}

function name2ellipsis($name,$count = 14){
    if(!empty($name)){
        if(mb_strlen($name,'utf8') > $count){
            $name = mb_substr($name,0,$count - 1,'UTF-8') . '...';
        }
    }
    return $name;

}

/*
 * 简历预览时候对浏览器进行判断
 */
function checkBrowser(){
    $brower_type = getBrowser();
    if($brower_type == 'ie'){
        $brower_version = getBrowserVer();
        if($brower_version >= 11){
            return true;
        }
    }
    if($brower_type == 'chrome'){
        $brower_version = getBrowserVer();
        if($brower_version >= 42){
            return true;
        }
    }
    if($brower_type == 'spartan'){
        return true;
    }
    return false;
}

function getBrowserVer(){
    if (empty($_SERVER['HTTP_USER_AGENT'])){    //当浏览器没有发送访问者的信息的时候
        return 'unknow';
    }
    $agent= $_SERVER['HTTP_USER_AGENT'];
    if (preg_match('/MSIE\s(\d+)\..*/i', $agent, $regs)){
        return $regs[1];//IE浏览器版本号
    } elseif (preg_match('/rv:([^\s;\)]+)/i', $agent, $rvRegs)){
        return $rvRegs[1];
    } elseif (preg_match('/FireFox\/(\d+)\..*/i', $agent, $regs)){
        return $regs[1]; //火狐浏览器版本号
    } elseif (preg_match('/Opera[\s|\/](\d+)\..*/i', $agent, $regs)){
        return $regs[1];//opera浏览器版本号
    } elseif (preg_match('/Chrome\/(\d+)\..*/i', $agent, $regs)){
        return $regs[1];//谷歌浏览器版本号
    } elseif ((strpos($agent,'Chrome')==false)&&preg_match('/Safari\/(\d+)\..*$/i', $agent, $regs)){
        return $regs[1];
    } else{
        return 'unknow';
    }
}

//职位详情url
function postUrl($id=''){
    return C('FRONT_URL').'/Post/info/id/'.enInt($id);
}

//企业详情url
function enterpriseUrl($id=''){
    return C('FRONT_URL').'/Enterprise/info/id/'.enInt($id);
}
