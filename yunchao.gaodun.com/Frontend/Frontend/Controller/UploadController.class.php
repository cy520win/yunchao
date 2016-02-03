<?php
/**
 * Created by PhpStorm.
 * User: allen
 * Date: 2015/5/15
 * Time: 16:23
 */

namespace Frontend\Controller;
use Think\Controller;
use Think\Upload as Upload;
class UploadController extends Controller{

    protected $upload;
    public $jpeg_quality = 100;

    public function _initialize(){
        $this->upload = new Upload();
    }

    //学生、企业个人中心上传头像
    public function upload(){
        if(!empty($_FILES)){
            $upload = $this->upload;
            $upload->maxSize    = 3145728;
            $upload->exts       = array('jpg', 'jpeg', 'gif', 'png');
            $upload->rootPath   = 'Public/upload/headpic_omg/';
            dir_create($upload->rootPath);  //根据日期创建文件夹
            $info = $upload->uploadOne($_FILES['headpic']);
            if(!$info){
                exit(json_encode(array('status' => 'fail','msg' => $upload->getError())));
            }else{
                $path = '/Public/upload/headpic_omg/' . $info['savepath'].$info['savename'];
                list($width, $height) = getimagesize('Public/upload/headpic_omg/' . $info['savepath'].$info['savename']);
                $width = $width < 260 ? 260 : $width;
                exit(json_encode(array('status' => 'success','path' => $path, 'width' => $width, 'height' => $height,
                    'msg' => C('L_NORMAL_SUCCESS'))));
            }
        }else{
            exit(json_encode(array('status' => 'fail','msg' => C('L_NORMAL_FAIL'))));
        }
    }

    //意见反馈上传图片
    public function uploadAdvice(){
        if(!empty($_FILES)){
            $upload = $this->upload;
            $upload->maxSize    = 3145728;
            $upload->exts       = array('jpg', 'jpeg', 'bmp', 'png');
            $upload->rootPath   = 'Public/upload/advice_img/';
            $bool_ext = $upload->checkExtBool($_FILES['adviceimg']);
            if(!$bool_ext){
                $_FILES=null;
                $str = '请上传jpeg,jpg,png或bmp格式图片';
                exit(json_encode(array('status' => 'fail','msg' =>$str,'code'=>4)));
            }
            dir_create($upload->rootPath);  //根据日期创建文件夹
            $info = $upload->uploadOne($_FILES['adviceimg']);
            if(!$info){
                exit(json_encode(array('status' => 'fail','msg' => $upload->getError(),'code'=>1)));
            }else{
                $path = '/Public/upload/advice_img/' . $info['savepath'].$info['savename'];
                exit(json_encode(array('status' => 'success','path' => $path, 'msg' => C('L_NORMAL_SUCCESS'),'code'=>3)));
            }
        }else{
            exit(json_encode(array('status' => 'fail','msg' => C('L_NORMAL_FAIL'),'code'=>2)));
        }       
    }

    //文件上传
    public function uploadResume(){
        if(!empty($_FILES)){
            $resume_name = $_FILES['resume']['name'];
            $upload = $this->upload;
            $upload->maxSize    = 3145728;
            $upload->exts       = array('pdf', 'doc', 'docx');
            $upload->rootPath   = 'Public/upload/resume_file/';
            dir_create($upload->rootPath);  //根据日期创建文件夹
            $info = $upload->uploadOne($_FILES['resume']);
            if(!$info){
                exit(json_encode(array('status' => 'fail','msg' => $upload->getError())));
            }else{
                $file_path = addslashes(C('APP_URL') . '/Public/upload/resume_file/' . $info['savepath'].$resume_name);
                //复制一份文件，作为与简历名称相同的文件，保证下载名字的正确成功
                $true_path = C('APP_URL') . '/Public/upload/resume_file/' . $info['savepath'].$info['savename'];
                $new_path = 'Public/upload/resume_file/' . $info['savepath'].$resume_name; //中文名称
                copy('Public/upload/resume_file/' . $info['savepath'].$info['savename'],$new_path);
                $data = array(
                    'resume_name' => $_FILES['resume']['name'],
                    'file_path' => $file_path,
                    'true_path' => $true_path
                );
                A('Resume')->resumeExist($data);
                exit(json_encode(array('status' => 'success','info' => $data,'msg' => C('L_NORMAL_SUCCESS'))));
            }
        }else{
            exit(json_encode(array('status' => 'fail','msg' => C('L_NORMAL_FAIL'))));
        }
    }

