<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Services\AdminService;
use App\Models\Role;
use App\Models\CmsDepartment;
use App\Consts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AdminController extends Controller
{

    private $adminService;

    public function __construct()
    {
        $this->adminService = new AdminService();
        $this->routeDefault  = 'admins';
        $this->viewPart = 'admin.pages.admins';
        $this->responseData['module_name'] = __('Admin user management');
    }


    
    // Xác nhận chữ ký số
    public function verifyDigitalSignature($data, $signature, $publicKey)
    {
        // Chuyển đổi dữ liệu thành dạng băm
        $hash = hash('sha256', $data, true);

        // Xác nhận chữ ký
        $result = openssl_verify($hash, base64_decode($signature), $publicKey, OPENSSL_ALGO_SHA256);

        return $result === 1;
    }
    
    // Tạo chữ ký số
    public function createDigitalSignature($data)
    {
        $privateKey = openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        // Chuyển đổi dữ liệu thành dạng băm
        $hash = hash('sha256', $data, true);

        // Ký băm dữ liệu
        openssl_sign($hash, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        // Lấy chữ ký dưới dạng base64
        $signature = base64_encode($signature);

        // Lấy public key
        $publicKey = openssl_pkey_get_details($privateKey)['key'];

        return [
            'data' => $data,
            'signature' => $signature,
            'publicKey' => $publicKey,
        ];
    }
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $keyword = trim($request->input('keyword'));

        $admins = $this->adminService->getAdmins($request->all(), true);

        $this->responseData['admins'] = $admins;
        $this->responseData['keyword'] = $keyword;

        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
        $this->responseData['roles'] = $roles;
		
		$department = CmsDepartment::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, id DESC')->get();
        $this->responseData['department'] = $department;

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
            'name' => 'required',
            'email' => "required|email|max:255|unique:admins",
            'password' => "required|min:8|max:255",
        ]);

        $params = $request->only([
            'email',
            'name',
            'role',
            'avatar',
            'status',
            'password',
            'is_super_admin',
			'department_id'
        ]);
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $data = $params['name'];
        $signatureData = $this->createDigitalSignature($data);

        $params['public_key'] = $signatureData['publicKey'];
        $params['private_key'] = $signatureData['signature'];

        Admin::create($params);

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
        $admin = Admin::find($id);

        if (!$admin) {
            return redirect()->route($this->routeDefault . '.index')->with('errorMessage', __('Record not found!'));
        }

        $roles = Role::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
		$department = CmsDepartment::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, id DESC')->get();
        $this->responseData['department'] = $department;
        $this->responseData['roles'] = $roles;
        $this->responseData['admin'] = $admin;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'name' => 'required',
            'email' => "required|email|max:255|unique:admins,email," . $admin->id,
        ]);

        $params = $request->only([
            'email',
            'name',
            'avatar',
            'role',
            'status',
            'is_super_admin',
			'department_id'
        ]);
        $password_new = $request->input('password_new');
        if ($password_new != '') {
            if (strlen($password_new) < 8) {
                return redirect()->back()->with('errorMessage', __('Password is very short!'));
            }
            $params['password'] = $password_new;
        }
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        /*
        if($admin->name != $params['name']){
            $data = $params['name'];
            $signatureData = $this->createDigitalSignature($data);

            $params['public_key'] = $signatureData['publicKey'];
            $params['private_key'] = $signatureData['signature'];
        }
        */
        
        $admin->fill($params);
        $admin->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage',  __('Delete record successfully!'));
    }
	
	public function loadMember(Request $request)
    {
        $dep = $request->dep;

        $department = CmsDepartment::where('id',$dep)->orderBy('id','asc')->first();
        $data_post = '';
        if($department){
            $members = Admin::where('status','active')->where('department_id',$dep)->where('id','!=',Auth::guard('admin')->user()->id)->get();
            $am = '';
            foreach($members as $admin){
                $am .= '<input class="checkbox _check_all _check_relative _check_all_'.$department->id.'" type="checkbox" name="user_receive[]" value="'.$admin->id.'" data-value="'.$department->id.'"> <span>'.$admin->name.'</span><br>';
            }
            $data_post .= '
            <tr class="">
                <td class="">
                    <input class="_check_all checkbox check_class" id="check_class_'.$department->id.'" type="checkbox" name="department[]" value="'.$department->id.'"> <span>'.$department->title.'</span>
                </td>
                <td class="">
                    '.$am.'
                </td>
            </tr>
            ';
        }else{
            // if(Auth::guard('admin')->user()->is_super_document == 1){
                // $admins = Admin::where('status','active')->where('id','!=',Auth::guard('admin')->user()->id)->get();
                // $departments = CmsDepartment::where('status','active')->orderBy('id','asc')->get();
            // } else {
                // $admins = Admin::where('status','active')->where('id','!=',Auth::guard('admin')->user()->id)->where('department_id',Auth::guard('admin')->user()->department_id)->get();
                // $departments = CmsDepartment::where('status','active')->where('id',Auth::guard('admin')->user()->department_id)->orderBy('id','asc')->get();
            // }
			
			$admins = Admin::where('status','active')->where('id','!=',Auth::guard('admin')->user()->id)->get();
            $departments = CmsDepartment::where('status','active')->orderBy('id','asc')->get();
            

            foreach($departments as $department){
                $members = Admin::where('status','active')->where('department_id',$dep)->get();
                $am = '';
                foreach($admins as $admin){
                    if($admin->department_id ==$department->id){
                        $am .= '<input class="checkbox _check_all _check_relative _check_all_'.$department->id.'" type="checkbox" name="user_receive[]" value="'.$admin->id.'" data-value="'.$department->id.'"> <span>'.$admin->name.'</span><br>';
                    }
                }
                $data_post .= '
                    <tr class="">
                        <td class="">
                            <input class="_check_all checkbox check_class" id="check_class_'.$department->id.'" type="checkbox" name="department[]" value="'.$department->id.'"> <span>'.$department->title.'</span>
                        </td>
                        <td class="">
                            '.$am.'
                        </td>
                    </tr>
                ';
            }

        }

        $data_post .= '
        <script>
            $(document).ready(function() {
                $(".check-all").on("change", function() {
                    if($(".check-all:checked").val() == 0){
                        $("._check_all").prop("checked",true);
                        $(".check-all-relative").prop("checked",true);
                    }else{
                        $("._check_all").prop("checked",false);
                        $(".check-all-relative").prop("checked",false);
                    }
                });
            
                $(".check_class").on("change", function() {
                    var class_id = $(this).val();
                    if($("#check_class_"+class_id+":checked").val() == class_id){
                        $("._check_all_"+class_id).prop("checked",true);
                    }else{
                        $("._check_all_"+class_id).prop("checked",false);
                    }
                    $(".check-all-relative").prop("checked",false);
                });
            
                $("._check_all").on("change", function() {
                    var class_id = $(this).attr("data-value");
                    $(".check-all").attr("disabled", false);
                    $(".check-all").prop("checked",false);
                    $("#check_class_"+class_id).prop("checked",false);
                });
            
                $(".btn-psadmin").attr("disabled", "disabled");
                
                $(".checkbox").on("change", function() {
                    if($(".checkbox:checked").val() >= 0){
                        $(".btn-psadmin").attr("disabled", false);
                    }else{
                        $(".btn-psadmin").attr("disabled", "disabled");
                    }
                });
                
                $(".check-all-relative").on("change", function() {
                    if($(".check-all-relative:checked").val() == 0){
                        $("._check_relative").prop("checked",true);
                    }else{
                        $("._check_relative").prop("checked",false);
                    }
                    $(".check-all").prop("checked",false);
                    $(".check_class").prop("checked",false);
                });
            
                $("._check_relative").on("change", function() {
                    $(".check-all").prop("checked",false);
                    $(".check-all-relative").prop("checked",false);
                });
            });
        </script>';
        return $data_post;

    }

    public function adminProfile()
    {
        $id = Auth::guard('admin')->user()->id;
        $admin = Admin::find($id);

        if (!$admin) {
            return redirect()->route($this->routeDefault . '.index')->with('errorMessage', __('Record not found!'));
        }

        $roles = Role::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();

        $department = CmsDepartment::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, id DESC')->get();

        $this->responseData['department'] = $department;
        $this->responseData['roles'] = $roles;
        $this->responseData['admin'] = $admin;

        return $this->responseView($this->viewPart . '.editprofile');
    }

    public function updateProfile(Request $request)
    {
        $id = Auth::guard('admin')->user()->id;
        $admin = Admin::find($id);
    
        $params = $request->all();
        $password_new = $request->input('password_new');
        if ($password_new != '') {
            if (strlen($password_new) < 8) {
                return redirect()->route('admin_profile.index')->with('errorMessage', __('Mật khẩu độ dài không đủ!'));
            }
            $params['password'] = $password_new;
        }
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $admin->fill($params);
        $admin->save();

        return redirect()->route('admin_profile.index')->with('successMessage', __('Successfully updated!'));
    }
}
