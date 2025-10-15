<?php

namespace App\Http\Controllers\Admin;

//use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Consts;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'users';
        $this->viewPart = 'admin.pages.users';
        $this->responseData['module_name'] = __('Public user management');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 
        
        $keyword = trim($request->input('keyword'));

        $rows = User::when(!empty($keyword), function ($query) use ($keyword) {
            $pattern = '%' . $keyword . '%';
            return $query->where(function ($where) use ($pattern) {
                $where->where('name', 'like', $pattern)->orWhere('email', 'like', $pattern);
            });
        })->orderByRaw('id DESC')
        ->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
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
        //
        $request->validate([
            'name' => 'required',
            'email' => "required|email|max:255|unique:admins",
            'password' => "required|min:8|max:255",
        ]);
        $params = $request->all();
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        User::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
        $this->responseData['user'] = $user;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
        $request->validate([
            'name' => 'required',
            'email' => "required|email|max:255|unique:admins,email," . $user->id,
        ]);

        $params = $request->all();

        $password_new = $request->input('password_new');
        if ($password_new != '') {
            if (strlen($password_new) < 8) {
                return redirect()->back()->with('errorMessage', __('Password is very short!'));
            }
            $params['password'] = $password_new;
        }
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $user->fill($params);
        $user->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage',  __('Delete record successfully!'));
    }

    public function createUsers(Request $request)
    {
        
        $name = $request->name;
        $phone = $request->phone;
        $email = $request->email;
        $debt = $request->debt;
        /**/
        $params = [];
        $params['name'] = $name;
        $params['phone'] = $phone;
        $params['email'] = $email;
        $params['debt'] = $debt;
        $params['password'] = '12345678';
        $params['status'] = 'active';
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
        $params['updated_at'] = date('Y-m-d H:i:s');
        $params['created_at'] = date('Y-m-d H:i:s');
        
        $cmsUser = User::create($params);
        
        return $cmsUser->id;
        
    }

}
