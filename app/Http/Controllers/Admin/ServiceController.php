<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use League\OAuth1\Client\Server\Server;
use Illuminate\Support\Facades\Http;

class ServiceController extends Controller
{   
   public function __construct()
{
    $this->routeDefault  = 'services';
    $this->viewPart = 'admin.pages.services';
    $this->responseData['module_name'] = __('Quản lý dịch vụ');

    // Giả sử lấy danh sách menu theo user
    $this->responseData['accessMenus'] = auth()->user()->menus ?? [];
}
    // Hiển thị danh sách dịch vụ
    public function index()
    {
        $allServiceRoot = [];
        // dd($this->apitoken);
        $url = 'https://bossotp.net/api/v4/service-manager/services';
        
        $response = Http::withHeaders([
            'accept' => '*/*',
        ])->get($url, []);
        // dd($response->json());
        if ($response->successful()) {
            // Trả về dữ liệu JSON
            $allServiceRoot = $response->json();
        }

        $services = Service::orderBy('id', 'desc')->paginate(30);

        
        $this->responseData['allServiceRoot'] = $allServiceRoot;
        $this->responseData['services'] = $services;
        return $this->responseView($this->viewPart . '.index');
    }

    // Form thêm dịch vụ
    public function create()
    {
        return $this->responseView($this->viewPart . '.create');
    }

    // Lưu dịch vụ mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price_per_unit' => 'required|numeric|min:0',
        ]);
        Service::create([
            'name' => $request->name,
            'price_per_unit' => $request->price_per_unit,
            'description' => $request->description,
            'status' => $request->status === 'active' ? 1 : 0,
            'duration_minutes' => $request->duration_minutes
        ]);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    // Form chỉnh sửa dịch vụ
        public function edit(Service $service)
        {
            $this->responseData['service'] = $service;
            return $this->responseView($this->viewPart . '.edit');
        }



    // Cập nhật dịch vụ
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price_per_unit' => 'required|numeric|min:0',
        ]);


        $service->update([
            'name' => $request->name,
            'price_per_unit' => $request->price_per_unit,
            'description' => $request->description,
            'status' => $request->status === 'active' ? 1 : 0,
            'duration_minutes' => $request->duration_minutes
        ]);

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    // Xóa dịch vụ
    public function destroy(Service $service)
    {
        $service->delete();
         return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }

    public function saveAjax(Request $request)
    {
        
        $id = $request->id;
        $title = $request->title;
        $service_id = $request->service_id;
        $price = $request->price;
        $status = $request->status;
        
		$taxonomy = Service::where('id',$id)->first();

        if($taxonomy){
            
            $taxonomy -> name = $title;
            $taxonomy -> service_id = $service_id;
            $taxonomy -> price_per_unit = $price;
            $taxonomy -> status = $status;
            $taxonomy -> save();

        }
		
		return $id;
		/*
		$hienthi = isset($params['hienthi']) ? implode(';',$params['hienthi']) : '';
		$params['hienthi'] = ';'.$hienthi.';';
		
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        CmsTaxonomy::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
		*/
		
    }

}
