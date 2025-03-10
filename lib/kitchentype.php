<?php

class kitchenType {

    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }
  
    public function selectKitchenType($kitchenType_id) {

        $sql = "select * from kitchen_type where id = $kitchenType_id";
        
        $result = mysqli_query($this->connection, $sql);
        $kitchenType = mysqli_fetch_array($result, MYSQLI_ASSOC);

        return($kitchenType);

    }


}
