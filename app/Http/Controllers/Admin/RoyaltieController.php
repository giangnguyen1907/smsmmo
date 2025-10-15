<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
//use App\Http\Controllers\Controller;
use App\Models\Royaltie;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class RoyaltieController extends Controller
{
    public function __construct()
    {

        $this->routeDefault  = 'royaltie';
        $this->viewPart = 'admin.pages.royalties';
        $this->responseData['module_name'] = __('Quản lý nhuận bút');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();

        $params['from_date'] = isset($params['from_date']) ? $params['from_date'] : date('Y-m').'-01';
        $params['to_date'] = isset($params['to_date']) ? $params['to_date'] : date('Y-m-d');

        $rows = Royaltie:: getRoyaltieByParams($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
        $this->responseData['params'] = $params;
        return $this->responseView($this->viewPart . '.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect()->back();
        //return $this->responseView($this->viewPart . '.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $params = $request->all();
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $royaltie = Royaltie::create($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Royaltie  $royaltie
     * @return \Illuminate\Http\Response
     */
    public function show(Royaltie $royaltie)
    {
        //
        //dd($royaltie);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Royaltie  $royaltie
     * @return \Illuminate\Http\Response
     */
    public function edit(Royaltie $royaltie)
    {
        //dd($royaltie);
        $params['id'] = $royaltie->id;
        $this->responseData['detail'] = Royaltie::getRoyaltieByParams($params)->first();
        //$this->responseData['detail'] = $royaltie;
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Royaltie  $royaltie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Royaltie $royaltie)
    {
        
        $params = $request->all();

        //dd($params);
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $royaltie->fill($params);
        $royaltie->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));

    }

    public function updateStatus(Request $request)
    {   
        $id = $request->id;
        $status = $request->status;
        
		Royaltie::where('id',$id)->update(['status' => $status]);
        
        return $id;
		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Royaltie  $royaltie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Royaltie $royaltie)
    {
        //dd($royaltie);
        //echo "die".$royaltie->id;die;
        $royaltie->delete();
        return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }
}
