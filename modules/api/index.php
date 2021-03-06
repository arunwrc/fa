<?php
/**********************************************
Author: Andres Amaya
Name: SASYS REST API
Free software under GNU GPL

--> 15-July-2013:
- Added .htaccess
- GET with pagination
- Sales Methods

--> 14-June-2013:
- Added POST /locations/ To Add A Location Thanks to Richard Vinke

--> 6-Sept-2014
- Several bug fixes and additions Thanks to Cambell Prince
***********************************************/

ini_set('html_errors', false);
ini_set('xdebug.show_exception_trace', 0);
// ini_set('xdebug.auto_trace', 2);

include_once('config_api.php');

global $security_areas, $security_groups, $security_headings, $path_to_root, $db, $db_connections;

$page_security = 'SA_API';

include_once (API_ROOT . "/session-custom.inc");
include_once (API_ROOT . "/Slim/Slim/Slim.php");

\Slim\Slim::registerAutoloader();

include_once (API_ROOT . "/util.php");

include_once(FA_ROOT . "/includes/date_functions.inc");
include_once(FA_ROOT . "/includes/data_checks.inc");

// echo "sales quote => ".ST_SALESQUOTE;
// echo "sales order => ".ST_SALESORDER;
// echo "sales invoice => ".ST_SALESINVOICE;
// echo "cust delivery => ".ST_CUSTDELIVERY;
// echo "cust credit => ".ST_CUSTCREDIT;

$rest = new \Slim\Slim(array(
	'log.enabled' => true,
	'mode' => 'debug',
	'debug' => true
));
$rest->setName('SASYS');

// API Login Hook
api_login();

$req	= $rest->request();

define("RESULTS_PER_PAGE", 50);

// API Routes
//-----------------------------ADMIN---------------------------------
$rest->get('/company/', function() use ($rest){
	include_once (API_ROOT . "/admin.inc");
	company_get();
});

//-----------------------------ADMIN---------------------------------
$rest->get('/admin/', function() use ($rest){
	include_once (API_ROOT . "/admin.inc");
	admin_get();
});




// ------------------------------- Items -------------------------------
// Get Items
$rest->get('/inventory/', function() use ($rest){

	global $req;
	include_once (API_ROOT . "/inventory.inc");

	$page	= $req->get("page");

	if ($page == null) {
		inventory_all();
	} else {
		// If page = 1 the value will be 0, if page = 2 the value will be 1, ...
		$from = --$page * RESULTS_PER_PAGE;
		inventory_all($from);
	}
});

/***
** GET INVENTORY ITEMS BY STOCK ID STARTS----------------------
****/
$rest->get('/inventorybystockid/:stock_id/', function($stock_id) use ($rest){

	global $req;
	include_once (API_ROOT . "/inventory.inc");

	$page	= $req->get("page");

	if ($page == null) {
		inventory_by_stock_id($stock_id);
	} else {
		// If page = 1 the value will be 0, if page = 2 the value will be 1, ...
		$from = --$page * RESULTS_PER_PAGE;
		inventory_by_stock_id(stock_id,$from);
	}
});
/****/
/***
** GET INVENTORY ITEMS BY LOCATION LOW STOCK  STARTS----------------------
****/
$rest->get('/inventorybylocodelowstock/:loc_code', function($loc_code) use ($rest){
/***********/
global $req;
	include_once (API_ROOT . "/inventory.inc");

	$page	= $req->get("page");

	if ($page == null) {
		get_inventory_by_loc_code_low_stock($loc_code);
	} else {
		// If page = 1 the value will be 0, if page = 2 the value will be 1, ...
		$from = --$page * RESULTS_PER_PAGE;
		get_inventory_by_loc_code_low_stock($loc_code,$from);
	}
	
});
/****/
/***
** GET INVENTORY ITEMS BY LOCATION LOW STOCK ENDS------------------------
****/

/** GET INVENTORY ITEMS BY LOCATION STARTS----------------------
****/
$rest->get('/inventorybylocode/:loc_code', function($loc_code) use ($rest){
/***********/
global $req;
	include_once (API_ROOT . "/inventory.inc");

	$page	= $req->get("page");

	if ($page == null) {
		get_inventory_by_loc_code($loc_code);
	} else {
		// If page = 1 the value will be 0, if page = 2 the value will be 1, ...
		$from = --$page * RESULTS_PER_PAGE;
		get_inventory_by_loc_code($loc_code,$from);
	}
	
});
/****/
/***
** GET INVENTORY ITEMS BY LOCATION ENDS------------------------
****/


