<?php
namespace App;

class Consts
{
	const THELOAI_BANTHAO = [
            'vanxuoi' => 'Văn xuôi',
            'tho' => 'Thơ',
            'llpb' => 'LLPB',
            'dich' => 'Dịch',
            'khac' => 'Khác',
        ];
		
	const STATUS_WORK = [
        'prioritize' => 'Ưu tiên',
        'processing' => 'Đang xử lý',
        'complete' => 'Hoàn thành',
        'reprocess' => 'Xử lý lại'
    ];
	
    const ORDER_DETAIL_STATUS = [
        'pending' => 'Chờ duyệt',
        'delivery' => 'Đang giao',
        'complete' => 'Hoàn thành',
        'reject' => 'Hủy đơn',
    ];
    
    const SELECT_COLECTION = [
        'title' => 'Nhan đề',
        'publisher' => 'Nhà xuất bản',
        'author' => 'Tác giả',
        'country' => 'Quốc gia',
    ];

    const SELECT_BOOK = [
        'percent_paper' => 'Sách giấy',
        'percent_ebook' => 'Sách Ebook',
        'percent_audio' => 'Sách Audio',
    ];

    const SELECT_PUBLISH_YEAR = [
        'publish_paper' => 'Năm xb sách giấy',
        'publish_ebook' => 'Năm xb sách Ebook',
    ];

    const SELECT_OPERATOR = [
        'and' => 'Và',
        'or' => 'Hoặc',
    ];

    const IS_TYPE_COMMENT = [
        'document' => 'Sách',
        'news' => 'Tin tức',
    ];

    const POST_STATUS_TEXT = [
        'active' => 'Hoạt động',
        'deactive' => 'Từ chối',
        'waiting' => 'Chờ xử lý',
        'lock' => 'Đã gỡ',
        // 'draft' => 'Nháp',
        'delete' => 'Đã xóa',
    ];
    // For delete some data
    const STATUS_DELETE = 'delete';

    const url_admin = '/admin/';

    // Status for users
    const USER_STATUS = [
        'pending' => 'pending',
        'active' => 'active',
        'deactive' => 'deactive',
        'delete' => 'delete'
    ];

    //vị trí của danh mục hiển thị ngoài trang chủ
    const POST_POSITION_TAXONMY = [
        '1' => 'Nổi bật trang chủ',
        '0' => 'Không hiển thị trên trang chủ'
    ];

    // vị trí của tin
    const POST_POSITION = [
        '2' => 'Tin chân trang',
        '1' => 'Tin nổi bật trang chủ',
        '0' => 'Tin thường'
    ];
     
     //vị trí của ảnh 
     const ALBUM_POSITION = [
        '2' => 'Ảnh dọc',
        '1' => 'Ảnh ngang',
        '0' => 'Ảnh thường',
    ];
    // vị trí của tin
    const POST_POSITION_SORT = [
        '2' => 'Đáng chú ý',
        '1' => 'Xu hướng'
    ];


    // Status for general
    const STATUS_COMMENT = [
        '0' => 'Chấp nhận',
        '1' => 'Chờ duyệt',
        '2' => 'Bị khóa'
    ];

    // Status for general
    const STATUS = [
        'active' => 'Hoạt động',
        'deactive' => 'Không HĐ'
    ];

    // Array taxonomy status
    const LIVE_STATUS = [
        'active' => 'Hoạt động',
        'deactive' => 'Không HĐ',
        'waiting' => 'Chờ duyệt',
    ];
    // Array taxonomy status
    const TAXONOMY_STATUS = [
        'active' => 'active',
        'deactive' => 'deactive',
        'choduyet' => 'choduyet',
        'waiting' => 'waiting',
        'lock' => 'lock',
        'tralai' => 'tralai'
    ];
    // Thể loại taxonomy
    const TAXONOMY = [
        'news' => 'News',
		'about-us' => 'About us',
        'intro' => 'Introduction',
        'document' => 'Documents',
		'contact' => 'Contact',
    ];

    const BLOG = [
        '1' => 'Manicure',
        '2' => 'Waxing',
        '3' => 'Pedicure',
        '4' => 'Nails',
    ];

    const LOCALE = [
        'locale' => 'en',
    ];

    const TAXONOMY_DICHVU = [
        'service'=>'Danh mục dịch vụ'
    ];

    // Thể loại taxonomy
    const CATEGORY = [
        'news' => 'news',
		'about-us' => 'about-us',
        'intro' => 'intro',
        'service' => 'service',
        'contact' => 'contact',
        'document' => 'document',
        'faqs' => 'faqs',
        'testimonial' => 'testimonial',
    ];
    // Loại bài đăng
    const POST_TYPE = [
        'post' => 'post',
        'product' => 'product',
        'intro' => 'intro',
        'doctor' => 'doctor',
        'gallery' => 'gallery',
        'resource' => 'resource'
    ];
    // Mảng lưu trạng thái bài viết
    const POST_STATUS = [
        'active' => 'active',
        'deactive' => 'deactive',
        'pending' => 'pending',
        'waiting' => 'waiting',
        'lock' => 'lock',
        'rollback' => 'rollback',
        'draft' => 'draft',
        'delete' => 'delete',
    ];
    const ROUTE_PREFIX_TAXONOMY = [
        '' => 'deactive',
        'reporting' => 'reporting',
        'exchange' => 'exchange',
        'post_detail' => 'chi-tiet',
        'post_category' => 'danh-muc',
        'post_tag' => 'tag',
        'post_introduction' => 'introduction'
    ];

