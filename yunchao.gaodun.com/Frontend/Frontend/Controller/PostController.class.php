<?php
/**
 * Created by PhpStorm.
 * User: allen
 * Date: 2015/5/25
 * Time: 11:10
 */
namespace Frontend\Controller;
use Vendor\PageAdmin as PageAdmin;
class PostController extends CommonController{

    protected $PostModel;
    protected $ResumeModel;
    protected $ResumePostModel;
    protected $TagRelationModel;    //标签库模型
    protected $page_number = 10;
    protected $date_time = '0000-00-00';

    public function _initialize(){
        parent::_initialize();
        // $this->PostModel = D('Post');
        // $this->ResumeModel = D('Resume');
        // $this->ResumePostModel = D('ResumePost');
        // $this->TagRelationModel = D('TagRelation');
    }

    public function index(){
        redirect('/post/lists');
    }

    /*
     * 列表页兼首页
     */
    public function lists(){
        // $param = I('get.');
        // $where = array();
        // $where['create_time'] = array('neq','0000-00-00 00:00:00');

        // // -------------------------------------------- 致远<george.zou@gaodun.cn>
        // if(isset($_GET['tag']) && I('tag')=='hot'){
        //     $where['post.is_hot'] = 1;
        // }
        // // --------------------------------------------
        // $search_data = array();
        // if(!empty($param['keyword']) && !empty($param['key'])){
        //     $param['keyword'] = urldecode($param['keyword']);
        //     switch($param['key']){
        //         case 1 :
        //             $this->getEnterpriseIds($param['keyword']) ? $where['post.enterprise_id'] = array('in', $this->getEnterpriseIds($param['keyword'])) : $search_data = 1;
        //             break;
        //         case 2 :
        //             $where['post.title'] = array('like',"%".$param['keyword']."%");
        //             break;
        //         case 3 :
        //             $cityIds = $this->getRegionIds($param['keyword']);
        //             if(!empty($cityIds)){
        //                 $where['_string'] = 'post.province_id in (' . $cityIds . ') OR post.city_id in (' . $cityIds . ')';
        //             }else{
        //                 $search_data = 1;
        //             }
        //             break;
        //         default:
        //             $search_data = array();
        //             break;
        //     }
        // }
        // if(!empty($param['ct'])){
        //     $param['ct'] = $param['ct'];
        // }
        // isset($param['ct']) ? $where['city_id'] = $param['ct'] : ''; //城市
        // isset($param['wa']) ? $where['week_available'] = $param['wa'] : '';    //每周可工作时间
        // isset($param['ci']) ? $where['category_id'] = $param['ci'] : '';       //岗位类型
        // isset($param['ed']) ? $param['ed'] == 5 ? $where['education'] = 5 : $where['education'] = array('in',array($param['ed'],5)) : '';//学历
        // isset($param['kp']) ? $where['keep_on'] = $param['kp'] : ''; //是否留用
        // isset($param['gy']) ? $where['graduate_year'] = array('like','%'.$param['gy'].'%') : ''; // 毕业年级
        // $postTotal = $this->PostModel->getPostTotal($where);//列表总数量
        // $page = isset($_GET['p']) ? intval(htmlspecialchars(strip_tags($_GET['p']))) : '';//获取url的分页参数
        // $page = max(1,$page);
        // $page = min($page,ceil($postTotal/$this->page_number)); // 获取合法的分页数
        // $post = $this->PostModel->getPostList($where,$page,$this->page_number);
        // $map = $param ? $param : array();
        // $Page = new PageAdmin($postTotal,$this->page_number,$map);

        // $hotCityList = M('HotCity')->where(array('is_delete' => 2))->order('order_num desc')
        //     ->limit(5)->getField('region_id,region_name');

        // if(!empty($param['ct'])){
        //     $result = D('Regions')->field('region_name')->where(array('region_id' => $param['ct']))->find();
        //     $param['ct'] = $result['region_name'];
        // }

        // if(empty($param['ct']) && empty($param['wa']) && empty($param['ci']) && empty($param['ed']) && empty($param['kp']) && empty($param['gy'])){
        //     $param['keep'] = 'hide'; //已选条件是否显示|0为不显示
        //     if($search_data == 1){
        //         $post = array();
        //     }
        // }

        // $this->assign('page',$Page->show());
        // $this->assign('param',$param);
        // $this->assign('postList',$post);
        // $this->assign('hotCityList',$hotCityList);
        // $info_lists = $this->infoList();
        // krsort($info_lists['graduateList']);
        // $this->assign('list',$info_lists);
        // if(!empty($_GET['p'])){
        //     $this->assign('p',$_GET['p']);
        // }

        $this->display('lists');
    }

