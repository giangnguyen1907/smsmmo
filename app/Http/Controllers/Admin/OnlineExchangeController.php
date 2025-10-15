<?php

namespace App\Http\Controllers\Admin;

//use App\Http\Controllers\Controller;
use App\Models\OnlineExchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Consts;
use App\Models\Admin;
use App\Http\Services\ContentService;
use App\Models\Expert;
use App\Models\OnlineExchangeDetail;

class OnlineExchangeController extends Controller
{

    public function __construct()
    {   
        $this->routeDefault  = 'online_exchange';
        $this->viewPart = 'admin.pages.online_exchange';
        $this->responseData['module_name'] = __('Giao lưu trực tuyến');
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(ContentService::checkRole($this->routeDefault,'index') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }

        $params = $request->all();
        $rows = OnlineExchange::getOnlineExchangeByParam($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
        return $this->responseView($this->viewPart . '.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(ContentService::checkRole($this->routeDefault,'create') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }

        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
        $paramTaxonomys['taxonomy'] = Consts::CATEGORY['exchange'];
        $this->responseData['admins'] = Admin::where('status','active')->get();
        $this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
        $this->responseData['experts'] = Expert::where('id','>',0)->get();

        return $this->responseView($this->viewPart . '.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(ContentService::checkRole($this->routeDefault,'create') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $request->validate([
            'title' => 'required|max:255',
            'url_part' => 'required|max:255',
            'taxonomy_id' => 'required|max:255',
        ]);
        
        $params = $request->all();

        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $params['member'] =  isset($params['member']) ? ','.implode(",",$params['member']).',' : '';
        $params['manage'] = isset($params['manage']) ? ','.implode(",",$params['manage']).',' : '';
        $params['experts'] = isset($params['experts']) ? ','.implode(",",$params['experts']).',' : '';
        
        OnlineExchange::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OnlineExchange  $onlineExchange
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, OnlineExchange $onlineExchange)
    {
        if(ContentService::checkRole($this->routeDefault,'show') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        //dd($task);
        if(!isset($request->task)){
            $task = 'active';
        }else{
            $task = $request->task;
        }

        $this->responseData['check_time'] = 0;
        // Check người viết bài
        $check_member = 0;
        if(strtotime($onlineExchange->end_date) >= time()){
            $list_member = explode(',', trim($onlineExchange->member,',')) ;
            if(Auth::guard('admin')->user()->is_super_admin == 1){
                $check_member = 1;
            }else if(in_array(Auth::guard('admin')->user()->id, $list_member)) {
                $check_member = 1;
            }
        }else{
            $this->responseData['check_time'] = 1;
        }
        
        // Check người duyệt
        $check_manage = 0;
        $list_manage = explode(',', trim($onlineExchange->manage,',')) ;
        if(Auth::guard('admin')->user()->is_super_admin == 1){
            $check_manage = 1;
        }else if(in_array(Auth::guard('admin')->user()->id, $list_manage)) {
            $check_manage = 1;
        }

        $this->responseData['module_name'] = __('Giao lưu trực tuyến');
        $this->responseData['check_member'] = $check_member;
        $this->responseData['check_manage'] = $check_manage;
        $this->responseData['detail'] = $onlineExchange;
        $params['exchange_id'] = $onlineExchange->id;
        $params['status'] = $task;
        $this->responseData['task'] = $task;
        $expert_ids = explode(',',trim($onlineExchange->experts,','));
        $this->responseData['experts'] = Expert::whereIn('id', $expert_ids )->get();
        $this->responseData['OnlineExchangeDetail'] = OnlineExchangeDetail::getOnlineExchangeDetailByParams($params)->get();
        return $this->responseView($this->viewPart . '.show');

    }

    public function createComment(Request $request)
    {
        if(ContentService::checkRole($this->routeDefault,'create') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $params = $request->all();

        $params['status'] = 'active';
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        OnlineExchangeDetail::create($params);

        return redirect()->back()->with('successMessage', __('Thêm mới bình luận trực tiếp thành công!'));
    }

    public function updateStatusComment(Request $request)
    {   
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $id = $request->id;
        $status = $request->status;

        $check_exchange = OnlineExchangeDetail::where('id',$id)->first();

        if($check_exchange){
            if($check_exchange->parent_id ==''){
                OnlineExchangeDetail::where('parent_id', $id)->update([ 'status' => $status ]);
            }
            $check_exchange -> status = $status;
            $check_exchange -> save();
        }

        return $id;
		
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OnlineExchange  $onlineExchange
     * @return \Illuminate\Http\Response
     */
    public function edit(OnlineExchange $onlineExchange)
    {
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
        $paramTaxonomys['taxonomy'] = Consts::CATEGORY['exchange'];
        $this->responseData['admins'] = Admin::where('status','active')->get();
        $this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
        $this->responseData['detail'] = $onlineExchange;
        $this->responseData['experts'] = Expert::where('id','>',0)->get();
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OnlineExchange  $onlineExchange
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OnlineExchange $onlineExchange)
    {
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $request->validate([
            'title' => 'required|max:255',
            'url_part' => 'required|max:255',
            'taxonomy_id' => 'required|max:255',
        ]);

        $params = $request->all();
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $params['member'] =  isset($params['member']) ? ','.implode(",",$params['member']).',' : '';
        $params['manage'] = isset($params['manage']) ? ','.implode(",",$params['manage']).',' : '';
        $params['experts'] = isset($params['experts']) ? ','.implode(",",$params['experts']).',' : '';

        $onlineExchange->fill($params);
        $onlineExchange->save();
        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OnlineExchange  $onlineExchange
     * @return \Illuminate\Http\Response
     */
    public function destroy(OnlineExchange $onlineExchange)
    {
        if(ContentService::checkRole($this->routeDefault,'delete') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $onlineExchange -> status = 'deactive';
        $onlineExchange -> admin_updated_id = Auth::guard('admin')->user()->id;
        $onlineExchange -> save();
        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }
}
