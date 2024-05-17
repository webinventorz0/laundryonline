@extends('layouts.header')
@section('title','All Customers')
@section('content')
@include('includes.flash')
<div class="card">
    <div class="card-header">
        <i class="fa fa-users"></i> Customers
    </div>
    <table class="table table-bordered table-striped table-hover">
        <tr>
            <th>
                Name
            </th>
            <th>
                Email
            </th>
            <th>
                What's App
            </th>
            <th>
                Address
            </th>
            <th>
                Actions
            </th>
        </tr>
        <form action="{{ route('save.customer') }}" method="post">
            @csrf
            <tr>
                <td>
                    <input type="text" name="name" class="form-control">
                    @error('name')
                    <font color="red"><b><small>{{ $message }}</small></b></font>
                    @enderror
                </td>
                <td>
                    <input type="text" name="email" class="form-control">
                    @error('email')
                    <font color="red"><b><small>{{ $message }}</small></b></font>
                    @enderror
                </td>
                <td>
                    <input type="text" name="whatsapp" class="form-control">
                    @error('whatsapp')
                    <font color="red"><b><small>{{ $message }}</small></b></font>
                    @enderror
                </td>
                <td>
                    <input type="text" name="address" class="form-control">
                    @error('address')
                    <font color="red"><b><small>{{ $message }}</small></b></font>
                    @enderror
                </td>
                <td>
                <button class="btn btn-success"><i class="fa fa-save"></i></button>
                </td>
            </tr>
        </form>
    </table>
</div>
@endsection