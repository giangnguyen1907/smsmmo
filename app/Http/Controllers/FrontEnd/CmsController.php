<?php

namespace App\Http\Controllers\FrontEnd;

use App\Consts;
use App\Http\Services\ContentService;
use App\Http\Services\PageBuilderService;
use App\Models\BlockContent;
use App\Models\CmsBlog;
use App\Models\CmsDichvu;
use App\Models\CmsPost;
use App\Models\Profile;
use App\Models\CmsPostDocument;
use App\Models\Document;
use App\Models\CmsService;
use App\Models\Comment;
use App\Models\User;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Spatie\PdfToText\Pdf;
use App\Models\EbookPackage;
use App\Models\CmsHistoryBuyebook;

class CmsController extends Controller
{

    // Load voucher
    public function loadVoucher(Request $request)
    {
        $p = $request->p;
        $pr = [];
        $pr['code'] = trim($p);
        $text = '';
        if(trim($p) != ""){

            $listVoucher = Voucher::getVoucher($p,date('Y-m-d H:i:s'));
            
            if($listVoucher){
                return $listVoucher->id.'_'.$listVoucher->discount;
            }else{
                return 0; // Không có mã
            }

        }else{
            return -1; // Chưa nhập mã
        }
           
    }

	// Load các quận huyện
	public function loadDistrict(Request $request)
    {
		$p = $request->id;
		$text = '';
		if($p > 0){
			$listDistrict = District::getDistrict($p);
			
			foreach($listDistrict as $district){
				$text .="<option value='".$district->s_code."'>".$district->name."</option>"; 
			}
		}
		
		return $text;
		
	}
	
	// Load các xã
	public function loadWard(Request $request)
    {
		$p = $request->id;
		$text = '';
		if($p > 0){
			$listWard = Ward::getWard($p);
			foreach($listWard as $ward){
				$text .="<option value='".$ward->s_code."'>".$ward->name."</option>"; 
			}
		}
		return $text;
		
	}
	
    //service
    public function service($alias)
    {
        if ($alias != '' && $alias != 'service.html') {
            $url_part = str_replace('.html', '', $alias);
            
            $serviceParent = CmsDichvu::where('url_part', $url_part)
                ->where('status', 'active')
                ->first();

            $this->responseData['serviceParent'] = $serviceParent;

            $serviceChild = CmsDichvu::where('parent_id', $serviceParent->id)
                ->where('status', 'active')
                ->get();

            $this->responseData['serviceChild'] = $serviceChild;

            return $this->responseView('frontend.pages.home.service');
        }

        if ($alias == 'service.html') {
            $url_part = 'manicure';
            
            $serviceParent = CmsDichvu::where('url_part', $url_part)
                ->where('status', 'active')
                ->first();

            $this->responseData['serviceParent'] = $serviceParent;

            $serviceChild = CmsDichvu::where('parent_id', $serviceParent->id)
                ->where('status', 'active')
                ->get();

            $this->responseData['serviceChild'] = $serviceChild;

            return $this->responseView('frontend.pages.home.service');
        }
    }
	
	
	public function cmsDocument($alias = null, Request $request)
    {
        //$id = $request->get('id')  ?? null;
		$paramDoc['status'] = 1;
		$this->responseData['taxonomy'] = array();
		if ($alias != "" ) {
            $params['url_part'] = str_replace('.html','',$alias);
            $params['status'] = Consts::TAXONOMY_STATUS['active'];
            $params['taxonomy'] = 'document';
            $taxonomy = ContentService::getCmsTaxonomy($params)->first();
			
			if ($taxonomy) {
				$id=$taxonomy->id;
				
				$paramDoc['category'] = $id;
				
				$this->responseData['taxonomy'] = $taxonomy;
			}
			
		}
		
		$this->responseData['posts'] = ContentService::getDocument($paramDoc)->paginate(Consts::POST_PAGINATE_LIMIT);
			
		
        return $this->responseView('frontend.pages.document.category');
    }
    
	public function cmsDocumentDetail($alias = null, Request $request)
    {
        //$id = $request->get('id')  ?? null;
        
        if ($alias != '') {

            $params['alias'] = str_replace('.html','',$alias);
			
            $params['status'] = 1;
            //dd($params);
            $detail = ContentService::getDocument($params)->first();
            //dd($detail);
            if ($detail) {
                
				$category_id = $detail->category;
				
                $id = $detail->id;
                $detail->view = $detail->view + 1;
                $detail->save();

                $this->responseData['detail'] = $detail;

                //lấy ra comments liên quan đến document
                $comments = $detail->cmsComments()
                    ->where('status', 0)
                    ->paginate(3);
                $this->responseData['comments'] = $comments;
				
				$params_relative['relative_id'] = $id;
				$params_relative['status'] = 1;
				$params_relative['category'] = $category_id;
				$this->responseData['posts'] = ContentService::getDocument($params_relative)->limit(8)->get();

                $ebook = EbookPackage::where('status', 1)->get();
                $this->responseData['ebook'] = $ebook;
				
                if (Auth::check()) {
                    $isValid = CmsHistoryBuyebook::isReadValid(Auth::user()->id, $detail->id);
					//dd($isValid);
                    $this->responseData['limit_page'] = $isValid ? '' : base64_encode($detail->limit_page);
					$this->responseData['isValid'] = $isValid;
					
                } else {
                    $this->responseData['limit_page'] = base64_encode($detail->limit_page);
                }
				
                return $this->responseView('frontend.pages.document.detail');
            }
        }

        return redirect()->back()->with('errorMessage', __('not_found'));
    }
	
    //news (blogs)
    public function blog($alias = null,Request $request)
    {   
        return $this->responseView('frontend.pages.home.blog');
    }

    public function blogDetail ($alias = null) 
    {
        $blog = ContentService::getCmsPost(['status' => 'active', 'url_part' => $alias ])->first();
        
        $blog->view += 1;
        $blog->save();

        $this->responseData['blog'] = $blog;
		
        $relatedBlogs = CmsPost::where('taxonomy_id', $blog->taxonomy_id)
            ->where('id', '!=', $blog->id)
            ->where('news_position', '0')
            ->where('status', 'active')
            ->paginate(2);


        $this->responseData['relatedBlogs'] = $relatedBlogs;

        return $this->responseView('frontend.pages.home.blog_detail');
    }
	
    public function language(Request $request){

        $lang = request('lang') ?? '';

         // Kiểm tra xem phiên đã bắt đầu chưa
        if (!session()->has('language')) {
            // Nếu phiên chưa tồn tại, gán giá trị mặc định là 'vi'
            session(['language' => '']);
        }
		session(['locale' => $lang]);
		//echo $lang;die;
		App::setLocale($lang) ;
		
		return $this->responseView('frontend.pages.home.index');
		//return redirect()->back();
		
    }
    public function countdownload(Request $request)
    {
        $id = request('id') ?? '';

        $id_document = request('id_document') ?? '';

        $count = CmsPost::where('id',$id)->first();

        $link_document = CmsPostDocument::where('id',$id_document)->first();

        $link = $link_document->link_file;

        if($count){

            $click_new = $count->number_download + 1;
            $count->number_download = $click_new;
            $count->save();
            echo $link;
        }
    }

