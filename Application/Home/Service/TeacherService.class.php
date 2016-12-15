<?php
namespace Home\Service;

class TeacherService
{
    public function sign($schedule_id,$status, $date,$items_id,$student_id)
    {

        $date = $this->signAnalysisTime($date);//首先进行时间解析
        if ($status == 0 ) {
             $result = $this->signInsert($schedule_id,$status, $date);//插入数据
             return $result;
        }else if($status == 1){
            $signId = $this->signInsert($schedule_id,$status, $date);//插入数据
            $studentIds = $this->getStudentId($items_id);//得到学生的id
             $result = $this->signInfoInsert($signId,$studentIds);//将无缺课数据加入sign_info数据库中status为0
             return $result;
        }else if($status == 2){
           $signId = $this->signInsert($schedule_id,$status, $date);//插入数据
           $result = $this->signInfoInsert($signId,$student_id);//加入sign_info数据库中
           return  $result;
        }
    }

    /**
     * 插入签到信息，返回 SignId
     * @param  int  $schedule_id [description]
     * @param  int $status      [description]
     * @param  string $date        [description]
     * @return int   $result            [description]
     */
    private function signInsert($schedule_id,$status, $date)//插入数据sign
    {
        $data['schedule_id'] = $schedule_id;
        $data['sign_date'] = $date;
        $data["teacher_id"] = session('teacher_id');
        $data['sign_status'] = $status;
        $sign = M("sign");
        $result = $sign->add($data);
        return  $result;

    }
    /**
     * [signInfoInsert description]
     * @param  int $sign_id    [description]
     * @param  int $student_id [description]
     * @return int $result             [description]
     */
    private function signInfoInsert($sign_id,$student_id)//插入数据signInfo
    {
        $data['sign_id'] = $sign_id;
        $signInfo = M("sign_info");
        foreach ($student_id as $key => $value) {
            $data['student_id'] = $value['student_id'];
            $data['sign_info_status'] =  $value['sign_info_status'];
            $result = $signInfo>add($data);
        }
        return $result;
    }
    /**
     *
     * @param  int       $items_id
     * @return array           $results
     */
    private function getStudentId($items_id)//得到学生的id
    {
            $require['course_item_id'] =$items_id;//取得学生id
            $course = M("Course");
            $student_id = array(); //存储学生的id
            $result = $course->where($require)->select();
            $results = array();//存储所需的数据
             foreach ($result as $key => $value) {
                 $results[$key]['course_student_id'] = $value['course_student_id'];
                 $results[$key]['status'] = 0;
             }
             return $results;
    }
    /**
     * [signAnalysisTime description]
     * @param  string $date [description]
     * @return string  $date     [description]
     */
    private function signAnalysisTime($date) //时间数据解析
    {
       $date = str_replace('年','-',$date);
       $date = str_replace('月','-',$date);
       $date = str_replace('日','',$date);
      return $date;
    }
}

