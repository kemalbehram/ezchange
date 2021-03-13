@extends('admin/layouts/default')

@section('title')
Users List
@parent
@stop

@section('header_styles')
<link rel="stylesheet" href="{{ asset('vendors/datatables/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/tables.css') }}">
@livewireStyles
@stop

@section('content')

<section class="content-header">
    <h1>مدیریت مشتریان</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="liveicon" data-name="home" data-size="18" data-loop="true"></i>
                صفحه اصلی
            </a>
        </li>
        <li class="active">
            مدیریت مشتریان
        </li>
    </ol>
</section>

<section class="content pr-3 pl-3">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box bg-primary text-white mb-4">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="liveicon" data-name="responsive" data-size="16" data-loop="true" data-c="#fff"
                            data-c="white"></i>
                        مشتریان
                    </div>
                </div>
                <div class="portlet-body bg-white text-dark p-2 flip-scroll">
                   <table class="table table-bordered table-striped table-condensed flip-content example">
                      <thead class="flip-content">
                         <tr>
                            <th>ردیف</th>
                            <th>نام</th>
                            <th>نام خانوادگی</th>
                            <th class="numeric">شماره ملی</th>
                            <th>نام پدر</th>
                         </tr>
                      </thead>
                      <tbody>

                      </tbody>
                   </table>
                </div>
            </div>
        </div>
    </div>
</section>

@section('footer_scripts')

@livewireScripts

@stop
