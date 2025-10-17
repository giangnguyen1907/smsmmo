<?php

namespace App\Http\Controllers\FrontEnd;

use App\Consts;
use App\Http\Services\ContentService;
use App\Http\Services\PageBuilderService;
use App\Models\CmsTaxonomy;
use App\Models\CmsTranslate;
use App\Models\Popup;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use stdClass;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Part to views for Controller
    protected $viewPart;
    protected $apitoken;
    // Data response to view
    protected $responseData = [];
    protected $web_information = [];
    protected $translates = [];

    public function __construct()
    {
        // Get all global system params
        $options = ContentService::getOption();
        if ($options) {
            $this->web_information = new stdClass();
            foreach ($options as $option) {
                $this->web_information->{$option->option_name} = json_decode($option->option_value);
            }
            $this->responseData['web_information'] = $this->web_information;
            $this->apitoken = $this->web_information->information->apitoken ?? '';
            // dd($this->apitoken);
        }
        $this->translates = CmsTranslate::pluck('translate','id')->toArray();
        // dd($this->translates);
    }

    /**
     * Xử lý các thông tin hệ thống trước khi đổ ra view
     * @author: ThangNH
     * @created_at: 2021/10/01
     */

    protected function responseView($view)
    {
        $this->responseData['user_auth'] = Auth::user();
        
        //$this->responseData['menu'] = ContentService::getMenu(['status' => 'active', 'order_by' => ['iorder' => 'ASC']])->get();
		
		// Lấy danh sách ngôn ngữ
		$languages = ContentService::getCmsLanguage(array())->get();
		
		if(count($languages) > 0){
			$locale = $languages[0]->lang_code;
		}else{
			$locale = App::getLocale();
		}
		App::setLocale($locale);
        // Set locale to use mutiple languages 
        if (session('locale') !== null) {
            App::setLocale(session('locale'));
        }
		
		//echo App::getLocale();
		
		$this->responseData['languages'] = $languages;
        $this->responseData['locale'] = App::getLocale();
        
        $this->responseData['apitoken'] = $this->apitoken;

        $taxonomy_all = ContentService::getCmsTaxonomy(['status' => 'active', 'order_by' => ['iorder' => 'ASC']])->get();
		
        // Get page info and block default
        $params_page['route_name'] = Route::getCurrentRoute()->getName();
        $params_page['id'] = $this->responseData['web_information']->page->{$params_page['route_name']} ?? null;

		$params_bl = array();
		$blocksContent = ContentService::getBlockContentByParams($params_bl)->get();
		$this->responseData['blocksContent'] = $blocksContent;

        $paramspost['status'] = 'active';
        $paramspost['news_position'] = '2';
        $listPostFooter = ContentService::getCmsPost($paramspost)->limit(5)->get();
        $this->responseData['listPostFooter'] = $listPostFooter;
        
        // Lấy tất cả bản dịch
        $translates = ContentService::getCmsTranslate(array())->get();
        $array_translate = array();

        foreach($translates as $translate){
            $array_translate[strtolower($translate->local)] = $translate->json_param;
        }

        $this->responseData['array_translate'] = $array_translate;
		
		$array_category = [];
		$array_category_document = array();
		foreach ($taxonomy_all as $category) {
			if ($category->parent_id != '') {
				$array_category[$category->parent_id] = $category->parent_id;
			}
			$array_category_document[$category->id] = $category->title->$locale;
		}
		
		$this->responseData['taxonomy_all'] = $taxonomy_all;
		$this->responseData['array_category'] = $array_category;
		$this->responseData['array_category_document'] = $array_category_document;
		
		// Tác giả:
		$cmsAuthors = ContentService::getCmsAuthor([])->get();
		$array_authors = [];
		foreach($cmsAuthors as $author){
			$array_authors[$author->id] = $author->title;
		}
		$this->responseData['array_authors'] = $array_authors;
		$this->responseData['cmsAuthors'] = $cmsAuthors;
        
		$params['status'] = '1';
		$params['hienthi'] = '0';
		$documentFeature = ContentService::getDocument($params)->limit(12)->get();
		
		$this->responseData['documentFeature'] = $documentFeature;
		
        $newDocuments = ContentService::getDocument(['status' => '1'])->limit(3)->get();
		$this->responseData['newDocuments'] = $newDocuments;
		/*
        $taxonomy_all_header = CmsTaxonomy::where('status', 'active')
            ->orderBy('iorder', 'ASC')
            ->take(5)
            ->get();
        $this->responseData['taxonomy_all_header'] = $taxonomy_all_header;
		*/
        // Get popup infor by page
        //$start_time = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
        
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
	
	public function setLanguage(Request $request)
    {
        $lang = request('lang') ?? '';
		
		App::setLocale($lang) ;
		
		return true;
		
    }
	
}
