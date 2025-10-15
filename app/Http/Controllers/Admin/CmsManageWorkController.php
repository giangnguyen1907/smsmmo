<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\ContentService;
use App\Http\Services\AdminService;
use App\Models\CmsManageWork;
use App\Models\CmsHistoryWork;
use App\Models\CmsRelationWork;
use App\Models\CmsItemWork;
use App\Models\CmsTypeWork;
use App\Models\Admin;
use App\Models\CmsDepartment;
use App\Models\FileRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CmsManageWorkController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'manage_work';
        $this->viewPart = 'admin.pages.cms_managework';
        $this->responseData['module_name'] = __('Quản lý công việc');
    }

    public function index(Request $request)
    {
        $params = $request->all();
        $params['keyword'] = isset($params['keyword']) ? $params['keyword'] : '';
        $params['user_id'] = isset($params['user_id']) ? $params['user_id'] : '';
        $params['from_date'] = isset($params['from_date']) ? $params['from_date'] : '';
        $params['to_date'] = isset($params['to_date']) ? $params['to_date'] : '';

        $query = CmsManageWork::query();

        $query->where('status', 'active');

        // Tìm kiếm theo title nếu có từ khóa nhập vào
        if ($params['keyword']) {
            $keyword = $params['keyword'];
            $query->where(function ($query) use ($keyword) {
                $query->where('title', 'like', "%$keyword%");
            });
        }
        // Tìm kiếm theo người tham gia
        if ($params['user_id']) {
            $user_id = $params['user_id'];
            $query->whereRaw("FIND_IN_SET('$user_id', list_id)"); // Tìm kiếm user_id trong list_id
        }
        // Tìm kiếm theo thời gian
        if ($params['from_date'] && $params['to_date']) {
            $fromDate = date("Y-m-d", strtotime($params['from_date']));
            $toDate = date("Y-m-d", strtotime($params['to_date']));
            $query->where(function ($query) use ($fromDate, $toDate) {
                $query->whereDate('updated_at', '>=', $fromDate)
                      ->whereDate('updated_at', '<=', $toDate);
            });
        }

        $currentUser = Auth::guard('admin')->user();

        $manageWorks = $query->where(function ($query) use ($currentUser) {
			$query->where('is_public', 1)
				->orWhere('admin_created_id', $currentUser->id)
				->orWhereRaw("FIND_IN_SET(?, list_id)", [$currentUser->id]);
		});
		
		// dd($query->toSql(), $query->getBindings());
		
		$manageWorks = $manageWorks->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
		


        if(Auth::guard('admin')->user()->is_super_admin == 1){
            $this->responseData['admins']= Admin::where('status','active')->get();
        } else {
            $this->responseData['admins'] = Admin::where('status','active')->where('department_id',Auth::guard('admin')->user()->department_id)->get();
        }

        $this->responseData['manageWorks'] = $manageWorks;

        $this->responseData['params'] = $params;

        return $this->responseView($this->viewPart . '.index');
    }

    public function viewRelationWork(Request $request, $id)
    {
        $params = $request->all();

        $query = CmsManageWork::with(['relationWorks', 'relationWorks.historyWorks', 'relationWorks.fileRelation', 'relationWorks.historyWorks.fileRelationHis']);

        if ($id) {
            $query->where(function ($query) use ($id) {
                $query->where('id', $id);
            });
        }
        
        $manageWorks = $query->get();

        $currentUser = Auth::guard('admin')->user();

        // Lặp qua danh sách công việc để kiểm tra điều kiện và lọc kết quả
        $filteredManageWorks = [];
        $title_manageWork = $manage_id = $manage_created_id = '';
        foreach ($manageWorks as $manageWork) {
            $title_manageWork = $manageWork->title;
            $manage_id = $manageWork->id;
            $manage_created_id = $manageWork->admin_created_id;
            if ($manageWork->is_public == 1) {
                // Nếu công việc là công khai, hiển thị cho tất cả người dùng
                $filteredManageWorks[] = $manageWork;
            } elseif ($manageWork->admin_created_id == $currentUser->id) {
                // Nếu công việc không công khai và được tạo bởi người dùng hiện tại, hiển thị cho người dùng hiện tại
                $filteredManageWorks[] = $manageWork;
            } elseif ($manageWork->list_id && in_array($currentUser->id, explode(',', $manageWork->list_id))) {
                // Nếu công việc không công khai và người dùng hiện tại có trong danh sách list_id, hiển thị cho người dùng hiện tại
                $filteredManageWorks[] = $manageWork;
            }
        }

        $this->responseData['module_name'] = $title_manageWork;

        $this->responseData['manage_id'] = $manage_id;

        $this->responseData['manage_created_id'] = $manage_created_id;

        $this->responseData['manageWorks'] = $filteredManageWorks;

        return $this->responseView($this->viewPart . '.view_relation_work');
    }

    public function create()
    {
        // Get all taxonomy is active
        $params['status'] = Consts::TAXONOMY_STATUS['active'];
        $this->responseData['taxonomys'] = ContentService::getCmsTaxonomy($params)->get();

        $this->responseData['admins'] = ContentService::getAdmins($params)->get();

        return $this->responseView($this->viewPart . '.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        $params = $request->all();
        $params['status'] = 'active';
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        CmsManageWork::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    public function show(CmsManageWork $cmsManageWork)
    {
        // Do not use this function
        return redirect()->back();
    }

    public function edit(CmsManageWork $cmsManageWork)
    {
        // Get all parents which have status is active
        $params['status'] = Consts::TAXONOMY_STATUS['active'];
        $params['different_id'] = $cmsManageWork->id;
        $this->responseData['taxonomys'] = ContentService::getCmsTaxonomy($params)->get();
        $this->responseData['detail'] = $cmsManageWork;

        return $this->responseView($this->viewPart . '.edit');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        $params = $request->all();

        $manage_work = CmsManageWork::find($id);

        if($manage_work){
            $manage_work->title = $params['title'];
            $manage_work->admin_updated_id = Auth::guard('admin')->user()->id;
            $manage_work->save();
        }

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Successfully updated!'));
    }

    public function destroy($id)
    {
        // $manageWork = CmsManageWork::findOrFail($id);
        // $manageWork->delete();
        
        // CmsRelationWork::where('manage_work_id', $id)->delete();
        
        $manageWork = CmsManageWork::findOrFail($id);
        $manageWork->status = 'delete';
        $manageWork->save();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }

    public function creatRelationWork($id)
    {
        $params['status'] = 'active';

        $this->responseData['item_work'] =  CmsItemWork::getCmsItemWork($params)->get();
        $this->responseData['type_work'] =  CmsTypeWork::getCmsTypeWork($params)->get();
        $this->responseData['cmsManageWork'] = CmsManageWork::find($id)->title;
        $this->responseData['manage_work_id'] = $id;

        // if(Auth::guard('admin')->user()->is_super_admin == 1){
            // $this->responseData['admins']= Admin::where('status','active')->get();
            // $this->responseData['departments'] = CmsDepartment::where('status','active')->orderBy('id','asc')->get();
        // } else {
            // $this->responseData['admins'] = Admin::where('status','active')->where('department_id',Auth::guard('admin')->user()->department_id)->get();
            // $this->responseData['departments'] = CmsDepartment::where('status','active')->where('id',Auth::guard('admin')->user()->department_id)->orderBy('id','asc')->get();
        // }
		
		$this->responseData['admins']= Admin::where('status','active')->get();
        $this->responseData['departments'] = CmsDepartment::where('status','active')->orderBy('id','asc')->get();

        return $this->responseView($this->viewPart . '.creat_relation_work');
    }

    public function storeRelationWork(Request $request)
    {
        $params = $request->all();

        $manage_work_id = $params['manage_work_id'];
        $title = $params['title'];
        $deadline = $params['deadline'];
        $item_work_id = $params['item_work_id'] ?? null;
        $type_work_id = $params['type_work_id'] ?? null;
        $content = $params['content'];
        $status = $params['status'];
        $is_public = $params['is_public'];
        $user_work = isset($params['user_work']) ? implode(',',$params['user_work']) : '';
        $department_user_work = isset($params['department_user_work']) ? implode(',',$params['department_user_work']) : '';
		
        $cmsRelationWork                        = new CmsRelationWork();
        $cmsRelationWork->manage_work_id        = $manage_work_id;
        $cmsRelationWork->title                 = $title;
        $cmsRelationWork->deadline              = $deadline;
        $cmsRelationWork->item_work_id          = $item_work_id;
        $cmsRelationWork->type_work_id          = $type_work_id;
        $cmsRelationWork->content               = $content;
        $cmsRelationWork->status                = $status;
        $cmsRelationWork->is_public             = $is_public;
        $cmsRelationWork->user_work             = $user_work;
        $cmsRelationWork->department_user_work  = $department_user_work;
        $cmsRelationWork->admin_created_id      = Auth::guard('admin')->user()->id;
        $cmsRelationWork->admin_updated_id      = Auth::guard('admin')->user()->id;
        $cmsRelationWork->save();

        $is_public_relation_work = $cmsRelationWork->is_public;

        //kiểm tra tồn tại công việc manage_work_id
        $checkManageWork =  CmsRelationWork::where('manage_work_id',$manage_work_id)->get();
        if($checkManageWork){
            $uniqueUsers = [];
            foreach ($checkManageWork as $record) {
                $users = explode(',', $record->user_work);
                $uniqueUsers = array_merge($uniqueUsers, $users);
            }
            $uniqueUsers = array_unique($uniqueUsers);
            sort($uniqueUsers);
            $uniqueUsersString = implode(',', $uniqueUsers);

            $manage_work = CmsManageWork::find($manage_work_id);
            $manage_work->list_id = $uniqueUsersString;
            $manage_work->is_public = $is_public_relation_work;
            $manage_work->save();
        }
		
		$year = date('Y');
		
		$destinationPath = 'data-congviec/'.$year.'/tai-lieu/';

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
		
		if($request->hasFile('file_work')) {
			$file_danhsachfile = $request->file('file_work');
			foreach ($file_danhsachfile as $file) {

				$pathFile = $file->getPathName();
				$name = $file->getClientOriginalName();

				$fileName = CmsManageWorkController::convertName($name);
				$file->move($destinationPath, $fileName);

				$fileRelation                	  = new FileRelation();
				$fileRelation->relation_work_id   = $cmsRelationWork->id;
				$fileRelation->name   		 	  = $fileName;
				$fileRelation->link_file     	  = '/public/data-congviec/'.$year.'/tai-lieu/'.$fileName;
				$fileRelation->status    	 	  = 'active';
				$fileRelation->type    	 	  	  = 'relation';
				$fileRelation->save();
            
            }
        }

        return redirect()->route('admin.view_relation_work', ['id' => $manage_work_id])->with('successMessage', __('Add new successfully!'));
    }

    public function editRelationWork($id)
    {
        $this->responseData['cmsRelationWork'] = CmsRelationWork::find($id);

        $params['status'] = 'active';
        $this->responseData['item_work'] =  CmsItemWork::getCmsItemWork($params)->get();
        $this->responseData['type_work'] =  CmsTypeWork::getCmsTypeWork($params)->get();

        // if(Auth::guard('admin')->user()->is_super_admin == 1){
            // $this->responseData['admins']= Admin::where('status','active')->get();
            // $this->responseData['departments'] = CmsDepartment::where('status','active')->orderBy('id','asc')->get();
        // } else {
            // $this->responseData['admins'] = Admin::where('status','active')->where('department_id',Auth::guard('admin')->user()->department_id)->get();

            // $this->responseData['departments'] = CmsDepartment::where('status','active')->where('id',Auth::guard('admin')->user()->department_id)->orderBy('id','asc')->get();
        // }
		
		$this->responseData['admins']= Admin::where('status','active')->get();
        $this->responseData['departments'] = CmsDepartment::where('status','active')->orderBy('id','asc')->get();
        $this->responseData['files'] = FileRelation::where('status','active')->where('relation_work_id',$id)->where('type','relation')->get();
		
        return $this->responseView($this->viewPart . '.edit_relation_work');
    }

    public function updateRelationWork(Request $request)
    {
        $params = $request->all();

        $cmsRelationWork = CmsRelationWork::find($params['relation_work_id']);

        $title = $params['title'];
        $deadline = $params['deadline'];
        $item_work_id = $params['item_work_id'] ?? null;
        $type_work_id = $params['type_work_id'] ?? null;
        $content = $params['content'];
        $status = $params['status'];
        $is_public = $params['is_public'];
        $user_work = isset($params['user_work']) ? implode(',',$params['user_work']) : '';
        $department_user_work = isset($params['department_user_work']) ? implode(',',$params['department_user_work']) : '';

        $cmsRelationWork->title                 = $title;
        $cmsRelationWork->deadline              = $deadline;
        $cmsRelationWork->item_work_id          = $item_work_id;
        $cmsRelationWork->type_work_id          = $type_work_id;
        $cmsRelationWork->content               = $content;
        $cmsRelationWork->status                = $status;
        $cmsRelationWork->is_public             = $is_public;
        $cmsRelationWork->user_work             = $user_work;
        $cmsRelationWork->department_user_work  = $department_user_work;
        $cmsRelationWork->user_work             = $user_work;
        $cmsRelationWork->admin_updated_id      = Auth::guard('admin')->user()->id;
        $cmsRelationWork->save();

        $is_public_relation_work = $cmsRelationWork->is_public;
        $manage_work_id = $cmsRelationWork->manage_work_id;

        //kiểm tra tồn tại công việc manage_work_id
        $checkManageWork =  CmsRelationWork::where('manage_work_id',$manage_work_id)->get();
        if($checkManageWork){
            $uniqueUsers = [];
            foreach ($checkManageWork as $record) {
                $users = explode(',', $record->user_work);
                $uniqueUsers = array_merge($uniqueUsers, $users);
            }
            $uniqueUsers = array_unique($uniqueUsers);
            sort($uniqueUsers);
            $uniqueUsersString = implode(',', $uniqueUsers);

            $manage_work = CmsManageWork::find($manage_work_id);
            $manage_work->list_id = $uniqueUsersString;
            $manage_work->is_public = $is_public_relation_work;
            $manage_work->save();
        }
		
		$year = date('Y');
		
		$destinationPath = 'data-congviec/'.$year.'/tai-lieu/';

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
		
		if($request->hasFile('file_work')) {
			$file_danhsachfile = $request->file('file_work');
			foreach ($file_danhsachfile as $file) {

				$pathFile = $file->getPathName();
				$name = $file->getClientOriginalName();

				$fileName = CmsManageWorkController::convertName($name);
				$file->move($destinationPath, $fileName);

				$fileRelation                	  = new FileRelation();
				$fileRelation->relation_work_id   = $cmsRelationWork->id;
				$fileRelation->name   		 	  = $fileName;
				$fileRelation->link_file     	  = '/public/data-congviec/'.$year.'/tai-lieu/'.$fileName;
				$fileRelation->status    	 	  = 'active';
				$fileRelation->type    	 	  	  = 'relation';
				$fileRelation->save();
            
            }
        }

        return redirect()->route('admin.view_relation_work', ['id' => $manage_work_id])->with('successMessage', __('Successfully updated!'));
    }

    public function destroyRelationWork(Request $request)
    {
        $id = $request->input('id');

        $relationWork = CmsRelationWork::find($id);

        if (!$relationWork) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy công việc.']);
        }

        // Cập nhật list_id của manageWork
        $manageWork = $relationWork->manageWork;
        $listIds = explode(',', $manageWork->list_id);
        $userIdsToRemove = explode(',', $relationWork->user_work);
        $listIds = array_diff($listIds, $userIdsToRemove);
        $manageWork->list_id = implode(',', array_unique($listIds));

        // Lấy danh sách các công việc con còn lại của manageWork
        $otherRelationWorks = CmsRelationWork::where('manage_work_id', $manageWork->id)
            ->where('id', '!=', $relationWork->id)
            ->get();

        // Tạo danh sách mới chứa tất cả user_work của các công việc con còn lại
        $newListIds = [];
        foreach ($otherRelationWorks as $otherRelationWork) {
            $newListIds = array_merge($newListIds, explode(',', $otherRelationWork->user_work));
        }
        $newListIds = array_unique($newListIds);
        sort($newListIds);
        $manageWork->list_id = implode(',', $newListIds);

        // Kiểm tra xem công việc con còn lại có công việc nào is_public = 1 hay không
        $hasPublicWork = CmsRelationWork::where('manage_work_id', $manageWork->id)->where('is_public', 1)->exists();

        // Cập nhật is_public của manage_work
        $manageWork->is_public = $hasPublicWork ? 1 : 0;

        // Lưu các thay đổi
        $manageWork->save();

        // Xóa công việc con
        $relationWork->delete();

        return response()->json(['success' => true, 'message' => 'Công việc đã được xóa thành công.']);
    }

    public function storeHistoryWork(Request $request)
    {
        $relationWorkId = $request->input('relation_work_id');
        $manage_work_id = $request->input('manage_work_id');
        $comment = $request->input('comment');
        $status = $request->input('status');

        // Lưu dữ liệu vào cơ sở dữ liệu
        $historyWork = new CmsHistoryWork();
        $historyWork->relation_work_id = $relationWorkId;
        $historyWork->comment = $comment;
        $historyWork->status = $status;
        $historyWork->admin_created_id = Auth::guard('admin')->user()->id;
        $historyWork->admin_updated_id = Auth::guard('admin')->user()->id;
        $historyWork->save();
		
		$year = date('Y');
		
		$destinationPath = 'data-congviec/'.$year.'/tai-lieu/';

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
		
		if($request->hasFile('file_work')) {
			$file_danhsachfile = $request->file('file_work');
			foreach ($file_danhsachfile as $file) {

				$pathFile = $file->getPathName();
				$name = $file->getClientOriginalName();

				$fileName = CmsManageWorkController::convertName($name);
				$file->move($destinationPath, $fileName);

				$fileRelation                	  = new FileRelation();
				$fileRelation->relation_work_id   = $historyWork->id;
				$fileRelation->name   		 	  = $fileName;
				$fileRelation->link_file     	  = '/public/data-congviec/'.$year.'/tai-lieu/'.$fileName;
				$fileRelation->status    	 	  = 'active';
				$fileRelation->type    	 	  	  = 'his';
				$fileRelation->save();
            
            }
        }

        return redirect()->route('admin.view_relation_work', ['id' => $manage_work_id])->with('successMessage', __('Báo cáo công việc thành công'));
    }

    public function updateStatusRelationWork(Request $request)
    {
        $relationWorkId = $request->input('relation_work_id');
        $status = $request->input('status');

        // Xử lý cập nhật trạng thái của công việc dựa trên $relationWorkId và $status
        if($status != ''){
            $relationWork = CmsRelationWork::find($relationWorkId);
            $relationWork->status = $status;
            $relationWork->save();
        }

        return response()->json(['success' => true]);
    }
	
	public static function convertName($str) {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        $str = preg_replace("/(\“|\”|\‘|\’|\,|\!|\&|\;|\@|\#|\%|\~|\`|\=|\_|\'|\]|\[|\}|\{|\)|\(|\+|\^)/", '_', $str);
        $str = preg_replace("/( )/", '_', $str);
        return $str;
    }
	
	public function deleteFile(Request $request)
    {
        $id = $request->input('id');
		
		$file_relation = FileRelation::where('id', '=', $id)->where('status','active')->where('type','relation')->first();
		$file_relation->status = 'deactive';
		$file_relation->save();

        return response()->json(['success' => true]);
    }

}
