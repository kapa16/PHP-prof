<?php

require_once __DIR__ . '/../../config/config.php';

if (empty($_REQUEST['apiMethod'])) {
    die;
}

$id = $_REQUEST['postData']['id'] ?? 0;
$orderStatus = $_REQUEST['postData']['status'] ?? 0;

switch ($_REQUEST['apiMethod']) {
    case 'changeOrderStatus':
        if (!$id || !$orderStatus) {
            error('Неверные данные');
        }
        changeOrderStatus($id, $orderStatus);
        success();
    case 'deleteProductFromOrder':
        if (!$id) {
            error('Не передан ID');
        }
        deleteRetrieveProductFromOrder($id, 1);
        success();
    case 'retrieveProductFromOrder':
        if (!$id) {
            error('Не передан ID');
        }
        deleteRetrieveProductFromOrder($id, 0);
        success();
}