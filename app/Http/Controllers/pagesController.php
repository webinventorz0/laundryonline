<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\customer;

class pagesController extends Controller
{
    // dashboard ..
    public function dashboard(){
        return view('admin.dashboard');
    }
    // customer ..
    public function customer(){
        return view('admin.customers.customers');
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
    // department
    public function department(){
        return view('admin.departments.department');
    }
    // admin.staff
    public function staff(){
        return view('admin.staff.staff');
    }
    // new orders 
    public function new_order(){
        return view('admin.orders.new');
    }
    // all orders ..
    public function orders(){
        return view('admin.orders.orders');
    }
}
