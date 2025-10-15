<?php

namespace App\Http\Controllers\Admin;

use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Services\ContentService;
use App\Models\Role;
use App\Models\CmsTaxonomy;
use App\Models\CmsAuthor;
use App\Consts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;


class DocumentController extends Controller
{
	public function __construct()
    {
        $this->routeDefault  = 'document';
        $this->viewPart = 'admin.pages.document';
		$this->responseData['locale']  = 'vi';
        $this->responseData['module_name'] = __('Quản lý tài liệu số');

        $this->array_task = array('active'=>1,'waiting'=>0,'pending'=>2,'rollback'=>3,'deactive'=>4,'lock'=>5,'draft'=>6);

        $this->authors = ContentService::getCmsAuthor(array())->get();
        $this->tags = ContentService::getCmsTag(array())->get();
        $this->language = ContentService::getCmsLanguage(array())->get();
        $this->managershop = ContentService::getManageShop(array('code'=>3))->get();

        $this->responseData['array_task'] = $this->array_task;
        $this->responseData['authors'] = $this->authors;
		$this->responseData['tags'] = $this->tags;
		$this->responseData['language'] = $this->language;
		$this->responseData['managershop'] = $this->managershop;
		$this->responseData['booksize'] = ContentService::getBookSize(array())->get();

		// Nhóm người dùng: 0: Phóng viên, 1: Quản trị hệ thống, 2: Ban biên tập, 3: Tổng biên tập

    }
    
