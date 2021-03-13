@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
تنظیمات
@parent
@stop

{{-- page level styles --}}
{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>تنظیمات</h1>
    <ol class="breadcrumb">
        <li>
            <a href="#">
                <i class="livicon" data-name="home" data-size="16" data-color="#333" data-hovercolor="#333"></i>
                پیشخوان
            </a>
        </li>
        <li class="active"><span>تنظیمات</span></li>
    </ol>
</section>
<!-- section ends -->
<section class="content pl-3 pr-3">
    <div class="row">
        <div class="col-md-12">
            <form>
                <div class="row form-group">
                    <label class="col-sm-4 col-form-label text-right font-weight-bold">تایین حداقل مقدار خرید</label>
                    <div class="col-sm-6">
                        <div class="input-group form-control-md">
                            <input type="text" class="form-control" placeholder="قیمت(ریال)">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">ثبت</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12">
            <form>
                <div class="row form-group">
                    <label class="col-sm-4 col-form-label text-right font-weight-bold">حداکثر خرید خودکار</label>
                    <div class="col-sm-6">
                        <div class="input-group form-control-md">
                            <input type="text" class="form-control" placeholder="قیمت(ریال)">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">ثبت</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12">
            <form>
                <div class="row form-group">
                    <label class="col-sm-4 col-form-label text-right font-weight-bold">تایید نرخ خرید و فروش</label>
                    <div class="col-sm-6">
                        <div class="input-group form-control-md">
                            <input type="text" class="form-control" placeholder="قیمت(ریال)">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">ثبت</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12">
            <form>
                <div class="row form-group">
                    <label class="col-sm-4 col-form-label text-right font-weight-bold">تایین میزان سود برای معرفی دوستان(به ازای هر تراکنش)</label>
                    <div class="col-sm-6">
                        <div class="input-group form-control-md">
                            <input type="text" class="form-control" placeholder="قیمت(ریال)">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">ثبت</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@stop
