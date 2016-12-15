<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 16-8-6
 * Time: 下午11:23.
 */

namespace Home\Controller;

use Org\Util\Date;
use Think\Controller;
use Home\Service\ReportService;

/**
 * 报表控制器，生成报表方法
 * @package Home\Controller
 */
class ReportController extends Controller
{
    //显示教师管理视图

    public function _initialize()
    {
        if (session('user_name') == null) {
            session('[destroy]');
            echo "<script type='text/javascript'  > alert('违法操作'); window.location.reload();</script>";
            exit();
        }
    }

    /**
     * 显示学生信息查询界面
     */
    public function student_info()
    {
        $this->display('student_info');
    }
    /**
     * [getStudent description]
     * @param   $name [description]
     * @param  [type] $php  [description]
     * @return        [description]
     */
    public function getStudent($name ,$php)
    {
        $name = I('post.name');
        $reportService = new ReportService();
        $reportService->getStudent( $name,);
        return $aa;

    }

    /**
     * 根据前台Post数据查询学生数据，调用 ReportService 类
     * 返回 json 格式
     * {
     *  code:200,
     *  message:"",
     *  data:{
     *       student_name: ,
     *       student_id
     *
     *      }
     * }
     * @return json 学生信息
     */
    public function getStudentInfo()
    {
        /**
         * 查询开始时间
         * @var String 默认为0
         */
        $startTime = I('post.startTime', '0');
        /**
         * 查询结束时间
         * @var String 默认为0
         */
        $endTime = I('post.endTime', '0');
        /**
         * 查询学生名字，默认为0，未填写
         * @var String 默认为0
         */
        $studentName = I('post.name', '0');
        /**
         * 传入状态字符，0：未选择，1：缺课，2：请假，3：缺课+请假
         * @var Int 默认为0
         */

        $status = I('post.status', '0');

//        $startTime = \DateTime::createFromFormat('y-m-d',$startTime);
//        $endTime = \DateTime::createFromFormat('y-m-d',$endTime);
//        exit();
        //$startTime =

        $reportService = new ReportService();
        $data = $reportService->getStudentInfo($startTime, $endTime, $studentName, $status);
        echo json_encode($data);
        //echo $this->toJson('200','',$data);
    }

    /**
     * 对传入的参数进行json转码。
     *
     * @param $code
     * @param $message
     * @param $data
     * @return string
     */
    public function toJson($code,$message,$data)
    {
        $reposed['code'] = $code;
        $reposed['message'] = $message;
        $reposed['data'] = $data;
        return json_encode($reposed);
    }

}
