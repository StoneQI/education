<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 16-8-6
 * Time: 下午11:23.
 */

namespace Home\Service;

use Think\Model;

/**
 * 为 ReportController 提供服务
 * @package Home\Service
 */
class ReportService
{

    /**
     * 根据参数返回学生信息。
     *
     * @param $startTime
     * @param $endTime
     * @param $studentName
     * @param $status
     */
    public function getStudentInfo($startTime, $endTime, $studentName, $status)
    {
        $signInfo = M('sign_info');

        //根据学生名模糊查询学生ID
        if ($studentName) {
            $student = M('student');
            $map['student_name'] = array('like','%'.$studentName.'%');
            $studentIds = $student->where($map)->select();
            foreach ($studentIds as $key => $value) {
                $studentId[] = $value['student_id'];
            }
            if ($studentId){
                $studentSql['sign_info.student_id'] = array('in', $studentId);
            }else{
                return "";
            }

        }

        //查询签到ID
        if ($startTime or $endTime) {
            $startTimeSql = $startTime ? "where date >=" . $startTime : "";
            $endTimeSql = $endTime ? "and date <=" . $startTime : "";
            $Model = new Model(); // 实例化一个model对象 没有对应任何数据表$Model
            $signIds = $Model->query("select sign_id from sign " . $startTimeSql . " " . $endTimeSql . ";");

            foreach ($signIds as $key => $value) {
                $signId[] = $value['sign_id'];
            }
            if ($signId){
                $studentSql['sign_info.sign_id'] = array('in', $signId);
            }else{
                return "";
            }
        }



        $status ? $studentSql['sign_info.sign_info_status'] = $status : "";
        if ($studentSql) {
            $results = $signInfo->join('student ON sign_info.student_id = student.student_id')->join('sign ON sign_info.sign_id = sign.sign_id');
            $results = $results->where($studentSql)->select();
        } else {
            $results = $signInfo->join('student ON sign_info.student_id = student.student_id')->join('sign ON sign_info.sign_id = sign.sign_id');
            $results = $signInfo->select();
        }
        $teacher = M('teacher');
        foreach ($results as $key=>$value){
            $teacher->find($value['teacher_id']);
            $results[$key]['teacher_name'] = $teacher->teacher_name;
        }


        return $results;
    }

    private function andFilterWhere(array $condition)
    {

        if ($condition !== []) {
            $this->andWhere($condition);
        }
        return $this;
    }

}