    /*
     * 详情页
     */
    public function info(){
     //    if(I('get.id') == 'undefined'){
     //        redirect('/');
     //    }
     //    $id = I('get.id');
     //    if(!is_numeric($id)){
     //        $id = enInt($id);
     //    }
     //    if(!is_numeric($id)){
     //        redirect('/');
     //    }
     //    /*福利标签展示 2015-12-16*/
     //    $relation_where = array('model_id' => $id,'tag_relation.is_delete' => 2,'model_type' => 1);
     //    $relation_lists = $this->TagRelationModel->returnTagRelationLists($relation_where);
     //    $this->assign('relation_lists',$relation_lists);

     //    //简历类型数组
     //    $default_type = array(
     //        1 => array('pkid' => 1,'name' => '中文简历','url' => '/Student/like'),
     //        3 => array('pkid' => 3,'name' => '中/英文简历','url' => '/EnStudent/enlike'),
     //        4 => array('pkid' => 4,'name' => '附件简历','url' => '/PageOffice/like'),
     //    );

     //    $info = $this->PostModel->getPostInfoById($id);
     //    /*---------------2015-12-08--------------------*/
     //    $info['post_qr'] = $this->postQr($id);
     //    $info['collect_p_id'] = I('get.id');
     //    $info['collect'] = $this->collectRight($id);
     //    if(strtotime($info['receive_start']) && !strtotime($info['receive_finish'])){
     //        $info['receive_text'] = $info['receive_start'].'以后';
     //    }
     //    if(!strtotime($info['receive_start']) && strtotime($info['receive_finish'])){
     //        $info['receive_text'] = date('Y-m-d',NOW_TIME).'至'.$info['receive_finish'];
     //    }
     //    if(strtotime($info['receive_start']) && strtotime($info['receive_finish'])){
     //        $info['receive_text'] = $info['receive_start'].'至'.$info['receive_finish'];
     //    }
     //    if(!strtotime($info['receive_start']) && !strtotime($info['receive_finish'])){
     //        $info['receive_text'] = '任意时间';
     //    }
     //    if(strtotime($info['attend_start']) && !strtotime($info['attend_finish'])){
     //        $info['attend_text'] = $info['attend_start'].'以后';
     //    }
     //    if(!strtotime($info['attend_start']) && strtotime($info['attend_finish'])){
     //        $info['attend_text'] = date('Y-m-d',NOW_TIME).'至'.$info['attend_finish'];
     //    }
     //    if(strtotime($info['attend_start']) && strtotime($info['attend_finish'])){
     //        $info['attend_text'] = $info['attend_start'].'至'.$info['attend_finish'];
     //    }
     //    if(!strtotime($info['attend_start']) && !strtotime($info['attend_finish'])){
     //        $info['attend_text'] = '任意时间';
     //    }
     //    /*---------2015-07-28 18:24:26  星期二---------*/
     //    $city_bool = stripos($info['address'],$info['region_name']) ;
     //    $info['address_map'] = $city_bool===false ? trim($info['region_name']).trim($info['address']) : $info['address'];
     //    /*-----------2015-07-28 18:33:32  星期二-------*/ 
     //    $enterprise_id = $info['enterprise_id'];
     //    $enInfo = D('Enterprise')->getInfoByWhere(array('enterprise.pkid' => $info['enterprise_id']));
     //    $enInfo['logo'] = imageExist($enInfo['logo']);
     //    $sameWhere = array(
     //        'post.category_id' => $info['category_id'],
     //        'post.enterprise_id' => array('neq',$info['enterprise_id']),
     //        'post.city_id' => $info['city_id'],
     //        'post.is_delete' => 2,
     //        'post.status' => 1,
     //    );
     //    $sameList = $this->PostModel->getSamePostList($sameWhere);
     //    $info['graduate_year'] = !empty($info['graduate_year']) ? explode(',',$info['graduate_year']) : '';

     //    #判断是学生用户还是企业用户 --begin--
     //    $account = session('account');
     //    if(!empty($account)){
     //        if(!empty($account['student_id'])){
     //            $studentInfo = M('Student')->field('pkid,name,email_verify')->find($account['student_id']);
     //            $this->assign('studentInfo',$studentInfo);
     //            if($studentInfo['email_verify'] != 1){
     //                $postType = 6;   //邮箱未验证
     //            }else{
     //                $resume = $this->ResumeModel
     //                    ->where(array('student_id' => $account['student_id'],'resume_type' => array('neq',2)))
     //                    ->getField('resume_type,pkid,resume_name');
     //                if(empty($resume)){
     //                    $postType = 1;  //没有简历，需要创建（中英文都没有）
     //                }else{
     //                    /*判断填写的简历类型 -begin-*/
     //                    $default = $this->ResumeModel->resumeDefault();
     //                    if(empty($default)){
     //                        $this->ResumeModel
     //                            ->data(array('default_status' => 1))
     //                            ->where(array('student_id' => $account['student_id'],'resume_type' => 1))
     //                            ->save();
     //                        $default = $this->ResumeModel->resumeDefault();
     //                    }
                        
     //                    $this->assign('default',$default['resume_type']);
     //                    $this->assign('resumeDefault',$default_type[$default['resume_type']]);
     //                    /*-end-*/
     //                    $this->assign('defaultArr',$default_type);
     //                    $this->assign('resumeArr',$resume);
     //                    $this->assign('resumeInfo',$resume[$default['resume_type']]);
     //                    $check = M('ResumePost')->where(array('student_id' => $account['student_id'],'post_id' =>
     //                        $id,'status' => array('in',array(1,2,3,5))))->order('create_time desc')->find();
     //                    if(empty($check)){
     //                        /*2015-12-10  判断简历可投递时间是否与当前时间相匹配*/
     //                        if(!isMobile()){
     //                            if($info['receive_type'] == 2){
     //                                $now_time = NOW_TIME;
     //                                $receive_start =  empty($info['receive_start']) || $info['receive_start'] == $this->date_time ? NOW_TIME : strtotime($info['receive_start'].'00:00:01');
     //                                $receive_finish = empty($info['receive_finish']) || $info['receive_finish'] == $this->date_time ? NOW_TIME : strtotime($info['receive_finish'].'00:00:01');
     //                                if($receive_start > $now_time || $receive_finish < $now_time){
     //                                    $postType = 7;
     //                                }else{
     //                                    $postType = 2;
     //                                }
     //                            }else{
     //                                $postType = 2;  //未投递，可投递
     //                            }
     //                        }else{
     //                            $postType = 2;  //未投递，可投递
     //                        }

     //                    }else{
     //                        $postType = 3;  //已投递过，不可再投递
     //                    }
     //                }
     //            }
     //        }else{
     //            $postType = 4;   //企业用户，不存在投递与否
     //        }
     //    }else{
     //        $postType = 5;  //未登录状态
     //    }

     //    /*判断到岗时间是否与实习意向相匹配 2015-12-10*/
     //    if(!isMobile()){
     //        if($postType == 2){
     //            if($info['attend_type'] == 2) {
     //                $attend_start = empty($info['attend_start']) || $info['attend_start'] == $this->date_time ? NOW_TIME : strtotime($info['attend_start'].'23:59:59');
     //                $attend_finish = empty($info['attend_finish']) || $info['attend_finish'] == $this->date_time ? NOW_TIME : strtotime($info['attend_finish'].'23:59:59');
     //                $post_subscribe = M('PostSubscribe')->field('period_start,period_finish')->where(array('info_type' => 1, 'student_id' => session('account.student_id'),'status' => 1))->find();
     //                $period_start = empty($post_subscribe['period_start']) || $post_subscribe['period_start'] == $this->date_time ? '' : strtotime($post_subscribe['period_start'].'00:00:01');
     //                $period_finish = empty($post_subscribe['period_finish']) || $post_subscribe['period_finish'] == $this->date_time ? '' : strtotime($post_subscribe['period_finish'].'00:00:01');
     //                $attend = 'true';
     //                if(!empty($period_start) && !empty($period_finish)){
     //                    if($period_start > $attend_finish || $period_finish < $attend_start){
     //                        $attend = 'false';
     //                    }
     //                }
     //                if(!empty($period_start) && empty($period_finish)){
     //                    if($period_start > $attend_finish){
     //                        $attend = 'false';
     //                    }
     //                }
     //                $this->assign('attend',$attend);
     //            }
     //        }
     //    }

     //    #判断是学生用户还是企业用户  --end--

     //    #判断是否为登录企业的职位，筛选符合的学生简历 --begin--
     //    $rightSide = 0;
     //    if($enterprise_id == session('account.enterprise_id')){
     //        $studentList = $this->studentRecommend($id);
     //        $this->assign('studentList',$studentList);
     //        $rightSide = 1;   //本企业，则显示推荐的学生简历
     //    }
     //    #判断是否为登录企业的职位，筛选符合的学生简历  --end--

     //    if (parent::isStudentCenter()) {
     //        $mail_type = parent::checMailType(session('account.account_email'));
     //        $mail_url = $mail_type ? parent::mailAll($mail_type) : '';
     //        $this->assign('mailtype', $mail_url);
     //    }

     //    /*---------------2015-07-14 17:28:22  星期二-----------------*/
     //    $info['pc_graduate_year'] = graduateYearPost($info['graduate_year']);
     //    $info['pc_major_title'] = majorNamePost($info['major_wish']);
	    // $info['new_major_title'] = majorNameString($info['major_wish']);
     //    /*---------------2015-07-14 17:28:29  星期二-----------------*/

     //    // 修正企业主页链接
     //    $enInfo['website'] = $enInfo['website'] ? pregHttp($enInfo['website']) : '';

     //    //加载邮件发送成功的模板文件
     //    $this->assign('postType',$postType);
     //    $this->assign('rightSide',$rightSide);
     //    $this->assign('app_url',C('APP_URL'));
     //    $this->assign('info',$info);
     //    $this->assign('enInfo',$enInfo);
     //    $this->assign('sameList',$sameList);
     //    $this->assign('list',$this->infoList());
     //    if(isMobile()){
     //        $this->display('/MobilePost/info');
     //        exit;
     //    }
        $this->display('info');
    }

