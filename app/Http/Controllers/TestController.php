<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddCartRequest;
use App\Services\CartService;

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

    /**
     * @param AddCartRequest $request
     * @return array
     */
    public function add(AddCartRequest $request)
    {
        $this->cartService->add($request->input('sku_id'), $request->input('amount'));

        return [];
    }
}