    //反馈意见图片删除触发按钮
    public function deleteImg(){
        $img = I('post.img');
        if(!empty($img)){
            $img = substr($img,1);
            if(file_exists($img)){
                unlink($img);
            }
        }
    }

    //图片裁剪
    public function crop(){
        $x = abs(I('post.x'));
        $y = abs(I('post.y'));
        $w = abs(I('post.w'));
        $h = abs(I('post.h'));
        $tw = abs(I('post.tw'));
        $th = abs(I('post.th'));
        $model = I('post.model');
        if(in_array(0, array($w, $h, $tw, $th)))
            exit("Invalid params");
        $src = I('post.src');
        $dir = 'Public/upload/headpic_crop/' . date('Y-m-d',NOW_TIME) . '/';  //创建裁切后图片的文件夹
        dir_create($dir);
        $src_arr = explode('/',$src);
        $newpath = $dir . $src_arr[5];           //裁剪后的文件
        $path = substr($src,1,strlen($src) - 1); //原始文件
        $type = explode('.', $src_arr[5]);
        $ext = $type[1];
        switch($ext){
            case 'gif': $source_img = imagecreatefromgif($path); break;
            case 'png': $source_img = imagecreatefrompng($path); break;
            default: $source_img = imagecreatefromjpeg($path); break;
        }

        // 裁剪图片
        $new_img = imagecreatetruecolor($tw, $th);
        if($ext == 'png'){
            imagealphablending($new_img, false);
            imagesavealpha($new_img, true);
        }
        imagecopyresampled($new_img, $source_img, 0, 0, $x, $y, $tw, $th, $w, $h);

        // 生成图片
        switch($ext){
            case 'gif': imagegif($new_img, $newpath, $this->jpeg_quality); break;
            case 'png': imagepng($new_img, $newpath, (int)(($this->jpeg_quality-1)/10));
                break;
            default: imagejpeg($new_img, $newpath, $this->jpeg_quality); break;
        }

        if($model=='headpic'){//保存头像
            D('Student')->uploadAvatar(C('APP_URL').'/'.$newpath);
        }
        echo json_encode(array('path' => $newpath));
    }

    //图片裁剪
    public function new_crop(){
        $x = abs(I('post.x'));
        $y = I('post.y');
        $w = abs(I('post.w'));
        $h = abs(I('post.h'));
        $tw = abs(I('post.tw'));
        $th = abs(I('post.th'));
        if(in_array(0, array($w, $h, $tw, $th)))
            exit("Invalid params");
        $src = I('post.src');
        $dir = 'Public/upload/headpic_crop/' . date('Y-m-d',NOW_TIME) . '/';  //创建裁切后图片的文件夹
        dir_create($dir);
        $src_arr = explode('/',$src);
        $newpath = $dir . $src_arr[5];           //裁剪后的文件
        $path = substr($src,1,strlen($src) - 1); //原始文件
        $type = explode('.', $src_arr[5]);
        $ext = $type[1];
        $path = $this->resize($path,$w,$ext);  //获取放大后或缩小后的图片
        switch($ext){
            case 'gif': $source_img = imagecreatefromgif($path); break;
            case 'png': $source_img = imagecreatefrompng($path); break;
            default: $source_img = imagecreatefromjpeg($path); break;
        }

        // 裁剪图片
        list($src_img_w,$src_img_h) = getimagesize($path);
        if($tw > $src_img_w) $tw = $src_img_w;
        if($th > $src_img_h) $th = $src_img_h;
        if($x < 0) $x = 0;
        if($y < 0) $y = 0;
        $new_img = imagecreatetruecolor($tw,$th);
        imagecolorallocate($new_img,255,255,255);
        if($ext == 'png'){
            imagealphablending($new_img, false);
            imagesavealpha($new_img, true);
        }
        imagecopy($new_img, $source_img, 0, 0, $x, $y, $tw, $th);

        // 生成图片
        switch($ext){
            case 'gif': imagegif($new_img, $newpath, $this->jpeg_quality); break;
            case 'png': imagepng($new_img, $newpath, (int)(($this->jpeg_quality-1)/10));
                break;
            default: imagejpeg($new_img, $newpath, $this->jpeg_quality); break;
        }
        unlink($path); //删除临时图片
        echo json_encode(array('path' => $newpath));
    }

