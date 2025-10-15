<?php
namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\ContentService;
use App\Models\CmsPost;
use App\Models\History;
use App\Models\CmsPostImage;
use App\Models\CmsPostDocument;
//use App\Models\TermTaxonomy;
//use App\Models\PostTemp;
use App\Models\Royaltie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Admin;

class CmsPostController extends Controller
{
    public function __construct()
    {
      $this->routeDefault  = 'cms_posts';
      $this->viewPart = 'admin.pages.cms_posts';
      $this->responseData['module_name'] = __('Post Management');
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
      //echo $task;
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
      }
	  
      // 1: chờ biên tập, 2: chờ duyệt, 3: Đã xuất bản, 4: Từ chối, 5: Trả lại, 6: đã gỡ, 7: tin nháp 
      if($task == 'pending'){
          $params['status'] = Consts::POST_STATUS['pending'];
          $this->responseData['module_name'] = 'Danh sách tin chờ biên tập';
      }else if($task == 'waiting'){
          $params['status'] = Consts::POST_STATUS['waiting'];
          $this->responseData['module_name'] = 'Danh sách tin chờ xuất bản';
      }else if($task == 'active'){
          $params['status'] = Consts::POST_STATUS['active'];
          $this->responseData['module_name'] = 'Danh sách tin đã đăng';
      }else if($task == 'deactive'){
          $params['status'] = Consts::POST_STATUS['deactive'];
          $this->responseData['module_name'] = 'Danh sách bị từ chối';
      }else if($task == 'rollback'){
          $params['status'] = Consts::POST_STATUS['rollback'];
          $this->responseData['module_name'] = 'Danh sách bị trả lại';
      }else if($task == 'lock'){
          $params['status'] = Consts::POST_STATUS['lock'];
          $this->responseData['module_name'] = 'Danh sách tin đã gỡ';
      }else if($task == 'draft'){
        $params['status'] = Consts::POST_STATUS['draft'];
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $this->responseData['module_name'] = 'Tin nháp của tôi';
      }else{
          $params['status'] = Consts::POST_STATUS['active'];
          $_REQUEST['task'] = 'active';
          $this->responseData['module_name'] = 'Danh sách tin đã đăng';
      }
      */
      // Get list post with filter params
      $rows = ContentService::getCmsPostJson($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
      
      $paramTaxonomys['taxonomy'] = 'news';
      $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
      $this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
      
	    $this->responseData['admins'] = Admin::where('id','>',0)->get();
      
      $this->responseData['hidden'] = $hidden;
      $this->responseData['rows'] =  $rows;
      $this->responseData['params'] = $params;
      $this->responseData['booleans'] = Consts::TITLE_BOOLEAN;
      $this->responseData['postStatus'] = Consts::STATUS;

      return $this->responseView($this->viewPart . '.index');
    }

