@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
   مدیریت کیف پول ها
@stop

{{-- page level styles --}}
@section('header_styles')
@stop

{{-- Page content --}}
@section('content')

<section class="content-header">
   <!--section starts-->
   <h1>کیف پول ها</h1>
   <ol class="breadcrumb">
      <li>
         <a href="#">
            <i class="liveicon" data-name="home" data-size="14" data-loop="true"></i>
            پیشخوان
         </a>
      </li>
      <li class="active">
         <span>مدیریت کیف پول ها</span>
      </li>
   </ol>
</section>
<!--section ends -->
<section class="content pr-3 pl-3">
   <div class="row">
      <div class="col-md-4 my-3">
         <div class="card">
            <div class="card-header bg-primary text-white">
               <span>کیف پول شماره 1</span>
            </div>
            <div class="card-body">
               <div class="cb-item">
                  <span>آدرس کیف پول:</span>
                  <span>wefioier328ccewe8</span>
               </div>
               <div class="cb-item">
                  <span>موجودی کیف پول:</span>
                  <span>5000usdt</span>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-4 my-3">
         <div class="card">
            <div class="card-header bg-primary text-white">
               <span>کیف پول شماره 2</span>
            </div>
            <div class="card-body">
               <div class="cb-item">
                  <span>آدرس کیف پول:</span>
                  <span>wefioier328ccewe8</span>
               </div>
               <div class="cb-item">
                  <span>موجودی کیف پول:</span>
                  <span>5000usdt</span>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-4 my-3">
         <div class="card">
            <div class="card-header bg-primary text-white">
               <span>کیف پول شماره 3</span>
            </div>
            <div class="card-body">
               <div class="cb-item">
                  <span>آدرس کیف پول:</span>
                  <span>wefioier328ccewe8</span>
               </div>
               <div class="cb-item">
                  <span>موجودی کیف پول:</span>
                  <span>24000ustd</span>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
@stop
