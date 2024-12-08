@php use App\CentralLogics\Helpers; @endphp
@extends('layouts.admin.app')

@section('title',translate('messages.Order List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        @php($parcel_order = Request::is('admin/parcel/orders*'))
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-xl-10 col-md-9 col-sm-8 mb-3 mb-sm-0 mb-2">
                    <h1 class="page-header-title text-capitalize m-0">
                        <span class="page-header-icon">
                            <img src="{{asset('public/assets/admin/img/order.png')}}" class="w--26" alt="">
                        </span>
                        <span>
                            {{translate('messages.parcel_orders')}}
                            <span class="badge badge-soft-dark ml-2">{{$total}}</span>
                        </span>
                    </h1>
                </div>
            </div>
            <!-- End Row -->
        </div>
        <!-- End Page Header -->
        <!-- Card -->

        <div class="card">
            <!-- Header -->
            <div class="card-header py-1 border-0">
                <div class="search--button-wrapper justify-content-end">
                    <form class="search-form min--260">
                        <!-- Search -->
                        <div class="input-group input--group">
                            <input id="datatableSearch_" type="search" name="search" class="form-control h--40px"
                                   placeholder="{{ translate('messages.Ex:') }} 10010"
                                   value="{{ request()?->search ?? null}}"
                                   aria-label="{{translate('messages.search')}}">
                            <input type="hidden" name="parcel_order" value="{{$parcel_order}}">
                            <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                        </div>
                        <!-- End Search -->
                    </form>

                    @if(request()->get('search'))
                        <button type="reset" class="btn btn--primary ml-2 location-reload-to-base"
                                data-url="{{url()->full()}}">{{translate('messages.reset')}}</button>
                    @endif

                    <!-- Datatable Info -->
                    <div id="datatableCounterInfo" class="mr-2 mb-2 mb-sm-0 initial-hidden">
                        <div class="d-flex align-items-center">
                                <span class="font-size-sm mr-3">
                                <span id="datatableCounter">0</span>
                                {{translate('messages.selected')}}
                                </span>
                        </div>
                    </div>

                    <div class="hs-unfold mr-2">
                        <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle h--40px" href="javascript:;"
                           data-hs-unfold-options='{
                                "target": "#usersExportDropdown",
                                "type": "css-animation"
                            }'>
                            <i class="tio-download-to mr-1"></i> {{translate('messages.export')}}
                        </a>

                        <div id="usersExportDropdown"
                             class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                            <span class="dropdown-header">{{translate('messages.download_options')}}</span>
                            <a id="export-excel" class="dropdown-item" href="javascript:;">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                     src="{{asset('public/assets/admin')}}/svg/components/excel.svg"
                                     alt="Image Description">
                                {{translate('messages.excel')}}
                            </a>
                            <a id="export-csv" class="dropdown-item" href="javascript:;">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                     src="{{asset('public/assets/admin')}}/svg/components/placeholder-csv-format.svg"
                                     alt="Image Description">
                                .{{translate('messages.csv')}}
                            </a>
                        </div>
                    </div>

                    @if(Request::is('admin/refund/*'))
                        <div class="select-item">
                            <select name="slist" class="form-control js-select2-custom refund-filter">
                                <option
                                    {{($status=='requested')?'selected':''}} value="{{ route('admin.refund.refund_attr', ['requested']) }}">{{translate('messages.Refund Requests')}}</option>
                                <option
                                    {{($status=='refunded')?'selected':''}} value="{{ route('admin.refund.refund_attr', ['refunded']) }}">{{translate('messages.Refund')}}</option>
                                <option
                                    {{($status=='rejected')?'selected':''}} value="{{ route('admin.refund.refund_attr', ['rejected']) }}">{{translate('Rejected')}}</option>
                            </select>
                        </div>
                    @endif

                    <div class="hs-unfold mr-2">
                        <a class="js-hs-unfold-invoker btn btn-sm btn-white h--40px filter-button-show"
                           href="javascript:;">
                            <i class="tio-filter-list mr-1"></i> {{ translate('messages.filter') }} <span
                                class="badge badge-success badge-pill ml-1" id="filter_count"></span>
                        </a>
                    </div>

                    {{-- @if ($status != 'scheduled')
                    <div class="hs-unfold">
                        <a class="js-hs-unfold-invoker btn btn-sm btn-white h--40px" href="javascript:;"
                            data-hs-unfold-options='{
                                "target": "#showHideDropdown",
                                "type": "css-animation"
                            }'>
                            <i class="tio-table mr-1"></i> {{translate('messages.columns')}}
                        </a>

                        <div id="showHideDropdown"
                                class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right dropdown-card min--240">
                            <div class="card card-sm">
                                <div class="card-body">

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{translate('messages.date')}}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_date">
                                            <input type="checkbox" class="toggle-switch-input"
                                                    id="toggleColumn_date" checked>
                                            <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <!-- End Checkbox Switch -->
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{translate('messages.customer')}}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm"
                                                for="toggleColumn_customer">
                                            <input type="checkbox" class="toggle-switch-input"
                                                    id="toggleColumn_customer" checked>
                                            <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <!-- End Checkbox Switch -->
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{translate('messages.parcel_category')}}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm"
                                                for="toggleColumn_store">
                                            <input type="checkbox" class="toggle-switch-input"
                                                    id="toggleColumn_store" checked>
                                            <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <!-- End Checkbox Switch -->
                                    </div>


                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{translate('messages.total')}}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_total">
                                            <input type="checkbox" class="toggle-switch-input"
                                                    id="toggleColumn_total" checked>
                                            <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <!-- End Checkbox Switch -->
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{translate('messages.order_status')}}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_order_status">
                                            <input type="checkbox" class="toggle-switch-input"
                                                    id="toggleColumn_order_status" checked>
                                            <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <!-- End Checkbox Switch -->
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="mr-2">{{translate('messages.actions')}}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm"
                                                for="toggleColumn_actions">
                                            <input type="checkbox" class="toggle-switch-input"
                                                    id="toggleColumn_actions" checked>
                                            <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <!-- End Checkbox Switch -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif --}}
                    <!-- End Unfold -->
                </div>
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table id="datatable"
                       class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table fz--14px"
                       data-hs-datatables-options='{
                     "columnDefs": [{
                        "targets": [0],
                        "orderable": false
                      }],
                     "order": [],
                     "info": {
                       "totalQty": "#datatableWithPaginationInfoTotalQty"
                     },
                     "search": "#datatableSearch",
                     "entries": "#datatableEntries",
                     "isResponsive": false,
                     "isShowPaging": false,
                     "paging": false
                   }'>
                    <thead class="thead-light">
                    <tr>
                        <th class="border-0">
                            {{translate('messages.sl')}}
                        </th>
                        <th class="table-column-pl-0 border-0">{{translate('messages.order_id')}}</th>
                        <th class="border-0">{{translate('messages.order_date')}}</th>
                        @if ($status == 'scheduled')
                            <th class="border-0">{{translate('messages.scheduled_at')}}</th>
                        @endif
                        <th class="border-0">{{translate('messages.customer_information')}}</th>
                        <th class="border-0">{{translate('messages.parcel_category')}}</th>
                        <th class="border-0">{{translate('messages.Payment_By')}}</th>
                        <th class="border-0">{{translate('messages.total_amount')}}</th>

                        @if ($status == 'refunded')
                            <th class="text-center border-0">{{translate('messages.Refunded_order_status')}}</th>
                        @else
                            <th class="text-center border-0">{{translate('messages.order_status')}}</th>
                        @endif
                        <th class="text-center border-0">{{translate('messages.actions')}}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($orders as $key=>$order)

                        <tr class="status-{{$order['order_status']}} class-all">
                            <td class="">
                                {{$key+$orders->firstItem()}}
                            </td>
                            <td class="table-column-pl-0">
                                <a href="{{route('admin.parcel.order.details',['id'=>$order['id']])}}">{{$order['id']}}</a>
                            </td>
                            <td>
                                <div>
                                    <div>
                                        {{ Helpers::date_format($order->created_at) }}
                                    </div>
                                    <div class="d-block text-uppercase">
                                        {{ Helpers::time_format($order->created_at) }}
                                    </div>
                                </div>
                            </td>
                            @if ($status == 'scheduled')
                                <td>
                                    <div>
                                        <div>
                                            {{ Helpers::date_format($order->schedule_at) }}
                                        </div>
                                        <div class="d-block text-uppercase">
                                            {{ Helpers::time_format($order->schedule_at) }}
                                        </div>
                                    </div>
                                </td>
                            @endif
                            <td>
                                @if($order->is_guest)
                                    @php($customer_details = json_decode($order['delivery_address'],true))
                                    <strong>{{$customer_details['contact_person_name']}}</strong>
                                    <a href="tel:{{$customer_details['contact_person_number']}}">
                                        <div>{{$customer_details['contact_person_number']}}</div>
                                    </a>
                                @elseif($order->customer)

                                    <a class="text-body" href="{{route('admin.customer.view',[$order['user_id']])}}">
                                        <strong>
                                            <div> {{$order->customer['f_name'].' '.$order->customer['l_name']}}</div>
                                        </strong>
                                    </a>
                                    <a href="tel:{{$order->customer['phone']}}">
                                        <div>{{$order->customer['phone']}}</div>
                                    </a>
                                @else
                                    <label
                                        class="badge badge-danger">{{translate('messages.invalid_customer_data')}}</label>
                                @endif
                            </td>

                            <td>
                                <div>{{Str::limit($order->parcel_category?$order->parcel_category->name:translate('messages.not_found'),20,'...')}}</div>
                            </td>

                            <td>
                                <div>{{translate($order->charge_payer)}}</div>
                                <strong class="text-success">
                                    {{translate($order->payment_method)}}
                                </strong>
                            </td>


                            <td>
                                <div class="text-right mw--85px">
                                    <div>
                                        {{Helpers::format_currency($order['order_amount'])}}
                                    </div>
                                    @if($order->payment_status=='paid')
                                        <strong class="text-success">
                                            {{translate('messages.paid')}}
                                        </strong>
                                    @elseif($order->payment_status=='partially_paid')
                                        <strong class="text-success">
                                            {{translate('messages.partially_paid')}}
                                        </strong>
                                    @else
                                        <strong class="text-danger">
                                            {{translate('messages.unpaid')}}
                                        </strong>
                                    @endif
                                </div>
                            </td>
                            <td class="text-capitalize text-center">
                                @if($order['order_status']=='pending')
                                    <span class="badge badge-soft-info">
                                      {{translate('messages.pending')}}
                                    </span>
                                @elseif($order['order_status']=='confirmed')
                                    <span class="badge badge-soft-info">
                                      {{translate('messages.confirmed')}}
                                    </span>
                                @elseif($order['order_status']=='processing')
                                    <span class="badge badge-soft-warning">
                                      {{translate('messages.processing')}}
                                    </span>
                                @elseif($order['order_status']=='picked_up')
                                    <span class="badge badge-soft-warning">
                                      {{translate('messages.out_for_delivery')}}
                                    </span>
                                @elseif($order['order_status']=='delivered')
                                    <span class="badge badge-soft-success">
                                      {{translate('messages.delivered')}}
                                    </span>
                                @elseif($order['order_status']=='failed')
                                    <span class="badge badge-soft-danger">
                                      {{translate('messages.payment_failed')}}
                                    </span>
                                @elseif($order['order_status']=='handover')
                                    <span class="badge badge-soft-danger">
                                      {{translate('messages.handover')}}
                                    </span>
                                @elseif($order['order_status']=='canceled')
                                    <span class="badge badge-soft-danger">
                                      {{translate('messages.canceled')}}
                                    </span>
                                @elseif($order['order_status']=='accepted')
                                    <span class="badge badge-soft-danger">
                                      {{translate('messages.accepted')}}
                                    </span>
                                @elseif($order['order_status']=='refund_requested')
                                    <span class="badge badge-soft-danger">
                                      {{translate('messages.refund_requested')}}
                                    </span>
                                @else
                                    <span class="badge badge-soft-danger">
                                      {{str_replace('_',' ',$order['order_status'])}}
                                    </span>
                                @endif

                            </td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="ml-2 btn btn-sm btn--warning btn-outline-warning action-btn"
                                       href="{{route('admin.parcel.order.details',['id'=>$order['id']])}}">
                                        <i class="tio-invisible"></i>
                                    </a>
                                    <a class="ml-2 btn btn-sm btn--primary btn-outline-primary action-btn"
                                       href="{{route('admin.order.generate-invoice',['id'=>$order['id']])}}">
                                        <i class="tio-print"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- End Table -->


            @if(count($orders) !== 0)
                <hr>
            @endif
            <div class="page-area">
                {!! $orders->appends($_GET)->links() !!}
            </div>
            @if(count($orders) === 0)
                <div class="empty--data">
                    <img src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="public">
                    <h5>
                        {{translate('no_data_found')}}
                    </h5>
                </div>
            @endif
        </div>
        <!-- End Card -->

        <!-- Order Filter Modal -->
        <div id="datatableFilterSidebar"
             class="hs-unfold-content_ sidebar sidebar-bordered sidebar-box-shadow initial-hidden">
            <div class="card card-lg sidebar-card sidebar-footer-fixed">
                <div class="card-header">
                    <h4 class="card-header-title">{{translate('messages.order_filter')}}</h4>

                    <!-- Toggle Button -->
                    <a class="js-hs-unfold-invoker_ btn btn-icon btn-sm btn-ghost-dark ml-2 filter-button-hide"
                       href="javascript:;">
                        <i class="tio-clear tio-lg"></i>
                    </a>
                    <!-- End Toggle Button -->
                </div>
                <?php
                $filter_count = 0;
                if (isset($zone_ids) && count($zone_ids) > 0) $filter_count += 1;
                if (isset($vendor_ids) && count($vendor_ids) > 0) $filter_count += 1;
                if ($status == 'all') {
                    if (isset($orderstatus) && count($orderstatus) > 0) $filter_count += 1;
                    if (isset($scheduled) && $scheduled == 1) $filter_count += 1;
                }

                if (isset($from_date) && isset($to_date)) $filter_count += 1;
                if (isset($order_type)) $filter_count += 1;

                ?>
                    <!-- Body -->
                <form class="card-body sidebar-body sidebar-scrollbar" action="{{route('admin.order.filter')}}"
                      method="POST" id="order_filter_form">
                    @csrf
                    <small class="text-cap mb-3">{{translate('messages.zone')}}</small>

                    <div class="mb-2 initial--21">
                        <select name="zone[]" data-title="{{ translate('messages.select_zone') }}"
                                data-placeholder="{{ translate('messages.select_zone') }}" id="zone_ids"
                                class="form-control js-select2-custom" multiple="multiple">
                            @foreach(\App\Models\Zone::all() as $zone)
                                <option
                                    value="{{$zone->id}}" {{isset($zone_ids)?(in_array($zone->id, $zone_ids)?'selected':''):''}}>{{$zone->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <hr class="my-4">
                    @if($status == 'all')
                        <small class="text-cap mb-3">{{translate('messages.order_status')}}</small>

                        <!-- Custom Checkbox -->
                        <div class="custom-control custom-radio mb-2">
                            <input type="checkbox" id="orderStatus2" name="orderStatus[]" class="custom-control-input"
                                   {{isset($orderstatus)?(in_array('pending', $orderstatus)?'checked':''):''}} value="pending">
                            <label class="custom-control-label"
                                   for="orderStatus2">{{translate('messages.pending')}}</label>
                        </div>
                        <div class="custom-control custom-radio mb-2">
                            <input type="checkbox" id="orderStatus1" name="orderStatus[]" class="custom-control-input"
                                   value="confirmed" {{isset($orderstatus)?(in_array('confirmed', $orderstatus)?'checked':''):''}}>
                            <label class="custom-control-label"
                                   for="orderStatus1">{{translate('messages.confirmed')}}</label>
                        </div>
                        <div class="custom-control custom-radio mb-2">
                            <input type="checkbox" id="orderStatus3" name="orderStatus[]" class="custom-control-input"
                                   value="processing" {{isset($orderstatus)?(in_array('processing', $orderstatus)?'checked':''):''}}>
                            <label class="custom-control-label"
                                   for="orderStatus3">{{translate('messages.processing')}}</label>
                        </div>
                        <div class="custom-control custom-radio mb-2">
                            <input type="checkbox" id="orderStatus4" name="orderStatus[]" class="custom-control-input"
                                   value="picked_up" {{isset($orderstatus)?(in_array('picked_up', $orderstatus)?'checked':''):''}}>
                            <label class="custom-control-label"
                                   for="orderStatus4">{{translate('messages.out_for_delivery')}}</label>
                        </div>
                        <div class="custom-control custom-radio mb-2">
                            <input type="checkbox" id="orderStatus5" name="orderStatus[]" class="custom-control-input"
                                   value="delivered" {{isset($orderstatus)?(in_array('delivered', $orderstatus)?'checked':''):''}}>
                            <label class="custom-control-label"
                                   for="orderStatus5">{{translate('messages.delivered')}}</label>
                        </div>

                        <div class="custom-control custom-radio mb-2">
                            <input type="checkbox" id="orderStatus7" name="orderStatus[]" class="custom-control-input"
                                   value="failed" {{isset($orderstatus)?(in_array('failed', $orderstatus)?'checked':''):''}}>
                            <label class="custom-control-label"
                                   for="orderStatus7">{{translate('messages.failed')}}</label>
                        </div>
                        <div class="custom-control custom-radio mb-2">
                            <input type="checkbox" id="orderStatus8" name="orderStatus[]" class="custom-control-input"
                                   value="canceled" {{isset($orderstatus)?(in_array('canceled', $orderstatus)?'checked':''):''}}>
                            <label class="custom-control-label"
                                   for="orderStatus8">{{translate('messages.canceled')}}</label>
                        </div>
                     
                    @endif

                    <hr class="my-4">

                    <small class="text-cap mb-3">{{translate('messages.payment_status')}}</small>
                    <div class="mb-2 initial--21">
                        <select name="payment_status" data-title="{{ translate('messages.payment_status') }}"
                                data-placeholder="{{ translate('messages.payment_status') }}"
                                class="form-control js-select2-custom">
                            <option
                                value="all" {{isset($payment_status) &&  $payment_status== 'all' ?'selected':''}}>{{translate('messages.All')}}</option>
                            <option
                                value="paid" {{isset($payment_status) &&  $payment_status== 'paid' ?'selected':''}}>{{translate('messages.paid')}}</option>
                            <option
                                value="unpaid" {{isset($payment_status) &&  $payment_status== 'unpaid' ?'selected':''}}>{{translate('messages.unpaid')}}</option>
                        </select>
                    </div>
                    <hr class="my-4">

                    <small class="text-cap mb-3">{{translate('messages.payment_By')}}</small>
                    <div class="mb-2 initial--21">
                        <select name="payment_by" data-title="{{ translate('messages.payment_By') }}"
                                data-placeholder="{{ translate('messages.payment_By') }}"
                                class="form-control js-select2-custom">
                            <option
                                value="all" {{isset($payment_By) &&  $payment_By== 'all' ?'selected':''}}>{{translate('messages.All')}}</option>
                            <option
                                value="sender" {{isset($payment_By) &&  $payment_By== 'sender' ?'selected':''}}>{{translate('messages.Sender')}}</option>
                            <option
                                value="receiver" {{isset($payment_By) &&  $payment_By== 'receiver' ?'selected':''}}>{{translate('messages.Receiver')}}</option>
                        </select>
                    </div>

                    <hr class="my-4">

                    <small class="text-cap mb-3">{{translate('messages.date_between')}}</small>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group m-0">
                                <input type="date" name="from_date" class="form-control" id="date_from"
                                       value="{{isset($from_date)?$from_date:''}}">
                            </div>
                        </div>
                        <div class="col-12 text-center">----{{ translate('messages.to') }}----</div>
                        <div class="col-12">
                            <div class="form-group">
                                <input type="date" name="to_date" class="form-control" id="date_to"
                                       value="{{isset($to_date)?$to_date:''}}">
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer sidebar-footer">
                        <div class="row gx-2">
                            <div class="col">
                                <button type="reset" class="btn btn-block btn-white"
                                        id="reset">{{ translate('Clear all filters') }}</button>
                            </div>
                            <div class="col">
                                <button type="submit"
                                        class="btn btn-block btn-primary">{{ translate('messages.save') }}</button>
                            </div>
                        </div>
                    </div>
                    <!-- End Footer -->
                </form>
            </div>
        </div>
        <!-- End Order Filter Modal -->
        @endsection

        @push('script_2')
            <script src="{{asset('public/assets/admin')}}/js/view-pages/order-list.js"></script>
            <script>
                "use strict";
                $(document).on('ready', function () {
                    @if($filter_count>0)
                    $('#filter_count').html({{$filter_count}});
                    @endif

                    // INITIALIZATION OF DATATABLES
                    // =======================================================
                    let datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
                        dom: 'Bfrtip',
                        buttons: [
                            {
                                extend: 'copy',
                                className: 'd-none'
                            },
                            {
                                extend: 'excel',
                                className: 'd-none',
                                action: function (e, dt, node, config) {
                                    window.location.href = '{{route("admin.parcel.parcel_orders_export",['status'=>$status,'file_type'=>'excel','type'=>'parcel', request()->getQueryString()])}}';
                                }
                            },
                            {
                                extend: 'csv',
                                className: 'd-none',
                                action: function (e, dt, node, config) {
                                    window.location.href = '{{route("admin.parcel.parcel_orders_export",['status'=>$status,'file_type'=>'csv','type'=>'parcel', request()->getQueryString()])}}';
                                }
                            },

                            {
                                extend: 'print',
                                className: 'd-none'
                            },
                        ],
                        select: {
                            style: 'multi',
                            selector: 'td:first-child input[type="checkbox"]',
                            classMap: {
                                checkAll: '#datatableCheckAll',
                                counter: '#datatableCounter',
                                counterInfo: '#datatableCounterInfo'
                            }
                        },
                        language: {
                            zeroRecords: '<div class="text-center p-4">' +
                                '<img class="w-7rem mb-3" src="{{asset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="Image Description">' +

                                '</div>'
                        }
                    });
                    $('#export-copy').click(function () {
                        datatable.button('.buttons-copy').trigger()
                    });

                    $('#export-excel').click(function () {
                        datatable.button('.buttons-excel').trigger()
                    });

                    $('#export-csv').click(function () {
                        datatable.button('.buttons-csv').trigger()
                    });

                    $('#export-print').click(function () {
                        datatable.button('.buttons-print').trigger()
                    });

                    $('#datatableSearch').on('mouseup', function (e) {
                        let $input = $(this),
                            oldValue = $input.val();

                        if (oldValue == "") return;

                        setTimeout(function () {
                            let newValue = $input.val();

                            if (newValue == "") {
                                // Gotcha
                                datatable.search('').draw();
                            }
                        }, 1);
                    });

                    $('#toggleColumn_date').change(function (e) {
                        datatable.columns(2).visible(e.target.checked)
                    })

                    $('#toggleColumn_customer').change(function (e) {
                        datatable.columns(3).visible(e.target.checked)
                    })
                    $('#toggleColumn_store').change(function (e) {
                        datatable.columns(4).visible(e.target.checked)
                    })


                    $('#toggleColumn_total').change(function (e) {
                        datatable.columns(5).visible(e.target.checked)
                    })
                    $('#toggleColumn_order_status').change(function (e) {
                        datatable.columns(6).visible(e.target.checked)
                    })

                    $('#toggleColumn_actions').change(function (e) {
                        datatable.columns(7).visible(e.target.checked)
                    })
                });

                $('#reset').on('click', function () {
                    // e.preventDefault();
                    location.href = '{{url('/')}}/admin/order/filter/reset';
                });

            </script>
    @endpush
