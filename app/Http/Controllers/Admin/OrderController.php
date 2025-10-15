<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Exports\OrderExport;
use App\Http\Services\ContentService;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ImportBook;
use App\Models\ImportBookDetail;
use App\Models\ExportBook;
use App\Models\ExportBookDetail;
use App\Models\Document;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'orders';
        $this->viewPart = 'admin.pages.orders';
        $this->responseData['module_name'] = __('Order Management');
		$this->responseData['array_payment_method'] = array(0=>'COD',1=>'Ví',2=>'Chuyển khoản',3=>'VNPAY',4=>'Viettel money');
		$this->responseData['array_payment_staus'] = array(0=>'Chưa thanh toán',1=>'Đã thanh toán');
        //$this->responseData['array_status'] = array(0=>'Chưa thanh toán',1=>'Đã thanh toán');
	}

	public function index(Request $request)
    {
		if(ContentService::checkRole($this->routeDefault,'index') == 0){
			$this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
			return $this->responseView($this->viewPart . '.404');
		}
		
		$params = $request->all();
		
		$rows = ContentService::getOrders($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
		$this->responseData['rows'] = $rows;
		// dd($rows );
		return $this->responseView($this->viewPart . '.index');
    }
	
	public function show(Order $order)
    {
		if(ContentService::checkRole($this->routeDefault,'index') == 0){
			$this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
			return $this->responseView($this->viewPart . '.404');
		}
		$this->responseData['module_name'] = __('Order Management');
		$rows = ContentService::getOrderDetail(['order_id'=> $order->id])->get();
        $this->responseData['rows'] = $rows;
		$this->responseData['detail'] = $order;
		return $this->responseView($this->viewPart . '.show');
	}
	
    public function edit(Order $order)
    {
		$this->responseData['module_name'] = __('Order Product Management');
        $this->responseData['detail'] = $order;

        $rows = ContentService::getOrderDetail(['order_id'=> $order->id])->get();

        if($order->status == 'pending'){
            $array_status = ['pending' => 'Chờ duyệt', 'delivery' => 'Đang giao', 'complete' => 'Hoàn thành', 'reject' => 'Hủy đơn'];
        }else{
            $array_status = ['delivery' => 'Đang giao', 'complete' => 'Hoàn thành', 'reject' => 'Hủy đơn'];
        }

        $this->responseData['array_status'] = $array_status;
        $this->responseData['rows'] = $rows;
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|max:255'
        ]);

        $status_old = $order->status;
        $status_new = $request->status;
        $payment_status_old = $order->payment_status;
        //dd($payment_status_old);
        /* Chặn trường hợp đang giao rồi chọn lại trạng thái chờ duyệt, chỉ có thể hủy hoặc tiếp tục giao */
        if($status_old == 'pending'){
            $array_status = ['pending' => 'Chờ duyệt', 'delivery' => 'Đang giao', 'complete' => 'Hoàn thành', 'reject' => 'Hủy đơn'];
        }else{
            $array_status = ['delivery' => 'Đang giao', 'complete' => 'Hoàn thành', 'reject' => 'Hủy đơn'];
        }

        $customer_id = $order->customer_id > 0 ? $order->customer_id : 1;

        if(isset($array_status[$status_new])){

            // DB::beginTransaction();
        
            // try{

                $params = $request->only([
                    'status', 'admin_note','payment_status'
                ]);

                $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

                $order->fill($params);
                $order->save();
                
                $order_info = $order->order_info;

                $payment_status = $order->payment_status;

                if($payment_status == 1){ // Đã thanh toán
                    $debt = 0;
                    $payment = $order->payment;
                }else{
                    $debt = $order->payment;
                    $payment = 0;
                }

                $totalbill = $order->total_payment + $order->ship - $order->discount;

                if($status_old != $status_new){ // Nếu thay đổi trạng thái đơn hàng

                    // Đơn hàng đang giao và hoàn thành. Tạo phiếu xuất kho
                    if($status_old == "pending" and $status_new !="reject"){
                        
                        // Xuất kho lần đầu
                        $xuatkho = new ExportBook();
                        // Thêm vào bảng phiếu nhập kho
                        $xuatkho->code        = $order_info;
                        $xuatkho->bill_id     = null;
                        $xuatkho->bookshop    = null;
                        $xuatkho->customer_id = $customer_id;
                        $xuatkho->date_at     = $order->order_date;
                        $xuatkho->ship        = $order->ship;
                        $xuatkho->totalbill   = $order->total_payment;
                        $xuatkho->discount    = $order->discount;
                        $xuatkho->payment     = $payment;
                        $xuatkho->olddebt     = 0;
                        $xuatkho->debt        = $debt;
                        $xuatkho->note        = $order->customer_note;
                        $xuatkho->status      = 1;
                        $xuatkho->save();
                        
                        $export_id = $xuatkho->id;

                        // Chi tiết đơn hàng để chuyển sang xuất kho.
                        $listOrderDetail = OrderDetail::where('order_id','=',$order->id)->get();
                        $data = [];
                        foreach($listOrderDetail as $orderDetail){
                            $export_book ['export_id'] = $export_id;
                            $export_book ['document_id'] = $orderDetail->item_id;
                            $export_book ['quantity'] = $orderDetail->quantity;
                            $export_book ['cost'] = $orderDetail->price;
                            $export_book ['total'] = $orderDetail->quantity * $orderDetail->price;
                            $export_book ['created_at'] = date('Y-m-d H:i:s');
                            $export_book ['updated_at'] = date('Y-m-d H:i:s');

                            array_push($data, $export_book);

                            // Cập nhật số lượng vào kho
                            $documentlist = Document::where('id',$orderDetail->item_id)->first();
                            $sluong = $orderDetail->quantity;
                            if($documentlist){
                                $daban = $documentlist->export;
                                $tonkho = $documentlist->inventory;

                                $documentlist->export = $daban + $sluong;
                                $documentlist->inventory = $tonkho - $sluong;

                                $documentlist->save();

                            }
                            

                        }

                        ExportBookDetail::insert($data);

                    }
                    /* Trường hợp 2
                    - Đơn hàng đã duyệt sau đó bị hủy lần 1
                    - Đơn hàng bị hủy lần 2?
                    */
                    // Nếu đơn hàng đã tạo phiếu xuất, mà lại bị hủy trong quá trình giao, Phải tạo phiếu nhập vào kho
                    if(($status_old == "delivery" or $status_old == "complete") and $status_new =="reject" ){
                        
                        // Kiểm tra xem đã tạo phiếu hoàn hàng chưa

                        $checkImportBook = ImportBook::where('code',$order_info)->first();
                        if($checkImportBook){
                            // Hủy đơn hàng từ vòng thứ 2
                            $checkImportBook->status = 1;
                            $checkImportBook->save();

                            // Cập nhật lại số lượng sách trong hệ thống.
                            $listImportBookDetail = ImportBookDetail::where('import_id',$checkImportBook->id)->get();

                            foreach($listImportBookDetail as $importBookDetail){
                                $sluong = $importBookDetail->quantity;
                                $documentlist = Document::find($importBookDetail->document_id);
                                if($documentlist){
                                    $danhap = $documentlist->import;
                                    $tonkho = $documentlist->inventory;

                                    $documentlist->import = $danhap + $sluong;
                                    $documentlist->inventory = $tonkho + $sluong;
                                    $documentlist->save();
                                }
                            }

                        }else{

                            // Nhập vào kho lần đầu
                            // Thêm vào bảng phiếu nhập kho
                            $nhapkho = new ImportBook();
                            $nhapkho->code        = $order_info;
                            $nhapkho->bill_id     = null;
                            $nhapkho->bookshop    = null;
                            $nhapkho->customer_id = $customer_id;
                            $nhapkho->date_at     = date('Y-m-d', strtotime($order->order_date));
                            $nhapkho->ship        = $order->ship;
                            $nhapkho->totalbill   = $order->total_payment;
                            $nhapkho->discount    = $order->discount;
                            $nhapkho->payment     = $payment;
                            $nhapkho->olddebt     = 0;
                            $nhapkho->debt        = $debt;
                            $nhapkho->note        = $order->customer_note;
                            $nhapkho->status      = 1;
                            $nhapkho->save();
                            
                            $import_id = $nhapkho->id;

                            // Chi tiết đơn hàng để chuyển sang xuất kho.
                            $listOrderDetail = OrderDetail::where('order_id','=',$order->id)->get();
                            $data2 = [];
                            foreach($listOrderDetail as $orderDetail){

                                $import_book ['import_id'] = $import_id;
                                $import_book ['document_id'] = $orderDetail->item_id;
                                $import_book ['quantity'] = $orderDetail->quantity;
                                $import_book ['cost'] = $orderDetail->price;
                                $import_book ['total'] = $orderDetail->quantity * $orderDetail->price;
                                $import_book ['created_at'] = date('Y-m-d H:i:s');
                                $import_book ['updated_at'] = date('Y-m-d H:i:s');

                                array_push($data2, $import_book);
                                $sluong = $orderDetail->quantity;
                                // Cập nhật số lượng vào kho
                                $documentlist = Document::find($orderDetail->item_id);
                                if($documentlist){
                                    $danhap = $documentlist->import;
                                    $tonkho = $documentlist->inventory;

                                    $documentlist->import = $danhap + $sluong;
                                    $documentlist->inventory = $tonkho + $sluong;

                                    $documentlist->save();

                                }
                                
                            }

                            ImportBookDetail::insert($data2);
                        }
                    }

                    /* Trường hợp 3
                    - Chưa có phiếu xuất và phiếu nhập
                    - Có phiếu xuất rồi => hủy đơn, xong lại duyệt đơn => hủy đơn xong lại duyệt đơn
                    */
                    if($status_old =="reject" and ($status_new == "delivery" or $status_new == "complete")){
                        
                        // Kiểm tra đã có phiếu hoàn hàng chưa.
                        $checkImportBook = ImportBook::where('code',$order_info)->first();
                        if($checkImportBook){
                            // Đã xuất rồi hủy => giờ lại giao cho khách
                            $checkImportBook->status = 0;
                            $checkImportBook->save();

                            // Cập nhật lại số lượng sách trong hệ thống.
                            $listImportBookDetail = ImportBookDetail::where('import_id',$checkImportBook->id)->get();

                            foreach($listImportBookDetail as $importBookDetail){
                                $sluong = $importBookDetail->quantity;
                                $documentlist = Document::find($importBookDetail->document_id);
                                if($documentlist){
                                    $danhap = $documentlist->import;
                                    $tonkho = $documentlist->inventory;

                                    $documentlist->import = $danhap - $sluong;
                                    $documentlist->inventory = $tonkho - $sluong;
                                    $documentlist->save();
                                }
                            }

                        }else{
                            // Chưa có phiếu hoàn => mới xuất hàng lần đầu

                            $xuatkho = new ExportBook();
                            // Thêm vào bảng phiếu nhập kho
                            $xuatkho->code        = $order_info;
                            $xuatkho->bill_id     = null;
                            $xuatkho->bookshop    = null;
                            $xuatkho->customer_id = $customer_id;
                            $xuatkho->date_at     = $order->order_date;
                            $xuatkho->ship        = $order->ship;
                            $xuatkho->totalbill   = $order->total_payment;
                            $xuatkho->discount    = $order->discount;
                            $xuatkho->payment     = $payment;
                            $xuatkho->olddebt     = 0;
                            $xuatkho->debt        = $debt;
                            $xuatkho->note        = $order->customer_note;
                            $xuatkho->status      = 1;
                            $xuatkho->save();
                            
                            $export_id = $xuatkho->id;

                            // Chi tiết đơn hàng để chuyển sang xuất kho.
                            $listOrderDetail = OrderDetail::where('order_id','=',$order->id)->get();
                            $data = [];
                            foreach($listOrderDetail as $orderDetail){
                                $export_book ['export_id'] = $export_id;
                                $export_book ['document_id'] = $orderDetail->item_id;
                                $export_book ['quantity'] = $orderDetail->quantity;
                                $export_book ['cost'] = $orderDetail->price;
                                $export_book ['total'] = $orderDetail->quantity * $orderDetail->price;
                                $export_book ['created_at'] = date('Y-m-d H:i:s');
                                $export_book ['updated_at'] = date('Y-m-d H:i:s');

                                array_push($data, $export_book);

                                // Cập nhật số lượng vào kho
                                $documentlist = Document::find($orderDetail->item_id);
                                $sluong = $orderDetail->quantity;
                                if($documentlist){
                                    $daban = $documentlist->export;
                                    $tonkho = $documentlist->inventory;

                                    $documentlist->export = $daban + $sluong;
                                    $documentlist->inventory = $tonkho - $sluong;

                                    $documentlist->save();

                                }
                            }
                            ExportBookDetail::insert($data);
                        }
                    }
                } // END đổi trạng thái đơn hàng.
                //dd($payment_status_old."__".$payment_status);
                // Đổi trạng thái thanh toán.
                if($payment_status_old != $payment_status){

                    if($payment_status_old == 0 and $payment_status == 1){
                        // Nếu trạng thái chuyển từ chưa thanh toán => đã thanh toán: Kiểm tra đã có trong lịch sử giao dịch chưa
                        // Chưa có thì thêm mới, có rồi thì chuyển trạng thái thành công
                        $status_trans = 1;
                    }else{
                        // Trạng thái từ đã thanh toán => chưa thanh toán: Kiểm tra lịch sử giao dịch
                        // Chưa có thì thêm mới, có rồi thì chuyển trạng thái thất bại
                        $status_trans = 2;
                    }

                    $checkTransaction = Transaction::where('order_code',$order_info)->first();
                    //dd($order_info);
                    if($checkTransaction){
                        $checkTransaction->status = $status_trans;
                        $checkTransaction->save();
                    }else{

                        $transaction_params = [];
                        $transaction_params ['order_code'] = $order_info;
                        $transaction_params ['guest'] = $order->name;
                        $transaction_params ['guest_id'] = $order->customer_id;
                        $transaction_params ['transaction_no'] = $order->trans_code;
                        $transaction_params ['response_code'] = $order->response_code;
                        $transaction_params ['amount'] = $order->payment;
                        $transaction_params ['date_at'] = date('Y-m-d H:i:s');
                        $transaction_params ['is_type'] = $order->payment_method; // 0: Giao dịch tiền mặt, 1: CK, 2 VNpay
                        $transaction_params ['status'] = $status_trans ;
                        $transaction_params ['content'] = $order->customer_note;
                        $transaction_params ['created_at'] = date('Y-m-d H:i:s');
                        $transaction_params ['updated_at'] = date('Y-m-d H:i:s');

                        Transaction::create($transaction_params);

                    }
                }
                
                // Trạng thái đơn hàng đã giao và đã thanh toán => Cập nhật giao dịch hoàn thành
                if($order->status == 'complete' && $payment_status == 1){

                    $checkTransaction = Transaction::where('order_code',$order_info)->first();
                    if($checkTransaction){

                        $checkTransaction->status = 1;
                        $checkTransaction->save();
                        //dd($checkTransaction);
                    }
                }

                // DB::commit();
                
                return redirect()->back()->with('successMessage', __('Successfully updated!'));

            // } catch (\Throwable $e) {
            //     DB::rollback();
            //     throw $e;
            //     return redirect()->back()->with('errorMessage', __('Lỗi '.$e));
            // }
            
            
        }else{
            return redirect()->back()->with('errorMessage', __('Không xác định trạng thái đơn hàng'));
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->status = 'reject';
        $order->save();

        return redirect()->back()->with('successMessage', __('Hủy đơn hàng thành công!'));
    }

    public function exportExcel() {
        $previousUrl = url()->previous();
        $parsedUrl = parse_url($previousUrl);

        $queryParams = [];
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
        }

        $params['status'] = $queryParams['status'] ?? null;
        $params['keyword']  = $queryParams['keyword'] ?? null;

        $orders = ContentService::getOrders($params)->get();

        return Excel::download(new OrderExport($orders), 'order.xlsx');
    }
}
