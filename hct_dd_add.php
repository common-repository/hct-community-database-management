<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if( current_user_can('edit_others_pages') ) { 

class hct_Community_Add {
	
	function __construct(){
		echo '<div class="wrap">';
		$this->hctdd_form_post();
		$this->hctdd_form(); 
		echo '</div>'; 
	}
	
	public $hctdd_name = '';	public $hctdd_gend = '';	public $hctdd_dofb = '';	public $hctdd_marr = 'No';	
	public $hctdd_anni = '';	public $hctdd_occu = '';	public $hctdd_educ = '';	public $hctdd_emai = '';
	public $hctdd_mobi = '';	public $hctdd_emer = '';	public $hctdd_addr = '';	public $hctdd_service = '';	
	public $hctdd_sqid = '';	public $hctdd_action = '';	public $hctdd_editid = '';
	public $hct_sel_options = ''; public $hctdd_h2_form = 'Add New Member';				public $args = array();
	
	function hctdd_selectoption($selVal, $opVal) { 
		$this->hct_sel_options = '';
		
		if($selVal=='gender'){ $this->args['gender'] = array('Male', 'Female'); }
		else if($selVal=='married'){ $this->args['married'] = array('No', 'Yes'); }
		else {  }
		
		$arrLen = count($this->args["$selVal"]);
		
		for($i=0; $i<$arrLen; $i++)
		{
			$selected = '';
			if(strcmp($this->args[$selVal][$i],$opVal)==0) { $selected = 'selected'; }
			$this->hct_sel_options .= '<option value="'.$this->args[$selVal][$i].'" '.$selected.'>'.$this->args[$selVal][$i].'</option>';
		}
		
		return $this->hct_sel_options;
	}
	
	function hctdd_get_data($hctdd_editid) {
		global $wpdb; $hctdd_list_arr = array();
		$table_name = $wpdb->prefix."community_database";
		$hctdd_qstr = "SELECT id, name, gender, dob, married, marriage_ani, occupation, education, email, mobile_no, emergency_no, address, service FROM $table_name WHERE id= $hctdd_editid";
		$hctdd_dl = $wpdb->get_row($hctdd_qstr);
		
		if ($hctdd_dl) {
				$hctdd_list_arr =  array('id' => $hctdd_dl->id, 'name' => $hctdd_dl->name, 'gender' => $hctdd_dl->gender, 'dob' => $hctdd_dl->dob,  'married' => $hctdd_dl->married, 'marriage_ani' => $hctdd_dl->marriage_ani, 'occupation' => $hctdd_dl->occupation, 'education' => $hctdd_dl->education, 'email' => $hctdd_dl->email, 'mobile_no' => $hctdd_dl->mobile_no, 'emergency_no' => $hctdd_dl->emergency_no, 'address' => $hctdd_dl->address, 'service' => $hctdd_dl->service);
		}
		return $hctdd_list_arr;
	}
	
	function hctdd_form() {
		if($this->hctdd_sqid != '') { $this->hctdd_h2_form = 'Update New Member'; }
		
		echo '<h2>'.$this->hctdd_h2_form.'</h2><form name="hctdd_form" method="post" action="'.str_replace( '%7E', '~', $_SERVER['REQUEST_URI']).'">'.wp_nonce_field('hct_nonce_member','hct_nonces' ).'<input type="hidden" name="hctdd_sqid" value="'.esc_html($this->hctdd_sqid).'">
		<div class="hctdd_row">
			<div class="hctdd_label">
				<label>Name: * </label>
			</div>
			<div class="hctdd_input">
				<input type="text" name="hctdd_name" id="hctdd_name" title="Alphabet and Space Only 30 Max" pattern="[A-Za-z]+|[A-Za-z]+\s[A-Za-z]+|[A-Za-z]+\s[A-Za-z]+\s[A-Za-z]+" minlength="1" maxlength="30" required autofocus value="'.esc_html($this->hctdd_name).'">
			</div>
		</div>
		<div class="hctdd_row">
			<div class="hctdd_label">
				<label>Gender: * </label>
			</div>
			<div class="hctdd_input">				
				<select name="hctdd_gend" id="hctdd_gend" required>'.$this->hctdd_selectoption($selVal = "gender", $this->hctdd_gend).'</select>
			</div>
		</div>
		<div class="hctdd_row">
			<div class="hctdd_label">
				<label>Date of Birth: * </label>
			</div>
			<div class="hctdd_input">
				<input type="date" name="hctdd_dofb" id="hctdd_dofb" placeholder="YYYY-MM-DD" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" value="'.esc_html($this->hctdd_dofb).'">
			</div>
		</div>
		<div class="hctdd_row">
			<div class="hctdd_label">
				<label>Married: * </label>
			</div>
			<div class="hctdd_input">
				<select name="hctdd_marr" id="hctdd_marr">'.$this->hctdd_selectoption($selVal = "married", $this->hctdd_marr).'</select>
			</div>
		</div>
		<div class="hctdd_row">
			<div class="hctdd_label">Marriage Anniversary:  </label>
			</div>
			<div class="hctdd_input">
				<input type="date" name="hctdd_anni" id="hctdd_anni" placeholder="YYYY-MM-DD" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" value="'.esc_html($this->hctdd_anni).'">
			</div>
		</div>
		<div class="hctdd_row">
			<div class="hctdd_label">
				<label>Occupation: * </label>
			</div>
			<div class="hctdd_input">
				<input type="text" name="hctdd_occu" id="hctdd_occu" minlength="1" maxlength="30" value="'.esc_html($this->hctdd_occu).'" required >
			</div>
		</div>
		<div class="hctdd_row">
			<div class="hctdd_label">
				<label>Education: * </label>
			</div>
			<div class="hctdd_input">
				<input type="text" name="hctdd_educ" id="hctdd_educ" minlength="1" maxlength="30" value="'.esc_html($this->hctdd_educ).'">
			</div>
		</div>
		<div class="hctdd_row">
			<div class="hctdd_label">
				<label>Email:  </label>
			</div>
			<div class="hctdd_input">
				<input type="email" name="hctdd_emai" id="hctdd_emai"  value="'.esc_html($this->hctdd_emai).'">
			</div>
		</div>
		<div class="hctdd_row">
			<div class="hctdd_label">
				<label>Mobile No: * </label>
			</div>
			<div class="hctdd_input">
				<input type="tel" name="hctdd_mobi" id="hctdd_mobi" placeholder="10 Digits only eg: 9000000000" pattern="\d{10}" value="'.esc_html($this->hctdd_mobi).'" required>
			</div>
		</div>
		<div class="hctdd_row">
			<div class="hctdd_label">
				<label>Emergency No:  </label>
			</div>
			<div class="hctdd_input">
				<input type="text" name="hctdd_emer" id="hctdd_emer"placeholder="10 Digits eg: 9000000000" pattern="\d{10}" value="'.esc_html($this->hctdd_emer).'">
			</div>
		</div>
		<div class="hctdd_row">
			<div class="hctdd_label">
				<label>Address: * </label>
			</div>
			<div class="hctdd_input">
				<textarea name="hctdd_addr" id="hctdd_addr" rows="5" cols="50" minlength="1" maxlength="50" required>'.esc_html($this->hctdd_addr).'</textarea>
			</div>
		</div>	
		<div class="hctdd_row">
			<div class="hctdd_label">
				<label>Service: * </label>
			</div>
			<div class="hctdd_input">
				<textarea name="hctdd_service" id="hctdd_service" minlength="1" maxlength="50" rows="5" cols="50">'.esc_html($this->hctdd_service).'</textarea>
			</div>
		</div>
		
        <p class="submit">
        <input type="submit" name="Submit" value="'.$this->hctdd_h2_form.'" />
        </p>
    </form>';
	}
	
	function hctdd_sanitize_text($hctdd_textVal) {
		$hctdd_safe_text = sanitize_text_field($hctdd_textVal);
		if (!$hctdd_safe_text) {
		  $hctdd_safe_text = '';
		}

		if (strlen($hctdd_safe_text)>50) {
		  $hctdd_safe_text = substr($hctdd_safe_text,0,50);
		}
		return $hctdd_safe_text;
	}
	
	function hctdd_sanitize_number($hctdd_numVal) {
		$hctdd_safe_num = preg_replace("%[^0-9]%", "", $hctdd_numVal );

		if (strlen($hctdd_safe_num)>10) {
		  $hctdd_safe_num = substr($hctdd_safe_num,0,10);
		}
		return $hctdd_safe_num;
	}
	
	function hctdd_sanitize_tarea($hctdd_tareaVal) {
		
		$hctdd_safe_tarea = sanitize_text_field($hctdd_tareaVal);
		if (!$hctdd_safe_tarea) {
		  $hctdd_safe_tarea = '';
		}

		if (strlen($hctdd_safe_tarea)>80) {
		  $hctdd_safe_tarea = substr($hctdd_safe_tarea,0,80);
		}
		return $hctdd_safe_tarea;
	}
	
	function hctdd_sanitize_email($hctdd_emailVal) {
		
		$hctdd_safe_email = is_email(sanitize_email($hctdd_emailVal));
		if (!$hctdd_safe_email) {
		  $hctdd_safe_email = '';
		}
		return $hctdd_safe_email;
	}
	
	function hctdd_form_post() {
	
		if (!empty($_POST) && check_admin_referer('hct_nonce_member','hct_nonces')) {
			global $wpdb;
			$table_name = $wpdb->prefix . "community_database";
			
			$this->hctdd_sqid = $this->hctdd_sanitize_number($_POST['hctdd_sqid']); // Id 
			$this->hctdd_name = $this->hctdd_sanitize_text($_POST['hctdd_name']); // Name
			$this->hctdd_gend = $this->hctdd_sanitize_text($_POST['hctdd_gend']); // Gender
			$this->hctdd_dofb = $this->hctdd_sanitize_text($_POST['hctdd_dofb']); // Date of Birth
			$this->hctdd_marr = $this->hctdd_sanitize_text($_POST['hctdd_marr']); // Married Status
			$this->hctdd_anni = $this->hctdd_sanitize_text($_POST['hctdd_anni']); // Mariage Anniversary
			$this->hctdd_occu = $this->hctdd_sanitize_text($_POST['hctdd_occu']); // Occupation
			$this->hctdd_educ = $this->hctdd_sanitize_text($_POST['hctdd_educ']); // Education
			$this->hctdd_emai = $this->hctdd_sanitize_email($_POST['hctdd_emai']); // email
			$this->hctdd_mobi = $this->hctdd_sanitize_number($_POST['hctdd_mobi']); // Mobile No
			$this->hctdd_emer = $this->hctdd_sanitize_text($_POST['hctdd_emer']); // Emergency Number
			$this->hctdd_addr = $this->hctdd_sanitize_tarea($_POST['hctdd_addr']); // Address
			$this->hctdd_service = $this->hctdd_sanitize_tarea($_POST['hctdd_service']); // Service
			
			if($this->hctdd_sqid == '') {
				$wpdb->insert($table_name, array(
					'name' => $this->hctdd_name,
					'gender' => $this->hctdd_gend,
					'dob' => $this->hctdd_dofb,
					'married' => $this->hctdd_marr,	  
					'marriage_ani' => $this->hctdd_anni,
					'occupation' => $this->hctdd_occu,
					'education' => $this->hctdd_educ,
					'email' => $this->hctdd_emai,
					'mobile_no' => $this->hctdd_mobi,
					'emergency_no' => $this->hctdd_emer,
					'address' => $this->hctdd_addr,
					'service' => $this->hctdd_service
				), 
				array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s') 
				);
				if($wpdb->insert_id != false)
				{
					echo '<div class="updated"><p ><strong>New Member Added</strong></p></div>';
				}
				else
				{
					echo '<div class="updated"><p ><strong>Error</strong></p></div>';
				}
			}
			else
			{
				$hct_update = $wpdb->update($table_name, 
					array( 
						'name' => $this->hctdd_name,
						'gender' => $this->hctdd_gend,
						'dob' => $this->hctdd_dofb,
						'married' => $this->hctdd_marr,	  
						'marriage_ani' => $this->hctdd_anni,
						'occupation' => $this->hctdd_occu,
						'education' => $this->hctdd_educ,
						'email' => $this->hctdd_emai,
						'mobile_no' => $this->hctdd_mobi,
						'emergency_no' => $this->hctdd_emer,
						'address' => $this->hctdd_addr,
						'service' => $this->hctdd_service
					), 
					array( 'id' => $this->hctdd_sqid ), 
					array( '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s'), 
					array( '%d' ) 
				);
				if( $hct_update === FALSE) 
					echo '<div class="updated"><p ><strong>Updation Error</strong></p></div>';
				else {
					echo '<div class="updated"><p ><strong>Member Record Updated</strong></p></div>';
				}
			}
		}
		else if(isset($_GET['action']) && !empty($_GET['action']) && isset($_GET['editid']) && !empty($_GET['editid'])) { 
			$this->hctdd_action = $_GET['action'];
			$this->hctdd_editid = $_GET['editid'];
			
			$hctdd_sdata = $this->hctdd_get_data($this->hctdd_editid);
			$this->hctdd_sqid = $hctdd_sdata['id'];				$this->hctdd_name = $hctdd_sdata['name'];
			$this->hctdd_gend = $hctdd_sdata['gender'];			
			$this->hctdd_dofb = $this->hctdd_empty_date($hctdd_sdata['dob']);	
			$this->hctdd_marr =$hctdd_sdata['married'];	
			$this->hctdd_anni = $this->hctdd_empty_date($hctdd_sdata['marriage_ani']);
			$this->hctdd_occu = $hctdd_sdata['occupation'];		$this->hctdd_educ = $hctdd_sdata['education'];
			$this->hctdd_emai = $hctdd_sdata['email'];			$this->hctdd_mobi = $hctdd_sdata['mobile_no'];
			$this->hctdd_emer = $hctdd_sdata['emergency_no'];	$this->hctdd_addr = $hctdd_sdata['address'];
			$this->hctdd_service = $hctdd_sdata['service'];
		}
		else {
		}
	}
	
	function hctdd_empty_date($hctdatedata) {
		if($hctdatedata == '0000-00-00'){
			$hctdatedata = '';
		}
		return $hctdatedata;
	}
}
$hctCommunityAdd = new hct_Community_Add();

}


