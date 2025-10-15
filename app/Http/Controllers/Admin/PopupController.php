<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\ContentService;
use App\Models\Page;
use App\Models\Popup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PopupController extends Controller
{

    public function __construct()
    {
        $this->routeDefault  = 'popups';
        $this->viewPart = 'admin.pages.popups';
        $this->responseData['module_name'] = __('Popups Management');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rows = Popup::where('status', "!=", Consts::STATUS_DELETE)
            ->orderByRaw('status ASC, id DESC')
            ->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

        $this->responseData['rows'] =  $rows;

        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pages = Page::where('status', 'active')->orderByRaw('iorder ASC, id DESC')->get();
        $this->responseData['pages'] =  $pages;

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
            'status' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $params = $request->all();
        $params['start_time'] = ($request->get('start_time')) ? Carbon::createFromFormat('d/m/Y', $request->get('start_time')) : null;
        $params['end_time'] = ($request->get('end_time')) ? Carbon::createFromFormat('d/m/Y', $request->get('end_time')) : null;

        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        Popup::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Popup  $popup
     * @return \Illuminate\Http\Response
     */
    public function show(Popup $popup)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Popup  $popup
     * @return \Illuminate\Http\Response
     */
    public function edit(Popup $popup)
    {
        $pages = Page::where('status', 'active')->orderByRaw('iorder ASC, id DESC')->get();
        // Reorder pages setting of this widget
        $page_setting = $popup->json_params->page ?? [];
        // Filter selected blockContents
        $page_selected = $pages->filter(function ($item) use ($page_setting) {
            return in_array($item->id, $page_setting);
        });
        // Reorder selected pages
        $page_selected = $page_selected->sortBy(function ($item) use ($page_setting) {
            return array_search($item->id, $page_setting);
        });

        $this->responseData['page_selected'] = $page_selected;
        $this->responseData['pages'] = $pages;
        $this->responseData['detail'] = $popup;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Popup  $popup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Popup $popup)
    {
        $request->validate([
            'title' => 'required|max:255',
            'status' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $params = $request->all();

        $params['start_time'] = ($request->get('start_time')) ? Carbon::createFromFormat('d/m/Y H:i:s', $request->get('start_time') . ' 00:00:00') : null;
        $params['end_time'] = ($request->get('end_time')) ? Carbon::createFromFormat('d/m/Y H:i:s', $request->get('end_time') . ' 00:00:00') : null;

        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $popup->fill($params);
        $popup->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Popup  $popup
     * @return \Illuminate\Http\Response
     */
    public function destroy(Popup $popup)
    {
        $popup->delete();

        return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }
}
