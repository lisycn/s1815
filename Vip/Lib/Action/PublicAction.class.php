<?php

class PublicAction extends CommonAction
{

    public function _initialize()
    {
        header("Content-Type:text/html; charset=utf-8");
        $this->_inject_check(1); // 调用过滤函数
        $this->_Config_name(); // 调用参数
    }
    
    // 过滤查询字段
    function _filter(&$map)
    {
        $map['title'] = array(
            'like',
            "%" . $_POST['name'] . "%"
        );
    }
    // 顶部页面
    public function top()
    {
        C('SHOW_RUN_TIME', false); // 运行时间显示
        C('SHOW_PAGE_TRACE', false);
        $this->display();
    }
    // 尾部页面
    public function footer()
    {
        C('SHOW_RUN_TIME', false); // 运行时间显示
        C('SHOW_PAGE_TRACE', false);
        $this->display();
    }
    // 菜单页面
    public function menu()
    {
        $this->_checkUser();
        $map = array();
        $id = $_SESSION[C('USER_AUTH_KEY')];
        $field = '*';
        
        $map = array();
        $map['s_uid'] = $id; // 会员ID
        $map['s_read'] = 0; // 0 为未读
        $info_count = M('msg')->where($map)->count(); // 总记录数
        $this->assign('info_count', $info_count);
        
        $fck = M('fck');
        $fwhere = array();
        $fwhere['ID'] = $_SESSION[C('USER_AUTH_KEY')];
        $frs = $fck->where($fwhere)
            ->field('*')
            ->find();
        // dump($frs);
        $HYJJ = '';
        $this->_levelConfirm($HYJJ, 1);
        $this->assign('voo', $HYJJ);
        
        $this->assign('fck_rs', $frs);
        $this->display('menu');
    }
    