/****/
/***
** GET INVENTORY ITEMS BY LOCATION CATEGORY ID STARTS----------------------
****/

//////////////////////////////////////////
/*$rest->get('/sales/:trans_no/:trans_type', function($trans_no, $trans_type) use ($rest){
	include_once (API_ROOT . "/sales.inc");
	sales_get($trans_no, $trans_type);
});*/
//////////////////////////////////////////
$rest->get('/inventorybylocodecatid/:loc_code/:category_id', function($loc_code, $category_id) use ($rest){
/***********/
global $req;
	include_once (API_ROOT . "/inventory.inc");

	$page	= $req->get("page");

	
		get_inventory_by_loc_code_catid($loc_code, $category_id);
	
	
});
/****/
/***
** GET INVENTORY ITEMS BY LOCATION CATEGORY ID ENDS------------------------
****/

//***********
// Get Items
$rest->get('/inventory/:category_id/', function($category_id) use ($rest){
	
	global $req;
	include_once (API_ROOT . "/inventory.inc");
	$page	= $req->get("page");

	if ($page == null) {
		inventory_all($category_id);
	} else {
		// If page = 1 the value will be 0, if page = 2 the value will be 1, ...
		$from = --$page * RESULTS_PER_PAGE;
		inventory_all($category_id,$from);
	}
});
//***********
// Get Specific Item by Stock Id
$rest->get('/inventory/:id', function($id) use ($rest) {

	include_once (API_ROOT . "/inventory.inc");
	inventory_get($id);

});
// Add Item
$rest->post('/inventory/', function() use ($rest){
	include_once (API_ROOT . "/inventory.inc");
	inventory_add();

});
// Edit Specific Item
$rest->put('/inventory/:id', function($id) use ($rest){
	include_once (API_ROOT . "/inventory.inc");
	inventory_edit($id);

});
// Delete Specific Item
$rest->delete('/inventory/:id', function($id) use ($rest){
	include_once (API_ROOT . "/inventory.inc");
	inventory_delete($id);

});
// ------------------------------- Items -------------------------------

// ------------------------------- Inventory Movements -------------------------------
// Get Inventory Movement Types
$rest->get('/movementtypes/', function() use ($rest){
	include_once (API_ROOT . "/inventory.inc");
	inventory_movementstype_all();

});
// ------------------------------- Inventory Movements -------------------------------

// ------------------------------- Inventory Locations -------------------------------
// Get Locations
$rest->get('/locations/', function() use ($rest){
	include_once (API_ROOT . "/inventory.inc");
	
	inventory_locations_all();
});

$rest->get('/locations/:loc_code', function($loc_code) use ($rest){
	include_once (API_ROOT . "/inventory.inc");
	
	inventory_location($loc_code);
	

});




// Add Location, added by Richard Vinke
$rest->post('/locations/', function() use ($rest){
	include_once (API_ROOT . "/inventory.inc");
	inventory_location_add();

});
// ------------------------------- Inventory Locations -------------------------------

// ------------------------------- Stock Adjustments -------------------------------
// Add Stock Adjustment
$rest->post('/stock/', function() use ($rest){
	include_once (API_ROOT . "/inventory.inc");
	stock_adjustment_add();

});
// ------------------------------- Stock Adjustments -------------------------------

// ------------------------------- Item Categories -------------------------------
// Get Items Categories
$rest->get('/category/', function() use ($rest){
	global $req;
	include_once (API_ROOT . "/category.inc");

	$page	= $req->get("page");

	if ($page == null) {
		category_all(null);
	} else {
		// If page = 1 the value will be 0, if page = 2 the value will be 1, ...
		$from = --$page * RESULTS_PER_PAGE;
		category_all($from);
	}
});


//Get category count
$rest->get('/categorycount/', function() use ($rest){
	include_once (API_ROOT . "/category.inc");
	category_count();
});


