@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    تاریخچه ویرایش پروفایل
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
        <h1>تاریخچه ویرایش پروفایل</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    پیشخوان
                </a>
            </li>
            <li>گزارشات</li>
            <li class="active">تاریخچه ویرایش پروفایل</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content user_profile  pr-3 pl-3">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            تاریخچه ویرایش پروفایل
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                            <table class="table table-bordered width100" id="table">
                                <thead>
                                <tr class="filters">
                                    <th>@lang('reports/title.id')</th>
                                    <th>@lang('reports/title.date')</th>
                                    <th>@lang('reports/title.time')</th>
                                    <th>@lang('reports/title.title_field')</th>
                                    <th>@lang('reports/title.old_field')</th>
                                    <th>@lang('reports/title.new_field')</th>
                                    <th>@lang('reports/title.actions')</th>
                                </tr>
                                </thead>
                                <tbody>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop


{{-- page level scripts --}}
@section('footer_scripts')
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/jquery.dataTables.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.bootstrap4.js') }}" ></script>
    <script>
        $(function() {
            var table = $('#table').DataTable({
                language: {
                    "emptyTable": "هیچ داده‌ای در جدول وجود ندارد",
                    "info": "نمایش _START_ تا _END_ از _TOTAL_ ردیف",
                    "infoEmpty": "نمایش 0 تا 0 از 0 ردیف",
                    "infoFiltered": "(فیلتر شده از _MAX_ ردیف)",
                    "infoThousands": ",",
                    "lengthMenu": "نمایش _MENU_ ردیف",
                    "processing": "در حال پردازش...",
                    "search": "جستجو:",
                    "zeroRecords": "رکوردی با این مشخصات پیدا نشد",
                    "paginate": {
                        "first": "برگه‌ی نخست",
                        "last": "برگه‌ی آخر",
                        "next": "بعدی",
                        "previous": "قبلی"
                    },
                    "aria": {
                        "sortAscending": ": فعال سازی نمایش به صورت صعودی",
                        "sortDescending": ": فعال سازی نمایش به صورت نزولی"
                    },
                    "autoFill": {
                        "cancel": "انصراف",
                        "fill": "پر کردن همه سلول ها با ساختار سیستم",
                        "fillHorizontal": "پر کردن سلول های افقی",
                        "fillVertical": "پرکردن سلول های عمودی",
                        "info": "نمونه اطلاعات پرکردن خودکار"
                    },
                    "buttons": {
                        "collection": "مجموعه",
                        "colvis": "قابلیت نمایش ستون",
                        "colvisRestore": "بازنشانی قابلیت نمایش",
                        "copy": "کپی",
                        "copySuccess": {
                            "1": "یک ردیف داخل حافظه کپی شد",
                            "_": "%ds ردیف داخل حافظه کپی شد"
                        },
                        "copyTitle": "کپی در حافظه",
                        "excel": "اکسل",
                        "pageLength": {
                            "-1": "نمایش همه ردیف‌ها",
                            "1": "نمایش 1 ردیف",
                            "_": "نمایش %d ردیف"
                        },
                        "print": "چاپ",
                        "copyKeys": "برای کپی داده جدول در حافظه سیستم کلید های ctrl یا ⌘ + C را فشار دهید",
                        "csv": "فایل CSV",
                        "pdf": "فایل PDF"
                    },
                    "searchBuilder": {
                        "add": "افزودن شرط",
                        "button": {
                            "0": "جستجو ساز",
                            "_": "جستجوساز (%d)"
                        },
                        "clearAll": "خالی کردن همه",
                        "condition": "شرط",
                        "conditions": {
                            "date": {
                                "after": "بعد از",
                                "before": "بعد از",
                                "between": "میان",
                                "empty": "خالی",
                                "equals": "برابر",
                                "not": "نباشد",
                                "notBetween": "میان نباشد",
                                "notEmpty": "خالی نباشد"
                            },
                            "number": {
                                "between": "میان",
                                "empty": "خالی",
                                "equals": "برابر",
                                "gt": "بزرگتر از",
                                "gte": "برابر یا بزرگتر از",
                                "lt": "کمتر از",
                                "lte": "برابر یا کمتر از",
                                "not": "نباشد",
                                "notBetween": "میان نباشد",
                                "notEmpty": "خالی نباشد"
                            },
                            "string": {
                                "contains": "حاوی",
                                "empty": "خالی",
                                "endsWith": "به پایان می رسد با",
                                "equals": "برابر",
                                "not": "نباشد",
                                "notEmpty": "خالی نباشد",
                                "startsWith": "شروع  شود با"
                            },
                            "array": {
                                "equals": "برابر",
                                "empty": "خالی",
                                "contains": "حاوی",
                                "not": "نباشد",
                                "notEmpty": "خالی نباشد",
                                "without": "بدون"
                            }
                        },
                        "data": "اطلاعات",
                        "deleteTitle": "حذف عنوان",
                        "logicAnd": "و",
                        "logicOr": "یا",
                        "title": {
                            "0": "جستجو ساز",
                            "_": "جستجوساز (%d)"
                        },
                        "value": "مقدار"
                    },
                    "select": {
                        "1": "%d ردیف انتخاب شد",
                        "_": "%d ردیف انتخاب شد",
                        "cells": {
                            "1": "1 سلول انتخاب شد",
                            "_": "%d سلول انتخاب شد"
                        },
                        "columns": {
                            "1": "یک ستون انتخاب شد",
                            "_": "%d ستون انتخاب شد"
                        },
                        "rows": {
                            "0": "%d ردیف انتخاب شد",
                            "1": "1ردیف انتخاب شد",
                            "_": "%d  انتخاب شد"
                        }
                    },
                    "thousands": ",",
                    "decimal": "اعشاری",
                    "searchPanes": {
                        "clearMessage": "همه را پاک کن",
                        "collapse": {
                            "0": "صفحه جستجو",
                            "_": "صفحه جستجو (٪ d)"
                        },
                        "count": "{total}",
                        "countFiltered": "{shown} ({total})",
                        "emptyPanes": "صفحه جستجو وجود ندارد",
                        "loadMessage": "در حال بارگیری صفحات جستجو ...",
                        "title": "فیلترهای فعال - %d"
                    },
                    "loadingRecords": "در حال بارگذاری...",
                    "datetime": {
                        "previous": "قبلی",
                        "next": "بعدی",
                        "hours": "ساعت",
                        "minutes": "دقیقه",
                        "seconds": "ثانیه",
                        "amPm": [
                            "صبح",
                            "عصر"
                        ]
                    },
                    "editor": {
                        "close": "بستن",
                        "create": {
                            "button": "جدید",
                            "title": "ثبت جدید",
                            "submit": "ایجــاد"
                        },
                        "edit": {
                            "button": "ویرایش",
                            "title": "ویرایش",
                            "submit": "به‌روزرسانی"
                        },
                        "remove": {
                            "button": "حذف",
                            "title": "حذف",
                            "submit": "حذف"
                        },
                        "multi": {
                            "restore": "واگرد"
                        }
                    }
                }  ,
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.users.data') !!}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'first_name', name: 'first_name' },
                    { data: 'last_name', name: 'last_name' },
                    { data: 'email', name: 'email' },
                    { data: 'status', name: 'status'},
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