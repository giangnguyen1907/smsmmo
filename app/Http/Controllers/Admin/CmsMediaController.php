<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\ContentService;
use App\Models\CmsMedia;
use App\Models\CmsMediaVideo;
use App\Models\CmsMediaImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Admin;

class CmsMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
      $this->routeDefault  = 'cms_media';
      $this->viewPart = 'admin.pages.cms_media';
      $this->responseData['module_name'] = __('Quản lý tin media');
    }

    public function index(Request $request)
    {
        $task = $request->get('task');
      
        if($task == ''){
            $task = 'active';
            $_REQUEST['task'] = 'active';
        }
        if(ContentService::checkRole($this->routeDefault,'index') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }

        $params = $request->all();
        $params['is_type'] = Consts::POST_TYPE['post'];

        $hidden = 0;
        /*
		if(Auth::guard('admin')->user()->is_super_admin == 0){
            $hidden = 1;
            $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        }*/
        $rows = ContentService::getCmsMedia($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
        $paramTaxonomys['taxonomy'] = 'thu-vien';
        $this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
      
        $this->responseData['admins'] = Admin::where('id','>',0)->get();
        $this->responseData['hidden'] = $hidden;
        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] = $params;
        $this->responseData['booleans'] = Consts::TITLE_BOOLEAN;
        $this->responseData['postStatus'] = Consts::STATUS;

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
        $paramTaxonomys['taxonomy'] = 'thu-vien';
        $paramTag['taxonomy'] = 'tag';
        $this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
        $this->responseData['parents2'] = ContentService::getCmsTaxonomy($paramTag)->get();

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
              'taxonomy_id' => 'required|max:255',
          ]);
          
          $params = $request->all();

          $trangthaitin = $params['submit'];
          
          if(Auth::guard('admin')->user()->is_super_admin == 1){
            
              if ($trangthaitin == "gobaidang") { // Gỡ bài đăng
                  $params['status'] = 'lock';
              }else if ($trangthaitin == "xuatban") { // Xuất bản
                  $params['status'] = 'active';
              }else if ($trangthaitin == "choduyet") { // Chờ xử lý
                  $params['status'] = 'waiting';
              }else if ($trangthaitin == "tuchoi") { // Từ chối đăng
                  $params['status'] = 'deactive';
              }else if ($trangthaitin == "tralai") { // Từ trả lại
                  $params['status'] = 'tralai';
              }else{
      				  $params['status'] = 'active';
      			  }

          }else{

              if ($trangthaitin == "gobaidang") { // Gỡ bài đăng
                  $params['status'] = 'lock';
              }else if ($trangthaitin == "choduyet") { // Chờ xử lý
                  $params['status'] = 'waiting';
              }else if ($trangthaitin == "tuchoi") { // Từ chối đăng
                  $params['status'] = 'deactive';
              }else if ($trangthaitin == "tralai") { // Từ trả lại
                  $params['status'] = 'tralai';
              }else{
      				  $params['status'] = 'active';
      			  }
          }

          $cmsPost                      = new CmsMedia();
          $cmsPost->title               = $params['title'];
          $cmsPost->content             = $params['content'];
          $cmsPost->url_part            = $params['url_part'];
          $cmsPost->brief               = $params['brief'];
          $cmsPost->iframe_video        = $params['iframe_video'];
          $cmsPost->taxonomy_id         = $params['taxonomy_id'];
          $cmsPost->news_position       = $params['news_position'];
          $cmsPost->image               = $params['image'];
          $cmsPost->status              = $params['status'];
          $cmsPost->admin_created_id    = Auth::guard('admin')->user()->id;
          $cmsPost->admin_updated_id    = Auth::guard('admin')->user()->id;

          $cmsPost->save();

          if(isset($params['imagelist'])){

              $imagelist = $params['imagelist'];

              foreach($imagelist as $k => $image){

                  $CmsPostImage                = new CmsMediaImage();

                  $CmsPostImage->media_id      = $cmsPost->id;

                  $CmsPostImage->link_image    = $image;

                  $CmsPostImage->status        = 'active';

                  $CmsPostImage->save();

              }
          }

          if(isset($params['documentlist'])){

              $documentlist = $params['documentlist'];

              foreach($documentlist as $key => $document){

                  $CmsPostDocument                = new CmsMediaVideo();

                  $CmsPostDocument->media_id      = $cmsPost->id;

                  $CmsPostDocument->link_file     = $document;

                  $CmsPostDocument->status        = 'active';

                  $CmsPostDocument->save();

              }
          }

          $check_url = CmsMedia::where('url_part',$cmsPost->url_part)->where('id','!=',$cmsPost->id)->first();

          if($check_url){
              $params['url_part'] = $cmsPost->url_part.'-'.$cmsPost->id;
              $cmsPost -> url_part = $params['url_part'];
              $cmsPost -> save();
          }

      return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CmsMedia  $cmsMedia
     * @return \Illuminate\Http\Response
     */
    public function show(CmsMedia $cmsMedia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CmsMedia  $cmsMedia
     * @return \Illuminate\Http\Response
     */
    public function edit(CmsMedia $cmsMedia)
    {
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
          }
          $images = CmsMediaImage::where('media_id',$cmsMedia->id)->get();
          $this->responseData['images'] = $images;
          $document = CmsMediaVideo::where('media_id',$cmsMedia->id)->get();
          $this->responseData['document'] = $document;
          $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
          $paramTaxonomys['taxonomy'] = 'thu-vien';
          $this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
          $this->responseData['detail'] = $cmsMedia;
          $params_relative['media_id'] = explode(',',trim($cmsMedia->relation,','));
          $this->responseData['relative_posts'] = ContentService::getCmsPostRelative($params_relative)->get();
          $this->responseData['cms_posts'] = ContentService::getCmsPost(['status'=>'active','limit'=>100])->get();

          return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CmsMedia  $cmsMedia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CmsMedia $cmsMedia)
    {
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
        $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
        return $this->responseView($this->viewPart . '.404');
      }
      $request->validate([
          'title' => 'required|max:255',
          'taxonomy_id' => 'required|max:255',
      ]);

      $params = $request->all();
      
      $check_url = CmsMedia::where('url_part',$params['url_part'])->where('id','!=',$cmsMedia->id)->first();

      if($check_url){
          $params['url_part'] = $params['url_part'].'-'.$cmsMedia->id;
      }
      $params['relation'] = isset($params['relation']) ? ','.implode(",",$params['relation']).',' : '';
      
      $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

      $trangthaitin = $params['submit'];
      
      if(Auth::guard('admin')->user()->is_super_admin == 1){
          if ($trangthaitin == "gobaidang") { // Gỡ bài đăng
              $params['status'] = 'lock';
          }else if ($trangthaitin == "xuatban") { // Xuất bản
              $params['status'] = 'active';
          }else if ($trangthaitin == "choxuly") { // Chờ xử lý
              $params['status'] = 'waiting';
          }else if ($trangthaitin == "tuchoi") { // Từ chối đăng
              $params['status'] = 'deactive';
          }else if ($trangthaitin == "tralai") { // Từ trả lại
              $params['status'] = 'tralai';
          }else{
            $params['status'] = $cmsMedia->status;
          }

      }else{
          if ($trangthaitin == "gobaidang") { // Gỡ bài đăng
              $params['status'] = 'lock';
          }else if ($trangthaitin == "choxuly") { // Chờ xử lý
              $params['status'] = 'waiting';
          }else if ($trangthaitin == "tuchoi") { // Từ chối đăng
              $params['status'] = 'deactive';
          }else if ($trangthaitin == "tralai") { // Từ trả lại
              $params['status'] = 'tralai';
          }else{
            $params['status'] = $cmsMedia->status;
          }
      }

      $cmsMedia->title               = $params['title'];
      $cmsMedia->content             = $params['content'];
      $cmsMedia->url_part            = $params['url_part'];
      $cmsMedia->brief               = $params['brief'];
      $cmsMedia->iframe_video        = $params['iframe_video'];
      $cmsMedia->taxonomy_id         = $params['taxonomy_id'];
      $cmsMedia->news_position       = $params['news_position'];
      $cmsMedia->image               = $params['image'];
      $cmsMedia->status              = $params['status'];
      $cmsMedia->admin_created_id    = Auth::guard('admin')->user()->id;
      $cmsMedia->admin_updated_id    = Auth::guard('admin')->user()->id;
      $cmsMedia->save();

      CmsMediaImage::where('media_id', '=', $cmsMedia->id)->delete();
        if(isset($params['imagelist'])){
            $imagelist = $params['imagelist'];

            foreach($imagelist as $key => $image){
              $CmsPostImage                = new CmsMediaImage();
              $CmsPostImage->media_id      = $cmsMedia->id;
              $CmsPostImage->link_image    = $image;
              $CmsPostImage->status        = 'active';
              $CmsPostImage->save();
            }
        }

      CmsMediaVideo::where('media_id', '=', $cmsMedia->id)->delete();
      if(isset($params['documentlist'])){
          $documentlist = $params['documentlist'];

          foreach($documentlist as $key => $document){
              $CmsPostDocument                = new CmsMediaVideo();
              $CmsPostDocument->media_id      = $cmsMedia->id;
              $CmsPostDocument->link_file     = $document;
              $CmsPostDocument->status        = 'active';
              $CmsPostDocument->save();

          }
      }
      
      return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CmsMedia  $cmsMedia
     * @return \Illuminate\Http\Response
     */
    public function destroy(CmsMedia $cmsMedia)
    {
        if(ContentService::checkRole($this->routeDefault,'delete') == 0){
          $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
          return $this->responseView($this->viewPart . '.404');
        }

        $cmsMedia->delete();

        return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }
}
