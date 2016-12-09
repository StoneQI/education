<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 16-8-6
 * Time: 下午11:23.
 */

namespace Home\Controller;

use Think\Controller;
use Home\Service\ReportService;


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

    public function student_info()
    {
        $this->display('student_info');
    }

    public function getStudentInfo()
    {
        $startTime = I('post.startTime', 'flase');
        $endTime = I('post.endTime', 'flase');
        $studentName = I('post.studentName', '0');
        $status = I('post.status', '0');
        $reportService = new ReportService();
        $reportService->getStudentInfo($startTime, $endTime, $studentName, $status);
    }

}
