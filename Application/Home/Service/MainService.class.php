<?php
/**
 * Created by PhpStorm.
 * User: StoneQi
 * Date: 2016/12/14
 * Time: 18:36
 */

namespace Home\Service;


class MainService
{
    public function getWarringStudent()
    {
        $course = M('course');
        $results = $course->join('student ON course.course_student_id = student.student_id');
        $map['remain_times'] = array('elt',5);
        $results = $results->where($map)->select();
        $items = M('items');
        foreach ($results as $key=>$value){
            $items->find($value['course_item_id']);
            $results[$key]['items_name'] = $items->items_name;
        }
        return $results;

    }
}