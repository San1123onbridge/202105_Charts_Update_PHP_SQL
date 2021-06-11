<?php
    require('config.php');

    $item = $_POST["show_sel"];
    $t_query = "SELECT * FROM $item WHERE Date > 202100 AND Week_PRICE is not null order by ID desc limit 3";
    $query = $t_query;
    $stmt = $db_pdo->prepare($query);
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_NUM);
    $result = json_encode($rows);
    
    echo $result;
?>