<?php

    include('database_connection.php');

    if(isset($_POST['btn_action']))
    {
        if($_POST['btn_action'] == 'Add')
        {
            $query = "INSERT INTO brand (category_id, brand_name) VALUES (:category_id, :brand_name)";
            $statement = $conn->prepare($query);
            $statement->execute(
                array(
                    ":category_id" => $_POST["category_id"],
                    ":brand_name"  => $_POST["brand_name"]
                )
            );

            $result = $statement->fetchAll();
            if(isset($result))
            {
                echo "Brand name added";
            }
        }
    }


?>