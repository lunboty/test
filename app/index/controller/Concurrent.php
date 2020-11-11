<?php
namespace app\index\controller;

use app\BaseController;
use think\facade\Request;
use think\facade\Db;

class Concurrent extends BaseController
{

    /**
     * 悲观锁
     */
    public function pessimistic()
    {
        Db::startTrans();
        try{
            $data = Db::table('goods')->where(['id'=>1])->lock(true)->find();
            if(empty($data)){
                Db::rollback();
                return self::errorReturn(['msg'=>'商品不存在']);
            }
            if($data['stock'] == 0){
                Db::rollback();
                return self::errorReturn(['msg'=>'库存不足']);
            }

            Db::table('goods')->where(['id'=>1])->update(['stock'=>$data['stock'] - 1]);
            Db::commit();
            return self::successReturn(['msg'=>'购买成功']);
        }catch(\Exception $e){
            Db::rollback();
            return self::errorReturn(['msg'=>'购买失败']);
        }
    }

    /**
     * 乐观锁
     */
    public function optimistic()
    {
        $data = Db::table('goods')->where(['id'=>1])->find();
        if(empty($data)){
            return self::errorReturn(['msg'=>'商品不存在']);
        }
        if($data['stock'] == 0){
            return self::errorReturn(['msg'=>'库存不足']);
        }

        $result = Db::table('goods')->where(['id'=>1,'version'=>$data['version']])->update(['stock'=>$data['stock'] - 1,'version'=>$data['version'] + 1]);
        if(!$result){
            return self::errorReturn(['msg'=>'购买失败']);
        }
        
        return self::successReturn(['msg'=>'购买成功']);
    }

    /**
     * 创建商品队列
     */
    public function goodsQueue()
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1','6379');
        $redis->auth('123456');

        $goods = Db::table('goods')->select();
        foreach($goods as $k=>$v){
            $redis->del('goods'.$v['id']);
            for($i=0;$i<$v['stock'];$i++){
                $redis->lpush('goods'.$v['id'],1);
            }
        }

        return self::successReturn(['msg'=>'创建成功']);
    }

    /**
     * 商品秒杀
     */
    public function goodsSeckill()
    {
        $param = Request::param();

        $redis = new \Redis();
        $redis->connect('127.0.0.1','6379');
        $redis->auth('123456');

        $result = $redis->lpop('goods'.$param['goods_id']);
        if(!$result){
            return self::errorReturn(['msg'=>'库存不足']);
        }

        $result = Db::table('goods')->where('id',$param['goods_id'])->dec('stock',1)->update();
        if(!$result){
            return self::errorReturn(['msg'=>'购买失败']);
        }

        return self::successReturn(['msg'=>'购买成功']);
    }

    // 日志
    protected static function log($content)
    {
        $handle = fopen('log.txt','a+');
        fwrite($handle,$content."\r\n");
        fclose($handle);
    }

}
