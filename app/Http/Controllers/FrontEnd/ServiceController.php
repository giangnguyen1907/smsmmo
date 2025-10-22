<?php

namespace App\Http\Controllers\FrontEnd;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RentHistory;
use App\Models\Voucher;
use App\Models\CmsHistoryRechargeuser;
use App\Models\Service;
use App\Jobs\FetchBossOtpServices;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class ServiceController extends Controller
{

    protected $apitoken;
    protected $web_information;
    protected $translates;

    public function rentSim(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('frontend.login'); // Thay 'login' bằng route tên của bạn nếu khác
        }
        $apiKey = $this->apitoken ?? env('BOSSOTP_API_KEY');
        $user = Auth::user();
        // // dd($this->apitoken);
        // $url = 'https://bossotp.net/api/v5/service-manager/services/me';
        // $apiToken = 'sk_D3eTBZ5GH7VwvcwNQb6NIQmFTGJNj9kR';

        // $response = Http::withHeaders([
        //     'accept' => '*/*',
        // ])->get($url, [
        //     'api_token' => $apiToken,
        // ]);
        // // dd($response->json());
        // if ($response->successful()) {
        //     // Trả về dữ liệu JSON
        //     $danhSachDichVu = $response->json();
        //     // dd($danhSachDichVu);
        //     // return $response->json();
        // } else {
        //     // Xử lý lỗi
        //     // return response()->json([
        //     //     'error' => 'Không thể lấy dữ liệu từ API',
        //     //     'status' => $response->status(),
        //     //     'message' => $response->body()
        //     // ], $response->status());
        // }
          
        // FetchBossOtpServices::dispatch();
        // $sims = collect([
        //     (object) ['id'=>1,'network'=>'Viettel','service'=>'Facebook','number'=>'0987123456','price'=>3000,'status'=>'available'],
        //     (object) ['id'=>2,'network'=>'Mobifone','service'=>'Zalo','number'=>'0905123456','price'=>3500,'status'=>'rented'],
        //     (object) ['id'=>3,'network'=>'Vinaphone','service'=>'Telegram','number'=>'0912345678','price'=>2500,'status'=>'available'],
        //     (object) ['id'=>4,'network'=>'Vietnamobile','service'=>'Shopee','number'=>'0923456789','price'=>2800,'status'=>'available'],
        //     (object) ['id'=>5,'network'=>'Viettel','service'=>'Tiktok','number'=>'0987234567','price'=>3200,'status'=>'available'],
        // ]);

        // Nhận giá trị lọc
        $keyword = trim($request->get('keyword', ''));
        $network = $request->get('network', '');
        $service_id = $request->get('service_id', '');
        $prefix = $request->get('prefix', '');


        // Danh sách sim đã thuê
        $sims = RentHistory::where('user_id',$user->id)->orderBy('id','DESC')->get();
        // Lọc dữ liệu
        $filtered = $sims->filter(function ($sim) use ($keyword, $network, $service_id, $prefix) {
            $match = true;
            if ($keyword) {
                $match = $match && (
                    stripos($sim->network, $keyword) !== false ||
                    stripos($sim->service_id, $keyword) !== false ||
                    stripos($sim->sim_number, $keyword) !== false
                );
            }
            if ($network) $match = $match && ($sim->network == $network);
            if ($service_id) $match = $match && ($sim->service_id == $service_id);
            if ($prefix) $match = $match && (strpos($sim->sim_number, $prefix) === 0);
            return $match;
        });

        // Phân trang
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $filtered->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginatedSims = new LengthAwarePaginator(
            $currentItems,
            $filtered->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        $services = Service::where('status', 1)
                   ->orderBy('id', 'ASC')
                   ->get()->keyby('id');

        // Lấy danh sách sim đang chờ thuê
        $dsDaThue = $sims->where('status','PENDING');

        foreach($dsDaThue as $daThue){
            $response = Http::get('https://bossotp.net/api/v4/rents/check', [
                'api_token' => $apiKey,
                '_id' => $daThue->rent_id,
            ]);

            if ($response->successful()) {
                $dataCheck = $response->json();
                $trangthai = $dataCheck['status'];
                $otp_code = $dataCheck['otp'] ?? '';

                $daThue->status = $trangthai;
                $daThue->otp_code = $otp_code;

                $daThue->save();

                $price = $daThue->price;
                if($trangthai == 'FAILED'){
                    // Hoàn tiền cho khách hàng
                    $user->wallet = $user->wallet + $price;
                    $user->save();
                }
            }
        }

        //dd($dsDathue);
        // Trả về view
        return view('frontend.services.rent-sim', [
            'sims' => $paginatedSims,
            'services' => $services,
            'web_information' => $this->web_information,
            'translates' => $this->translates,
        ]);

        // $this->responseData['sims'] = $sims;
        // $this->responseData['services'] = $services;
        // return $this->responseView('frontend.services.rent-sim');

    }

    public function rentSimCreate(Request $request)
    {   
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thuê sim.'
            ]);
        } 
        $user = Auth::user();
        $serviceId = $request->input('service_id');
        $network = $request->input('network') ?? ''; // nếu có chọn nhà mạng
        $prefixs = $request->input('prefixs') ?? [];
        $price = $request->input('price') ?? '';
        $apiKey = $this->apitoken ?? env('BOSSOTP_API_KEY');
        try {

            $listService = Service::where('status',1)->get()->keyby('id');
            
            if(isset($listService[$serviceId])){
                // Kiểm tra giá hệ thống với giá clien
                $detailService = $listService[$serviceId];
                $price_system = $detailService->price_per_unit;
                // dd($price_system.'__'.$price);
                if($price_system == $price){
                    // Kiểm tra số dư
                    if($user->wallet >= $price){

                        // Gọi API tới trung gian thuê sim
                        $prefixs_txt = implode('|',$prefixs);
                        // dd($apiKey.'_'.$detailService->service_id.'_'.$prefixs_txt.'_'.$network);

                        $response = Http::get('https://bossotp.net/api/v4/rents/create', [
                            'api_token' => $apiKey,
                            'service_id' => $detailService->service_id,
                            'prefixs' => $prefixs_txt,
                            'network' => $network,
                        ]);

                        if ($response->successful()) {
                            $notice = 'Mua sim thành công';
                            $data = $response->json();
                            // dd($data);
                            // Lưu lịch sử thuê sim
                            $history = RentHistory::create([
                                'user_id'   => $user->id,
                                'service_id'   => $serviceId,
                                'sim_number'    => $data['number'] ?? null,
                                'rent_id'  => $data['rent_id'] ?? null,
                                'status'    => 'PENDING',
                                'price'     => $price,
                            ]);

                            // Trừ tiền trong ví của khách hàng
                            $user->wallet = $user->wallet - $price;
                            $user->save();

                            // $this->checkBalance();

                        }else{
                            $notice = 'Không thể thuê sim, vui lòng thử lại sau.';
                        }
                        
                    }else{
                        $notice = "Tài khoản không đủ";
                    }
                }else{
                    $notice = "Giá dịch vụ không trùng khớp";
                }
            }else{
                $notice = "Không tồn tại dịch vụ";
            }
            return redirect()->back()->with('successMessage', $notice);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage(),
            ]);
        }
    }

    public function rentSimOldCreate(Request $request)
    {   
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thuê sim.'
            ]);
        } 
        $user = Auth::user();
        $serviceId = $request->input('service_id');
        $rent_id = $request->input('rent_id') ?? ''; // nếu có chọn nhà mạng
        $price = $request->input('price') ?? '';
        $apiKey = $this->apitoken ?? env('BOSSOTP_API_KEY');
        try {

            $listService = Service::where('status',1)->get()->keyby('id');
            
            if(isset($listService[$serviceId])){
                // Kiểm tra giá hệ thống với giá clien
                $detailService = $listService[$serviceId];
                $price_system = $detailService->price_per_unit;
                // dd($price_system.'__'.$price);
                if($price_system == $price){
                    // Kiểm tra số dư
                    if($user->wallet >= $price){

                        // Kiểm tra trạng thái thuê
                        if($rent_id !=""){
                            // Gọi API tới trung gian thuê sim
                            $response = Http::get('https://bossotp.net/api/v4/rents/check', [
                                'api_token' => $apiKey,
                                'rent_id' => $rent_id,
                            ]);
                            if ($response->successful()) {

                                $checkOrder = $response->json();

                                $notice = "Giao dịch thành công";
                                // Gọi API tới trung gian thuê sim
                                $response = Http::get('https://bossotp.net/api/v4/rents/create', [
                                    'api_token' => $apiKey,
                                    'service_id' => $detailService->service_id,
                                    're_number' => $checkOrder['number'] ?? '',
                                ]);
                                if ($response->successful()) {
                                    $data = $response->json();
                                    
                                    // Lưu lịch sử thuê sim
                                    $history = RentHistory::create([
                                        'user_id'   => $user->id,
                                        'service'   => $serviceId,
                                        'sim_number'    => $data['number'] ?? null,
                                        'rent_id'  => $data['rent_id'] ?? null,
                                        'status'    => 'success',
                                        'price'     => $price,
                                    ]);

                                    // Trừ tiền trong ví của khách hàng
                                    $user->wallet = $user->wallet - $price;
                                    $user->save();
                                    $this->checkBalance();
                                }else{
                                    $notice = 'Không thể thuê sim, vui lòng thử lại sau.';
                                }
                                
                            }else{
                                $notice = 'Tạo yêu cầu thất bại';
                            }
                        }

                    }else{
                        $notice = "Tài khoản không đủ";
                    }
                }else{
                    $notice = "Giá dịch vụ không trùng khớp";
                }
            }else{
                $notice = "Không tồn tại dịch vụ";
            }

            return redirect()->back()->with('successMessage', $notice);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage(),
            ]);
        }
    }

    public function rentOldNumber(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('frontend.login'); // Thay 'login' bằng route tên của bạn nếu khác
        }
        $user = Auth::user();
        $items = RentHistory::where('user_id',$user->id)->get();

        // Lọc (nếu cần) dựa trên request params
        $keyword = trim($request->get('keyword', ''));
        $network  = $request->get('network', '');
        $service_id  = $request->get('service_id', '');
        $prefix   = $request->get('prefix', '');

        $filtered = $items->filter(function($it) use ($keyword, $network, $service_id, $prefix) {
            if ($keyword) {
                $matchKeyword = stripos($it->sim_number, $keyword) !== false
                    || stripos($it->service_id, $keyword) !== false
                    || stripos($it->network, $keyword) !== false;
                if (! $matchKeyword) return false;
            }
            if ($network && $it->network !== $network) return false;
            if ($service_id && $it->service_id !== $service_id) return false;
            if ($prefix && strpos($it->sim_number, $prefix) !== 0) return false;
            return true;
        });

        // paginator thủ công
        $perPage = 10;
        $page = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $filtered->slice(($page - 1) * $perPage, $perPage)->values();
        $paginated = new LengthAwarePaginator(
            $currentItems,
            $filtered->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        $services = Service::where('status', 1)
                   ->orderBy('id', 'ASC')
                   ->get()->keyby('id');

        return view('frontend.services.rent-old-number', [
            'oldNumbers' => $paginated,
            'items' => $items,
            'services'   => $services,
            'web_information' => $this->web_information,
            'translates' => $this->translates
        ]);
    }

    public function rentHistory(Request $request)
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('frontend.login'); // Thay 'login' bằng route tên của bạn nếu khác
        }

        $user = Auth::user();
        $keyword = $request->get('keyword');
        $network = $request->get('network');
        $service_id = $request->get('service');
        $status = $request->get('status');
        $statusValue = null;
        if ($status === 'success') {
            $statusValue = 0;
        } elseif ($status === 'fail' || $status === 'failed' || $status === 'error') {
            $statusValue = 1;
        }
        $histories = RentHistory::query()
            ->where('user_id', $user->id)
            ->when($keyword, function ($q) use ($keyword) {
                $q->where('sim_number', 'LIKE', "%$keyword%")
                ->orWhere('service_id', 'LIKE', "%$keyword%");
            })
            ->when(!empty($network), function ($q) use ($network) {
                 $q->where('network', $network);
            })
            ->when(!empty($service_id), function ($q) use ($service_id) {
                $q->where('service_id', $service_id);
            })
            ->when($statusValue !== null, fn($q) => $q->where('status', $statusValue))
            ->orderByDesc('id')
            ->paginate(10);
          $services = Service::where('status', 1)
                   ->orderBy('id', 'ASC')
                   ->get()->keyby('id');
        $web_information = $this->web_information;
        $translates = $this->translates;
        return view('frontend.services.rent-history', compact('histories','services','web_information','translates'));
    }

    /**
     * Trang nạp tiền vào sim
     */
    public function rechargeSim(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('frontend.login'); // Thay 'login' bằng route tên của bạn nếu khác
        }
        // $this->checkBalance();
        $user = Auth::user();
        
        // Xử lý form nạp tiền
        if ($request->isMethod('post')) {
            /*
            $amount = (int) $request->input('amount');
            $method = $request->input('method');

            if ($amount < 10000) {
                return back()->with('errorMessage', 'Số tiền tối thiểu để nạp là 10.000 VNĐ.');
            }

            $user->balance += $amount;
            $user->save();

            // Lưu lại lịch sử nạp (giả lập)
            // Trong thực tế, bạn lưu vào DB
            session()->push('recharge_history', [
                'amount' => $amount,
                'method' => $method,
                'created_at' => now()->format('Y-m-d H:i:s'),
            ]);
            */
            // return back()->with('successMessage', "Nạp thành công " . number_format($amount) . " VNĐ qua {$method}!");
        }
        $web_information = $this->web_information;
        $translates = $this->translates;
        $sims = RentHistory::where('user_id',$user->id)->get();
        return view('frontend.services.recharge-sim', [
            'sims' => $sims,
            'web_information' => $web_information,
            'translates' => $translates,
        ]);

        // return view('frontend.services.recharge-sim', compact('sims', 'recharges'));
    }

    public function create101(Request $request)
    {   
         if (!Auth::check()) {
            return redirect()->route('frontend.login'); // Thay 'login' bằng route tên của bạn nếu khác
        }

        // Giả lập data
        $images = collect([
            (object) ['id'=>1, 'title'=>'Ảnh 101# 1', 'status'=>'active', 'created_at'=>'2025-10-16'],
            (object) ['id'=>2, 'title'=>'Ảnh 101# 2', 'status'=>'inactive', 'created_at'=>'2025-10-16'],
            // thêm dữ liệu nếu cần
        ]);

        // Search/filter
        $keyword = $request->input('keyword');
        if ($keyword) {
            $images = $images->filter(function($img) use($keyword) {
                return str_contains(strtolower($img->title), strtolower($keyword));
            });
        }

        // Pagination thủ công
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $images->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedImages = new LengthAwarePaginator(
            $currentItems,
            $images->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        $web_information = $this->web_information;
        $translates = $this->translates;
        return view('frontend.services.create-101', [
            'images' => $paginatedImages,
            'keyword' => $keyword,
            'web_information' => $web_information,
            'translates' => $translates,
        ]);
    }

    // Trang ảnh gửi tin nhắn
    public function sendMessageImg(Request $request)
    {   
         if (!Auth::check()) {
            return redirect()->route('frontend.login'); // Thay 'login' bằng route tên của bạn nếu khác
        }
        $messages = collect([
            (object) ['id'=>1, 'number'=>'0987123456', 'content'=>'Test message', 'status'=>'sent', 'created_at'=>'2025-10-16'],
            (object) ['id'=>2, 'number'=>'0905123456', 'content'=>'Hello world', 'status'=>'pending', 'created_at'=>'2025-10-16'],
        ]);

        // Search/filter
        $keyword = $request->input('keyword');
        if ($keyword) {
            $messages = $messages->filter(function($msg) use($keyword) {
                return str_contains(strtolower($msg->number), strtolower($keyword))
                    || str_contains(strtolower($msg->content), strtolower($keyword));
            });
        }

        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $messages->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedMessages = new LengthAwarePaginator(
            $currentItems,
            $messages->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        $web_information = $this->web_information;
        $translates = $this->translates;
        return view('frontend.services.send-message-img', [
            'messages' => $paginatedMessages,
            'keyword' => $keyword,
            'web_information' => $web_information,
            'translates' => $translates,
        ]);
    }

    public function historyRecharge(Request $request){
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('frontend.login'); // Thay 'login' bằng route tên của bạn nếu khác
        }

        $user = Auth::user();
        $keyword = $request->get('keyword');

        // $histories = RentHistory::query()
        //     ->where('user_id', $user->id)
        //     ->when($keyword, function ($q) use ($keyword) {
        //         $q->where('sim_number', 'LIKE', "%$keyword%")
        //         ->orWhere('service_id', 'LIKE', "%$keyword%");
        //     })
        //     ->orderByDesc('id')
        //     ->paginate(10);
        
        $histories = CmsHistoryRechargeuser::where('customer_id',$user->id)->orderBy('id','DESC')->paginate(10);
        $web_information = $this->web_information;
        $translates = $this->translates;
        return view('frontend.services.history-recharge', compact('histories','web_information','translates'));
    }

    private function checkBalance(){
        // Gọi API tới trung gian thuê sim
        $apiKey = $this->apitoken ?? env('BOSSOTP_API_KEY');
        $response = Http::get('https://bossotp.net/api/v4/users/me/balance', [
            'api_token' => $apiKey,
        ]);
        if ($response->successful()) {
            $data = $response->json();
            
            $balance = $data['balance'];
            // return $balance;
            // // Số tiền nhỏ hơn để gửi thông báo đến admin

            $web_information = $this->web_information;

            $nguongcanhbao = $web_information->information->price ?? 500000;

            if($balance < $nguongcanhbao){
                // $customer_name = Auth::user()->name;
                // $document_name = "Nạp tiền hệ thống";
                $txt_email = $this->web_information->information->email ?? '';
                if($txt_email !=""){
                    $array_email = explode(',',$txt_email);
                    Mail::send('frontend.emails.balance', ['balance' => $balance], function ($message) use ($array_email) {
                        foreach($array_email as $email){
                            $message->to($email);
                        }
                        $message->subject('Nạp tiền vào hệ thống');
                    });
                }
                
            }

        }
    }

    public function rechargeAccount(Request $request)
    {   
        if (!Auth::check()) {
            return redirect()->route('frontend.login'); // Thay 'login' bằng route tên của bạn nếu khác
        }
        if ($request->isMethod('post')) {
            $user = Auth::user();
            
            $params = $request->all();
            $submit = $params['submit'];
            $amount_payment = $params['amount_payment'];
            $trans_code_transfer = $params['trans_code_transfer'];
            $customer_note = 'Nạp tiền tài khoản ' . Auth::user()->id;
            $recharge_info = time().rand(100,999);

            if ($amount_payment < 10000) {
                return back()->with('errorMessage', 'Số tiền tối thiểu để nạp là 10.000 VNĐ.');
            }
            
            $historyRecharge = CmsHistoryRechargeuser::create([
                'recharge_info' => $trans_code_transfer,
                'customer_id' => Auth::user()->id,
                'payment' => $amount_payment,
                'status' => 3, // trạng thái chờ duyệt
                'payment_method' => 2, // Chuyển khoản
            ]);


            $customer_name = $user->name;
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
            

            return redirect()->back()->with('successMessage', 'Cảm ơn bạn đã nạp tiền, vui lòng chờ 1-10 phút. Hệ thống đang xử lý yêu cầu của bạn.');
            // return back()->with('successMessage', "Nạp thành công " . number_format($amount) . " VNĐ!");
        }
        $dateTime = now();
        $listVoucher = Voucher::where('status',1)->where('start_date', '<=', $dateTime)->where('stop_date', '>=', $dateTime)->get();


        //dd($listVoucher);
        // $this->responseData['web_information'] = $this->web_information;

        return view('frontend.services.recharge-account',['web_information'=>$this->web_information,'translates' => $this->translates,'listVoucher'=>$listVoucher]);
    }

}
