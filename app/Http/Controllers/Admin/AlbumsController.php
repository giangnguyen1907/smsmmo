<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\ContentService;
use App\Models\Albums;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlbumsController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'albums';
        $this->viewPart = 'admin.pages.albums';
        $this->responseData['module_name'] = __('Albums Management');
    }

    /**
     * Display a listing of the doctor.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        // Get list post with filter params
        $rows = ContentService::getAlbums($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];

        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] = $params;
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
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        Albums::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified doctor.
     *
     * @param  \App\Models\CmsDoctor  $cmsDoctor
     * @return \Illuminate\Http\Response
     */
    public function show(Albums $album)
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
    public function edit(Albums $album)
    {
        
        $this->responseData['detail'] = $album;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CmsDoctor  $cmsDoctor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Albums $album)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        $params = $request->all();

        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $album->fill($params);
        $album->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CmsDoctor  $cmsDoctor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Albums $album)
    {
      

        $album->status = Consts::STATUS_DELETE;
        $album->save();

        return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }
}
