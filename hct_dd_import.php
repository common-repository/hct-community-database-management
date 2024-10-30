<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if( current_user_can('edit_others_pages') ) { 

class hct_Community_Data {
	
	function __construct(){
		echo '<div class="wrap"><h2>Import CSV Data</h2>';
		if (!empty($_POST) && check_admin_referer('hct_nonce_import','hct_nonces')) { $this->hctdd_form_post(); }
		$this->hctdd_form(); 
		echo '</div>'; 
	}
	
	function hctdd_form() {
		echo '<form name="hctdd_import" id="hctdd_import" action="'.str_replace( '%7E', '~', $_SERVER['REQUEST_URI']).'" method="post" enctype="multipart/form-data">
		<table>
			<tr>
				<td>'.wp_nonce_field('hct_nonce_import','hct_nonces' ).'<input type="file" name="hctdd_file" id="hctdd_file">
				</td>
			</tr>
			<tr>
				<td>
					<input type="submit" name="submit" value="Submit">
				</td>
			</tr>
		</table>
		</form>';
	}
	
	public function hct_date($datestring) {
		$hctdd_date = '';
		if($datestring != ''){
			$hctdd_date = date('Y-m-d',strtotime($datestring));
		}
		return $hctdd_date;
	}
	
	function hctdd_form_post() {
		global $wpdb;
		$table_name = $wpdb->prefix."community_database";
		
		if ($_FILES["hctdd_file"]["error"] > 0) { echo "Error: " . $_FILES["hctdd_file"]["error"] . "<br>"; }
		else {
			//echo "Upload: " . $_FILES["hctdd_file"]["name"] . "<br>";
			//echo "Type: " . $_FILES["hctdd_file"]["type"] . "<br>";
			//echo "Size: " . ($_FILES["hctdd_file"]["size"] / 1024) . " Kb<br>";
			//echo "Stored in: " . $_FILES["hctdd_file"]["tmp_name"];
			$csv_file=$_FILES["hctdd_file"]["tmp_name"];

			if (($getfile = fopen($csv_file, "r")) !== FALSE) {
				$query = '';
				$data = fgetcsv($getfile, 1000, ",");
				
				while (($data = fgetcsv($getfile, 1000, ",")) !== FALSE) {
					$result = $data;
					$str = implode(",", $result);
					$slice = explode(",", $str);					
					
					$wpdb->insert($table_name, array(
						'name' => sanitize_text_field($slice[0]),
						'gender' => sanitize_text_field($slice[1]),
						'dob' => sanitize_text_field($this->hct_date($slice[2])),
						'married' => sanitize_text_field($slice[3]),	  
						'marriage_ani' => sanitize_text_field($this->hct_date($slice[4])),
						'occupation' => sanitize_text_field($slice[5]),
						'education' => sanitize_text_field($slice[6]),
						'email' => sanitize_email($slice[7]),
						'mobile_no' => sanitize_text_field($slice[8]),
						'emergency_no' => sanitize_text_field($slice[9]),
						'address' => sanitize_text_field($slice[10]),
						'service' => sanitize_text_field($slice[11]),	  
						'created_on' => sanitize_text_field($this->hct_date($slice[12]))
					));			
				}
				fclose($getfile);	
				
				if($wpdb->insert_id != false)
				{
					echo '<div class="updated"><p ><strong>Database updated</strong></p></div>';
				}
				else
				{
					echo '<div class="updated"><p ><strong>Error</strong></p></div>';
				}
			}
		}
	}
}

$hctCommunityData = new hct_Community_Data();

}