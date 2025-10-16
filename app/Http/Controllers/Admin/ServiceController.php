<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

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
        $services = Service::orderBy('id', 'desc')->paginate(30);
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
}
