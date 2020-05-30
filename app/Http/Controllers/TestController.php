<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\CartService;
use Ar414\RedisLock;

class TestController extends Controller
{
    protected $cartService;

    /**
     * TestController constructor.
     * @param CartService $cartService
     */
    public function __construct(CartService $cartService)
    {
        // 利用 Laravel 的自动解析功能注入 CartService 类
        $this->cartService = $cartService;
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return  response()->json(["code"=>"0","msg"=>"success","data"=>['测试数据']]);

    }

    public function change_save(Request $request)
    {
        //悲观锁
        $redis = new \Redis();
        $redis->connect('127.0.0.1','6397');

        $lockTimeOut = 5;
        $redisLock = new RedisLock($redis,$lockTimeOut);

        $lockKey = 'lock:user:wallet:uid:1001';
        $lockExpire = $redisLock->getLock($lockKey); //设置每个锁的最长使用时间

        try{
            if(!$lockExpire||$lockExpire<time()){
                $redisLock->releaseLock($lockKey,$lockExpire);
                throw new \Exception('Busy Lock');
            }else{
                $data = [
                    'id'=>$request->get("id"),
                ];

                $pro = new Product();
                $num =  $pro->change_save($data);
                echo $num;
                $redisLock->releaseLock($lockKey,$lockExpire);
            }
        }catch (Exception $e){
                echo $e->getMessage();
        }
    }


}
