<?php

namespace App\Http\Controllers\FrontEnd;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RentHistory;


class ServiceController extends Controller
{
  public function rentSim(Request $request)
    {
        $sims = collect([
            (object) ['id'=>1,'network'=>'Viettel','service'=>'Facebook','number'=>'0987123456','price'=>3000,'status'=>'available'],
            (object) ['id'=>2,'network'=>'Mobifone','service'=>'Zalo','number'=>'0905123456','price'=>3500,'status'=>'rented'],
            (object) ['id'=>3,'network'=>'Vinaphone','service'=>'Telegram','number'=>'0912345678','price'=>2500,'status'=>'available'],
            (object) ['id'=>4,'network'=>'Vietnamobile','service'=>'Shopee','number'=>'0923456789','price'=>2800,'status'=>'available'],
            (object) ['id'=>5,'network'=>'Viettel','service'=>'Tiktok','number'=>'0987234567','price'=>3200,'status'=>'available'],
        ]);

        // Nhận giá trị lọc
        $keyword = trim($request->get('keyword', ''));
        $network = $request->get('network', '');
        $service = $request->get('service', '');
        $prefix = $request->get('prefix', '');

        // Lọc dữ liệu
        $filtered = $sims->filter(function ($sim) use ($keyword, $network, $service, $prefix) {
            $match = true;
            if ($keyword) {
                $match = $match && (
                    stripos($sim->network, $keyword) !== false ||
                    stripos($sim->service, $keyword) !== false ||
                    stripos($sim->number, $keyword) !== false
                );
            }
            if ($network) $match = $match && ($sim->network == $network);
            if ($service) $match = $match && ($sim->service == $service);
            if ($prefix) $match = $match && (strpos($sim->number, $prefix) === 0);
            return $match;
        });

        // Phân trang
        $perPage = 5;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $filtered->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginatedSims = new LengthAwarePaginator(
            $currentItems,
            $filtered->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Trả về view
        return view('frontend.services.rent-sim', [
            'sims' => $paginatedSims,
            'keyword' => $keyword,
            'network' => $network,
            'service' => $service,
            'prefix' => $prefix,
        ]);
    }

    public function rentSimcreate(Request $request)
    {
        // Xử lý dữ liệu thuê sim
        // (ở đây demo trả JSON)
        return response()->json([
            'success' => true,
            'message' => 'Thuê sim thành công!',
            'data' => $request->all(),
        ]);
    }



    public function rentOldNumber(Request $request)
    {
          // dữ liệu demo
        $items = collect([
            (object)['id'=>1,'number'=>'0987123456','network'=>'Viettel','service'=>'Facebook','price'=>5000,'status'=>'available'],
            (object)['id'=>2,'number'=>'0905123456','network'=>'Mobifone','service'=>'Zalo','price'=>6000,'status'=>'rented'],
            (object)['id'=>3,'number'=>'0912345678','network'=>'Vinaphone','service'=>'Telegram','price'=>7000,'status'=>'available'],
            // ... thêm nếu cần
        ]);

        // Lọc (nếu cần) dựa trên request params
        $keyword = trim($request->get('keyword', ''));
        $network  = $request->get('network', '');
        $service  = $request->get('service', '');
        $prefix   = $request->get('prefix', '');

        $filtered = $items->filter(function($it) use ($keyword, $network, $service, $prefix) {
            if ($keyword) {
                $matchKeyword = stripos($it->number, $keyword) !== false
                    || stripos($it->service, $keyword) !== false
                    || stripos($it->network, $keyword) !== false;
                if (! $matchKeyword) return false;
            }
            if ($network && $it->network !== $network) return false;
            if ($service && $it->service !== $service) return false;
            if ($prefix && strpos($it->number, $prefix) !== 0) return false;
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

        // danh sách select options để view dùng
        $networks = ['Viettel','Mobifone','Vinaphone','Vietnamobile'];
        $services = ['Facebook','Zalo','Telegram','Shopee','Tiktok'];
        $prefixes = ['090','091','092','098','097'];

        return view('frontend.services.rent-old-number', [
            'oldNumbers' => $paginated,
            'networks'   => $networks,
            'services'   => $services,
            'prefixes'   => $prefixes,
            'keyword'    => $keyword,
            'network'    => $network,
            'service'    => $service,
            'prefix'     => $prefix,
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

        $histories = RentHistory::query()
            ->where('user_id', $user->id)
            ->when($keyword, function ($q) use ($keyword) {
                $q->where('number', 'LIKE', "%$keyword%")
                ->orWhere('service', 'LIKE', "%$keyword%");
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('frontend.services.rent-history', compact('histories'));
    }

    /**
     * Trang nạp tiền
     */
    public function rechargeSim(Request $request)
{
    $user = Auth::user();

    // Xử lý form nạp tiền
    if ($request->isMethod('post')) {
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

        return back()->with('successMessage', "Nạp thành công " . number_format($amount) . " VNĐ qua {$method}!");
    }

    // Giả lập danh sách sim của user
    $sims = collect([
        (object) ['id' => 1, 'number' => '0987123456', 'network' => 'Viettel', 'balance' => 12000, 'status' => 'active'],
        (object) ['id' => 2, 'number' => '0905123456', 'network' => 'Mobifone', 'balance' => 5000, 'status' => 'inactive'],
    ]);

    // Lọc theo keyword và trạng thái
    $keyword = $request->keyword;
    $status = $request->status;

    $filtered = $sims->filter(function ($sim) use ($keyword, $status) {
        $matchKeyword = !$keyword || str_contains($sim->number, $keyword) || str_contains($sim->network, $keyword);
        $matchStatus = !$status || $sim->status === $status;
        return $matchKeyword && $matchStatus;
    });

    // Phân trang thủ công cho sim
    $perPage = 10;
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $currentItems = $filtered->slice(($currentPage - 1) * $perPage, $perPage)->values();

    $activeSims = new LengthAwarePaginator(
        $currentItems,
        $filtered->count(),
        $perPage,
        $currentPage,
        ['path' => request()->url(), 'query' => request()->query()]
    );

    // Lấy lịch sử nạp tiền từ session (giả lập)
    $rechargeHistory = collect(session('recharge_history', []))->reverse(); // mới nhất lên trước
    $rechargeItems = $rechargeHistory->slice(($currentPage - 1) * $perPage, $perPage)->values();

    $recharges = new LengthAwarePaginator(
        $rechargeItems,
        $rechargeHistory->count(),
        $perPage,
        $currentPage,
        ['path' => request()->url(), 'query' => request()->query()]
    );

    return view('frontend.services.recharge-sim', compact('activeSims', 'recharges'));
}
 public function create101(Request $request)
    {
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

        return view('frontend.services.create-101', [
            'images' => $paginatedImages,
            'keyword' => $keyword
        ]);
    }

    // Trang ảnh gửi tin nhắn
    public function sendMessageImg(Request $request)
    {
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

        return view('frontend.services.send-message-img', [
            'messages' => $paginatedMessages,
            'keyword' => $keyword
        ]);
    }

    public function rechargeAccount(Request $request)
    {
        if ($request->isMethod('post')) {
            $user = Auth::user();
            $amount = (int) $request->input('amount');

            if ($amount < 10000) {
                return back()->with('errorMessage', 'Số tiền tối thiểu để nạp là 10.000 VNĐ.');
            }

            // Giả lập cộng tiền
            $user->balance += $amount;
            $user->save();

            return back()->with('successMessage', "Nạp thành công " . number_format($amount) . " VNĐ!");
        }

        return view('frontend.services.recharge-account');
    }

}
