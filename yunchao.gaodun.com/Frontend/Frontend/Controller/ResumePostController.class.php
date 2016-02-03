<?php
/**
 * Created by PhpStorm.
 * User: allen
 * Date: 2015/5/25
 * Time: 9:06
 */

namespace Frontend\Controller;
use Vendor\PageAdmin as PageAdmin;

class ResumePostController extends CommonController{

    protected $ResumePostModel;     //投递模型
    protected $PostModel;           //岗位模型
    protected $ResumeForwardModel;  //转发模型
    protected $TagModel;            //标签库列表模型
    protected $TagRelationModel;    //标签库模型
    protected $EnterpriseTraceController; //发送邮件控制器
    protected $tag_lists;   //初始化标签库
    protected $page_number = 10;
    protected $controller_code  = 500;
    protected $date_type = '0000-00-00';
    protected $default_text = array(3=>'你好，同学！你的简历已通过我们的审核，近期我们会电话联系你，请保持电话畅通。',
        4=>'非常荣幸收到你的简历，但你的简历与该职位条件不太匹配，祝你找到满意的工作。');

    public function _initialize(){
        parent::_initialize();
        parent::verifyRightLogin();
        self::checkType();
        $this->ResumePostModel = D('ResumePost');
        self::getTotal();
        $this->PostModel = D('Post');
        $this->ResumeForwardModel = M('resume_forward');
        $this->EnterpriseTraceController = A('EnterpriseTrace');
        $this->TagModel = D('Tag');
        $this->TagRelationModel = D('TagRelation');
        $this->tag_lists = $this->returnTagLists();
    }

    /*
    * @function 待处理--简历列表
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function index(){
        actionLogAdd(31,session('account'));
        $this->checkRedNote();
        //获取列表数据        
        $this->resumeListBox(array('in','1,2'),'/ResumePost/index','',true);
        $this->display();
    }

    /*
    * @function 待定--简历列表
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function wait(){
        $this->resumeListBox(array('eq',5),'/ResumePost/wait','resume_post.deal_time desc');
        $this->display();
    }

    /*
    * @function 处理待处理简历、待定简历的列表数据
    * @param array:$where_type 简历状态筛选条件
    * @param string:$search_form 简历搜索action
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    protected function resumeListBox($where_type,$search_form,$order,$forward=false){
        $enterprise = session('account');
        //获取列表数据
        $param = I('get.');
        $param['ct'] = I('get.ct') ? urldecode(I('get.ct')) : '';
        isset($_GET['pt']) && $_GET['pt'] ? $where['resume_post.post_id'] = intval($param['pt']) : '';//职位
        isset($_GET['dt']) && $_GET['dt'] ? $where['student.education'] = intval($param['dt']) : '';//学历
        isset($_GET['ct']) && $_GET['ct'] ? $where['student.living_city'] = cityNameToid($param['ct'])  : '';//学生所在城市
        isset($_GET['gt']) && $_GET['gt'] ? $where['student.graduate_year'] = I('get.gt')  : '';//学生所在城市
        /*---------更新搜索规则start------------*/
        isset($_GET['ps']) && $_GET['ps'] ? $start = I('get.ps') : '';//实习开始时间
        isset($_GET['pe']) && $_GET['pe'] ? $end = I('get.pe') : '';//实习结束时间
        if($start && !$end){
            $where['_string'] = "post_subscribe.period_start >= '{$start}' OR post_subscribe.period_start ='0000-00-00'";
        }
        if(!$start && $end){
            $where['_string'] = "post_subscribe.period_start <= '{$end}'";
        }
        if($start && $end){
            $where['_string'] = "(post_subscribe.period_start between '{$start}' and '{$end}') OR post_subscribe.period_start='0000-00-00'";
        }
        /*----------更新搜索规则end-----------*/
        isset($_GET['major_wish']) ? $major = I('get.major_wish') : '';//多专业
        if(is_array($major) && count($major)>0 && $major[0] != 0){
            $where['student.major_type'] = array('in',$major);
            $major_string = join($major,',');
        }
        if(is_string($major)){
            $major_arr = explode(',',$major);
            $where['student.major_type'] = array('in',$major_arr);
            $major_string = $major;
        }
        $where['resume_post.status'] = $where_type;
        $where['resume_post.send_type'] = 1 ;
        $where['post_subscribe.info_type'] = 1 ;
        $where['resume_post.enterprise_id'] = session('account.enterprise_id');
        $resumePostTotal = $this->ResumePostModel->getResumePostCount($where);//列表总数量
        $page = isset($_GET['p']) ? intval(htmlspecialchars(strip_tags($_GET['p']))) : '';//获取url的分页参数
        $page = max(1,$page);
        $page = min($page,ceil($resumePostTotal/$this->page_number)); // 获取合法的分页数
        $resumePostList = $this->ResumePostModel->getResumePostData($where,$page,$this->page_number,$order);
        if($resumePostList){
            foreach($resumePostList as $k=>$v){
                /*用于计算转发次数by allen at 2015-12-03*/
                if($forward == true){
                    $resumePostList[$k]['forward_count'] = $this->ResumeForwardModel->where(array('resume_post_id'
                    => $v['pkid']))->getField('count(pkid)');
                }
                /*用于计算转发次数*/
                $resumePostList[$k]['education_text'] = $v['education'] ? getDegreeTextSTU($v['education']) : '';
                $resumePostList[$k]['graduate_text'] = $v['graduate_year'] ? getGradeText($v['graduate_year']).'毕业' : '';
                $resumePostList[$k]['city_text'] = $v['living_city'] ? regionIdToname($v['living_city']) : '';
                $resumePostList[$k]['type_text'] = $v['resume_type'] ? resumePostType($v['resume_type']) : '';
                //处理时间
                if($v['status']==1 || $v['status']==2){
                    $show_time = $v['create_time'];
                }else{
                    $show_time = $v['deal_time'];
                }
                if($v['status'] == 3){
                    $resumePostList[$k]['status_text'] = '允许面试';
                }
                if($v['status'] == 4){
                    $resumePostList[$k]['status_text'] = '不合适';
                }
                $resumePostList[$k]['time_text'] = $show_time ? time2Unit($show_time) :'';

                if($v['period_start'] == $this->date_type && $v['period_finish'] == $this->date_type){
                    $resumePostList[$k]['dateType'] = 1;
                }elseif($v['period_start'] != $this->date_type && $v['period_finish'] == $this->date_type){
                    $resumePostList[$k]['dateType'] = 2;
                }else{
                    $resumePostList[$k]['dateType'] = 3;
                }
            }
        }
        if($major_string){
            $param['major_wish'] = $major_string;
        }else{
            unset($param['major_wish']);
        }

