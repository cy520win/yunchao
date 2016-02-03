<?php
// +----------------------------------------------------------------------
// | 文件类型 ：控制器 模型 函数 视图模板 功能模块 类库
// +----------------------------------------------------------------------
// | 文件说明 ：
// +----------------------------------------------------------------------
// | 创建时间 ：
// +----------------------------------------------------------------------
// | 作者 : 致远<george.zou@gaodun.cn> 
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

//密码正则
function pwdReg(){
    return '/[a-zA-Z0-9]{6,16}/';
}

//匹配字母+数字
function pwdCharInt($pwd){
    if(strlen($pwd)<6||strlen($pwd)>16){
        $bool=false;
    }else{
        $bool=true;
    }
    $bool2 = preg_match('/[a-zA-Z]+/i',$pwd);
    $bool3 = preg_match('/[0-9]+/',$pwd);
    if(!$bool||!$bool2||!$bool3){
        return false;
    }else{
        return true;
    }
}

/**
 * 验证密码格式
 * @param    string  $path   路径
 * @return   string  路径
 * @ 致远
 */
function checkpass($pass){
    $bool = pwdCharInt($pass);
    return $bool;
}

/**
 * 验证二次密码
 * @param    string  $path   路径
 * @return   string  路径
 * @ 致远
 */
function checkrepass($pass,$repass){
    $bool['pass'] = pwdCharInt($pass);
    $bool['repass'] = pwdCharInt($repass);
    $bool['equal'] = strtolower($pass) != strtolower($repass) ? false : true;
    return $bool;
}

function checkrepassReg($pass,$repass){
    $bool = strtolower($pass) == strtolower($repass) ? true : false;
    return $bool;
}

/**
 * 验证邮箱规则
 * @param    string  $path   路径
 * @return   string  路径
 * @ 致远
 */
function checkMailRule($email){
    $email = strtolower($email);
    $reg = '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/';
    return preg_match($reg,$email);
}

function checkMailRuleStudent($email){
    $email = strtolower($email);
    $reg = '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/';
    return preg_match($reg,$email);
}

function checkMailRuleStudent2($email){
    $email = strtolower($email);
    $reg = "/^\w+([-+.]\w+)*@(sina|qq|163|126|sohu|hotmail|gmail|139)+\.(com|cn|net)$/i";
    return preg_match($reg,$email);
}

/**
 * 手机号13* 15* 18*规则
 * @param    string  $path   路径
 * @return   string  路径
 * @ 致远
 */
function checkPhoneRule($phone){
    $reg = '/^((13[0-9])|(15[0-9])|(18[0-9])|(17[6-8])|(14[5-7]))[0-9]{8}$/';
    return preg_match($reg,$phone);
}

function checkPhoneRuleSpecial($phone){
    $bool = checkPhoneRule($phone);
    $tel2 =  '/^(010|02\d{1}|0[3-9]\d{2})-*\d{7,9}(-\d+)?$/'; //带区号座机
    $bool2 = preg_match($tel2,$phone);
    $tel3 = '/^\d{7,9}$/'; //不带区号座机
    $bool3 = preg_match($tel3,$phone);
    if($bool || $bool2 || $bool3){
        return true;
    }else{
        return false;
    }
}

/*
* @function 将性别的数字标示替换为文本
* @author 致远<george.zou@gaodun.cn>    
* @remark 
* @最后更新时间 
*/
function getGerderText($key,$type='CH'){
    $gender = array(
        'CH' => array(1=>'男',2=>'女'),
        'EN' => array(1=>'Male',2=>'Female')
    );
    if(empty($key)){
        return $gender[$type];
    }else{
        return $gender[$type][$key];
    }
}

/*
* @function 将证书状态数字标示替换为文本
* @author 致远<george.zou@gaodun.cn>    
* @remark 
* @最后更新时间 
*/
function getCertStatusText($key,$type='CH'){
    $data = array(
        'CH' => array(1=>'在读',2=>'通过'),
        'EN' => array(1=>'In progress',2=>'Passed')
    );
    if(empty($key)){
        return $data[$type];
    }else{
        return $data[$type][$key];
    }
}

/*
* @function 将政治面貌的数字标示替换为文本
* @author 致远<george.zou@gaodun.cn>    
* @remark 
* @最后更新时间 
*/
function getPoliticsText($key = '',$type='CH'){
    $polittics = array(
        'CH' => array(1=>'党员',2=>'其他'),
        'EN' => array(1=>'Communit',2=>'Other')
    );
    if(empty($key)){
        return $polittics[$type];
    }else{
        return $polittics[$type][$key];
    }
}

/*
* @function 将证书数字类型转化为文本
* @author 致远<george.zou@gaodun.cn>    
* @remark 
* @最后更新时间 
*/
function getCertificateText($key = '',$type = 'CH'){
    $data = array(
        'CH' => array(1=>'财经',2=>'语言',0=>'自定义'),
        'EN' => array(1=>'Finance',2=>'Language',0=>'Customize'),
    );
    if($key == ''){
        return $data[$type];
    }else{
        return $data[$type][$key];
    }
}

