<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\ContentService;
use App\Models\CmsResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CmsResourceController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'cms_resources';
        $this->viewPart = 'admin.pages.cms_resources';
        $this->responseData['module_name'] = __('Resources Management');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $params['is_type'] = Consts::POST_TYPE['resource'];
        // Get list post with filter params
        $rows = ContentService::getCmsPost($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
        $paramTaxonomys['taxonomy'] = Consts::TAXONOMY['resource_category'];
        $this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();

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
        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
        $paramTaxonomys['taxonomy'] = Consts::TAXONOMY['resource_category'];
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
        $request->validate([
            'title' => 'required|max:255',
            'taxonomy_id' => 'required|max:255',
        ]);

        $params = $request->all();
        $params['is_type'] = Consts::POST_TYPE['resource'];
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        CmsResource::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CmsResource  $cmsResource
     * @return \Illuminate\Http\Response
     */
    public function show(CmsResource $cmsResource)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CmsResource  $cmsResource
     * @return \Illuminate\Http\Response
     */
    public function edit(CmsResource $cmsResource)
    {
        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
        $paramTaxonomys['taxonomy'] = Consts::TAXONOMY['resource_category'];
        $this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
        $this->responseData['detail'] = $cmsResource;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CmsResource  $cmsResource
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CmsResource $cmsResource)
    {
        $request->validate([
            'title' => 'required|max:255',
            'taxonomy_id' => 'required|max:255',
        ]);

        $params = $request->all();

        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $cmsResource->fill($params);
        $cmsResource->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CmsResource  $cmsResource
     * @return \Illuminate\Http\Response
     */
    public function destroy(CmsResource $cmsResource)
    {
        // check is type post
        if ($cmsResource->is_type != Consts::POST_TYPE['resource']) {
            return redirect()->back()->with('errorMessage', __('Permission denied!'));
        }

        $cmsResource->status = Consts::STATUS_DELETE;
        $cmsResource->save();

        return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }
}