// Get Specific Item Category
$rest->get('/category/:id', function($id) use ($rest) {
	include_once (API_ROOT . "/category.inc");
	category_get($id);

});
// Add Item Category
$rest->post('/category/', function() use ($rest){
	include_once (API_ROOT . "/category.inc");
	category_add();

});
// Edit Item Category
$rest->put('/category/:id', function($id) use ($rest){
	include_once (API_ROOT . "/category.inc");
	category_edit($id);

});
// Delete Item Category
$rest->delete('/category/:id', function($id) use ($rest){
	include_once (API_ROOT . "/category.inc");
	category_delete($id);

});


// ------------------------------- Item Categories -------------------------------

// ------------------------------- Tax Types -------------------------------
// Tax Types
// Get All Item Tax Types
$rest->get('/taxtypes/', function() use ($rest){
	global $req;
	include_once (API_ROOT . "/taxtypes.inc");

	$page	= $req->get("page");

	if ($page == null){
		taxtypes_all();
	}	else {
		// If page = 1 the value will be 0, if page = 2 the value will be 1, ...
		$from = --$page * RESULTS_PER_PAGE;
		taxtypes_all($from);
	}
});
// ------------------------------- Tax Types -------------------------------

// ------------------------------- Tax Groups -------------------------------
// Tax Groups
// Get All Tax Groups
$rest->get('/taxgroups/', function() use ($rest){
	global $req;
	include_once (API_ROOT . "/taxgroups.inc");

	$page	= $req->get("page");

	if ($page == null) {
		taxgroups_all();
	} else {
		// If page = 1 the value will be 0, if page = 2 the value will be 1, ...
		$from = --$page * RESULTS_PER_PAGE;
		taxgroups_all($from);
	}
});
// ------------------------------- Tax Groups -------------------------------

// ------------------------------- Customers -------------------------------
// Customers



// Get Customer By Name
$rest->get('/customername/:name', function($name) use ($rest){
include_once (API_ROOT . "/customers.inc");
customer_by_name($name);

});

// Get Customer General Info
$rest->get('/customers/:id', function($id) use ($rest){
	include_once (API_ROOT . "/customers.inc");
	customer_get($id);

});

// Get Customer and Branch General Info
$rest->get('/customer_n_branch/:id/:trans_type', function($id,$trans_type) use ($rest){
	include_once (API_ROOT . "/customers.inc");
	customer_n_branch_get($id,$trans_type);

});

// All Customers
$rest->get('/customers/', function() use ($rest){
	global $req;
	include_once (API_ROOT . "/customers.inc");

	$page	= $req->get("page");

	if ($page == null) {
		customer_all();
	} else {
		// If page = 1 the value will be 0, if page = 2 the value will be 1, ...
		$from = --$page * RESULTS_PER_PAGE;
		customer_all($from);
	}
});
// Add Customer
$rest->post('/customers/', function() use ($rest){
	include_once (API_ROOT . "/customers.inc");
	customer_add();

});
// Edit Customer
$rest->put('/customers/:id', function($id) use ($rest){
	include_once (API_ROOT . "/customers.inc");
	customer_edit($id);

});
// Delete Customer
$rest->delete('/customers/:id', function($id) use ($rest){
	include_once (API_ROOT . "/customers.inc");
	customer_delete($id);

});
// Get Customer Branches
$rest->get('/customers/:id/branches/', function($id) use ($rest){
	include_once (API_ROOT . "/customers.inc");
	customer_branches_get($id);

});
// ------------------------------- Customers -------------------------------

// ------------------------------- Suppliers -------------------------------
// Suppliers
// All Suppliers
$rest->get('/suppliers/', function() use ($rest){
	global $req;
	include_once (API_ROOT . "/suppliers.inc");

	$page = $req->get("page");

	supplier_all($page);
});
// Get Supplier General Info
$rest->get('/suppliers/:id', function($id) use ($rest){
	include_once (API_ROOT . "/suppliers.inc");
	supplier_get($id);

});
// Add Supplier
$rest->post('/suppliers/', function() use ($rest){
	
	include_once (API_ROOT . "/suppliers.inc");
	supplier_add();

});
// Edit Supplier
$rest->put('/suppliers/:id', function($id) use ($rest){
	include_once (API_ROOT . "/suppliers.inc");
	supplier_edit($id);

});
// Delete Supplier
$rest->delete('/suppliers/:id', function($id) use ($rest){
	include_once (API_ROOT . "/suppliers.inc");
	supplier_delete($id);

});
// Get Supplier Contacts
$rest->get('/suppliers/:id/contacts/', function($id) use ($rest){
	include_once (API_ROOT . "/suppliers.inc");
	supplier_contacts_get($id);

});
// ------------------------------- Suppliers -------------------------------



