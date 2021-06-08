<?php
    require_once('config.php');
    $item = array("CJ_ALU","CJ_COPPER","CJ_ZINC","GOLD","HOT_RSS","LON_COPPER","LON_NICKEL","NY_BASEOIL","PA","PP","SCREW","SPRING","US_NEWHOUSE");
    $temp_result = array();
    
    $i = 0;
    while($i < 13){
        $temp_query = "SELECT * FROM " . $item[$i] . " WHERE Date < 202200 AND Date > 201900";
        $query = $temp_query;                
        $stmt = $db_pdo->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchALL(PDO::FETCH_NUM);
        array_push($temp_result, $rows);
        $i++;
    }

    $result = json_encode($temp_result);
    echo $result;

?>