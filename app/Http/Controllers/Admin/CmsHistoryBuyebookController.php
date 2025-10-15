<?php

namespace App\Http\Controllers\Admin;

use App\Models\CmsHistoryBuyebook;
use App\Models\User;
use App\Models\CmsAuthor;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Services\ContentService;
use App\Consts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CmsHistoryBuyebookController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'history_buyebook';
        $this->viewPart = 'admin.pages.cms_history_buyebook';
		$this->locale = 'vi';
        $this->responseData['module_name'] = __('Lịch sử mua ebook');
		
		$this->responseData['array_payment_method'] = $this->array_payment_method = array(1=>'VNPay',2=>'Tài khoản',3=>'Chuyển khoản');
        $this->responseData['array_status'] = $this->array_status = array(0=>'Thất bại',1=>'Thành công',2=>'Hủy bỏ',3=>'Chờ duyệt');
		
    }

    public function index(Request $request)
    {
		if(ContentService::checkRole($this->routeDefault,'index') == 0){
			$this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
			return $this->responseView($this->viewPart . '.404');
		}
		
		$params = $request->all();
		
		$rows = ContentService::getHistoryBuyebook($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $user = User::get();
        $author = CmsAuthor::get();
		$this->responseData['rows'] = $rows;
		$this->responseData['user'] = $user;
		$this->responseData['author'] = $author;
		$this->responseData['params'] = $params;
		$this->responseData['array_payment_method'] = $this->array_payment_method;
        $this->responseData['array_status'] = $this->array_status;
		
		return $this->responseView($this->viewPart . '.index');
    }
	
	
    public function updateStatus(Request $request)
    {
		
        $id = $request->input('id');
        $action = $request->input('action');
        $row = CmsHistoryBuyebook::find($id);
        $user = User::find($row->customer_id);
		
        if(!$user){
            return response()->json(['success' => false, 'message' => 'Không tìm thấy người dùng.']);
        }
		
        if ($row) {
			
			if ($action == 'approve') {
                $row->status = 1; // Chấp nhận
				$row->buy_date = date('Y-m-d H:i:s');
				
				$documentId = $row->document_id;
				
				$document = Document::find($documentId);
				
				//dd($documentId);
				
				$message = 'Đơn hàng đã được duyệt, Khách hàng có thể đọc sách.';
				
				$document->update([
					'download' => $document->download + 1
				]);
				
				// Gửi email cho khách hàng
				$email = $user->email;
				Mail::send('frontend.emails.ebook', ['member_name' => $user->name,'document_name'=>$document->title], function ($message) use ($email,$user,$document) {
					$message->to($email);
					$message->subject($user->name.' đã mua sách '.$document->title);
				});
                $message = 'Đơn hàng đã được duyệt, Khách hàng có thể đọc sách.';
				
            } elseif ($action == 'cancel') {
                $row->status = 2;
                $message = 'Đơn hàng đã bị hủy.';
            }
            $row->save();
			
            return response()->json(['success' => true, 'message' => $message]);
        } else {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy dữ liệu.']);
        }
    }

	
}