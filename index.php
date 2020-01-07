<?php
require_once ('app/api/Filter_Api.php');
$environment = include __DIR__.'/environment.php';
try {
    $api = new \app\api\Filter_Api($environment);
    $api->run_api()->run();
} catch (Exception $e) {
    echo json_encode(Array('error' => $e->getMessage(),'code'=>$e->getCode()));
}
