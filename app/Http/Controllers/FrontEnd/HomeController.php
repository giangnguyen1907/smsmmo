<?php

namespace App\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use App\Consts;
use App\Models\TbViews;
use App\Http\Services\ContentService;
use App\Http\Services\PageBuilderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		
    	if(!$request->session()->has('userOnline')){
    		//DD('AAAAA');
    		$userOnline = TbViews::select('*')->where('ngay',strtotime(date('Y-m-d')))->first();

    		if ($userOnline) {
				if ($this->svl_ismobile() == 1) {
					$web = $userOnline->web;
					$mb = $userOnline->mobile + 1;
				} else {
					$web = $userOnline->web + 1;
					$mb = $userOnline->mobile;
				}
	            $toantrang = $userOnline->toantrang + 1;
				$userOnline->mobile = $mb;
				$userOnline->web = $web;
				$userOnline->toantrang = $toantrang;
				$userOnline->save();
			} else {
				if ($this->svl_ismobile() == 1) {
					$web = 0;
					$mb = 1;
				} else {
					$web = 1;
					$mb = 0;
				}
				$userOnline = new TbViews();
	            $userOnline->ngay = strtotime(date('Y-m-d'));
				$userOnline->mobile = $mb;
				$userOnline->web = $web;
				$userOnline->toantrang = 1;
				$userOnline->save();
			}

    		$request->session()->put('userOnline', $userOnline);

    	}
    	
        //return redirect()->route('frontend.home')->with('successMessage', 'Thêm mới tin thành công! Tin của bạn đang được chờ duyệt');
        return $this->responseView('frontend.pages.home.index');
        //return redirect()->route('frontend.home');
    }

    // Hàm kiểm tra xem truy cập bằng thiết bị gì
    private function svl_ismobile() {
        $is_mobile = '0';
        if(preg_match('/(android|iphone|ipad|up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
            $is_mobile=1;
        if((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml')>0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE']))))
            $is_mobile=1;
        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));
        $mobile_agents = array('w3c ','acs-','alav','alca','amoi','andr','audi','avan','benq','bird','blac','blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno','ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-','maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-','newt','noki','oper','palm','pana','pant','phil','play','port','prox','qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar','sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-','tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp','wapr','webc','winw','winw','xda','xda-');
     
        if(in_array($mobile_ua,$mobile_agents))
            $is_mobile=1;
     
        if (isset($_SERVER['ALL_HTTP'])) {
            if (strpos(strtolower($_SERVER['ALL_HTTP']),'OperaMini')>0)
                $is_mobile=1;
        }
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows')>0)
            $is_mobile=0;
        return $is_mobile;
    }
}