    /*
     * 简历投递
     */
    // public function resumePost(){
    //     $data = array(
    //         'student_id'    => session('account.student_id'),
    //         'enterprise_id' => enInt(I('post.enId')),
    //         'post_id'       => enInt(I('post.ptId')),
    //         'resume_id'     => enInt(I('post.rsId')),
    //         'send_type'     => 1,
    //         'status'        => 1,
    //         'create_time'   => date('Y-m-d H:i:s',NOW_TIME)
    //     );
    //     //简历ID为空判断
    //     if(empty($data['resume_id'])){
    //     	exit(json_encode(array('status' => 'fail','msg' => C('L_NORMAL_FAIL'))));
    //     }
    //     if(empty($data['student_id'])){
    //         redirect('/Account/logout');
    //         exit;
    //     }
    //     $check = $this->ResumePostModel->where(array('student_id' => $data['student_id'],'post_id' => $data['post_id'],
    //         'status' => 3))->order('create_time')
    //         ->find();
    //     if(!empty($check)){
    //         exit(json_encode(array('status' => 'success')));
    //     }
    //     $result = $this->ResumePostModel->data($data)->add();
    //     if($result !== false){
    //         /*新增转发的判断*/
    //         $receive_email = I('post.email');
    //         $default_id = I('post.defaultId');
    //         if(!empty($receive_email)){
    //             if($default_id != 4){
    //                 $this->resumeForward(intval($result),$receive_email);
    //             }
    //         }