    public function search_document(Request $request)
    {

        $keyword = $request->search ?? '';

        $params['keyword'] = $keyword;
        $params['status'] = 1;

        $searchDocuments = ContentService::getDocument($params)->paginate(12);

        // dd($documents);
        $this->responseData['keyword'] = $keyword;
        $this->responseData['searchDocuments'] = $searchDocuments;

        return $this->responseView('frontend.pages.document.search');
    }

    public function search_advanced(Request $request)
    {
        $params = $request->all();
        $keywords = [];

        // dd($params);
        // $query = ContentService::getSearchDocument(array());
        $query = Document::select('tb_document.*')
            ->leftJoin('tb_cms_author', 'tb_cms_author.id', '=', 'tb_document.main_author')
            ->leftJoin('tb_manager_shop', 'tb_manager_shop.id', '=', 'tb_document.publisher');
            

        $titleColumns = [
            'title' => 'tb_document.title',
            'author' => 'tb_cms_author.title',
            'publisher' => 'tb_manager_shop.title',
            'country' => 'tb_document.country',
            'publish_paper' => 'tb_document.publishing_year',
            'publish_ebook' => 'tb_document.publishing_year_ebook',
        ];

        // kiểm tra xem input có data ko
        $hasInput = false;
        for ($i = 1; $i <= 4; $i++) {
            if (isset($params["field$i"]) && $params["field$i"] !== '') {
                $hasInput = true;
                break;
            }
        }

        $category = $params['category'] ?? ''; 
        $input_category = $params['input_category'] ?? '';
		
		if(isset($category) and $category > 0){
			$query->where('tb_document.category', $category);
		}
		
		if(isset($input_category) and $input_category!=""){
			$query->where('tb_document.title', 'like', '%' .$params['input_category']. '%')
                ->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_document.category');
		}
		/*
        if (!empty($category) && !empty($input_category)) {
            $keywords[] = $input_category;

            $query->where('tb_cms_taxonomys.id', $category)
                ->where('tb_document.title', 'like', '%' .$params['input_category']. '%')
                ->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_document.category');
        } else if (empty($category) && isset($params['input_category'])) {
            $keywords[] = $input_category;

            $query->where('tb_document.title', 'like', '%' .$params['input_category']. '%');
        }
		*/
        if ($params) {
            for ($i = 1; $i <= 4; $i++) {
                $collection = $params["colection$i"] ?? '';
                $field = $params["field$i"] ?? '';
                $logicalOperator = $params["logical_operator$i"] ?? '';

                // lưu keywords để hiển thị title in đậm
                if (!empty($field)) {
                    $keywords[] = $field;
                }
    
                // kiểm tra điều kiện and, or trước khi query
                if ($collection && $field) {
                    if (isset($titleColumns[$collection])) {
                        if ($i > 1) {
                            $query->{$logicalOperator == 'and' ? 'where' : 'orWhere'}(function ($query) use ($titleColumns, $collection, $field, $logicalOperator) {
                                $query->{$logicalOperator == 'and' ? 'where' : 'orWhere'}($titleColumns[$collection], 'like', '%' .$field. '%');
                            });
                        } else {
                            $query->where($titleColumns[$collection], 'like', '%' .$field. '%');
                        }
                    }
                }
            }
        }

        if (!$hasInput && empty($category) && empty($input_category)) {
            $searchDocuments = [];
        } else {
            $searchDocuments = $query->get();
        }

        // $searchDocuments = $query->get();
        // dd($searchDocuments);  
        
        $this->responseData['params'] = $params;
        $this->responseData['searchDocuments'] = $searchDocuments;
        $this->responseData['keywords'] = $keywords;

        return $this->responseView('frontend.pages.document.search_advanced');
    }

    public function likeDocument(Request $request)
    {
        if (Auth::check()) {
            $user = User::find(auth()->user()->id);

            if (isset($user->like_document) && in_array($request->id, $user->like_document)) {
                $user->removeFavorites($request->id);
                return response()->json(['message'=> 'Document removed from favorites']);
            } else {
                $user->addFavorites($request->id);
                return response()->json(['message'=> 'Document added to favorites']);
            }
        }
    }

    public function listLikeDocument(Request $request)
    {
        if (Auth::check()) {
            // dd(auth()->user()->like_document);
            $listDocumentIds = auth()->user()->like_document;
            if (!$listDocumentIds) {
            }
    
            $listDocuments = Document::whereIn('id', $listDocumentIds)
                ->where('status', 1)    
                ->paginate(8);
    
            $this->responseData['listDocuments'] = $listDocuments;
            return $this->responseView('frontend.pages.document.list_like_document');
        } else {
            return redirect()->route('frontend.home');
        }
    }

    public function allDocument(Request $request)
    {
        $params['status'] = 1;
		
		$this->responseData['posts'] = ContentService::getDocument($params)->paginate(12);

        return $this->responseView('frontend.pages.document.all_document');
    }

    public function downloadFile(Request $request)
    {
        if ($request->has('file')) {
            $filePath = base64_decode($request->input('file'));
            $params['filepdf'] = $filePath;
            $params['status'] = 1;
            $document = ContentService::getDocument($params)->first();
            
            if (file_exists(public_path($filePath))) {
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="downloaded.pdf"');
                
                readfile(public_path($filePath));

                $document->download++;
                $document->save();
                exit;
            } else {
                return response()->json(['error' => 'File not found'], 404);
            }
        } else {
            return response()->json(['error' => 'Invalid request'], 400);
        }
    }

    public function readPdf(Request $request) 
    {
        if ($request->has('file')) {
            $filePath = base64_decode($request->input('file'));

            if (file_exists(public_path($filePath))) {
                header('Content-Type', 'application/pdf');
                header('Content-Disposition: inline; filename="' . basename($filePath) . '"');
                header('Content-length: ' . filesize(public_path($filePath)));

                readfile(public_path($filePath));
                exit;
            } else {
                echo 'File not Found';
            }
        } else {
            echo 'Invalid request';
        }

    }

