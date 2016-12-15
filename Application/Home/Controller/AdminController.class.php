<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 16-8-6
 * Time: 下午11:23.
 */

namespace Home\Controller;

use Think\Controller;

class AdminController extends Controller
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

    public function teacher($value = '')
    {
        $this->display('addteacher');
    }

    public function readallteacher()
    {
        $page = I('post.page');
        $pagesize = I('post.rows');
        $sort = I('post.sort');
        $order = I('post.order');
        if (isset($_POST['teacher_name']) && !empty($_POST['teacher_name'])) {
            $condition['teacher_name|teacher_no'] = array('like', '%'.I('post.teacher_name').'%'); //姓名或学号搜索
        }
        $condition['extend'] = 0;
        $teacher = M('teacher');
        $result = $teacher->where($condition)->order($sort.' '.$order)->page($page, $pagesize)->select();
        $json = json_encode($result);

        $total = $teacher->where($condition)->count();
        echo '{"total":'.$total.',"rows":'.$json.'}';
    }

    public function addteacher_info()
    {
        $row = I('post.row');
        $data['teacher_no'] = $row['teacher_no'];
        $data['teacher_birthday'] = $row['teacher_birthday'];
        $data['teacher_education'] = $row['teacher_education'];
        $data['teacher_id_card'] = $row['teacher_id_card'];
        $data['teacher_major'] = $row['teacher_major'];
        $data['teacher_name'] = $row['teacher_name'];
        $data['teacher_password'] = $row['teacher_password']; //grade_name属性的value值是grade_id
        $data['teacher_remark'] = $row['teacher_remark'];
        $data['teacher_salary'] = $row['teacher_salary'];
        $data['teacher_sex'] = $row['teacher_sex'];
        $teacher = M('teacher');
        $result = $teacher->add($data);
        if (!$result) {
            echo '失败';
        } else {
            echo '成功';
        }
    }

    public function updateteacher_info()
    {
        $row = I('post.row');
        $data['teacher_no'] = $row['teacher_no'];
        $data['teacher_birthday'] = $row['teacher_birthday'];
        $data['teacher_education'] = $row['teacher_education'];
        $data['teacher_id_card'] = $row['teacher_id_card'];
        $data['teacher_major'] = $row['teacher_major'];
        $data['teacher_name'] = $row['teacher_name'];
        $data['teacher_password'] = $row['teacher_password']; //grade_name属性的value值是grade_id
        $data['teacher_remark'] = $row['teacher_remark'];
        $data['teacher_salary'] = $row['teacher_salary'];
        $data['teacher_sex'] = $row['teacher_sex'];
        $teacher = M('teacher');
        $condition['teacher_id'] = $row['teacher_id'];
        $result = $teacher->where($condition)->save($data);
        if (!$result) {
            echo '失败';
        } else {
            echo '成功';
        }
    }

    public function deleteteacher_info()
    {
        $ids = I('post.ids');
        $teacher = M('teacher');
        $condition['teacher_id'] = array('IN', "$ids");
        $result = $teacher->where($condition)->delete();
        echo $result;
    }

    //显示学生管理视图
    public function student($value = '')
    {
        $this->display('addstudent');
    }

    public function readallstudent()
    {
        $page = I('post.page');
        $pagesize = I('post.rows');
        $sort = I('post.sort');
        $order = I('post.order');
        if (isset($_POST['student_name']) && !empty($_POST['student_name'])) {
            $condition['student_name'] = array('like', '%'.I('post.student_name').'%'); //姓名或学号搜索
        }
        $student = M('student');
        $result = $student->where($condition)->order($sort.' '.$order)->page($page, $pagesize)->select();
        $json = json_encode($result);

        $total = $student->where($condition)->count();
        echo '{"total":'.$total.',"rows":'.$json.'}';
    }

    public function addstudent_info()
    {
        $row = I('post.row');
        $data['student_birthday'] = $row['student_birthday'];
        $data['student_tel'] = $row['student_tel'];
        $data['student_name'] = $row['student_name'];
        $data['student_remark'] = $row['student_remark'];
        $data['student_sex'] = $row['student_sex'];
        $student = M('student');
        $result = $student->add($data);
        if (!$result) {
            echo '失败';
        } else {
            echo '成功';
        }
    }

    public function updatestudent_info()
    {
        $row = I('post.row');
        $data['student_birthday'] = $row['student_birthday'];
        $data['student_tel'] = $row['student_tel'];
        $data['student_name'] = $row['student_name'];
        $data['student_remark'] = $row['student_remark'];
        $data['student_sex'] = $row['student_sex'];
        $student = M('student');
        $condition['student_id'] = $row['student_id'];
        $result = $student->where($condition)->save($data);
        if (!$result) {
            echo '失败';
        } else {
            echo '成功';
        }
    }

    public function deletestudent_info()
    {
        $ids = I('post.ids');
        $student = M('student');
        $condition['student_id'] = array('IN', "$ids");
        $result = $student->where($condition)->delete();
        echo $result;
    }

    //缴费管理
    public function pay_course()
    {
        $this->display('addcourse');
    }

    public function select_item()
    {
        $student_id = I('get.student_id');
        $this->assign('student_id', $student_id);
        $this->display('select_item');
    }

    public function select_item_submit()
    {
        $student_id = I('post.student_id');
        $items_ids = I('post.items_ids');
        $select_item_names = I('post.select_item_names');
        $pay_payable = I('post.pay_payable');
        $pay_favor = I('post.pay_favor');
        $pay_total = I('post.pay_total');
        $course = M('course');
        $items = M('items');
        $items_id = explode(',', $items_ids);
        foreach ($items_id as $key => $value) {
            $items_times = $items->field('items_times')->where('items_id=%d', intval($value))->select();
            $course_data['remain_times'] = intval($items_times[0]['items_times']);
            $result = $course->where('course_item_id=%d and course_student_id=%d',array(intval($value),$student_id))->find();
            if ($result) {
                $course->remain_times = $course->remain_times+ $course_data['remain_times'];
                 $course->course_datetime = date('Y-m-d H:i:s');
                $course->save();
            }else{
                $course_data['course_item_id'] = intval($value);
                $course_data['course_student_id'] = $student_id;
                $course_data['course_datetime'] = date('Y-m-d H:i:s');
                $course_results = $course->data($course_data)->add();
            }
        }
        $pay = M('pay');
        $pay_data['pay_student_id'] = $student_id;
        $pay_data['pay_course_name'] = $select_item_names;
        $pay_data['pay_payable'] = $pay_payable;
        $pay_data['pay_favor'] = $pay_favor;
        $pay_data['pay_total'] = $pay_total;
        $pay_data['pay_datetime'] = date('Y-m-d H:i:s');
        $pay_results = $pay->data($pay_data)->add();
        if ($pay_results and $course_results) {
            echo '成功';
        } else {
            echo '失败';
        }
    }

    public function pay_course_select($value = '')
    {
        $this->display('coursemanagment');
    }

    public function readallcourse()
    {
        $page = I('post.page');
        $pagesize = I('post.rows');
        $sort = I('post.sort');
        $order = I('post.order');
        if (isset($_POST['student_name']) && !empty($_POST['student_name'])) {
            $condition['student_name'] = array('like', '%'.I(post.student_name).'%'); //姓名或学号搜索
        }
        $course = M('course');
        $items = M('items');
        $students = M('student');
        $result = $course->where($condition)->order($sort.' '.$order)->page($page, $pagesize)->select();

        foreach ($result as $key => $value) {
            $item = $items->where('items_id=%d', $value['course_item_id'])->select();
            $result[$key]['items_name'] = $item[0]['items_name'];
            $student = $students->where('student_id=%d', $value['course_student_id'])->select();
            $result[$key]['student_name'] = $student[0]['student_name'];
        }
        $json = json_encode($result);
        $total = $course->where($condition)->count();
        echo '{"total":'.$total.',"rows":'.$json.'}';
    }

    public function deletecourse_info()
    {
        $ids = I('post.ids');
        $student = M('course');
        $condition['course_id'] = array('IN', "$ids");
        $result = $student->where($condition)->delete();
        echo $result;
    }

    public function updatecourse_info()
    {
        $row = I('post.row');

        $data['course_item_id'] = $row['course_item_id'];
        $data['course_student_id'] = $row['course_student_id'];
        $data['remain_times'] = $row['remain_times'];
        $data['course_datetime'] = $row['course_datetime'];
        $course = M('course');
        $condition['course_id'] = $row['course_id'];
        $result = $course->where($condition)->save($data);
        if (!$result) {
            echo '失败';
        } else {
            echo '成功';
        }
    }

    //历史缴费管理
    public function pay_managment($value = '')
    {
        $this->display('paymanagment');
    }

    public function pay_info()
    {
        $student_id = I('get.student_id');
        $this->assign('student_id', $student_id);
        $this->display('pay_info');
    }

    public function readallpay()
    {
        $page = I('post.page');
        $pagesize = I('post.rows');
        $sort = I('post.sort');
        $order = I('post.order');
        if (isset($_GET['student_id']) && !empty($_GET['student_id'])) {
            $condition['pay_student_id'] = I('get.student_id'); //姓名或学号搜索
        }
        $course = M('pay');
        $result = $course->where($condition)->order($sort.' '.$order)->page($page, $pagesize)->select();
        $json = json_encode($result);
        $total = $course->where($condition)->count();
        echo '{"total":'.$total.',"rows":'.$json.'}';
    }

    public function addpay_info()
    {
        $row = I('post.row');
        $pay_data['pay_student_id'] = I('get.student_id');
        $pay_data['pay_course_name'] = '';
        $pay_data['pay_payable'] = 0;
        $pay_data['pay_favor'] = 0;
        $pay_data['pay_total'] = 0;
        $pay_data['pay_refund'] = $row['pay_refund'];
        $pay_data['pay_remark'] = $row['pay_remark'];
        $pay_data['pay_datetime'] = date('Y-m-d H:i:s');
        $pay = M('pay');
        $result = $pay->add($pay_data);
        if (!$result) {
            echo '失败';
        } else {
            echo '成功';
        }
    }

    public function deleteitem_info()
    {
        $ids = I('post.ids');
        $items = M('Items');
        $condition['items_id'] = array('IN', "$ids");
        $result = $items->where($condition)->delete();
        echo $result;
    }

    public function read_all_teacher()
    {
        $teacher = M('Teacher');
        $result = $teacher->field('teacher_id,teacher_name,selected')->select();
        $json = json_encode($result);
        echo $json;
    }
    //历史缴费管理
    public function item($value = '')
    {
        $this->display('additem');
    }

    public function readallitem()
    {
        $page = I('post.page');
        $pagesize = I('post.rows');
        $sort = I('post.sort');
        $order = I('post.order');
        if (isset($_POST['items_name']) && !empty($_POST['items_name'])) {
            $condition['items_name'] = array('like', '%'.I('post.items_name').'%'); //姓名搜索
        }
        $condition['extend'] = 0;

        $items = M('Items');
        $result = $items->join('teacher on items.items_teacher_id=teacher.teacher_id')->where($condition)->order($sort.' '.$order)->page($page, $pagesize)->select();
        $json = json_encode($result);

        $total = $items->where($condition)->count();
        echo '{"total":'.$total.',"rows":'.$json.'}';
    }

    public function additem_info()
    {
        $row = I('post.row');
        $data['items_name'] = $row['items_name'];
        $data['items_times'] = $row['items_times'];
        $data['items_price'] = $row['items_price'];
        $data['items_teacher_id'] = $row['teacher_name'];
        $items = M('items');
        $result = $items->add($data);
        if (!$result) {
            echo '失败';
        } else {
            echo '成功';
        }
    }

    public function updateitem_info()
    {
        $row = I('post.row');
        $data['items_name'] = $row['items_name'];
        $data['items_times'] = $row['items_times'];
        $data['items_price'] = $row['items_price'];
        $data['items_teacher_id'] = $row['teacher_name'];
        $data['items_id'] = $row['items_id'];
        $items = M('Items');
        $result = $items->save($data);
        if (!$result) {
            echo '失败';
        } else {
            echo'成功';
        }
    }
    //课程时间管理
    public function schedule($value = '')
    {
        $this->display('addschedule');
    }

    public function read_all_items()
    {
        $items = M('Items');
        $result = $items->select();

        $json = json_encode($result);
        echo $json;
    }

    public function readallschedule()
    {
        $page = I('post.page');
        $pagesize = I('post.rows');
        $sort = I('post.sort');
        $order = I('post.order');
        $schedule = M('Schedule');
        $result = $schedule->join('items on items.items_id=schedule.schedule_items_id')->order($sort.' '.$order)->page($page, $pagesize)->select();
        $json = json_encode($result);
        echo $json;
    }

    public function addschedule_info()
    {
        $data['schedule_weekend'] = I('post.schedule_weekend');
        $data['schedule_time'] = I('post.schedule_time');

        $items_ids = explode('/', $_POST['schedule_items_id']);
        $schedule = M('Schedule');
        foreach ($items_ids as $key => $value) {
            if ($value != '') {
                $data['schedule_items_id'] = $value;
                $result = $schedule->add($data);
            }
        }
        if (!$result) {
            echo '失败';
        } else {
            echo '成功';
        }
    }
    //这个函数需修改很多
     public function updateschedule_info()
     {
         $data['schedule_weekend'] = I('post.schedule_weekend');
         $data['schedule_time'] = I('post.schedule_time');
         $str_id = $_POST['schedule_items_id'];

         //删除空字符串
         $str_hidden = $_POST['select_updata_hidden'];
         $str_hidden = str_replace(' ', '', $str_hidden);
         $arr_hidden = explode('@', $str_hidden);
         $arr1 = array();
         foreach ($arr_hidden as $key => $value) {
             if (!empty($value)) {
                 array_push($arr1, $value);
             }
         }
        //先删除数据库已存在数据
        $schedule = M('schedule');
         var_dump($arr1);
         foreach ($arr1 as $key => $value) {
             if (!empty($value)) {
                 $result_del = $schedule->delete($value);
                 if (!$result_del) {
                     echo '删除失败';
                 } else {
                     echo'删除成功';
                 }
             }
         }
                // 然后添加新数据
                // 先删除空字符串
            $str_id = str_replace(' ', '', $str_id);
         $arr_id = explode('/', $str_id);
         $arr2 = array();
         foreach ($arr_id as $key => $value) {
             if (!empty($value)) {
                 array_push($arr2, $value);
             }
         }
         var_dump($arr2);
         foreach ($arr2 as  $key => $value) {
             if (!empty($value)) {
                 $data['schedule_items_id'] = $value;
                 $result_add = $schedule->add($data);
                 if (!$result_add) {
                     echo '添加失败';
                 } else {
                     echo'添加成功';
                 }
             }
         }
     }

    public function deleteschedule_info()
    {
        $ids = I('post.ids');
        $schedule = M('schedule');
        $condition['schedule_id'] = array('IN', "$ids");
        $result = $schedule->where($condition)->delete();
        echo $result;
    }

    public function edit_schedule()
    {
        $this->display('edit_schedule');
    }

    public function add_schedule()
    {
        $this->display('add_schedule');
    }
    //读取所有

   public function read_all_items_name()
   {
       $Items = M('Items');
       $result = $Items->field('items_id,items_name')->select();
       $json = json_encode($result);
       echo $json;
   }

    public function read_all_time()
    {
        $schedule = M('Schedule');
        $result = $schedule->select();
        $json = json_encode($result);
        echo $json;
    }

    public function read_select_items_name()
    {
        $schedule = M('Schedule');
        $condition['schedule_weekend'] = I('get.schedule_weekend');
        $condition['schedule_time'] = I('get.schedule_time');
        $result = $schedule->where($condition)->select();
        $json = json_encode($result);
        echo $json;
    }

    public function read_part_time()
    {
        $schedule = M('Schedule');
        $condition['schedule_weekend'] = I('get.schedule_weekend');
        $result = $schedule->where($condition)->select();
        $json = json_encode($result);
        echo $json;
    }
}