    public function index(Request $request)
    {
		if(ContentService::checkRole($this->routeDefault,'index') == 0){
			$this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
			return $this->responseView($this->viewPart . '.404');
		}
		
      	$task = $request->get('task');
	      
      	if($task == ''){
	        $task = 'active';
	        $_REQUEST['task'] = 'active';
      	}

      	$status = $this->array_task[$task];

        $params = $request->all();
        $params['status'] = $status;
        $params['keyword'] = $request->get('keyword');

      	// Kiểm tra quyền của người dùng
      	// Nếu là phóng viên, thì chỉ xem những bài của họ đăng
      	if(Auth::guard('admin')->user()->is_super_admin == 0){
      		$params['admin_created_id'] =  Auth::guard('admin')->user()->id;
      	}else if(Auth::guard('admin')->user()->is_super_admin == 2){ 
      		// Thuộc ban biên tập - Xem những bài chờ biên tập, Những bài mà họ đã biên tập
      		// Chỉ nhìn thấy những bài mà họ duyệt
      		$params['admin_accept_id'] =  Auth::guard('admin')->user()->id;

      	}

      	// Nếu là tin nháp thì chỉ có người soạn mới có thể nhìn thấy
      	if($status == 6){
      		$params['admin_created_id'] =  Auth::guard('admin')->user()->id;
      	}

        $rows = ContentService::getDocument($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

		$paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
		$paramTaxonomys['taxonomy'] = 'document';
		$this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();

		$authors = $this->authors;
		$tags = $this->tags;
		
		$array_categorys = $array_authors = $array_tags = array();
		
		/*
		foreach($authors as $author){
			$array_authors[$author->id] = $author->title;
		}
		$this->responseData['authors'] = $array_authors;
		*/
		
		foreach($tags as $tag){
			$array_tags[$tag->id] = $tag->title;
		}

		$this->responseData['tags'] = $array_tags;

        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] = $params;

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
		$paramTaxonomys['taxonomy'] = 'document';
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
		
		$params = $request->all();

		$array_categorys = array();
		
		if(isset($params['authors'])){
			$params['authors'] = ','.implode(',',$params['authors']).',';
		}else{
			$params['authors'] = '';
		}
		
		if(isset($params['tags'])){
			$params['tags'] = ','.implode(',',$params['tags']).',';
		}else{
			$params['tags'] = '';
		}
		
		$hienthi = isset($params['hienthi']) ? implode(';',$params['hienthi']) : '';
		$params['hienthi'] = ','.$hienthi.',';
		
		$trangthaitin = $this->array_task[$params['submit']] ?? 10;

		$params['admin_created_id'] = Auth::guard('admin')->user()->id;
		$params['admin_updated_id'] = Auth::guard('admin')->user()->id;

		// Nếu là phóng viên
		if(Auth::guard('admin')->user()->is_super_admin == 0){
			$array_accept = array(0,6); // Trạng thái chờ duyệt và nháp

		}else if(Auth::guard('admin')->user()->is_super_admin == 2){
			// Nếu là ban biên tập
			$array_accept = array(0,2,4,6); // Trạng thái chờ duyệt,chờ xuất bản, từ chối và nháp
			$params['admin_accept_id'] = Auth::guard('admin')->user()->id;
		}else{
			$array_accept = array(0,1,2,3,4,5,6); // Tất cả trạng thái
			$params['admin_accept_id'] = Auth::guard('admin')->user()->id;
		}

		if(!in_array($trangthaitin,$array_accept)){
			$params['status'] = 0;
		}else{
			$params['status'] = $trangthaitin;
		}

		unset($params['submit']);

		if (isset($params['filepdf']) && $params['filepdf'] != '') {
			//đếm số trang
			$pdftext = file_get_contents($request->file('filepdf'));
			$num = preg_match_all("/\/Page\W/", $pdftext, $matches);

			// di chuyển và đặt tên file
			$file = $params['filepdf'];
			$fileName = $params['alias'] . '-' . time() . '.pdf';
			$year = date('Y');
			$pdfPath = $file->move('documents/pdf/' . $year, $fileName);
			$filePath = '/documents/pdf/' . $year . '/' . $fileName;

			$params['filepdf'] = $filePath;
			// $params['number_page'] = $num;
		}

		if (isset($params['file_other']) && $params['file_other'] != '') {
			//đếm số trang
			$pdftext = file_get_contents($request->file('file_other'));
			$num = preg_match_all("/\/Page\W/", $pdftext, $matches);

			// di chuyển và đặt tên file
			$file = $params['file_other'];
			$fileName = $params['alias'] . '-' . time();
			$year = date('Y');
			$pdfPath = $file->move('documents/files/' . $year . '/', $fileName);
			$filePath = '/documents/files/' . $year . '/' . $fileName;

			$params['file_other'] = $filePath;
		}

		Document::create($params);
		
		// Đếm số tài liệu trong hệ thống để cập nhật thêm
		$category = $params['category'];
		
		$demsotailieu = Document::where('category',$category)->where('status',1)->count();
		
		$taxo_moi = CmsTaxonomy::where('id',$category)->first();
		$taxo_moi -> number_document = $demsotailieu;
		$taxo_moi -> save();
		
		// Đếm số tài liệu của tác giả
		$tailieutacgia = Document::where('main_author',$params['main_author'])->count();
		$auther_moi = CmsAuthor::where('id',$params['main_author'])->first();
		$auther_moi -> number_doc = $tailieutacgia;
		$auther_moi -> save();

		return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
    	$this->responseData['module_name'] = __('Đọc sách');
    	$this->responseData['detail'] = $document;
        return $this->responseView($this->viewPart . '.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
		if(ContentService::checkRole($this->routeDefault,'update') == 0){
			$this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
			return $this->responseView($this->viewPart . '.404');
		}
		
		if(Auth::guard('admin')->user()->is_super_admin == 0){
			// Chỉ được sửa tin của nó, và với trại thái là đang nháp hoặc chờ duyệt, hoặc trả lại
			$array_status = array(0,3,6);

			if($document->admin_created_id != Auth::guard('admin')->user()->id || !in_array($document->status,$array_status)){
				return redirect()->route($this->routeDefault . '.index')->with('errorMessage', 'Bạn không có quyền thao tác dữ liệu này. Vui lòng kiểm tra lại');
			}

		}else if(Auth::guard('admin')->user()->is_super_admin == 2){ // Ban biên tập

			$array_status = array(0,2,3,6);
			if($document->admin_created_id != Auth::guard('admin')->user()->id || !in_array($document->status,$array_status)){
				return redirect()->route($this->routeDefault . '.index')->with('errorMessage', 'Bạn không có quyền thao tác dữ liệu này. Vui lòng kiểm tra lại');
			}
		}

		$paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
		$paramTaxonomys['taxonomy'] = 'document';
		$this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
		
		$this->responseData['detail'] = $document;
		
		return $this->responseView($this->viewPart . '.edit');
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $document)
    {
		if(ContentService::checkRole($this->routeDefault,'update') == 0){
			$this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
			return $this->responseView($this->viewPart . '.404');
		}
		
		$binhluancu = $document->comment;

		$danhmuccu = $document->category;

		$tacgiacu = $document->main_author;
		
		$params = $request->all();
		
		//dd($params);
		
		$check_url = Document::where('alias',$params['alias'])->where('id','!=',$document->id)->first();
		
		if($check_url){
			$params['alias'] = $params['alias'].'-'.$document->id;
		}
		
		if(isset($params['authors'])){
			$params['authors'] = ','.implode(',',$params['authors']).',';
		}else{
			$params['authors'] = '';
		}
		
		if(isset($params['tags'])){
			$params['tags'] = ','.implode(',',$params['tags']).',';
		}else{
			$params['tags'] = '';
		}
		
		$hienthi = isset($params['hienthi']) ? implode(',',$params['hienthi']) : '';
		$params['hienthi'] = ','.$hienthi.',';
		
		$params['admin_updated_id'] = Auth::guard('admin')->user()->id;

		$trangthaitin = $this->array_task[$params['submit']] ?? 10;
		//echo $trangthaitin;die;
		// Nếu là phóng viên
		if(Auth::guard('admin')->user()->is_super_admin == 0){
			$array_accept = array(0,6); // Trạng thái chờ duyệt và nháp

		}else if(Auth::guard('admin')->user()->is_super_admin == 2){
			// Nếu là ban biên tập
			$params['admin_accept_id'] = Auth::guard('admin')->user()->id;
			$array_accept = array(0,2,4,6); // Trạng thái chờ duyệt,chờ xuất bản, từ chối và nháp
		}else{
			$array_accept = array(0,1,2,3,4,5,6); // Tất cả trạng thái

			if($document->admin_accept_id == ''){
				$params['admin_accept_id'] = Auth::guard('admin')->user()->id;
			}

		}

		if(!in_array($trangthaitin,$array_accept)){
			$params['status'] = 0;
		}else{
			$params['status'] = $trangthaitin;
		}

		if($params['comment'] !=''){
			$noidung_binhluan = '<p><b>'.Auth::guard('admin')->user()->name.'</b> (<i>'.date('H:i d/m/Y').'</i>) :</p><p>'.$params['comment'].'</p>';
			$binhluancu = $binhluancu.$noidung_binhluan;
		}

		$params['comment'] = $binhluancu;

		unset($params['submit']);

		if (isset($params['filepdf']) && $params['filepdf'] != '') {
			//xóa file cũ
			if (File::exists($document->filepdf)) {
				File::delete($document->filepdf);
			}

			$pdftext = file_get_contents($request->file('filepdf'));
			$num = preg_match_all("/\/Page\W/", $pdftext, $matches);

			$file = $params['filepdf'];
			$fileName = $params['alias'] . '-' . time() . '.pdf';
			$year = date('Y');
			$pdfPath = $file->move('documents/pdf/' . $year, $fileName);
			$filePath = '/documents/pdf/' . $year . '/' . $fileName;

			$params['filepdf'] = $filePath;
			$params['number_page'] = $num;
		}

		if (isset($params['file_other']) && $params['file_other'] != '') {
			//xóa file cũ
			if (File::exists($document->file_other)) {
				File::delete($document->file_other);
			}

			//đếm số trang
			$pdftext = file_get_contents($request->file('file_other'));
			$num = preg_match_all("/\/Page\W/", $pdftext, $matches);
			$params['number_page'] = $num;
			// di chuyển và đặt tên file
			$file = $params['file_other'];
			$fileName = $params['alias'] . '-' . time();
			$year = date('Y');
			$pdfPath = $file->move('documents/files/' . $year, $fileName);
			$filePath = '/documents/files/' . $year . '/' . $fileName;

			$params['file_other'] = $filePath;
		}


		$document->fill($params);
        $document->save();
		
		$category = $params['category'];
		
		if($danhmuccu != $category){
			
			$demsotailieu = Document::where('category',$category)->where('status',1)->count();
			
			$taxo_moi = CmsTaxonomy::where('id',$category)->first();
			$taxo_moi -> number_document = $demsotailieu;
			$taxo_moi -> save();
			
			$demso_cu = Document::where('category',$danhmuccu)->where('status',1)->count();
			
			$taxo_cu = CmsTaxonomy::where('id',$danhmuccu)->first();
			if($taxo_cu){
				$taxo_cu -> number_document = $demso_cu;
				$taxo_cu -> save();
			}
		}else{
			$demso_cu = Document::where('category',$danhmuccu)->where('status',1)->count();
			
			$taxo_cu = CmsTaxonomy::where('id',$danhmuccu)->first();
			if($taxo_cu){
				$taxo_cu -> number_document = $demso_cu;
				$taxo_cu -> save();
			}
		}

		if($tacgiacu != $params['main_author']){
			// Đếm số tài liệu của tác giả cũ
			$tailieutacgia = Document::where('main_author',$tacgiacu)->count();
			$auther_cu = CmsAuthor::where('id',$tacgiacu)->first();
			if($auther_cu){
				$auther_cu -> number_doc = $tailieutacgia;
				$auther_cu -> save();
			}
			

			// Đếm số tài liệu của tác giả mới
			$tailieutacgia2 = Document::where('main_author',$params['main_author'])->count();
			$auther_moi = CmsAuthor::where('id',$params['main_author'])->first();
			if($auther_moi){
				$auther_moi -> number_doc = $tailieutacgia2;
				$auther_moi -> save();
			}
			
		}

		
        return redirect()->back()->with('successMessage', __('Successfully updated!'));
		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
		if(ContentService::checkRole($this->routeDefault,'delete') == 0){
			$this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
			return $this->responseView($this->viewPart . '.404');
		}

		if(Auth::guard('admin')->user()->is_super_admin == 1 || Auth::guard('admin')->user()->is_super_admin == 3 ){
			
			$document->status(5); // Trạng thái gỡ tin
			$document->save();
		}

		return redirect()->back()->with('successMessage', __('Đã gỡ tài liệu thành công'));

    }
	
	public function loadBook(Request $request)
    {
		$params = $request->all();
		//dd($params['q']);
		
		$keyword = $params['q'];
		
		//echo $keyword;
		
		$listBook = ContentService::getDocument(array('keyword'=>$keyword,'status'=>1))->get();
		$array = array();
		foreach($listBook as $row){
			$array[] = $row->id.'||'.$row->cost.'||'.$row->title;
		}
		echo json_encode ($array);
		/**/
		
	}
	
}