    public function cmsComment(Request $request) 
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ], [
            'name.required' => 'Tên không được để trống',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ'
        ]);

        try {
            $params['member_name'] = $request->name;
            $params['email_user'] = $request->email;
            $params['content'] = $request->comment;
            $params['post_id'] = $request->postId;
            $params['is_type'] = 'document';
            $params['user_id'] = auth()->user()->id;
    
            $comment = Comment::create($params);

            return response()->json(['message' => 'Bình luận đã được gửi đi!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function cmsPostComment(Request $request) 
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ], [
            'name.required' => 'Tên không được để trống',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ'
        ]);

        try {
            $params['member_name'] = $request->name;
            $params['email_user'] = $request->email;
            $params['content'] = $request->comment;
            $params['post_id'] = $request->postId;
            $params['is_type'] = 'news';
            $params['user_id'] = auth()->user()->id;
    
            $comment = Comment::create($params);

            return response()->json(['message' => 'Bình luận đã được gửi đi!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function post($alias_detail = null, Request $request)
    {
        //$id = $request->get('id')  ?? null;
        
        if ($alias_detail != '') {

            $params['url_part'] = str_replace('.html','',$alias_detail);
            //$params['url_part'] = $alias_detail;
            $params['status'] = Consts::POST_STATUS['active'];
            $params['is_type'] = Consts::POST_TYPE['post'];
            $params['aproved_date'] = date('Y-m-d H:i:s');
            //dd($params);
            $detail = ContentService::getCmsPost($params)->first();
            
            if ($detail) {
                //dd($alias_detail);
                $id = $detail->id;
                
                $detail->number_view = $detail->number_view + 1;
                
                $detail->save();

                $this->responseData['detail'] = $detail;

                //lấy ra comments liên quan đến post
                $comments = $detail->cmsComments()
                    ->where('status', 0)
                    ->paginate(3);
                $this->responseData['comments'] = $comments;

				$id = $detail->id;
				$params_relative['id'] = $id;
				$params_relative['taxonomy_id'] = $detail->taxonomy_id;
				$params_relative['status'] = Consts::POST_STATUS['active'];
				$params_relative['is_type'] = Consts::POST_TYPE['post'];
                $this->responseData['posts'] = ContentService::getCmsPostRelative($params_relative)->limit(Consts::DOCTOR_OTHER_LIMIT)->get();

                $paramstaxo['id'] = $detail->taxonomy_id;
                $paramstaxo['status'] = Consts::TAXONOMY_STATUS['active'];
                $this->responseData['taxonomy'] = ContentService::getCmsTaxonomy($paramstaxo)->first();
				
                return $this->responseView('frontend.pages.post.detail');
            }
        }

        return redirect()->back()->with('errorMessage', __('not_found'));
    }

    public function authorsDocuments(Request $request) 
    {
        $id = $request->id;
        $posts = Document::where('status', 1)
            ->where('main_author', $id)
            ->orWhere('authors', 'like', '%,' . $id . ',%')
            ->paginate(12);
        
        $this->responseData['posts'] = $posts;
        return $this->responseView('frontend.pages.document.authors_documents');
    }

    public function allAuthor()
    {
        return $this->responseView('frontend.pages.document.all_author');
    }

    public function viewMore()
    {

        $txt_post = session('post_id');
        
        //$articles = request('articles') ?? '';
        $trang = request('trang') ?? '';
        $banghibandau = request('row') ?? '';
        $limit = request('limit') ?? '';

        $locale = 'vi';

        $paramPost['status']='active';
        $paramPost['limit']=$limit;
        $paramPost['is_type'] = Consts::POST_TYPE['post'];
        $paramPost['order_by']= array( 'news_position'=>'desc', 'iorder' => 'asc', 'aproved_date'=>'desc' );
        
        $article_id = $txt_post;

        $dsTinTuc = ContentService::getCmsPostLoading($paramPost,trim($txt_post,','))->get();
        $data_post = '';
        foreach($dsTinTuc as $item){
            $title = $item->json_params->title->{$locale} ?? $item->title;
            $brief = $item->json_params->brief->{$locale} ?? $item->brief;
            $image = $item->image_thumb != '' ? $item->image_thumb : ($item->image != '' ? $item->image : null);
            $date = date('H:i d/m/Y', strtotime($item->created_at));
            // Viet ham xu ly lay alias bai viet
            $alias_detail = $item->url_part ? $item->url_part : Str::slug($title);
            
            $url_link = route('frontend.cms.post', ['alias_detail' => $alias_detail]) . '.html';
            $article_id.=$item->id.',';
            $author = $item->author !='' ? $item->author : $item->fullname;
            $hienthingay = ContentService::postTime($item->aproved_date);
            $avatar = $item->avatar !='' ? $item->avatar : '/images/noiavatar.png';
            $data_post .=' 
            <article class="story story--flex story--round " id="article'.$item->id.'">
                <div class="story__meta">
                    <div class="story__avatar">
                        <img src="'.$avatar .'" alt="'.$author.'" class="img-fluid rounded-circle">
                    </div>
                    <div class="story__info">
                        <h3 class="story__author">'.$author.'</h3>
                        <div class="story__time"><time datetime="'.$item->updated_at.'" class="time-ago">'.$hienthingay.'</time></div>
                    </div>
                </div>
                <div class="story__header">
                    <h3 class="story__title">'.$title.'</h3>
                    <div class="story__summary">
                        '.$brief.'
                        <a href="'.$url_link.'" class="view-more">Xem thêm</a>
                        <div class="post-content d-none"></div>
                    </div>
                </div>
                <div class="story__images lightgallery">
                    
                    <div data-src="'.$image.'" class="item">
                        <img src="'.$image.'" alt="'.$title.'" class="img-fluid" title="'.$title.'">
                    </div>
                        
                </div>
                <footer class="story__footer">
                    <div class="story__react share">
                        <div class="fb-like fb_iframe_widget" data-href="'.$url_link.'" data-width="" data-layout="button_count" data-action="like" data-size="small" data-share="true" fb-xfbml-state="rendered" fb-iframe-plugin-query="action=like&amp;app_id=625475154576703&amp;container_width=0&amp;href=https%3A%2F%2Fnguoimuanha.vn%2Fgia-bds-se-tiep-tuc-tang-60883.html&amp;layout=button_count&amp;locale=vi_VN&amp;sdk=joey&amp;share=true&amp;size=small&amp;width="><span style="vertical-align: bottom; width: 150px; height: 28px;"><iframe name="f1f94d842c23d74" width="1000px" height="1000px" data-testid="fb:like Facebook Social Plugin" title="fb:like Facebook Social Plugin" frameborder="0" allowtransparency="true" allowfullscreen="true" scrolling="no" allow="encrypted-media" src="https://www.facebook.com/v4.0/plugins/like.php?action=like&amp;app_id=625475154576703&amp;channel=https%3A%2F%2Fstaticxx.facebook.com%2Fx%2Fconnect%2Fxd_arbiter%2F%3Fversion%3D46%23cb%3Df2eda2a027ba0d8%26domain%3Dnguoimuanha.vn%26is_canvas%3Dfalse%26origin%3Dhttps%253A%252F%252Fnguoimuanha.vn%252Ff102ff38a076bb8%26relation%3Dparent.parent&amp;container_width=0&amp;href='.$url_link.'&amp;layout=button_count&amp;locale=vi_VN&amp;sdk=joey&amp;share=true&amp;size=small&amp;width=" style="border: none; visibility: visible; width: 150px; height: 28px;" class=""></iframe></span></div>
                    </div>
                    <a href="'.$url_link.'#detail__footer" title="Bình luận" class="story__react comment" data-article="'.$item->id.'"><i class="fal fa-comment"></i><span></span></a>
                    <a href="javascript:void(0)" title="Chia sẻ lên facebook" class="story__react love" data-article="'.$item->id.'"><i class="fal fa-share"></i></a>
                </footer>

                <div class="story__comment" data-count-comment="0" >
                    <div class="comment-listing" id="post'.$item->id.'" data-url="'.$url_link.'"></div>
                    <div class="input-wrap">
                        <div class="avatar avatarUser"></div>
                        <div class="content">
                            <div contenteditable="true" draggable="true" class="form-control bg-light editor inputComment auto-size" spellcheck="false" data-id="post'.$item->id.'"></div>
                            <span class="fal fa-image commentUploadImage">
                                <input type="file" accept="image/png, image/jpeg, img/gif" onchange="Images.UploadImage(this,$(this).parent().prev())">
                            </span>
                            <span class="btn-send pointer" title="Gửi bình luận"><i class="fas fa-paper-plane"></i></span>
                        </div>
                    </div>
                </div>

                <div class="story__extend">
                    <div class="dropdown">
                        <a class="" href="#" role="button" id="dropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="far fa-ellipsis-h"></i></a>
                        <div class="dropdown-menu dropdown-menu-right moreActionArticle" aria-labelledby="dropdownMenuLink1" data-user="377" data-article="'.$item->id.'">
                            <a class="dropdown-item aNotiArticle" href="javascript:void(0)" onclick="FollowingArticles.Follow('.$item->id.')"><i class="fal fa-bell mr-2"></i>Thông báo khi có bình luận</a>
                            <a class="dropdown-item aFollow" href="javascript:void(0)" onclick="Following.Follow(377)"> <i class="fal fa-user-plus mr-2"></i>Theo dõi tác giả</a>
                            
                            <a class="dropdown-item getLinkArticle" href="javascript:void(0)" data-toggle="modal" data-url="'.$url_link.'"><i class="fal fa-link mr-2"></i>Lấy link bài viết</a>

                            <a class="dropdown-item text-danger reportArticle" href="javascript:void(0)" data-toggle="modal" data-target="#modalReport" data-article="'.$item->id.'"><i class="fal fa-exclamation-square mr-2"></i>Báo cáo bài viết</a>
                        </div>
                    </div>
                </div>
            </article>';
        }
        session(['post_id'=>$article_id]);
        return $data_post;
        
    }

    public function postCategoryProfile($alias = null, Request $request)
    {
        
        if ($alias != "" ) {
            $params['url_part'] = str_replace('.html','',$alias);
            $params['status'] = Consts::TAXONOMY_STATUS['active'];
            $params['taxonomy'] = Consts::CATEGORY['profile'];
            $taxonomy = ContentService::getCmsTaxonomy($params)->first();

            $paramslist['status'] = Consts::TAXONOMY_STATUS['active'];
            $taxonomylist = ContentService::getCmsTaxonomy($paramslist)->get();
            $this->responseData['taxonomylist'] = $taxonomylist;

            if ($taxonomy) {
                $id=$taxonomy->id;
                $this->responseData['taxonomy'] = $taxonomy;
                if ($taxonomy->sub_taxonomy_id != null) {
                    $str_taxonomy_id = $id . ',' . $taxonomy->sub_taxonomy_id;
                    $paramPost['taxonomy'] = array_map('intval', explode(',', $str_taxonomy_id));
                } else {
                    $paramPost['taxonomy'] = $id;
                }
                $paramPost['status'] = Consts::POST_STATUS['active'];
                $this->responseData['posts'] =  ContentService::getCmsProfile($paramPost)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

                return $this->responseView('frontend.pages.post.category_profile');
            } else {
                return redirect()->back()->with('errorMessage', __('not_found'));
            }
        } else {
            $paramPost['status'] = Consts::POST_STATUS['active'];
            $paramPost['is_type'] = Consts::POST_TYPE['post'];
            $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
        }

        return $this->responseView('frontend.pages.post.default');
    }

    public function postCategoryTailieu($alias = null, Request $request)
    {
        //$id = $request->get('id')  ?? null;
        
        if ($alias != "" ) {
            $params['url_part'] = str_replace('.html','',$alias);
            $params['status'] = Consts::TAXONOMY_STATUS['active'];
            $params['taxonomy'] = Consts::CATEGORY['tai-lieu'];
            $taxonomy = ContentService::getCmsTaxonomy($params)->first();

            $paramslist['status'] = Consts::TAXONOMY_STATUS['active'];
            $taxonomylist = ContentService::getCmsTaxonomy($paramslist)->get();
            $this->responseData['taxonomylist'] = $taxonomylist;

            if ($taxonomy) {
                $id=$taxonomy->id;
                $this->responseData['taxonomy'] = $taxonomy;
                if ($taxonomy->sub_taxonomy_id != null) {
                    $str_taxonomy_id = $id . ',' . $taxonomy->sub_taxonomy_id;
                    $paramPost['taxonomy_id'] = array_map('intval', explode(',', $str_taxonomy_id));
                    $paramPostNoibat['taxonomy_id'] = array_map('intval', explode(',', $str_taxonomy_id));
                } else {
                    $paramPost['taxonomy_id'] = $id;
                    $paramPostNoibat['taxonomy_id'] = $id;
                }
                $paramPost['different_news_position'] = '1';
                $paramPost['status'] = Consts::POST_STATUS['active'];
                $paramPost['is_type'] = Consts::POST_TYPE['post'];
                $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);

                $paramPostNoibat['news_position'] = '1';
                $paramPostNoibat['status'] = Consts::POST_STATUS['active'];
                $paramPostNoibat['is_type'] = Consts::POST_TYPE['post'];
                $this->responseData['postsnoibat'] = ContentService::getCmsPost($paramPostNoibat)->get();
                return $this->responseView('frontend.pages.post.category_document');
            } else {
                return redirect()->back()->with('errorMessage', __('not_found'));
            }
        } else {
            $paramPost['status'] = Consts::POST_STATUS['active'];
            $paramPost['is_type'] = Consts::POST_TYPE['post'];
            $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
        }

        return $this->responseView('frontend.pages.post.default');
    }
    
    public function postCategoryMedia($alias = null, Request $request)
    {
        //$id = $request->get('id')  ?? null;
        
        if ($alias != "" ) {
            $params['url_part'] = str_replace('.html','',$alias);
            $params['status'] = Consts::TAXONOMY_STATUS['active'];
            $params['taxonomy'] = Consts::CATEGORY['media'];
            $taxonomy = ContentService::getCmsTaxonomy($params)->first();

            $paramslist['status'] = Consts::TAXONOMY_STATUS['active'];
            $taxonomylist = ContentService::getCmsTaxonomy($paramslist)->get();
            $this->responseData['taxonomylist'] = $taxonomylist;

            if ($taxonomy) {
                $id=$taxonomy->id;
                $this->responseData['taxonomy'] = $taxonomy;
                if ($taxonomy->sub_taxonomy_id != null) {
                    $str_taxonomy_id = $id . ',' . $taxonomy->sub_taxonomy_id;
                    $paramPost['taxonomy_id'] = array_map('intval', explode(',', $str_taxonomy_id));
                } else {
                    $paramPost['taxonomy_id'] = $id;
                }
                $paramPost['status'] = Consts::POST_STATUS['active'];
                $paramPost['is_type'] = Consts::POST_TYPE['post'];
                $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
                return $this->responseView('frontend.pages.post.category_media');
            } else {
                return redirect()->back()->with('errorMessage', __('not_found'));
            }
        } else {
            $paramPost['status'] = Consts::POST_STATUS['active'];
            $paramPost['is_type'] = Consts::POST_TYPE['post'];
            $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
        }

        return $this->responseView('frontend.pages.post.default');
    }
    public function postCategory($alias = null, Request $request)
    {
        //$id = $request->get('id')  ?? null;
        
        //dd('AAAAAAA');

        $keyword = $request->get('keyword')  ?? null;
        $paramPost['keyword'] = $keyword;
        $this->responseData['keyword'] = $keyword;
        
        if ($alias != "" ) {

            $params['url_part'] = str_replace('.html','',$alias);

            $this->responseData['alias'] = str_replace('.html','',$alias);

            //dd($params['url_part']);
            //echo 'AAAAAAAA'.$id;die;
            //$params['id'] = $id;
            $params['status'] = Consts::TAXONOMY_STATUS['active'];
            $params['taxonomy'] = Consts::CATEGORY['news'];
            $taxonomy = ContentService::getCmsTaxonomy($params)->first();

            $paramslist['status'] = Consts::TAXONOMY_STATUS['active'];
            $taxonomylist = ContentService::getCmsTaxonomy($paramslist)->get();
            $this->responseData['taxonomylist'] = $taxonomylist;

            if ($taxonomy) {
                $id=$taxonomy->id;

                $this->responseData['taxonomy'] = $taxonomy;
                if ($taxonomy->sub_taxonomy_id != null) {
                    $str_taxonomy_id = $id . ',' . $taxonomy->sub_taxonomy_id;
                    $paramPost['taxonomy_id'] = array_map('intval', explode(',', $str_taxonomy_id));
                } else {
                    $paramPost['taxonomy_id'] = $id;
                }
                $paramPost['status'] = Consts::POST_STATUS['active'];
                $paramPost['is_type'] = Consts::POST_TYPE['post'];
                $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
                return $this->responseView('frontend.pages.post.category');
            } else {
                return redirect()->back()->with('errorMessage', __('not_found'));
            }
        } else {
            $paramPost['status'] = Consts::POST_STATUS['active'];
            $paramPost['is_type'] = Consts::POST_TYPE['post'];
            $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);

        }

        return $this->responseView('frontend.pages.post.default');
    }

    public function dichvuCategory($alias = null, Request $request)
    {
        if ($alias == "" ) {

            $paramPost['status'] = Consts::POST_STATUS['active'];
            $paramPost['taxonomy'] = 'dich-vu';
            $this->responseData['posts'] = ContentService::getCmsDichvu($paramPost)->get();

            return $this->responseView('frontend.pages.post.category_dichvu');

        } else {

            $params['url_part'] = str_replace('.html','',$alias);
            $this->responseData['alias'] = str_replace('.html','',$alias);
            $params['status'] = Consts::TAXONOMY_STATUS['active'];
            $params['taxonomy'] = 'dich-vu';
            $taxonomy = ContentService::getCmsDichvu($params)->first();
            if ($taxonomy) {

                $id=$taxonomy->id;
                $this->responseData['taxonomy'] = $taxonomy;
                $paramPost['status'] = Consts::POST_STATUS['active'];
                $paramPost['taxonomy_id'] = $id;
                $this->responseData['posts'] = ContentService::getCmsPostDichvu($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);

                $paramslist['status'] = Consts::TAXONOMY_STATUS['active'];
                $paramslist['taxonomy'] = 'dich-vu';
                $this->responseData['dichvu'] = ContentService::getCmsDichvu($paramslist)->get();

                return $this->responseView('frontend.pages.post.default_dichvu');

            } else {

                return redirect()->back()->with('errorMessage', __('not_found'));

            }

        }

        return $this->responseView('frontend.pages.post.category_dichvu');
    }

    public function dichvuDetail($alias_detail = null, Request $request)
    {
        
        if ($alias_detail != '') {

            $params['url_part'] = str_replace('.html','',$alias_detail);
            $params['status'] = Consts::POST_STATUS['active'];
            $params['is_type'] = Consts::POST_TYPE['post'];
            $params['aproved_date'] = date('Y-m-d H:i:s');
            $detail = ContentService::getCmsPostDichvu($params)->first();

            if ($detail) {

                $detail->number_view = $detail->number_view + 1;
                
                $detail->save();

                $this->responseData['detail'] = $detail;

                $paramstaxo['id'] = $detail->taxonomy_id;
                $paramstaxo['status'] = Consts::TAXONOMY_STATUS['active'];
                $paramstaxo['taxonomy'] = 'dich-vu';
                $this->responseData['taxonomy'] = ContentService::getCmsDichvu($paramstaxo)->first();

                $id = $detail->id;
                $params_relative['different_id'] = $id;
                $params_relative['taxonomy_id'] = $detail->taxonomy_id;
                $params_relative['status'] = Consts::POST_STATUS['active'];
                $params_relative['is_type'] = Consts::POST_TYPE['post'];
                $this->responseData['posts'] = ContentService::getCmsPostDichvu($params_relative)->limit(Consts::DOCTOR_OTHER_LIMIT)->get();

                $paramslist['status'] = Consts::TAXONOMY_STATUS['active'];
                $paramslist['taxonomy'] = 'dich-vu';
                $this->responseData['dichvu'] = ContentService::getCmsDichvu($paramslist)->get();
                
                return $this->responseView('frontend.pages.post.detail_dichvu');
            }
        }

        return redirect()->back()->with('errorMessage', __('not_found'));
    }

    public function thuvienCategory($alias = null, Request $request)
    {
        //$id = $request->get('id')  ?? null;

        $keyword = $request->get('keyword')  ?? null;
        $paramPost['keyword'] = $keyword;
        
        if ($alias != "" ) {

            $params['url_part'] = str_replace('.html','',$alias);
            $this->responseData['alias'] = str_replace('.html','',$alias);

            $params['status'] = Consts::TAXONOMY_STATUS['active'];
            $params['taxonomy'] = 'thu-vien';
            $taxonomy = ContentService::getCmsTaxonomy($params)->first();

            if ($taxonomy) {
                $id=$taxonomy->id;
                $this->responseData['taxonomy'] = $taxonomy;
                if ($taxonomy->sub_taxonomy_id != null) {
                    $str_taxonomy_id = $id . ',' . $taxonomy->sub_taxonomy_id;
                    $paramPost['taxonomy_id'] = array_map('intval', explode(',', $str_taxonomy_id));
                } else {
                    $paramPost['taxonomy_id'] = $id;
                }
                $paramPost['status'] = Consts::POST_STATUS['active'];
                $this->responseData['posts'] = ContentService::getCmsMedia($paramPost)->paginate(Consts::POST_MEDIA_PAGINATE_LIMIT);
                return $this->responseView('frontend.pages.post.category_thuvien');
            } else {
                return redirect()->back()->with('errorMessage', __('not_found'));
            }
        } else {
            $paramPost['status'] = Consts::POST_STATUS['active'];
            $paramPost['is_type'] = Consts::POST_TYPE['post'];
            $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);

        }

        return $this->responseView('frontend.pages.post.default');
    }
    public function album(Request $request)
    {
        //$id = $request->get('id')  ?? null;
		/*
        $keyword = $request->get('keyword')  ?? null;
        $paramPost['keyword'] = $keyword;
       
            $paramPost['status'] = Consts::POST_STATUS['active'];
            $this->responseData['posts'] = ContentService::getAlbums($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);


        return $this->responseView('frontend.pages.post.album');
		*/
		$params['status'] = Consts::TAXONOMY_STATUS['active'];
		$params['taxonomy'] = Consts::CATEGORY['album'];
		$taxonomy = ContentService::getCmsTaxonomy($params)->first();
		
		$this->responseData['taxonomy'] = $taxonomy;
		$paramPost['status'] = Consts::POST_STATUS['active'];
		$this->responseData['posts'] = ContentService::getAlbums($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
		
        return $this->responseView('frontend.pages.post.gallery');
		
		
    }
    public function addnew(Request $request)
    {
        $params = $request->all();

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required|max:7000',
        ]);

        $targetDir = "member/hinhanh".Auth::guard('web')->user()->id."/";
        //$allowTypes = array('jpg','png','jpeg','gif');
        if(!file_exists($targetDir)){
            if(mkdir($targetDir)){
                //echo "Tạo thư mục thành công.";
            }
        }
        
        if($_FILES['image']['name']){
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
    
            $imageName = time().'.'.$request->image->extension();  
    
            $request->image->move(public_path($targetDir), $imageName);
            
            $path_image = $targetDir.$imageName;
        }else{
            $path_image = null;
        }

        $tb_cms_posts = new CmsPost();
        $tb_cms_posts->is_type = 'post';
        $tb_cms_posts->title = $params['title'];
        $tb_cms_posts->brief = $params['title'];
        $tb_cms_posts->content = $params['content'];
        $tb_cms_posts->image = $path_image;
        $tb_cms_posts->status = 'waiting';
        $tb_cms_posts->user_id = Auth::guard('web')->user()->id;
        $tb_cms_posts->save();
        
        //return redirect('/')->with('successMessage', 'Thêm mới tin thành công! Tin của bạn đang được chờ duyệt');

        $this->responseData['successMessage']  =  __('Thêm mới tin thành công! Tin của bạn đang được chờ duyệt.');
        return $this->responseView('frontend.pages.home.index');

    }

    public function search($alias = null, Request $request)
    {
        $keyword = $request->get('keyword')  ?? null;

        $paramPost['status'] = Consts::POST_STATUS['active'];
        $paramPost['is_type'] = Consts::POST_TYPE['post'];
        $paramPost['keyword'] = $keyword;
        $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);

        return $this->responseView('frontend.pages.post.category');
    }

    public function cmstag($alias = null, Request $request)
    {
        $id = $request->get('id')  ?? null;
        //echo 'AAAAAAAA'.$id;die;
        if ($id > 0) {
            //echo 'AAAAAAAA'.$id;die;
            $params['id'] = $id;
            $params['status'] = Consts::TAXONOMY_STATUS['active'];
            $params['taxonomy'] = Consts::CATEGORY['post_tag'];
            $taxonomy = ContentService::getCmsTaxonomy($params)->first();
            if ($taxonomy) {
                $this->responseData['taxonomy'] = $taxonomy;
                if ($taxonomy->sub_taxonomy_id != null) {
                    $str_taxonomy_id = $id . ',' . $taxonomy->sub_taxonomy_id;
                    $paramPost['taxonomy_id'] = array_map('intval', explode(',', $str_taxonomy_id));
                } else {
                    $paramPost['taxonomy_id'] = $id;
                }
                $paramPost['status'] = Consts::POST_STATUS['active'];
                $paramPost['is_type'] = Consts::POST_TYPE['post'];
                $this->responseData['posts'] = ContentService::getCmsPostTag($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
                return $this->responseView('frontend.pages.post.category');
            } else {
                return redirect()->back()->with('errorMessage', __('not_found'));
            }
        } else {
            $paramPost['status'] = Consts::POST_STATUS['active'];
            $paramPost['is_type'] = Consts::POST_TYPE['post'];
            $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
        }

        return $this->responseView('frontend.pages.post.default');
    }

    public function postMedia($alias_detail = null, Request $request)
    {
        if ($alias_detail != '') {

            $params['url_part'] = str_replace('.html','',$alias_detail);
            $params['status'] = Consts::POST_STATUS['active'];
            $params['is_type'] = Consts::POST_TYPE['post'];
            $params['aproved_date'] = date('Y-m-d H:i:s');
            $detail = ContentService::getCmsMedia($params)->first();

            if ($detail) {

                $id = $detail->id;
                
                $detail->number_view = $detail->number_view + 1;
                
                $detail->save();

                $this->responseData['detail'] = $detail;

                $id = $detail->id;
                $params_relative['id'] = $id;
                $params_relative['taxonomy_id'] = $detail->taxonomy_id;
                $params_relative['status'] = Consts::POST_STATUS['active'];
                $params_relative['is_type'] = Consts::POST_TYPE['post'];

                $this->responseData['posts'] = ContentService::getCmsMediaRelative($params_relative)->get();
                
                $paramstaxo['id'] = $detail->taxonomy_id;
                $paramstaxo['status'] = Consts::TAXONOMY_STATUS['active'];
                $this->responseData['taxonomy'] = ContentService::getCmsTaxonomy($paramstaxo)->first();

                //list ảnh
                $params_image['media_id'] = $id;
                $params_image['status'] = Consts::POST_STATUS['active'];
                $this->responseData['list_image'] = ContentService::getCmsMediaImage($params_image)->get();

                //list video
                $params_document['media_id'] = $id;
                $params_document['status'] = Consts::POST_STATUS['active'];
                $this->responseData['list_video'] = ContentService::getCmsMediaVideo($params_document)->get();
                
                return $this->responseView('frontend.pages.post.detail_media');
            }
        }

        return redirect()->back()->with('errorMessage', __('not_found'));
    }


    public function testimonial($alias = null,Request $request)
    {   
		$params['status'] = Consts::TAXONOMY_STATUS['active'];
		$params['taxonomy'] = Consts::CATEGORY['testimonial'];
		$taxonomy = ContentService::getCmsTaxonomy($params)->first();
		
		$this->responseData['taxonomy'] = $taxonomy;
		
        return $this->responseView('frontend.pages.home.testimonial');
    }

    public function faqs($alias = null,Request $request)
    {   

        return $this->responseView('frontend.pages.home.faqs');

    }

    public function aboutus($alias = null,Request $request)
    {   
        if ($alias !="") {
            $url_part = str_replace('.html','',$alias);
			
            //$params['id'] = $id;
            $params['status'] = Consts::TAXONOMY_STATUS['active'];
            $params['taxonomy'] = Consts::CATEGORY['about-us'];
            $taxonomy = ContentService::getCmsTaxonomy($params)->first();
			
			$this->responseData['taxonomy'] = $taxonomy;
			
            //if ($url_part == 'about-us') {
                return $this->responseView('frontend.pages.home.aboutus');
            //}
			/*
            $blockContents = BlockContent::where('block_code', $url_part)
                ->where('parent_id', '!=', null)
                ->orderBy('id', 'DESC')
                ->paginate(16);

            $this->responseData['blockContents'] = $blockContents;

            // dd($blockContents);

            return $this->responseView('frontend.pages.home.gallery4column');
			*/
        }
    }

    public function postIntroduction($alias = null, Request $request)
    {
		//dd($alias);
        
        if ($alias !="") {
            $params['url_part'] = str_replace('.html','',$alias);
            //$params['id'] = $id;
            $params['status'] = Consts::TAXONOMY_STATUS['active'];
            $params['taxonomy'] = Consts::CATEGORY['intro'];
            $taxonomy = ContentService::getCmsTaxonomy($params)->first();
			//dd($taxonomy);
			if ($taxonomy) {
				$id=$taxonomy->id;
				$paramPost['taxonomy_id'] = $id;
				$paramPost['status'] = Consts::POST_STATUS['active'];
                $paramPost['is_type'] = Consts::POST_TYPE['intro'];
				//dd($paramPost);
				$detail = ContentService::getCmsPost($paramPost)->first();
				//dd($detail);
                $this->responseData['detail'] = $detail;
                $this->responseData['taxonomy'] = $taxonomy;
                
                return $this->responseView('frontend.pages.post.intro');
				
			}
			
        }

        return redirect()->back()->with('errorMessage', __('not_found'));
    }



    public function serviceCategory($alias = null, Request $request)
    {
        if ($alias !="") {
            $params['url_part'] = str_replace('.html','',$alias);

            $params['status'] = Consts::POST_STATUS['active'];
            $params['taxonomy'] = Consts::CATEGORY['service'];

            $taxonomy = ContentService::getCmsTaxonomy($params)->first();
            if ($taxonomy) {
                $id=$taxonomy->id;
                $paramPost['taxonomy'] = $id;
                $paramPost['status'] = Consts::POST_STATUS['active'];
                $list_service = ContentService::getCmsDichvu($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);

                $this->responseData['taxonomy'] = $taxonomy;
                $this->responseData['posts'] = $list_service;
				//echo $id;
                //echo count($list_service);die;

                if(count($list_service) >= 1){
					//echo count($list_service);die;
                    return $this->responseView('frontend.pages.service.category');
                }
				/*else if(count($list_service) == 1){
					$this->responseData['detail'] = $list_service[0];
                    return $this->responseView('frontend.pages.service.detail');
                }*/
				else{
					// Lấy tất cả danh mục con 
					$paramTaxo['parent_id'] = $id;
					$paramTaxo['status'] = Consts::POST_STATUS['active'];
					$this->responseData['list_taxonomy'] = ContentService::getCmsTaxonomy($paramTaxo)->paginate(Consts::POST_PAGINATE_LIMIT);
					return $this->responseView('frontend.pages.service.default');
					
				}
                
            } else {
                return redirect()->back()->with('errorMessage', __('not_found'));
            }
        } else {
            $paramPost['status'] = Consts::POST_STATUS['active'];
            $paramPost['is_type'] = Consts::POST_TYPE['service'];
            $this->responseData['posts'] = ContentService::getCmsPostDichvu($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
        }

        return $this->responseView('frontend.pages.service.default');
    }

    public function serviceDetail($alias = null, Request $request)
    {
        if ($alias !="") {

            $paramPost['url_part'] = str_replace('.html','',$alias);
            $paramPost['status'] = Consts::POST_STATUS['active'];
            $list_service = ContentService::getCmsPostDichvu($paramPost)->first();

            $this->responseData['detail'] = $list_service;
			
			$paramstaxo['id'] = $list_service->taxonomy_id;
			$paramstaxo['status'] = Consts::TAXONOMY_STATUS['active'];
			$this->responseData['taxonomy'] = ContentService::getCmsTaxonomy($paramstaxo)->first();
			
            if($list_service){
                return $this->responseView('frontend.pages.service.detail');
            }else {
                return redirect()->back()->with('errorMessage', __('not_found'));
            }

        } else {
            $paramPost['status'] = Consts::POST_STATUS['active'];
            $paramPost['is_type'] = Consts::POST_TYPE['service'];
            $this->responseData['detail'] = ContentService::getCmsPostDichvu($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
        }

        return $this->responseView('frontend.pages.service.default');
    }

   
    public function productCategory($alias = null, Request $request)
    {
		//echo $alias;die;
		
		if ($alias != "" ) {
            $params['url_part'] = str_replace('.html','',$alias);
			
			$params['status'] = Consts::TAXONOMY_STATUS['active'];
            $params['taxonomy'] = Consts::CATEGORY['san-pham'];
            $taxonomy = ContentService::getCmsTaxonomy($params)->first();
            if ($taxonomy) {
                $id=$taxonomy->id;
                $this->responseData['taxonomy'] = $taxonomy;
				
				if($taxonomy->parent_id > 0){
					$paramPost['taxonomy_id'] = $id;
				}else{
					$paramPost['parent_id'] = $id;
				}
				
                $paramPost['status'] = 1;
				//dd($paramPost);
                $this->responseData['posts'] = ContentService::getProducts($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
				
				return $this->responseView('frontend.pages.product.category');
            } else {
                return redirect()->back()->with('errorMessage', __('not_found'));
            }
		}else {
            $paramPost['status'] = 1;
            $this->responseData['posts'] = ContentService::getProducts($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
        }
		
        return $this->responseView('frontend.pages.product.category');
    }
	
	public function productSearch(Request $request)
    {
		//echo $alias;die;
		$params = $request->all();
		$keyword = $params['search'];
		$this->responseData['posts'] = array();
		
		if($keyword!=""){
			$paramPost['status'] = 1;
			$paramPost['keyword'] = $keyword;
			$this->responseData['posts'] = ContentService::getProducts($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
			
			return $this->responseView('frontend.pages.product.search');
			
		}
		
        return $this->responseView('frontend.pages.product.search');
    }
	
	public function newsSearch(Request $request)
    {
		//echo $alias;die;
		$params = $request->all();
		$keyword = $params['keyword'];
		$this->responseData['posts'] = array();
		
		if($keyword!=""){
			$paramPost['status'] = Consts::POST_STATUS['active'];
			$paramPost['keyword'] = $keyword;
			$this->responseData['keyword'] = $keyword;
			$this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
			
			return $this->responseView('frontend.pages.post.search');
			
		}
		
        return $this->responseView('frontend.pages.post.search');
    }
	

    public function product($alias_detail = null, Request $request)
    {
		//dd($alias_detail);
		if($alias_detail != ""){
			
			$params['alias'] = str_replace('.html','',$alias_detail);
			$params['status'] = 1;
			
			$detail = ContentService::getProducts($params)->first();
			
			if ($detail) {
				
				$this->responseData['detail'] = $detail;
				
				$params_relative['different_id'] = $detail->id;
				$params_relative['taxonomy_id'] = $detail->taxonomy_id;
				
                $this->responseData['posts'] = ContentService::getProducts($params_relative)->limit(Consts::DEFAULT_OTHER_LIMIT)->get();
				//dd($alias_detail);
				return $this->responseView('frontend.pages.product.detail');
			}
			
		}
		
		return redirect()->back()->with('errorMessage', __('not_found'));
		
    }

    public function doctorList(Request $request)
    {
        $paramPost['status'] = Consts::POST_STATUS['active'];
        $paramPost['is_type'] = Consts::POST_TYPE['doctor'];
        $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);

        return $this->responseView('frontend.pages.doctor.default');
    }

    public function doctor($alias = null, $id = null, Request $request)
    {
        $id = $request->get('id')  ?? $id;
        if ($id > 0) {
            $params['id'] = $id;
            $params['status'] = Consts::POST_STATUS['active'];
            $params['is_type'] = Consts::POST_TYPE['doctor'];
            $detail = ContentService::getCmsPost($params)->first();
            if ($detail) {
                $detail->count_visited = $detail->count_visited + 1;
                $detail->save();
                $this->responseData['detail'] = $detail;
                $params['id'] = null;
                $params['different_id'] = $detail->id;
                $this->responseData['posts'] = ContentService::getCmsPost($params)->limit(Consts::DOCTOR_OTHER_LIMIT)->get();

                return $this->responseView('frontend.pages.doctor.detail');
            }
        }

        return redirect()->back()->with('errorMessage', __('not_found'));
    }

    public function galleryCategory($alias = null, $id = null, Request $request)
    {

        $paramPost['status'] = Consts::POST_STATUS['active'];
        $paramPost['is_type'] = Consts::POST_TYPE['gallery'];
        $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);

        return $this->responseView('pages.gallery.default');
    }

    public function gallery($alias = null,Request $request)
    {	
		if ($alias !="") {
            $url_part = str_replace('.html','',$alias);
			
            //$params['id'] = $id;
            $params['status'] = Consts::TAXONOMY_STATUS['active'];
            $params['taxonomy'] = Consts::CATEGORY['gallery'];
            $taxonomy = ContentService::getCmsTaxonomy($params)->first();
			
			$this->responseData['taxonomy'] = $taxonomy;
			
		}
		
		return $this->responseView('frontend.pages.home.gallery');
        
    }


    public function department($alias = null, Request $request)
    {
        $id = $request->get('id')  ?? null;
        if ($id > 0) {
            $params['id'] = $id;
            $params['status'] = Consts::TAXONOMY_STATUS['active'];
            $params['taxonomy'] = Consts::TAXONOMY['department'];
            $taxonomy = ContentService::getCmsTaxonomy($params)->first();
            if ($taxonomy) {
                $this->responseData['detail'] = $taxonomy;

                $params['id'] = null;
                $params['different_id'] = $taxonomy->id;
                $this->responseData['posts'] = ContentService::getCmsTaxonomy($params)->limit(Consts::DEPARTMENT_OTHER_LIMIT)->get();


                return $this->responseView('frontend.pages.department.detail');
            } else {
                return redirect()->back()->with('errorMessage', __('not_found'));
            }
        } else {
            $paramPost['status'] = Consts::TAXONOMY_STATUS['active'];
            $paramPost['taxonomy'] = Consts::TAXONOMY['department'];
            $this->responseData['posts'] = ContentService::getCmsTaxonomy($paramPost)->paginate(Consts::POST_PAGINATE_LIMIT);
        }

        return $this->responseView('frontend.pages.department.default');
    }

    public function resourceCategory($alias = null, Request $request)
    {
        $id = $request->get('id')  ?? null;
        if ($id > 0) {
            $params['id'] = $id;
            $params['status'] = Consts::TAXONOMY_STATUS['active'];
            $params['taxonomy'] = Consts::TAXONOMY['resource_category'];
            $taxonomy = ContentService::getCmsTaxonomy($params)->first();
            if ($taxonomy) {
                $this->responseData['taxonomy'] = $taxonomy;
                $paramPost['taxonomy_id'] = $id;
                $paramPost['status'] = Consts::POST_STATUS['active'];
                $paramPost['is_type'] = Consts::POST_TYPE['resource'];
                $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
                return $this->responseView('frontend.pages.resource.category');
            } else {
                return redirect()->back()->with('errorMessage', __('not_found'));
            }
        } else {
            $paramPost['status'] = Consts::POST_STATUS['active'];
            $paramPost['is_type'] = Consts::POST_TYPE['resource'];
            $this->responseData['posts'] = ContentService::getCmsPost($paramPost)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        }

        return $this->responseView('frontend.pages.resource.default');
    }

    public function resource($alias_category = null, $alias_detail = null, Request $request)
    {
        $id = $request->get('id')  ?? null;
        if ($id > 0) {
            $params['id'] = $id;
            $params['status'] = Consts::POST_STATUS['active'];
            $params['is_type'] = Consts::POST_TYPE['resource'];
            $detail = ContentService::getCmsPost($params)->first();
            if ($detail) {
                $detail->count_visited = $detail->count_visited + 1;
                $detail->save();

                $this->responseData['detail'] = $detail;

                $params['id'] = null;
                $params['different_id'] = $detail->id;
                $params['order_by'] = 'id';
                $params['taxonomy_id'] = $detail->taxonomy_id;
                $this->responseData['posts'] = ContentService::getCmsPost($params)->limit(Consts::DEFAULT_OTHER_LIMIT)->get();

                return $this->responseView('frontend.pages.resource.detail');
            }
        }

        return redirect()->back()->with('errorMessage', __('not_found'));
    }
}
