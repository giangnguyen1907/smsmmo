<?php

namespace App\Http\Controllers\FrontEnd;

use App\Consts;
use App\Models\CmsProduct;
use App\Models\Document;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Province;
use App\Models\District;
use App\Models\Voucher;
use App\Models\User;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeOrderService(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required',
                'phone' => 'required',
                'customer_note' => "required|string",
                'item_id' => "required|integer|min:0",
            ]);
            // Check and store order
            $order_params = $request->only([
                'name', 'email', 'phone', 'customer_note'
            ]);
            $order_params['is_type'] = Consts::ORDER_TYPE['service'];
            $order = Order::create($order_params);

            // Check and store order_detail
            $order_detail_params = $request->only([
                'item_id', 'quantity', 'price', 'discount'
            ]);
            $order_detail_params['quantity'] = $request->get('quantity') > 0 ? $request->get('quantity') : 1;
            $order_detail_params['order_id'] = $order->id;
            $order_detail_params['json_params']['post_type'] = Consts::POST_TYPE['service'];
            $order_detail_params['json_params']['post_link'] = $request->headers->get('referer');

            $order_detail = OrderDetail::create($order_detail_params);

            $messageResult = $this->web_information->information->notice_advise ?? __('Booking successfull!');

            if (isset($this->web_information->information->email)) {
                $email = $this->web_information->information->email;
                Mail::send(
                    'frontend.emails.booking',
                    [
                        'order' => $order,
                        'order_detail' => $order_detail
                    ],
                    function ($message) use ($email) {
                        $message->to($email);
                        $message->subject(__('You received a new appointment from the system'));
                    }
                );
            }
            DB::commit();
            return $this->sendResponse($order, $messageResult);
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    // Cart
    public function cart()
    {
		$listProvince = Province::getProvince();
		$this->responseData['listProvince'] = $listProvince;
		$this->responseData['listDistrict'] = District::getDistrict();
        return $this->responseView('frontend.pages.cart.index');
    }

    public function addToCart(Request $request)
    {
        $id = $request->get('id') ?? null;
        $quantity = $request->get('quantity')  ?? 1;

        $product = Document::find($id);

        if (!$product) {
            return response()->json(['error' => 'Sách chưa có trong hệ thống!'], 404);
        }

        if($product->status_hang == 0){
            return response()->json(['error' => 'Sách hiện tại đã hết hàng!'], 404);
        }

        $cart = session()->get('cart', []);
        $price = $product->price;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $cart[$id]['quantity'] + $quantity;
        } else {
            $cart[$id] = [
                "title" => $product->title,
                "quantity" => $quantity,
                "price" => $price,
                "image" => $product->image,
                // "image_thumb" => $product->image_thumb
            ];
        }

        session()->put('cart', $cart);
        //return response()->json(['success' => 'Thêm vào giỏ hàng thành công!']);
		
		$tamtinh = 0;
		$text_cart = '<div class="tg-minicartbody">';
		foreach(session('cart') as $cart_){
			$tamtinh = $tamtinh + $cart_['price'];
			$text_cart.= '<div class="tg-minicarproduct">
					<figure style="margin-right: 15px; width: 30%;">
						<a href="javascript:void(0);">
							<img src="'.$cart_['image'].'" alt="'.$cart_['title'].'">
						</a>
					</figure>
					<div class="tg-minicarproductdata">
						<h5><a href="javascript:void(0);">'.$cart_['title'].'</a></h5>
						<h6><a href="javascript:void(0);">'.number_format($cart_['price']).'₫</a></h6>
					</div>
				</div>';
		}
		
		$text_cart .= '</div>
				<div class="" style="margin-left: 57%; margin-top: 10px;">Tạm tính: <strong>'.number_format($tamtinh).'₫</strong></div>
				<div class="tg-minicartfoot">
					<a class="tg-btnemptycart" href="javascript:;" onclick="clearCart()">
						<i class="fa fa-trash-o"></i>
						<span>Xóa giỏ hàng</span>
					</a>
					<span class="tg-subtotal" onclick="refreshCart()" style="cursor: pointer;">
						<i class="fa fa-refresh" aria-hidden="true"></i>
						Làm mới giỏ hàng</span>
					<div class="tg-btns">
						<a class="tg-btn tg-active" href="/gio-hang">Giỏ hàng</a>
						<a class="tg-btn" href="/order-tracking">Theo giõi đơn hàng</a>
					</div>
				</div>';
		
		echo $text_cart;
		
		//$number = count(session()->get('cart')) ?? 0;
		
		//return $number;
		
    }

    public function updateCart(Request $request)
    {
        $quantity = request('quantity') ?? '1';
        $id = request('id') ?? '';
        $totalPrice = 0;

        if ($id && $quantity) {
            $cart = session()->get('cart');
            $cart[$id]["quantity"] = $quantity;
            session()->put('cart', $cart);

            foreach ($cart as $item) {
                $totalPrice += $item['price'] * $item['quantity'];
            }
            return response()->json([
                'quantity' => $cart[$id]['quantity'],
                'price' => $cart[$id]['quantity'] * $cart[$id]['price'],
                'totalPrice' => $totalPrice
            ]);
        }
    }

    public function removeCart(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('successMessage', 'Đã xóa sản phẩm khỏi giỏ hàng!');
        }
    }

    public function storeOrderProduct(Request $request)
    {
        if($request->submit == 'vnpay'){
            session()->flash('error', 'Phương thức thanh toán VNPay đang cập nhật, vui lòng chọn phương thức khác');
			return redirect()->back();
        }

        $customer_note = $request->customer_note ?? 'Thanh toán đơn hàng';
		$order_info = time().rand(100,999);
		//dd($request->submit);
		// DB::beginTransaction();
		// try {
			$cart = session()->get('cart', []);
			if (empty($cart)) {
				return redirect()->back()->with('errorMessage', __('Cart is empty!'));
			}
			
			$request->validate([
				'name' => 'required',
				'phone' => 'required',
				'address' => 'required',
			]);
			$ship = $discount = 0;
			$voucher_id = $request->voucher_id;

			if($voucher_id > 0){
				// Kiểm tra voucher có tồn tại hay không
				$checkVoucher = Voucher::getVoucherId($voucher_id,date('Y-m-d H:i:s'));

				if($checkVoucher){
					$discount = $checkVoucher->discount;
					$checkVoucher -> status = 0;
					$checkVoucher -> save();
				}

			}else{
				$discount = $request->discount ?? 0;
			}
			$ship = $request->ship ?? 0;
			
			$array_params = array();
			
			$array_params['province_id'] = $request->tinhthanh;
			$array_params['province_name'] = $request->txt_tinhthanh;
			$array_params['district_id'] = $request->quanhuyen;
			$array_params['district_name'] = $request->txt_quanhuyen;
			$array_params['ward_id'] = $request->xaphuong;
			$array_params['ward_name'] = $request->txt_xaphuong;
			
			$order_params['name'] = $request->name;
			$order_params['email'] = $request->email;
			$order_params['phone'] = $request->phone;
			$order_params['customer_note'] = $request->customer_note;
			$order_params['address'] = $request->address.', '.$request->txt_xaphuong.', '.$request->txt_quanhuyen.', '.$request->txt_tinhthanh;
			
			$order_params['is_type'] = Consts::ORDER_TYPE['product'];
			$order_params['status'] = 'pending';
			
			if ($request->submit == 'transfer'){
				$order_params['Payment_method'] = '2';
				$order_params['trans_code'] = $request->trans_code_transfer;
                $order_params['response_code'] = '00';
			}else{
				$order_params['Payment_method'] = '0';
			}
			
			$order_params['payment_status'] = '0';
			$order_params['total_payment'] = $request->total_payment;
			$order_params['ship'] = $ship;
			$order_params['discount'] = $discount;
			$order_params['order_info'] = $order_info;
			$order_params['payment'] = $request->payment;
			$order_params['json_params'] = $array_params;
			$order_params['order_date'] = Carbon::now();
			if ($request->customer_id) {
				$order_params['customer_id'] = $request->customer_id;
			}

			$order = Order::create($order_params);
			$totalPayment = 0;
			$data = [];
			foreach ($cart as $id => $details) {
				// Check and store order_detail

				$thanhtien = $details['quantity']*$details['price'];

				$order_detail_params['order_id'] = $order->id;
				$order_detail_params['item_id'] = $id;
				$order_detail_params['quantity'] = $details['quantity'] ?? 1;
				$order_detail_params['price'] = $details['price'] ?? null;
				$order_detail_params['customer_note'] = $details['customer_note'] ?? null;
				$order_detail_params['status'] = 'pending';
				array_push($data, $order_detail_params);
				
				$totalPayment = $totalPayment + $thanhtien;

			}
			
			//dd($request->submit);
			OrderDetail::insert($data);

			$customer_id = $request->customer_id;
			if($request->customer_id == ""){
				// Kiểm tra xem có phải khách hàng cũ trong hệ thống hay không
				$checkUser = User::where('phone','=', str_replace(['.',',','-',' ','+'], '', $request->phone) )->first();
				if($checkUser){
					$customer_id = $checkUser->id;
				}else{
					$user_params = []; $array_address = [] ;

					$array_address['province_id'] = $request->tinhthanh;
					$array_address['province_name'] = $request->txt_tinhthanh;
					$array_address['district_id'] = $request->quanhuyen;
					$array_address['district_name'] = $request->txt_quanhuyen;
					$array_address['ward_id'] = $request->xaphuong;
					$array_address['ward_name'] = $request->txt_xaphuong;

					$user_params['name'] = $request->name;
					$user_params['username'] = str_replace(['.',',','-',' ','+'], '', $request->phone);
					$user_params['email'] = $request->email ?? strtolower(preg_replace('/[^a-z0-9.]+/', '', $request->name)) . '@gmail.com';
					$user_params['phone'] = str_replace(['.',',','-',' ','+'], '', $request->phone);
					$user_params['note'] = $request->customer_note;
					$user_params['password'] = $request->phone;
					$user_params['address'] = $request->address;
					$user_params['status'] = 'active';
					$user_params['debt'] = 0;
					$user_params['created_at'] = date('Y-m-d H:i:s');
					$user_params['updated_at'] = date('Y-m-d H:i:s');
					$user_params['json_params'] = $array_address;

					$checkUser = User::create($user_params);

					$customer_id = $checkUser->id;

				}
			}

			$payment = $totalPayment + $ship - $discount;
			$order -> total_payment = $totalPayment;
			$order -> customer_id = $customer_id;
			$order -> payment = $payment ;
			$order -> save();

			if ($request->submit == 'vnpay') {

				$vnp_Url = env('vnp_Url', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');"";
				$vnp_TmnCode = $this->web_information->information->vnp_tmncode;
				$vnp_HashSecret = $this->web_information->information->vnp_hashsecret;
				
				$vnp_Returnurl = route('frontend.order.return_cart');
				
				$vnp_TxnRef = $_REQUEST['madonhang']; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
				$vnp_OrderInfo = $customer_note;
				$vnp_OrderType = 'billpayment';
				$vnp_Amount = $payment * 100;
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
					"vnp_OrderInfo" => $order_info,
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

				//var_dump($inputData);
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
				header('Location: ' . $vnp_Url);
				die();
			}
			
			//DB::commit();

			session()->forget('cart');
			
			// return redirect()->back()->with('successMessage', $messageResult);
			session()->flash('success', 'Đặt hàng thành công. Cảm ơn bạn đã mua hàng!');
			return redirect()->back();
		// } catch (Exception $ex) {
			// DB::rollBack();
			// throw $ex;
			// session()->flash('error', 'Có lỗi xảy ra!');
		// }
	
    }

    public function returnCart(Request $request)
    {
        if ($request->vnp_TransactionStatus) {
            //$orderDate = Carbon::createFromTimestamp(1715937036);
            // dd($orderDate);
            $order = Order::where('order_info', $request->vnp_OrderInfo)->first();

            if($request->vnp_TransactionStatus == "00"){ // Trạng thái thành công
            	$status = 1;
                $payment_status = 1;
                $session_type = 'success';
                $message = 'Đã thanh toán thành công! Đơn hàng sẽ được chuyển đi trong thời gian sớm nhất.';
            }else{
            	$status = 0; // Giao dịch thất bại
                $payment_status = 0;
                $session_type = 'error';
                $message = 'Giao dịch đã bị hủy, vui lòng đặt lại đơn hàng.';
            }

            if ($order) {
				$order->trans_code = $request->vnp_TransactionNo;
				$order->response_code = $request->vnp_TransactionStatus;
                $order->payment_status = $payment_status;
                $order->payment_method = '3';
                $order->payment = $request->vnp_Amount / 100;
                $order->save();

                $transaction_params = [];
                $transaction_params ['order_code'] = $order->order_info;
                $transaction_params ['guest'] = $order->name;
                $transaction_params ['guest_id'] = $order->customer_id;
                $transaction_params ['transaction_no'] = $order->trans_code;
                $transaction_params ['response_code'] = $order->response_code;
                $transaction_params ['amount'] = $order->payment;
                $transaction_params ['date_at'] = $order->order_date;
                $transaction_params ['is_type'] = 2;
                $transaction_params ['status'] = $status ;
                $transaction_params ['content'] = $order->customer_note;
                $transaction_params ['created_at'] = date('Y-m-d H:i:s');
				$transaction_params ['updated_at'] = date('Y-m-d H:i:s');

				Transaction::create($transaction_params);

            }

            session()->forget('cart');
            session()->flash($session_type, $message);
            return redirect()->route('frontend.order.cart');
        } else {
            session()->flash('error', 'Có lỗi xảy ra. Thanh toán thất bại!');
            return redirect()->route('frontend.order.cart');
        }

    }

    public function saveCart(Request $request)
    {
        // try {
            $cart = session()->get('cart', []);
            if (empty($cart)) {
                return redirect()->back()->with('error', __('Cart is empty!'));
            }
    
            $request->validate([
                'name' => 'required',
                'phone' => 'required'
            ]);
    
            // Check and store order
            $order_params = $request->only([
                'name',
                'email',
                'phone',
                'address',
                'customer_note'
            ]);
            $order_params['is_type'] = Consts::ORDER_TYPE['product'];
            $order_params['status'] = 'pending';
            $order_params['Payment_method'] = '0';
            $order_params['payment_status'] = '0';
            $order_params['trans_code'] = $request->madonhang;
            $order_params['total_payment'] = $request->total_payment;
            $order_params['ship'] = '0';
            $order_params['discount'] = '0';
            $order_params['payment'] = $request->payment;
    
            $order_params['order_date'] = Carbon::now();
            if ($request->customer_id) {
                $order_params['customer_id'] = $request->customer_id;
            }
    
            $order = Order::create($order_params);
    
            $data = [];
            foreach ($cart as $id => $details) {
                // Check and store order_detail
                $order_detail_params['order_id'] = $order->id;
                $order_detail_params['item_id'] = $id;
                $order_detail_params['quantity'] = $details['quantity'] ?? 1;
                $order_detail_params['price'] = $details['price'] ?? null;
                $order_detail_params['customer_note'] = $details['customer_note'] ?? null;
                $order_detail_params['status'] = 'pending';
                array_push($data, $order_detail_params);
            }
            OrderDetail::insert($data);
        //     return response()->json(['success' => true, 'message' => 'Lưu thành công!']);
        // } catch (\Exception $e) {
        //     return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra!']);
        // }
    }

    public function orderTracking(Request $request)
    {
        if (Auth::check()) {
            $id = auth()->user()->id;
            $status = $request->input('status', 'complete');

            $orders = Order::with('orderDetails')
                ->where('customer_id', $id)
                ->where('status', $status)
                ->orderBy('id', 'DESC')
                ->get();

            $this->responseData['details'] = $orders;
            $this->responseData['status'] = $status; 
        }
        return $this->responseView('frontend.pages.cart.order_tracking');
    }

    public function clearCart()
    {
        session()->forget('cart');
        return true;
        //session()->flash('success', 'Xóa giỏ hàng thành công!');
        //return response()->json(['success' => 'Xóa giỏ hàng thành công!']);
    }
}
