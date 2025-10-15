<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\ContentService;
use App\Models\CmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CmsServiceController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'cms_services';
        $this->viewPart = 'admin.pages.cms_services';
        $this->responseData['module_name'] = __('Services Management');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $params['is_type'] = Consts::POST_TYPE['service'];
        // Get list post with filter params
        $rows = ContentService::getCmsPost($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
        $paramTaxonomys['taxonomy'] = Consts::TAXONOMY['service_category'];
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
        $paramTaxonomys['taxonomy'] = Consts::TAXONOMY['service_category'];
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

        $params['is_type'] = Consts::POST_TYPE['service'];
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        CmsService::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CmsService  $cmsService
     * @return \Illuminate\Http\Response
     */
    public function show(CmsService $cmsService)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CmsService  $cmsService
     * @return \Illuminate\Http\Response
     */
    public function edit(CmsService $cmsService)
    {
        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
        $paramTaxonomys['taxonomy'] = Consts::TAXONOMY['service_category'];
        $this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
        $this->responseData['detail'] = $cmsService;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CmsService  $cmsService
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CmsService $cmsService)
    {
        $request->validate([
            'title' => 'required|max:255',
            'taxonomy_id' => 'required|max:255',
        ]);

        $params = $request->all();

        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $cmsService->fill($params);
        $cmsService->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CmsService  $cmsService
     * @return \Illuminate\Http\Response
     */
    public function destroy(CmsService $cmsService)
    {
        // check is type service
        if ($cmsService->is_type != Consts::POST_TYPE['service']) {
            return redirect()->back()->with('errorMessage', __('Permission denied!'));
        }

        $cmsService->status = Consts::STATUS_DELETE;
        $cmsService->save();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
}
