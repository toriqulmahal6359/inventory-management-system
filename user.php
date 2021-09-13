<?php
    include('database_connection.php');

    if(!isset($_SESSION["type"]))
    {
        header("location:login.php");
    }
    if($_SESSION["type"] == 'master')
    {
        header("location:index.php");
    }

    include('header.php');
?>
<span class="alert alert-action"></span>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                    <div class="row">
                        <h3>User List</h3>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">

                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <table class="table table-bordered table-striped" id="user_data">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="userModal" class="modal fade">
    <div class="modal-dialog">
        <form action="POST" id="user_form">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus"></i>Add User</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Enter User Name</label>
                    <input type="text" name="user_name" id="user_name" class="form-control" required />
                </div>
                <div class="form-group">
                    <label>Enter User Email</label>
                    <input type="text" name="user_email" id="user_email" class="form-control" required />
                </div>
                <div class="form-group">
                    <label>Enter Password</label>
                    <input type="password" name="user_password" id="user_password" class="form-control" required />
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="user_id" id="user_id"/>
                <input type="hidden" name="btn_action" id="btn_action"/>
                <input type="submit" name="action" id="action" class="btn btn-info" value="Add"/>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function(){

        var userDataTable = $('#user_data').DataTable({
            "processing": true,
            "serverSide": true,
            "order":[],
            "ajax":{
                url: "user_fetch.php",
                type:"POST"
            },
            "columnDefs":[
                {
                    "target": [4,5],
                    "orderable":false
                }
            ],
            "pageLength": 25
        });

        $(document).on('submit', '#user_form', function(event){
            event.preventDefault();
            $('#action').attr('disabled', 'disabled');
            var form_data = $(this).serialize();
            $.ajax({
                url:"user_action.php",
                method:"POST",
                data:form_data,
                success:function(data){
                    $('#user_form')[0].reset();
                    $('#userModal').modal('hide');
                    $('#alert-action').fadeIn().html('<div class="alert alert-success">'+data+'</div>');
                    $('#action').attr('disabled', false);
                    userDataTable.ajax.reload();
                }
            })
        });

        $(document).on('click', '.update', function(){
            var user_id = $(this).attr("id");
            var btn_action = 'fetch_single';
            $.ajax({
                url: "user_action.php",
                method: "POST",
                data: {user_id:user_id, btn_action:btn_action},
                dataType: "json",
                success:function(data)
                {
                    $('#userModal').modal('show');
                    $('#user_name').val(data.user_name);
                    $('#user_email').val(data.user_email);
                    $('.modal_title').html('<i class="fa fa-pencil-square-o">Edit User</>');
                    $('#user_id').val(user_id);
                    $('#action').val('Edit');
                    $('#btn_action').val('Edit');
                    $('#user_password').attr('required', false);   
                }
            })
        });

        $(document).on('click', 'delete', function(event){
            var user_id = $(this).attr("id");
            var status = $(this).data('status');
            var btn_action = "delete";

            if(confirm("Are you sure want to change status ?"))
            {
                $.ajax({
                    url: "user_action.php",
                    method: "POST",
                    data: {user_id:user_id, status:status, btn_action:btn_action},
                    success:function(data)
                    {
                        $("#alert-action").fadeIn().html('<div class="alert alert-info">'+data+'</div>');
                        userDataTable.ajax.reload();
                    }
                })
            }
        });
    });
</script>