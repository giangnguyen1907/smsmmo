<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Http\Services\AdminService;
use App\Models\CmsPost;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Part to views for Controller
    protected $viewPart;
    // Route default for Controller
    protected $routeDefault;
    // Data response to view
    protected $responseData = [];

    /**
     * Xử lý các thông tin hệ thống trước khi đổ ra view
     * @author: ThangNH
     * @created_at: 2021/10/01
     */

    protected function responseView($view )
    {
        session_start();
        
        //session_destroy();
        
        //$routeDefault = $this->routeDefault ? $this->routeDefault : '' ;
        
        //if(!isset($_SESSION['user']['id']) || $_SESSION['user']['id'] <= 0){
            $_SESSION['user']['id'] = Auth::guard('admin')->user()->id;
            $_SESSION['user']['role'] = Auth::guard('admin')->user()->role;
            $_SESSION['user']['is_super_admin'] = Auth::guard('admin')->user()->is_super_admin;
            $_SESSION['user']['email'] = Auth::guard('admin')->user()->email;
        //}
        
        $this->responseData['admin_auth'] = Auth::guard('admin')->user();

        $comments = Comment::selectRaw('status, COUNT(id) as sum')->groupBy('status')->get();
        
        $cho = 0; $duyet = 0;$go =0;
        foreach($comments as $key){
        if($key->status == 1)
            $cho = $key->sum;
        if($key->status == 0)
            $duyet =  $key->sum;
        if($key->status == 2)
            $go =  $key->sum;
        }

        $tongsobinhluan['cho'] = $cho;
        $tongsobinhluan['duyet'] = $duyet;
        $tongsobinhluan['go'] = $go;
        $tongsobinhluan['tong'] =$cho + $duyet + $go;

        $count_route = array();
        /*
        if(Auth::guard('admin')->user()->is_super_admin == 1){
            $countPost = CmsPost::selectRaw('status, COUNT(id) as sum')->groupBy('status')->get();
        }else{
            $countPost = CmsPost::selectRaw('status, COUNT(id) as sum')->where('admin_created_id',Auth::guard('admin')->user()->id )->groupBy('status')->get();
        }

        foreach($countPost as $total_post){

            if($total_post->status == 'pending')
                $count_route['cms_posts?task=pending'] = $total_post->sum;
            if($total_post->status == 'waiting')
                $count_route['cms_posts?task=waiting'] = $total_post->sum;
            if($total_post->status == 'deactive')
                $count_route['cms_posts?task=deactive'] = $total_post->sum;
            if($total_post->status == 'rollback')
                $count_route['cms_posts?task=rollback'] = $total_post->sum;
            if($total_post->status == 'lock')
                $count_route['cms_posts?task=lock'] = $total_post->sum;
            if($total_post->status == 'draft')
                $count_route['cms_posts?task=draft'] = $total_post->sum;
            if($total_post->status == 'active')
                $count_route['cms_posts?task=active'] = $total_post->sum;
        }
        */
        //echo count($countPost);die;
        //dd($countPost);

        $count_route['comment'] = $cho;
        //dd($count_route);
        $this->responseData['tongsobinhluan'] = $tongsobinhluan;
        $this->responseData['count_route'] = $count_route;
        /**
         * Get all access menu to show in the sidebar by role of current User 
         */
        $this->responseData['accessMenus'] = AdminService::getAccessMenu();

        // App::setLocale('en');

        return view($view, $this->responseData);
    }

    protected function sendResponse($data, $message = '')
    {
        $response = [
            'data' => $data,
            'message' => $message
        ];

        return response()->json($response);
    }
}