        if(is_null($major_string)){
            $majorLi = '';
        }
        if($major_string || ($major_wish[0]==0 && isset($_GET['major_wish']))){
            $majorLi = majorNameListLi($major_string);
        }
        $map = $param ? $param : array();
        $Page = new PageAdmin($resumePostTotal,$this->page_number,$map);
        $this->assign('show',!empty($resumePostList) ? 1 : 0); //判断下拉框是否需要显示
        $this->assign('search_form',$search_form);
        $this->assign('majorLi',$majorLi);
        $this->assign('param',$param);
        $this->assign('total',$resumePostTotal);
        $this->assign('resumePostList',$resumePostList);
        $this->assign('page',$Page->show());
        $this->assign('majorTypeList',$this->majorTypeList());
        $this->assign('postList',$this->postList());
        $this->assign('majorList',$this->majorTypeArr());
        $this->assign('degreList',getDegreeTextSTU());
        $this->assign('grade',getGradeText());
    }

    /*
     * 已下线职位
     */
    public function offline(){
        $where['post.status'] = 2;
        $where['enterprise_id'] = session('account.enterprise_id');
        $order = 'modify_time desc';
        #岗位列表  --begin--
        $postTotal = $this->PostModel->getPostTotalOffine($where);//列表总数量
        $page = isset($_GET['p']) ? intval(htmlspecialchars(strip_tags($_GET['p']))) : '';//获取url的分页参数
        $page = max(1,$page);
        $page = min($page,ceil($postTotal/$this->page_number)); // 获取合法的分页数
        $post = $this->PostModel->getPostList($where,$page,$this->page_number,$order);
        $Page = new PageAdmin($postTotal,$this->page_number); //分页类实例化
        $this->assign('list',$this->infoList());
        $this->assign('postList',$post);
        $this->assign('page',$Page->show());
        $this->display();
    }

    /*
     * 允许面试
     */
    public function allow(){
        $where = array();
        $order = 'deal_time desc';
        $param = I('get.');
        $param['pt'] ? $where['post_id'] = intval($param['pt']) : '';
        $param['st'] ? $where['send_type'] = $param['st'] : '';
        $where['status'] = 3;
        $where['enterprise_id'] = session('account.enterprise_id');
        $resumePostTotal = $this->ResumePostModel->getResumePostTotal($where);//列表总数量
        $page = isset($_GET['p']) ? intval(htmlspecialchars(strip_tags($_GET['p']))) : '';//获取url的分页参数
        $page = max(1,$page);
        $page = min($page,ceil($resumePostTotal/$this->page_number)); // 获取合法的分页数
        $resumePostList = $this->ResumePostModel->getResumePostList($where,$page,$this->page_number,$order);
        $map = $param ? $param : array();
        $Page = new PageAdmin($resumePostTotal,$this->page_number,$map);

        //判断下拉框是否需要显示
        if(!empty($param['pt']) || !empty($resumePostList) || !empty($param['st'])){
            $show = 1;
            $this->assign('show',$show);
        }

        $this->assign('param',$param);
        $this->assign('resumePostList',$resumePostList);
        $this->assign('page',$Page->show());
        $this->assign('majorTypeList',$this->majorTypeList());
        $this->assign('postList',$this->postList());
        $this->display();
    }

    /*
     * 已拒绝
     */
    public function refuse(){
        $this->display();
    }

    /*
     * 不合适
     */
    public function improper(){
        $where = array();
        $order = 'deal_time desc';
        $where['status'] = 4;
        $where['enterprise_id'] = session('account.enterprise_id');
        $resumePostTotal = $this->ResumePostModel->getResumePostTotal($where);//列表总数量
        $page = isset($_GET['p']) ? intval(htmlspecialchars(strip_tags($_GET['p']))) : '';//获取url的分页参数
        $page = max(1,$page);
        $page = min($page,ceil($resumePostTotal/$this->page_number)); // 获取合法的分页数
        $resumePostList = $this->ResumePostModel->getResumePostList($where,$page,$this->page_number,$order);
        $Page = new PageAdmin($resumePostTotal,$this->page_number);
        $this->assign('resumePostList',$resumePostList);
        $this->assign('majorTypeList',$this->majorTypeList());
        $this->assign('page',$Page->show());
        $this->display();
    }

    /*
     * 有效职位
     */
    public function effective(){
        $where['post.status'] = 1;
        $where['enterprise_id'] = session('account.enterprise_id');
        #岗位列表  --begin--
        $postTotal = $this->PostModel->getPostTotal($where);//列表总数量
        $page = isset($_GET['p']) ? intval(htmlspecialchars(strip_tags($_GET['p']))) : '';//获取url的分页参数
        $page = max(1,$page);
        $page = min($page,ceil($postTotal/$this->page_number)); // 获取合法的分页数
        $post = $this->PostModel->getPostList($where,$page,$this->page_number);
        foreach($post as $k=>$v){
            $post[$k]['day_diff_disabled'] = strtotime($v['refresh_time']) ? reflushDay($v['create_time'],1) : TRUE;
        }
        $Page = new PageAdmin($postTotal,$this->page_number); //分页类实例化
        $this->assign('list',$this->infoList());
        $this->assign('postList',$post);
        $this->assign('page',$Page->show());
        $this->display();
    }

    /*
     * 发布职位
     */
    public function add(){
        $this->assign('list',$this->infoList());
        $this->assign('graduateList',$this->graduateList());
        $this->assign('type','add');
        $this->assign('view',self::checkEnterpriseInfo());
        $this->assign('showLiHtml',$this->getTagHtml('choose'));
        $info2 = M('Enterprise')->find(session('account.enterprise_id'));
        if($info2['email_verify'] != 1){
            $this->assign('verify','not');
        }
        $info['attend_tips'] = '选择时段表示您期望学生在设置的时段中到岗';
        $info['receive_tips'] = '选择时段表示您只允许学生在设置的时段中投递该职位';
        $info['subscribe_email'] = $info2['subscribe_email'] ? $info2['subscribe_email'] : $info2['login_email'];
        $this->assign('info',$info);
        $this->display();
    }


    /*
     * 编辑职位页面
     */
    public function edit(){
        $this->editPostInfo();
        $this->assign('type','edit');
        $this->display('add');
    }

    //快速发布
    public function copy(){
        $this->editPostInfo();
        $this->assign('type','copy');
        $this->display('add_copy');
    }

    //获取职位详情
    protected function editPostInfo(){
        $id = enInt(I('get.id'));
        $info = $this->PostModel->getPostInfo(array('post.pkid' => $id));
        $enterprise = M('Enterprise')->field('subscribe_email,login_email')->find($info['enterprise_id']);
        $info['subscribe_email'] = $enterprise['subscribe_email'] ? $enterprise['subscribe_email'] : $enterprise['login_email'];
        $info['receive_start'] = strtotime($info['receive_start']) ? $info['receive_start'] : '' ;
        $info['receive_finish'] = strtotime($info['receive_finish']) ? $info['receive_finish'] : '' ;
        $info['attend_start'] = strtotime($info['attend_start']) ? $info['attend_start'] : '' ;
        $info['attend_finish'] = strtotime($info['attend_finish']) ? $info['attend_finish'] : '' ;
        $info['attend_tips'] = '选择时段表示您期望学生在设置的时段中到岗';
        $info['receive_tips'] = '选择时段表示您只允许学生在设置的时段中投递该职位';
        if(strtotime($info['receive_start']) && !strtotime($info['receive_finish'])){
            $info['receive_tips'] = '允许学生在"开始日期"后投递该职位';
        }
        if(!strtotime($info['receive_start']) && strtotime($info['receive_finish'])){
            $info['receive_tips'] = '允许学生在"截止日期"前投递该职位';
        }
        if(strtotime($info['receive_start']) && strtotime($info['receive_finish'])){
            $info['receive_tips'] = '允许学生在设置的时段中投递该职位';
        }
        if(strtotime($info['attend_start']) && !strtotime($info['attend_finish'])){
            $info['attend_tips'] = '允许学生在"开始日期"后投递该职位';
        }
        if(!strtotime($info['attend_start']) && strtotime($info['attend_finish'])){
            $info['attend_tips'] = '允许学生在"截止日期"前投递该职位';
        }
        if(strtotime($info['attend_start']) && strtotime($info['attend_finish'])){
            $info['attend_tips'] = '期望学生在设置的时段中到岗';
        }

        /*-------------2015-07-14 16:56:27  星期二-------------*/
        $major_string = $info['major_wish'];
        $major_arr = explode(',',$major_string);
        $major_arr_str = majorNameListLi($major_string);
        /*--------------2015-07-14 16:56:39  星期二------------*/
        $graduateList = $this->graduateList();
        $graduateArr = !empty($info['graduate_year']) ? explode(',',$info['graduate_year']) : array();
        foreach($graduateList as $key => $value){
            if(in_array($key,$graduateArr) !== FALSE){
                $graduateList[$key]['flag'] = 'y';
            }
        }

        /*编辑页内选中标签展示 2015-12-16*/
        $relation_where = array('model_id' => $id,'tag_relation.is_delete' => 2,'model_type' => 1);
        $relation_lists = $this->TagRelationModel->returnTagRelationLists($relation_where);

        /*获取被选中的标签的key值*/
        foreach($relation_lists as $value){
            $key_lists[] = $value['tag_id'];
        }

        $this->assign('choosedHtml',$this->getTagHtml('choosed',1,$relation_lists));
        $this->assign('showLiHtml',$this->getTagHtml('choose',1,$key_lists));
        $this->assign('major',$major_arr);
        $this->assign('majorLi',$major_arr_str);
        $this->assign('list',$this->infoList());
        $this->assign('graduateList',$graduateList);
        $this->assign('info',$info);
        $this->assign('view',1);
    }

    /*
    * @function 有效职位列表
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function postList(){
        $id = session('account.enterprise_id');
        $list = array();
        if(!empty($id)){
            $list = M('Post')->where(array('enterprise_id' =>$id,'is_delete'=>2,'status'=>1))->getField('pkid,title');
        }
        return $list;
    }

    //更新数据
    public function update(){
        $post = I('param.');
        $post['status'] == 2 ? $data['read_time'] = date('Y-m-d H:i:s',NOW_TIME) : $data['deal_time'] = date('Y-m-d H:i:s', NOW_TIME);
        $post['id'] ? $pkid = enInt($post['id']) : $pkid=$post['pkid'];
        $data['modify_time'] = dateTime();
        $data['status'] = $post['status'];
        $where['enterprise_id'] = session('account.enterprise_id');
        $where['pkid'] = $pkid;
        $bool = $this->ResumePostModel->where($where)->data($data)->save();
        $studentTrace = new StudentTraceController();
        if($data['status'] == 3){
            $studentTrace->allowInterview($pkid,TRUE);
        }
        if($data['status'] == 4){
            $studentTrace->refuseInterview($pkid,TRUE);
        }
    }


    //简历操作允许面试、不适合、待定
    public function updateView(){
        $post = I('param.');
        $post['status'] == 2 ? $data['read_time'] = date('Y-m-d H:i:s',NOW_TIME) : $data['deal_time'] = date('Y-m-d H:i:s', NOW_TIME);
        $post['id'] ? $pkid = enInt($post['id']) : $pkid=$post['pkid'];
        $data['modify_time'] = dateTime();
        $data['status'] = $post['status'];
        if($data['status'] == 3 || $data['status'] == 4){
            $data['hr_remark'] = !empty($post['remark']) ? $post['remark'] : $this->default_text[$data['status']];
        }
        $where['enterprise_id'] = session('account.enterprise_id');
        $where['pkid'] = $pkid;
        $bool = $this->ResumePostModel->where($where)->data($data)->save();
        $studentTrace = new StudentTraceController();
        if($data['status'] == 3){
            $studentTrace->allowInterview($pkid,TRUE);
        }
        if($data['status'] == 4){
            $studentTrace->refuseInterview($pkid,TRUE);
        }
        if($bool){
            returnjson(array('status'=>TRUE,'msg'=>''));
        }else{
            returnjson(array('status'=>FALSE,'msg'=>'fail'));
        }
    }

    //更新职位信息
    public function postEdit(){
        $data = I('get.');
        $data['modify_time'] = date('Y-m-d H:i:s',NOW_TIME);
        $this->PostModel->data($data)->save();
        //下线时，更新邀请职位的状态
        if($data['status']==2){
            D('InterviewInvitation')->invitePostStatus($data['pkid'],2);
        }
        //上线时，更新邀请职位的状态
        if($data['status']==1){
            D('InterviewInvitation')->invitePostStatus($data['pkid'],1);
        }
    }

    //保存与新增
    public function save(){
        $data = I('post.');

        // 接收时间 ，到岗时间2015-12-07 start
        if($data['receive_finish'] && $data['receive_start']){
            if(strtotime($data['receive_finish']) < strtotime($data['receive_start'])){
                $result['status'] = 'fail';
                $result['msg'] = '接收截止日期不得小于开始日期';
                $result['code'] = 101;
                $result['data'] = 'accept';
                returnjson($result);
            }
        }
        if($data['attend_finish'] && $data['attend_start']){
            if(strtotime($data['attend_finish']) < strtotime($data['attend_start'])){
                $result['status'] = 'fail';
                $result['msg'] = '接收截止日期不得小于开始日期';
                $result['code'] = 102;
                $result['data'] = 'post';
                returnjson($result);
            }
        }
        if(isset($data['receive_start']) && $data['receive_start']){
            if(strtotime($data['receive_start']) < strtotime(date('Y-m-d',NOW_TIME))){
                $result['status'] = 'fail';
                $result['msg'] = '接收开始日期不得小于当前日期';
                $result['code'] = 103;
                $result['data'] = 'accept';
                returnjson($result);
            }
        }
        if(isset($data['attend_start']) && $data['attend_start']){
            if(strtotime($data['attend_start']) < strtotime(date('Y-m-d',NOW_TIME))){
                $result['status'] = 'fail';
                $result['msg'] = '到岗开始日期不得小于当前日期';
                $result['code'] = 104;
                $result['data'] = 'post';
                returnjson($result);
            }
        }
        if($data['receive_type'] == 1){
            $data['receive_start'] = null;
            $data['receive_finish'] = null;
        }else{
            $data['receive_start'] = $data['receive_start'] ? $data['receive_start'] : null ;
            $data['receive_finish'] = $data['receive_finish'] ? $data['receive_finish'] : null ;
        }
        if($data['attend_type'] == 1){
            $data['attend_start'] = null;
            $data['attend_finish'] = null;
        }else{
            $data['attend_start'] = $data['attend_start'] ? $data['attend_start'] : null ;
            $data['attend_finish'] = $data['attend_finish'] ? $data['attend_finish'] : null ;
        }
        //end

        $data['enterprise_id'] = session('account.enterprise_id');
        if(!empty($data['graduate'])){
            $data['graduate_year'] = implode(',',$data['graduate']);
        }
        /*---------------2015-07-14 16:41:48  星期二----------------*/
        if(!empty($data['major_wish'])){
            $data['major_wish'] = implode(',',$data['major_wish']);
        }
        /*---------------2015-07-14 16:41:57  星期二----------------*/
        $data['status'] = 1;
        $data['address'] = trim($data['address']);
        $data['city_id'] = $this->getCityId($data['city']);
        $province = M('Regions')->where(array('region_id' => $data['city_id']))->find();
        $data['province_id'] = $province['parent_id'];
        if($data['city'] == '台湾省' || $data['city'] == '香港' || $data['city'] == '澳门'){
            $result = M('Regions')->field('region_id')
                ->where(array('region_name' => array('like',"%" .$data['city'] . "%")))
                ->find();
            $data['city_id'] = $data['province_id'] = $result['region_id'];
        }
        unset($data['city']);unset($data['grade']);

        /*新增标签库  2015-12-16*/
        if(!empty($data['relation'])){
            $relation = $data['relation'];
        }
        unset($data['relation']);


        if(isset($data['pkid']) && !empty($data['pkid'])){
            $data['modify_time'] = date('Y-m-d H:i:s',NOW_TIME);
            $result = $this->PostModel->postUpdate($data);
            $type = 'edit';
        }else{
            $data['create_time'] = date('Y-m-d H:i:s',NOW_TIME);
            $data['modify_time'] = $data['create_time'];
            $result = $this->PostModel->postAdd($data);
            $type = 'add';
        }


        if($result !== FALSE){
            if($type=='add'){ //新增时判断
                /*---------2015-08-14 10:22:33  星期五----------*/
                //check发布职位积分
                D('EnterpriseScore')->checkInviteUpdatePost($data['enterprise_id']);
                /*---------2015-08-14 10:22:33  星期五----------*/
            }else{
                $where = array('model_id' => $data['pkid'],'is_delete' => 2);
                $delete = array('is_delete' => 1,'modify_time' => dateTime());
                $this->TagRelationModel->tagRelationSave($delete,$where);
            }
            /*标签库内容新增*/
            if(!empty($relation)){
                if(is_array($relation)){
                    foreach($relation as $value){
                        $relation_data[] = array(
                            'model_type' => 1,
                            'model_id' => $type == 'add' ? $result : $data['pkid'],
                            'tag_id' => $value,
                            'is_delete' => 2,
                            'create_time' => dateTime()
                        );
                    }
                    $this->TagRelationModel->tagRelationAdd($relation_data);
                }
            }

            exit(json_encode(array('status' => 'success','message' => C('L_NORMAL_SUCCESS'),'type' => $type)));
        }else{
            exit(json_encode(array('status' => 'fail','message' => C('L_NORMAL_FAIL'))));
        }
    }

    //获取未处理的数量
    public function getTotal(){
        $resumePostTotal = $this->ResumePostModel->getResumePostCount(array('resume_post.status' => array('in','1,2'),
            'resume_post.enterprise_id' => session('account.enterprise_id'),'resume_post.send_type'=>1,'post_subscribe.info_type=1'));  //   待处理简历 
        $resumePostTotalWait = $this->ResumePostModel->getResumePostCount(array('resume_post.status' => array('in','5'),'resume_post.enterprise_id' => session('account.enterprise_id'),'resume_post.send_type'=>1,'post_subscribe.info_type=1'));//待定简历
        $invite_total = D('InterviewInvitation')->inviteListsTotalAccept();//接受待处理
        $invite_total = min($invite_total,30);
        session('resume.number_invite',intval($invite_total));
        session('resume.number_waiting',intval($resumePostTotalWait));
        session('resume.number',intval($resumePostTotal));
    }

    //联合数组
    public function infoList()
    {
        //专业
        $list = array(
            'scaleList'     => parent::getScaleList(),
            'categoryList'  => parent::getCategoryList(),
            'majorList'     => self::majorTypeList(),
            'eduList'       => getDegreeText(null),
            'rangeList'     => getDaySalaryText(null),
            'weekList'      => getWorkDayText(null)
        );
        return $list;
    }

    //专业分类列表
    public function majorTypeList(){
        return $this->majorTypeArr();
    }

    //年级数组
    public function gradeList(){
        return array(
            1 => array('name' => '大一','flag' => 'n'),
            2 => array('name' => '大二','flag' => 'n'),
            3 => array('name' => '大三','flag' => 'n'),
            4 => array('name' => '大四','flag' => 'n'),
            5 => array('name' => '研究生','flag' => 'n'),
        );
    }

    //毕业年级
    public function graduateList(){
        return getGradeText('','',TRUE);
    }

    //获取城市ID
    public function getCityId($name){
        $id = FALSE;
        if(!empty($name)){
            $result = M('Regions')->field('region_id')->where(array('region_name' => array('like',"%" .$name .
                "%"),'region_type' => 2))
                ->find();
            $id = $result['region_id'];
        }
        return $id;
    }

    //页面跳转时候的判断
    public function checkType(){
        if(session('account.account_type') == 2){
            redirect('/Student/index');
            exit;
        }
    }

    //页面跳转时候的判断
    public function checkEnterpriseInfo(){
        $enterprise_id = session('account.enterprise_id');
        $result = D('Enterprise')->getInfoByWhere(array('enterprise.pkid' => $enterprise_id));
        if(empty($result['full_name']) || empty($result['industry_id'])){
            return 2;
        }else{
            return 1;
        }
    }

    /*
    * @function 将查看简历次数写入数据表
    * @param 简历投递主键id
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 最后更新时间
    */
    protected function resumeView($rid=''){
        if($rid){
            $data = $this->ResumePostModel->resumeViewData(intval($rid));
            if($data){
                D('ResumeView')->viewAdd($data);
            }
        }
    }


    /*
    * @function 耍新职位发布时间
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function reflushPostTime(){
        if(IS_POST){
            $id = isset($_POST['data']) ? intval(I('post.data')): '';
            if($id){
                $exist = $this->PostModel->existPostPkid($id);
                if($exist>0){
                    $time = $this->PostModel->postReflushTime($id) ;
                    $time_bool = strtotime($time) ? reflushDay($time,1) : TRUE;
                    if($time_bool===TRUE){
                        $bool = $this->PostModel->updateReflushTime($id);
                        if($bool){
                            $data = time2Unit($this->PostModel->postReflushTime($id));
                            returnjson(array('code'=>501,'status'=>TRUE,'msg'=>'','data'=>$data));
                        }else{
                            returnjson(array('code'=>502,'status'=>FALSE,'msg'=>'刷新失败'));
                        }
                    }
                    if($time_bool===FALSE){
                        returnjson(array('code'=>503,'status'=>FALSE,'msg'=>'未到再次刷新限制'));
                    }

                }

            }

        }
    }

    /*
    * @function hr批量处理待定、允许面试、不适合
    * @param string:$data 由简历投递主键组成的字符串数据，如：'45,89,908'
    * @param string:$status 处理类型，{3:'允许面试',4:'不适合',5:待定}
    * 致远<george.zou@gaodun.cn> ('<^>') 2015-11-04 14:51:11  星期三
    */
    public function resumeHandle(){
        if(IS_POST && !empty($_POST['data']) && !empty($_POST['status'])){
            $data = I('post.data');
            $data = trim($data,',');
            $status = I('post.status');
            $status = intval($status);
            if(!in_array($status,array(3,4,5)) || empty($data)){
                returnjson(array('code'=>505,'status'=>FALSE,'msg'=>'param error'));
            }
            $where['pkid'] = array('in',$data);
            $where['enterprise_id'] = session('account.enterprise_id');
            $update['status'] = $status;
            $update['modify_time'] = dateTime();
            $update['deal_time'] = $update['modify_time'];
            $bool = $this->ResumePostModel->where($where)->data($update)->save();
            if($bool){
                if($update['status'] == 3 OR $update['status'] == 4){
                    $studentTrace = new StudentTraceController();
                    $resume_data = explode(',',trim($data,','));
                }
                if($update['status'] == 3 AND !empty($resume_data)){
                    foreach($resume_data as $v){
                        $studentTrace->allowInterview($v,TRUE);
                    }
                }
                if($update['status'] == 4 AND !empty($resume_data)){
                    foreach($resume_data as $v){
                        $studentTrace->refuseInterview($v,TRUE);
                    }
                }
                returnjson(array('code'=>506,'status'=>TRUE,'msg'=>''));

            }else{
                returnjson(array('code'=>504,'status'=>FALSE,'msg'=>'update fail'));
            }
        }
    }

    /*
     * @function 转发功能的实现
     * @author allen
     */
    public function forwardResume(){
        $id = I('post.id');
        if(!is_numeric($id)){
            $id = enInt($id);
        }
        $email = I('post.email');
        $verify_code = rand(100000,999999);
        $data = array(
            'resume_post_id' => $id,
            'forward_email'  => $email,
            'verify_code'    => $verify_code,
            'create_time'    => dateTime(),
            'forward_type'   => 1 //手动转发
        );
        $result = $this->ResumePostModel->resumeForwardInfo($id);
        $forward_id = $this->ResumeForwardModel->add($data);
        $result['verify_code'] = $data['verify_code'];
        $result['forward_id'] = $forward_id;
        $result['education_text'] = getDegreeTextSTU($result['education']);
        $result['graduate_text'] = getGradeText($result['graduate_year']);
        $result['url'] = '/ResumeForward/index/id/'.enInt($forward_id);
        $result['contact_mobile'] = !empty($result['contact_mobile']) ? $result['contact_mobile'] : $result['mobile'];
        $this->EnterpriseTraceController->resumeForward($result,$email);
    }

    /*
     * @function 标签新增
     * @author allen
     */
    public function tagAdd(){
        $title = I('post.title');
        $where = array('title' => $title,'account_id' => session('account.account_id'),'is_delete' => 2,'type' => 2);
        //判断是否存在该标签
        $result = $this->TagModel->returnDataByFields($where);
        if(!empty($result)){
            returnJson(array('status' => 'success','pkid' => $result));
        }

        $data = $where;
        $data['range'] = 1;
        $data['create_time'] = dateTime();
        $id = $this->TagModel->tagAdd($data); //新增标签
        if($id !== FALSE){
            returnJson(array('status' => 'success','pkid' => $id));
        }else{
            returnJson(array('status' => 'fail'));
        }
    }

    /*
     * @function 获取标签页/系统与个人
     * @author allen
     * @return array 数据数组
     */
    public function returnTagLists(){
        $where = array(
            'type&is_delete&range' => array(1,2,array('like','%1%'),'_multi'=>true),
            '_logic' => 'or',
            'account_id&type&is_delete&range' => array(session('account.account_id'),2,2,array('like','%1%'), '_multi'=>true)
        );
        $fields = 'pkid,pkid,title';
        return $this->TagModel->returnDataByFields($where,$fields);
    }

    /*
     * @function 随机获取标签 长度42个字
     * @author allen
     * @return array
     */
    public function randTagLists($p = 1){
        $lists = $this->tag_lists;
        $arr = $this->listsArray($lists);
        $count = count($arr);
        if($p > $count){
            $p = 1;
        }
        return array('lists' => $arr[$p],'count' => $count,'p' => $p+1);
    }

    /*递归获取标签*/
    public function listsArray($lists,$i=0,$new_array=array()){
        $length = 0;
        if(!empty($lists)) {
            $i = $i + 1;
            foreach ($lists as $key => $value) {
                $j = mb_strlen($value['title'], 'utf8');
                $length += $j;
                if ($length <= 45) {
                    $new[] = $lists[$key];
                    unset($lists[$key]);
                }
            }
            $new_array[$i] = $new;
            unset($new);
            return $this->listsArray($lists, $i, $new_array);
        }else{
            return $new_array;
        }
    }

    /*
     * @function 获取标签列表
     * @author allen
     * @return html 模版
     */
    public function getTagHtml($type,$p = 1,$lists = array()){
        if($type == 'choose'){
            $choosed_list = $lists;
        }
        //string $type 分为已选中列表与展示随机内容列表 choosed/choose
        $lists = $type == 'choosed' ? $lists : $this->randTagLists($p);
        /*选中或者ajax请求时候，判断该标签是否已经被选择，被选择则隐藏*/
        if(!empty($choosed_list)){
            foreach($lists['lists'] as $key => $value){
                if(in_array($value['pkid'],$choosed_list)){
                    $lists['lists'][$key]['class'] = 'dn';
                }
            }
        }
        $this->assign('tagLists',$lists);
        $this->assign('type',$type);
        return $this->fetch('tag_lists');
    }

    //ajax获取标签列表
    public function getTagLists(){
        $type = I('post.type');
        $p = I('post.p');
        $lists = I('post.lists');
        $lists = substr($lists,1,strlen($lists)-1);
        if(!empty($lists)){
            $choosed_list = explode(',',$lists);
        }
        $html = $this->getTagHtml($type,$p,!empty($choosed_list) ? $choosed_list : array());
        returnJson(array('status' => 'success','html' => $html));
    }
}
