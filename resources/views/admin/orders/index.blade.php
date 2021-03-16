@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    لیست کاربران
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/dataTables.bootstrap4.css') }}" />
    <link href="{{ asset('css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <h1>کاربران</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    پیشخوان
                </a>
            </li>
            <li class="active">مدیریت کاربران</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content pl-3 pr-3">
        <div class="row">
            <div class="col-12">
                <div class="card ">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title my-2 float-left"> <i class="livicon" data-name="user" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                            لیست سفارشات
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                            <table class="table table-bordered width100" id="table">
                                <thead>
                                <tr class="filters">
                                    <th>@lang('orders/title.id')</th>
                                    <th>@lang('orders/title.amount_in_tethers')</th>
                                    <th>@lang('orders/title.amount_in_rials')</th>
                                    <th>@lang('orders/title.price_in_rials')</th>
                                    <th>@lang('orders/title.type')</th>
                                    <th>@lang('orders/title.payment_status')</th>
                                    <th>@lang('orders/title.pay_time')</th>
                                    <th>@lang('orders/title.created_at')</th>
                                    <th>@lang('orders/title.actions')</th>
                                </tr>
                                </thead>
                                <tbody>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- row-->
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/jquery.dataTables.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.bootstrap4.js') }}" ></script>

<script>

    $(function() {
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.orders.data') !!}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'amount_in_tethers', name: 'amount_in_tethers' },
                { data: 'amount_in_rials', name: 'amount_in_rials' },
                { data: 'price_in_rials', name: 'price_in_rials'},
                { data: 'type', name: 'type'},
                { data: 'payment_status', name:'payment_status'},
                { data: 'pay_time', name:'pay_time'},
                { data: 'created_at', name:'created_at'},
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });
        table.on( 'draw', function () {
            $('.livicon').each(function(){
                $(this).updateLivicon();
            });
        } );
    });

</script>

    <div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="deleteLabel">حذف کاربر</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    آیا مطمئن هستید که این کاربر را حذف می کنید؟ این عملیات برگشت ناپذیر است.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">لغو</button>
                    <a  type="button" class="btn btn-danger Remove_square">حذف</a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
    <!-- /.modal-dialog -->
<script>
$(function () {
	$('body').on('hidden.bs.modal', '.modal', function () {
		$(this).removeData('bs.modal');
	});
});
var $url_path = '{!! url('/') !!}';
$('#delete_confirm').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var $recipient = button.data('id');
    var modal = $(this)
    modal.find('.modal-footer a').prop("href",$url_path+"/admin/users/"+$recipient+"/delete");
})
</script>
@stop
