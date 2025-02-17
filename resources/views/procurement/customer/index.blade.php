@extends('layouts.app')

@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Customer Master</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>  
                                    <li class="breadcrumb-item active">Customer List</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-end col-md-7 mb-2 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button class="btn btn-warning btn-sm mb-50 mb-sm-0" data-bs-target="#filter" data-bs-toggle="modal"><i data-feather="filter"></i> Filter</button> 
                        <a class="btn btn-primary btn-sm" href="{{ route('customer.create') }}"><i data-feather="plus-circle"></i> Add New</a> 
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="table-responsive">
                                    <table class="datatables-basic table myrequesttablecbox"> 
                                        <thead>
                                            <tr>
                                                <th>S.NO.</th>
                                                <th>Customer Code</th>
                                                <th>Customer Name</th>
                                                <th>Type</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Category</th>
                                                <th>Sub Category</th>
                                                <th>Sales Person</th>
                                                <th>Status</th>
                                                <th>Action</th>
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
                <!-- Filter Modal -->
                <div class="modal modal-slide-in fade filterpopuplabel" id="filter">
                    <div class="modal-dialog sidebar-sm">
                        <form class="add-new-record modal-content pt-0">
                            <div class="modal-header mb-1">
                                <h5 class="modal-title" id="exampleModalLabel">Apply Customer Filter</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                            </div>
                            <div class="modal-body flex-grow-1">
                                <div class="mb-1">
                                    <label class="form-label">Category</label>
                                    <select id="filter-category" name="category_id" class="form-select">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-1">
                                    <label class="form-label">SubCategory</label>
                                    <select id="filter-subcategory" name="subcategory_id" class="form-select" data-selected-id="">
                                        <option value="">Select SubCategory</option>
                                    </select>
                                </div>

                                <!-- Sales Person -->
                                <div class="mb-1">
                                    <label class="form-label">Sales Person</label>
                                    <select id="filter-sales-person" class="form-select">
                                        <option value="">Select Sales Person</option>
                                        @foreach($salesPersons as $id => $name)
                                            <option value="{{ $name }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                  <!-- Customer Type -->
                                <div class="mb-1">
                                    <label class="form-label">Customer Type</label>
                                    <select id="filter-customer-type" class="form-select">
                                        <option value="">Select Customer Type</option>
                                        <option value="Individual">Individual</option>
                                        <option value="Organisation">Organisation</option>
                                    </select>
                                </div>
                                <!-- Status -->
                                <div class="mb-1">
                                    <label class="form-label">Status</label>
                                    <select id="filter-status" class="form-select">
                                        <option value="">Select Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="draft">Draft</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-start">
                                <button type="button" class="btn btn-primary apply-filter mr-1">Apply</button>
                                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        var dt_basic_table = $('.datatables-basic');
        
        function renderData(data) {
            return data ? data : 'N/A'; 
        }

        var dt_exchange_rate = dt_basic_table.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('customer.index') }}",
                data: function(d) {
                    d.customer_code = $('#filter-customer-code').val();
                    d.customer_name = $('#filter-customer-name').val();
                    d.customer_type = $('#filter-customer-type').val();
                    d.category_id = $('#filter-category').val(); 
                    d.subcategory_id = $('#filter-subcategory').val(); 
                    d.sales_person = $('#filter-sales-person').val(); 
                    d.status = $('#filter-status').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'customer_code', name: 'customer_code', render: renderData},
                {data: 'company_name', name: 'company_name', render: renderData},
                {data: 'customer_type', name: 'customer_type', render: renderData}, 
                {data: 'phone', name: 'phone', render: renderData},
                {data: 'email', name: 'email', render: renderData},
                { data: 'category.name', name: 'category.name', render: renderData }, 
                { data: 'subcategory.name', name: 'subcategory.name', render: renderData }, 
                { data: 'sales_person.name', name: 'sales_person.name', render: renderData }, 
                {data: 'status', name: 'status', render: renderData, orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            dom: '<"d-flex justify-content-between align-items-center mx-2 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-3 withoutheadbuttin dt-action-buttons text-end"B><"col-sm-12 col-md-3"f>>t<"d-flex justify-content-between mx-2 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            buttons: [
                {
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle',
                    text: feather.icons['share'].toSvg({ class: 'font-small-4 mr-50' }) + 'Export',
                    buttons: [
                        {
                            extend: 'print',
                            text: feather.icons['printer'].toSvg({ class: 'font-small-4 mr-50' }) + 'Print',
                            className: 'dropdown-item',
                            title: 'Customer Master',
                            exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6,7,8,9] }
                        },
                        {
                            extend: 'csv',
                            text: feather.icons['file-text'].toSvg({ class: 'font-small-4 mr-50' }) + 'Csv',
                            className: 'dropdown-item',
                            title: 'Customer Master',
                            exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6 ,7,8,9] }
                        },
                        {
                            extend: 'excel',
                            text: feather.icons['file'].toSvg({ class: 'font-small-4 mr-50' }) + 'Excel',
                            className: 'dropdown-item',
                            title: 'Customer Master',
                            exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6 ,7,8,9] }
                        },
                        {
                            extend: 'pdf',
                            text: feather.icons['clipboard'].toSvg({ class: 'font-small-4 mr-50' }) + 'Pdf',
                            className: 'dropdown-item',
                            title: 'Customer Master',
                            exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6 ,7,8,9] }
                        },
                        {
                            extend: 'copy',
                            text: feather.icons['copy'].toSvg({ class: 'font-small-4 mr-50' }) + 'Copy',
                            className: 'dropdown-item',
                            title: 'Customer Master',
                            exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6 ,7,8,9] }
                        }
                    ],
                    init: function(api, node, config) {
                        $(node).removeClass('btn-secondary'); 
                        $(node).parent().removeClass('btn-group');
                        setTimeout(function() {
                            $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
                        }, 50);
                    }
                }
            ],
            drawCallback: function() {
                feather.replace();  
            },
            language: {
                emptyTable: "No matching records found", 
                paginate: {
                    previous: '&nbsp;',
                    next: '&nbsp;'     
                }
            },
            search: { 
                caseInsensitive: true  
            }
        });

        $('.apply-filter').on('click', function() {
            dt_exchange_rate.ajax.reload(); 
            $('#filter').modal('hide'); 
        });
    });
</script>
@endsection