/*
* @function 将专业类型数字类型转化为文本
* @author 致远<george.zou@gaodun.cn>    
* @remark 
* @最后更新时间 
*/
function getMajorTypeText($key=null,&$MajorTypeModel = null,$type='CH'){
    if(empty($MajorTypeModel)){
        $MajorTypeModel = D('MajorType');
    }
    $data = $MajorTypeModel->getMajorTypeSelect($type);
    if(is_null($key)){
        return $data;
    }
    if($key){
        return $data[$key];
    }
}

/*
* @function 企业将学历数字类型转化为文本
* @author 致远<george.zou@gaodun.cn>    
* @remark 
* @最后更新时间 
*/
function getDegreeText($key = null){
    $data = array(1 => '专科',2 => '本科',3 => '硕士',4 => '博士',5 => '学历不限');
    if(is_null($key)){
        return $data;
    }
    if($key){
        return $data[$key];
    }
}


function getDegreeTextSTU($key = '',$type='CH'){
    $data = array(
        'CH' => array(1 => '专科',2 => '本科',3 => '硕士',4 => '博士'),
        'EN' => array(1 => 'Associate',2 => 'Bachelor',3 => 'Master',4 => 'Doctorate')
    );
    if(empty($key)){
        return $data[$type];
    }
    if($key){
        return $data[$type][$key];
    }
}

/*
* @function 将年级数字类型转化为文本
* @author 致远<george.zou@gaodun.cn>    
* @remark 2015-10-19 09:49:07  星期一
*/
function getGradeText($key = '',$type='CH',$model=FALSE){
    $year = date('Y',NOW_TIME);
    $year_start = $year-3;
    $year_end = $year+5;
    if(!$model){
        for($i=$year_start;$i<$year_end;$i++){
            $ch[$i] = $i.'年';
            $en[$i] = $i;
        }
        $data = array(
            // 'CH' => array(1 => '2018年',2 => '2017年',3 => '2016年',4 => '2015年',5 => '2014年及以前'),
            // 'EN' => array(1 => '2018',2 => '2017',3 => '2016',4 => '2015',5 => 'before 2014')
            'CH' => $ch,
            'EN' => $en
        );
        if(empty($key)){
            return $data[$type];
        }
        if($key){
            return $data[$type][$key];
        }
    }
    if($model){
        for($i=$year_start;$i<$year_end;$i++){
            $ch[$i] = array('name' => "{$i}年",'flag' => 'n');
        }     
        return $ch;
    }

}

function graduateYearPost($data){
    $string = '';
    if(!$data){
        return $string;
    }
    foreach($data as $k=>$v){
        $string .= getGradeText($v).'毕业、';
    }
    return rtrim($string,'、');
}

/*
* @function 将实习天数数字类型转化为文本
* @author 致远<george.zou@gaodun.cn>    
* @remark 
* @最后更新时间 
*/
function getWorkDayText($key = '',$type='CH'){
    $data = array(
        'CH' => array(1 => '1天',2 => '2天',3 => '3天',4 => '4天',5 => '5天'),
        'EN' => array(1 => 'one day',2 => 'two days',3 => 'three days',4 => 'four days',5 => 'five days'),
    );
    if(empty($key)){
        return $data[$type];
    }
    if($key){
        return $data[$type][$key];
    }
}

/*
* @function 将实习日薪数字类型转化为文本
* @author 致远<george.zou@gaodun.cn>    
* @remark 
* @最后更新时间 
*/
function getDaySalaryText($key = '',$type = 'CH'){
    $data = array(
        'CH' => array(1 => '50以下/天',2 => '50~99元/天',3 => '100~200元/天',4 => '200以上/天',5=>'无'),
        'EN' => array(1 => 'below ￥50/day',2 => '￥50/day-￥99/day',3 => '￥100/day - ￥200/day',4 => 'over ￥200/day',
            5=>'unpaid')
    );
    if(empty($key)){
        return $data[$type];
    }
    if($key){
        if($key==5){
            if($type == 'CH'){
                return '对薪资无要求';
            }else{
                return 'unpaid';
            }
        }else{
            return $data[$type][$key];
        }
    }
}

//订阅器推荐职位模板
function getDaySalaryRecommend($key){
    $data = array(1 => '50元以下',2 => '50~99元',3 => '100~200元',4 => '200元以上',5=>'无');
    if(is_null($key)){
        return $data;
    }
    if($key){
        return $data[$key];
    }
}

/*
* @function 将岗位订阅周期数字类型转化为文本
* @author 致远<george.zou@gaodun.cn>    
* @remark 
* @最后更新时间 
*/
function getCycleDayText($key){
    $data = array(3 => '3天',7 => '7天');
    if(empty($data[$key])){
        return $data;
    }else{
        return $data[$key];
    }
}

