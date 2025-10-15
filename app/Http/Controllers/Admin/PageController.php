<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\PageBuilderService;
use App\Models\BlockContent;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PageController extends Controller
{

    public function __construct()
    {
        $this->routeDefault  = 'pages';
        $this->viewPart = 'admin.pages.pages';
        $this->responseData['module_name'] = __('Page Management');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows = Page::where('status', "!=", Consts::STATUS_DELETE)
            ->orderByRaw('status ASC, iorder ASC, id DESC')
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
            'name' => 'required|max:255',
            'route_name' => 'required|max:255',
            'alias' => 'max:100'
        ]);

        $params = $request->all();
        $params['alias'] = Str::slug($params['alias'] ?? $params['name']);

        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        Page::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        $params['template'] = $page->json_params->template;
        $params['status'] = Consts::STATUS['active'];
        $params['order_by'] = [
            'status' => 'ASC',
            'iorder' => 'ASC',
            'id' => 'DESC'
        ];
        $blockContents = PageBuilderService::getBlockContent($params)->where('tb_block_contents.parent_id', NULL)->get();
        // Reorder blockContents setting of this widget
        $block_setting = $page->json_params->block_content ?? [];
        // Filter selected blockContents
        $block_selected = $blockContents->filter(function ($item) use ($block_setting) {
            return in_array($item->id, $block_setting);
        });
        // Reorder selected blockContents
        $block_selected = $block_selected->sortBy(function ($item) use ($block_setting) {
            return array_search($item->id, $block_setting);
        });

        // Config widgets for this page
        $params_widget['status'] = Consts::STATUS['active'];
        $params_widget['order_by'] = [
            'widget_code' => 'ASC',
            'status' => 'ASC',
            'iorder' => 'ASC',
            'id' => 'DESC'
        ];
        $widgets = PageBuilderService::getWidget($params_widget)->get();

        $this->responseData['block_selected'] = $block_selected;
        $this->responseData['blockContents'] = $blockContents;
        $this->responseData['widgets'] = $widgets;
        $this->responseData['detail'] = $page;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {

        $request->validate([
            'name' => 'required|max:255',
            'route_name' => 'required|max:255',
            'alias' => 'max:100'
        ]);

        $params = $request->all();
        $params['alias'] = Str::slug($params['alias'] ?? $params['name']);

        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $page->fill($params);
        $page->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        // Update status to delete
        $page->status = Consts::STATUS_DELETE;
        $page->save();

        return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }
}