    public function listFeatured(Request $request)
    {
      //echo $this->routeDefault;
      $url_full = url()->full();
      $url_admin = url('').'/admin/';
      $routeDefault = str_replace($url_admin,'',$url_full);
      
      if(ContentService::checkRole($routeDefault,'update') == 0 && ContentService::checkRole($routeDefault,'create') == 0){
        $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
        return $this->responseView($this->viewPart . '.404');
      }
      /*
      // Convert data
      $term_taxonomys = TermTaxonomy::where('object_type','post')->orderBy('object_id','asc')->get();
      $array_post = array();
      foreach($term_taxonomys as $term_taxonomy){
          if(isset($array_post[$term_taxonomy->object_id])){
              $array_post[$term_taxonomy->object_id] = $array_post[$term_taxonomy->object_id].$term_taxonomy->term_taxonomy_id.',';
          }else{
              $array_post[$term_taxonomy->object_id] = ','.$term_taxonomy->term_taxonomy_id.',';
          }
      }
      //dd($array_post);
      $psPosts = PostTemp::where('id','>',0)->get();
      foreach($psPosts as $psPost){

          $tb_cms_post = CmsPost::where('id',$psPost->id)->first();
          if(!$tb_cms_post){
              $tb_cms_post = new CmsPost();
          }
          $tb_cms_post -> id = $psPost->id;
          $tb_cms_post -> title = $psPost->title_vi;
          $tb_cms_post -> brief = $psPost->brief_vi;
          $tb_cms_post -> content = $psPost->content_vi;
          $tb_cms_post -> is_type = $psPost->post_type;
          $tb_cms_post -> status = $psPost->post_status;
          $tb_cms_post -> image = $psPost->image;
          $tb_cms_post -> image_thumb = $psPost->image_thumb;
          $tb_cms_post -> aproved_date = $psPost->aproved_date;
          $tb_cms_post -> news_position = $psPost->news_position;
          $tb_cms_post -> author = $psPost->author;
          $tb_cms_post -> url_coppy = $psPost->url_coppy;
          $tb_cms_post -> iorder = $psPost->iorder;
          $tb_cms_post -> torder = $psPost->torder;
          $tb_cms_post -> number_like = $psPost->number_like;
          $tb_cms_post -> number_view = $psPost->number_view;
          $tb_cms_post -> meta_title = $psPost->meta_title;
          $tb_cms_post -> meta_keyword = $psPost->meta_keyword;
          $tb_cms_post -> meta_description = $psPost->meta_description;
          $tb_cms_post -> tukhoa = $psPost->tukhoa;
          $tb_cms_post -> view_ngay = $psPost->view_ngay;
          $tb_cms_post -> view_tuan = $psPost->view_tuan;
          $tb_cms_post -> view_thang = $psPost->view_thang;

          if(isset($array_post[$psPost->id])){
              $txt_id = trim($array_post[$psPost->id],',');
              $tb_cms_post -> taxonomy_id =substr($txt_id,0,1) ;
              $tb_cms_post -> category = $array_post[$psPost->id];
          }
          
          $tb_cms_post -> save();

      }
      
      // Cap nhat json
      $cmsPosts = CmsPost::where('id','>',0)->get();
      foreach($cmsPosts as $cmsPost){
          /*
          $array_parram['content']['vi'] = $cmsPost->content;
          $array_parram['brief']['vi'] = $cmsPost->brief;
          $array_parram['seo_title'] = $cmsPost->meta_title;
          $array_parram['seo_keyword'] = $cmsPost->meta_keyword;
          $array_parram['seo_description'] = $cmsPost->meta_description;
          $array_parram['refer'] = $cmsPost->refer;

          $cmsPost -> json_params = Json_encode($array_parram);
          
          $url_part = Str::slug( $cmsPost->title);
          $cmsPost -> url_part = $url_part;
          $cmsPost -> save();
      }
      */

      $task = $request->get('task');
      
      $update_iorder = $request->get('update_iorder');
      $vitri = $request->get('vitri');

      $params = $request->all();
      $params['is_type'] = Consts::POST_TYPE['post'];

      $params['status'] = 'active';
      
      if(!isset($params['news_position'])){
        $params['news_position'] = 2;
      }
		
		  //$params['news_position'] = '0';
		  //$params['struck'] = '>';
      $params['order_by'] = array('news_position'=>'desc', 'iorder' => 'asc', 'aproved_date'=>'desc');
      //$_REQUEST['task'] = 'tab_2';
      // Get list post with filter params
      $rows = ContentService::getCmsPost($params)->get();
      
      // $paramTaxonomys['taxonomy'] = 'news';
      $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
      $this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
      
      $this->responseData['rows'] =  $rows;
      $this->responseData['params'] = $params;
      $this->responseData['booleans'] = Consts::TITLE_BOOLEAN;
      $this->responseData['postStatus'] = Consts::STATUS;

      return $this->responseView($this->viewPart . '.list_featured');
    }

    public function updateSort()
    {
      
      $iorder = request('iorder') ?? [];
      $vitri = request('vitri') ?? [];

      $hid_remove = request('hid_remove') ?? 0;

      $stt=0;
      foreach($iorder as $post_id=>$stt_cu){
          //echo $post_id.'<br>';
          CmsPost::where('id', $post_id)->update(['iorder' => $stt]);
          $stt++;
      }

      // Xác nhận gỡ tin nổi bật
      if($hid_remove == 1){
          $str_vitri = implode(',',$vitri);
          //echo $str_vitri;die;
          CmsPost::whereRaw('id in ('.$str_vitri.')')->update(['iorder' => 0,'news_position' => 0]);
      }
      
      //return true;
      return redirect()->route('cms_posts_featured.index')->with('successMessage', __('Add new successfully!'));
    }

