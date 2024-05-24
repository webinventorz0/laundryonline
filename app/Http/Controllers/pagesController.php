<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\customer;
use App\Models\department;

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
    // all orders ..
    public function orders(){
        return view('admin.orders.orders');
    }
}
