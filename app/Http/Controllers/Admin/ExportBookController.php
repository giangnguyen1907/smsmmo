<?php

namespace App\Http\Controllers\Admin;

use App\Models\ExportBook;
use App\Models\ExportBookDetail;
use Illuminate\Http\Request;
use App\Http\Services\ContentService;
use App\Consts;
use App\Exports\ExportBookExport;
use Illuminate\Support\Facades\DB;
use App\Models\Document;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

class ExportBookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->routeDefault  = 'exportbook';
        $this->viewPart = 'admin.pages.exportbook';
        $this->locale = 'vi';
        $this->responseData['module_name'] = __('Xuất kho');
        
        $this->responseData['listBill'] = ContentService::getListBill(array('is_type'=>1))->get();
        $this->responseData['managerShop'] = ContentService::getManageShop(array())->get();
        $this->responseData['listUsers'] = ContentService::getUsers(array())->get();
        
        //session_start();

    }


    public function index(Request $request)
    {
        if(ContentService::checkRole($this->routeDefault,'index') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        
        //$request->session()->forget('exportbook');

        $params = $request->all();
        //dd($params);
        $rows = ContentService::getExportBook($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
        $this->responseData['params'] = $params;
        
        return $this->responseView($this->viewPart . '.index');
        
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
        
        $this->responseData['module_name'] = __('Thêm mới phiếu xuất');
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
        
        DB::beginTransaction();
        
        try{
            
            $params = $request->all();
            
            $quantitys = $params['quantity'];
            
            $costs = $params['cost'];
            $discount = $params['discount'] ?? 0;
            $payment = $params['payment'] ?? 0;
            $totalbill = $params['totalbill'] ?? 0;
            $olddebt = $params['olddebt'] ?? 0;
            $debt = ($params['totalbill'] + $params['olddebt'] - $discount) - $params['payment'];

            //dd($params['quantity']);
            $xuatkho = new ExportBook();
            // Thêm vào bảng phiếu nhập kho
            $xuatkho -> code        = $params['code'];
            $xuatkho -> bill_id     = $params['bill_id'];
            $xuatkho -> bookshop    = $params['bookshop'];
            $xuatkho -> customer_id = $params['customer_id'];
            $xuatkho -> date_at     = $params['date_at'] ?? date('Y-m-d');
            $xuatkho -> payment     = $payment;
            $xuatkho -> totalbill   = $totalbill;
            $xuatkho -> discount    = $discount;
            $xuatkho -> olddebt     = $olddebt;
            $xuatkho -> debt        = $debt;
            $xuatkho -> note        = $params['note'];
            $xuatkho -> status      = 1;
            $xuatkho -> save();
            
            $export_id = $xuatkho->id;
            
            // Thêm vào bảng chi tiết phiếu nhập
            $totalbill = 0;
            foreach($quantitys as $book_id => $sluong){
                $chitiet = [];
                
                $cost = $costs[$book_id] ?? 0;
                
                $chitiet['export_id'] = $export_id;
                $chitiet['document_id'] = $book_id;
                $chitiet['quantity'] = $sluong;
                $chitiet['cost'] = $cost;
                $chitiet['total'] = $cost*$sluong;

                $totalbill = $totalbill + $cost*$sluong;

                ExportBookDetail::create($chitiet);
                
                // Cập nhật số lượng vào thông tin sách

                $documentlist = Document::find($book_id);

                $daban = $documentlist->export;
                $tonkho = $documentlist->inventory;

                $documentlist -> export = $daban + $sluong;
                $documentlist -> inventory = $tonkho - $sluong;

                $documentlist -> save();

            }
            
            $xuatkho -> totalbill   = $totalbill;
            $xuatkho -> save();
            
            // Cập nhật công nợ khách hàng
            $customer = User::find($params['customer_id']);

            if($customer){
                //$customer -> olddebt = $customer->debt;
                $customer -> debt = $debt;
                $customer -> save();
            }

            session(['exportbook' => array()]);

            DB::commit();
            
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExportBook  $exportBook
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(ContentService::checkRole($this->routeDefault,'index') == 0){
			$this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
			return $this->responseView($this->viewPart . '.404');
		}

		$rows = ExportBook::withDetails()->find($id);
        
        $this->responseData['rows'] = $rows;
		$this->responseData['module_name'] = __('Quản lý phiếu xuất kho');
		return $this->responseView($this->viewPart . '.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExportBook  $exportBook
     * @return \Illuminate\Http\Response
     */
    public function edit(ExportBook $exportBook)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExportBook  $exportBook
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExportBook $exportBook)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExportBook  $exportBook
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExportBook $exportBook)
    {
        //
    }


    public function saveAjax(Request $request)
    {
        $params = $request->all();
        
        $id = $params['id'];
        $cost = $params['cost'];
        $sluong = $params['quantity'];
        $name = $params['name'];
        
        $array_import = session('exportbook') ?? array();

        $array_import[$id]['name'] = $name;
        $array_import[$id]['cost'] = $cost;
        $array_import[$id]['sluong'] = $sluong;

        session(['exportbook' => $array_import]);

        //$value = session('exportbook');
        //dd($value);
        return true;
    }

    public function saveDelete(Request $request)
    {
        $params = $request->all();
        
        $id = $params['id'];
        
        $array_import = session('exportbook') ?? array();

        unset($array_import[$id]);

        session(['exportbook' => $array_import]);

        //$value = session('exportbook');
        //dd($value);
        return true;
    }

    public function saveUpdate(Request $request)
    {
        $params = $request->all();
        
        $id = $params['id'];
        $sl = $params['quantity'];
        
        $array_import = session('exportbook') ?? array();

        $array_import[$id]['sluong'] = $sl;

        //unset($array_import[$id]);

        session(['exportbook' => $array_import]);

        //$value = session('exportbook');
        //dd($value);
        return true;
    }


    public function loadBebt(Request $request)
    {
        $params = $request->all();
        
        $id = $params['id'];

        $customer = User::find($id);

        if($customer){
            return $customer->debt;
        }else{
            return 0;
        }
    }

    public function export(Request $request) 
    {
		$previousUrl = url()->previous();
		//tách scheme của url
		$parseUrl = parse_url($previousUrl);

		//chuyển chuỗi trong query thành 1 mảng
		$queryParams = [];
		if (isset($parseUrl['query'])) {
			parse_str($parseUrl['query'], $queryParams);
		}

		$params['customer_id'] = $queryParams['customer_id'] ?? null;
		$params['from_date'] = $queryParams['from_date'] ?? null;
		$params['to_date'] = $queryParams['to_date'] ?? null;
		$params['keyword'] = $queryParams['keyword'] ?? null;

		$exportBook = ContentService::getExportBook($params)->get();

		return Excel::download(new ExportBookExport($exportBook), 'phieuxuatkho.xlsx');
	}
}