    public function postRelative(Request $request)
    { 
      
      $keyword = $request->keyword;
      $params['keyword'] = $keyword;

      $params['status'] = 'active';
      $params['limit'] = 30;
      $params['order_by'] = array( 'iorder' => 'asc', 'aproved_date'=>'desc');
      // Get list post with filter params
      $rows = ContentService::getCmsPost($params)->get();
      
      $paramTaxonomys['taxonomy'] = 'news';
      $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
      
      $data_post = '';
      $stt=0;
      
      foreach($rows as $post){
          $stt++;
          $data_post .= '
          <tr>
              <td>'.$post->title.'</td>
              <td>
              <input type="checkbox" onclick="checkPostReative('.$post->id.')" id="post_id_'.$post->id.'" name="check_realtive[]" value="'.$post->id.'" />
              <input type="hidden" id="post_title_'.$post->id.'" value="'.$post->title.'" />
              </td>
          </tr>
          ';
      }
      
      return $data_post;

    }

    public function loadFeatured(Request $request)
    { 
      
      $keyword = $request->keyword;
      $params['keyword'] = $keyword;

      $params['status'] = 'active';
      $params['news_position'] = 0;
      $params['limit'] = 30;
      $params['order_by'] = array( 'iorder' => 'asc', 'aproved_date'=>'desc');
      // Get list post with filter params
      $rows = ContentService::getCmsPost($params)->get();
      
      // $paramTaxonomys['taxonomy'] = 'news';
      $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
      $parents = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
      
      $array_category = array();
      foreach ($parents as $item){
          $array_category[$item->id] = $item->title;
      } 

      $data_post = '';
      $stt=0;
      
      foreach($rows as $row){
          $stt++;
          $category = explode(',',trim($row->category,','));
          $title_category = "";
          foreach($category as $cat_id){
              $title_category .= $array_category[$cat_id].', ';
          }
          $data_post .= '
          <tr class="bg-gray-light valign-middle">
              <td class="text-center">'.$stt.'</td>
              <td>'.$row->title.'</td>
              <td> '.$title_category.'</td>
              <td style="">'.date('H:i d/m/Y', strtotime($row->aproved_date)).'</td>
              <td class="text-center">
                  <input type="checkbox" name="vitri[]" id="vitri_'.$row->id.'" value="'.$row->id.'">
              </td>
          </tr>
          ';
      }
      
      //return 'Baiviet:'.count($rows).'_danhmuc:'.count($parents);
      return $data_post;

    }


    public function updateFeatured(Request $request)
    {   

      $params = $request->all();

      $post_ids = $params['vitri'];

      CmsPost::whereIn('id',$post_ids)->update(['news_position' => 2]);

      return redirect()->route('cms_posts_featured.index')->with('successMessage', __('Thêm mới tin nổi bật thành công!'));
        
    }

