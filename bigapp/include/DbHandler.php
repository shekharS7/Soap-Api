<?php

class DbHandler {
 
    private $conn;
 
    function __construct() {
        require_once dirname(__FILE__) . './DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }
 
    

    public function createOrder($email, $name, $price, $quantity) {
        $response = array();
 
        // First check if user already existed in db
      $today=date('Y-m-d H:i:s');
            // insert query
            $stmt = $this->conn->prepare("INSERT INTO orders(email_id, status, created_at) values(?, 'created', ?)");
            $stmt->bind_param("ss",$email,$today);
 
            $result = $stmt->execute();
            $order=$stmt->insert_id;
   $stmt->close();
            // Check for successful insertion
            if ($result) {
                 // User successfully inserted
                
                 $stmt1=$this->conn->prepare("INSERT INTO order_item(order_id, name, price, quantity,created_at) values(?,?,?,?,?)");
            $stmt1->bind_param("isiis",$order,$name,$price,$quantity,$today);
           $result1 = $stmt1->execute();
            $stmt1->close();
                    if($result1)
                    {
                        return ORDER_CREATED_SUCCESSFULLY;
                    }else{
                        return ORDER_CREATE_FAILED;
                    }
            } else {
                // Failed to create user
                return ORDER_CREATE_FAILED;
            }
        
 
        return $response;
    }


    public function updateOrder($order_id, $email, $quantity) {
           $now=date('Y-m-d H:i:s');
        $stmt = $this->conn->prepare("UPDATE orders o, order_item oi set o.email_id = ?, oi.quantity = ? ,o.updated_at= ? , oi.updated_at = ? WHERE o.id = ? AND o.id = oi.order_id ");
        $stmt->bind_param("sissi", $email, $quantity,$now,$now ,$order_id);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    public function getOrder($order_id) {
        $stmt = $this->conn->prepare("SELECT o.id, o.email_id, o.status, o.created_at, oi.name, oi.price, oi.quantity from orders o, order_item oi WHERE o.id = ? AND oi.order_id = o.id ");
        $stmt->bind_param("i", $order_id);
        if ($stmt->execute()) {
            $res = array();
            $stmt->bind_result($id, $email, $status, $created_at,$name,$price,$quantity);
            // TODO
            // $task = $stmt->get_result()->fetch_assoc();
            $stmt->fetch();
            $res["id"] = $id;
            $res["email"] = $email;
            $res["status"] = $status;
            $res["created_at"] = $created_at;
            $res["name"] = $name;
            $res["price"] = $price;
            $res["quantity"] = $quantity;
            $stmt->close();
            return $res;
        } else {
            return NULL;
        }
    }

        public function cancelOrder($order_id) {
               $now=date('Y-m-d H:i:s');
            $stmt = $this->conn->prepare("UPDATE orders o set o.status = 'cancelled',o.updated_at= ?  WHERE o.id = ? ");
            $stmt->bind_param("si",$now,$order_id);
            $stmt->execute();
            $num_affected_rows = $stmt->affected_rows;
            $stmt->close();
            return $num_affected_rows > 0;
        }



 
}
 
?>
