<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\ContentService;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CmsProfileController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'profile';
        $this->viewPart = 'admin.pages.cms_profile';
        $this->responseData['module_name'] = __('Quản lý thông tin đội ngũ');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $this->responseData['rows'] =  ContentService::getCmsProfile($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

        $params['status'] = Consts::TAXONOMY_STATUS['active'];
        $params['taxonomy'] = 'chuyen-khoa';
        $this->responseData['taxonomys'] = ContentService::getCmsTaxonomy($params)->get();

        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         // Get all taxonomy is active
        $params['status'] = Consts::TAXONOMY_STATUS['active'];
        $params['taxonomy'] = 'chuyen-khoa';
        $this->responseData['taxonomys'] = ContentService::getCmsTaxonomy($params)->get();

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
            'taxonomy' => 'required|max:255',
        ]);

        $params = $request->all();
        
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        Profile::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {
        // Get all parents which have status is active
        $params['status'] = Consts::TAXONOMY_STATUS['active'];
        $params['different_id'] = $profile->id;
        $params['taxonomy'] = 'chuyen-khoa';
        $this->responseData['taxonomys'] = ContentService::getCmsTaxonomy($params)->get();
        $this->responseData['detail'] = $profile;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile)
    {
        $request->validate([
            'title' => 'required|max:255',
            'taxonomy' => 'required|max:255',
        ]);

        $params = $request->all();
        
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $profile->fill($params);
        $profile->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        $profile->status = Consts::STATUS_DELETE;
        $profile->save();

        return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }
}
