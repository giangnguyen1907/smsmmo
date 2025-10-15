<?php

namespace App\Http\Controllers\Admin;

use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderDetailController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrderDetail  $orderDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrderDetail $orderDetail)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:1',
            'price' => 'numeric|min:0'
        ]);

        $params = $request->only([
            'quantity', 'price'
        ]);

        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $orderDetail->fill($params);
        $orderDetail->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrderDetail  $orderDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->id) {
            $orderDetail = OrderDetail::find($request->id);
            $orderDetail->delete();
            session()->flash('successMessage', 'Product removed successfully!');
        }
    }
}
