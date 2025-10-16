<?php
namespace App\Http\Services;
use App\Consts;
use App\Models\Booking;
use App\Models\CmsPost;
use App\Models\CmsProduct;
use App\Models\CmsTaxonomy;
use App\Models\Profile;
use App\Models\Contact;
use App\Models\Menu;
use App\Models\Option;
use App\Models\Order;
use App\Models\Albums;
use App\Models\CmsMedia;
use App\Models\CmsMediaVideo;
use App\Models\CmsMediaImage;
use App\Models\CmsPostImage;
use App\Models\CmsPostDocument;
use App\Models\OrderDetail;
use App\Models\Comment;
use App\Models\CmsDichvu;
use App\Models\CmsTag;
use App\Models\CmsPostDichvu;
use App\Models\BlockContent;
use App\Models\Page;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRole;
use App\Models\AdminMenu;
use App\Models\CmsLanguage;
use App\Models\CmsTranslate;
use App\Models\CmsAuthor;
use App\Models\Document;
use App\Models\ManagerShop;
use App\Models\Transaction;
use App\Models\CmsHistoryRechargeuser;
use App\Models\ListBill;
use App\Models\ImportBook;
use App\Models\ExportBook;
use App\Models\BookSize;
use App\Models\User;
use App\Models\Voucher;
use App\Models\CmsHistoryBuyebook;

class ContentService
{
    
    public static function getApiToken()
    {
        $data = Option::where('is_system_param', 0)->where('option_name','information')->first();
        $apitoken = '';
        if($data){
            $apitoken = json_decode($data->option_value)->apitoken ?? '';
        }
        return $apitoken;
    }

