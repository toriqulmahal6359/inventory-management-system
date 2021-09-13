<?php
    //profile.php
    include("database_connection.php");

    if(!isset($_SESSION["type"]))
    {
        header("location:login.php");
    }

    if(isset($_SESSION["user_id"]) != NULL){
        $query = "SELECT * FROM user_details WHERE user_id = '".$_SESSION["user_id"]."' ";

        $statement = $conn->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();

        $name = "";
        $email = "";
        $user_id = "";

        foreach($result as $row)
        {
            $name = $row['user_name'];
            $email = $row['user_email'];
            $user_id = $row['user_id'];
        }
    }

    include("header.php");
?>

<div class="panel panel-default">
    <div class="panel-heading">Edit Profile</div>
    <div class="panel-body">
        <form method="post" id="edit_profile_form">
            <span id="message"></span>
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="user_name" id="user_name" class="form-control" value="<?php echo isset($name)? $name : ''; ?>" required/>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="user_email" id="user_email" class="form-control" required value="<?php echo isset($email)? $email : ''; ?>" />
            </div>
            <hr>
            <label>Leave Password Blank If you don't want to change</label>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="user_new_password" id="user_new_password" class="form-control" />
            </div>
            <div class="form-group">
                <label>Re-enter Password</label>
                <input type="password" name="user_re_enter_password" id="user_re_enter_password" class="form-control" />
                <span id="error_password"></span>
            </div>
            <div class="form-group">
                <input type="submit" name="edit_profile" id="edit_profile" value="Edit" class="btn btn-info" />
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#edit_profile_form').on('submit', function(event){
            event.preventDefault();
            if($('#user_new_password').val() != ""){
                if($('#user_new_password').val() != $('#user_re_enter_password').val())
                {
                    $('#error_password').html('<label class="text-danger">Password does not match</label>');
                }
                else
                {
                    $('#error_password').html();
                }
            }
            $('#edit_profile').attr('disabled', 'disabled');
            var form_data = $(this).serialize();
            $.ajax({
                url:"edit_profile.php",
                method:"POST",
                data:form_data,
                success:function(data)
                {
                    $('#edit_profile').attr('disabled', false);
                    $('#user_new_password').val('');
                    $('#user_re_type_password').val('');
                    $('#message').html(data);
                }
            });
        });
    });
</script>