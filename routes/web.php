<?php

use App\Http\Controllers\FrontEnd\CmsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::namespace('FrontEnd')->group(function () {

  Route::get('buyebook-document', 'EbookController@listBuyebookDocumentByUser')->name('frontend.ebook.buyebook-document');
  Route::get('/calculate-price', 'EbookController@calculatePrice')->name('frontend.ebook.calculatePrice');
  Route::post('/buy-ebook', 'EbookController@storeBuyEbook')->name('frontend.ebook.store');
  Route::get('/vnpay-ebook', 'EbookController@vnpayEbook')->name('frontend.ebook.vpnay_ebook');

  Route::get('/countdownload', 'CmsController@countdownload');
  Route::get('/search_document', 'CmsController@search_document')->name('frontend.search.document');
  Route::get('/search_advanced', 'CmsController@search_advanced')->name('frontend.search.advanced');
  
  Route::get('/login', 'LoginController@index')->name('frontend.login');
  Route::post('/login-post', 'LoginController@login')->name('frontend.login.post');
  Route::get('/register', 'UsersController@showRegisterForm')->name('frontend.register.index');
  Route::post('/register', 'UsersController@register')->name('frontend.register');
  Route::get('/logout', 'LoginController@logout')->name('frontend.logout');
  //Quên mật khẩu
  Route::post('send-email-password', 'UsersController@sendEmailPassword')->name('frontend.send.email.password');
  Route::get('/reset-password-view/{alias}', 'UsersController@resetPasswordView')->name('frontend.view.reset.password');
  Route::post('reset-password-post', 'UsersController@resetPasswordPost')->name('frontend.reset.password.post');
  //check login
  Route::get('/check-login', function () {
    return response()->json(['loggedIn' => Auth::check()]);
  })->name('frontend.check.login');

  Route::get('/', 'HomeController@index')->name('frontend.home');
  Route::get('search', 'CmsController@search')->name('frontend.search.index');

  
  Route::get('/thue-sim', 'ServiceController@rentSim')->name('frontend.service.rent-sim');
  Route::post('/rentsim/create', 'ServiceController@rentSimCreate')->name('rentsim.create');
  // Thuê lại sim
  Route::post('/rentsimold/create', 'ServiceController@rentSimOldCreate')->name('rentsimold.create');

  Route::get('/thue-so-cu', 'ServiceController@rentOldNumber')->name('frontend.service.rent-old-number');
  Route::get('/lich-su-thue-sim', 'ServiceController@rentHistory')->name('frontend.service.history');
  Route::get('/nap-sim', 'ServiceController@rechargeSim')->name('frontend.service.recharge');
  Route::post('/nap-tien-sim', 'ServiceController@rechargeSubmit')->name('frontend.service.recharge-sim.submit');

  Route::get('/lich-su-nap', 'ServiceController@historyRecharge')->name('frontend.history.recharge');

  // Tạo ảnh *101#
  Route::get('/create-101', [App\Http\Controllers\FrontEnd\ServiceController::class, 'create101'])->name('frontend.service.create-101');

  // Ảnh gửi tin nhắn
  Route::get('/send-message-img', [App\Http\Controllers\FrontEnd\ServiceController::class, 'sendMessageImg'])->name('frontend.service.send-message-img');
  Route::match(['get','post'], '/recharge-account', [App\Http\Controllers\FrontEnd\ServiceController::class, 'rechargeAccount'])
    ->name('frontend.service.recharge-account');

  Route::get('/check-balance', 'ServiceController@checkBalance')->name('frontend.service.balance');




  // CMS
  // Route::get('dich-vu/{alias?}', 'CmsController@serviceCategory')->name('frontend.cms.service_category');
  // Route::get('dich-vu/{alias_category}/{alias_detail}', 'CmsController@service')->name('frontend.cms.service');

  Route::get('chuyen-khoa/{alias?}', 'CmsController@department')->name('frontend.cms.department');

  Route::get('bac-si', 'CmsController@doctorList')->name('frontend.cms.doctor.list');

  Route::get('thu-vien/{alias?}', 'CmsController@resourceCategory')->name('frontend.cms.resource_category');
  Route::get('thu-vien/{alias_category}/{alias_detail}', 'CmsController@resource')->name('frontend.cms.resource');

  //dịch vụ khám
  Route::get('dich-vu/{alias?}', 'CmsController@dichvuCategory')->name('frontend.cms.dichvu_category');
  Route::get('chi-tiet-dich-vu/{alias_detail}', 'CmsController@dichvuDetail')->name('frontend.cms.dichvu_detail');

  Route::get('profile/{alias?}', 'CmsController@postCategoryProfile')->name('frontend.cms.post_category_profile');
  
  Route::get('media/{alias?}', 'CmsController@postCategoryMedia')->name('frontend.cms.post_category_media');
  Route::get('xem-them-bai-viet', 'CmsController@viewMore')->name('frontend.cms.view_more');
  Route::get('news/{alias}', 'CmsController@postCategory')->name('frontend.cms.post_category_search');
  // Route::get('news/{alias?}', 'CmsController@postCategory')->name('frontend.cms.post_category');
// Tin tức
  //Route::get('news/{alias}', 'CmsController@blog')->name('frontend.cms.blog');
  Route::get('news-detail/{alias}', 'CmsController@blogDetail')->name('frontend.cms.blog_detail');
  
  // Tài lieu
  Route::get('document/{alias?}', 'CmsController@cmsDocument')->name('frontend.cms.document');
  Route::get('view/{alias?}', 'CmsController@cmsDocumentDetail')->name('frontend.cms.view');
  Route::get('download', 'CmsController@downloadFile')->name('frontend.cms.download');
  Route::get('read-pdf', 'CmsController@readPdf')->name('frontend.cms.read-pdf');
  Route::match(['get', 'post'],'like-document', 'CmsController@likeDocument')->name('frontend.cms.like-document');
  Route::get('list-like-document', 'CmsController@listLikeDocument')->name('frontend.cms.list-like-document');

  // author
  Route::get('all-author', 'CmsController@allAuthor')->name('frontend.cms.all-author');
  Route::get('authors-documents/{id?}', 'CmsController@authorsDocuments')->name('frontend.cms.authors-documents');
  
  //tất cả tài liệu
  Route::get('all-document', 'CmsController@allDocument')->name('frontend.all.document');
  Route::post('comment', 'CmsController@cmsComment')->name('frontend.cms.comment');
  Route::post('comment-post', 'CmsController@cmsPostComment')->name('frontend.cms.post.comment');

  // Infors
  Route::match(['get', 'post'], 'edit-infor', 'UsersController@editInfor')->name('frontend.edit.infor');
  Route::post('change-password', 'UsersController@changePassword')->name('frontend.change.password');
  //phân trang
  // Route::post('', 'UsersController@changePassword')->name('frontend.change.password');
  

  //dich vu
  //Route::get('service/{alias}', 'CmsController@service')->name('frontend.cms.service');
  

  Route::get('thu-vien/{alias?}', 'CmsController@thuvienCategory')->name('frontend.cms.thuvien_category');
  Route::get('detail/{alias_detail}', 'CmsController@post')->name('frontend.cms.post');
  Route::get('chi-tiet-thu-vien/{alias_detail}', 'CmsController@postMedia')->name('frontend.cms.post_media');
  Route::get('chi-tiet-bac-si/{alias_detail}', 'CmsController@postDoctor')->name('frontend.cms.post_doctor');
  Route::get('tag/{alias_category?}', 'CmsController@cmstag')->name('frontend.cms.tag');

  Route::get('service/{alias}', 'CmsController@serviceCategory')->name('frontend.cms.service_category');
  Route::get('service-detail/{alias}', 'CmsController@serviceDetail')->name('frontend.cms.service_detail');

  Route::post('search', 'CmsController@newsSearch')->name('frontend.cms.news_search');
  Route::get('san-pham/{alias}', 'CmsController@productCategory')->name('frontend.cms.product_category');
  Route::get('chi-tiet-sp/{alias_detail}', 'CmsController@product')->name('frontend.cms.product');

  // Bài viết giới thiệu
  Route::get('intro/{alias}', 'CmsController@postIntroduction')->name('frontend.cms.post_introduction');
  //album 
  Route::get('album/{alias}', 'CmsController@album')->name('frontend.cms.album');

  Route::get('gallery/{alias}', 'CmsController@gallery')->name('frontend.cms.gallery');

  Route::get('faqs/{alias}', 'CmsController@faqs')->name('frontend.cms.faq');
  
  Route::get('testimonial/{alias}', 'CmsController@testimonial')->name('frontend.cms.testimonial');
  
  Route::get('about-us/{alias}', 'CmsController@aboutus')->name('frontend.cms.aboutus');
  
  // Booking
  Route::post('booking', 'BookingController@store')->name('frontend.booking.store');
  // Contact
  Route::get('contact/{alias}', 'ContactController@index')->name('frontend.contact');
  Route::post('contact', 'ContactController@store')->name('frontend.contact.store');
  // Order
  Route::post('order-service', 'OrderController@storeOrderService')->name('frontend.order.store.service');
  // Cart
  Route::get('/add-to-cart', 'OrderController@addToCart')->name('frontend.order.add_to_cart');
  Route::get('gio-hang', 'OrderController@cart')->name('frontend.order.cart');
  Route::get('update-cart', 'OrderController@updateCart')->name('frontend.order.cart.update');
  Route::get('remove-from-cart', 'OrderController@removeCart')->name('frontend.order.cart.remove');
  Route::post('order-product', 'OrderController@storeOrderProduct')->name('frontend.order.store.product');
  Route::get('clear-cart', 'OrderController@clearCart')->name('frontend.order.clear_cart');
  Route::get('return-cart', 'OrderController@returnCart')->name('frontend.order.return_cart');
  Route::get('save-cart', 'OrderController@saveCart')->name('frontend.order.save_cart');
	// Theo dõi đơn hàng
  Route::get('order-tracking', 'OrderController@orderTracking')->name('frontend.order.tracking');
   //hủy đơn hàng
   Route::post('order-cancel{alias}', 'OrderController@orderCancel')->name('frontend.order.cancel');

  // status order
  Route::get('status-order', 'UsersController@statusOrder')->name('frontend.status.order');

  Route::get('status-banthao', 'BanThaoController@statusBanthao')->name('frontend.status.banthao');
	
  Route::get('/language', 'CmsController@language');

	Route::get('load-district', 'CmsController@loadDistrict')->name('frontend.load_district');
	Route::get('load-ward', 'CmsController@loadWard')->name('frontend.load_ward');

  Route::get('load-voucher', 'CmsController@loadVoucher')->name('frontend.load_voucher');

  //Route::get('/{alias2}', 'PageController@index')->name('frontend.page');

  
  
  
  Route::group(['prefix' => 'user', 'middleware' => ['auth:web']], function () {
    Route::post('banthao', 'BanThaoController@storeBanthao')->name('frontend.user.banthao');
    Route::get('profile', 'UsersController@index')->name('frontend.user.index');
    Route::get('paginate', 'UsersController@paginate')->name('frontend.user.paginate');
    Route::post('update_profile', 'UsersController@update')->name('frontend.user.update');
    Route::post('comment', 'CommentController@store')->name('frontend.comment.store');
    Route::post('addnew', 'CmsController@addnew')->name('frontend.post.addnew');
    Route::get('/logout', 'LoginController@logout')->name('frontend.logout');
    Route::post('recharge', 'UsersController@storeRecharge')->name('frontend.user.store_recharge');
    Route::get('vnpay-recharge', 'UsersController@vnpayRecharge')->name('frontend.user.vpnay_recharge');
  });

  Route::get('auth/google', 'LoginController@redirectToGoogle')->name('login.google');
  Route::get('auth/google/callback', 'LoginController@handleGoogleCallback'); 

  Route::get('auth/facebook', 'LoginController@redirectToFacebook')->name('login.facebook');
  Route::get('auth/callback/facebook', 'LoginController@handleFacebookCallback'); 

  Route::get('/route-cache', function() {
    \Artisan::call('route:cache');
    return 'Routes cache cleared';
});
Route::get('/clear-cache', function() {
    \Artisan::call('cache:clear');
    return 'Application cache cleared';
});
});