    // Lấy tin crawler
    public function loadCrawler(Request $request)
    {
      $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
      // $paramTaxonomys['taxonomy'] = 'news';
      $paramTag['taxonomy'] = 'tag';
      $parents = $this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
      $parents2 = $this->responseData['parents2'] = ContentService::getCmsTaxonomy($paramTag)->get();
      
      // start crawl
      $url = $request->url_craw;
      $imgBasename = time();
      
      $apiCrawl = "https://crawler.nguoimuanha.vn/article.ashx?link=";
      $detail = $this->responseData['detail'] = json_decode(file_get_contents($apiCrawl.$url));
      
      $path = 'upload/crawled/';
      
      if($detail->FeaturedImage != null){
        
        $url = $detail->FeaturedImage;
        $thumb = 'upload/crawled/'.basename($url);
        // Save image
        file_put_contents($thumb, file_get_contents($url));
        
      } else {
        $thumb = "upload/crawled_images/nopic.jpg";
      }
      $txtTitle = "getUrlPart('txtTitle','txtUrlPart')";
      $txtUrlPart = "getUrlPart('txtUrlPart','txtUrlPart')";
      $data_post = '
      <div class="col-md-8 col-lg-8 col-sm-12 col-xs-12">
        <div class="form-group">
          <div class="form-group">
          <label>Nội dung</label>
          <textarea name="content" class="form-control" id="content">'.$detail->Content.'</textarea>
          </div>
        </div>

        <div class="form-group">
          <label>Tin liên quan</label>
          <button onclick="addNew()" type="button" class="btn btn-sm btn-primary">Chọn</button>
          <ul id="list_relative">
          
          </ul>
        </div>

        <div class="form-group">
          <label>seo_title</label>
          <input name="meta_title" id="meta_title" class="form-control"
          value="'.$detail->Title.'">
        </div>
        <div class="form-group">
          <label>seo_keyword</label>
          <input name="meta_keyword" id="meta_keyword" class="form-control"
          value="'.$detail->Keywords.'">
        </div>
        <div class="form-group">
          <label>seo_description</label>
          <input name="meta_description" id="meta_description" class="form-control"
          value="'.$detail->Sapo.'">
        </div>
        </div>

        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
        <div class="form-group">
          <label>Tiêu đề <small class="text-red">*</small></label>
          <input type="text" class="form-control" name="title" placeholder="Title"
          value="'.$detail->Title.'" id="txtTitle" onchange="'.$txtTitle.'" onclick="'.$txtTitle.'" onblur="'.$txtTitle.'" required>
        </div>
        <div class="form-group">
          <label>Alias</label>
          <input type="text" class="form-control" name="url_part" placeholder="Alias"
          value=""  id="txtUrlPart" onchange="'.$txtUrlPart.'" onclick="'.$txtUrlPart.'" onblur="'.$txtUrlPart.'">
        </div>
        <div class="form-group">
          <label>Mô tả</label>
          <textarea name="brief" id="brief" class="form-control"
          rows="5">'.$detail->Sapo.'</textarea>
        </div>
        <div class="form-group">
          <label>Danh mục <small class="text-red">*</small></label>
          <select name="taxonomy_id[]" id="taxonomy_id" multiple="multiple" class="form-control select2" style="width: 100%">
          ';
          
          foreach ($parents as $item){
            if ($item->parent_id == 0 || $item->parent_id == null){
              $data_post .= '<option value="'.$item->id.'">'.$item->title.'</option>';
              foreach ($parents as $sub){
                if ($item->id == $sub->parent_id){
                  
                  $data_post .= '<option value="'. $sub->id .'">- - '.$sub->title.'</option>';

                  foreach ($parents as $sub_child){
                    if ($sub->id == $sub_child->parent_id){
                      $data_post .= '<option value="'. $sub_child->id .'">- - - -'.$sub_child->title.'</option>';
                    }
                  }
                }
              }
            }
          }
          $data_post .= '</select>
        </div>

        <div class="form-group">
          <label>Tag </label>
          <select name="cms_tag[]" id="cms_tag" multiple="multiple" class="form-control select2" style="width: 100%">
          ';
          foreach ($parents2 as $item2){
            if ($item2->parent_id == 0 || $item2->parent_id == null){
            $data_post .= '<option value="'.$item2->id.'">
              '.$item2->title.'</option>';

            foreach ($parents2 as $sub2){
              if ($item2->id == $sub2->parent_id){
                $data_post .= '<option value="'.$sub2->id.'">- - '.$sub2->title .'</option>';

                foreach ($parents2 as $sub_child2){
                  if ($sub->id == $sub_child2->parent_id){
                  $data_post .= '<option value="'.$sub_child2->id .'">- - - -'. $sub_child2->title .'</option>';
                  }
                }
              }
            }
            }
          }
          $data_post .= '</select>
        </div>
        <div class="form-group">
          <label>Tác giả <small class="text-red">*</small></label>
          <input type="text" class="form-control" id="author" name="author" placeholder="Tác giả"
          value="'.$detail->Author.'">
        </div>
        <div class="form-group">
          <label>Nguồn tham khảo </label>
          <input type="text" class="form-control" name="json_params[refer]" placeholder="Nguồn tham khảo"
          value="">
        </div>
        <div class="form-group">
          <label>Đường dẫn </label>
          <input type="text" class="form-control" name="url_coppy" placeholder="Đường dẫn"
          value="'.$url.'">
        </div>
        <div class="form-group">
          <label>Thời gian xuất bản </label>
          <input class="form-control" name="aproved_date" id="aproved_date" type="datetime-local" value="'.date('Y-m-d\TH:i').'" />
        </div>
        <div class="form-group">
          <label>Image</label>
          <div class="input-group">
          <span class="input-group-btn">
            <a data-input="image" data-preview="image-holder" class="btn btn-primary lfm"
            data-type="cms-image">
            <i class="fa fa-picture-o"></i> choose
            </a>
          </span>
          <input id="image" class="form-control" value="/'.$thumb.'" type="text" name="image"
            placeholder="image_link...">
          </div>
          <div id="image-holder" style="margin-top:15px;max-height:100px;">
          <img style="height: 5rem;" src="/'.$thumb.'">
          </div>
        </div>
        <div class="form-group">
          <label>Image thumb</label>
          <div class="input-group">
          <span class="input-group-btn">
            <a data-input="image_thumb" data-preview="image_thumb-holder" class="btn btn-primary lfm"
            data-type="cms-image">
            <i class="fa fa-picture-o"></i> choose
            </a>
          </span>
          <input id="image_thumb" class="form-control" type="text" name="image_thumb"
            placeholder="image_link...">
          </div>
          <div id="image_thumb-holder" style="margin-top:15px;max-height:100px;">
          
          </div>
        </div>

        </div>
        
        <div class="col-md-12">
        <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
        </div>
        
        <script>
          
          ClassicEditor.create( document.querySelector( "#content" ), {
            toolbar: {
            items: [
              "CKFinder","|",
              "heading",
              "bold",
              "link",
              "italic",
              "|",
              "blockQuote",
              "alignment:left", "alignment:right", "alignment:center", "alignment:justify",
              "insertTable",
              "undo",
              "redo",
              "bulletedList",
              "numberedList",
              "mediaEmbed",
              "fontBackgroundColor",
              "fontColor",
              "fontSize",
              "fontFamily"
            ]
            },
            language: "vi",
            image: {
            toolbar: ["imageTextAlternative", "|", "imageStyle:alignLeft", "imageStyle:full","imageStyle:side", "imageStyle:alignCenter"],
            styles: [
              "full",
              "side",
              "alignCenter",
              "alignLeft",
              "alignRight"
            ]
            },
            table: {
            contentToolbar: [
              "tableColumn",
              "tableRow",
              "mergeTableCells"
            ]
            },
            licenseKey: "",
            
            
          } )
          .then( editor => {
            window.editor = editor;
            
          } )
          .catch( error => {
            console.error( "Oops, something went wrong!" );
            console.error( "Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:" );
            console.warn( "Build id: v10wxmoi2tig-mwzdvmyjd96s" );
            console.error( error );
          } );
        </script>

        <style>
          div.ck-editor__editable {
          height: 705px !important;
          overflow: scroll;
          }
        </style>
      ';
      
          return $data_post;
      
    }
    // Cập nhật vị trí -- ajax --
    public function updatePosition(Request $request)
    {   
      $id = $request->id;
      $position = $request->position;
          
      CmsPost::where('id',$id)->update(['news_position' => $position]);
      
      return $id;
      
    }
	
    
    // Cập nhật vị trí -- ajax --
    public function updateOrder(Request $request)
    {   
      $id = $request->id;
      $stt_order = $request->stt_order;
          
      CmsPost::where('id',$id)->update(['iorder' => $stt_order]);
      
      return $id;
      
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

      $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
      $paramTaxonomys['taxonomy'] = 'news';
      $paramTag['taxonomy'] = 'tag';
      $this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
      $this->responseData['parents2'] = ContentService::getCmsTaxonomy($paramTag)->get();
      // Lấy các tin liên quan
      $this->responseData['cms_posts'] = ContentService::getCmsPost(['status'=>'active','limit'=>100])->get();

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

      $content = [];
      foreach ($request->input('content') as $language => $translation) {
          $content[$language] = $translation;
      }
      $params['content'] = $content;

      $title = [];
      foreach ($request->input('title') as $language_title => $translation_title) {
          $title[$language_title] = $translation_title;
      }
      $params['title'] = $title;

      $brief = [];
      foreach ($request->input('brief') as $language_brief => $translation_brief) {
          $brief[$language_brief] = $translation_brief;
      }
      $params['brief'] = $brief;

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
			  $params['status'] = 'active';
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
              $params['status'] = 'active';
          }
      }

      $cmsPost                      = new CmsPost();
      $cmsPost->title               = $title;
      $cmsPost->content             = $content;
      $cmsPost->url_part            = $params['url_part'];
      $cmsPost->brief               = $brief;
      $cmsPost->iframe_video        = $params['iframe_video'];
      $cmsPost->taxonomy_id         = $params['taxonomy_id'];
      $cmsPost->news_position       = $params['news_position'];
      $cmsPost->image               = $params['image'];
      $cmsPost->status              = $params['status'];
      $cmsPost->admin_created_id    = Auth::guard('admin')->user()->id;
      $cmsPost->admin_updated_id    = Auth::guard('admin')->user()->id;

      $cmsPost->save();
      
      $check_url = CmsPost::where('url_part',$cmsPost->url_part)->where('id','!=',$cmsPost->id)->first();

      if($check_url){
          $params['url_part'] = $cmsPost->url_part.'-'.$cmsPost->id;
          $cmsPost -> url_part = $params['url_part'];
          $cmsPost -> save();
      }

      // $params['action'] = 'add';

      // History::create($params);


      return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CmsPost  $cmsPost
     * @return \Illuminate\Http\Response
     */
    public function show(CmsPost $cmsPost)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CmsPost  $cmsPost
     * @return \Illuminate\Http\Response
     */
    public function edit(CmsPost $cmsPost)
    {
      if(ContentService::checkRole($this->routeDefault,'update') == 0){
        $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
        return $this->responseView($this->viewPart . '.404');
      }

      $params_lang['status'] = '1';
      $languages = ContentService::getCmsLanguage($params_lang)->get();
      $this->responseData['languages'] =  $languages;

      $images = CmsPostImage::where('post_id',$cmsPost->id)->get();
      $this->responseData['images'] = $images;
      
      $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
      $paramTaxonomys['taxonomy'] = 'news';
      $paramTag['taxonomy'] = 'tag';
      $this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
      $this->responseData['parents2'] = ContentService::getCmsTaxonomy($paramTag)->get();
      $this->responseData['detail'] = $cmsPost;
      $params_relative['post_id'] = explode(',',trim($cmsPost->relation,','));
      $this->responseData['relative_posts'] = ContentService::getCmsPostRelative($params_relative)->get();
      $this->responseData['cms_posts'] = ContentService::getCmsPost(['status'=>'active','limit'=>100])->get();

      return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CmsPost  $cmsPost
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CmsPost $cmsPost)
    {
      if(ContentService::checkRole($this->routeDefault,'update') == 0){
        $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
        return $this->responseView($this->viewPart . '.404');
      }
      $params = $request->all();
      
      $check_url = CmsPost::where('url_part',$params['url_part'])->where('id','!=',$cmsPost->id)->first();
      if($check_url){
          $params['url_part'] = $params['url_part'].'-'.$cmsPost->id;
      }
      //$params['category'] = is_array($params['taxonomy_id']) ? ','.implode(",",$params['taxonomy_id']).',' : '';
      $params['relation'] = isset($params['relation']) ? ','.implode(",",$params['relation']).',' : '';
      
      //$params['taxonomy_id'] = $params['taxonomy_id'][0];
      $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
      //$params['brief'] = $params['json_params']['brief']['vi'];
      //$params['content'] = $params['json_params']['content']['vi'];
      //$params['aproved_date'] = strtotime($params['aproved_date']);

      $content = [];
      foreach ($request->input('content') as $language => $translation) {
          $content[$language] = $translation;
      }
      $params['content'] = $content;

      $title = [];
      foreach ($request->input('title') as $language_title => $translation_title) {
          $title[$language_title] = $translation_title;
      }
      $params['title'] = $title;

      $brief = [];
      foreach ($request->input('brief') as $language_brief => $translation_brief) {
          $brief[$language_brief] = $translation_brief;
      }
      $params['brief'] = $brief;

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
            $params['status'] = $cmsPost->status;
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
            $params['status'] = $cmsPost->status;
          }
      }

      $cmsPost->title               = $title;
      $cmsPost->content             = $content;
      $cmsPost->url_part            = $params['url_part'];
      $cmsPost->brief               = $brief;
      $cmsPost->iframe_video        = $params['iframe_video'];
      $cmsPost->taxonomy_id         = $params['taxonomy_id'];
      $cmsPost->news_position       = $params['news_position'];
      $cmsPost->image               = $params['image'];
      $cmsPost->status              = $params['status'];
      $cmsPost->admin_created_id    = Auth::guard('admin')->user()->id;
      $cmsPost->admin_updated_id    = Auth::guard('admin')->user()->id;
      $cmsPost->save();

      // CmsPostImage::where('post_id', '=', $cmsPost->id)->delete();

      //   if(isset($params['imagelist'])){
      //       $imagelist = $params['imagelist'];

      //       foreach($imagelist as $key => $image){
      //         $CmsPostImage                = new CmsPostImage();
      //         $CmsPostImage->post_id       = $cmsPost->id;
      //         $CmsPostImage->link_image    = $image;
      //         $CmsPostImage->status        = 'active';
      //         $CmsPostImage->save();
      //       }
      //   }

      // CmsPostDocument::where('post_id', '=', $cmsPost->id)->delete();
      // if(isset($params['documentlist'])){
      //     $documentlist = $params['documentlist'];

      //     foreach($documentlist as $key => $document){
      //         $CmsPostDocument                = new CmsPostDocument();
      //         $CmsPostDocument->post_id       = $cmsPost->id;
      //         $CmsPostDocument->link_file     = $document;
      //         $CmsPostDocument->status        = 'active';
      //         $CmsPostDocument->save();

      //     }
      // }
      
	  /*
      if(Auth::guard('admin')->user()->is_super_admin == 1 and $cmsPost->status == 'active'){
          
          $nhuanbut = $cmsPost->nhuanbut;
          $royalties = Royaltie::where('post_id',$cmsPost->id)->first();
          if($royalties){
              $royalties -> price = $nhuanbut;
              $royalties -> status = 0; // Chuyển trạng thái chờ duyệt
              $royalties -> admin_updated_id = Auth::guard('admin')->user()->id;
              $royalties -> save();
          }else{
              if($nhuanbut > 0){
                  $royalties = new Royaltie();
                  $royalties -> post_id = $cmsPost->id;
                  $royalties -> price = $nhuanbut;
                  $royalties -> status = 0;
                  $royalties -> admin_created_id = Auth::guard('admin')->user()->id;
                  $royalties -> admin_updated_id = Auth::guard('admin')->user()->id;
                  $royalties -> save();
              }
          }
      }
	  */


      // $params['post_id'] = $cmsPost->id;
      // $params['action'] = 'edit';
      // History::create($params);

      

      return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CmsPost  $cmsPost
     * @return \Illuminate\Http\Response
     */
    public function destroy(CmsPost $cmsPost)
    {
      if(ContentService::checkRole($this->routeDefault,'delete') == 0){
          $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
          return $this->responseView($this->viewPart . '.404');
      }
	  //dd($cmsPost);
	  
	  /*
      // check is type post
      if ($cmsPost->is_type != Consts::POST_TYPE['post']) {
          return redirect()->back()->with('errorMessage', __('Permission denied!'));
      }
		
      $cmsPost->status = 'lock'; // Chuyển trạng thái đã gỡ
      $cmsPost -> admin_updated_id = Auth::guard('admin')->user()->id;
      //$cmsPost->save();

      $params['post_id'] = $cmsPost->id;
      $params['action'] = 'delete';
      $params['title'] = $cmsPost->title;
      $params['brief'] = $cmsPost->brief;
      $params['taxonomy_id'] = $cmsPost->taxonomy_id;
      $params['content'] = $cmsPost->content;
      $params['is_type'] = $cmsPost->is_type;
      $params['json_params'] = $cmsPost->json_params;
      $params['image'] = $cmsPost->image;
      $params['image_thumb'] = $cmsPost->image_thumb;
      $params['status'] = $cmsPost->status;
      $params['author'] = $cmsPost->author;
      $params['iorder'] = $cmsPost->iorder;
      $params['url_coppy'] = $cmsPost->url_coppy;
      $params['url_part'] = $cmsPost->url_part;
      $params['torder'] = $cmsPost->torder;
      $params['number_comment'] = $cmsPost->number_comment;
      $params['number_like'] = $cmsPost->number_like;
      $params['number_view'] = $cmsPost->number_view;
      $params['view_ngay'] = $cmsPost->view_ngay;
      $params['view_tuan'] = $cmsPost->view_tuan;
      $params['view_thang'] = $cmsPost->	view_thang;
      $params['nhuanbut'] = $cmsPost->nhuanbut;
      $params['aproved_date'] = $cmsPost->aproved_date;
      $params['category'] = $cmsPost->category;
      $params['cms_tag'] = $cmsPost->cms_tag;
      $params['new_position'] = $cmsPost->new_position;
      $params['relation'] = $cmsPost->relation;
      $params['seo_title'] = $cmsPost->seo_title;
      $params['seo_keyword'] = $cmsPost->seo_keyword; 
      $params['seo_description'] = $cmsPost->seo_description;
      $params['admin_created_id'] = Auth::guard('admin')->user()->id;
      $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

      History::create($params);
      */
	  $cmsPost->delete();
	  
      return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }
}
