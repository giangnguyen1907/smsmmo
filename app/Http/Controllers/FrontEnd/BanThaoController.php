<?php

namespace App\Http\Controllers\FrontEnd;

use App\Consts;
use App\Models\Comment;
use App\Models\Banthao;
use App\Http\Controllers\FrontEnd\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use App\Models\District;
use App\Models\Province;
use App\Models\Ward;
use App\Models\Document;
use App\Models\Order;
use App\Models\CmsHistoryBuyebook;

class BanThaoController extends Controller
{
    public function storeBanthao(Request $request)
    {
        $params = $request->all();

        $tacpham = $params['tacpham'];
        $tacgia = $params['tacgia'];
        $butdanh = $params['butdanh'];
        $diachi = $params['diachi'];
        $dienthoai = $params['dienthoai'];
        $email = $params['email'];
        $theloai = $params['theloai'];
        $noidung = $params['noidung'];
        $sotrang = $params['sotrang'];
        $khuonkho = $params['khuonkho'];
        $dinhdang = $params['dinhdang'];
        $dungluong = $params['dungluong'];
        $lanxuatban = $params['lanxuatban'];
        $lantaiban = $params['lantaiban'];
        $soluong = $params['soluong'];
        $nhain = $params['nhain'];
        $diachinhain = $params['diachinhain'];
        $trangweb = $params['trangweb'];
        $user_created_id = Auth::guard('web')->user()->id;

        $email = Auth::guard('web')->user()->email;
        $array_email = explode('@',$email);
        $name_email = str_replace(['.',',','_','-'],'',$array_email[0]);
        $destinationPath = 'banthao/'.$name_email.'/';
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $fileNames = [];
        if($request->hasFile('list_file_goc')) {
            $file_danhsachfile = $request->file('list_file_goc');
            foreach ($file_danhsachfile as $file) {
                $name = $file->getClientOriginalName();
                $fileName = Controller::convertName($name);
                $file->move($destinationPath, $fileName);
                $fileNames[] = '/'.$destinationPath.$fileName;
            }
        }
        $fileNameString = implode(';', $fileNames);

        $banthao = new Banthao();
        $banthao->tacpham = $tacpham;
        $banthao->tacgia = $tacgia; 
        $banthao->butdanh = $butdanh;
        $banthao->diachi = $diachi; 
        $banthao->dienthoai = $dienthoai; 
        $banthao->email = $email; 
        $banthao->theloai = $theloai; 
        $banthao->noidung = $noidung; 
        $banthao->sotrang = $sotrang; 
        $banthao->khuonkho = $khuonkho; 
        $banthao->dinhdang = $dinhdang; 
        $banthao->dungluong = $dungluong; 
        $banthao->lanxuatban = $lanxuatban; 
        $banthao->lantaiban = $lantaiban; 
        $banthao->soluong = $soluong; 
        $banthao->nhain = $nhain;
        $banthao->diachinhain = $diachinhain;
        $banthao->trangweb = $trangweb; 
        $banthao->user_created_id = $user_created_id;
        $banthao->list_file_goc = $fileNameString.';';
        $banthao->save();

        $listProvince = Province::getProvince();
        $this->responseData['listProvince'] = $listProvince;
        $this->responseData['listDistrict'] = District::getDistrict();
        $this->responseData['listWard'] = Ward::getWard();

        $listDocuments = [];
        $listDocumentIds = auth()->user()->like_document;
        if(!empty($listDocumentIds)){
            $listDocuments = Document::whereIn('id', $listDocumentIds)
                ->where('status', 1)    
                ->paginate(8);
        }

        // ds đơn hàng
        $user = auth()->user(); 
        $id = $user->id;
        $status = $request->input('status', 'complete');

        $orders = Order::with('orderDetails')
            ->where('customer_id', $id)
            ->where('status', $status)
            ->orderBy('id', 'DESC')
            ->get();
        
        $buyEbook = CmsHistoryBuyebook::buyEbookUser(Auth::user()->id);

        $this->responseData['details'] = $orders;    
        $this->responseData['status'] = $status;    
        $this->responseData['listDocuments'] = $listDocuments;
        $this->responseData['buyEbook'] = $buyEbook;

        $this->responseData['array_payment_method'] = array(1=>'Vpnay',2=>'Tài khoản',3=>'Chuyển khoản');

        return $this->responseView('frontend.pages.user.index');
    }

    public function statusBanthao(Request $request) 
    {
        $id = Auth::guard('web')->user()->id;
        $status = $request->status;

        $listBanthao = Banthao::where('status', $status)
            // ->where('user_created_id', $id)
            ->orderBy('id', 'DESC')
            ->get();

        $this->responseData['details'] = $listBanthao;
        $this->responseData['status'] = $status;

        return $this->responseView('frontend.pages.user.banthao');
    }
}
