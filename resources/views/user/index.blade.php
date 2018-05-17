@extends('layouts.main')

@section('content')


<div class="main-content">
   <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="#">User</a>
                    </li>

                    <li>
                        <a href="#">Index</a>
                    </li>

            </ul><!-- /.breadcrumb -->
        </div>
        <br>
        <div class="col-xs-12">
            <table id="real-table" class="table table-bordered table-hover">
                <thead>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Create Time</th>
                    <th>Process</th>
                </thead>
            </table>    
        </div>
    </div>
</div>

<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">权限设置</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group" id="radio_div">

                     </div>
                    <div class="form-group" id="radio_role">
                        
                     </div>                     
                    </div><!--form control-->  
                     <div class="col-lg-12">
                        <input type="hidden" id='power_id' value="" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-xs" id ="addPower">Confirm</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
   <script type="text/javascript">
        $(function() {
            $('#real-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: '{{ route("user.tableGet") }}',
                    type: 'post'
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'created_at', name: 'created_at'},
                    {
                        name: 'actions',
                        data: null,
                        sortable: false,
                        searchable: false,
                        render: function (data) {
                            var actions = '';
                            actions += '<a class="btn btn-xs btn-danger btn-minier" onclick="deleteOrder('+data.id+')"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"></i></a>';
                            return actions
                        }
                    }                    
                  
                ],
                searchDelay: 500,
                // order: [[2,"desc"]]
            });
        });



       




        function deleteOrder(id){
            swal({
              title: "Are you sure?",
              text: "Your will not be able to recover this imaginary file!",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "Yes, delete it!",
              cancelButtonText: "No, cancel plx!",
              closeOnConfirm: false,
              closeOnCancel: false
            },
            function(isConfirm){
              if (isConfirm) {
                $.ajax({
                    type        : 'post',
                    url         : '/user/delete',
                    headers     : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data        : {'id':id},
                    success     : function(e){
                        if(e.errCode  == 200){
                            swal("Deleted!", "Your imaginary file has been deleted.", "success");
                            setTimeout("window.location.reload()",1000);
                        }else{
                            swal("Warning",e.errMsg,"warning");
                        }
                    },
                    error    : function(e) {
                        swal("Warning","出错了！请联系管理人员","warning");
                    }
                });
              } else {
                swal("Cancelled", "Your imaginary file is safe :)", "error");
              }
            });
        }

      
    </script>
@endsection 




