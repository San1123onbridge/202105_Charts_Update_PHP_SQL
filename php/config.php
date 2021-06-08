<?php
    try{
        $db_pdo = new PDO('mysql:host=localhost;dbname=db_futures_price_test',
                            'root',
                            'successful1');
    }catch(PDOException $e){
        echo "Couldnt connect mysql databases.  " . $e->getMessage(); 
    }
?>