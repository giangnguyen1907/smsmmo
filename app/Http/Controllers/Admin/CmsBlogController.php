<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Controllers\Admin\Controller;
use App\Http\Services\ContentService;
use App\Http\Services\PageBuilderService;
use App\Models\Block;
use App\Models\BlockContent;
use App\Models\CmsBlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CmsBlogController extends Controller
{

    public function __construct()
    {
        $this->routeDefault  = 'cms_blog';
        $this->viewPart = 'admin.pages.cms_blog';
        $this->responseData['module_name'] = __('Quản lý Blogs');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $this->responseData['params'] = $params;

        $params['order_by'] = [
            'status' => 'ASC',
            'id' => 'DESC'
        ];

        $rows = PageBuilderService::getBlog($params)->get();
        $this->responseData['rows'] =  $rows;

        // Get all blocks which have status is active
        $blogs = CmsBlog::where('status', 'active')->orderByRaw('id DESC')->get();

        $this->responseData['blogs'] = $blogs;

        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $params_lang['status'] = '1';
        $languages = ContentService::getCmsLanguage($params_lang)->get();

        // Get all parents which have status is active
        $parents = BlockContent::where('status', 'active')->orderByRaw('iorder ASC, id DESC')->get();

        // Get all blocks which have status is active
        $blocks = Block::where('status', 'active')->orderByRaw('iorder ASC, id DESC')->get();

        $blogs = CmsBlog::where('status', 'active')->orderByRaw('id DESC')->get();
        $this->responseData['blogs'] = $blogs;
		
		$paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
		$this->responseData['taxonomy'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
		
        $this->responseData['parents'] = $parents;
        $this->responseData['blocks'] = $blocks;
        $this->responseData['languages'] =  $languages;

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
        $request->validate([
            'title' => 'required|max:255',
        ]);

        $params = $request->all();
		
		// $taxonomy = implode(',',$params['taxonomy']);
		// $params['taxonomy'] = $taxonomy;
		
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        CmsBlog::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $blog = CmsBlog::find($id);

        $blog->view += 1;
        $blog->save();

        dd($blog);

        $this->responseData['blog'] = $blog;

        // product relate
        $relatedBlogs = CmsBlog::where('blog_id', $blog->blog_id)
            ->where('id', '!=', $blog->id)
            ->where('status', 'active')
            ->paginate(2);

        $this->responseData['relatedBlogs'] = $relatedBlogs;

        return $this->responseView('frontend.pages.home.blog_detail');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Get all parents which have status is active
        $parents = CmsBlog::where('status', 'active')->where('id', '!=', $id)->orderByRaw('id DESC')->get();

        $blog = CmsBlog::where('status', 'active')->where('id', $id)->first();
        // Get all blocks which have status is active
        $blocks = Block::where('status', 'active')->orderByRaw(' id DESC')->get();
        
        $params_lang['status'] = '1';
        $languages = ContentService::getCmsLanguage($params_lang)->get();
		
		$paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
		$this->responseData['taxonomy'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
		
        $this->responseData['parents'] = $parents;
        $this->responseData['blocks'] = $blocks;
        $this->responseData['detail'] = $blog;
        $this->responseData['languages'] =  $languages;

        // dd($blog);

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