// ------------------------------- SalesMan -------------------------------
// SalesMan

// Add SalesMan
/*$rest->post('/salesman/', function() use ($rest){
	
	include_once (API_ROOT . "/salesman.inc");
	salesman_add();

});*/

// Edit SalesMan

$rest->post('/salesman/', function($id) use ($rest){
	include_once (API_ROOT . "/salesman.inc");

	if($id > 0)
		salesman_edit($id);
	else
		salesman_add();

});

// ------------------------------- SalesMan -------------------------------




// ------------------------------- Bank Accounts -------------------------------
// Bank Accounts
// Get All Bank Accounts
$rest->get('/bankaccounts/', function() use ($rest){
	global $req;
	include_once (API_ROOT . "/bankaccounts.inc");

	$page	= $req->get("page");

	if ($page == null) {
		bankaccounts_all();
	} else {
		// If page = 1 the value will be 0, if page = 2 the value will be 1, ...
		$from = --$page * RESULTS_PER_PAGE;
		bankaccounts_all($from);
	}
});
// Get Specific Bank Account
$rest->get('/bankaccounts/:id', function($id) use ($rest){
	include_once (API_ROOT . "/bankaccounts.inc");
	bankaccounts_get($id);

});
// ------------------------------- Bank Accounts -------------------------------

// ------------------------------- GL Accounts -------------------------------
// GL Accounts
// Get GL Accounts
$rest->get('/glaccounts/', function() use ($rest){
	global $req;
	include_once (API_ROOT . "/glaccounts.inc");

	$page	= $req->get("page");

	if ($page == null) {
		glaccounts_all();
	} else {
		// If page = 1 the value will be 0, if page = 2 the value will be 1, ...
		$from = --$page * RESULTS_PER_PAGE;
		glaccounts_all($from);
	}
});
// Get Specific GL Account
$rest->get('/glaccounts/:id', function($id) use ($rest){
	include_once (API_ROOT . "/glaccounts.inc");
	glaccounts_get($id);

});
// Get GL Account Types
$rest->get('/glaccounttypes/', function() use ($rest){
	include_once (API_ROOT . "/glaccounts.inc");
	glaccounttypes_all();

});
// ------------------------------- GL Accounts -------------------------------

// ------------------------------- Currencies -------------------------------
// Currencies
// Get All Currencies
$rest->get('/currencies/', function() use ($rest){
	global $req;
	include_once (API_ROOT . "/currencies.inc");

	$page	= $req->get("page");

	if ($page == null) {
		currencies_all();
	} else {
		// If page = 1 the value will be 0, if page = 2 the value will be 1, ...
		$from = --$page * RESULTS_PER_PAGE;
		currencies_all($from);
	}
});
// Get Specific Currency
$rest->get('/currencies/:id', function($id) use ($rest){
	include_once (API_ROOT . "/currencies.inc");
	currencies_get($id);

});
// Get Last Exchange Rate
$rest->get('/exrates/:curr_abrev', function($curr_abrev) use ($rest){
	include_once (API_ROOT . "/currencies.inc");
	currencies_last_exrate($curr_abrev);

});
// ------------------------------- Currencies -------------------------------

// ------------------------------- Inventory Costs -------------------------------
// Inventory Costs
// Get Item Cost
$rest->get('/itemcosts/:id', function($id) use ($rest){
	include_once (API_ROOT . "/items.inc");
	itemcosts_get($id);

});
// Update Item Cost
$rest->put('/itemcosts/:id', function($id) use ($rest){
	include_once (API_ROOT . "/items.inc");
	itemcosts_update($id);

});

//Get product count
$rest->get('/catproductcount/:cat', function($cat) use ($rest){

	include_once (API_ROOT . "/inventory.inc");
	
	cat_product_count($cat);
	
});

// ------------------------------- Inventory Costs -------------------------------

