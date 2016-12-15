<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 16-8-6
 * Time: 下午11:23
 */

namespace Home\Controller;

use Think\Controller;
use Home\Service\TeacherService;


class TeacherController extends Controller
{
    //显示教师管理视图
    //
    public function _initialize()
    {
        if (session("user_name") == null) {
            session('[destroy]');
            echo ("<script type='text/javascript'  > alert('违法操作'); window.location.reload();</script>");
            exit();
        }
    }

    public function teacher($value = '')
    {

        $this->display("addteacher");
    }
    //以下为修改区

    private function toJson($code = '200', $message = '数据正确', $data)
    {
        $pushdata = array(); //定义新数组
        $pushdata['code'] = $code;
        $pushdata['message'] = $message;
        $pushdata['data'] = $data;
        return json_encode($pushdata, JSON_UNESCAPED_UNICODE); //
    }

    public function getDate()
      {
          $data = array();
              for ($i = 0; $i < 7; $i++) {
                   $data[$i]['date'] = date("Y年m月d日",time()-24*60*60*$i);
                   $week= date("w",time()-24*60*60*$i)-1;
                   if ( $week ==-1) {
                       $week =6;
                   }
                   $data[$i]['week'] = $week;
                   if ($i ==0) {
                       $data[$i]['active'] = $week;
                   }
              }
               if (empty($data)) {
                      $code = "400";
                      $message = '数据错误';
                  } else {
                      $code = "200";
                      $message = '  ';
                  }
                  $getdate = array(); //定义新数组
                  $result = $this->toJson($code, $message, $data);
                  echo $result;
      }

    public function getSchedule()
    {
        $judge = true; //判断返回的正确性
        $data = array();
        $week = I("get.week"); //返回数据为星期
        $schedule = M("schedule");
        $require_schedule['schedule_weekend'] = $week;
        $result_schedule = $schedule->where($require_schedule)->select();
        if (!$result_schedule) {
            $judge = false; //判断返回的正确性
        }
        $schedule_id = array(); //id值
        $schedule_time = array(); //课程时间值
        $schedule_items_id = array(); //课程表的id值
        foreach ($result_schedule as $key => $value) {
            $arr = $value;
            foreach ($arr as $key => $value) {
                if ($key == 'schedule_id') {array_push($schedule_id, $value);}
                if ($key == 'schedule_time') {array_push($schedule_time, $value);}
                if ($key == 'schedule_items_id') {array_push($schedule_items_id, $value);}
            }
        }
        //通过schedule_items_id取课程表的名字
        $items = M("items");
        $items_name = array(); //存储课程的名字
        foreach ($schedule_items_id as $key => $value) {
            $id = $value; //查找的id
            $result_items = $items->find($id);
            if (!$result_items) {
                $judge = false; //判断返回的正确性
            }
            foreach ($result_items as $key => $value) {
                if ($key == 'items_name') {array_push($items_name, $value);}
            }
        }
        //通过schedule_id取签到的状态
        $sign_status = M("sign");
        $status = array(); //存储签到的状态
        $teacher_id = array(); //存储老师的id
        $sign_schedule_id = array(); //存储状态表对应的schedule_id
        foreach ($schedule_id as $key => $value) {
            $id = $value; //查找的id
            $require_sign['schedule_id'] = $id;
            $result_sign = $sign_status->where($require_sign)->select();
            if ($result_sign) {
                foreach ($result_sign as $key => $value) {
                    $arr = $value;
                    foreach ($arr as $key => $value) {
                        if ($key == 'status') {array_push($status, $value);}
                        if ($key == 'teacher_id') {array_push($teacher_id, $value);}
                        if ($key == 'schedule_id') {array_push($sign_schedule_id, $value);}
                    }
                }
            }
        }
        //通过teacher_id取课程教师的名字
        $teacher = M("teacher");
        $teacher_name = array(); //存储老师的名字
        foreach ($teacher_id as $key => $value) {
            $id = $value; //查找的id
            $result_teacher = $teacher->find($id);
            foreach ($result_teacher as $key => $value) {
                if ($key == 'teacher_name') {array_push($teacher_name, $value);}
            }
        }

        //综合数据
        foreach ($schedule_id as $key => $value) {
            $data[$key]['time'] = $schedule_time[$key];
            $data[$key]['schedule_id'] = $schedule_id[$key];
            $data[$key]['items'] = $items_name[$key];
            $data[$key]['items_id'] = $schedule_items_id[$key];
            foreach ($sign_schedule_id as $add_key => $add_value) {
          //通过存储状态表对应的schedule_id添加状态
                if ($add_value == $data[$key]['schedule_id']) {
                    $data[$key]['status'] = $status[$key];
                    $data[$key]['teacher_name'] = $teacher_name[$key];
                }
            }

        }
        if ($judge = false) {
            $code = "400";
            $message = '数据错误';
        } else {
            $code = "200";
            $message = '  ';
        }
        $result = $this->toJson($code, $message, $data);
        echo "$result";
    }

    public function sign()
    {
      //测试数据
         $schedule_id = I(post.schedule_id);
          $status =I(post.status);
         $date =I(post.date);
         $items_id =I(post.items_id);
          $student_id  =I(post.student_id);

        $teacherService = new TeacherService();

       $result = $teacherService->sign($schedule_id,$status, $date,$items_id,$student_id);
       if($result){
         $result_end = $this->toJson(200, "");
         echo "$result_end";
       }


    }

    public function allstudent()
    {
        $items_id = I('get.items_id');

        $datas = $this->getStudent($items_id);
        $result = $this->toJson(200, "", $datas);
        echo "$result";

    }
    private function getStudent($items_id='')
    {
      $require["course_item_id"] = $items_id;
      $course = M("course");
      $student_id = array(); //存储学生的id
      $result = $course->where($require)->select();
      if (!$result) {
            ;
      } else {
          foreach ($result as $value) {
              $pop_student = $value;
              foreach ($pop_student as $key => $value) {
                  if ($key == 'course_student_id') {array_push($student_id, $value);}
              }
          }
      }
      //用学生的id来查找学生的姓名
      $student = M("student");
      $datas = array();
      $student_name = array(); //存储学生的名字
      foreach ($student_id as $key => $value) {
          $id = $value; //查找的id
          $result_student = $student->find($id);
          $data = array();
          $data['student_id']=$value;
          $data['student_name']=$result_student["student_name"];
          $data['status'] = 0;
          array_push($datas,$data);
      }
      return $datas;

    }


}
