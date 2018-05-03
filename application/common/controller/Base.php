<?php
/**
 * Created by PhpStorm.
 * User: szh
 * Date: 2018/4/25
 * Time: 14:11
 */
namespace app\common\controller;

use think\Controller;

class Base extends Controller {
    public function __construct(){
        parent::__construct();
        //TODO 安装检测
        $lockFile = './install.lock';
        if(!file_exists($lockFile)){
            file_put_contents($lockFile, date('Y-m-d H-i-s'));
        }

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
}