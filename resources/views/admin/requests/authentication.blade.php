@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    مدیریت درخواست ها - احرازهویت
    @parent
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
            <li>مدیریت درخواست ها</li>
            <li class="active">احرازهویت</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content user_profile  pr-3 pl-3">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            مدیریت درخواست ها - احرازهویت
                        </h3>

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

