<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Services\ContentService;
use App\Consts;

class TransactionController extends Controller
{
	public function __construct()
    {
        $this->routeDefault  = 'transaction';
        $this->viewPart = 'admin.pages.transaction';
		$this->locale = 'vi';
        $this->responseData['module_name'] = __('Lịch sử giao dịch');
		
		$this->responseData['array_istype'] = $this->array_istype = array(0=>'COD',1=>'Chuyển khoản',2=>'VNPAY',3=>'Mua Ebook',4=>'Nạp tiền tài khoản');
        $this->responseData['array_status'] = $this->array_status = array(0=>'Thất bại',1=>'Thành công','2'=>'Bị hủy',3=>'Chưa thu phát sinh');
		
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
		
		$params = $request->all();
		
		$rows = ContentService::getTransaction($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
		$this->responseData['rows'] = $rows;
		$this->responseData['params'] = $params;
		$this->responseData['array_istype'] = $this->array_istype;
		
		return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->responseView($this->viewPart . '.404');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->responseView($this->viewPart . '.404');
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