    /*
     * @function 图片放大或缩小
     */
    public function resize($path,$width,$ext){
        switch($ext){
            case 'gif': $source_img = imagecreatefromgif($path); break;
            case 'png': $source_img = imagecreatefrompng($path); break;
            default: $source_img = imagecreatefromjpeg($path); break;
        }
        list($src_img_w,$src_img_h) = getimagesize($path);
        $height = round($width * $src_img_h / $src_img_w);
        $tmp_img = imagecreatetruecolor($width,$height);
        if($ext == 'png'){
            imagealphablending($tmp_img, false);
            imagesavealpha($tmp_img, true);
        }
        imagecopyresampled($tmp_img, $source_img, 0, 0, 0, 0, $width, $height, $src_img_w, $src_img_h);
        $mypath = 'Public/upload/headpic_crop/' . date('Y-m-d',NOW_TIME) . '/' . rand(0,1000) . '.' . $ext;
        switch($ext){
            case 'gif': imagegif($tmp_img, $mypath, $this->jpeg_quality); break;
            case 'png': imagepng($tmp_img, $mypath, (int)(($this->jpeg_quality-1)/10));
                break;
            default: imagejpeg($tmp_img, $mypath, $this->jpeg_quality); break;
        }
        return $mypath;
    }

    /*
    * @function 生成城市数组
    * @param 
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 最后更新时间
    */
    public function cityJsJson(){

        //格式 citySelector.pc[0] = new Array("河北", "石家庄|邯郸|邢台|保定|张家口|承德|廊坊|唐山|秦皇岛|沧州|衡水");
        $Regions =D('Regions');
        $parent = $Regions->parentCity();
        foreach($parent as $k=>$v){
             $child = $Regions->childCity($v['region_id']);
             foreach($child as $v2){
                $child_new[] = $v2['region_name_en'];
             }
             $child_str = join('|',$child_new);
           $citySelector[$k] = 'citySelector.pc['.$k.']=new Array("'.$v['region_name_en'].'","'.$child_str.'");';
           unset($child);
           unset($child_new);
           unset($child_str);
        }
        foreach($citySelector as $value){
            echo $value;
            echo '<br>';
        }
    }

    public function cityJsNewJson(){
        $Regions =D('Regions');
        $parent = $Regions->parentCity();
        foreach($parent as $k=>$v){
            $child = $Regions->childCity($v['region_id']);
            foreach($child as $v2){
                $child_new[] = '[' . $v2['region_id'] .','. $v2['region_name'] .']';
            }
            $child_str = join('|',$child_new);
            $citySelector[$k] = 'citySelector.pc['.$k.']=new Array("'. '[' . $v['region_id'] . ',' .'' .$v['region_name']. ']' . '","' .$child_str.'");';
            print_r($citySelector);
            unset($child);
            unset($child_new);
            unset($child_str);
        }
    }

    /*
     * 获取城市ID
     */
    public function getCityId(){
        $Regions = M('Regions');
        $region = I('post.city');
        $result = $Regions->field('region_id')->where(array('region_name' => array('like',"%" . $region . "%"), 'region_type' => 2))->find();
        if($region == '台湾省' || $region == '香港' || $region == '澳门'){
            $result = $Regions->field('region_id')
                ->where(array('region_name' => array('like',"%" . $region . "%")))
                ->find();
        }
        echo json_encode(array('cityId' => $result['region_id']));
    }
}