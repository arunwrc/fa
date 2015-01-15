<?php
/**********************************************
Author: Swapna
Name: Purchase
Free software under GNU GPL
***********************************************/

$path_to_root = "../..";

include_once($path_to_root . "/purchasing/includes/db/purchasing_db.inc");


function purchase_all($filter, $from = null) {

	if ($from == null) $from = 0;

	$info = array();

	$sql = get_sql_for_supplier_inquiry($filter, '12/16/2014', '01/15/2015');

	$query = db_query($sql, "error");

	while($purchase = db_fetch($query, "error")) {
		$info[] = $purchase;
	}

	api_success_response(json_encode($info));
}

?>
