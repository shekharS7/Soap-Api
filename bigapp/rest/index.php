<?php
 
require_once '../include/DbHandler.php';
require '../libs/Slim/Slim.php';
 
\Slim\Slim::registerAutoloader();
 
$app = new \Slim\Slim();
 
// User id from db - Global Variable
$user_id = NULL;
 
/**
 * Verifying required params posted or not
 */
$app->post('/orders', function() use ($app) {
            // check for required params
            verifyRequiredParams(array('email', 'name','price','quantity'));
 
            $response = array();
 
            // reading post params
            $email = $app->request->post('email');
            $name = $app->request->post('name');
            $price = $app->request->post('price');
            $quantity = $app->request->post('quantity');
        
 
 
            $db = new DbHandler();
            $res = $db->createOrder($email, $name, $price, $quantity);
 
            if ($res == ORDER_CREATED_SUCCESSFULLY) {
                $response["error"] = false;
                $response["message"] = "Your order was successfully placed";
                echoRespnse(201, $response);
            } else {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while order placing";
                echoRespnse(200, $response);
            } 
        });


$app->put('/orders/:id', function($order_id) use($app) {
            // check for required params
            verifyRequiredParams(array('email','quantity'));
            $email = $app->request->put('email');
            $quantity = $app->request->put('quantity');
            $db = new DbHandler();
            $response = array();
            $result = $db->updateOrder($order_id, $email, $quantity);
            if ($result) {
                $response["error"] = false;
                $response["message"] = "order updated successfully";
            } else {
               
                $response["error"] = true;
                $response["message"] = "order failed to update. Please try again!";
            }
            echoRespnse(200, $response);
        });


$app->put('/orders/:id/cancel', function($order_id) use($app) {
            // check for required params
            $db = new DbHandler();
            $response = array();
            $result = $db->cancelOrder($order_id);
            if ($result) {
                $response["error"] = false;
                $response["message"] = "order cancel successfully";
            } else {
               
                $response["error"] = true;
                $response["message"] = "order failed to update. Please try again!";
            }
            echoRespnse(200, $response);
        });


$app->get('/orders/:id', function($order_id) {
            $response = array();
            $db = new DbHandler();

            // fetch task
            $result = $db->getOrder($order_id);

            if ($result != NULL) {
                $response["error"] = false;
                $response["id"] = $result["id"];
                $response["email"] = $result["email"];
                $response["status"] = $result["status"];
                $response["createdAt"] = $result["created_at"];
                $response["name"] = $result["name"];
                $response["price"] = $result["price"];
                $response["quantity"] = $result["quantity"];
                echoRespnse(200, $response);
            } else {
                $response["error"] = true;
                $response["message"] = "The requested resource doesn't exists";
                echoRespnse(404, $response);
            }
        });


function verifyRequiredParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }
 
    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoRespnse(400, $response);
        $app->stop();
    }
}


function echoRespnse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);
 
    // setting response content type to json
    $app->contentType('application/json');
 
    echo json_encode($response);
}
 


$app->run();
?>