<?php

namespace App\Http\Controllers\Admin;

use App\Models\CmsLanguage;
use Illuminate\Http\Request;
use App\Http\Services\ContentService;
use App\Models\Role;
use App\Consts;
use Illuminate\Support\Facades\Auth;

class CmsLanguageController extends Controller
{

    public function __construct()
    {
        $this->routeDefault  = 'cms_language';
        $this->viewPart = 'admin.pages.cms_language';
        $this->responseData['module_name'] = __('Quản lý ngôn ngữ');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $params['keyword'] = $request->get('keyword');
        $rows = ContentService::getCmsLanguage($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

        $this->responseData['rows'] =  $rows;
        $this->responseData['keyword'] = $params['keyword'];

        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
            'title' => 'required',
            'lang_code' => "required|unique:tb_cms_language",
        ]);

        $params = $request->all();

        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        CmsLanguage::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rows = CmsLanguage::find($id);

        if (!$rows) {
            return redirect()->route($this->routeDefault . '.index')->with('errorMessage', __('Record not found!'));
        }
        $this->responseData['rows'] = $rows;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CmsLanguage $cmsLanguage)
    {
        $request->validate([
            'title' => 'required',
            'lang_code' => "required",
        ]);

        $params = $request->all();
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $cmsLanguage->fill($params);
        $cmsLanguage->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(CmsLanguage $cmsLanguage)
    {
        $cmsLanguage->delete();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage',  __('Delete record successfully!'));
    }
}
