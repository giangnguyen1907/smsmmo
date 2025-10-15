<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Admin;
use App\Models\TbViews;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->viewPart = 'admin.pages.home';
        $this->responseData['module_name'] = 'Welcome to Admin System!';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Auth::guard('admin')->user()->login_at = date('Y-m-d H:i:s');
        Auth::guard('admin')->user()->save();
        // Lượt truy cập trang
		$day = 10;
        $to_date = time();
        $from_date = strtotime(date("Y-m-d", $to_date) . "-".$day." day");
        $rows = TbViews::getViewsPage($from_date, $to_date);

        $timenow = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", $to_date) . "-5 minute"));
        //dd($timenow);
        // Admin đang online
        $adminOnlines = Admin::where('login_at','>=',$timenow)->get();
        //$adminOnlines = Admin::where('id','>=',0)->get();
        //dd($adminOnlines);
        $this->responseData['day'] = $day;
        $this->responseData['rows'] = $rows;
        $this->responseData['adminOnlines'] = $adminOnlines;

        return $this->responseView($this->viewPart . '.index');
    }
}