    // 后台首页 查看系统信息
    public function main()
    {
        $this->_checkUser();
//         $ppfg = $_POST['ppfg'];
        $id = $_SESSION[C('USER_AUTH_KEY')]; // 登录AutoId
        $fck = M('fck');
        $jiadan = M('Jiadan');
        $jiadanb = M('jiadanb');
//         $cash = M('cash');
//         $form = M('form');
//         $map = array();
//         $map['status'] = array(
//             'eq',
//             1
//         );
//         $field = '*';
//         $newslist = $form->where($map)
//             ->field($field)
//             ->order('baile desc,id desc')
//             ->limit(10)
//             ->select();
//         $this->assign('newslist', $newslist); // 数据输出到模板
        
//         $map = array();
//         $map['s_uid'] = $id; // 会员ID
//         $map['s_read'] = 0; // 0 为未读
//         $info_count = M('msg')->where($map)->count(); // 总记录数
//         $this->assign('info_count', $info_count);
        
        // 会员级别
        $urs = $fck->where('id=' . $id)
            ->field('*')
            ->find();
        $this->assign('fck_rs', $urs); // 总奖金
        // 团队人数
        $all_nn = $fck->where('re_path like "%,' . $id . ',%" and is_pay=1')->count();
        $this->assign('all_nn', $all_nn);
        // 团队总业绩
        $nowdate = strtotime(date('Y-m-d'));
        $all_nmoney = $fck->where('p_path like "%,' . $id . ',%" and is_pay=1 and pdt<' . $nowdate)->sum('cpzj');
        if (empty($all_nmoney)) {
            $all_nmoney = 0.00;
        }
        $this->assign('all_nmoney', $all_nmoney);
        
        // 出局分红包数
        $where = Array();
        $where['user_id'] = $urs['user_id'];
        $where['is_pay'] = 1;
        $out_counts = $jiadan->where($where)->sum('danshu');
        if (empty($out_counts)) {
            $out_counts = 0;
        }
        $this->assign('out_counts', $out_counts);
        
        // 未出局分红包数
        $where['user_id'] = $urs['user_id'];
        $where['is_pay'] = 0;
        $in_counts = $jiadan->where($where)->sum('danshu');
        if (empty($in_counts)) {
            $in_counts = 0;
        }
        $this->assign('in_counts', $in_counts);
        // B网版块
        $in_countsb = $jiadanb->where($where)->sum('danshu');
        if (empty($in_countsb)) {
            $in_countsb = 0;
        }
        $this->assign('in_countsb', $in_countsb);
        $where['is_pay'] = 1;
        $out_countsb = $jiadanb->where($where)->sum('danshu');
        if (empty($out_countsb)) {
            $out_countsb = 0;
        }
        $this->assign('out_countsb', $out_countsb);
        $netb = M('netb');
        $netb_rs = $netb->where('uid=' . $id)->field('*')->find();
        $this->assign('netB', $netb_rs);
//         // 直推人数
//         $one = $fck->where('id=1')
//             ->field('tz_nums')
//             ->find();
//         $this->assign('tz_nums', $one['tz_nums']);
        
//         $fee = M('fee');
//         $fee_rs = $fee->field('s3,s12,str1,str7,str9,str21,str22,str23,gp_one')->find();
//         $str21 = $fee_rs['str21'];
//         $str22 = $fee_rs['str22'];
//         $str23 = $fee_rs['str23'];
//         $all_img = $str21 . "|" . $str22 . "|" . $str23;
//         $this->assign('all_img', $all_img);
//         $s3 = explode("|", $fee_rs['s3']);
//         $s12 = $fee_rs['s12'];
//         $str1 = $fee_rs['str1'];
//         $str5 = explode("|", $fee_rs['str7']);
//         $str9 = $fee_rs['str9'];
// //         $one_price = $fee_rs['gp_one'];
//         $this->assign('s3', $s3);
//         $this->assign('s12', $s12);
//         $this->assign('str1', $str1);
//         $this->assign('str9', $str9);
        
//         // 股票价格
//         $this->assign('one_price', $one_price);
//         $gupiaojz = $one_price * $urs['live_gupiao'];
//         $this->assign('gupiaojz', $gupiaojz);
        
//         $maxqq = 4;
//         if (count($str5) > $maxqq) {
//             $lenn = $maxqq;
//         } else {
//             $lenn = count($str5);
//         }
//         for ($i = 0; $i < $lenn; $i ++) {
//             $qqlist[$i] = $str5[$i];
//         }
//         $this->assign('qlist', $qqlist);
        
        $HYJJ = "";
        $this->_levelConfirm($HYJJ, 1);
        $this->assign('voo', $HYJJ); // 会员级别
        
        $see = $_SERVER['HTTP_HOST'] . __APP__;
        $see = str_replace("//", "/", $see);
        $this->assign('server', $see);
        
//         $cp = M('product');
//         $fck = M('fck');
//         $map['id'] = $_SESSION[C('USER_AUTH_KEY')];
//         $f_rs = $fck->where($map)->find();
        
//         $where = array();
//         $ss_type = (int) $_REQUEST['tp'];
//         if ($ss_type > 0) {
//             $where['cptype'] = array(
//                 'eq',
//                 $ss_type
//             );
//         }
//         $this->assign('tp', $ss_type);
        
//         $where['yc_cp'] = array(
//             'eq',
//             0
//         );
        
//         $cptype = M('cptype');
//         $tplist = $cptype->where('status=0')
//             ->order('id asc')
//             ->select();
//         $this->assign('tplist', $tplist);
        
//         $order = 'id asc';
//         $field = '*';
//         // =====================分页开始==============================================
//         import("@.ORG.ZQPage"); // 导入分页类
//         $count = $cp->where($where)->count(); // 总页数
//         $listrows = 20; // 每页显示的记录数
//         $page_where = 'tp=' . $ss_type; // 分页条件
//         $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
//         // ===============(总页数,每页显示记录数,css样式 0-9)
//         $show = $Page->show(); // 分页变量
//         $this->assign('page', $show); // 分页变量输出到模板
//         $list = $cp->where($where)
//             ->field($field)
//             ->order('id desc')
//             ->page($Page->getPage() . ',' . $listrows)
//             ->select();
//         // =================================================
//         foreach ($list as $voo) {
//             $w_money = $voo['a_money'];
//             $e_money = $voo['b_money'];
//             $cc[$voo['id']] = $w_money;
//             $cc[$voo['cid']] = $e_money;
//         }
//         $this->assign('cc', $cc);
//         $this->assign('list', $list); // 数据输出到模板
        
//         $this->assign('f_rs', $f_rs);
        $this->display();
    }
    
