<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); 

if( current_user_can('edit_others_pages') ) { 

class hct_Community_Export {
	
	function __construct(){
		echo '<div class="wrap"><h2>Export CSV Data</h2>';
		$this->hctdd_form(); 
		echo '</div>'; 
	}
	
	function hctdd_form() {
		echo '<form name="hctdd_export" id="hctdd_export" action="'.admin_url( 'admin-post.php' ).'" method="post">
			<table>
				<tr>
					<td>'.wp_nonce_field('hct_nonce_export','hct_nonces' ).'<input type="hidden" name="action" value="generate_csv">
						<label>Order By: </label><select name="hctdd_init" id="hctdd_init">
							<option value="id" selected>ID</option>
							<option value="name">Name</option>
							<option value="gender">Gender</option>
							<option value="married">Married</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<label>Order: </label><select name="hctdd_asc_dsc" id="hctdd_asc_dsc">
							<option value="ASC" selected>ASC</option>
							<option value="DESC">DESC</option>
						</select>
					</td>
				</tr>
				
				<tr>
					<td>
						<input type="submit" name="export" value="Export to CSV">
					</td>
				</tr>
			</table>
		</form>';
	}
}

$hctCommunityExport = new hct_Community_Export();

}
