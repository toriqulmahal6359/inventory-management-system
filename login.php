<?php
    //login.php
    include("database_connection.php");

    if(isset($_SESSION['type']))
    {
        header("location:index.php");
    }

    $message = '';
    if(isset($_POST["login"]))
    {
        $query = "SELECT * FROM user_details WHERE user_email= :user_email";
        $statement = $conn->prepare($query);

        $statement->execute(
                array(
                    'user_email' => $_POST["user_email"]
                )
            );
        
        $count = $statement->rowCount();

        if($count > 0)
        {
            $result = $statement->fetchAll();
            foreach($result as $row)
            {
                if($row['user_status'] == 'active')
                {
                    if(isset($_POST['user_password'])){
                    if($_POST['user_password'] == $row['user_password'])
                    // if(password_verify($_POST["user_password"], $row["user_password"]))
                    {
                        $_SESSION['type'] = $row['user_type'];
                        $_SESSION['user_id'] = $row['user_id'];
                        $_SESSION['user_name'] = $row['user_name'];
                        header("location:index.php");
                    }
                    else
                    {
                        $message = "<label>Wrong Password</label>";
                    }
                    }
                }
                else
                {
                    $message = "<label>Your account is Disabled, Please Contact to Master</label>";
                }
            }
        }
        else
        {
            $message = "<label>wrong Email Address</label>";
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inventory Management System</title>
    <script src="js/jquery-1.10.2.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>

</head>
<body>
    <br>
    <div class="container">
        <h2 align="center">Inventory Management System</h2>
        <br>
        <div class="panel panel-default">
            <div class="panel-heading">Login</div>
            <div class="panel-body">
                <form method="POST">
                <?php echo $message; ?>
                    <div class="form-group">
                        <label>User Email</label>
                        <input type="text" name="user_email" id="user_email" class="form-control" required/>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="user_password" id="user_password" class="form-control" required/>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="login" value="Login" class="btn btn-info" required/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>