<?php
    require_once('config.php');

    $query1 = "SELECT Date FROM CJ_COPPER_TEST WHERE Date > 202100 AND Week_price is not NuLL order by Date desc limit 1";
    $query2 = "SELECT Date FROM US_NEWHOUSE_TEST WHERE Date > 202100 AND Week_price is not NuLL order by Date desc limit 1";

    $stmt = $db_pdo->prepare($query1);
    $stmt->execute();    
    $rows1 = $stmt->fetchALL(PDO::FETCH_NUM);
    
    $result = array();
    array_push($result, $rows1);
    
    $stmt2 = $db_pdo->prepare($query2);
    $stmt2->execute();
    $rows2 = $stmt2->fetchAll(PDO::FETCH_NUM);
    array_push($result, $rows2);

    echo json_encode($result);
?>