    //         $mailData = $this->sendResumePostMail(array('enId' => $data['enterprise_id'],'ptId' => $data['post_id'], 'stId' => session('account.account_id')));
    //         $mailData['resume_id'] = I('post.rsId');
    //         $mailData['p_r_id'] = $result;
    //         $mailData['enId'] = $data['enterprise_id'];
    //         $enterpriseTrace = A('EnterpriseTrace');
    //         $enterpriseTrace->resumePostAccept($mailData);
    //         exit(json_encode(array('status' => 'success','msg' => C('L_NORMAL_SUCCESS'))));
    //     }else{
    //         exit(json_encode(array('status' => 'fail','msg' => C('L_NORMAL_FAIL'))));
    //     }
    // }

    // //联合数组
    // public function infoList()
    // {
    //     $list = array(
    //         'eduList'       => getDegreeText(),
    //         'rangeList'     => getDaySalaryText(),
    //         'graduateList'  => getGradeText(),
    //         'workdaysList'  => getWorkDayText(),
    //         'welfareList' => parent::getWelfareList(),
    //         'categoryList'=> parent::getCategoryList(),
    //         'industryList'=> parent::getIndustryList()
    //     );
    //     return $list;
    // }

    // //获取省份或城市的id
    // public function getRegionIds($name){
    //     $string = '';
    //     if(!empty($name)){
    //         $array = M('Regions')->field('region_id')->where(array('region_name' => array('like',"%".$name."%")))
    //             ->select();
    //         if(!empty($array)){
    //             foreach($array as $value){
    //                 $newArr[] = $value['region_id'];
    //             }
    //         }
    //         $string = implode(',',$newArr);
    //     }
    //     return $string;
    // }

