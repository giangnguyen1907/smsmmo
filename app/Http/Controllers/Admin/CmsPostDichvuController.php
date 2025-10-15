<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\ContentService;
use App\Models\CmsDichvu;
use App\Models\CmsPostDichvu;
use App\Models\CmsMediaImage;
use App\Models\CmsMediaVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Admin;

class CmsPostDichvuController extends Controller
{
    public function __construct()
    {
      $this->routeDefault  = 'cms_posts_dichvu';
      $this->viewPart = 'admin.pages.cms_posts_dichvu';
      $this->responseData['module_name'] = __('Quản lý tin tức dịch vụ');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        /*if(Auth::guard('admin')->user()->is_super_admin == 0){
            $hidden = 1;
            $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        }
		*/
        $rows = ContentService::getCmsPostDichvuJson($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

        $paramTaxonomys['taxonomy'] = Consts::CATEGORY['service'];
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
        $params_lang['status'] = '1';
        $languages = ContentService::getCmsLanguage($params_lang)->get();
        $this->responseData['languages'] =  $languages;

        $paramTaxonomys['taxonomy'] = Consts::CATEGORY['service'];
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
              'taxonomy_id' => 'required|max:255',
          ]);
          
          $params = $request->all();

          $trangthaitin = $params['submit'];
          
          $cmsPostDichvu                      = new CmsPostDichvu();
          $cmsPostDichvu->title               = $params['title'];
          $cmsPostDichvu->content             = $params['content'];
          $cmsPostDichvu->url_part            = $params['url_part'];
          $cmsPostDichvu->brief               = $params['brief'];
          $cmsPostDichvu->iframe_video        = $params['iframe_video'];
          $cmsPostDichvu->taxonomy_id         = $params['taxonomy_id'];
          $cmsPostDichvu->news_position       = $params['news_position'];
          $cmsPostDichvu->image               = $params['image'];
          $cmsPostDichvu->status              = 'active';
          $cmsPostDichvu->admin_created_id    = Auth::guard('admin')->user()->id;
          $cmsPostDichvu->admin_updated_id    = Auth::guard('admin')->user()->id;

          $cmsPostDichvu->save();

          $check_url = CmsPostDichvu::where('url_part',$cmsPostDichvu->url_part)->where('id','!=',$cmsPostDichvu->id)->first();

          if($check_url){
              $params['url_part'] = $cmsPostDichvu->url_part.'-'.$cmsPostDichvu->id;
              $cmsPostDichvu -> url_part = $params['url_part'];
              $cmsPostDichvu -> save();
          }

      return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CmsPostDichvu  $cmsPostDichvu
     * @return \Illuminate\Http\Response
     */
    public function show(CmsPostDichvu $cmsPostDichvu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CmsPostDichvu  $cmsPostDichvu
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
          }

          $params_lang['status'] = '1';
          $languages = ContentService::getCmsLanguage($params_lang)->get();
          $this->responseData['languages'] =  $languages;

          $paramTaxonomys['taxonomy'] = Consts::CATEGORY['service'];
          $this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();

          $cmsPostDichvu = CmsPostDichvu::findOrFail($id);
          $this->responseData['detail'] = $cmsPostDichvu;

          return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CmsPostDichvu  $cmsPostDichvu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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

      $cmsPostDichvu = CmsPostDichvu::findOrFail($id);
      
      $check_url = CmsPostDichvu::where('url_part',$params['url_part'])->where('id','!=',$cmsPostDichvu->id)->first();

      if($check_url){
          $params['url_part'] = $params['url_part'].'-'.$cmsPostDichvu->id;
      }
      $params['relation'] = isset($params['relation']) ? ','.implode(",",$params['relation']).',' : '';
      
      $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

      $trangthaitin = $params['submit'];
      
      $cmsPostDichvu->title               = $params['title'];
      $cmsPostDichvu->content             = $params['content'];
      $cmsPostDichvu->url_part            = $params['url_part'];
      $cmsPostDichvu->brief               = $params['brief'];
      $cmsPostDichvu->iframe_video        = $params['iframe_video'];
      $cmsPostDichvu->taxonomy_id         = $params['taxonomy_id'];
      $cmsPostDichvu->news_position       = $params['news_position'];
      $cmsPostDichvu->image               = $params['image'];
      $cmsPostDichvu->status              = 'active';
      $cmsPostDichvu->admin_created_id    = Auth::guard('admin')->user()->id;
      $cmsPostDichvu->admin_updated_id    = Auth::guard('admin')->user()->id;
      $cmsPostDichvu->save();

      return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CmsPostDichvu  $cmsPostDichvu
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(ContentService::checkRole($this->routeDefault,'delete') == 0){
          $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
          return $this->responseView($this->viewPart . '.404');
        }

        $cmsPostDichvu = CmsPostDichvu::findOrFail($id);

        $cmsPostDichvu->delete();

        return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }
}