    public static function getHistoryBuyebook($params, $isPaginate = false)
    {
        $query = CmsHistoryBuyebook::selectRaw('tb_history_buyebook.*,
                    u.name as customer_name, 
                    d.title as title_document, d.brief as brief_document, d.alias as alias_document, d.image as image_document, d.number_page as number_page_document, d.view as view_document,
                    a.title as title_author, a.id as id_author,
                    e.title as title_package')
        ->leftJoin('users AS u', 'u.id', '=', 'tb_history_buyebook.customer_id')
        ->leftJoin('tb_document AS d', 'd.id', '=', 'tb_history_buyebook.document_id')
        ->leftJoin('tb_cms_author AS a', 'a.id', '=', 'd.main_author')
        ->leftJoin('tb_ebook_package AS e', 'e.id', '=', 'tb_history_buyebook.ebook_package_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_history_buyebook.buy_info', 'like', '%' . $keyword . '%');
                });
            });
			
		if(isset($params['status'])) {
            $query->where('tb_history_buyebook.status', $params['status']);
        }

        if(isset($params['author'])) {
            $query->where('d.main_author', $params['author']);
        }

        if(isset($params['customer_id'])) {
            $query->where('tb_history_buyebook.customer_id', $params['customer_id']);
        }

		if(isset($params['payment_method'])) {
            $query->where('tb_history_buyebook.payment_method', $params['payment_method']);
        }

        if(isset($params['from_date']) && isset($params['to_date'])) {
            $fromDate = date("Y-m-d", strtotime($params['from_date']));
            $toDate = date("Y-m-d", strtotime($params['to_date']));
            $query->where(function ($query) use ($fromDate, $toDate) {
                $query->whereDate('buy_date', '>=', $fromDate)
                      ->whereDate('buy_date', '<=', $toDate);
            });
        }

        $query->orderByRaw('tb_history_buyebook.id DESC');
        
        return $query;
    }
    
    public static function getCmsPostJson($params, $isPaginate = false)
    {
        $query = CmsPost::selectRaw('tb_cms_posts.*, tb_cms_taxonomys.url_part as taxonomy_url_part,admins.avatar as avatar,admins.name as fullname,
            b.name as admin_name, tb_cms_taxonomys.title AS taxonomy_json_title, tb_cms_taxonomys.taxonomy AS taxonomy, tb_cms_taxonomys.json_params AS taxonomy_json_params')
            ->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_cms_posts.taxonomy_id')
            ->leftJoin('admins', 'admins.id', '=', 'tb_cms_posts.admin_created_id')
            ->leftJoin('admins as b', 'b.id', '=', 'tb_cms_posts.admin_updated_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_cms_posts.title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.id', $params['id']);
            })
            ->when(!empty($params['different_id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.id', '!=', $params['different_id']);
            })
            ->when(!empty($params['taxonomy_id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.taxonomy_id',$params['taxonomy_id']);
            })
            ;

        if (!empty($params['is_type'])) {
            $query->where('tb_cms_posts.is_type', $params['is_type']);
        } else {
            $query->where('tb_cms_posts.is_type', Consts::POST_TYPE['post']);
        }
        if (!empty($params['status'])) {
            $query->where('tb_cms_posts.status', $params['status']);
        } else {
            $query->where('tb_cms_posts.status', "!=", Consts::STATUS_DELETE);
        }
        
        if (!empty($params['url_part'])) {
            $query->where('tb_cms_posts.url_part', $params['url_part']);
        }

        if (!empty($params['aproved_date'])) {
            $query->where('tb_cms_posts.aproved_date', '<=' , $params['aproved_date']);
        }

        if (isset($params['news_position'])) {
            $query->where('tb_cms_posts.news_position', $params['news_position']);
        }

        if (isset($params['different_news_position'])) {
            $query->where('tb_cms_posts.news_position','!=',$params['different_news_position']);
        }

        if (isset($params['admin_created_id'])) {
            $query->where('tb_cms_posts.admin_created_id', $params['admin_created_id']);
        }

        if (isset($params['admin_updated_id'])) {
            $query->where('tb_cms_posts.admin_updated_id', $params['admin_updated_id']);
        }

        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_posts.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_posts.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_posts.iorder ASC, tb_cms_posts.id DESC');
        }
        if (!empty($params['limit'])){
            $query->limit($params['limit']);
        }
        return $query;
    }
    
    public static function getVoucher($params, $isPaginate = false)
    {
        $query = Voucher::select('tb_voucher.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_voucher.code', 'like', '%' . $keyword . '%');
                });
            });
        
        if(isset($params['code'])) {
            $query->where('tb_voucher.code', $params['code']);
        }

        /*
        if(isset($params['start_date'])) {
            $query->whereRaw('DATE_FORMAT(tb_voucher.start_date,"%Y%m%d") >='.date('Ymd',strtotime($params['from_date'])));
        }
    
        if(isset($params['end_date'])) {
            $query->whereRaw('DATE_FORMAT(tb_voucher.end_date,"%Y%m%d")<='.date('Ymd',strtotime($params['to_date'])));
        }
        */
        if(isset($params['status'])) {
            $query->where('tb_voucher.status', $params['status']);
        }

        $query->orderByRaw('tb_voucher.id DESC');
        
        return $query;
    }
    
	public static function getCmsTag($params, $isPaginate = false)
    {
        $query = CmsTag::select('tb_cms_tag.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_cms_tag.title', 'like', '%' . $keyword . '%');
                });
            });
       
        $query->orderByRaw('tb_cms_tag.id DESC');
        
        return $query;
    }
	
	public static function getListBill($params, $isPaginate = false)
    {
        $query = ListBill::select('tb_list_bill.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_list_bill.title', 'like', '%' . $keyword . '%');
                });
            });
		if(isset($params['is_type'])) {
            $query->where('tb_list_bill.is_type', $params['is_type']);
        }
        $query->orderByRaw('tb_list_bill.id DESC');
        
        return $query;
    }

	public static function getBookSize($params, $isPaginate = false)
    {
        $query = BookSize::select('tb_book_size.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_book_size.width', 'like', '%' . $keyword . '%')
                    ->orWhere('tb_book_size.height', 'like', '%' . $keyword . '%');
                });
            });
        if(isset($params['width'])) {
            $query->where('tb_book_size.width', $params['width']);
        }
        if(isset($params['height'])) {
            $query->where('tb_book_size.height', $params['height']);
        }
        $query->orderByRaw('tb_book_size.width ASC');
        
        return $query;
    }
    
	public static function getTransaction($params, $isPaginate = false)
    {
        $query = Transaction::select('tb_transaction.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_transaction.guest', 'like', '%' . $keyword . '%')
					->orWhere('tb_transaction.order_code', 'like', '%' . $keyword . '%')
					->orWhere('tb_transaction.transaction_no', 'like', '%' . $keyword . '%');
                });
            });
			
		if(isset($params['status'])) {
            $query->where('tb_transaction.status', $params['status']);
        }

		if(isset($params['is_type'])) {
            $query->where('tb_transaction.is_type', $params['is_type']);
        }

        $query->orderByRaw('tb_transaction.id DESC');
        
        return $query;
    }

    public static function getHistoryRechargeuser($params, $isPaginate = false)
    {
        $query = CmsHistoryRechargeuser::select('tb_history_rechargeuser.*')
        ->selectRaw('u.name as customer_name')
        ->leftJoin('users AS u', 'u.id', '=', 'tb_history_rechargeuser.customer_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_history_rechargeuser.recharge_info', 'like', '%' . $keyword . '%');
                });
            });
			
		if(isset($params['status'])) {
            $query->where('tb_history_rechargeuser.status', $params['status']);
        }

        if(isset($params['customer_id'])) {
            $query->where('tb_history_rechargeuser.customer_id', $params['customer_id']);
        }

		if(isset($params['payment_method'])) {
            $query->where('tb_history_rechargeuser.payment_method', $params['payment_method']);
        }

        $query->orderByRaw('tb_history_rechargeuser.id DESC');
        
        return $query;
    }
	
	public static function getImportBook($params, $isPaginate = false)
    {
        $query = ImportBook::select('tb_import_book.*')
        ->selectRaw('u.name as customer_name, m.title as workshop_title')
        ->leftJoin('users AS u', 'u.id', '=', 'tb_import_book.customer_id')
        ->leftJoin('tb_manager_shop AS m', 'm.id', '=', 'tb_import_book.workshop')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_import_book.code', 'like', '%' . $keyword . '%');
                });
            });
		  
        if(isset($params['date_at'])) {
            $query->where('tb_import_book.date_at', $params['date_at']);
        }
	    
        if(isset($params['from_date'])) {
            $query->whereRaw('DATE_FORMAT(tb_import_book.date_at,"%Y%m%d") >='.date('Ymd',strtotime($params['from_date'])));
        }
    
        if(isset($params['to_date'])) {
            $query->whereRaw('DATE_FORMAT(tb_import_book.date_at,"%Y%m%d")<='.date('Ymd',strtotime($params['to_date'])));
        }

		if(isset($params['status'])) {
            $query->where('tb_import_book.status', $params['status']);
        }

		if(isset($params['bill_id'])) {
            $query->where('tb_import_book.bill_id', $params['bill_id']);
        }

		if(isset($params['bookshop'])) {
            $query->where('tb_import_book.bookshop', $params['bookshop']);
        }

		if(isset($params['workshop'])) {
            $query->where('tb_import_book.workshop', $params['workshop']);
        }

		if(isset($params['customer_id'])) {
            $query->where('tb_import_book.customer_id', $params['customer_id']);
        }
		
        $query->orderByRaw('tb_import_book.id DESC');
        
        return $query;
    }

    public static function getExportBook($params, $isPaginate = false)
    {
        $query = ExportBook::select('tb_export_book.*')
        ->selectRaw('u.name as customer_name, m.title as workshop_title')
        ->leftJoin('users AS u', 'u.id', '=', 'tb_export_book.customer_id')
        ->leftJoin('tb_manager_shop AS m', 'm.id', '=', 'tb_export_book.workshop')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_export_book.code', 'like', '%' . $keyword . '%');
                });
            });
          
        if(isset($params['date_at'])) {
            $query->where('tb_export_book.date_at', $params['date_at']);
        }
        
        if(isset($params['from_date'])) {
            $query->whereRaw('DATE_FORMAT(tb_export_book.date_at,"%Y%m%d") >='.date('Ymd',strtotime($params['from_date'])));
        }
    
        if(isset($params['to_date'])) {
            $query->whereRaw('DATE_FORMAT(tb_export_book.date_at,"%Y%m%d")<='.date('Ymd',strtotime($params['to_date'])));
        }

        if(isset($params['status'])) {
            $query->where('tb_export_book.status', $params['status']);
        }

        if(isset($params['bill_id'])) {
            $query->where('tb_export_book.bill_id', $params['bill_id']);
        }

        if(isset($params['bookshop'])) {
            $query->where('tb_export_book.bookshop', $params['bookshop']);
        }

        if(isset($params['workshop'])) {
            $query->where('tb_export_book.workshop', $params['workshop']);
        }

        if(isset($params['customer_id'])) {
            $query->where('tb_export_book.customer_id', $params['customer_id']);
        }
        
        $query->orderByRaw('tb_export_book.id DESC');
        
        return $query;
    }
    

	public static function getCmsAuthor($params, $isPaginate = false)
    {
        $query = CmsAuthor::select('tb_cms_author.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_cms_author.title', 'like', '%' . $keyword . '%');
                });
            });
       
        $query->orderByRaw('tb_cms_author.id DESC');
        
        return $query;
    }

	
	public static function getCmsTranslate($params, $isPaginate = false)
    {
        $query = CmsTranslate::select('tb_cms_translate.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_cms_translate.local', 'like', '%' . $keyword . '%');
                });
            });
       
        $query->orderByRaw('tb_cms_translate.id DESC');
        
        return $query;
    }
	
	
    public static function getManageShop($params, $isPaginate = false)
    {
        $query = ManagerShop::select('tb_manager_shop.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_manager_shop.title', 'like', '%' . $keyword . '%');
                });
            });
        if(isset($params['code'])) {
            $query->where('tb_manager_shop.code', $params['code']);
        }

        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_manager_shop.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_manager_shop.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_manager_shop.id asc');
        }
        

        return $query;
    }
	
    public static function getCmsLanguage($params, $isPaginate = false)
    {
        $query = CmsLanguage::select('tb_cms_language.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_cms_language.title', 'like', '%' . $keyword . '%')
                                ->orWhere('tb_cms_language.lang_code', 'like', '%' . $keyword . '%');
                });
            });
        if(isset($params['status'])) {
            $query->where('tb_cms_language.status', $params['status']);
        }

        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_language.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_language.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_language.is_default asc');
        }
        

        return $query;
    }
    public static function getMenu($params = [])
    {
        $query = Menu::select('tb_menus.*')
            ->selectRaw('count(b.id) AS sub')
            ->leftJoin('tb_menus AS b', 'tb_menus.id', '=', 'b.parent_id')
            ->groupBy('tb_menus.id')
            ->when(!empty($params['id']), function ($query) use ($params) {
                $query->where('tb_menus.id', '=', $params['id']);
            })
            ->when(!empty($params['parent_id']), function ($query) use ($params) {
                $query->where('tb_menus.parent_id', '=', $params['parent_id']);
            })
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                return $query->where(function ($where) use ($params) {
                    return $where->where('tb_menus.name', 'like', '%' . $params['keyword'] . '%');
                });
            });
        // Status delete
        if (!empty($params['status'])) {
            $query->where('tb_menus.status', $params['status']);
        } else {
            $query->where('tb_menus.status', "!=", Consts::STATUS_DELETE);
        }
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_menus.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_menus.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_menus.id desc');
        }

        return $query;
    }

	
    public static function getProducts($params)
    {
        $query = CmsProduct::select('tb_products.*')
            ->selectRaw('admins.name as admin_created, b.name as admin_updated, tb_cms_taxonomys.title as taxonomy_title, tb_cms_taxonomys.url_part as url_part')
			->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_products.taxonomy_id')
            ->leftJoin('admins', 'admins.id', '=', 'tb_products.admin_created_id')
			->leftJoin('admins as b', 'b.id', '=', 'tb_products.admin_updated_id')
			->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_products.title', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_products.mota', 'like', '%' . $keyword . '%')
						->orWhere('tb_products.mota', 'like', '%' . $keyword . '%')
						->orWhere('tb_products.meta_description', 'like', '%' . $keyword . '%');
                });
            });
			
			if (!empty($params['alias'])) {
				$query->where('tb_products.alias', $params['alias']);
			} 
			
			if (!empty($params['taxonomy_id'])) {
				$query->where('tb_products.taxonomy_id', $params['taxonomy_id']);
			} 
			
			if (!empty($params['different_id'])) {
				$query->where('tb_products.id','!=',$params['different_id']);
			}
			
			if (!empty($params['status'])) {
				$query->where('tb_products.status', $params['status']);
			}
			
			if (!empty($params['order_by'])) {
				if (is_array($params['order_by'])) {
					foreach ($params['order_by'] as $key => $value) {
						$query->orderBy('tb_products.' . $key, $value);
					}
				} else {
					$query->orderByRaw('tb_products.' . $params['order_by'] . ' desc');
				}
			} else {
				$query->orderByRaw('tb_products.id DESC');
			}

        return $query;
    }


    public static function getOption()
    {
        return Option::where('is_system_param', 0)->get();
    }

    public static function tinNoiBat($params, $isPaginate = false)
    {
        $query = CmsPost::selectRaw('tb_cms_posts.*, tb_cms_taxonomys.url_part as taxonomy_url_part,admins.avatar as avatar,admins.name as fullname,  tb_cms_taxonomys.title AS taxonomy_title, tb_cms_taxonomys.taxonomy AS taxonomy, tb_cms_taxonomys.json_params AS taxonomy_json_params')
            ->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_cms_posts.taxonomy_id')
            ->leftJoin('admins', 'admins.id', '=', 'tb_cms_posts.admin_created_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_cms_posts.title', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.id', $params['id']);
            })
            ->when(!empty($params['different_id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.id', '!=', $params['different_id']);
            })
            ->when(!empty($params['taxonomy_id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.taxonomy_id', $params['taxonomy_id']);
            })
            ;

        if (!empty($params['is_type'])) {
            $query->where('tb_cms_posts.is_type', $params['is_type']);
        } else {
            $query->where('tb_cms_posts.is_type', Consts::POST_TYPE['post']);
        }
        if (!empty($params['status'])) {
            $query->where('tb_cms_posts.status', $params['status']);
        } else {
            $query->where('tb_cms_posts.status', "!=", Consts::STATUS_DELETE);
        }

        if (isset($params['news_position'])) {
            $query->where('tb_cms_posts.news_position', $params['news_position']);
        }

        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_posts.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_posts.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_posts.iorder ASC, tb_cms_posts.id DESC');
        }

        $query->where('tb_cms_posts.aproved_date',"<=", date('Y-m-d H:i:s'));

        if (!empty($params['limit'])){
            $query->limit($params['limit']);
        }

        return $query;
    }

    public static function getPage($params = [])
    {
        $query = Page::select('tb_pages.*')
            ->when(!empty($params['id']), function ($query) use ($params) {
                $query->where('tb_pages.id', '=', $params['id']);
            })
            ->when(!empty($params['route_name']), function ($query) use ($params) {
                $query->where('tb_pages.route_name', '=', $params['route_name']);
            })
            ->when(!empty($params['alias']), function ($query) use ($params) {
                $query->where('tb_pages.alias', '=', $params['alias']);
            });
        // Status delete
        if (!empty($params['status'])) {
            $query->where('tb_pages.status', $params['status']);
        } else {
            $query->where('tb_pages.status', "!=", Consts::STATUS_DELETE);
        }
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_pages.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_pages.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_pages.iorder ASC, tb_pages.id desc');
        }

        return $query;
    }

    public static function getCmsProfile($params, $isPaginate = false)
    {
        $query = Profile::select('tb_cms_profile.*')
            ->selectRaw('b.title as title_taxonomy')
			->leftJoin('tb_cms_taxonomys AS b', 'tb_cms_profile.taxonomy', '=', 'b.id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_cms_profile.title', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['url_part']), function ($query) use ($params) {
                return $query->where('tb_cms_profile.url_part', $params['url_part']);
            });
        if(isset($params['status'])) {
            $query->where('tb_cms_profile.status', $params['status']);
        }
        if(isset($params['position'])) {
            $query->where('tb_cms_profile.position', $params['position']);
        }
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_profile.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_profile.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_profile.iorder ASC, tb_cms_profile.id DESC');
        }

        return $query;
    }

    public static function getAlbums($params, $isPaginate = false)
    {
        $query = Albums::select('tb_albums.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_albums.title', 'like', '%' . $keyword . '%');
                });
            });
        if(isset($params['status'])) {
            $query->where('tb_albums.status', $params['status']);
        }
        if(isset($params['vitri'])) {
            $query->where('tb_albums.vitri', $params['vitri']);
        }
       
        $query->orderByRaw('tb_albums.id DESC');
        

        return $query;
    }
 

    public static function getCmsTaxonomy($params, $isPaginate = false)
    {
        $query = CmsTaxonomy::select('tb_cms_taxonomys.*')
            ->selectRaw('count(b.id) AS sub,admins.name as fullname')
            ->leftJoin('tb_cms_taxonomys AS b', 'tb_cms_taxonomys.id', '=', 'b.parent_id')
            ->leftJoin('admins', 'admins.id', '=', 'tb_cms_taxonomys.admin_created_id')
            ->groupBy('tb_cms_taxonomys.id')
            
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_cms_taxonomys.title', 'like', '%' . $keyword . '%');
                        //->orWhere('tb_cms_taxonomys.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['taxonomy']), function ($query) use ($params) {
                return $query->where('tb_cms_taxonomys.taxonomy', $params['taxonomy']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_cms_taxonomys.id', $params['id']);
            })
            ->when(!empty($params['different_id']), function ($query) use ($params) {
                return $query->where('tb_cms_taxonomys.id', '!=', $params['different_id']);
            })
            ->when(!empty($params['news_position']), function ($query) use ($params) {
                return $query->where('tb_cms_taxonomys.news_position', $params['news_position']);
            })
            ->when(!empty($params['parent_id']), function ($query) use ($params) {
                return $query->where('tb_cms_taxonomys.parent_id', $params['parent_id']);
            })
            ->when(!empty($params['url_part']), function ($query) use ($params) {
                return $query->where('tb_cms_taxonomys.url_part', $params['url_part']);
            });
        if (!empty($params['status'])) {
            $query->where('tb_cms_taxonomys.status', $params['status']);
        } else {
            $query->where('tb_cms_taxonomys.status', "!=", Consts::STATUS_DELETE);
        }
		
		if (!empty($params['ids']) and is_array($params['ids'])) {
            $query->whereIn('tb_cms_taxonomys.id', $params['ids']);
        }
		
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_taxonomys.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_taxonomys.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_taxonomys.taxonomy, tb_cms_taxonomys.iorder ASC, tb_cms_taxonomys.id DESC');
        }

        return $query;
    }

    public static function getCmsDichvu($params, $isPaginate = false)
    {
        $query = CmsDichvu::select('tb_cms_dichvu.*')
            ->selectRaw('count(b.id) AS sub,admins.name as fullname, c.title as json_title')
            ->leftJoin('tb_cms_dichvu AS b', 'tb_cms_dichvu.id', '=', 'b.parent_id')
			->leftJoin('tb_cms_taxonomys AS c', 'c.id', '=', 'tb_cms_dichvu.taxonomy')
            ->leftJoin('admins', 'admins.id', '=', 'tb_cms_dichvu.admin_created_id')
            ->groupBy('tb_cms_dichvu.id')
            
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_cms_dichvu.title', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_cms_dichvu.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['taxonomy']), function ($query) use ($params) {
                return $query->where('tb_cms_dichvu.taxonomy', $params['taxonomy']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_cms_dichvu.id', $params['id']);
            })
            ->when(!empty($params['different_id']), function ($query) use ($params) {
                return $query->where('tb_cms_dichvu.id', '!=', $params['different_id']);
            })
            ->when(!empty($params['is_featured']), function ($query) use ($params) {
                return $query->where('tb_cms_dichvu.is_featured', $params['is_featured']);
            })
            ->when(!empty($params['news_position']), function ($query) use ($params) {
                return $query->where('tb_cms_dichvu.news_position', $params['news_position']);
            })
            ->when(!empty($params['parent_id']), function ($query) use ($params) {
                return $query->where('tb_cms_dichvu.parent_id', $params['parent_id']);
            })
            ->when(!empty($params['url_part']), function ($query) use ($params) {
                return $query->where('tb_cms_dichvu.url_part', $params['url_part']);
            });
        if (!empty($params['status'])) {
            $query->where('tb_cms_dichvu.status', $params['status']);
        } else {
            $query->where('tb_cms_dichvu.status', "!=", Consts::STATUS_DELETE);
        }
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_dichvu.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_dichvu.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_dichvu.taxonomy, tb_cms_dichvu.iorder ASC, tb_cms_dichvu.id DESC');
        }

        return $query;
    }

    public static function getCmsPost($params, $isPaginate = false)
    {
        $query = CmsPost::selectRaw('tb_cms_posts.*, tb_cms_taxonomys.url_part as taxonomy_url_part,admins.avatar as avatar,admins.name as fullname,
            b.name as admin_name, tb_cms_taxonomys.title AS taxonomy_title, tb_cms_taxonomys.taxonomy AS taxonomy, tb_cms_taxonomys.json_params AS taxonomy_json_params')
            ->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_cms_posts.taxonomy_id')
            ->leftJoin('admins', 'admins.id', '=', 'tb_cms_posts.admin_created_id')
            ->leftJoin('admins as b', 'b.id', '=', 'tb_cms_posts.admin_updated_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_cms_posts.title', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.id', $params['id']);
            })
            ->when(!empty($params['different_id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.id', '!=', $params['different_id']);
            })
            ->when(!empty($params['taxonomy_id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.taxonomy_id',$params['taxonomy_id']);
            })
            ;

        if (!empty($params['is_type'])) {
            $query->where('tb_cms_posts.is_type', $params['is_type']);
        } else {
            $query->where('tb_cms_posts.is_type', Consts::POST_TYPE['post']);
        }
        if (!empty($params['status'])) {
            $query->where('tb_cms_posts.status', $params['status']);
        } else {
            $query->where('tb_cms_posts.status', "!=", Consts::STATUS_DELETE);
        }
        
        if (!empty($params['url_part'])) {
            $query->where('tb_cms_posts.url_part', $params['url_part']);
        }

        if (!empty($params['aproved_date'])) {
            $query->where('tb_cms_posts.aproved_date', '<=' , $params['aproved_date']);
        }

        if (isset($params['news_position'])) {
            $query->where('tb_cms_posts.news_position', $params['news_position']);
        }

        if (isset($params['different_news_position'])) {
            $query->where('tb_cms_posts.news_position','!=',$params['different_news_position']);
        }

        if (isset($params['admin_created_id'])) {
            $query->where('tb_cms_posts.admin_created_id', $params['admin_created_id']);
        }

        if (isset($params['admin_updated_id'])) {
            $query->where('tb_cms_posts.admin_updated_id', $params['admin_updated_id']);
        }

        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_posts.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_posts.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_posts.iorder ASC, tb_cms_posts.id DESC');
        }
        if (!empty($params['limit'])){
            $query->limit($params['limit']);
        }
        return $query;
    }

    public static function getCmsMedia($params, $isPaginate = false)
    {
        $query = CmsMedia::selectRaw('tb_cms_media.*, tb_cms_taxonomys.url_part as taxonomy_url_part,admins.avatar as avatar,admins.name as fullname,
            b.name as admin_name, tb_cms_taxonomys.title AS taxonomy_title, tb_cms_taxonomys.taxonomy AS taxonomy, tb_cms_taxonomys.json_params AS taxonomy_json_params')
            ->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_cms_media.taxonomy_id')
            ->leftJoin('admins', 'admins.id', '=', 'tb_cms_media.admin_created_id')
            ->leftJoin('admins as b', 'b.id', '=', 'tb_cms_media.admin_updated_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_cms_media.title', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_cms_media.id', $params['id']);
            })
            ->when(!empty($params['different_id']), function ($query) use ($params) {
                return $query->where('tb_cms_media.id', '!=', $params['different_id']);
            })
            ->when(!empty($params['taxonomy_id']), function ($query) use ($params) {
                return $query->where('tb_cms_media.taxonomy_id',$params['taxonomy_id']);
            })
            ;

        if (!empty($params['is_type'])) {
            $query->where('tb_cms_media.is_type', $params['is_type']);
        } else {
            $query->where('tb_cms_media.is_type', Consts::POST_TYPE['post']);
        }
        if (!empty($params['status'])) {
            $query->where('tb_cms_media.status', $params['status']);
        } else {
            $query->where('tb_cms_media.status', "!=", Consts::STATUS_DELETE);
        }
        
        if (!empty($params['url_part'])) {
            $query->where('tb_cms_media.url_part', $params['url_part']);
        }

        if (!empty($params['aproved_date'])) {
            $query->where('tb_cms_media.aproved_date', '<=' , $params['aproved_date']);
        }

        if (isset($params['news_position'])) {
            $query->where('tb_cms_media.news_position', $params['news_position']);
        }

        if (isset($params['different_news_position'])) {
            $query->where('tb_cms_media.news_position','!=',$params['different_news_position']);
        }

        if (isset($params['admin_created_id'])) {
            $query->where('tb_cms_media.admin_created_id', $params['admin_created_id']);
        }

        if (isset($params['admin_updated_id'])) {
            $query->where('tb_cms_media.admin_updated_id', $params['admin_updated_id']);
        }

        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_media.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_media.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_media.iorder ASC, tb_cms_media.id DESC');
        }
        if (!empty($params['limit'])){
            $query->limit($params['limit']);
        }
        return $query;
    }


    public static function getCmsPostDichvu($params, $isPaginate = false)
    {
        $query = CmsPostDichvu::selectRaw('tb_cms_posts_dichvu.*, tb_cms_taxonomys.url_part as taxonomy_url_part,admins.avatar as avatar,admins.name as fullname,
            b.name as admin_name, tb_cms_taxonomys.title AS taxonomy_title, tb_cms_taxonomys.taxonomy AS taxonomy, tb_cms_taxonomys.json_params AS taxonomy_json_params')
            ->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_cms_posts_dichvu.taxonomy_id')
            ->leftJoin('admins', 'admins.id', '=', 'tb_cms_posts_dichvu.admin_created_id')
            ->leftJoin('admins as b', 'b.id', '=', 'tb_cms_posts_dichvu.admin_updated_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_cms_posts_dichvu.title', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts_dichvu.id', $params['id']);
            })
            ->when(!empty($params['different_id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts_dichvu.id', '!=', $params['different_id']);
            })
            ->when(!empty($params['taxonomy_id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts_dichvu.taxonomy_id',$params['taxonomy_id']);
            })
            ;

        if (!empty($params['is_type'])) {
            $query->where('tb_cms_posts_dichvu.is_type', $params['is_type']);
        } else {
            $query->where('tb_cms_posts_dichvu.is_type', Consts::POST_TYPE['post']);
        }
        if (!empty($params['status'])) {
            $query->where('tb_cms_posts_dichvu.status', $params['status']);
        } else {
            $query->where('tb_cms_posts_dichvu.status', "!=", Consts::STATUS_DELETE);
        }
        
        if (!empty($params['url_part'])) {
            $query->where('tb_cms_posts_dichvu.url_part', $params['url_part']);
        }

        if (!empty($params['aproved_date'])) {
            $query->where('tb_cms_posts_dichvu.aproved_date', '<=' , $params['aproved_date']);
        }

        if (isset($params['news_position'])) {
            $query->where('tb_cms_posts_dichvu.news_position', $params['news_position']);
        }

        if (isset($params['different_news_position'])) {
            $query->where('tb_cms_posts_dichvu.news_position','!=',$params['different_news_position']);
        }

        if (isset($params['admin_created_id'])) {
            $query->where('tb_cms_posts_dichvu.admin_created_id', $params['admin_created_id']);
        }

        if (isset($params['admin_updated_id'])) {
            $query->where('tb_cms_posts_dichvu.admin_updated_id', $params['admin_updated_id']);
        }

        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_posts_dichvu.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_posts_dichvu.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_posts_dichvu.iorder ASC, tb_cms_posts_dichvu.id DESC');
        }
        if (!empty($params['limit'])){
            $query->limit($params['limit']);
        }
        return $query;
    }

    public static function getDocument($params, $isPaginate = false)
    {
        $query = Document::selectRaw('tb_document.*, tb_cms_taxonomys.url_part as taxonomy_url_part, tb_cms_taxonomys.title AS taxonomy_title, tb_cms_taxonomys.taxonomy AS taxonomy, tb_cms_taxonomys.json_params AS taxonomy_json_params, tb_cms_author.title as author_name, CONCAT(tb_book_size.width,"x",tb_book_size.height) as book_size_title,tb_book_size.width as width_size, tb_book_size.height as height_size,tb_manager_shop.title as publisher_title')
		->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_document.category')
		->leftJoin('tb_cms_author', 'tb_cms_author.id', '=', 'tb_document.main_author')
        ->leftJoin('tb_book_size', 'tb_book_size.id', '=', 'tb_document.book_size')
        ->leftJoin('tb_manager_shop', 'tb_manager_shop.id', '=', 'tb_document.publisher')

		->when(isset($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_document.title', 'like', '%' . $keyword . '%');
                });
            });
        if (isset($params['status'])) {
            $query->where('tb_document.status', $params['status']);
        } else {
            $query->where('tb_document.status', '!=', Consts::STATUS_DELETE);
        }
        
		if (isset($params['alias'])) {
			$query->where('tb_document.alias', $params['alias']);
		}

        if (isset($params['id'])) {
			$query->where('tb_document.id', $params['id']);
		}

        if (isset($params['filepdf'])) {
			$query->where('tb_document.filepdf', $params['filepdf']);
		}

        if (isset($params['main_author'])) {
			$query->where('tb_document.main_author', $params['main_author']);
		}
		
		if (isset($params['authors'])) {
			$query->where('tb_document.authors', 'like', '%,'.$params['authors'].',%');
		}
		
		if (isset($params['tags'])) {
			$query->where('tb_document.tags', 'like', '%,'.$params['tags'].',%');
		}
		
		// if (isset($params['hienthi'])) {
        //     $query->where('tb_document.hienthi', $params['hienthi']);
        // }
		
        if (isset($params['hienthi'])) {
			$query->where('tb_document.hienthi', 'like', '%,'.$params['hienthi'].',%');
		}

		if (isset($params['relative_id'])) {
			$query->where('tb_document.id','!=',$params['relative_id']);
		}
		
		if (isset($params['category'])) {
            $query->where('tb_document.category', $params['category']);
        }
		
		if (isset($params['array_author'])) {
            if (is_array($params['array_author'])) {
                foreach ($params['array_author'] as $key => $value) {
                    if($key == 0){
						$query->where('tb_document.authors', 'like', '%,'.$value.',%');
					}else{
						$query->orWhere('tb_document.authors', 'like', '%,'.$value.',%');
					}
                }
            }
        }
		
		if (isset($params['array_tag'])) {
            if (is_array($params['array_tag'])) {
                foreach ($params['array_tag'] as $key => $value) {
                    if($key == 0){
						$query->where('tb_document.tags', 'like', '%,'.$value.',%');
					}else{
						$query->orWhere('tb_document.tags', 'like', '%,'.$value.',%');
					}
                }
            }
        }
		
        if (isset($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_document.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_document.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_document.created_at DESC, tb_document.id DESC');
        }
        if (isset($params['limit'])){
            $query->limit($params['limit']);
        }
		// dd($query);
        return $query;
    }

    public static function getSearchDocument($params, $isPaginate = false)
    {
        $query = Document::selectRaw('tb_document.*, 
            tb_cms_taxonomys.url_part as taxonomy_url_part, tb_cms_taxonomys.title AS taxonomy_title, tb_cms_taxonomys.taxonomy AS taxonomy, tb_cms_taxonomys.json_params AS taxonomy_json_params,
            tb_manager_shop.title as publisher_title')
		->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_document.category')
        ->leftJoin('tb_manager_shop', 'tb_manager_shop.id', '=', 'tb_document.publisher')
		->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_document.title', 'like', '%' . $keyword . '%');
                });
            });
        
		if (!empty($params['alias'])) {
			$query->where('tb_document.alias', $params['alias']);
		}

        if (!empty($params['id'])) {
			$query->where('tb_document.id', $params['id']);
		}

        if (!empty($params['filepdf'])) {
			$query->where('tb_document.filepdf', $params['filepdf']);
		}
		
		if (!empty($params['authors'])) {
			$query->where('tb_document.authors', 'like', '%,'.$params['authors'].',%');
		}
		
		if (!empty($params['tags'])) {
			$query->where('tb_document.tags', 'like', '%,'.$params['tags'].',%');
		}
		
        if (isset($params['hienthi']) and $params['hienthi'] !=='') {
            //dd($params['hienthi']);
            $query->where('tb_document.hienthi', 'like', '%,'.$params['hienthi'].',%');
        }

        if (isset($params['status']) and $params['status'] !=='') {
            //dd($params['status']);
            $query->where('tb_document.status', $params['status']);
        }
        
		if (!empty($params['relative_id'])) {
			$query->where('tb_document.id','!=',$params['relative_id']);
		}
		
		if (!empty($params['category'])) {
            $query->where('tb_document.category', $params['category']);
        }
		
		if (!empty($params['array_author'])) {
            if (is_array($params['array_author'])) {
                foreach ($params['array_author'] as $key => $value) {
                    if($key == 0){
						$query->where('tb_document.authors', 'like', '%,'.$value.',%');
					}else{
						$query->orWhere('tb_document.authors', 'like', '%,'.$value.',%');
					}
                }
            }
        }
		
		if (!empty($params['array_tag'])) {
            if (is_array($params['array_tag'])) {
                foreach ($params['array_tag'] as $key => $value) {
                    if($key == 0){
						$query->where('tb_document.tags', 'like', '%,'.$value.',%');
					}else{
						$query->orWhere('tb_document.tags', 'like', '%,'.$value.',%');
					}
                }
            }
        }
		
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_document.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_document.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_document.created_at DESC, tb_document.id DESC');
        }
        if (!empty($params['limit'])){
            $query->limit($params['limit']);
        }
		//dd($query);
        return $query;
    }

    public static function getCmsPostImage($params, $isPaginate = false)
    {
        $query = CmsPostImage::selectRaw('tb_post_image.*')
            ->leftJoin('tb_cms_posts', 'tb_cms_posts.id', '=', 'tb_post_image.post_id')

            ->when(!empty($params['post_id']), function ($query) use ($params) {
                return $query->where('tb_post_image.post_id',$params['post_id']);
            })
            ;
        if (!empty($params['status'])) {
            $query->where('tb_post_image.status', $params['status']);
        } else {
            $query->where('tb_post_image.status', "!=", Consts::STATUS_DELETE);
        }
        
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_post_image.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_post_image.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_post_image.created_at DESC, tb_post_image.id DESC');
        }
        if (!empty($params['limit'])){
            $query->limit($params['limit']);
        }
        return $query;
    }

    public static function getCmsMediaVideo($params, $isPaginate = false)
    {
        $query = CmsMediaVideo::selectRaw('tb_media_video.*')
            ->leftJoin('tb_cms_media', 'tb_cms_media.id', '=', 'tb_media_video.media_id')

            ->when(!empty($params['media_id']), function ($query) use ($params) {
                return $query->where('tb_media_video.media_id',$params['media_id']);
            })
            ;
        if (!empty($params['status'])) {
            $query->where('tb_media_video.status', $params['status']);
        } else {
            $query->where('tb_media_video.status', "!=", Consts::STATUS_DELETE);
        }
        
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_media_video.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_media_video.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_media_video.created_at DESC, tb_media_video.id DESC');
        }
        if (!empty($params['limit'])){
            $query->limit($params['limit']);
        }
        return $query;
    }

    public static function getCmsMediaImage($params, $isPaginate = false)
    {
        $query = CmsMediaImage::selectRaw('tb_media_image.*')
            ->leftJoin('tb_cms_media', 'tb_cms_media.id', '=', 'tb_media_image.media_id')

            ->when(!empty($params['media_id']), function ($query) use ($params) {
                return $query->where('tb_media_image.media_id',$params['media_id']);
            })
            ;
        if (!empty($params['status'])) {
            $query->where('tb_media_image.status', $params['status']);
        } else {
            $query->where('tb_media_image.status', "!=", Consts::STATUS_DELETE);
        }
        
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_media_image.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_media_image.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_media_image.created_at DESC, tb_media_image.id DESC');
        }
        if (!empty($params['limit'])){
            $query->limit($params['limit']);
        }
        return $query;
    }

    // Lấy tin liên quan
    public static function getCmsPostRelative($params, $isPaginate = false)
    {
        $query = CmsPost::selectRaw('tb_cms_posts.* ,admins.avatar as avatar,admins.name as fullname, tb_cms_taxonomys.title AS taxonomy_title, tb_cms_taxonomys.taxonomy AS taxonomy, tb_cms_taxonomys.json_params AS taxonomy_json_params')
            ->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_cms_posts.taxonomy_id')
            ->leftJoin('admins', 'admins.id', '=', 'tb_cms_posts.admin_created_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_cms_posts.title', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.id','!=',$params['id']);
            })
            ->when(!empty($params['taxonomy_id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.taxonomy_id', $params['taxonomy_id']);
                // return $query->where('tb_cms_posts.category','LIKE' ,'%,'.$params['taxonomy_id'].',%');
            })
            ;

        if (!empty($params['is_type'])) {
            $query->where('tb_cms_posts.is_type', $params['is_type']);
        } else {
            $query->where('tb_cms_posts.is_type', Consts::POST_TYPE['post']);
        }
        if (isset($params['post_id']) and is_array($params['post_id'])) {
            $query->whereIn('tb_cms_posts.id',$params['post_id']);
        }
        if (!empty($params['status'])) {
            $query->where('tb_cms_posts.status', $params['status']);
        } else {
            $query->where('tb_cms_posts.status', "!=", Consts::STATUS_DELETE);
        }

        if (isset($params['news_position'])) {
            $query->where('tb_cms_posts.news_position','>',$params['news_position']);
        }

        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_posts.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_posts.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_posts.aproved_date DESC, tb_cms_posts.id DESC');
        }
        if (!empty($params['limit'])){
            $query->limit($params['limit']);
        }
        return $query;
    }

    public static function getCmsMediaRelative($params, $isPaginate = false)
    {
        $query = CmsMedia::selectRaw('tb_cms_media.* ,admins.avatar as avatar,admins.name as fullname, tb_cms_taxonomys.title AS taxonomy_title, tb_cms_taxonomys.taxonomy AS taxonomy, tb_cms_taxonomys.json_params AS taxonomy_json_params')
            ->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_cms_media.taxonomy_id')
            ->leftJoin('admins', 'admins.id', '=', 'tb_cms_media.admin_created_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_cms_media.title', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_cms_media.id','!=',$params['id']);
            })
            ->when(!empty($params['taxonomy_id']), function ($query) use ($params) {
                return $query->where('tb_cms_media.taxonomy_id', $params['taxonomy_id']);
            })
            ;

        if (!empty($params['is_type'])) {
            $query->where('tb_cms_media.is_type', $params['is_type']);
        } else {
            $query->where('tb_cms_media.is_type', Consts::POST_TYPE['post']);
        }
        if (isset($params['media_id']) and is_array($params['media_id'])) {
            $query->whereIn('tb_cms_media.id',$params['media_id']);
        }
        if (!empty($params['status'])) {
            $query->where('tb_cms_media.status', $params['status']);
        } else {
            $query->where('tb_cms_media.status', "!=", Consts::STATUS_DELETE);
        }

        if (isset($params['news_position'])) {
            $query->where('tb_cms_media.news_position','>',$params['news_position']);
        }

        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_media.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_media.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_media.aproved_date DESC, tb_cms_media.id DESC');
        }
        if (!empty($params['limit'])){
            $query->limit($params['limit']);
        }
        return $query;
    }

    public static function getCmsProfileRelative($params, $isPaginate = false)
    {
        $query = Profile::select('tb_cms_profile.*')
            
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_cms_profile.title', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_cms_profile.id','!=',$params['id']);
            })
            ->when(!empty($params['taxonomy']), function ($query) use ($params) {
                return $query->where('tb_cms_profile.taxonomy', $params['taxonomy']);
            })
            ;
        if(isset($params['status'])) {
            $query->where('tb_cms_profile.status', $params['status']);
        }
        if(isset($params['position'])) {
            $query->where('tb_cms_profile.position', $params['position']);
        }
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_profile.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_profile.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_profile.iorder ASC, tb_cms_profile.id DESC');
        }

        return $query;
    }

    public static function getCmsPostTag($params, $isPaginate = false)
    {
        $query = CmsPost::selectRaw('tb_cms_posts.*, tb_cms_taxonomys.url_part as taxonomy_url_part ,admins.avatar as avatar,admins.name as fullname, tb_cms_taxonomys.title AS taxonomy_title, tb_cms_taxonomys.taxonomy AS taxonomy, tb_cms_taxonomys.json_params AS taxonomy_json_params')
            ->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_cms_posts.taxonomy_id')
            ->leftJoin('admins', 'admins.id', '=', 'tb_cms_posts.admin_created_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_cms_posts.title', 'like', '%' . $keyword . '%')
                        //->orWhere('tb_cms_posts.json_params->title->vi', 'like', '%' . $keyword . '%')
                        ;
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.id', $params['id']);
            })
            ->when(!empty($params['different_id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.id', '!=', $params['different_id']);
            })
            ->when(!empty($params['taxonomy_id']), function ($query) use ($params) {
                //return $query->where('tb_cms_posts.taxonomy_id', $params['taxonomy_id']);
                return $query->where('tb_cms_posts.cms_tag','LIKE' ,'%,'.$params['taxonomy_id'].',%');
            })
            ;

        if (!empty($params['is_type'])) {
            $query->where('tb_cms_posts.is_type', $params['is_type']);
        } else {
            $query->where('tb_cms_posts.is_type', Consts::POST_TYPE['post']);
        }
        if (!empty($params['status'])) {
            $query->where('tb_cms_posts.status', $params['status']);
        } else {
            $query->where('tb_cms_posts.status', "!=", Consts::STATUS_DELETE);
        }

        if (isset($params['news_position'])) {
            $query->where('tb_cms_posts.news_position', $params['news_position']);
        }

        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_posts.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_posts.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_posts.iorder ASC, tb_cms_posts.id DESC');
        }
        if (!empty($params['limit'])){
            $query->limit($params['limit']);
        }
        return $query;
    }

    public static function getContact($params)
    {
        $query = Contact::select('tb_contacts.*')
            ->selectRaw('tb_cms_dichvu.title AS department')
            ->leftJoin('tb_cms_dichvu', 'tb_cms_dichvu.id', '=', 'tb_contacts.department_id')

            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_contacts.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_contacts.email', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_contacts.phone', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['department_id']), function ($query) use ($params) {
                $query->where('tb_contacts.department_id', '=', $params['department_id']);
            })
            ->when(!empty($params['is_type']), function ($query) use ($params) {
                return $query->where('tb_contacts.is_type', $params['is_type']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_contacts.status', $params['status']);
            })
            ->when(!empty($params['created_at_from']), function ($query) use ($params) {
                $query->where('tb_contacts.created_at', '>=', $params['created_at_from']);
            })
            ->when(!empty($params['created_at_to']), function ($query) use ($params) {
                $query->where('tb_contacts.created_at', '<=', $params['created_at_to']);
            });

        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_contacts.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_contacts.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_contacts.is_type ASC, tb_contacts.id DESC');
        }

        return $query;
    }

    public static function getBooking($params)
    {
        $query = Booking::select('tb_bookings.*')
            ->selectRaw('tb_cms_taxonomys.title AS department')
            ->selectRaw('tb_cms_posts.title AS doctor')
            ->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_bookings.department_id')
            ->leftJoin('tb_cms_posts', 'tb_cms_posts.id', '=', 'tb_bookings.doctor_id')

            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_bookings.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_bookings.email', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_bookings.phone', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['department_id']), function ($query) use ($params) {
                $query->where('tb_bookings.department_id', '=', $params['department_id']);
            })
            ->when(!empty($params['doctor_id']), function ($query) use ($params) {
                return $query->where('tb_bookings.doctor_id', $params['doctor_id']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_bookings.status', $params['status']);
            })
            ->when(!empty($params['created_at_from']), function ($query) use ($params) {
                $query->where('tb_bookings.booking_date', '>=', $params['created_at_from']);
            })
            ->when(!empty($params['created_at_to']), function ($query) use ($params) {
                $query->where('tb_bookings.booking_date', '<=', $params['created_at_to']);
            });

        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_bookings.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_bookings.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_bookings.id DESC');
        }

        return $query;
    }


	public static function getOrders($params)
    {
        $query = Order::select('tb_orders.*')
            // ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(tb_order_details.json_params, '$.post_link')) as post_link")
            // ->join('tb_order_details', 'tb_order_details.order_id', '=', 'tb_orders.id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_orders.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_orders.email', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_orders.phone', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['is_type']), function ($query) use ($params) {
                return $query->where('tb_orders.is_type', $params['is_type']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_orders.status', $params['status']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_orders.id', $params['id']);
            })
            ->when(!empty($params['created_at_from']), function ($query) use ($params) {
                $query->where('tb_orders.created_at', '>=', $params['created_at_from']);
            })
            ->when(!empty($params['created_at_to']), function ($query) use ($params) {
                $query->where('tb_orders.created_at', '<=', $params['created_at_to']);
            });
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_orders.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_orders.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_orders.id DESC, tb_orders.status ASC');
        }

        return $query;
    }

    public static function getOrderService($params)
    {
        $query = Order::select('tb_orders.*')
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(tb_order_details.json_params, '$.post_link')) as post_link")
            ->selectRaw('tb_cms_posts.title AS post_title')
            ->join('tb_order_details', 'tb_order_details.order_id', '=', 'tb_orders.id')
            ->join('tb_cms_posts', 'tb_cms_posts.id', '=', 'tb_order_details.item_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_orders.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_orders.email', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_orders.phone', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['is_type']), function ($query) use ($params) {
                return $query->where('tb_orders.is_type', $params['is_type']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_orders.status', $params['status']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_orders.id', $params['id']);
            })
            ->when(!empty($params['created_at_from']), function ($query) use ($params) {
                $query->where('tb_orders.created_at', '>=', $params['created_at_from']);
            })
            ->when(!empty($params['created_at_to']), function ($query) use ($params) {
                $query->where('tb_orders.created_at', '<=', $params['created_at_to']);
            });
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_orders.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_orders.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_orders.id DESC, tb_orders.status ASC');
        }

        return $query;
    }

    public static function getOrderProduct($params)
    {
        $query = Order::select('tb_orders.*')
            ->selectRaw('SUM(tb_order_details.quantity) AS total_quantity, SUM(tb_order_details.quantity * tb_order_details.price) AS total_money')
            ->leftJoin('tb_order_details', 'tb_order_details.order_id', '=', 'tb_orders.id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_orders.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_orders.email', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_orders.phone', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['is_type']), function ($query) use ($params) {
                return $query->where('tb_orders.is_type', $params['is_type']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_orders.status', $params['status']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_orders.id', $params['id']);
            })
            ->when(!empty($params['created_at_from']), function ($query) use ($params) {
                $query->where('tb_orders.created_at', '>=', $params['created_at_from']);
            })
            ->when(!empty($params['created_at_to']), function ($query) use ($params) {
                $query->where('tb_orders.created_at', '<=', $params['created_at_to']);
            });
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_orders.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_orders.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_orders.id DESC, tb_orders.status ASC');
        }

        $query->groupBy('tb_orders.id');

        return $query;
    }

    public static function getOrderDetail($params)
    {
        $query = OrderDetail::select('tb_order_details.*')
            ->selectRaw('tb_document.title AS post_title, tb_document.image as post_image')
            ->leftJoin('tb_document', 'tb_document.id', '=', 'tb_order_details.item_id')
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_order_details.status', $params['status']);
            })
            ->when(!empty($params['order_id']), function ($query) use ($params) {
                return $query->where('tb_order_details.order_id', $params['order_id']);
            });
            
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_order_details.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_order_details.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_order_details.id DESC');
        }

        return $query;
    }
    
    public static function getCmsPostLoading($params,$articles)
    {
        $query = CmsPost::selectRaw('tb_cms_posts.* ,admins.avatar as avatar,admins.name as fullname, tb_cms_taxonomys.title AS taxonomy_title, tb_cms_taxonomys.taxonomy AS taxonomy, tb_cms_taxonomys.json_params AS taxonomy_json_params')
            ->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_cms_posts.taxonomy_id')
            ->leftJoin('admins', 'admins.id', '=', 'tb_cms_posts.admin_created_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_cms_posts.title', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_cms_posts.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.id', $params['id']);
            })
            ->when(!empty($params['different_id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.id', '!=', $params['different_id']);
            })
            ->when(!empty($params['taxonomy_id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.taxonomy_id', $params['taxonomy_id']);
                //return $query->where('tb_cms_posts.category','LIKE' ,','.$params['taxonomy_id'].',');
            })
            ->when(!empty($params['is_featured']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.is_featured', $params['is_featured']);
            });

        if (!empty($params['is_type'])) {
            $query->where('tb_cms_posts.is_type', $params['is_type']);
        } else {
            $query->where('tb_cms_posts.is_type', Consts::POST_TYPE['post']);
        }
        if (!empty($params['status'])) {
            $query->where('tb_cms_posts.status', $params['status']);
        } else {
            $query->where('tb_cms_posts.status', "!=", Consts::STATUS_DELETE);
        }

        if (!empty($params['news_position'])) {
            $query->where('tb_cms_posts.news_position', $params['news_position']);
        }

        $query->where('tb_cms_posts.aproved_date','<=',date('Y-m-d H:i:s'));

        $query->whereRaw('tb_cms_posts.id NOT IN ('.$articles.')');
        
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_posts.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_posts.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_posts.iorder ASC, tb_cms_posts.id DESC');
        }
        if (!empty($params['limit'])){
            $query->limit($params['limit']);
        }
        return $query;
    }
	
    public static function getUsers($params)
    {
        $query = User::select('users.*')
			->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('users.username', 'like', '%' . $keyword . '%')
                        ->orWhere('users.name', 'like', '%' . $keyword . '%')
						->orWhere('users.phone', 'like', '%' . $keyword . '%');
                });
            })
		;
        
        if (!empty($params['status'])) {
            $query->where('users.status', $params['status']);
        }
		
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('users.' . $key, $value);
                }
            } else {
                $query->orderByRaw('users.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('users.id DESC');
        }

        return $query;
    }
	
	public static function getComment($params)
    {
        $query = Comment::select('tb_post_comment.*')
            ->selectRaw('users.name as comment_name, users.avatar')
            ->leftJoin('users', 'users.id', '=', 'tb_post_comment.user_id');
        
        $query->where('tb_post_comment.post_id', $params['post_id']);
        $query->where('tb_post_comment.status', 0);
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_post_comment.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_post_comment.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_post_comment.id DESC');
        }

        return $query;
    }

    public static function getBlockContentByParams($params)
    {
        $query = BlockContent::select('tb_block_contents.*');
            
        $query->where('tb_block_contents.status', 'active');
		
		if (!empty($params['block_code'])) {
            $query->where('tb_block_contents.block_code', $params['block_code']);
        }
		
        $query->orderByRaw('tb_block_contents.iorder asc');

        return $query;
    }

    public static function postTime($date_at){
        
        $first_date = time();
        $secon_date = strtotime($date_at);
        $diff = abs($first_date - $secon_date);
        $phut = $diff / 24;
        
        if($phut >= 60){
            $gio = floor($phut/60);
            if($gio < 10){
                $hienthingay = floor($gio).' giờ trước';
            }else{
                $hienthingay = date('H:i d/m/Y',strtotime($date_at));
            }
        }else{
            $hienthingay = floor($phut).' phút trước';
        }
        return $hienthingay;
    }

    public static function checkRole($routeDefault,$function){
        
        if(Auth::guard('admin')->user()->is_super_admin == 1){
           return true;
        }else{
            //dd($routeDefault);
            //$url_full = url()->full();
            //$routeDefault = str_replace(Consts::url_admin,'',$url_full);

            $menu_admin = AdminMenu::where('url_link',$routeDefault)->first();
            
            $role_id = Auth::guard('admin')->user()->role;
            $menu_id = '';
            if($menu_admin){
                $menu_id = $menu_admin->id;
            }
            
            $check_role = UserRole::where('role_id',$role_id)->first();

            $array_role_action = (array)$check_role->json_action;
            
            if(isset($array_role_action[$menu_id])){
                $arrayRoleAction = $array_role_action[$menu_id];
            }else{
                $arrayRoleAction = array();
            }
            $check_true = 0;
            foreach($arrayRoleAction as $checkAction){
                if($checkAction == $function){
                    $check_true = 1;
                }
            }
            return $check_true;
        }
        
    }

	public static function stringTruncate($string, $length = 100, $etc = '...', $break_words = false, $middle = false) {

		if ($length == 0) {
			return '';
		}

		$charset = 'UTF-8';
		$_UTF8_MODIFIER = 'u';

		if (mb_strlen ( $string, $charset ) > $length) {
			$length -= min ( $length, mb_strlen ( $etc, $charset ) );
			if (! $break_words && ! $middle) {
				$string = preg_replace ( '/\s+?(\S+)?$/' . $_UTF8_MODIFIER, '', mb_substr ( $string, 0, $length + 1, $charset ) );
			}
			if (! $middle) {
				return mb_substr ( $string, 0, $length, $charset ) . $etc;
			}

			return mb_substr ( $string, 0, $length / 2, $charset ) . $etc . mb_substr ( $string, - $length / 2, $length, $charset );
		}

		return $string;
	}
	

}
