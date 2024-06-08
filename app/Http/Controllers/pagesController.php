<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\customer;
use App\Models\department;
use App\Models\order;
use App\Models\orderdetail;

class pagesController extends Controller
{
    // dashboard ..
    public function dashboard(){
        return view('admin.dashboard');
    }
    // customer ..
    public function customer(){
        $customers = customer::orderby('id','desc')->get();
        return view('admin.customers.customers',compact('customers'));
    }
    // for save customer ..
    public function save_customer(Request $request){
        // validation ..
        $validated = $request->validate([
            'name' => 'required|max:255',
            'whatsapp' => 'required',
            'email' => 'required|unique:customers',
            'address' => 'required',

        ]);

        // new customer creation ..
        $customer = new customer;
        $customer->name = $request->name;
        $customer->whatsapp = $request->whatsapp;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->save();
        return redirect()->back()->with('success','Customer Addedd Succesfully');
    }
    // customer delete ..
    public function delete_customer($id){
        $customer = customer::find($id);
        $customer->delete();
        return redirect()->back()->with('error','Customer Deleted Succesfully');
    }
    // customer update
    public function update_customer($id, Request $request){
        $validated = $request->validate([
            'name' => 'required|max:255',
            'whatsapp' => 'required',
            'email' => 'required',
            'address' => 'required',

        ]);
        // customer create ..
        $customer = customer::find($id);
        $customer->name = $request->name;
        $customer->whatsapp = $request->whatsapp;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->save();
        return redirect()->back()->with('message','Customer Update Succesfully');
        
    }
    // department
    public function department(){
        $departments = department::orderby('id','desc')->get();
        return view('admin.departments.department',compact('departments'));
    }
    public function department_save(Request $request){
        $validated = $request->validate([
            'name' => 'required|max:255',

        ]);
        $department = new department;
        $department->name = $request->name;
        $department->save();
        return redirect()->back()->with('success','Department Created Successfully');
    }
    public function department_status($id){
        $department = department::find($id);
        if($department->status == 0){
            $department->status = 1;
            $department->save();
            return redirect()->back()->with('error','Department Disable Succesfully');
        } else {
            $department->status = 0;
            $department->save();
            return redirect()->back()->with('success','Department Enable Succesfully');

        }
    }
    // admin.staff
    public function staff(){
        return view('admin.staff.staff');
    }
    // new orders 
    public function new_order(){
        $customers = customer::all();
        $departments = department::where('status',0)->get();
        return view('admin.orders.new',compact('customers','departments'));
    }
    // save order ..
    public function save_order(Request $request){   
        $validated = $request->validate([
            'customer' => 'required',
            'type' => 'required',
            'department' => 'required',
            'ddate' => 'required',

        ]);
        $order = new order;
        $order->customer_id = $request->customer;
        $order->order_type = $request->type;
        $order->delivery_type = $request->delivery_type;
        $order->department_id = $request->department;
        $order->edd = $request->ddate;
        $order->save();
        return redirect(route('order.details',$order->id))->with('success','Order is placed succesfully, please add order details also');

    }
    // order details ..
    public function order_detail($id){
        $order = order::find($id);
        return view('admin.orders.order_details',compact('order'));
    }
    // all orders ..
    public function orders(){
        return view('admin.orders.orders');
    }
    // order details working ..
    public function order_detail_save($id, Request $request){
        $validated = $request->validate([
            'title' => 'required',
            'qty' => 'required',
            'ppp' => 'required',
        ]);   
        // order table for update sum ..
        $order = order::find($id);
        // order details 
        $orderdetail = new orderdetail;
        $orderdetail->order_id = $order->id;
        $orderdetail->name = $request->title;
        $orderdetail->qty = $request->qty;
        $orderdetail->price = $request->ppp;
        $total = $request->qty * $request->ppp;
        $discount = ($total/100) * $request->discount;
        $total = $total - $discount;
        $orderdetail->discount = $request->discount;
        $orderdetail->total = $total;
        $orderdetail->save();
        // end order details ..
        $orderdetail_sum = orderdetail::where('order_id',$order->id)->sum('total');
        $order->total = $orderdetail_sum;
        $order->save();
        // end order table for update sum ..
        return redirect()->back()->with('message','Item Added Into Order #'.$order->id.' Succesfully');

    }
    public function orderdetail($id){
        $orderdetails = orderdetail::find($id);
        // updating order ..
        $order = order::find($orderdetails->order_id);
        $order->total = $order->total - $orderdetails->total;
        $order->save();
        // item delete ..
        $orderdetails->delete();
        return redirect()->back()->with('warning','Item Deleted Successfully');
    }
    // place order ..
    public function place_order($id , Request $request){
        $validated = $request->validate([
            'advance' => 'required',
        ]);   
        $order = order::find($id);
        $remaining = $order->total - $request->advance;
        $order->advance = $request->advance;
        $order->pending = $remaining;
        $order->status = 1;
        $order->save();
        return redirect(route('all.orders'))->with('success','Order Is Placed Succesfully');
    }
}
