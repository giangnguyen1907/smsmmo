<?php

namespace App\Http\Controllers\Admin;

use App\Models\CmsTranslate;
use Illuminate\Http\Request;
use App\Http\Services\ContentService;
use App\Models\Role;
use App\Consts;
use Illuminate\Support\Facades\Auth;
class CmsTranslateController extends Controller
{
    // $allTranslations = CmsTranslate::get();

    // $translationsByLocal = [];
    
    // foreach ($allTranslations as $translation) {
    //     $translationsByLocal[$translation->local] = $translation->json_param;
    // }
    
    // $this->responseData['translationsByLocal'] =  $translationsByLocal;

    // //Ngoài view
    // $translationsByLocal['View']->ko;

    public function __construct()
    {
        $this->routeDefault  = 'cms_translate';
        $this->viewPart = 'admin.pages.cms_translate';
        $this->responseData['module_name'] = __('Quản lý bản dịch');
		$params['status'] = '1';
		$this->languages = ContentService::getCmsLanguage($params)->get();
        $this->responseData['languages'] =  $this->languages;
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
        $rows = ContentService::getCmsTranslate($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;

        $ngongu = [];
        foreach ($this->languages as $translation) {
            $ngongu[$translation->lang_code] = $translation->title;
        }
        $this->responseData['ngongu'] =  $ngongu;

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
        $params['status'] = '1';
        
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
            'local' => 'required|unique:tb_cms_translate',
            'translations' => "required",
        ]);

        $translations = [];
        foreach ($request->input('translations') as $language => $translation) {
            $translations[$language] = $translation;
        }

        CmsTranslate::create([
            'local' => $request->input('local'),
            'json_param' => $translations,
            'admin_created_id' => Auth::guard('admin')->user()->id,
            'admin_updated_id' => Auth::guard('admin')->user()->id
        ]);

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
        $rows = CmsTranslate::find($id);

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
    public function update(Request $request, CmsTranslate $cmsTranslate)
    {
        $request->validate([
            'local' => 'required',
            'translations' => "required",
        ]);

        $translations = [];
        foreach ($request->input('translations') as $language => $translation) {
            $translations[$language] = $translation;
        }

        $cmsTranslate->update([
            'local' => $request->input('local'),
            'json_param' => $translations,
            'admin_updated_id' => Auth::guard('admin')->user()->id
        ]);

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(CmsTranslate $cmsTranslate)
    {
        $cmsTranslate->delete();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage',  __('Delete record successfully!'));
    }
}
