<?php  defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if( current_user_can('edit_others_pages') ) { 

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class hct_Community_List extends WP_List_Table {

	public $hctdd_orderby = 'id'; 
	public $hctdd_order = 'asc'; 
	public $hctdd_delete = '';
	public $hctdd_bare_url = '';
	public $hctdd_nonce_val = '';
	
	function __construct (){
		parent::__construct();
			
		if(isset($_GET['orderby']) && !empty($_GET['orderby'])) { $this->hctdd_orderby = $_GET['orderby']; }

		if(isset($_GET['order']) && !empty($_GET['order'])) { $this->hctdd_order = $_GET['order']; }

		if(isset($_GET['action']) && !empty($_GET['action']) && isset($_GET['editid']) && !empty($_GET['editid'])) { 
			$this->hctdd_delete = $_GET['editid'];
		}
		
		$this->hctdd_bare_url = 'admin.php?page=hctdd_add_dd';
		
		echo '<div class="wrap"><h2>Community List</h2> <a href="'.$this->hctdd_bare_url.'">Add New Member</a>';
		$this->prepare_items(); 
		$this->display(); 
		echo '</div>'; 
	}
	
	public function hct_date($datestring) {
		$hctdd_date = date('d M Y',strtotime($datestring));
		return $hctdd_date;
	}

	private function hctdd_get_data(){
		global $wpdb;
		$table_name = $wpdb->prefix."community_database";
		$hctdd_qstr = "SELECT id, name, gender, dob, married, email, mobile_no, service FROM $table_name ORDER BY $this->hctdd_orderby $this->hctdd_order";
		$hctdd_community_list = $wpdb->get_results($hctdd_qstr, OBJECT);
		$hctdd_sno = 0; $hctdd_list_arr = array();
		
		if ($hctdd_community_list) { 
			foreach($hctdd_community_list as $hctdd_dl){ 
				$hctdd_sno++;
				
				$hctdd_list_arr[] =  array('id' => $hctdd_dl->id, 'name' => $hctdd_dl->name, 'gender' => $hctdd_dl->gender, 'dob' => $this->hct_date($hctdd_dl->dob), 'married' => $hctdd_dl->married, 'email' => $hctdd_dl->email, 'mobile_no' => $hctdd_dl->mobile_no, 'service' => $hctdd_dl->service);				
			}
		}
		return $hctdd_list_arr;
	}
	
	function hctdd_del_data(){
		global $wpdb;
		$table_name = $wpdb->prefix."community_database";
		$hctdd_del_res =  $wpdb->delete( $table_name, array( 'id' => $this->hctdd_delete ), array( '%d' ) );
		
		if( $hctdd_del_res === FALSE) 
			echo '<div class="updated"><p ><strong>Deletion Error</strong></p></div>';
		else {
			echo '<div class="updated"><p ><strong>Member Record Deleted</strong></p></div>';
		}
	}	

	
	function column_name($item) {
		$actions = array(
			'edit'      => sprintf('<a href="?page=%s&action=%s&editid=%s">Edit</a>','hctdd_add_dd','edit',$item['id']),
			'delete'    => sprintf('<a href="?page=%s&action=%s&editid=%s">Delete</a>',$_REQUEST['page'],'delete',$item['id']),
		);

		return sprintf('%1$s %2$s', $item['name'], $this->row_actions($actions) );
	}
	
	function get_columns(){
	  $columns = array(
		'id' => 'ID',
		'name'    => 'Name',
		'gender'      => 'Gender',
		'dob' => 'Date of Birth',
		'married'    => 'Married',
		'mobile_no'      => 'Mobile No',
		'service' => 'Service',
	  );
	  return $columns;
	}
	
	//$this->current_action();
	//echo $action;
	
	function get_sortable_col() {
		$sortable_columns = array(
			'name'  => array('name',true),
			'gender' => array('gender',false),
			'dob'   => array('dob',false),
			'married'   => array('married',false)
		  );
		  return $sortable_columns;
	}
	
	function prepare_items() {
		if($this->hctdd_delete != '') { $this->hctdd_del_data(); }
		
		$columns = $this->get_columns();
		$get_up_data = $this->hctdd_get_data(); //var_dump($get_up_data);
		$hidden = array();
		$sortable = $this->get_sortable_col();
		$this->_column_headers = array($columns, $hidden, $sortable);
		
		$per_page = 10;
		$current_page = $this->get_pagenum();
		$total_items = count($get_up_data);
		$order_page = ($current_page - 1) * $per_page;
	
		$found_data = array_slice($get_up_data, $order_page, $per_page);
		
		$this->set_pagination_args( array(
		'total_items' => $total_items,
		'per_page'    => $per_page
		) );		
		$this->items = $found_data;
	}

	function column_default( $item, $column_name ) {
	  switch( $column_name ) { 
		case 'id':
		case 'name':
		case 'gender':
		case 'dob':
		case 'married':
		case 'email':
		case 'mobile_no':
		case 'service':
		  return $item[ $column_name ];
		default:
		  return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
	  }
	}
}

$hctCommunityList = new hct_Community_List();

}

