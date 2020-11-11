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
    public function bubbleSort()
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
    public function choiceSort()
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

    /**
     * 插入排序-从后往前对比插入
     */
    public function insertSort()
    {
        $data = $this->data;
        $len = count($data);
        for($a = 1;$a < $len;$a++){
            $temp = $data[$a];
            for($b = $a - 1;$b >= 0;$b--){
                if($temp < $data[$b]){
                    $data[$b+1] = $data[$b];
                    $data[$b] = $temp;
                }else{
                    break;
                }
            }
        }

        return self::successReturn(['data'=>$data]);
    }

    /**
     * 快速排序-以基准左右大小分组递归合并
     */
    public function quickSort()
    {
        $data = $this->data;
        $data = $this->quickSortRecursion($data);

        return self::successReturn(['data'=>$data]);
    }

    protected function quickSortRecursion($data)
    {
        $len = count($data);

        if($len <= 1){
            return $data;
        }

        $left = [];
        $right = [];

        for($i = 1;$i < $len;$i++){
            if($data[$i] < $data[0]){
                $left[] = $data[$i];
            }else{
                $right[] = $data[$i];
            }
        }

        $left = $this->quickSortRecursion($left);
        $right = $this->quickSortRecursion($right);

        return array_merge($left,[$data[0]],$right);
    }

    /**
     * 归并排序-以长度平均左右分组递归合并
     */
    public function mergeSort()
    {
        $data = $this->data;
        $data = $this->mergeSortRecursion($data);

        return self::successReturn(['data'=>$data]);
    }

    protected function mergeSortRecursion($data)
    {
        $len = count($data);

        if($len <= 1){
            return $data;
        }

        $mid = ceil($len / 2);

        $left = array_slice($data,0,$mid);
        $right = array_slice($data,$mid);

        $left = $this->mergeSortRecursion($left);
        $right = $this->mergeSortRecursion($right);

        $center = [];

        while(count($left) && count($right)){
            $center[] = $left[0] < $right[0] ? array_shift($left) : array_shift($right);
        }

        return array_merge($center,$left,$right);
    }


}