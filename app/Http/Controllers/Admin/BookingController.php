<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\ContentService;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'bookings';
        $this->viewPart = 'admin.pages.bookings';
        $this->responseData['module_name'] = __('Booking Management');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $this->responseData['params'] = $params;
        $params['is_type'] = Consts::CONTACT_TYPE['contact'];
        if (isset($params['created_at_from'])) {
            $params['created_at_from'] = Carbon::createFromFormat('d/m/Y', $params['created_at_from'])->format('Y-m-d');
        }
        if (isset($params['created_at_to'])) {
            $params['created_at_to'] = Carbon::createFromFormat('d/m/Y', $params['created_at_to'])->format('Y-m-d');
        }
        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
        $paramTaxonomys['taxonomy'] = Consts::TAXONOMY['department'];
        $this->responseData['departments'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
        $params_doctor['status'] = Consts::POST_STATUS['active'];
        $params_doctor['is_type'] = Consts::POST_TYPE['doctor'];
        $doctors = ContentService::getCmsPost($params_doctor)->get();
        $this->responseData['doctors'] =  $doctors;
        // Get list with filter params
        $rows = ContentService::getBooking($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;

        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking $booking)
    {
        $paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
        $paramTaxonomys['taxonomy'] = Consts::TAXONOMY['department'];
        $this->responseData['departments'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
        $params_doctor['status'] = Consts::POST_STATUS['active'];
        $params_doctor['is_type'] = Consts::POST_TYPE['doctor'];
        $doctors = ContentService::getCmsPost($params_doctor)->get();
        $this->responseData['doctors'] =  $doctors;
        $this->responseData['detail'] = $booking;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'department_id' => 'required',
            'doctor_id' => 'required',
            'booking_date' => 'required'
        ]);
        $params = $request->all();

        $params['booking_date'] = ($request->get('booking_date')) ? Carbon::createFromFormat('d/m/Y', $request->get('booking_date')) : null;

        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $booking->fill($params);
        $booking->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }
}
