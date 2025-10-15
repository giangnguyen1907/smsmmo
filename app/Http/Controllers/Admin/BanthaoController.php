<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Consts;
use Illuminate\Http\Request;
use App\Models\Banthao;
use App\Models\Admin;
use App\Models\HistoryBanthao;
use App\Models\BanthaoCapphep;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\ContentService;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;

class BanthaoController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'cms_banthao';
        $this->viewPart = 'admin.pages.cms_banthao';
        $this->responseData['module_name'] = 'Quản lý bản thảo';
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

        $task = $request->get('task');
      
        if($task == ''){
            $task = 'chosogiayphep';
            $_REQUEST['task'] = 'chosogiayphep';
        }

        $params = $request->all();
        if($task == 'chosogiayphep'){
            $params['status'] = 'chosogiayphep';
            $this->responseData['module_name'] = 'Danh sách bản thảo chờ số giấy phép';
        }else if($task == 'choqdcapphep'){
            $params['status'] = 'choqdcapphep';
            $this->responseData['module_name'] = 'Danh sách bản thảo chờ QĐ cấp phép';
        }else if($task == 'choluuchieu'){
            $params['status'] = 'choluuchieu';
            $this->responseData['module_name'] = 'Danh sách bản thảo chờ lưu chiểu';
        }else if($task == 'phathanh'){
            $params['status'] = 'phathanh';
            $this->responseData['module_name'] = 'Danh sách bản thảo đã phát hành';
        }else if($task == 'thuhoi'){
            $params['status'] = 'thuhoi';
            $this->responseData['module_name'] = 'Danh sách bản thảo bị thu hồi';
        }else{
            $params['status'] = 'chosogiayphep';
            $_REQUEST['task'] = 'chosogiayphep';
            $this->responseData['module_name'] = 'Danh sách bản thảo chờ số giấy phép';
        }

        $rows = Banthao::getCmsBanthao($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

        $this->responseData['rows'] =  $rows;
        $this->responseData['params'] = $params;

        $tabs = [
            [
                'id' => 'tab_1',
                'title' => 'Đăng kí cấp phép',
                'active' => true
            ],
            [
                'id' => 'tab_2', 
                'title' => 'Quyết định cấp phép',
                'active' => false
            ],
            [
                'id' => 'tab_3',
                'title' => 'Quyết định lưu chiểu',
                'active' => false
            ],
            [
                'id' => 'tab_4',
                'title' => 'Quyết định phát hành',
                'active' => false
            ],
            [
                'id' => 'tab_5',
                'title' => 'Quyết định thu hồi',
                'active' => false
            ]
        ];

        $this->responseData['tabs'] = $tabs;
        return $this->responseView($this->viewPart . '.index');
    }

    public function updateNguoiChinhSua(Request $request)
    {
        try {
            $selectedPosts = $request->input('selectedPosts');
            $selectedUser = $request->input('selectedUser');

            Banthao::whereIn('id', $selectedPosts)
                ->update(['nguoichinhsua' => $selectedUser,
                'status' => 'processing']);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function storeHistoryBanthao(Request $request)
    {
        try {
            $banthao_id = $request->input('banthao_id');
            $note = $request->input('note');
            $admin_created_id = Auth::guard('admin')->user()->id;
            $admin_updated_id = Auth::guard('admin')->user()->id;

            $email = Auth::guard('admin')->user()->email;
            $array_email = explode('@',$email);
            $name_email = str_replace(['.',',','_','-'],'',$array_email[0]);
            $destinationPath = 'history_banthao/'.$name_email.'/';
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $fileNames = [];
            if($request->hasFile('list_file')) {
                $file_danhsachfile = $request->file('list_file');
                foreach ($file_danhsachfile as $file) {
                    $name = $file->getClientOriginalName();
                    $fileName = BanthaoController::convertName($name);
                    $file->move($destinationPath, $fileName);
                    $fileNames[] = '/'.$destinationPath.$fileName;
                }
            }
            $fileNameString = implode(';', $fileNames);

            $historyBanthao = new HistoryBanthao();
            $historyBanthao->banthao_id = $banthao_id;
            $historyBanthao->note = $note; 
            $historyBanthao->admin_created_id = $admin_created_id;
            $historyBanthao->admin_updated_id = $admin_updated_id;
            $historyBanthao->list_file = $fileNameString.';';
            $historyBanthao->save();

            return redirect()->back()->with('successMessage', 'Thêm mới báo cáo xử lý bản thảo thành công');
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
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
		$nguoidangky = $params['nguoidangky'];
        $diachinhain = $params['diachinhain'];
        $trangweb = $params['trangweb'];
        $admin_created_id = Auth::guard('admin')->user()->id;
        $admin_updated_id = Auth::guard('admin')->user()->id;

        $email_tk = Auth::guard('admin')->user()->email;
        $array_email = explode('@',$email_tk);
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
                $fileName = BanthaoController::convertName($name);
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
        $banthao->nguoidangky = $nguoidangky;
        $banthao->diachinhain = $diachinhain;
        $banthao->trangweb = $trangweb; 
        $banthao->admin_created_id = $admin_created_id;
        $banthao->admin_updated_id = $admin_updated_id;
        $banthao->list_file_goc = $fileNameString.';';
        $banthao->save();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }
	
	function cleanString($string) {
		return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
	}

    public function exportWord($id, Request $request)
    {
        // Lấy thông tin từ database
        $data = Banthao::find($id);

        $array_theloai = Consts::THELOAI_BANTHAO;
        
        // Khởi tạo PHPWord
        $phpWord = new PhpWord();
        
        // Thêm section
        $section = $phpWord->addSection();
        
        $titleStyleHeader = array(
            'bold' => false,
            'size' => 11,
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
        );

        $titleStyle = array(
            'size' => 11
        );
        
        if ($request->type === 'normal') {
            // Xuất bản đăng ký thường
            $section->addText('BẢN ĐĂNG KÝ CẤP GIẤY PHÉP', $titleStyleHeader);
            $section->addTextBreak(1);
            
            $section->addText('Tên tác phẩm: ' . $this->cleanString($data->tacpham), $titleStyle);
            $section->addText('Thể loại: ' . $array_theloai[$data->theloai], $titleStyle);
            $section->addText('Tên tác giả: ' . $data->tacgia, $titleStyle);
            $section->addText('Nội dung tóm tắt tác phẩm: ' . $this->cleanString($data->noidung), $titleStyle);
            $section->addText('Số trang: ' . $data->sotrang, $titleStyle);
            $section->addText('Khuôn khổ: ' . $data->khuonkho, $titleStyle);
            $section->addText('Lần xuất bản: ' . $data->lanxuatban, $titleStyle);
            $section->addText('Số lượng in: ' . $data->soluong, $titleStyle);
            
            // // $section->addTextBreak(1);
            $section->addText('Thông tin về người đăng ký', $titleStyle);
            $section->addText('Họ và tên: ' . $data->nguoidangky, $titleStyle);
            $section->addText('Địa chỉ: ' . $data->diachi, $titleStyle);
            $section->addText('Số điện thoại: ' . $data->dienthoai, $titleStyle);
            $section->addText('Email: ' . $data->email, $titleStyle);
            $section->addText('Dự kiến in tại nhà in: ' . $this->cleanString($data->nhain), $titleStyle);
            
        } else {
            // Xuất bản đăng ký điện tử
            $section->addText('BẢN ĐĂNG KÝ CẤP GIẤY PHÉP ĐIỆN TỬ', $titleStyleHeader);
            $section->addTextBreak(1);
            
            $section->addText('Tên tác phẩm: ' . $this->cleanString($data->tacpham), $titleStyle);
            $section->addText('Tên tác giả: ' . $data->tacgia, $titleStyle);
            $section->addText('Ngôn ngữ xuất bản: tiếng Việt', $titleStyle);
            $section->addText('Định dạng tệp tin: ' . $data->dinhdang, $titleStyle);
            $section->addText('Dung lượng của xuất bản phẩm điện tử (MB): ' . $data->dungluong  . ' MB', $titleStyle);
            $section->addText('Đối tác liên kết xuất bản: NXB Hội Nhà văn', $titleStyle);
            $section->addText('Tên biên tập viên: ' . $data->bientapvien, $titleStyle);
            $section->addText('Lần xuất bản: ' . $data->lanxuatban, $titleStyle);
            
            // $section->addTextBreak(1);
            $section->addText('Thông tin về người đăng ký', $titleStyle);
            $section->addText('Họ và tên: ' . $data->nguoidangky, $titleStyle);
            $section->addText('Địa chỉ: ' . $data->diachi, $titleStyle);
            $section->addText('Số điện thoại: ' . $data->dienthoai, $titleStyle);
            $section->addText('Email: ' . $data->email, $titleStyle);
            $section->addText('Dự kiến xuất bản tại: Nhà xuất bản Hội Nhà văn', $titleStyle);
            $section->addText('Hình thức sách điện tử tại: https://sachdientu.nxbhoinhavan.vn', $titleStyle);
            $section->addText('Nội dung tóm tắt tác phẩm: ' . $this->cleanString($data->noidung), $titleStyle);
        }

        // Lưu file
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        
        // Tạo response
        $fileName = $request->type === 'normal' ? 'ban_dang_ky.docx' : 'ban_dang_ky_dien_tu.docx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save("php://output");
        exit;
    }

    public function exportWordCapphep($id, Request $request)
    {
        try {

            $email_tk = Auth::guard('admin')->user()->email;
            $array_email = explode('@',$email_tk);
            $name_email = str_replace(['.',',','_','-'],'',$array_email[0]);
            $saveDirectory = 'banthao/'.$name_email.'/';
            if (!file_exists($saveDirectory)) {
                mkdir($saveDirectory, 0755, true);
            }

            // Lấy dữ liệu từ database
            $data = Banthao::findOrFail($id);

            if ($request->type === 'normal') {

                // Đường dẫn template
                $templatePath = public_path('templates/giay_phep_xuat_ban_2024.docx');
                
                // Kiểm tra file template tồn tại
                if (!file_exists($templatePath)) {
                    throw new \Exception('Template file không tồn tại!');
                }
                
                // Khởi tạo template processor
                $templateProcessor = new TemplateProcessor($templatePath);
                
                // Số quyết định
                $templateProcessor->setValue('so_quyet_dinh', $data->soqdcapphep ?? '');
                
                // Ngày tháng
                $date = Carbon::now();
                $templateProcessor->setValue('ngay', $date->format('d'));
                $templateProcessor->setValue('thang', $date->format('m'));
                $templateProcessor->setValue('nam', $date->format('Y'));
                
                // Thông tin xuất bản
                $templateProcessor->setValue('tac_pham', $this->cleanString($data->tacpham) ?? '');
                $templateProcessor->setValue('tac_gia', $data->tacgia ?? '');
                $templateProcessor->setValue('ngu_xuat_ban', 'Tiếng Việt');
                $templateProcessor->setValue('khuon_kho', $data->khuonkho ?? '');
                $templateProcessor->setValue('so_trang', $data->sotrang ?? '');
                $templateProcessor->setValue('so_luong', $data->soluong ?? '');
                $templateProcessor->setValue('doi_tac', $data->doitaclienket ?? '');
                $templateProcessor->setValue('bien_tap_vien', $data->bientapvien ?? '');
                $templateProcessor->setValue('ma_isbn', $this->cleanString($data->maisbn) ?? '');
                
                // Số xác nhận đăng ký
                $templateProcessor->setValue('so_giay_phep', $data->sogiayphep ?? '');
                
                // Thông tin in ấn
                $templateProcessor->setValue('nha_in', $this->cleanString($data->nhain) ?? '');
                $templateProcessor->setValue('dia_chi_in', $this->cleanString($data->diachinhain) ?? '');
                
                // Người ký
                $templateProcessor->setValue('nguoi_ky', 'NGUYỄN THÚY HẰNG');
                
                // Tạo tên file với timestamp
                $fileName = "quyet_dinh_xuat_ban_" . time() . ".docx";
                $filePath = $saveDirectory . $fileName;
                
                // Lưu file
                $templateProcessor->saveAs($filePath);
                
                // Return file để download
                return response()->download($filePath, $fileName, [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
                ])->deleteFileAfterSend(false); // Set false để giữ lại file sau khi download

            }else{

                // Đường dẫn template
                $templatePath = public_path('templates/giay_phep_xuat_ban_dien_tu_2024.docx');
                
                // Kiểm tra file template tồn tại
                if (!file_exists($templatePath)) {
                    throw new \Exception('Template file không tồn tại!');
                }
                
                // Khởi tạo template processor
                $templateProcessor = new TemplateProcessor($templatePath);
                
                // Số quyết định
                $templateProcessor->setValue('so_quyet_dinh', $data->soqdcapphep ?? '');
                
                // Ngày tháng
                $date = Carbon::now();
                $templateProcessor->setValue('ngay', $date->format('d'));
                $templateProcessor->setValue('thang', $date->format('m'));
                $templateProcessor->setValue('nam', $date->format('Y'));
                
                // Thông tin xuất bản
                $templateProcessor->setValue('tac_pham', $this->cleanString($data->tacpham) ?? '');
                $templateProcessor->setValue('tac_gia', $data->tacgia ?? '');
                $templateProcessor->setValue('ngu_xuat_ban', 'Tiếng Việt');
                $templateProcessor->setValue('dung_luong', $data->dungluong ?? '');
                $templateProcessor->setValue('doi_tac', $this->cleanString($data->doitaclienket) ?? '');
                $templateProcessor->setValue('bien_tap_vien', $data->bientapvien ?? '');
                $templateProcessor->setValue('ma_isbn', $this->cleanString($data->maisbn) ?? '');
                
                // Số xác nhận đăng ký
                $templateProcessor->setValue('so_giay_phep', $data->sogiayphep ?? '');
                
                // Thông tin in ấn
                $templateProcessor->setValue('trang_web', $this->cleanString($data->trangweb) ?? '');
                
                // Người ký
                $templateProcessor->setValue('nguoi_ky', 'NGUYỄN THÚY HẰNG');
                
                // Tạo tên file với timestamp
                $fileName = "quyet_dinh_xuat_ban_dien_tu_" . time() . ".docx";
                $filePath = $saveDirectory . $fileName;
                
                // Lưu file
                $templateProcessor->saveAs($filePath);
                
                // Return file để download
                return response()->download($filePath, $fileName, [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
                ])->deleteFileAfterSend(false); // Set false để giữ lại file sau khi download
                
            }
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function exportExcelLuuchieu(Request $request, $id){
        try {

            $email_tk = Auth::guard('admin')->user()->email;
            $array_email = explode('@',$email_tk);
            $name_email = str_replace(['.',',','_','-'],'',$array_email[0]);
            $saveDirectory = 'banthao/'.$name_email.'/';
            if (!file_exists($saveDirectory)) {
                mkdir($saveDirectory, 0755, true);
            }

            $date = Carbon::now();

            // Lấy dữ liệu từ database
            $data = Banthao::findOrFail($id); // Thay YourModel bằng model của bạn

            // Đọc file mẫu Excel
            $templatePath = public_path('templates/luu_chieu_2024.xlsx');

            if (!file_exists($templatePath)) {
                throw new \Exception('Template file không tồn tại!');
            }

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($templatePath);
            $sheet = $spreadsheet->getActiveSheet();

            // Điền dữ liệu vào file mẫu
            $sheet->setCellValue('B4', '/NXB HNV');
            $sheet->setCellValue('G5', 'Hà Nội, ngày ' . $date->format('d') . ' tháng ' . $date->format('m') . ' năm ' . $date->format('Y'));
            $sheet->setCellValue('E12', $this->cleanString($data->tacpham) ?? '');
            $sheet->setCellValue('E14', $data->tacgia ?? '');
            $sheet->setCellValue('E15', $data->tacgia ?? '');
            $sheet->setCellValue('E16', $data->bientapvien ?? '');
            $sheet->setCellValue('G17', $data->sogiayphep ?? '');
            $sheet->setCellValue('E18', $data->soqdphathanh . '/QĐ- NXBHNV ngày ');
            $sheet->setCellValue('E19', '1 tập');
            $sheet->setCellValue('E20', $data->lanxuatban ?? '');
            $sheet->setCellValue('E21', 'Việt');
            $sheet->setCellValue('E22', 'Việt');
            $sheet->setCellValue('F23', $data->sotrang ?? '');
            $sheet->setCellValue('G23', 'trang ( ' . ($data->dungluong ?? '') . ' MB byte)');
            $sheet->setCellValue('E24', $data->khuonkho ?? $data->dinhdang ?? '');
            $sheet->setCellValue('E25', $data->soluong ?? '');
            $sheet->setCellValue('E26', $this->cleanString($data->nhain) ?? '');
            $sheet->setCellValue('E27', $this->cleanString($data->diachinhain) ?? '');
            $sheet->setCellValue('E28', $data->giabanle ?? '');
            $sheet->setCellValue('H29', $this->cleanString($data->trangweb) ?? '');
            $sheet->setCellValue('F30', $this->cleanString($data->doitaclienket) ?? '');
            $sheet->setCellValue('E31', $this->cleanString($data->maisbn) ?? '');
            $sheet->setCellValue('D32', $data->sohuubanquyen ?? '');
            $sheet->setCellValue('G32', $data->thoihanbanquyen ?? '');

            // Tạo tên file với timestamp
            $fileName = "quyet_dinh_luu_chieu_" . time() . ".xlsx";
            $filePath = $saveDirectory . $fileName;

            // Lưu file Excel
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($filePath);

            // Trả về file để download
            return response()->download($filePath, $fileName, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
            ])->deleteFileAfterSend(false);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function exportWordPhathanh($id, Request $request)
    {
        try {

            $email_tk = Auth::guard('admin')->user()->email;
            $array_email = explode('@',$email_tk);
            $name_email = str_replace(['.',',','_','-'],'',$array_email[0]);
            $saveDirectory = 'banthao/'.$name_email.'/';
            if (!file_exists($saveDirectory)) {
                mkdir($saveDirectory, 0755, true);
            }

            // Lấy dữ liệu từ database
            $data = Banthao::findOrFail($id);

            // Đường dẫn template
            $templatePath = public_path('templates/quyet_dinh_phat_hanh_2024.docx');
            
            // Kiểm tra file template tồn tại
            if (!file_exists($templatePath)) {
                throw new \Exception('Template file không tồn tại!');
            }
            
            // Khởi tạo template processor
            $templateProcessor = new TemplateProcessor($templatePath);
            
            // Số quyết định
            $templateProcessor->setValue('so_qd_phat_hanh', $data->soqdphathanh ?? '');
            
            // Ngày tháng
            $date = Carbon::now();
            $templateProcessor->setValue('ngay', $date->format('d'));
            $templateProcessor->setValue('thang', $date->format('m'));
            $templateProcessor->setValue('nam', $date->format('Y'));
            
            // Thông tin xuất bản
            $templateProcessor->setValue('tac_pham', $this->cleanString($data->tacpham) ?? '');
            $templateProcessor->setValue('tac_gia', $data->tacgia ?? '');
            $templateProcessor->setValue('so_giay_phep', $data->sogiayphep ?? '');
            $templateProcessor->setValue('so_qd_cap_phep', $data->soqdcapphep ?? '');
            $templateProcessor->setValue('so_tap', '1');
            $templateProcessor->setValue('lan_xuat_ban', $data->lanxuatban ?? '');
            $templateProcessor->setValue('so_trang', $data->sotrang ?? '');
            $templateProcessor->setValue('khuon_kho', $data->khuonkho ?? '');
            $templateProcessor->setValue('so_luong', $data->soluong ?? '');
            $templateProcessor->setValue('nha_in', $this->cleanString($data->nhain) ?? '');
            $templateProcessor->setValue('dia_chi_nha_in', $this->cleanString($data->diachinhain) ?? '');
            $templateProcessor->setValue('doi_tac_lien_ket', $this->cleanString($data->doitaclienket) ?? '');
            $templateProcessor->setValue('ma_isbn', $this->cleanString($data->maisbn) ?? '');
            $templateProcessor->setValue('trang_web', $data->trangweb ?? '');
            
            // Người ký
            $templateProcessor->setValue('nguoi_ky', 'NGUYỄN THÚY HẰNG');
            
            // Tạo tên file với timestamp
            $fileName = "quyet_dinh_phat_hanh_" . time() . ".docx";
            $filePath = $saveDirectory . $fileName;
            
            // Lưu file
            $templateProcessor->saveAs($filePath);
            
            // Return file để download
            return response()->download($filePath, $fileName, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
            ])->deleteFileAfterSend(false); // Set false để giữ lại file sau khi download
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function exportWordThuhoi($id, Request $request)
    {
        try {

            $email_tk = Auth::guard('admin')->user()->email;
            $array_email = explode('@',$email_tk);
            $name_email = str_replace(['.',',','_','-'],'',$array_email[0]);
            $saveDirectory = 'banthao/'.$name_email.'/';
            if (!file_exists($saveDirectory)) {
                mkdir($saveDirectory, 0755, true);
            }

            // Lấy dữ liệu từ database
            $data = Banthao::findOrFail($id);

            // Đường dẫn template
            $templatePath = public_path('templates/quyet_dinh_thu_hoi_2024.docx');
            
            // Kiểm tra file template tồn tại
            if (!file_exists($templatePath)) {
                throw new \Exception('Template file không tồn tại!');
            }
            
            // Khởi tạo template processor
            $templateProcessor = new TemplateProcessor($templatePath);
            
            // Số quyết định
            $templateProcessor->setValue('so_thu_hoi', $data->socongvanthuhoi ?? '');
            
            // Ngày tháng
            $date = Carbon::now();
            $templateProcessor->setValue('ngay', $date->format('d'));
            $templateProcessor->setValue('thang', $date->format('m'));
            $templateProcessor->setValue('nam', $date->format('Y'));
            
            // Thông tin xuất bản
            $templateProcessor->setValue('tac_pham', $this->cleanString($data->tacpham) ?? '');
            $templateProcessor->setValue('tac_gia', $data->tacgia ?? '');
            $templateProcessor->setValue('so_giay_phep', $data->sogiayphep ?? '');
            $templateProcessor->setValue('so_qd_cap_phep', $data->soqdcapphep ?? '');
            $templateProcessor->setValue('so_tap', '1');
            $templateProcessor->setValue('lan_xuat_ban', $data->lanxuatban ?? '');
            $templateProcessor->setValue('so_trang', $data->sotrang ?? '');
            $templateProcessor->setValue('khuon_kho', $data->khuonkho ?? '');
            $templateProcessor->setValue('so_luong', $data->soluong ?? '');
            $templateProcessor->setValue('nha_in', $this->cleanString($data->nhain) ?? '');
            $templateProcessor->setValue('dia_chi_nha_in', $this->cleanString($data->diachinhain) ?? '');
            $templateProcessor->setValue('doi_tac_lien_ket', $this->cleanString($data->doitaclienket) ?? '');
            $templateProcessor->setValue('ma_isbn', $this->cleanString($data->maisbn) ?? '');
            $templateProcessor->setValue('trang_web', $this->cleanString($data->trangweb) ?? '');
            $templateProcessor->setValue('ly_do', $data->lydothuhoi ?? '');
            
            // Người ký
            $templateProcessor->setValue('nguoi_ky', 'NGUYỄN THÚY HẰNG');
            
            // Tạo tên file với timestamp
            $fileName = "quyet_dinh_thu_hoi_" . time() . ".docx";
            $filePath = $saveDirectory . $fileName;
            
            // Lưu file
            $templateProcessor->saveAs($filePath);
            
            // Return file để download
            return response()->download($filePath, $fileName, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
            ])->deleteFileAfterSend(false); // Set false để giữ lại file sau khi download
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function duyetBanthao(Request $request){

        $record = BanThao::findOrFail($request->id);
        $record->status = 'phathanh';
        $record->save();

        return response()->json(['status' => 'phathanh']);

    }

    public function detailCapphep($id)
    {
        $record = Banthao::find($id);
        return response()->json($record);
    }

    public function updateCapphep(Request $request, $id)
    {
        try {
            $record = Banthao::find($id);

            if($request->submit == 'duyet'){
                $record->status = 'choqdcapphep';
            }
            
            $record->bientapvien = $request->bientapvien;
            $record->tongbientap = $request->tongbientap;
            $record->giamdoc = $request->giamdoc;
            $record->nguoivebia = $request->nguoivebia;
            $record->nguoitrinhbay = $request->nguoitrinhbay;
            $record->nguoisuabanin = $request->nguoisuabanin;
            $record->nguoiveminhhoa = $request->nguoiveminhhoa;
            $record->sogiayphep = $request->sogiayphep;
            $record->soqdcapphep = $request->soqdcapphep;
            $record->maisbn = $request->maisbn;
            $record->doitaclienket = $request->doitaclienket;
            
            $record->save();
            
            return response()->json(['message' => 'Cập nhật thành công']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra'], 500);
        }
    }


    public function updateLuuchieu(Request $request, $id)
    {
        try {
            $record = Banthao::find($id);

            if($request->submit == 'duyet'){
                $record->status = 'choluuchieu';
            }
            
            $record->giabanle = $request->giabanle;
            $record->sohuubanquyen = $request->sohuubanquyen;
            $record->thoihanbanquyen = $request->thoihanbanquyen;
            $record->save();
            
            return response()->json(['message' => 'Cập nhật thành công']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra'], 500);
        }
    }

    public function updatePhathanh(Request $request, $id)
    {
        try {
            $record = Banthao::find($id);

            if($request->submit == 'duyet'){
                $record->status = 'phathanh';
            }
            
            $record->soqdphathanh = $request->soqdphathanh;
            $record->save();
            
            return response()->json(['message' => 'Cập nhật thành công']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra'], 500);
        }
    }

    public function updateThuhoi(Request $request, $id)
    {
        try {
            $record = Banthao::find($id);

            if($request->submit == 'duyet'){
                $record->status = 'thuhoi';
            }
            
            $record->socongvanthuhoi = $request->socongvanthuhoi;
            $record->lydothuhoi = $request->lydothuhoi;
            $record->save();
            
            return response()->json(['message' => 'Cập nhật thành công']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        
        $data = Banthao::find($id);
        $this->responseData['detail'] = $data;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }

        $banthao = Banthao::find($id);

        $params = $request->all();

        $email_tk = Auth::guard('admin')->user()->email;
        $array_email = explode('@',$email_tk);
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
                $fileName = BanthaoController::convertName($name);
                $file->move($destinationPath, $fileName);
                $fileNames[] = '/'.$destinationPath.$fileName;
            }

            $fileNameString = implode(';', $fileNames);
            $fileNameString = $fileNameString.';';
        }else{
            $fileNameString = $banthao->list_file_goc;
        }
        
        $banthao->tacpham = $params['tacpham'];
        $banthao->tacgia = $params['tacgia'];
        $banthao->butdanh = $params['butdanh'];
        $banthao->diachi = $params['diachi'];
        $banthao->dienthoai = $params['dienthoai'];
        $banthao->email = $params['email'];
        $banthao->theloai = $params['theloai'];
        $banthao->noidung = $params['noidung'];
        $banthao->sotrang = $params['sotrang'];
        $banthao->khuonkho = $params['khuonkho'];
        $banthao->dinhdang = $params['dinhdang'];
        $banthao->dungluong = $params['dungluong'];
        $banthao->lanxuatban = $params['lanxuatban'];
        $banthao->lantaiban = $params['lantaiban'];
        $banthao->soluong = $params['soluong'];
        $banthao->nhain = $params['nhain'];
        $banthao->nguoidangky = $params['nguoidangky'];
        $banthao->diachinhain = $params['diachinhain'];
        $banthao->trangweb = $params['trangweb'];
        $banthao->list_file_goc = $fileNameString;
        $banthao->bientapvien = $params['bientapvien'];
        $banthao->tongbientap = $params['tongbientap'];
        $banthao->giamdoc = $params['giamdoc'];
        $banthao->nguoivebia = $params['nguoivebia'];
        $banthao->nguoitrinhbay = $params['nguoitrinhbay'];
        $banthao->nguoisuabanin = $params['nguoisuabanin'];
        $banthao->nguoiveminhhoa = $params['nguoiveminhhoa'];
        $banthao->sogiayphep = $params['sogiayphep'];
        $banthao->soqdcapphep = $params['soqdcapphep'];
        $banthao->maisbn = $params['maisbn'];
        $banthao->thoigianluuchieu = $params['thoigianluuchieu'];
        $banthao->soqdphathanh = $params['soqdphathanh'];
        $banthao->socongvanthuhoi = $params['socongvanthuhoi'];
        $banthao->lydothuhoi = $params['lydothuhoi'];
        $banthao->admin_updated_id = Auth::guard('admin')->user()->id;
        $banthao->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(ContentService::checkRole($this->routeDefault,'delete') == 0){
			$this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
			return $this->responseView($this->viewPart . '.404');
		}
        $data = Banthao::find($id);
		$data->delete();
        return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }

    public function convertName($str) {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        $str = preg_replace("/(\“|\”|\‘|\’|\,|\!|\&|\;|\@|\#|\%|\~|\`|\=|\_|\'|\]|\[|\}|\{|\)|\(|\+|\^)/", '_', $str);
        $str = preg_replace("/( )/", '_', $str);
        return $str;
    }
}
