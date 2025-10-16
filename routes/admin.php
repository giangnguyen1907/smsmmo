<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * For check roles (permission access) for each route (function_code),
 * required each route have to a name which used to the
 * check in middleware permission and this is defined in Module, Function Management
 * @author: ThangNH
 * @created_at: 2021/10/01
 */
/**---------------------------------------------------------------------------------------------------------------------------
 *                          ADMIN USER ROLE MANAGE
 * ----------------------------------------------------------------------------------------------------------------------------*/
Route::group(['namespace' => 'Admin'], function () {
    // Login
    Route::get('/login', 'LoginController@index')->name('admin.login');
    Route::post('/login', 'LoginController@login')->name('admin.login.post');
    // Authentication middleware
    Route::group(['middleware' => ['auth:admin']], function () {
        // Logout
        Route::get('/logout', 'LoginController@logout')->name('admin.logout');
        // Dashboard
        Route::get('/', 'HomeController@index')->name('admin.home');
        // All route in admin system CRUD
        Route::resources([
            'admins' => 'AdminController',
            'admin_menus' => 'AdminMenuController',
            'modules' => 'ModuleController',
            'module_functions' => 'ModuleFunctionController',
            'roles' => 'RoleController',
            'blocks' => 'BlockController',
            'block_contents' => 'BlockContentController',
            'pages' => 'PageController',
            'menus' => 'MenuController',
            'options' => 'OptionController',
            'widgets' => 'WidgetController',
            'components' => 'ComponentController',
            'component_configs' => 'ComponentConfigController',
            'widget_configs' => 'WidgetConfigController',

            'cms_taxonomys' => 'CmsTaxonomyController',
            'profile' => 'CmsProfileController',
            'cms_services' => 'CmsServiceController',
            'cms_resources' => 'CmsResourceController',
            'cms_doctors' => 'CmsDoctorController',
            'cms_posts' => 'CmsPostController',
            'cms_products' => 'CmsProductController',
            'contacts' => 'ContactController',
            'bookings' => 'BookingController',
            'popups' => 'PopupController',
            'comment' => 'CommentController',
            'history' => 'HistoryController',
            'users' => 'UsersController',
            'introduction' => 'IntroductionController',
            'royaltie' => 'RoyaltieController',
            'expert' => 'ExpertController',
            'cms_media' => 'CmsMediaController',
            'cms_dichvu' => 'CmsDichvuController',
            'cms_posts_dichvu' => 'CmsPostDichvuController',
			'cms_language' => 'CmsLanguageController',
            'cms_translate' => 'CmsTranslateController',
            'cms_tag' => 'CmsTagController',
            'cms_author' => 'CmsAuthorController',
            'document' => 'DocumentController',
            'booksize' => 'BookSizeController',
            'ebookPackage' => 'EbookPackageController',
            'readingpackage' => 'ReadingPackageController',
            'managershop' => 'ManagerShopController',
            'transaction' => 'TransactionController',
            'listbill' => 'ListBillController',
            'orders' => 'OrderController',
            'importbook' => 'ImportBookController',
            'exportbook' => 'ExportBookController',
            'voucher' => 'VoucherController',
			'cms_department' => 'CmsDepartmentController',
            'cms_item_work' => 'CmsItemWorkController',
            'cms_type_work' => 'CmsTypeWorkController',
			'manage_work' => 'CmsManageWorkController',
            'cms_history_rechargeuser' => 'CmsHistoryRechargeuserController',
            'manage_project' => 'CmsManageProjectController',
            'history_buyebook' => 'CmsHistoryBuyebookController',
            'cms_banthao' => 'BanthaoController',
            'services' => 'ServiceController'
        ]);

        Route::post('export-word/{id}', 'BanthaoController@exportWord')->name('cms_banthao.export_word');
        Route::post('export-word-capphep/{id}', 'BanthaoController@exportWordCapphep')->name('cms_banthao.export_word_capphep');
        Route::post('export-excel-luu-chieu/{id}', 'BanthaoController@exportExcelLuuchieu')->name('cms_banthao.export_excel_luuchieu');
        Route::post('export-word-phathanh/{id}', 'BanthaoController@exportWordPhathanh')->name('cms_banthao.export_word_phathanh');
        Route::post('export-word-thuhoi/{id}', 'BanthaoController@exportWordThuhoi')->name('cms_banthao.export_word_thuhoi');
        Route::get('detail-capphep/{id}', 'BanthaoController@detailCapphep')->name('cms_banthao.detail_capphep');
        Route::post('update-capphep/{id}', 'BanthaoController@updateCapphep')->name('cms_banthao.update_capphep');
        Route::post('update-luuchieu/{id}', 'BanthaoController@updateLuuchieu')->name('cms_banthao.update_luuchieu');
        Route::post('update-phathanh/{id}', 'BanthaoController@updatePhathanh')->name('cms_banthao.update_phathanh');
        Route::post('update-thuhoi/{id}', 'BanthaoController@updateThuhoi')->name('cms_banthao.update_thuhoi');
        Route::post('duyet-phat-hanh', 'BanthaoController@duyetBanthao')->name('cms_banthao.duyet_banthao');

        Route::post('cms_banthao/update_nguoichinhsua', 'BanthaoController@updateNguoichinhsua')->name('cms_banthao.update_nguoichinhsua'); 
        Route::post('cms_banthao/store_history_banthao', 'BanthaoController@storeHistoryBanthao')->name('cms_banthao.store_history_banthao');

        Route::post('rechargeuser/update_status', 'CmsHistoryRechargeuserController@updateStatus')->name('cms_history_rechargeuser.updateStatus'); 
		Route::get('buyebook/update_ebook', 'CmsHistoryBuyebookController@updateStatus')->name('cms_history_buyebook.update_ebook');
		
        // export importbook
        Route::get('import-book/export', 'ImportBookController@export')->name('importbook.export.excel');

        // export exportbook
        Route::get('export-book/export', 'ExportBookController@export')->name('exportbook.export.excel');

        //order export excel
        Route::get('order/export', 'OrderController@exportExcel')->name('order.export');
		
        Route::post('users/create_ajax', 'UsersController@createUsers')->name('users.create_ajax'); 

        Route::post('cms_author/create_author', 'CmsAuthorController@createAuthor')->name('cms_author.create_author'); 

        Route::post('booksize/create_ajax', 'BookSizeController@createAjax')->name('booksize.create_ajax'); 

		// Load tên sách
		Route::get('load_book', 'DocumentController@loadBook')->name('document.load_book');

		// Lưu nhập kho vào SESSION
        Route::post('import_book/save_ajax', 'ImportBookController@saveAjax')->name('import_book.save_ajax');
        Route::post('import_book/save_delete', 'ImportBookController@saveDelete')->name('import_book.save_delete');
        Route::post('import_book/update_ajax', 'ImportBookController@saveUpdate')->name('import_book.update_ajax');

        Route::post('export_book/save_ajax', 'ExportBookController@saveAjax')->name('export_book.save_ajax');
        Route::post('export_book/save_delete', 'ExportBookController@saveDelete')->name('export_book.save_delete');
        Route::post('export_book/update_ajax', 'ExportBookController@saveUpdate')->name('export_book.update_ajax');

        // Lấy công nợ khách hàng
        Route::post('import_book/load_debt', 'ImportBookController@loadBebt')->name('import_book.load_debt');

        // Luu danh mục
		Route::post('cms_dichvu/save_ajax', 'CmsDichvuController@saveAjax')->name('cms_dichvu.save_ajax');
        
        Route::get('cms_posts_featured', 'CmsPostController@listFeatured')->name('cms_posts_featured.index');
        Route::post('cms_posts/update_sort', 'CmsPostController@updateSort')->name('cms_posts.update_sort');
        // Tin crawler
		Route::post('cms_posts/load_crawler', 'CmsPostController@loadCrawler')->name('cms_posts.load_crawler');
		
        Route::post('cms_posts/load_featured', 'CmsPostController@loadFeatured')->name('cms_posts.load_featured');
        Route::post('cms_posts/post_relative', 'CmsPostController@postRelative')->name('cms_posts.post_relative');
        Route::post('cms_posts/update_featured', 'CmsPostController@updateFeatured')->name('cms_posts.update_featured');
        Route::post('cms_posts/update_position', 'CmsPostController@updatePosition')->name('cms_posts.update_position');
        Route::post('cms_posts/update_order', 'CmsPostController@updateOrder')->name('cms_posts.update_order');
        Route::post('cms_posts/update_comment_status', 'CommentController@updateStatus')->name('cms_posts.update_comment_status');
        Route::post('cms_posts/update_royaltie', 'RoyaltieController@updateStatus')->name('cms_posts.update_royalties');
        Route::post('live_reporting/create_comment', 'LiveReportingController@createComment')->name('live_reporting.create_comment');
		Route::post('live_reporting/update_live', 'LiveReportingController@updateStatus')->name('live_reporting.update_live');
        Route::post('online_exchange/create_comment', 'OnlineExchangeController@createComment')->name('online_exchange.create_comment');
        Route::post('online_exchange/status_comment', 'OnlineExchangeController@updateStatusComment')->name('online_exchange.status_comment');

        // Order services
        Route::get('order_services', 'OrderController@listOrderService')->name('order_services.index');
        Route::get('order_services/{order}', 'OrderController@showOrderService')->name('order_services.show');
        Route::put('order_services/{order}', 'OrderController@update')->name('order_services.update');
        Route::delete('order_services/{order}', 'OrderController@destroy')->name('order_services.destroy');
        // Order Products
        Route::get('order_products', 'OrderController@listOrderProduct')->name('order_products.index');
        Route::get('order_products/{order}', 'OrderController@showOrderProduct')->name('order_products.show');
        Route::put('order_products/{order}', 'OrderController@update')->name('order_products.update');
        Route::delete('order_products/{order}', 'OrderController@destroy')->name('order_products.destroy');
        Route::put('order_details/{orderDetail}', 'OrderDetailController@update')->name('order_details.update');
        Route::delete('order_details', 'OrderDetailController@destroy')->name('order_details.destroy');
        // Call request
        Route::get('call_request', 'ContactController@listCallRequest')->name('call_request.index');
        Route::get('call_request/{contact}', 'ContactController@showCallRequest')->name('call_request.show');
        Route::put('call_request/{contact}', 'ContactController@update')->name('call_request.update');
        Route::delete('call_request/{contact}', 'ContactController@destroy')->name('call_request.destroy');

        // Get params block for update Page management
        Route::get('get_block_params', 'BlockController@getBlockParams')->name('blocks.params');
        Route::get('get_block_contents_by_template', 'BlockContentController@getBlockContentsByTemplate')->name('block_contents.get_by_template');
        // Sort menu in module update menu public
        Route::post('menus/update_sort', 'MenuController@updateSort')->name('menus.update_sort');
        Route::post('menus/delete', 'MenuController@delete')->name('menus.delete');

        // Config to use laravel-filemanager
        Route::group(['prefix' => 'filemanager'], function () {
            \UniSharp\LaravelFilemanager\Lfm::routes();
        });

        // Update information web to Option table
        Route::put('web/update/{id}', 'OptionController@webUpdate')->name('web.update');
        Route::get('web_information', 'OptionController@webInformation')->name('web.information');
        Route::get('web_image', 'OptionController@webImage')->name('web.image');
        Route::get('web_social', 'OptionController@webSocial')->name('web.social');
        Route::get('web_source', 'OptionController@webSource')->name('web.source');
        Route::get('web_payment_information', 'OptionController@webPaymentInformation')->name('web.payment_information');

        // Test export excel
        Route::get('export', 'ExportController@export')->name('export');
		
		// view thêm mới công việc con
        Route::get('/creat_relation_work/{id}', 'CmsManageWorkController@creatRelationWork')->name('admin.creat_relation_work');
        // lưu công việc con
        Route::post('/store_relation_work', 'CmsManageWorkController@storeRelationWork')->name('admin.store_relation_work');
        // view cập nhật công việc con
        Route::get('/edit_relation_work/{id}', 'CmsManageWorkController@editRelationWork')->name('admin.edit_relation_work');
        // cập nhật công việc con
        Route::post('/update_relation_work', 'CmsManageWorkController@updateRelationWork')->name('admin.update_relation_work');
        // xóa công việc con
        Route::get('/destroy_relation_work', 'CmsManageWorkController@destroyRelationWork')->name('cms_posts.destroy_relation_work');
        // thêm mới bình luận công việc con
        Route::post('/store_history_work', 'CmsManageWorkController@storeHistoryWork')->name('cms_work.store_history_work');
        // thay đổi trạng thái công việc con
        Route::post('/update_status_relation_work', 'CmsManageWorkController@updateStatusRelationWork')->name('cms_work.update_status_relation_work');
        // xóa công việc cha
        Route::get('/destroy_manage_work/{id}', 'CmsManageWorkController@destroy')->name('cms_work.destroy_manage_work');
        // xem danh sách công việc con
        Route::get('/view_relation_work/{id}', 'CmsManageWorkController@viewRelationWork')->name('admin.view_relation_work');
		
		Route::post('admins/load_member', 'AdminController@loadMember')->name('admins.load_member');

        Route::get('/admin_profile', 'AdminController@adminProfile')->name('admin_profile.index');

        Route::get('/update_profile', 'AdminController@updateProfile')->name('admins.update_profile');
		
		Route::get('/delete_file', 'CmsManageWorkController@deleteFile')->name('admins.relation-work.delete-file');

        Route::get('/destroy_manage_project/{id}', 'CmsManageProjectController@destroy')->name('cms_project.destroy_manage_project');

        Route::get('/view_parent_project/{id}', 'CmsManageProjectController@viewParentProject')->name('cms_project.view_parent_project');

        Route::get('/view_parent_project/{id}', 'CmsManageProjectController@viewParentProject')->name('cms_project.view_parent_project');

        Route::post('/project_file/{id}', 'CmsManageProjectController@storeProjectFile')->name('cms_project.project_file');

        Route::get('/destroy_project_file', 'CmsManageProjectController@destroyProjectFile')->name('cms_project.destroy_project_file');

        Route::get('/search_project_file', 'CmsManageProjectController@searchProjectFile')->name('cms_project.search_project_file');

        Route::post('/parent_project/{id}', 'CmsManageProjectController@storeParentProject')->name('cms_project.parent_project');

        Route::get('/destroy_parent_project', 'CmsManageProjectController@destroyParentProject')->name('cms_project.destroy_parent_project');
        
        Route::get('/status_project', 'CmsManageProjectController@changeStatusProject')->name('cms_project.status_project');

        Route::post('/update_payment', 'CmsHistoryRechargeuserController@updatePayment')->name('cms_history_rechargeuser.update_payment');
		
    });
});