    // //获取企业ID
    // public function getEnterpriseIds($name){
    //     $string = '';
    //     if(!empty($name)){
    //         $array = M('Enterprise')->field('pkid')->where(array('full_name' => array('like',"%".$name."%")))->select();
    //         if(!empty($array)){
    //             foreach($array as $value){
    //                 $newArr[] = $value['pkid'];
    //             }
    //         }
    //         $string = implode(',',$newArr);
    //     }
    //     return $string;
    // }


    // private function sendResumePostMail($data){
    //     if(!empty($data) && is_array($data)){
    //         $student_info = D('Student')->getBaseInfo($data['stId']);
    //         $enterprise_info = M('Enterprise')->field('pkid,full_name,subscribe_email,login_email')->find($data['enId']);
    //         $post_info = $this->PostModel->field('pkid,title')->find($data['ptId']);

    //         $mail = $enterprise_info['subscribe_email'] ? $enterprise_info['subscribe_email'] :
    //             $enterprise_info['login_email'];

    //         if(!empty($student_info)){
    //             $student_info['current_grade_text'] = getGradeText($student_info['graduate_year']?$student_info['graduate_year']:'');
    //             $student_info['education_text'] = getDegreeText($student_info['education']);
    //             $student_info['politics_status_text'] = getPoliticsText($student_info['politics_status']);
    //             $MajorTypeModel = '';
    //             $student_info['major_type_text'] = getMajorTypeText($student_info['major_type'],$MajorTypeModel);
    //         }

    //         $mail_tpl = array(
    //             'studentInfo' => $student_info,
    //             'enterpriseInfo' => $enterprise_info,
    //             'postInfo'  => $post_info,
    //             'mail'  => $mail
    //         );
    //         return $mail_tpl;
    //     }
    // }

    // /*
    // * @function 根据职位主键PKID，推荐符合条件的学生简历
    // * @param int:$post_id
    // * @return array
    // * 致远<george.zou@gaodun.cn> ('<^>') 
    // */
    // public function studentRecommend($id){
    //     $info = $this->PostModel->getPostInfo(array('post.pkid' => $id));
    //     $where['post_subscribe.expect_city'] = array('like','%'.$info['city_id'].'%');//意向城市
    //     $where['post_subscribe.expect_category'] = $info['category_id'];//意向分类
    //     $where['post_subscribe.week_available'] = $info['week_available'];//可实习天数
    //     $return = D('PostSubscribe')->getRecommand($where);
    //     return $return['data'];
    // }

    // //ajax判断是否有简历与邮箱是否已经验证
    // public function checkType(){
    //     $type = I('post.type');
    //     $stId = session('account.student_id');
    //     $status = 'fail';
    //     switch($type){
    //         case 'mail':
    //             $stInfo = M('Student')->field('email_verify')->find($stId);
    //             if($stInfo['email_verify'] != 1){
    //                 $status = 'success';
    //             }
    //             break;
    //         case 'resume':
    //             $resume = D('PostSubscribe')->existExpect();
    //             if(empty($resume)){
    //                 $status = 'success';
    //             }
    //             break;
    //         default:
    //             $status = $status;
    //             break;
    //     }
    //     exit(json_encode(array('status' => $status)));
    // }
    
    // //检查简历完成度
    // public function checkComplete(){
    //     $resume_type = I('post.resume_type');
    //     if($resume_type != 4){
    //         $result = M('Student')->field('complete_rate,complete_rate_en')->find(session('account.student_id'));
    //         if(!empty($result)){
    //             if($resume_type == 1){
    //                 if($result['complete_rate'] < 60){
    //                     exit(json_encode(array('status' => 'fail')));
    //                 }
    //             }else{
    //                 if($result['complete_rate'] < 60 && $result['complete_rate_en'] < 60){
    //                     exit(json_encode(array('status' => 'fail')));
    //                 }
    //             }
    //         }
    //     }

    //     exit(json_encode(array('status' => 'success')));
    // }
}
