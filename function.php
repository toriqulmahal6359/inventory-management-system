<?php
//function.php

function fill_category_list($conn)
{
    $query = "SELECT * FROM category WHERE category_status = 'Active' ORDER BY category_name ASC";
    $statement = $conn->prepare($query);
    $statement->execute();

    $result = $statement->fetchAll();

    $output ='';
    foreach($result as $row)
    {
        $output .= '<option value="'.$row["category_id"].'">'.$row["category_name"].'</option>';
    }
    return $output;
}


?>