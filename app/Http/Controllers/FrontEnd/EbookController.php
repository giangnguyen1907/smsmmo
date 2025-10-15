<?php

namespace App\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use App\Consts;
use App\Http\Services\ContentService;
use App\Http\Services\PageBuilderService;
use App\Models\EbookPackage;
use App\Models\Transaction;
use App\Models\Document;
use App\Models\CmsHistoryBuyebook;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class EbookController extends Controller
{
    public function listBuyebookDocumentByUser(Request $request)
    {
        if (Auth::check()) {
            $params['status'] = 1;
            $params['customer_id'] = Auth::user()->id;
            $listBuyEbook = ContentService::getHistoryBuyebook($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
    
            $this->responseData['listBuyEbook'] = $listBuyEbook;
            return $this->responseView('frontend.pages.document.list_buyebook_document');
        } else {
            return redirect()->route('frontend.home');
        }
    }
    public function storeBuyEbook(Request $request)
    {
	
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		$params = $request->all();
		$submit = $params['submit'];
		$documentId = $params['documentId'];
		$madonhang = $params['madonhang'];
		$totalPayment = $params['totalPayment'];
		$ebookPackageId = $params['ebookPackage'];
		$bookDetailUrl = $params['bookDetailUrl'];

		if($submit == 'payVnpay'){
			return redirect()->back()->with('error', 'Phương thức thanh toán VNPay đang cập nhật, vui lòng chọn phương thức khác');
		}

		$document = Document::findOrFail($documentId);

		$isValid = CmsHistoryBuyebook::isReadValid(Auth::user()->id, $document->id);

		if ($isValid) {
			session()->flash('error', 'Thời gian đọc vẫn còn hiệu lực. Bạn không thể mua lại Ebook này.');
			return redirect()->back();
		}

		$customer_note = 'Thanh toán mua ebook '. $document->title . '_' .$document->id;
		$buy_info = time().rand(100,999);

		$ebook = EbookPackage::findOrFail($ebookPackageId);

		$_SESSION['book_detail_url'] = $bookDetailUrl;
		
		if($submit == 'payAcc'){
			if(Auth::user()->wallet >= $totalPayment){
				
				$historyBuyEbook = CmsHistoryBuyebook::create([
					'buy_info' => $madonhang,
					'customer_id' => Auth::user()->id,
					'payment' => $totalPayment,
					'document_id' => $documentId,
					'ebook_package_id' => $ebookPackageId,
					'time_read' => $ebook->time, //thời gian đọc ebook
					'buy_date' => now(),
				]);

				$document->update([
					'download' => $document->download + 1
				]);
				
				Auth::user()->wallet = Auth::user()->wallet - $totalPayment;
				Auth::user()->save();

				$historyBuyEbook->update([
					'status' => 1,
					'payment_method' => 2,
				]);

				return redirect()->back()->with('success', 'Đã thanh toán thành công! Cảm ơn bạn đã mua Ebook. Bấm nút "Đọc sách" để đọc Ebook với phiên bản đầy đủ.');
			}else{
				
				return redirect()->back()->with('error', 'Tài khoản thanh toán không đủ tiền.');
			}
		}
		
		if($submit == 'accept'){ // Thanh toán chuyển khoản
			
			// Kiểm tra đã bấm chưa
			$check_payment = CmsHistoryBuyebook::where('buy_info',$madonhang)->first();
			if($check_payment){
				return redirect()->back()->with('success', 'Đơn hàng của bạn đang chờ duyệt. Cảm ơn quý độc giả đã tin tưởng.');
			}else{
				try{
					DB::beginTransaction();
					//echo "Đang kiểm tra";die;
					// Kiểm tra xem tài khoản này đã mua sách hay chưa

					$status = 3;
					// Sách 0 đồng
					if($totalPayment == 0){
						$status = 1;
					}

					$historyBuyEbook = CmsHistoryBuyebook::create([
						'buy_info' => $madonhang,
						'customer_id' => Auth::user()->id,
						'payment' => $totalPayment,
						'document_id' => $documentId,
						'ebook_package_id' => $ebookPackageId,
						'status' => $status,
						'payment_method' => 3,
						'time_read' => $ebook->time, //thời gian đọc ebook
						'buy_date' => now(),
					]);

					// Gửi email cho admin
					
					$customer_name = Auth::user()->name;
					$document_name = $document->title;
					
					if($totalPayment > 0){
						$email = env('email_admin','newwaytech.thanhpv@gmail.com');
						$email2 = env('email_admin2','tranhien23102k@gmail.com');
						Mail::send('frontend.emails.pending', ['member_name' => $customer_name,'document_name'=>$document_name], function ($message) use ($email,$email2,$customer_name,$document_name) {
							$message->to($email);
							$message->to($email2);
							$message->subject($customer_name.' chờ mua sách '.$document_name);
						});
					}
					/**/
					DB::commit();
				}catch(Exception $e) {
					DB::rollBack();
					return redirect()->back()->with('errorMessage', __('Error'));
					//return false;
				}
				
				return redirect()->back()->with('success', 'Đơn hàng của bạn đang chờ duyệt. Cảm ơn quý độc giả đã tin tưởng.');
				
			}
			
		}
	
		if($submit == 'payVnpay'){
			
			$historyBuyEbook = CmsHistoryBuyebook::create([
				'buy_info' => $buy_info,
				'customer_id' => Auth::user()->id,
				'payment' => $totalPayment,
				'document_id' => $documentId,
				'ebook_package_id' => $ebookPackageId,
				'time_read' => $ebook->time, //thời gian đọc ebook
				'buy_date' => now(),
			]);

			$document->update([
				'download' => $document->download + 1
			]);
			
			$vnp_Url = env('vnp_Url', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');"";
			$vnp_TmnCode = $this->web_information->information->vnp_tmncode;
			$vnp_HashSecret = $this->web_information->information->vnp_hashsecret;
			$vnp_Returnurl = route('frontend.ebook.vpnay_ebook');
			$vnp_TxnRef = $madonhang;
			$vnp_OrderInfo = $customer_note;
			$vnp_OrderType = 'billpayment';
			$vnp_Amount = $totalPayment * 100;
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
				"vnp_OrderInfo" => $buy_info,
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
	
        return redirect()->back()->with('error', 'Phương thức thanh toán không hợp lệ.');
    }

    public function vnpayEbook(Request $request)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $bookDetailUrl = isset($_SESSION['book_detail_url']) ? $_SESSION['book_detail_url'] : null;
        unset($_SESSION['book_detail_url']);

        $buyEbook = CmsHistoryBuyebook::where('buy_info', $request->vnp_OrderInfo)
                                   ->where('customer_id', Auth::user()->id)
                                   ->firstOrFail();
        
        if ($request->vnp_TransactionStatus) {
            if($request->vnp_TransactionStatus == "00"){ // Trạng thái thành công
            	$status = $payment_status = 1;;
                
                $session_type = 'success';
                $message = 'Đã thanh toán thành công! Cảm ơn bạn đã mua Ebook. Bấm nút "Đọc sách" để đọc Ebook với phiên bản đầy đủ.';
                //cập nhật phương thức thanh toán mua ebook
                $buyEbook->update([
                    'status' => $status,
                    'payment_method' => 1,
                    'transaction_no'=> $request->vnp_TransactionNo,
                    'response_code' => $request->vnp_TransactionStatus
                ]);

            }else{
            	$status = $payment_status = 0; // Giao dịch thất bại
                $session_type = 'error';
                $message = 'Giao dịch đã bị hủy, mua Ebook không thành công.';
                // cập nhật trạng thái lịch sử mua không thành công
                $buyEbook->update([
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
            //     'is_type' => 3,
            //     'status' => $status,
            //     'content' => '',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ]);
            
            session()->flash($session_type, $message);
            return redirect($bookDetailUrl);
        } else {
            session()->flash('error', 'Có lỗi xảy ra. Thanh toán thất bại!');
            return redirect($bookDetailUrl);
        }
    }

    public function calculatePrice(Request $request)
    {
        $ebookId = $request->input('ebookId');

        $documentId = $request->input('documentId');

        $totalPrice = 0;

        $document = Document::find($documentId);
        $priceDocument = $document->price;
        $pageDocument = $document->number_page;

        $ebook = EbookPackage::find($ebookId);
        $price = $ebook->price; //giá (1trang/Mb)
        $percent = $ebook->percent; //x%
        $min_price = $ebook->min_price; //giá tối thiểu
        
        if($ebook->recipe == 1){
            //giá 1 trang x tổng số trang
            $totalPrice = $price * $pageDocument;
        }elseif($ebook->recipe == 2){
            //theo x% giá sách giấy
            $totalPrice = $priceDocument * $percent / 100;
        }elseif($ebook->recipe == 3){
            //tự nhập giá
            $totalPrice = $price;
        }

        $totalPrice = $this->roundPrice($totalPrice, $ebook->rounding);

        if($totalPrice < $min_price){
            $totalPrice = $min_price;
        }

        return response()->json(['totalPrice' => $totalPrice]);
    }

    private function roundPrice($price, $rounding) {
        switch ($rounding) {
            case 1:
                return round($price, 0); // Làm tròn đến hàng đơn vị
            case 10:
                return round($price, -1); // Làm tròn đến hàng chục
            case 100:
                return round($price, -2); // Làm tròn đến hàng trăm
            case 1000:
                return round($price, -3); // Làm tròn đến hàng nghìn
            default:
                return $price;
        }
    }
}