    // 用户登录页面
    public function login()
    {
        $fee = M('fee');
        $fee_rs = $fee->field('str21,i9')->find();
        $this->assign('fflv', $fee_rs['str21']);
        $this->assign('i9', $fee_rs['i9']);
        unset($fee, $fee_rs);
        // $this->display('login3');
        $this->display('login6');
    }

    public function index()
    {
        // 如果通过认证跳转到首页
        redirect(__APP__);
    }
    
    // 用户登出
    public function LogOut()
    {
        $_SESSION = array();
        // unset($_SESSION);
        $this->assign('jumpUrl', __URL__ . '/login/');
        $this->success('退出成功！');
    }
    
    // 登录检测
    public function checkLogin()
    {
        if (empty($_POST['account'])) {
            $this->error('请输入帐号！');
        } elseif (empty($_POST['password'])) {
            $this->error('请输入密码！');
        } elseif (empty($_POST['verify'])) {
            $this->error('请输入验证码！');
        }
        $fee = M('fee');
        // $sel = (int) $_POST['radio'];
        // if($sel <=0 or $sel >=3){
        // $this->error('非法操作！');
        // exit;
        // }
        // if($sel != 1){
        // $this->error('暂时不支持英文版登录！');
        // exit;
        // }
        
        // 生成认证条件
        $map = array();
        // 支持使用绑定帐号登录
        $map['user_id'] = $_POST['account'];
        // $map['nickname'] = $_POST['account']; //用户名也可以登录
        // $map['_logic'] = 'or';
        // $map['_complex'] = $where;
        // $map["status"] = array('gt',0);
        if ($_SESSION['verify'] != md5($_POST['verify'])) {
            $this->error('验证码错误！');
        }
        
        import('@.ORG.RBAC');
        $fck = M('fck');
        $field = 'id,user_id,password,is_pay,is_lock,nickname,user_name,is_agent,user_type,last_login_time,login_count,is_boss';
        $authInfo = $fck->where($map)
            ->field($field)
            ->find();
        // 使用用户名、密码和状态的方式进行认证
        if (false == $authInfo) {
            $this->error('帐号不存在或已禁用！');
        } else {
            if ($authInfo['password'] != md5($_POST['password'])) {
                $this->error('密码错误！');
                exit();
            }
            
            if ($_POST['lang'] == 1) {
                $this->error('英文版本暂时无法登陆，请选择中文版本！');
                exit();
            }
            
            if ($_POST['agent'] == 2 && $authInfo['is_agent'] < $_POST['agent']) {
                $this->error('您为非报单中心,请选择会员登录入口！');
                exit();
            }
            
            if ($authInfo['is_pay'] < 1) {
                $this->error('用户尚未开通，暂时不能登录系统！');
                exit();
            }
            if ($authInfo['is_lock'] != 0) {
                $this->error('用户已锁定，请与管理员联系！');
                exit();
            }
            $_SESSION[C('USER_AUTH_KEY')] = $authInfo['id'];
            $_SESSION['loginUseracc'] = $authInfo['user_id']; // 用户名
            $_SESSION['loginNickName'] = $authInfo['nickname']; // 会员名
            $_SESSION['loginUserName'] = $authInfo['user_name']; // 开户名
            $_SESSION['lastLoginTime'] = $authInfo['last_login_time'];
            // $_SESSION['login_count'] = $authInfo['login_count'];
            $_SESSION['login_isAgent'] = $authInfo['is_agent']; // 是否报单中心
            $_SESSION['UserMktimes'] = mktime();
            $news = M('form');
            $news_result = $news->where('status = 1')->field('title')->select();
            $_SESSION['news'] = $news_result; // 新闻信息
            // 身份确认 = 用户名+识别字符+密码
            $_SESSION['login_sf_list_u'] = md5($authInfo['user_id'] . 'wodetp_new_1012!@#' . $authInfo['password'] . $_SERVER['HTTP_USER_AGENT']);
            
            // 登录状态
            $user_type = md5($_SERVER['HTTP_USER_AGENT'] . 'wtp' . rand(0, 999999));
            $_SESSION['login_user_type'] = $user_type;
            $where['id'] = $authInfo['id'];
            $fck->where($where)->setField('user_type', $user_type);
            // $fck->where($where)->setField('last_login_time',mktime());
            // 管理员
            
            $parmd = $this->_cheakPrem();
            if ($authInfo['id'] == 1 || $parmd[11] == 1) {
                $_SESSION['administrator'] = 1;
            } else {
                $_SESSION['administrator'] = 2;
            }
            
            // //管理员
            // if($authInfo['is_boss'] == 1) {
            // $_SESSION['administrator'] = 1;
            // }elseif($authInfo['is_boss'] == 2){
            // $_SESSION['administrator'] = 3;
            // }elseif($authInfo['is_boss'] == 3){
            // $_SESSION['administrator'] = 4;
            // }elseif($authInfo['is_boss'] == 4){
            // $_SESSION['administrator'] = 5;
            // }elseif($authInfo['is_boss'] == 5){
            // $_SESSION['administrator'] = 6;
            // }elseif($authInfo['is_boss'] == 6){
            // $_SESSION['administrator'] = 7;
            // }else{
            // $_SESSION['administrator'] = 2;
            // }
            
            $fck->execute("update __TABLE__ set last_login_time=new_login_time,last_login_ip=new_login_ip,new_login_time=" . time() . ",new_login_ip='" . $_SERVER['REMOTE_ADDR'] . "' where id=" . $authInfo['id']);
            
            // 缓存访问权限
            RBAC::saveAccessList();
            $this->success('登录成功！');
        }
    }
    // 二级密码验证
    public function cody()
    {
        $UrlID = (int) $_GET['c_id'];
        if (empty($UrlID)) {
            $this->error('二级密码错误!');
            exit();
        }
        if (! empty($_SESSION['user_pwd2'])) {
            $url = __URL__ . "/codys/Urlsz/$UrlID";
            $this->_boxx($url);
            exit();
        }
        $fck = M('cody');
        $list = $fck->where("c_id=$UrlID")->getField('c_id');
        
        if (! empty($list)) {
            $this->assign('vo', $list);
            $this->display('cody');
            exit();
        } else {
            $this->error('二级密码错误!');
            exit();
        }
    }
    // 二级验证后调转页面
    public function codys()
    {
        $Urlsz = $_POST['Urlsz'];
        if (empty($_SESSION['user_pwd2'])) {
            $pass = $_POST['oldpassword'];
            $fck = M('fck');
            if (! $fck->autoCheckToken($_POST)) {
                $this->error('页面过期请刷新页面!');
                exit();
            }
            if (empty($pass)) {
                $this->error('二级密码错误!');
                exit();
            }
            
            $where = array();
            $where['id'] = $_SESSION[C('USER_AUTH_KEY')];
            $where['passopen'] = md5($pass);
            $list = $fck->where($where)
                ->field('id')
                ->find();
            if ($list == false) {
                $this->error('二级密码错误!');
                exit();
            }
            $_SESSION['user_pwd2'] = 1;
        } else {
            $Urlsz = $_GET['Urlsz'];
        }
        switch ($Urlsz) {
            case 1:
                $_SESSION['DLTZURL02'] = 'updateUserInfo';
                $bUrl = __URL__ . '/updateUserInfo'; // 修改资料
                $this->_boxx($bUrl);
                break;
            case 2:
                $_SESSION['DLTZURL01'] = 'password';
                $bUrl = __URL__ . '/password'; // 修改密码
                $this->_boxx($bUrl);
                break;
            case 3:
                $_SESSION['DLTZURL01'] = 'pprofile';
                $bUrl = __URL__ . '/pprofile'; // 修改密码
                $this->_boxx($bUrl);
                break;
            case 4:
                $_SESSION['DLTZURL01'] = 'OURNEWS';
                $bUrl = __URL__ . '/News'; // 修改密码
                $this->_boxx($bUrl);
                break;
            default:
                $this->error('二级密码错误!');
                break;
        }
    }

    public function verify()
    {
        ob_clean();
        $type = isset($_GET['type']) ? $_GET['type'] : 'gif';
        import("@.ORG.Image");
        Image::buildImageVerify();
    }
}
?>