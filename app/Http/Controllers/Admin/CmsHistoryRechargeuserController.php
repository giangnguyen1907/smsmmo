<?php

namespace App\Http\Controllers\Admin;

use App\Models\CmsHistoryRechargeuser;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Services\ContentService;
use App\Consts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CmsHistoryRechargeuserController extends Controller
{
	public function __construct()
    {
        $this->routeDefault  = 'cms_history_rechargeuser';
        $this->viewPart = 'admin.pages.cms_history_rechargeuser';
		$this->locale = 'vi';
        $this->responseData['module_name'] = __('Lịch sử nạp tiền');
		
		$this->responseData['array_istype'] = $this->array_istype = array(1=>'VNPay',2=>'Chuyển khoản',3=>'Hệ thống nạp tiền');
        $this->responseData['array_status'] = $this->array_status = array(0=>'Thất bại',1=>'Thành công','2'=>'Hủy',3=>'Chờ duyệt', 4=>'Thu hồi');
		
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		if(ContentService::checkRole($this->routeDefault,'index') == 0){
			$this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
			return $this->responseView($this->viewPart . '.404');
		}
		/*
		$email = 'thanhp421@gmail.com';
		Mail::send('frontend.emails.payment', ['member_name' => 'Thành','payment'=>10000], function ($message) use ($email) {
			$message->to($email);
			$message->subject('Nạp tiền hệ thống sách điện tử');
		});
		*/
		$params = $request->all();
		
		$rows = ContentService::getHistoryRechargeuser($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $user = User::get();
		$this->responseData['rows'] = $rows;
		$this->responseData['user'] = $user;
		$this->responseData['params'] = $params;
		$this->responseData['array_istype'] = $this->array_istype;
        $this->responseData['array_status'] = $this->array_status;
		
		return $this->responseView($this->viewPart . '.index');
    }

    public function updateStatus(Request $request)
    {
        $id = $request->input('id');
        $action = $request->input('action');
        $row = CmsHistoryRechargeuser::find($id);
        $user = User::find($row->customer_id);

        if(!$user){
            return response()->json(['success' => false, 'message' => 'Không tìm thấy người dùng.']);
        }

        if ($row) {
            if ($action == 'approve') {
                $row->status = 1;

                $user->wallet = $user->wallet + $row->payment;
                $user->save();
				
				// Gửi email cho khách hàng
				$email = $user->email;
				Mail::send('frontend.emails.payment', ['member_name' => $user->name,'payment'=>$row->payment], function ($message) use ($email) {
					$message->to($email);
					$message->subject('Nạp tiền hệ thống sách điện tử');
				});
				
                $message = 'Đơn hàng đã được duyệt, cộng tiền vào tài khoản người dùng thành công.';
            } elseif ($action == 'recall') {
                $row->status = 4;

                if($user->wallet < $row->payment){
                    return response()->json(['success' => false, 'message' => 'Tài khoản của người dùng không đủ để thu hồi đơn hàng.']);
                }else{
                    $user->wallet = $user->wallet - $row->payment;
                    $user->save();
                    $message = 'Đơn hàng đã bị thu hồi, trừ đi số tiền trong tài khoản người dùng thành công.';
                }
            }elseif ($action == 'cancel') {
                $row->status = 2;
                $message = 'Đơn hàng đã bị hủy.';
            }
            $row->save();
            return response()->json(['success' => true, 'message' => $message]);
        } else {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy dữ liệu.']);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(ContentService::checkRole($this->routeDefault,'create') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $user = User::where('status','active')->get();
        $this->responseData['user'] = $user;
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
        if(ContentService::checkRole($this->routeDefault,'create') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $request->validate([
            'payment' => 'required|max:255',
        ]);
        
        $params = $request->all();
        $params['payment_method'] = 3;
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        CmsHistoryRechargeuser::create($params);

        $user = User::find($params['customer_id']);
        $user->wallet = $user->wallet + $params['payment'];
        $user->save();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    public function updatePayment(Request $request)
    {
        $id = $request->input('id');
        $payment = $request->input('payment');

        $cmsHistoryRechargeuser = CmsHistoryRechargeuser::findOrFail($id);
        $cmsHistoryRechargeuser->payment = $payment;
        $cmsHistoryRechargeuser->save();

        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return $this->responseView($this->viewPart . '.404');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        return $this->responseView($this->viewPart . '.404');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        return $this->responseView($this->viewPart . '.404');
    }
}
