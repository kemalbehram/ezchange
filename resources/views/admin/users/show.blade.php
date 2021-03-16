@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    View User Details
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <link href="{{ asset('vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet"/>
    <link href="{{ asset('vendors/x-editable/css/bootstrap-editable.css') }}" rel="stylesheet"/>

    <link href="{{ asset('css/pages/user_profile.css') }}" rel="stylesheet"/>
@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <!--section starts-->
        <h1>پروفایل کاربر</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-loop="true"></i>
                    پیشخوان
                </a>
            </li>
            <li>
                <a href="#">کاربران</a>
            </li>
            <li class="active"><span>پروفایل کاربر</span></li>
        </ol>
    </section>
    <!--section ends-->
    <section class="content user_profile  pr-3 pl-3">
        <div class="row">
            <div class="col-lg-12">
                <ul class="nav  nav-tabs first_svg">
                    <li class="nav-item">
                        <a href="#tab1" data-toggle="tab" class="nav-link active">
                            <i class="livicon" data-name="user" data-size="16" data-c="#777"  data-hc="#000" data-loop="true"></i>
                            پروفایل کاربر</a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab2" data-toggle="tab" class="nav-link">
                            <i class="livicon" data-name="key" data-size="16" data-loop="true" data-c="#000" data-hc="#000"></i>
                            مدیریت کارت ها</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class=" nav-link" >
                            <i class="livicon" data-name="gift" data-size="16" data-loop="true" data-c="#000" data-hc="#000"></i>
                            مدارک آپلود شده</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class=" nav-link" >
                            <i class="livicon" data-name="gift" data-size="16" data-loop="true" data-c="#000" data-hc="#000"></i>
                            بازدید ها</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class=" nav-link" >
                            <i class="livicon" data-name="gift" data-size="16" data-loop="true" data-c="#000" data-hc="#000"></i>
                            تاریخچه تغییرات</a>
                    </li>
                </ul>
                <div  class="tab-content mar-top" id="clothing-nav-content">
                    <div id="tab1" class="tab-pane fade show active">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            پروفایل کاربر
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                        <div class="col-md-4">
                                            <div class="img-file">
                                                @if($user->pic)
                                                    <img src="{{ $user->pic }}" alt="img"
                                                         class="img-fluid"/>
                                                @elseif($user->gender === "male")
                                                    <img src="{{ asset('images/authors/avatar3.png') }}" alt="..."
                                                         class="img-fluid"/>
                                                @elseif($user->gender === "female")
                                                    <img src="{{ asset('images/authors/avatar5.png') }}" alt="..."
                                                         class="img-fluid"/>
                                                @else
                                                    <img src="{{ asset('images/authors/no_avatar.jpg') }}" alt="..."
                                                         class="img-fluid"/>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                                <div class="table-responsive-lg table-responsive-sm table-responsive-md table-responsive">
                                                    <table class="table table-bordered table-striped" id="users">
                                                        <tr>
                                                            <td>@lang('users/title.first_name')</td>
                                                            <td>
                                                                <p class="user_name_max">{{ $user->first_name }}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('users/title.last_name')</td>
                                                            <td>
                                                                <p class="user_name_max">{{ $user->last_name }}</p>
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td>@lang('users/title.email')</td>
                                                            <td>
                                                                {{ $user->email }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                @lang('users/title.last_login')
                                                            </td>
                                                            <td>
                                                                {{ $user->last_login }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('users/title.dob')</td>

                                                            @if($user->birthdate=='0000-00-00')
                                                                <td>
                                                                </td>
                                                            @else
                                                                <td>
                                                                    {{ $user->birthdate }}
                                                                </td>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('users/title.national_code')</td>
                                                            <td>
                                                                {{ $user->national_code }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('users/title.parent_name')</td>
                                                            <td>
                                                                {{ $user->parent_name }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('users/title.state')</td>
                                                            <td>
                                                                {{ $user->user_state }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('users/title.city')</td>
                                                            <td>
                                                                {{ $user->city }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('users/title.address')</td>
                                                            <td>
                                                                {{ $user->address }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('users/title.status')</td>
                                                            <td>

                                                                @if($user->status == 1)
                                                                    فعال
                                                                @else
                                                                    غیرفعال
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('users/title.is_verified')</td>
                                                            <td>
                                                                @if($user->is_verified === 'banned')
                                                                    تایید نشده
                                                                @elseif($user->is_verified === 'process')
                                                                    در حال انتظار
                                                                @elseif($user->is_verified === 'verified')
                                                                    تایید شده
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('users/title.phone')</td>
                                                            <td>{{ $user->mobile_number }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('users/title.referral_code')</td>
                                                            <td>{{ $user->referral_code }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('users/title.referrer_id')</td>
                                                            <td>{{ $user->referrer_id }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('users/title.created_at')</td>
                                                            <td>
                                                                {!! $user->created_at->diffForHumans() !!}
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
                        </div>
                    <div id="tab2" class="tab-pane fade">
                        <div class="row">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- Bootstrap WYSIHTML5 -->
    <script  src="{{ asset('vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#change-password').click(function (e) {
                e.preventDefault();
                var check = false;
                if ($('#password').val() ===""){
                    alert('Please Enter password');
                }
                else if  ($('#password').val() !== $('#password-confirm').val()) {
                    alert("confirm password should match with password");
                }
                else if  ($('#password').val() === $('#password-confirm').val()) {
                    check = true;
                }

                if(check == true){
                var sendData =  '_token=' + $("input[name='_token']").val() +'&password=' + $('#password').val() +'&id=' + {{ $user->id }};
                    var path = "passwordreset";
                    $.ajax({
                        url: path,
                        type: "post",
                        data: sendData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                        },
                        success: function (data) {
                            $('#password, #password-confirm').val('');
                            alert('password reset successful');
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert('error in password reset');
                        }
                    });
                }
            });
        });



        $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
            e.target // newly activated tab
            e.relatedTarget // previous active tab
            $("#clothing-nav-content .tab-pane").removeClass("show active");
        });

    </script>

@stop
