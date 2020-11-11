<?php
namespace app\index\controller;

use app\BaseController;
use think\facade\Request;
use think\facade\Db;

class Algorithm extends BaseController
{
    protected $data = [10,1,9,2,8,3,7,4,6,5];

    /**
     * 冒泡排序-相邻数字比较大小
     */
    public function bubble()
    {
        $data = $this->data;
        $len = count($data);
        for($a = 1;$a < $len;$a++){
            for($b = 0;$b < $len - $a;$b++){
                if($data[$b] > $data[$b+1]){
                    $temp = $data[$b];
                    $data[$b] = $data[$b+1];
                    $data[$b+1] = $temp;
                }
            }
        }
        
        return self::successReturn(['data'=>$data]);
    }

    /**
     * 选择排序-选择最小数字的位置
     */
    public function choice()
    {
        $data = $this->data;
        $len = count($data);
        for($a = 0;$a < $len - 1;$a++){
            $min = $a;
            for($b = $a + 1;$b < $len;$b++){
                $min = $data[$min] < $data[$b] ? $min : $b;
            }

            if($min != $a){
                $temp = $data[$a];
                $data[$a] = $data[$min];
                $data[$min] = $temp;
            }
        }

        return self::successReturn(['data'=>$data]);
    }

}