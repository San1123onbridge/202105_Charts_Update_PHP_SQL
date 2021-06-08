<?php
    require_once('config.php');

    $item = $_POST['item_sel'];
    $year = $_POST['year_sel'];
    $year_s = $year;

    
    $tp1_year = $year_s + 1; //2021
    $tp2_year = $year - 1; //2019
    $tp3_year = $year - 2; //2018
    
    
    /* $query = "SELECT * FROM db_futures_price_test.CJ_ALU
              WHERE (Date / 100) <= :tp1 AND (Date / 100) >= :tp3"; 
    */
      
    $temp_query = "SELECT * FROM db_futures_price_test." . $item . " WHERE (Date / 100) <= :tp1 AND (Date / 100) >= :tp3";
    $query = $temp_query;

    if($stmt = $db_pdo->prepare($query)){        
        $stmt->bindParam(":tp1", $tp1_year, PDO::PARAM_INT);
        $stmt->bindParam(":tp3", $tp3_year, PDO::PARAM_INT);
    }
    
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_NUM);     
    $result = json_encode($rows);
    
    echo $result;
    
?>