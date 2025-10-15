<?php

namespace App\Http\Controllers\FrontEnd;

use App\Consts;
use App\Http\Services\ContentService;
use App\Models\Contact;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($alias = null, Request $request)
    {
		
		//dd($alias);
		
		$params['status'] = Consts::TAXONOMY_STATUS['active'];
		$params['taxonomy'] = Consts::CATEGORY['contact'];
		$taxonomy = ContentService::getCmsTaxonomy($params)->first();
		
		$this->responseData['taxonomy'] = $taxonomy;
		
        return $this->responseView('frontend.pages.contact.index');
		
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		
        try {
            
            $params = $request->all();
			
			//dd($params);
			
            $params['status'] = 'new';
            
			if(trim($params['name']) !='' and trim($params['phone']) !=''){
				
				$contact = Contact::create($params);
				if (isset($this->web_information->information->email)) {
					$email = $this->web_information->information->email;
					Mail::send('frontend.emails.contact', ['contact' => $contact], function ($message) use ($email) {
						$message->to($email);
						$message->subject('Thông tin đăng ký website');
					});
				}

				echo "<script>window.location.href = '/';alert('Gửi liên hệ thành công!');</script>";
			}else{
				echo "<script>window.location.href = '/';alert('Please enter information!');</script>";
			}
			
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
