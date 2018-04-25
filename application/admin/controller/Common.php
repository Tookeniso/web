<?php
/**
 * Created by PhpStorm.
 * User: szh
 * Date: 2018/4/23
 * Time: 9:20
 */
namespace app\admin\controller;

use app\common\controller\Base;
use think\facade\Cache;
use think\facade\Session;
use app\admin\model\Users;

class Common extends Base{

    public function __construct(){
        parent::__construct();
    }

    /**
     * ajax返回信息
     * @param $code
     * @param $info
     * @param $data
     * @param $url
     * @author szh
     */
    public function ajaxReturn($code, $info, $data, $url){
        if(!is_array($data) && empty($url)){
            $url = $data;
            $data = [];
        }
        $return = [
            'code' => $code,
            'info' => $info,
            'data' => $data,
            'url' => $url,
        ];
        echo json_encode($return);
        exit;
    }

    /**
     * ajax成功返回
     * @param string $info
     * @param $data
     * @param $url
     * @author szh
     */
    public function ajaxSuccess($info = '操作成功', $data=[], $url=''){
        $this->ajaxReturn(1, $info, $data, $url);
    }

    /**
     * ajax失败返回
     * @param string $info
     * @param $data
     * @param $url
     * @author szh
     */
    public function ajaxError($info = '操作失败', $data=[], $url=''){
        $this->ajaxReturn(0, $info, $data, $url);
    }

    /**
     * 清空数据缓存
     * @author szh
     */
    public function clearCache(){
        Cache::clear();
    }

    /**
     * 清空session
     * @author szh
     */
    public function logout(){
        Session::clear();
    }


    /**
     * 登录界面/登录
     * @return mixed
     * @author szh
     */
    public function login(){
        if(request()->isAjax()){
            if(session('admin_user'))
                $this->ajaxSuccess('登录成功', url('admin/index'));
            //验证数据
            $data = input('', [], 'text');
            $validate = validate('admin/users');

            if(!$validate->scene('login')->check($data))
                $this->ajaxError($validate->getError());

            //登录
            $userModel = new Users();
            if(!$userModel->login($data['phone'], $data['pass']))
                $this->ajaxError($userModel->getError());

            $this->ajaxSuccess('登录成功', url('admin/index'));
        }
        if(session('admin_user'))
            $this->redirect(url('admin/index'));
        return $this->fetch();
    }

    /**
     * 验证码
     * @return \think\response\Json
     * @author szh
     */
    public function verify(){
        if(request()->isAjax()){
            $url = captcha_src();
            $this->ajaxSuccess('获取成功',$url);
        }
        $this->ajaxError('获取失败');
    }

}