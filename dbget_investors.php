<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . "/includes/config.php";

extract($_REQUEST);

$response = array();


if (!isset($user_prompt)) {
    $user_prompt = '';
}

$db->where("md5(auto_id)", $_REQUEST['pid']);
$res = $db->getOne("business_funding_master");

if ($db->count) {
    $prompt = $user_prompt;
    $words = listWords($prompt);
    $output = json_decode($res['jsondata'], true);
    $country = $output['cover_page']['state'];
    $city = $output['cover_page']['city'];
    $investors = $output['amount_needed']['investorpreference'];

    $where_or_arr = array();
    foreach ($investors as $investor_type) {
        $where_or_arr[] = "FIND_IN_SET('" . $db->escape($investor_type) . "', investor_type) > 0";
    }
    $where_or = '';
    if (count($where_or_arr)) {
        $where_or = " AND (" . implode(" OR ", $where_or_arr) . ")";
    }
    
    $page = isset($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;
    $records_per_page = isset($_REQUEST['records_per_page']) ? (int)$_REQUEST['records_per_page'] : 500; // Default to 500
    $offset = ($page - 1) * $records_per_page;

    
    if ($page < 1) {
        $page = 1;
    }
    if ($records_per_page < 1) {
        $records_per_page = 500;
    }

    
    $query = "SELECT * FROM investor_list WHERE (city = '" . $db->escape($city) . "' OR country = '" . $db->escape($country) . "') " . $where_or . " ORDER BY company_name LIMIT $offset, $records_per_page";
    
    
    $result = $db->rawQuery($query);

    
    $response['draw'] = isset($_REQUEST['draw']) ? (int)$_REQUEST['draw'] : 0;

    
    $total_query = "SELECT COUNT(*) as total FROM investor_list WHERE (city = '" . $db->escape($city) . "' OR country = '" . $db->escape($country) . "') " . $where_or;
    $total_result = $db->rawQuery($total_query);
    $total_records = $total_result[0]['total'];

    $response['recordsTotal'] = $total_records;
    $response['recordsFiltered'] = $total_records;
    $response['data'] = array();

    
    foreach ($result as $row) {
        $response['data'][] = array(
            'company_name' => $row['company_name'],
            'contact_email' => $row['contact_email'],
            'domain' => '<a href="http://' . htmlspecialchars($row['domain']) . '" target="_blank">' . htmlspecialchars($row['domain']) . '</a>',
            'industries' => $row['industries'],
            'country' => $row['country'],
        );
    }
} else {
    $response['error'] = "No records found for the given PID.";
}
header('Content-Type: application/json');
echo json_encode($response);