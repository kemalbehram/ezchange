@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    جزِئیات کارت بانکی
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link type="text/css" href="{{ asset('vendors/bootstrap-multiselect/css/bootstrap-multiselect.css') }}"
          rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/dataTables.bootstrap4.css') }}" />
    <link href="{{ asset('css/pages/tables.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('css/pages/formelements.css') }}" rel="stylesheet"/>
@stop
{{-- Page content --}}
@section('content')
    <section class="content-header">
        <h1>جزِئیات کارت بانکی</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    پیشخوان
                </a>
            </li>
            <li>مدیریت کارت ها</li>
            <li class="active">جزِئیات کارت بانکی</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content user_profile  pr-3 pl-3">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            جزِئیات کارت بانکی
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
                            جزِئیات کارت بانکی
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 col-lg-6 col-12">
                                <!-- credit card section -->
                                <div class="card ">
                                    <div class="card-body">
                                        <div class="box-body">
                                            <div class="card-wrapper"></div>
                                            <br/>
                                            <div id="card">
                                                <form class="form-horizontal">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <label class="col-md-3 my-2 control-label" for="card1">Card
                                                                Number</label>
                                                            <div class="col-md-9">
                                                                <input name="number" required type="text" placeholder=""
                                                                       class="form-control" id="card1"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <label class="col-md-3 my-2 control-label" for="card2">Name
                                                                on Card</label>
                                                            <div class="col-md-9">
                                                                <input name="name" type="text" class="form-control"
                                                                       maxlength="40" required id="card2"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <label class="col-md-3  my-2 control-label"
                                                                   for="card3">CVV</label>
                                                            <div class="col-md-9">
                                                                <input name="cvc" required type="text" placeholder=""
                                                                       class="form-control" id="card3"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <label class="col-md-3 my-2  control-label" for="card4">Expiry
                                                                Date</label>
                                                            <div class="col-md-9">
                                                                <input name="expiry" placeholder="" class="form-control"
                                                                       id="card4"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- credit card section ends -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('footer_scripts')
    {{--    CARD--}}
    <script src="{{ asset('vendors/bootstrap-maxlength/js/bootstrap-maxlength.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendors/card/js/jquery.card.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/pages/formelements.js') }}" type="text/javascript"></script>
    {{--    /CARD--}}
@stop
