<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\ContentService;
use App\Models\CmsDoctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CmsDoctorController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'cms_doctors';
        $this->viewPart = 'admin.pages.cms_doctors';
        $this->responseData['module_name'] = __('Doctors Management');
    }

    /**
     * Display a listing of the doctor.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $params['is_type'] = Consts::POST_TYPE['doctor'];
        // Get list post with filter params
        $rows = ContentService::getCmsPost($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
        $paramTaxonomys['taxonomy'] = Consts::TAXONOMY['department'];
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
        $paramTaxonomys['taxonomy'] = Consts::TAXONOMY['department'];
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
        $params['is_type'] = Consts::POST_TYPE['doctor'];
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        CmsDoctor::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified doctor.
     *
     * @param  \App\Models\CmsDoctor  $cmsDoctor
     * @return \Illuminate\Http\Response
     */
    public function show(CmsDoctor $cmsDoctor)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CmsDoctor  $cmsDoctor
     * @return \Illuminate\Http\Response
     */
    public function edit(CmsDoctor $cmsDoctor)
    {
        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
        $paramTaxonomys['taxonomy'] = Consts::TAXONOMY['department'];
        $this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
        $this->responseData['detail'] = $cmsDoctor;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CmsDoctor  $cmsDoctor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CmsDoctor $cmsDoctor)
    {
        $request->validate([
            'title' => 'required|max:255',
            'taxonomy_id' => 'required|max:255',
        ]);

        $params = $request->all();

        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $cmsDoctor->fill($params);
        $cmsDoctor->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CmsDoctor  $cmsDoctor
     * @return \Illuminate\Http\Response
     */
    public function destroy(CmsDoctor $cmsDoctor)
    {
        // check is type post
        if ($cmsDoctor->is_type != Consts::POST_TYPE['doctor']) {
            return redirect()->back()->with('errorMessage', __('Permission denied!'));
        }

        $cmsDoctor->status = Consts::STATUS_DELETE;
        $cmsDoctor->save();

        return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }
}
