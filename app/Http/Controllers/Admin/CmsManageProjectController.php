<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\ContentService;
use App\Http\Services\AdminService;
use App\Models\CmsManageProject;
use App\Models\CmsFileProject;
use App\Models\Admin;
use App\Models\CmsDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\CmsManageWorkController;

class CmsManageProjectController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'manage_project';
        $this->viewPart = 'admin.pages.cms_manageproject';
        $this->responseData['module_name'] = __('Quản lý dự án');
    }

    public function index(Request $request)
    {
        if(ContentService::checkRole($this->routeDefault,'index') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }

        $params = $request->all();
        $params['keyword'] = isset($params['keyword']) ? $params['keyword'] : '';
        $params['user_id'] = isset($params['user_id']) ? $params['user_id'] : '';
        $params['from_date'] = isset($params['from_date']) ? $params['from_date'] : '';
        $params['to_date'] = isset($params['to_date']) ? $params['to_date'] : '';

        $query = CmsManageProject::query();

        $query->where('status', 'active');
        $query->where('parent_id', '0');

        if ($params['keyword']) {
            $keyword = $params['keyword'];
            $query->where(function ($query) use ($keyword) {
                $query->where('title', 'like', "%$keyword%");
            });
        }

        if ($params['user_id']) {
            $user_id = $params['user_id'];
            $query->whereRaw("FIND_IN_SET('$user_id', user_work)");
        }

        if ($params['from_date'] && $params['to_date']) {
            $fromDate = date("Y-m-d", strtotime($params['from_date']));
            $toDate = date("Y-m-d", strtotime($params['to_date']));
            $query->where(function ($query) use ($fromDate, $toDate) {
                $query->whereDate('updated_at', '>=', $fromDate)
                      ->whereDate('updated_at', '<=', $toDate);
            });
        }

        $currentUser = Auth::guard('admin')->user();

        $manageProject = $query->where(function ($query) use ($currentUser) {
			$query->where('is_public', 1)
				->orWhere('admin_created_id', $currentUser->id)
				->orWhereRaw("FIND_IN_SET(?, user_work)", [$currentUser->id]);
		});
		
		// dd($query->toSql(), $query->getBindings());
		
		$manageProject = $manageProject->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

        $this->responseData['admins']= Admin::where('status','active')->get();
        
        $this->responseData['departments'] = CmsDepartment::where('status','active')->orderBy('id','asc')->get();

        $this->responseData['manageProject'] = $manageProject;

        $this->responseData['params'] = $params;

        return $this->responseView($this->viewPart . '.index');
    }

    public function store(Request $request)
    {
        if(ContentService::checkRole($this->routeDefault,'create') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }

        $params = $request->all();
        $title = $params['title'];
        $is_public = $params['is_public'];
        $user_work = isset($params['user_work']) ? implode(',',$params['user_work']) : '';
        $department_user_work = isset($params['department_user_work']) ? implode(',',$params['department_user_work']) : '';
		
        $cmsManageProject                        = new CmsManageProject();
        $cmsManageProject->title                 = $title;
        $cmsManageProject->is_public             = $is_public;
        $cmsManageProject->user_work             = $user_work;
        $cmsManageProject->department_user_work  = $department_user_work;
        $cmsManageProject->admin_created_id      = Auth::guard('admin')->user()->id;
        $cmsManageProject->admin_updated_id      = Auth::guard('admin')->user()->id;
        $cmsManageProject->save();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    public function update(Request $request, $id)
    {
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }

        $params = $request->all();
        $title = $params['title'];
        $is_public = $params['is_public'];
        $user_work = isset($params['user_work']) ? implode(',',$params['user_work']) : '';
        $department_user_work = isset($params['department_user_work']) ? implode(',',$params['department_user_work']) : '';

        $cmsManageProject = CmsManageProject::findOrFail($id);
		
        if($cmsManageProject){
            $cmsManageProject->title                 = $title;
            $cmsManageProject->is_public             = $is_public;
            $cmsManageProject->user_work             = $user_work;
            $cmsManageProject->department_user_work  = $department_user_work;
            $cmsManageProject->admin_updated_id      = Auth::guard('admin')->user()->id;
            $cmsManageProject->save();
        }

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Successfully updated!'));
    }

    public function destroy($id)
    {
        $cmsManageProject = CmsManageProject::findOrFail($id);
        $cmsManageProject->status = 'delete';
        $cmsManageProject->save();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }

    public function viewParentProject(Request $request, $id)
    {
        if(ContentService::checkRole($this->routeDefault,'index') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }

        $parentProjects = CmsManageProject::where('status', '!=', 'delete')->where('parent_id', $id)->get();

        $fileProjects = CmsFileProject::where('status', 'active')->where('manage_project_id', $id)->orderBy('id','desc')->get();

        $cmsManageProject = CmsManageProject::findOrFail($id);

        $this->responseData['parentProjects'] = $parentProjects;

        $this->responseData['fileProjects'] = $fileProjects;

        $this->responseData['cmsManageProject'] = $cmsManageProject;

        return $this->responseView($this->viewPart . '.view_parent_project');
    }

    public function storeProjectFile(Request $request, $id)
    {
        if(ContentService::checkRole($this->routeDefault,'create') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }

        $params = $request->all();

        $year = date('Y');
		
		$destinationPath = 'data-congviec/'.$year.'/du-an/'.$id.'/';

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
		
		if($request->hasFile('file_project')) {
			$file_danhsachfile = $request->file('file_project');
			foreach ($file_danhsachfile as $file) {
				$name = $file->getClientOriginalName();
				$fileName = CmsManageWorkController::convertName($name);
				$file->move($destinationPath, $fileName);

				$cmsFileProject                	   = new CmsFileProject();
				$cmsFileProject->manage_project_id = $id;
				$cmsFileProject->name   		   = $fileName;
				$cmsFileProject->link_file     	   = '/public/data-congviec/'.$year.'/du-an/'.$id.'/'.$fileName;
				$cmsFileProject->admin_updated_id  = Auth::guard('admin')->user()->id;
				$cmsFileProject->save();
            
            }
        }

        return redirect()->route('cms_project.view_parent_project', ['id' => $id])->with('successMessage', __('Add new successfully!'));
    }

    public function destroyProjectFile(Request $request)
    {
        $id = $request->input('id');

        $cmsFileProject = CmsFileProject::findOrFail($id);

        if (!$cmsFileProject) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy file.']);
        }

        $cmsFileProject->status = 'delete';
        $cmsFileProject->save();

        return response()->json(['success' => true, 'message' => 'File đã được xóa thành công.']);
    }

    public function searchProjectFile(Request $request){

        $keyword = $request->input('keyword');
        $orderby = $request->input('orderby');
        $id = $request->input('manage_project_id');
        
        $query = CmsFileProject::query();

        $query = $query->where('status','active');

        $query = $query->where('manage_project_id', $id );
        
        if ($keyword) {
            $query->where('name', 'LIKE', "%{$keyword}%");
        }
        
        switch ($orderby) {
            case 'orderby-az':
                $query->orderBy('name', 'asc');
                break;
            case 'orderby-za':
                $query->orderBy('name', 'desc');
                break;
            case 'orderby-update':
                $query->orderBy('updated_at', 'desc');
                break;
            default:
                $query->orderBy('updated_at', 'desc');
                break;
        }

        $fileProjects = $query->get();

        $html = '';
        foreach ($fileProjects as $key => $fileProject) {
            $html .= '<tr class="valign-middle">';
            $html .= '<td>' . ($key + 1) . '</td>';
            $html .= '<td><a href="' . $fileProject->link_file . '" target="_blank" download>' . $fileProject->name . '</a></td>';
            $html .= '<td>' . date('H:i d/m/Y', strtotime($fileProject->updated_at)) . '</td>';
            $html .= '<td><a type="button" class="btn btn-sm btn-danger" onclick="deleteFile(\'' . $fileProject->id . '\')"><i class="fa fa-trash"></i></a></td>';
            $html .= '</tr>';
        }
        
        return response()->json($html);

    }

    public function storeParentProject(Request $request, $id){

        $params = $request->all();
        $title = $params['title'];
        $content = $params['content'];
        $status = $params['status'];
        $deadline = $params['deadline'];

        $parentManageProject                        = new CmsManageProject();
        $parentManageProject->parent_id             = $id;
        $parentManageProject->title                 = $title;
        $parentManageProject->content               = $content;
        $parentManageProject->status                = $status;
        $parentManageProject->deadline              = $deadline;
        $parentManageProject->admin_created_id      = Auth::guard('admin')->user()->id;
        $parentManageProject->admin_updated_id      = Auth::guard('admin')->user()->id;
        $parentManageProject->save();

        return redirect()->route('cms_project.view_parent_project', ['id' => $id])->with('successMessage', __('Add new successfully!'));

    }

    public function destroyParentProject(Request $request)
    {
        $id = $request->input('id');

        $cmsManageProject = CmsManageProject::findOrFail($id);

        if (!$cmsManageProject) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy công việc.']);
        }

        $cmsManageProject->status = 'delete';
        $cmsManageProject->save();

        return response()->json(['success' => true, 'message' => 'Công việc đã được xóa thành công.']);
    }

    public function changeStatusProject(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        $cmsManageProject = CmsManageProject::findOrFail($id);

        $cmsManageProject->status = $status;
        $cmsManageProject->save();

        return response()->json(['success' => true, 'message' => 'Thay đổi trạng thái công việc thành công.']);
    }

}
