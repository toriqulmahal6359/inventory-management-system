<?php

    include('database_connection.php');
    include('function.php');

    if(!isset($_SESSION['type']))
    {
        header('loacation:login.php');
    }

    if($_SESSION['type'] == 'master')
    {
        header('location:index.php');
    }

    include('header.php');

?>

    <span class="alert_action"></span>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="col-md-10">
                        <h3 class="panel-title">Brand List</h3>
                    </div>
                    <div class="col-md-2" align="right">
                        <button type="button" name="add" id="add_button" class="btn btn-success btn-xs">Add</button>
                    </div>
                </div>
                <div class="panel-body">
                    <table id="brand_table" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Category</th>
                                <th>Brand</th>
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

    <div class="modal fade" id="brandModal">
        <div class="modal-dialog">
            <form method="post" id="brand_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-plus"></i>Add Brand</h4>
                    </div>
                    <div class="modal-body">
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            <?php echo fill_category_list($conn); ?>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="brand_id" name="brand_id" />
                        <input type="hidden" id="btn_action" name="btn_action" />
                        <input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
                    </div>
                </div>
            </form>
        </div>
    </div>

<script>
    $(document).ready(function(){

        $('#add_button').click(function(){
            $('#brandModal').modal('show');
            $('#brand_form')[0].reset();
            $('.modal-title').html("<i class='fa fa-plus'></i>Add brand");
            $('#action').val("Add");
            $('#btn_action').val("Add");
        });

        $(document).on('submit', '#brand_form', function(event){
            event.preventDefault();

            $('#action').attr('disabled', 'disabled');
            var form_data = $(this).serialize();
            $.ajax({
                url: "brand-action.php",
                method: "POST",
                data: form_data,
                success:function(data)
                {
                    $('#brand_form')[0].reset();
                    $('#brandModal').modal('hide');
                    $('#alert_action').fadeIn().html('<div class="alert alert-success">'+data+'</div>');
                    $('#action').attr('disabled', false);
                    brandDataTable.ajax.reload();
                }
            })
        });



        var brandDataTable = $('#brand_data').DataTable({
            "processing": true,
            "serverSide:": true,
            "order": [],
            "ajax": {
                url: "brand_fetch.php",
                type: "POST",
            },
            "columnDefs":[
                {
                    "targets": [4,5],
                    "orderable": false,
                }
            ],
            "pageLength": 10
        });
    });
</script>

<?php
    include('footer.php');
?>