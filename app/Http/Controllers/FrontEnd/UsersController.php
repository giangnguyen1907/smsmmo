<?php

namespace App\Http\Controllers\FrontEnd;

use Exception;
use App\Consts;
use App\Http\Services\ContentService;
use App\Http\Services\PageBuilderService;
use App\Models\Admin;
use App\Models\District;
use App\Models\Document;
use App\Models\Order;
use App\Models\Province;
use App\Models\CmsHistoryBuyebook;
use App\Models\User;
use App\Models\Ward;
use App\Models\Transaction;
use App\Models\CmsHistoryRechargeuser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class UsersController extends Controller
{
    protected $web_information;
    public function storeRecharge(Request $request)
    {
        $params = $request->all();
        $submit = $params['submit'];
        $amount_payment = $params['amount_payment'];
        $trans_code_transfer = $params['trans_code_transfer'];
        $customer_note = 'Nạp tiền tài khoản ' . Auth::user()->id;
		$recharge_info = time().rand(100,999);

        if($submit == 'payVnpay'){
            return redirect()->back()->with('error_recharge', 'Phương thức thanh toán VNPay đang cập nhật, vui lòng chọn phương thức khác');
        }

        $historyRecharge = CmsHistoryRechargeuser::create([
            'recharge_info' => $trans_code_transfer,
            'customer_id' => Auth::user()->id,
            'payment' => $amount_payment,
        ]);

        if($submit == 'transfer'){
             $historyRecharge->update([
                'recharge_info' => $trans_code_transfer,
                'status' => 3, // trạng thái chờ duyệt
                'payment_method' => 2,
            ]);

            $customer_name = Auth::user()->name;
            $document_name = "Nạp tiền hệ thống";
            
            // dd($this->web_information);
            $txt_email = $this->web_information->information->email ?? '';
            if($txt_email !=""){
                $array_email = explode(',',$txt_email);

                Mail::send('frontend.emails.pending', ['member_name' => $customer_name,'document_name'=>$document_name], function ($message) use ($array_email,$customer_name,$document_name) {
                    foreach($array_email as $email){
                        $message->to($email);
                    }
                    $message->subject($customer_name.' chờ nạp tiền hệ thống');
                });
            }
            
            return redirect()->back()->with('success_recharge', 'Cảm ơn bạn đã nạp tiền. Hệ thống kiểm tra và nạp tiền vào tài khoản sớm nhất cho bạn.');
        }

        if($submit == 'payVnpay'){
            
            $vnp_Url = env('vnp_Url', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');"";
            $vnp_TmnCode = $this->web_information->information->vnp_tmncode;
            $vnp_HashSecret = $this->web_information->information->vnp_hashsecret;
            $vnp_Returnurl = route('frontend.user.vpnay_recharge');
            $vnp_TxnRef = $recharge_info;
            $vnp_OrderInfo = $customer_note;
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = $amount_payment * 100;
            $vnp_Locale = 'vn';
            $vnp_BankCode = '';
            $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $recharge_info,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef
            );

            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }
            if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
                $inputData['vnp_Bill_State'] = $vnp_Bill_State;
            }

            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }
            $returnData = array('code' => '00', 'message' => 'success', 'data' => $vnp_Url);
            return redirect()->away($vnp_Url);
        }

        return redirect()->back()->with('error_recharge', 'Phương thức thanh toán không hợp lệ.');
    }

    public function vnpayRecharge(Request $request)
    {
        $historyRecharge = CmsHistoryRechargeuser::where('recharge_info', $request->vnp_OrderInfo)
                                   ->where('customer_id', Auth::user()->id)
                                   ->firstOrFail();

        if ($request->vnp_TransactionStatus) {
            if($request->vnp_TransactionStatus == "00"){ // Trạng thái thành công
            	$status = $payment_status = 1;
                $session_type = 'success_recharge';
                $message = 'Nạp tiền vào tài khoản thành công';
                
                //thành công cộng tiền vào tài khoản
                Auth::user()->wallet = Auth::user()->wallet + $historyRecharge->payment;
                Auth::user()->save();

                $historyRecharge->update([
                    'status' => $status,
                    'payment_method' => 1,
                    'transaction_no'=> $request->vnp_TransactionNo,
                    'response_code' => $request->vnp_TransactionStatus
                ]);

            }else{
            	$status = $payment_status = 0; // Giao dịch thất bại
                $session_type = 'error_recharge';
                $message = 'Giao dịch đã bị hủy, nạp tiền vào tài khoản không thành công.';
                
                $historyRecharge->update([
                    'status' => $status,
                    'payment_method' => 1,
                    'transaction_no'=> $request->vnp_TransactionNo,
                    'response_code' => $request->vnp_TransactionStatus
                ]);
            }

            // Transaction::create([
            //     'order_code' => $request->vnp_OrderInfo,
            //     'guest' => Auth::user()->name,
            //     'guest_id' => Auth::user()->id,
            //     'transaction_no' => $request->vnp_TransactionNo,
            //     'response_code' => $request->vnp_TransactionStatus,
            //     'amount' => $request->vnp_Amount / 100,
            //     'date_at' => now(),
            //     'is_type' => 4,
            //     'status' => $status,
            //     'content' => '',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ]);
            
            session()->flash($session_type, $message);
            return redirect()->route('frontend.user.index');
        } else {
            session()->flash('error_recharge', 'Có lỗi xảy ra. Thanh toán thất bại!');
            return redirect()->route('frontend.user.index');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editInfor(Request $request) 
    {
        try {
            if ($request->name != '') {
                $params['name'] = $request->name;
            }
            if ($request->phone != '') {
                $params['phone'] = $request->phone;
            }
            if ($request->email != '') {
                $params['email'] = $request->email;
            }
            if ($request->address != '') {
                $params['address'] = $request->address;
            }
    
            $user = User::find(auth()->user()->id);
    
            $user->fill($params);
            $user->save();
    
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);

            if (!Hash::check($request->oldPassword, $user->password)) {
                return response()->json(['success' => false, 'message' => 'Mật khẩu cũ không chính xác!']);
            }

            $user->password = $request->newPassword;
            $user->save();

            return response()->json(['success' => true, 'message' => 'Cập nhật mật khẩu thành công!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function index(Request $request)
    {
        if( Auth::guard('web')->check() ){
            $listProvince = Province::getProvince();
            $this->responseData['listProvince'] = $listProvince;
            $this->responseData['listDistrict'] = District::getDistrict();
            $this->responseData['listWard'] = Ward::getWard();

            // ds yêu thích
			$listDocuments = [];
            $listDocumentIds = auth()->user()->like_document;
			if(!empty($listDocumentIds)){
				$listDocuments = Document::whereIn('id', $listDocumentIds)
					->where('status', 1)    
					->paginate(8);
			}

            // ds đơn hàng
            $user = auth()->user(); 
            $id = $user->id;
            $status = $request->input('status', 'complete');

            $orders = Order::with('orderDetails')
                ->where('customer_id', $id)
                ->where('status', $status)
                ->orderBy('id', 'DESC')
                ->get();
            
            $buyEbook = CmsHistoryBuyebook::buyEbookUser(Auth::user()->id);

            $this->responseData['details'] = $orders;    
            $this->responseData['status'] = $status;    
            $this->responseData['listDocuments'] = $listDocuments;
            $this->responseData['buyEbook'] = $buyEbook;

            $this->responseData['array_payment_method'] = array(1=>'Vpnay',2=>'Tài khoản',3=>'Chuyển khoản');

            return $this->responseView('frontend.pages.user.index');
        }else{
            
            return $this->responseView('frontend.pages.home');
        }
        
    }

    public function statusOrder(Request $request) 
    {
        $user = auth()->user();
        $id = $user->id;

        $status = $request->status;
        $orders = Order::with('orderDetails')
            ->where('customer_id', $id)
            ->where('status', $status)
            ->orderBy('id', 'DESC')
            ->get();

        $this->responseData['details'] = $orders;    
        $this->responseData['status'] = $status;  
        return $this->responseView('frontend.pages.user.partials_order_tracking');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        
        $params = $request->all();
        $targetDir = "member/hinhanh".Auth::guard('web')->user()->id."/";
        //$allowTypes = array('jpg','png','jpeg','gif');
        if(!file_exists($targetDir)){
            if(mkdir($targetDir)){
                //echo "Tạo thư mục thành công.";
            }
        }
        
        if($_FILES['image']['name']){
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
    
            $imageName = time().'.'.$request->image->extension();  
    
            $request->image->move(public_path($targetDir), $imageName);
            
            $path_image = $targetDir.$imageName;
        }else{
            $path_image = $params['avatar'];
        }
        
        /**/
        
        Auth::guard('web')->user()->avatar = '/'.$path_image;
        Auth::guard('web')->user()->sex = $params['sex'];
        Auth::guard('web')->user()->birthday = $params['birthday'];
        Auth::guard('web')->user()->phone = $params['phone'];
        
        Auth::guard('web')->user()->save();

        /*
        Auth::guard('web')->user()->save();
        */
        //print_r($params);die;
        return $this->responseView('frontend.pages.user.index');
        
    }
    public function showRegisterForm()
    {   
        if (Auth::guard('web')->check()) {
            return redirect()->route('frontend.home');
        }
        return $this->responseView('frontend.pages.register');
    }

    public function register(Request $request)
    {
        // dd(Auth::guard('web'));
        if (Auth::guard('web')->check()) {
            return redirect()->route('frontend.home');
        }
        $params = $request->all();
        $url = route('frontend.home');
		
		$checkEmail = User::where('username', trim($params['email']))->where('status','active')->first();
		
		if($checkEmail){
			return redirect()->back()->with('error', 'Tài khoản đã tồn tại, vui lòng đăng ký bằng tài khoản khác!');
		}
		
        $user_register = new User();
        $user_register->name = $params['name'];
        $user_register->username = $params['email'];
        $user_register->password = $params['password'];
        $user_register->status = 'active';
        $saveUser = $user_register -> save();
        
        if ($saveUser) {
            if (Auth::guard('web')->attempt([
                'username' => $params['email'],
                'password' => $params['password'],
            ])) {
                return redirect($url)->with('success', 'Đăng ký thành công!');
            } else {
                return redirect()->back()->with('error', 'Đăng ký thất bại');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
    }

    public function sendEmailPassword(Request $request)
    {
        DB::beginTransaction();

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Email chưa được đăng ký!']);
            }

            $token = Str::random(64);
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

        
            // Gửi email chứa token reset password
            Mail::send('frontend.pages.user.send_email_password', ['token' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset Password');
            });
    
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Email đặt lại mật khẩu đã được gửi!']);
    
        } catch (\Exception $e) {
            Log::error('Lỗi: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Không thể gửi email. Vui lòng thử lại sau.']);
        }
    }

    public function resetPasswordView($token) 
    {
        $this->responseData['token'] = $token;
        return $this->responseView('frontend.pages.home.index');
    }

    public function resetPasswordPost(Request $request)
    {

        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.exists' => 'Email không tồn tại trong hệ thống.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.'
        ]);

        $passwordReset = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])->first();

        if (!$passwordReset) {
            return response()->json(['success' => false, 'message' => 'Token không hợp lệ hoặc đã hết hạn!']);
        }

        // Kiểm tra thời gian hết hạn (giả sử token có hạn 60 phút)
        $expiresAt = Carbon::parse($passwordReset->created_at)->addMinutes(60);
        if (Carbon::now()->greaterThan($expiresAt)) {
            return response()->json(['success' => false, 'message' => 'Token đã hết hạn. Vui lòng gửi lại email!']);
        }

        User::where('email', $request->email)
            ->update([
                'password' => Hash::make($request->password)
            ]);

        DB::table('password_resets')->where('email', $request->email)->delete();

        return response()->json(['success' => true, 'message' => 'Thay đổi mật khẩu thành công!']);
    }
}
