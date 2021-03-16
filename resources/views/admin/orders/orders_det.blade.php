@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    جزِئیات سفارش
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link type="text/css" href="{{ asset('vendors/bootstrap-multiselect/css/bootstrap-multiselect.css') }}"
          rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/dataTables.bootstrap4.css') }}" />
    <link href="{{ asset('css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
@stop
{{-- Page content --}}
@section('content')
    <section class="content-header">
        <h1>جزِئیات سفارش</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    پیشخوان
                </a>
            </li>
            <li>مدیریت سفارشات</li>
            <li class="active">جزِئیات سفارش</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content user_profile  pr-3 pl-3">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            اطلاعات کاربر
                        </h3>

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div
                                    class="table-responsive-lg table-responsive-sm table-responsive-md table-responsive">
                                    <table class="table table-bordered table-striped" id="users">

                                        <tr>
                                            <td>@lang('users/title.first_name')</td>
                                            <td>
                                                <p class="user_name_max"></p>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>@lang('users/title.last_name')</td>
                                            <td>
                                                <p class="user_name_max"></p>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>@lang('users/title.email')</td>
                                            <td>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                @lang('users/title.gender')
                                            </td>
                                            <td>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>@lang('users/title.dob')</td>

                                            @if(true)
                                                <td>
                                                </td>
                                            @else
                                                <td>

                                                </td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td>@lang('users/title.country')</td>
                                            <td>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>@lang('users/title.state')</td>
                                            <td>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>@lang('users/title.city')</td>
                                            <td>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>@lang('users/title.address')</td>
                                            <td>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>@lang('users/title.postal')</td>
                                            <td>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>@lang('users/title.status')</td>
                                            <td>





                                            </td>
                                        </tr>
                                        <tr>
                                            <td>@lang('users/title.created_at')</td>
                                            <td>

                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            جزئیات سفارش
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div
                                    class="table-responsive-lg table-responsive-sm table-responsive-md table-responsive">
                                    <table class="table table-bordered table-striped" id="users">

                                        <tr>
                                            <td>@lang('orders/title.amount')</td>
                                            <td>
                                                <p class="user_name_max"></p>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>@lang('orders/title.Unit_Price')</td>
                                            <td>
                                                <p class="user_name_max"></p>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>@lang('orders/title.Total_Price')</td>
                                            <td>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>@lang('orders/title.status')</td>
                                            <td>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>@lang('orders/title.date')</td>
                                            <td>

                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