/*
* @function 将校内职务数字类型转化为文本
* @author 致远<george.zou@gaodun.cn>    
* @remark 
* @最后更新时间 
*/
function getScholJobText($key){
    $data2 = D('SchoolJob')->getSchoolJobList();
    foreach($data2 as $k=>$v){
        $data[$v['pkid']] = $v['title'];
    }
    if(empty($data[$key])){
        return $data;
    }else{
        return $data[$key];
    }
}

/*
* @function 将简历状态数字类型转化为文本
* @author 致远<george.zou@gaodun.cn>    
* @remark 
* @最后更新时间 
*/
function getResumeStatusText($key){
    $data = array(1 => '未查看', 2 => '已查看',3 => '允许面试', 4 => '不合适','5'=>'待定中');
    if(empty($data[$key])){
        return $data;
    }else{
        return $data[$key];
    }
}

/*
* @function 简历投递类型
* @author 致远<george.zou@gaodun.cn>    
* @remark 
* @最后更新时间 
*/
function getPostTypeText($key){
    $data = array(1=>'自投',2=>'邀请');
    if(empty($data[$key])){
        return $data;
    }else{
        return $data[$key];
    }
}

/*
* @function 
* @author 致远<george.zou@gaodun.cn>    
* @remark 
* @最后更新时间 
*/
function dateToFormate($date){
    $data = explode('-',$date);
    if(is_array($data)){
        return $data[0].'年'.$data[1].'月';
    }else{
        return '';
    }
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

function date2ToFormate($date){
    $data = explode('-',$date);
    if(is_array($data)){
        return $data[0].'-'.$data[1];
    }else{
        return '';
    }
}

function date3ToFormate($date){
    if($date){
       return '&nbsp;'.date('Y-m',strtotime($date)).'&nbsp;';  
    }else{
        return '';
    }
}

/*
* @function 岗位一级分类
* @author 致远<george.zou@gaodun.cn>    
* @remark 
* @最后更新时间 
*/
function getPostCategory($type = 'CH'){
    $data = D('PostCategory')->getPostParentCate();
    if($type == 'CH'){
        foreach($data as $k=>$v){
            $result[$v['pkid']] = $v['title'];
        }
    }else{
        foreach($data as $k=>$v){
            $result[$v['pkid']] = $v['title_en'];
        }
    }
    return $result;
}

/*
* @function 将城市文本转化为城市ID
* @author 致远<george.zou@gaodun.cn>    
* @remark 
* @最后更新时间 
*/
function cityNameToid($name='',$type = 'CH'){
    if($name){
        $city = D('Regions')->reginonNameToid($name,$type);
        if(empty($city['region_id']) && $name == 'HongKong'){
            $city['region_id'] = 810000;
        }
        return $city['region_id'];
    }
}

//将城市region_id转化为文本
function regionIdToname($region_id,&$Regions = null,$type='CH'){
    if(empty($Regions)){
        $Regions = D('Regions');
    }
    $city = $Regions->regionIdToname($region_id,$type);
    return $city;
}

//将城市region_id转化为文本，手机端专用
function regionId2name($region_id,$Regions){
    if(empty($Regions)){
        $Regions = D('Regions');
    }
    $city = $Regions->regionIdToname($region_id);
    return mb_substr($city,0,mb_strlen($city,'utf8')-1,'utf8');
}

/*
* @function 格式化时间
* @author 致远<george.zou@gaodun.cn>    
* @remark 
* @最后更新时间 
*/
function dateTime(){
    return date('Y-m-d H:i:s',NOW_TIME);
}

function dateMate($date,$type='time'){
    if($type == 'time'){
       return date('Y-m-d H:i:s',NOW_TIME); 
    }
    if($type == 'date'){
        return date('Y-m-d',strtotime($date));
    }  
}
/*
* @function AJAX返回JSON 只接受utf-8编码的字符
* @author 致远<george.zou@gaodun.cn>    
* @remark 
* @最后更新时间 
*/
function returnJson($data,$option=0){
    header('Content-Type:application/json;charset=utf-8');
    exit(json_encode($data,$option));
}

/*
 * @function 加密及解密 仅用于id的加密与解密
 * @param int
 * @return
 * @author allen
 */
function enInt($param){
    $Crypt = new \Think\Crypt();
    if(is_numeric($param)){
        return $Crypt::encrypt($param,md5(C('DATA_AUTH_KEY')));
    }else{
        return $Crypt::decrypt($param,md5(C('DATA_AUTH_KEY')));
    }
}

/*
* @function 
* @author 致远<george.zou@gaodun.cn>    
* @remark 最后更新时间
*/
function arrTokey($data){
    foreach($data as $k=>$v){
        $data4[$v['pkid']] = $v['full_name'];
    }
    return $data4;
}

function strLength($string,$num=32){
    if(strlen($string)>3*$num){//一个中文3个字符
        return false;
    }else{
        return true;
    }
}

//企业规模文本
function getScaleText($key,$scaleModel){
    if($key==0){
        return '';
    }
    if(empty($scaleModel)){
        $scaleModel = D('Scale');
    }
    $scale = $scaleModel->scale();
    if(is_null($key)){
        $scale;
    }
    if($key){
        return $scale[$key];
    }
}
//企业行业文本
function getIndustryText($key,$indusryModel){
    if(empty($indusryModel)){
        $indusryModel = D('Industry');
    }
    $indusry = $indusryModel->getIndustryList();
    if($key){
        return $indusry[$key];
    }else{
        return $indusry;
    }
}

/*
* @function 图片路径是否存在
* @author 致远<george.zou@gaodun.cn>    
* @remark 最后更新时间
*/
function imageExist($image){
    $path = preg_match("/^(http:\/\/){1}/i",$image);
    if(!$path){
        $image = C('ADMIN_URL').'/'.$image;
    }
    return $image;
}

//检查企业logo不存在，返回默认logo
function imageLogoExist($path){
    if(!$path){
        return C('APP_URL')."/Public/static/images/enterprise_basic_information/enterpraise_logo.png";
    }
    $bool = file_get_contents($path);
    if($bool){
        return $path;
    }else{
        return C('APP_URL')."/Public/static/images/enterprise_basic_information/enterpraise_logo.png";
    }
}

/*
 * @function 计算时间差
 * @param date $date
 * @param string $str
 * @author allen
 */
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

//简历反馈时间文本
function timeText($key){
    $time = array('1'=>'投递时间','2'=>'查看时间','3'=>'处理时间');
    return $time[$key];
}

/**
 * 安全过滤函数
 * @param $string
 * @return string
 */
function safe_replace($string) {
    $string = str_replace('%20','',$string);
    $string = str_replace('%27','',$string);
    $string = str_replace('%2527','',$string);
    $string = str_replace('*','',$string);
    $string = str_replace('"','&quot;',$string);
    $string = str_replace("'",'',$string);
    $string = str_replace('"','',$string);
    $string = str_replace(';','',$string);
    $string = str_replace('<','&lt;',$string);
    $string = str_replace('>','&gt;',$string);
    $string = str_replace("{",'',$string);
    $string = str_replace('}','',$string);
    $string = str_replace('\\','',$string);
    return $string;
}

/**
 * 格式化文本域内容
 *
 * @param $string 文本域内容
 * @return string
 */
function trim_textarea($string) {
    $string = nl2br(str_replace(' ','&nbsp;',$string));
    return $string;
}

//$string 会场企业名称中文字符串截取；$length 字数，省略符号
function string_cute_cn($string){
    if($string){
        $length = 9*3;
        return string_cute($string,$length,'...');
    }else{
        return '&nbsp;';
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
    $encode = mb_detect_encoding($string, array("ASCII","UTF-8","GB2312","GBK","BIG5"));
    if($strlen <= $length) return $string;
    $string = str_replace(array(' ','&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array('∵',' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
    $strcut = '';
    if(strtolower($encode) == 'utf-8') {
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

//处理多实习地址城市
function expectCityText($data){
    $city_text = '';
    foreach($data as $k=>$v){
        if($v['name']=='city_text' && $v['value']){
            $city_text .= $v['value'].',';
            unset($data[$k]);
        }
    }
    return $city_text ? trim($city_text,',') : '';
}
//处理多实习地址城市region_id
function expectCityID($string){
    $city_arr = explode(',',$string);
    $city_str = '';
    if(is_array($city_arr)){
        foreach($city_arr as $v){
            if($v){
                $city_str .= cityNameToid($v).',';
            }
        }
    }
    return $city_str ? trim($city_str,',') : '';
}
//处理多实习地址城市name
function expectCityName($string,$type=''){
    $city_arr = explode(',',$string);
    $city_count = count($city_arr);
    $RegionsModel = D('Regions');
    if(!empty($type) && $city_count > 3){
        $cityCount = 3;
    }else{
        $cityCount = $city_count;
    }
    for($i=0;$i<$cityCount;$i++){
        $city_name = $RegionsModel->regionIdToname($city_arr[$i]);
        $city_name_arr[] = mb_substr($city_name,-1,1,'utf8') == '市' ?
            mb_substr($city_name,0,mb_strlen($city_name, 'utf8')-1, 'utf8'):
            $city_name;
    }
    $city_str = implode('，',$city_name_arr);
    return $city_str ? !empty($type) && $city_count > 3 ? $city_str . '...' : $city_str : '';
}

function expectCityNameLI($string){
    $city_arr = explode(',',$string);
    $city_str = '';
    $RegionsModel = D('Regions');
    foreach($city_arr as $v){
        if($v){
            $city_name = $RegionsModel->regionIdToname($v);
            $city_str .= '<li>'.$city_name.' <span onclick="delSelCity(this,event)">×</span><input type="hidden" name="city_text" value='.$city_name.'></li>';
        }
    }
    return $city_str ? $city_str:'';
}


/*
 * 用于简历下载中textarea处理
 */
function n2p($data){
    if(!empty($data)){
        $newData = explode('\n',$data);
        $i = '';
        foreach($newData as $de){
            $i .= '<p>'.$de.'</p>';
        }
    }
    return $i ? $i : '';

}

//检查选中专业
function majorChecked($key,$data){
    if(in_array($key,$data)){
        return 'checked=checked';
    }else{
        return '';
    }
}

/*
* @function 
* @param string : $string ， 专业的数字连接字符串
* @param object : $MajorTypeModel , 专业类型模型
* @return 
* 致远<george.zou@gaodun.cn> ('<^>') 
*/
function majorNameList($string='',$MajorTypeModel=''){
    if(!$string){
        return '';
    }
    $where=explode(',',$string);
    if(empty($MajorTypeModel)){
        $MajorTypeModel = D("MajorType");
    }
    if(is_array($where)){
       $result = $MajorTypeModel->getMajorText($where); 
    }
    if(is_array($result)){
        foreach($result as $v){
            $title[] = $v['title'];
        }
        return !empty($title) ? $title :'';        
    }else{
        return '';
    }
}

function majorNamePost($string){
    $data = majorNameList($string);
    if(!empty($data)){
        $string = join('专业、',$data);
        return $string.'专业';        
    }else{
        return '';
    }
}


function majorNameListLi($string,&$MajorTypeModel){
    $where=explode(',',$string);
    if(empty($MajorTypeModel)){
        $MajorTypeModel = D("MajorType");
    }
    $result = $MajorTypeModel->getMajorText($where);
    if(in_array(0,$where)){
        $title[0] = '不限';
    }
    foreach($result as $k=>$v){
        $title[$v['pkid']] = $v['title'];
    }
    return !empty($title) ? $title :'';
}

function majorNameString($string){
    $result = majorNameList($string);
    $major_title = '不限';
    if(!empty($result) && is_array($result)){
        $major_title = implode('、',$result);
    }
    return $major_title;
}
//列表多专业
function majorNameOther($string,&$MajorTypeModel = null){
    if(empty($MajorTypeModel)) {
        $MajorTypeModel = D("MajorType");
    }
    if(strpos($string,',') !== FALSE){
        $major_title = '多专业';
    }elseif($string > 0){
        $info = $MajorTypeModel->getMajorTypeById($string);
        $major_title = !empty($info['title']) ? $info['title'] : '专业不限';
    }else{
        $major_title = '专业不限';
    }
    return $major_title;
}

/*
 * 数据统计
 * @param $action_id 行为log_id $account 账户信息
 */
function actionLogAdd($action_id,$account){
    $data['student_id'] = 0;
    $data['enterprise_id'] = 0;
    $type_list = actionTypeList();
    if($account['account_type'] == 1){
        $data['enterprise_id'] = $account['enterprise_id'];
    }else{
        $data['student_id'] = $account['student_id'];
    }
    $actionLogModel = M('ActionLog');
    $data['action_id'] = $action_id;
    $data['content'] = $type_list[$action_id];
    $data['create_time'] = date('Y-m-d H:i:s',NOW_TIME);
    $actionLogModel->data($data)->add();
}

function actionTypeList(){
    $result = M('ActionType')->getField('pkid,name');
    return $result;
}


/*
 * 设备的判断
 */
function isMobile(){
    if(defined(ROOT_PATH)){
        require_once ROOT_PATH . '/ThinkPHP/Library/Vendor/Mobile_Detect.php';
    }else{
        vendor('Mobile_Detect','','.php');
    }
    $detect = new Mobile_Detect();
    $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
    if($deviceType == 'tablet' || $deviceType == 'phone'){
        return true;
    }else{
        return false;
    }
}

/*
 * 缓存处理
 * @param string $name ModelName string $type (set,get,rm) array $data int $pkid
 * @author allen
 */
function memcache(&$memcached,$name,$pkid,$type = 'set',$data = array()){
    if(!$memcached){
        return '';
    }
    $memcache = $memcached;
    if(!is_numeric($pkid)){
        $pkid = enInt($pkid);
    }
    $memcacheId = md5($name . '_' . $pkid);
    switch($type){
        case 'set':
            $result = $memcache->set($memcacheId,$data);
            break;
        case 'get':
            $result = $memcache->get($memcacheId);
            break;
        case 'rm':
            $result = $memcache->rm($memcacheId);
            break;
    }
    return $result;
}

/*
* @function 
* @param 刷新时间值
* 致远<george.zou@gaodun.cn> ('<^>') 
*/
function reflushDay($datetime,$day=7){
    $datetime_int = strtotime($datetime);
    if($datetime_int){
        $number = dayDiff($datetime,date('Y-m-d H:i:s',NOW_TIME));
        if($number > 0){
            return TRUE;
        }else{
            return FALSE;
        }
    }else{
        return '';
    }
}

//日期之间的天数差
function dayDiff($data_start,$data_end){
    $datetime1 = date_create($data_start);
    $datetime2 = date_create($data_end);
    $interval = date_diff($datetime1, $datetime2);
    $diff = $interval->format('%a');
    $diff = intval($diff);
    return $diff;
}

//生成随机邀请码
function randCode($len=6){
    do{
        $num .= mt_rand(1,9);
        for($j=0;$j<$len-1;$j++){
            $num .= mt_rand(0,9);
        }
        if(strlen($num)<$len){
            $bool = true;
        }
    }while($bool);
    return $num;
}

/*
* @function 生成二维码png图片
* @param $data:string url||text url超链接或文本
* @param $fileName:string 二维码图片保存基本路径/Public/qr/
* @param $fileName:string('') 自定义路径
* @param $level:string('L') 容错级别默认L；$size:int(4) 点的大小默认4
* @return $path:string 二维码图片保存路径
* 致远<george.zou@gaodun.cn> ('<^>') 
*/
function qrCode($data,$fileName,$pathMy='',$level='L',$size=4){
    $path = './Public/qr/';
    if($pathMy){
        $path .= $pathMy.'/';
        $pathMy = $pathMy.'/';
    }
    dir_create($path);
    vendor('phpqrcode','','.php');
    $path .= $fileName.'.png';
    \QRcode::png($data,$path, $level, $size,2);
    if($fileName){
        return C('APP_URL').'/Public/qr/'.$pathMy.$fileName.'.png';
    }
}

/*
 * 获取省列表
 */
function getProvinceList(){
    return D('Regions')->parentCity();
}

/*
 * 获取市列表
 */
function getCityList(){
    $result = M('Regions')->field('parent_id,region_id,region_name,region_name_en')
        ->where(array('region_type' => 2,'region_name' => array('neq','县辖区'),'region_id' => array('lt',700000)))
        ->select();
    if(is_array($result)){
        foreach($result as $key => $value){
            $lists[$value['parent_id']][] = array('region_id' => $value['region_id'],'region_name'
            => $value['region_name'],'region_name_en' => $value['region_name_en']);
        }
        if(isMobile()){
            $lists[700000] = array(
                array('region_id' => 710000,'region_name' => '台湾','region_name_en' => 'Taiwan'),
                array('region_id' => 810000,'region_name' => '香港','region_name_en' => 'HongKong'),
                array('region_id' => 820000,'region_name' => '澳门','region_name_en' => 'Macau'),
                array('region_id' => 990100,'region_name' => '海外','region_name_en' => 'Abroad')
            );
        }
    }
    return $lists;
}


//获取未使用、有效的邀请码
function getInvite($model=''){
    //do{
    if(!$model){
        $model = M('invite_code');
    }
    $data = $model->field(array('code'))->order('pkid asc')->where(array('status'=>1))->find();
    //$where = "self_code=".$data['code'].' or inviter_code='.$data['code'];
    //$bool = M('enterprise')->where($where)->select();
    //}while($bool);
    return $data['code'];
}

//更新邀请码为已使用
function upInvite($code="",$model=''){
    if(!$model){
        $model = M('invite_code');
    }
    if($code){
        $data['status'] = 2;
        $data['use_time'] = dateTime();
        $bool = $model->data($data)->where(array('code'=>$code))->save();
        return $bool;
    }
}

//获取年份信息
function getYearList($key = ''){
    for($i=1980;$i<=2040;$i++){
        $arr[$i] = $i . '年';
    }
    if(!empty($key)){
        return $arr[$key];
    }else{
        return $arr;
    }
}

//获取月份
function getMonthList($key = ''){
    for($i=1;$i<=12;$i++){
        $arr[$i] = $i . '月';
    }
    if(!empty($key)){
        return $arr[$key];
    }else{
        return $arr;
    }
}

//获取日期
function getDayList($key = ''){
    for($i=1;$i<=31;$i++){
        $arr[$i] = $i . '日';
    }
    if(!empty($key)){
        return $arr[$key];
    }else{
        return $arr;
    }
}

//定义企业邀请规则分值
function getRuleScore($type='',$key=''){
    $rule = array(
        'regin' => array('self'=>5,'invite'=>10),
        'info' => array('self'=>15,'invite'=>30),
        'join' => array('self'=>25,'invite'=>50),
        'post' => array('self'=>35,'invite'=>70),
    );
    if($type&&$key){
        return $rule[$type][$key];
    }else{
        return '';
    }
}

//定义学生邀请规则分值
function getRuleScoreBar($type='',$key=''){
    $rule = array(
        'regin' => array('self'=>15,'invite'=>30),
        'resume' => array('self'=>25,'invite'=>50),
        'join' => array('self'=>5,'invite'=>10),
        'interview' => array('self'=>35,'invite'=>70),
    );
    if($type&&$key){
        return $rule[$type][$key];
    }else{
        return '';
    }
}



//访问来源,0:本站,1:微信2:微博3:EDM,4:高顿网站5:其他渠道
function fromVal(){
    $from = isset($_GET['from']) ? intval(I('from')) : 0;
    if ($from >= 0 && $from <= 5) {
        D('Account')->cookieActivityFrom($from);
    }
}
//获取简历被浏览次数
function resumeViewNum($student_id){
    if(empty($student_id)){
        $student_id = session('account.student_id');
    }
    $result = M('ResumeView')->where(array('student_id' => $student_id))->count();
    return intval($result);
}

//获取个人头像
function headpic($id){
    $account_type = session('account.account_type');
    if($account_type == 1){
        $id = !empty($id) ? $id : session('account.enterprise_id');
        $result = M('Enterprise')->field('logo')->where(array('pkid' => $id))->find();
        $headpic = $result['logo'];
    }else{
        $id = !empty($id) ? $id : session('account.student_id');
        $headpic = D('Student')->studentAvatar($id);
    }
    $headpic = !empty($headpic) ? $headpic : '/Public/static/images/new_nophoto_student.png';
    return $headpic;
}

//名字过长省略显示处理
function name2ellipsis($name,$count = 14){
    if(!empty($name)){
        if(mb_strlen($name,'utf8') > $count){
            $name = mb_substr($name,0,$count - 1,'UTF-8') . '...';
        }
    }
    return $name;
}

/*
* @function 简历新增删除的时候，进行百分比的变化
* @param object : $model , 简历模块相应的模型
* @param string : $type , {add:新增操作得分,del:删除操作减分}
* @param int : $lang , {1:中文简历,2:英文简历}
* @return 
*/
function perchange(&$model,$type,$lang=1){
    if(empty($model)){
        return false;
    }
    $id = session('account.student_id');
    $int = $model->where(array('student_id' => $id,'is_delete' => 2,'language_type'=>$lang))->count();
    if($type == 'add'){
        if($int > 1){
            return false;
        }
    }else{
        if(!empty($int)){
            return false;
        }
    }

    //中英文更新百分比区分字段
    $lang_arr = array(1 => 'complete_rate',2 => 'complete_rate_en');
    $type_arr = array(
        1 => array('add' => 'complete_rate+11','del' => 'complete_rate-11'),
        2 => array('add' => 'complete_rate_en+11','del' => 'complete_rate_en-11'),
    );
    $data = array(
        'pkid' => $id,
        $lang_arr[$lang] => array('exp',$type_arr[$lang][$type])
    );
    M('Student')->save($data);
    // 2015-09-01 11:01:06  星期二 获取学生简历完成值，大于60更新积分
    resumeRateScore();
}

//完成简历值大于60，则更新我和邀请方的积分
function resumeRateScore(){
    D('StudentScore')->checkInviteResumeRate(session('account.student_id'));
}

//中英文混合截取
function cutSting($str, $len,$dot='...',$charset="utf-8")
{
    //如果截取长度小于等于0，则返回空
    if( !is_numeric($len) or $len <= 0 or !$str)
    {
        return "";
    }

    //如果截取长度大于总字符串长度，则直接返回当前字符串
    $sLen = strlen($str);
    if( $len >= $sLen )
    {
        return $str;
    }

    //判断使用什么编码，默认为utf-8
    if ( strtolower($charset) == "utf-8" )
    {
        $len_step = 3; //如果是utf-8编码，则中文字符长度为3  
    }else{
        $len_step = 2; //如果是gb2312或big5编码，则中文字符长度为2
    }

    //执行截取操作
    $len_i = 0;
    //初始化计数当前已截取的字符串个数，此值为字符串的个数值（非字节数）
    $substr_len = 0; //初始化应该要截取的总字节数

    for( $i=0; $i < $sLen; $i++ )
    {
        if ( $len_i >= $len ) break; //总截取$len个字符串后，停止循环
        //判断，如果是中文字符串，则当前总字节数加上相应编码的中文字符长度
        if( ord(substr($str,$i,1)) > 0xa0 )
        {
            $i += $len_step - 1;
            $substr_len += $len_step;
        }else{ //否则，为英文字符，加1个字节
            $substr_len ++;
        }
        $len_i ++;
    }
    $result_str = substr($str,0,$substr_len );
    $strlen = strlen($result_str);
    if($strlen<$sLen){
        return $result_str.$dot;
    }else{
        return $result_str;
    }
}

//处理学生排行榜数字
function stuRankNum($num){
    $num = intval($num);
    $num = $num+1;
    $len = strlen($num);
    if($len<2){
        $num = '0'.$num;
    }
    return $num;
}

//处理学生排行榜邮箱
function string_cutemail($string){
    $data = explode('@',$string);
    return string_cute($data[0],16,'');
}

// 获取指定日期所在星期的开始时间与结束时间的时间戳
function getWeekTime($day=''){
    $day = $day ? $day : date('Y-m-d');
    $lastday=date('Y-m-d 23:59:59',strtotime("$day Sunday"));
    $firstday=date('Y-m-d 00:00:00',strtotime("$lastday -6 days"));
    $date['begin_time'] = $firstday;
    $date['end_time'] = $lastday;
    return  $date;
}


// 获取指定日期所在月份的第一天和最后一天
function getMonthTime($day=''){
    $day = $day ? $day : date('Y-m-d');
    $firstday = date('Y-m-01 00:00:00', strtotime($day));
    $lastday = date('Y-m-d 23:59:59', strtotime(date('Y-m-01', strtotime($day)) . ' +1 month -1 day'));
    $date['begin_day'] = $firstday;
    $date['end_day'] = $lastday;
    return  $date;
}


//建立预览截取邮箱
function invite_cutemail($string){
    $data = explode('@',$string);
    return '<span class=invite_f_span>'.str_repeat('*',10).'</span><span class=invite_s_span>@'.$data[1].'</span>';
}

//手机号四为*
function invite_cutemobile($string){
    return '<span class=invite_s_span>'.string_cute($string,8,'').'</span><span class=invite_f_span>'.str_repeat('*',4).'</span>';
}

//推送记录大于等于的数字
function trace_number($key = ''){
    $i=20;
    while($i<=45){
        $arr[$i] = $i;
        $i = $i+5;
    }
    if(empty($key)){
        return $arr;
    }else{
        return $arr[$key];
    }
}

//推送间隔
function trace_hours($key = ''){
    $arr = array(0 => '实时发送',24 => '每天发送',72 => '每隔3天发送');
    if(empty($key)){
        return $arr;
    }
    return $arr[$key];
}

function dateErr($date){
    $rule = '0000-00-00';
    if(empty($date)){
        return '';
    }
    if($date == $rule){
        return '';
    }
    return $date;
}

/*
* @function 投递简历类型
* @param int:$id {1:'中文',2:'英文',3:'中英文'}
* @return string
* 致远<george.zou@gaodun.cn> ('<^>') 
*/
function resumePostType($id=NULL){
    if($id){
        switch ($id) {
            case 1:
                return '中文';
                break;
            case 2:
                return '英文';
                break;
            case 3:
                return '中/英文';
                break;
            case 4:
                return '附件简历';
                break;
        }        
    }
}

/*
 * 清除多余换行符
 * @bool $type 是否是编辑保存模式
 */
function delMoreBr($data,$type = FALSE){
    $data = nl2br($data);
    $arr = explode("<br />",$data);
    foreach($arr as $key => $value){
        $new = trim($value);
        if(empty($new) || $new == ''){
            unset($arr[$key]);
        }
    }
    if($type == TRUE){
        $new_arr = implode("",$arr);
    }else{
        $new_arr = implode('<br>',$arr);
    }
    return $new_arr;
}

/*
* @function 去除textarea多余换行符，仅保留一个换行符
* @param bool:$type ,默认FALSE,输出html时转化换行，TRUE表示输出textarea时转化换行
* 致远<george.zou@gaodun.cn> ('<^>') 
*/
function textareaFormat($string,$type=FALSE){
    if(!$type){
        return preg_replace('/\s\s+/',"<br>",$string);
    }
    if($type){
        return preg_replace('/\s\s+/',"\n",$string);
    }
}

/*
* @function 判断当前学生头像是否存在，如果否返回默认小二黑
* @param 学生头像路径
* 致远<george.zou@gaodun.cn> ('<^>') 
*/
function studentAvatar($image){
    if(file_get_contents($image)){
        return $image;
    }else{
        return C('APP_URL').'/Public/static/images/new_nophoto_student.png';
    }
}

//截取邮箱，隐藏@之前字符
function cuteEmail($string=''){
    if(! $string)
        return '';
    $data = explode('@',$string);
    return str_repeat('*',10).$data[1];
}

//截取手机，隐藏后4位字符
function cuteMobile($string=''){
    if(! $string)
        return '';
    return string_cute($string,8,'').str_repeat('*',4);
}

//判断年份的合法性
function checkRightYear($year=''){
    return preg_match('/\d{4}/i',$year);
}

//处理链接
function pregHttp($url=''){
    $url = strtolower(trim($url));
    if(! preg_match('/^(http|https)/i',$url))
            $url = 'http://'.$url;
    return $url;
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

//职位详情url
function postUrl($id=''){
    return C('FRONT_URL').'/Post/info/id/'.enInt($id);
}

//企业详情url
function enterpriseUrl($id=''){
    return C('FRONT_URL').'/Enterprise/info/id/'.enInt($id);
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