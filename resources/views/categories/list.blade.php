@extends('layouts.app')

@section('content')
<style>
    .row,table .row
    {
        margin-left: 10px !important;
        margin-right: 10px !important;
    }
    </style>
<div class="page-wrapper">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div style="clear: both"></div>
                <div class="page-header-title">
                    <div class="d-inline">
                        <h4>Manage Categories</h4>
                        <!-- <span>lorem ipsum dolor sit amet, consectetur adipisicing elit</span> -->
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-right mb-2">
           
                <a href="{{ url('categories/create') }}" class="btn cur-p btn-sm btn-success" style="color: black;">Add Category</a>
            </div>
        </div>
    </div>
    <!-- Page-header end -->

    <!-- Page-body start -->
    <div class="page-body">
        <div class="row">
            <div class="col-sm-12">
                <!-- Zero config.table start -->
                <div class="card">
                    <div class="card-block">
                        <div class="dt-responsive table-responsive">
                        @include('common.error')
                        @php
                        $i=0;
                        // print_r($roles); die;
                        @endphp
                        <table id="simpletable" class="table table-striped table-bordered nowrap">
                                <thead>
                                <tr>
                                    <th width="20%">Sr. No.</th>
                                    <th width="20%">Parent Category Name</th>
                                    <th width="20%">Category Name</th>
                                    <th width="20%">Action</th>
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
    </div>
    <!-- Page-body end -->
</div>

<script src="{{ asset('assets\js\datatables.net\js\jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets\js\datatables.net-buttons\js\dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets\js\datatables.net-bs4\js\dataTables.bootstrap4.min.js') }}"></script>


<script>
    $(function(){
        var table = $('#simpletable').DataTable({
        "processing":true,
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "bFilter" : true,
        "bLengthChange": false,
        "ordering"  : false,
        "iDisplayLength" : 10,
        "responsive"  :true,
        "ajax": {
        "url": "{{ route('categories.index') }}",
        "type": "GET",
        "dataType": "json",
        "dataSrc": function (jsonData) {
        return jsonData.data;
      }
    },
    //Set column definition initialisation properties.
    "columnDefs": [
    {
        "targets": [ 0 ], //first column / numbering column
        "orderable": false, //set not orderable
      },
      ],
    });
        // dataTable();
    });

    function fn_delete(ball_id)
    {
       // alert(ball_id);
        bootbox.confirm({
            title: "",
            message: "Are you sure, you want to delete this Category?",
            callback: function (result) {
                if(result)
                {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    var url = '{{ route("categories.destroy", ":ball_id") }}';
                    url = url.replace(':ball_id', ball_id);

                    $.ajax({

                        type: "POST",
                        data:{
                            _method:"DELETE"
                        },
                        url: url,
                        //url: '{{--{{ route('role.destroy', $user_id) }}--}}',
                        dataType: 'JSON'

                    })
                    .done(function(response){
                        bootbox.alert({title: "Deleted?", message: response.message});
                        location.reload();
                    })
                    .fail(function(response){
                        bootbox.alert({title: "Deleted?",
                            message: 'Something Went Wrong..'});
                    })
                }
            }
        });
    }
</script>
@endsection
<!-- data-table js -->
