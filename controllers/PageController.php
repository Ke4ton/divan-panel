<?php

include_once "DbController.php";

function num_pages($db_table_to_sort, $elements_per_page)
{
    global $db;
    $result = mysqli_query($db, "SELECT * from `$db_table_to_sort`;");
    return ceil(mysqli_num_rows($result)/$elements_per_page);
}
function num_pages_act_keys($elements_per_page)
{
    global $db;
    $result = mysqli_query($db, "SELECT * from `keys` WHERE `status` = 'activated';");
    return ceil(mysqli_num_rows($result)/$elements_per_page);
}