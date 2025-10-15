<?php

namespace App\Http\Controllers\Admin;

use App\Models\ImportBook;
use App\Models\ImportBookDetail;
use Illuminate\Http\Request;
use App\Http\Services\ContentService;
use App\Consts;
use App\Exports\ImportBookExport;
use Illuminate\Support\Facades\DB;
use App\Models\Document;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

class ImportBookController extends Controller
{
	public function __construct()
    {
        $this->routeDefault  = 'importbook';
        $this->viewPart = 'admin.pages.importbook';
		$this->locale = 'vi';
        $this->responseData['module_name'] = __('Nhập kho');
		
		$this->responseData['listBill'] = ContentService::getListBill(array('is_type'=>0))->get();
		$this->responseData['managerShop'] = ContentService::getManageShop(array())->get();
		$this->responseData['listUsers'] = ContentService::getUsers(array())->get();
		
		//session_start();

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
		
		//$request->session()->forget('importbook');

		$params = $request->all();
		//dd($params);
		$rows = ContentService::getImportBook($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
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
		
		$this->responseData['module_name'] = __('Thêm mới phiếu nhập');
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
			$debt = ($totalbill + $olddebt - $discount) - $payment;

			//dd($params['quantity']);
			$nhapkho = new ImportBook();
			// Thêm vào bảng phiếu nhập kho
			$nhapkho -> code 		= $params['code'];
			$nhapkho -> bill_id	 	= $params['bill_id'];
			$nhapkho -> bookshop 	= $params['bookshop'];
			$nhapkho -> workshop 	= $params['workshop'];
			$nhapkho -> customer_id = $params['customer_id'];
			$nhapkho -> date_at 	= $params['date_at'] ?? date('Y-m-d');
			$nhapkho -> payment 	= $payment;
			$nhapkho -> totalbill 	= $totalbill;
			$nhapkho -> discount 	= $discount;
			$nhapkho -> olddebt 	= $olddebt;
			$nhapkho -> debt 		= $debt;
			$nhapkho -> note 		= $params['note'];
			$nhapkho -> status 		= 1;
			$nhapkho -> save();
			
			$import_id = $nhapkho->id;
			
			// Thêm vào bảng chi tiết phiếu nhập
			$totalbill = 0;
			foreach($quantitys as $book_id => $sluong){
				$chitiet = [];
				
				$cost = $costs[$book_id] ?? 0;
				
				$chitiet['import_id'] = $import_id;
				$chitiet['document_id'] = $book_id;
				$chitiet['quantity'] = $sluong;
				$chitiet['cost'] = $cost;
				$chitiet['total'] = $cost*$sluong;

				$totalbill = $totalbill + $cost*$sluong;

				ImportBookDetail::create($chitiet);
				
				// Cập nhật số lượng vào thông tin sách

				$documentlist = Document::find($book_id);

				$danhap = $documentlist->import;
				$tonkho = $documentlist->inventory;

				$documentlist -> import = $danhap + $sluong;
				$documentlist -> inventory = $tonkho + $sluong;

				$documentlist -> save();

			}
			
			$nhapkho -> totalbill 	= $totalbill;
			$nhapkho -> save();
			
			// Cập nhật công nợ khách hàng
			$customer = User::find($params['customer_id']);

			if($customer){
				//$customer -> olddebt = $customer->debt;
				$customer -> debt = $debt;
				$customer -> save();
			}

			session(['importbook' => array()]);

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
     * @param  \App\Models\ImportBook  $importBook
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(ContentService::checkRole($this->routeDefault,'index') == 0){
			$this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
			return $this->responseView($this->viewPart . '.404');
		}

		$rows = ImportBook::withDetails()->find($id);
		
        $this->responseData['rows'] = $rows;
		$this->responseData['module_name'] = __('Quản lý phiếu nhập kho');
		return $this->responseView($this->viewPart . '.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ImportBook  $importBook
     * @return \Illuminate\Http\Response
     */
    public function edit(ImportBook $importBook)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ImportBook  $importBook
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ImportBook $importBook)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ImportBook  $importBook
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImportBook $importBook)
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
		
		$array_import = session('importbook') ?? array();

		$array_import[$id]['name'] = $name;
		$array_import[$id]['cost'] = $cost;
		$array_import[$id]['sluong'] = $sluong;

		session(['importbook' => $array_import]);

		//$value = session('importbook');
		//dd($value);
		return true;
	}

	public function saveDelete(Request $request)
    {
		$params = $request->all();
		
		$id = $params['id'];
		
		$array_import = session('importbook') ?? array();

		unset($array_import[$id]);

		session(['importbook' => $array_import]);

		//$value = session('importbook');
		//dd($value);
		return true;
	}

	public function saveUpdate(Request $request)
    {
		$params = $request->all();
		
		$id = $params['id'];
		$sl = $params['quantity'];
		
		$array_import = session('importbook') ?? array();

		$array_import[$id]['sluong'] = $sl;

		//unset($array_import[$id]);

		session(['importbook' => $array_import]);

		//$value = session('importbook');
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

		$importBook = ContentService::getImportBook($params)->get();

		return Excel::download(new ImportBookExport($importBook), 'phieunhapkho.xlsx');
	}
}
