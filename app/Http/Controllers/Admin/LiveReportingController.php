<?php

namespace App\Http\Controllers\Admin;

//use App\Http\Controllers\Controller;
use App\Models\LiveReporting;
use App\Models\LiveReportingDetail;
use App\Http\Services\ContentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Consts;
use App\Models\Admin;

class LiveReportingController extends Controller
{
    public function __construct()
    {   
        $this->routeDefault  = 'live_reporting';
        $this->viewPart = 'admin.pages.live_reporting';
        $this->responseData['module_name'] = __('Quản lý tường thuật trực tiếp');
        
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
        $rows = LiveReporting::getLiveReportingByParam($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
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
        //
        if(ContentService::checkRole($this->routeDefault,'create') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }

        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
        $paramTaxonomys['taxonomy'] = Consts::CATEGORY['reporting'];
        $this->responseData['admins'] = Admin::where('status','active')->get();
        $this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
        
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

        $liveReporting = LiveReporting::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LiveReporting  $liveReporting
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, LiveReporting $liveReporting)
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
        if(strtotime($liveReporting->end_date) >= time()){
            $list_member = explode(',', trim($liveReporting->member,',')) ;
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
        $list_manage = explode(',', trim($liveReporting->manage,',')) ;
        if(Auth::guard('admin')->user()->is_super_admin == 1){
            $check_manage = 1;
        }else if(in_array(Auth::guard('admin')->user()->id, $list_manage)) {
            $check_manage = 1;
        }

        $this->responseData['module_name'] = __('Bình luận trực tiếp');
        $this->responseData['check_member'] = $check_member;
        $this->responseData['check_manage'] = $check_manage;
        $this->responseData['detail'] = $liveReporting;
        $params['live_id'] = $liveReporting->id;
        $params['status'] = $task;
        $this->responseData['task'] = $task;
        $this->responseData['liveReportingDetail'] = LiveReportingDetail::getLiveReportingDetail($params)->get();
        return $this->responseView($this->viewPart . '.show');

    }

    public function updateStatus(Request $request)
    {   
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $id = $request->id;
        $status = $request->status;
        LiveReportingDetail::where('id', $id)->update([ 'status' => $status ]);

        return $id;
		
    }

    public function createComment(Request $request)
    {
        if(ContentService::checkRole($this->routeDefault,'create') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $params = $request->all();

        $params['status'] = 'waiting';
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        LiveReportingDetail::create($params);

        return redirect()->back()->with('successMessage', __('Thêm mới bình luận trực tiếp thành công!'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LiveReporting  $liveReporting
     * @return \Illuminate\Http\Response
     */
    public function edit(LiveReporting $liveReporting)
    {
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
        $paramTaxonomys['taxonomy'] = Consts::CATEGORY['reporting'];
        $this->responseData['admins'] = Admin::where('status','active')->get();
        $this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
        $this->responseData['detail'] = $liveReporting;
        return $this->responseView($this->viewPart . '.edit');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LiveReporting  $liveReporting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LiveReporting $liveReporting)
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

        $liveReporting->fill($params);
        $liveReporting->save();
        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LiveReporting  $liveReporting
     * @return \Illuminate\Http\Response
     */
    public function destroy(LiveReporting $liveReporting)
    {
        if(ContentService::checkRole($this->routeDefault,'delete') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $liveReporting -> status = 'deactive';
        $liveReporting -> admin_updated_id = Auth::guard('admin')->user()->id;
        $liveReporting -> save();
        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }
}