// ------------------------------- Assets -------------------------------
// Fixed Assets
function assets_supported() {
	global $path_to_root;
	return file_exists($path_to_root . '/modules/asset_register');
}
if (assets_supported()) {
	// Get Fixed Asset
	$rest->get('/assets/:id', function($id) use ($rest){
		include_once (API_ROOT . "/assets.inc");
		assets_get($id);

	});
	// Insert Fixed Asset
	$rest->post('/assets/', function() use ($rest){
		include_once (API_ROOT . "/assets.inc");
		assets_add();

	});
	// Get Asset Types
	$rest->get('/assettypes/', function() use ($rest){
		global $req;
		include_once (API_ROOT . "/assets.inc");

		$page	= $req->get("page");

		if ($page == null) {
			assettypes_all();
		} else {
			// If page = 1 the value will be 0, if page = 2 the value will be 1, ...
			$from = --$page * RESULTS_PER_PAGE;
			assettypes_all($from);
		}
	});
}
// ------------------------------- Assets -------------------------------

// ------------------------------- Sales Order Entry -------------------------------
// Sales
// Get Sales Header and Details
$rest->get('/sales/:trans_no/:trans_type', function($trans_no, $trans_type) use ($rest){
	include_once (API_ROOT . "/sales.inc");
	sales_get($trans_no, $trans_type);
});
// Insert Sales
$rest->post('/sales/', function() use ($rest){
	include_once (API_ROOT . "/sales.inc");
	sales_add();

});
// Edit Sales
$rest->put('/sales/:trans_no/:trans_type', function($trans_no, $trans_type) use ($rest){
	include_once (API_ROOT . "/sales.inc");
	sales_edit($trans_no, $trans_type);

});
// Cancel Sales
$rest->delete('/sales/:branch_id/:uuid', function($branch_id, $uuid) use ($rest) {
	include_once(API_ROOT . "/sales.inc");
	sales_cancel($branch_id, $uuid);
});
// All Sales
$rest->get('/sales/:trans_type/', function($trans_type) use ($rest){
	global $req;
	include_once (API_ROOT . "/sales.inc");

	$page	= $req->get("page");

	if ($page == null) {
		sales_all($trans_type);
	} else {
		// If page = 1 the value will be 0, if page = 2 the value will be 1, ...
		$from = --$page * RESULTS_PER_PAGE;
		sales_all($trans_type, $from);
	}
});
// All Sales By Location
$rest->get('/getsalesbylocation/:trans_type/:location', function($trans_type, $location) use ($rest){
	global $req;
	include_once (API_ROOT . "/sales.inc");

	$page	= $req->get("page");

	if ($page == null) {
		sales_by_location($trans_type, $location);
	} else {
		// If page = 1 the value will be 0, if page = 2 the value will be 1, ...
		$from = --$page * RESULTS_PER_PAGE;
		sales_by_location($trans_type, $location, $from);
	}
});
// ------------------------------- Sales Order Entry -------------------------------

// Insert Sales invoice
$rest->post('/salesinvoice/', function() use ($rest){
    include_once (API_ROOT . "/sales.inc");
    salesinvoice_add();

});


// ------------------------------- Cancel A Sale -------------------------------
// Void Sale



$rest->post('/voidsale/', function() use ($rest){
include_once (API_ROOT . "/sales.inc");
void_add();

});

// ------------------------------- Cancel A Sale -------------------------------


//<<<<<<<<<<<<----------get tax transaction for salesinvoice-----------------------

$rest->get('/taxtransaction/:trans_no',function($trans_no) use ($rest){
	include_once(API_ROOT . "/sales.inc");
	get_tax_items($trans_no);
});

//----------------------get tax transaction for salesinvoice -->>>>>>>>>>>>>>>>>>>>




// ------------------------------- Purchase -------------------------------

// All purchase
$rest->get('/purchase/:trans_type/', function($trans_type) use ($rest){
	global $req;
	include_once (API_ROOT . "/purchase.inc");

	$page	= $req->get("page");

	if ($page == null) {
		purchase_all($trans_type);
	} else {
		// If page = 1 the value will be 0, if page = 2 the value will be 1, ...
		$from = --$page * RESULTS_PER_PAGE;
		purchase_all($trans_type, $from);
	}
});

// All purchase
$rest->post('/purchasesearch/', function() use ($rest){
    include_once (API_ROOT . "/purchase.inc");
    purchase_search();

});



// Init API
$rest->run();

?>