    const DEFAULT_PAGINATE_LIMIT = 20;
    const POST_PAGINATE_LIMIT = 20;
    const POST_MEDIA_PAGINATE_LIMIT = 18;
    const DEFAULT_OTHER_LIMIT = 3;
    const DEPARTMENT_OTHER_LIMIT = 4;
    const DOCTOR_OTHER_LIMIT = 4;
    const DEFAULT_SIDEBAR_LIMIT = 5;
    const DEFAULT_RELATIVE_LIMIT = 4;

    const TITLE_BOOLEAN = [
        '1' => 'true',
        '0' => 'false'
    ];
    
    const STATUS_ROYALTIES =[
        0 => 'Chờ TT',
        1 => 'Đã TT'
    ];

    const MENU_TYPE = [
        'header' => 'Menu chính',
		'left' => 'Menu nổi bật (Trái - phải)',
        'footer' => 'Menu chân trang'
    ];

    const URI_TYPE = [
        'route' => 'Route name',
        'path' => 'Path',
        'url' => 'Url Customize',
    ];

    // Loại liên hệ
    const CONTACT_TYPE = [
        'contact' => 'contact',
        'faq' => 'faq',
        'newsletter' => 'newsletter',
        'advise' => 'advise',
        'call_request' => 'call_request'
    ];
    const CONTACT_STATUS = [
        'new' => 'Mới',
        'processing' => 'Đang xử lý',
        'processed' => 'Đã xử lý',
        'cancel' => 'Hủy'
    ];
    // Type for order
    const ORDER_TYPE = [
        'product' => 'product',
        'service' => 'service',
    ];
    const ORDER_STATUS = [
        'pending' => 'Chờ duyệt',
        'delivery' => 'Đang giao',
        'complete' => 'Hoàn thành',
        'reject' => 'Hủy đơn',
    ];
    // Tạo danh sách chức năng định tuyến để gọi khi tạo trang trong admin -> người dùng có thể tùy chọn
    const ROUTE_NAME = [
        [
            "title" => "Home Page",
            "name" => "frontend.home",
            "template" => [
                [
                    "title" => "Home Primary",
                    "name" => "home.primary"
                ]
            ]
        ],
        [
            "title" => "Post Page",
            "name" => "frontend.cms.post",
            "template" => [
                [
                    "title" => "Post Default",
                    "name" => "post.default"
                ]
            ]
        ],
        [
            "title" => "Post Category Page",
            "name" => "frontend.cms.post_category",
            "template" => [
                [
                    "title" => "Post Category Default",
                    "name" => "post.category.default"
                ]
            ]
        ],
        [
            "title" => "Product Page",
            "name" => "frontend.cms.product",
            "template" => [
                [
                    "title" => "Product Default",
                    "name" => "product.default"
                ]
            ]
        ],
        [
            "title" => "Product Category Page",
            "name" => "frontend.cms.product_category",
            "template" => [
                [
                    "title" => "Product Category Default",
                    "name" => "product.category.default"
                ]
            ]
        ],
        [
            "title" => "Service Page",
            "name" => "frontend.cms.service",
            "template" => [
                [
                    "title" => "Service Default",
                    "name" => "service.default"
                ]
            ]
        ],
        [
            "title" => "Service Category Page",
            "name" => "frontend.cms.service_category",
            "template" => [
                [
                    "title" => "Service Category Default",
                    "name" => "service.category.default"
                ]
            ]
        ],
        [
            "title" => "Department Page",
            "name" => "frontend.cms.department",
            "template" => [
                [
                    "title" => "Department Default",
                    "name" => "department.default"
                ]
            ]
        ],
        [
            "title" => "Doctor List Page",
            "name" => "frontend.cms.doctor.list",
            "template" => [
                [
                    "title" => "Doctor List Default",
                    "name" => "doctor.default"
                ]
            ]
        ],
        [
            "title" => "Profile Page",
            "name" => "frontend.cms.user",
            "template" => [
                [
                    "title" => "Profile Default",
                    "name" => "user.index"
                ]
            ]
        ],
        [
            "title" => "Resource Page",
            "name" => "frontend.cms.resource",
            "template" => [
                [
                    "title" => "Resource Default",
                    "name" => "resource.default"
                ]
            ]
        ],
        [
            "title" => "Resource Category Page",
            "name" => "frontend.cms.resource_category",
            "template" => [
                [
                    "title" => "Resource Category Default",
                    "name" => "resource.category.default"
                ]
            ]
        ],
        [
            "title" => "Contact Page",
            "name" => "frontend.contact",
            "template" => [
                [
                    "title" => "Contact Page Default",
                    "name" => "contact.default"
                ]
            ]
        ],
        [
            "title" => "Cart Page",
            "name" => "frontend.order.cart",
            "template" => [
                [
                    "title" => "Cart Page Default",
                    "name" => "cart.default"
                ]
            ]
        ]
    ];

    
}
