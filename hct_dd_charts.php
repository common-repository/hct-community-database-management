<?php  defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if( current_user_can('edit_others_pages') ) { 

class hctdd_chart_class {
	function __construct() {
		$hct_orderby = 'gender';
		echo '<div class="wrap"><h2>Charts</h2>';
		if(!empty($_POST) && check_admin_referer('hct_nonce_charts','hct_nonces')) {			
			$hct_orderby = sanitize_text_field($_POST['hctdd_cinit']);
		}
		
		echo '<form name="hctdd_chart" id="hctdd_chart" action="'.str_replace( '%7E', '~', $_SERVER['REQUEST_URI']).'" method="post" enctype="multipart/form-data">
		<table>
			<tr>
				<td>'.wp_nonce_field('hct_nonce_charts','hct_nonces' ).'<label>Chart of: </label><select name="hctdd_cinit" id="hctdd_cinit">
						<option value="gender">Gender</option>
						<option value="married">Married</option>						
						<option value="occupation">Occupation</option>
						<option value="education">Education</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<input type="submit" name="submit" value="Submit">
				</td>
			</tr>
		</table>
		</form>';
		
		$this->hctdd_create_Chart($hct_orderby);
		echo '<div id="chart_div"></div>';
		echo '</div>';
		
	}
	
	function hctdd_arrayshift($hctarray){
		$harrcount = 0; $haar1 = array();
		$harrcount = count($hctarray);
		if($harrcount != 0){
			for($i = 0; $i < $harrcount; $i++)
			{
				$harrcount1 = 0;
				$harrcount1 = count($hctarray[$i]);
				if($harrcount1 != 0){
					for($j = 0; $j < $harrcount1; $j++)
					{
						$haar1[] = $hctarray[$i][$j];
					}
				}
			}	
		}
		return $haar1;
	}
	
	private function hctdd_create_Chart($hct_orderby) {
		global $wpdb;
		$table_name = $wpdb->prefix."community_database";
		$orderby = $hct_orderby;
		
		if($orderby == 'gender') { $chartTitle = 'Members by Gender'; }
		else if($orderby == 'married') { $chartTitle = 'Members by Marital Status'; }
		else if($orderby == 'occupation') { $chartTitle = 'Members by Occupation'; }
		else if($orderby == 'education') { $chartTitle = 'Members by Education'; }
		else { $chartTitle = 'Welcome'; }
		
		$hctdd_qstr = "SELECT $orderby FROM $table_name ORDER BY $orderby ASC";
		$hctdd_community_list = $wpdb->get_results($hctdd_qstr, ARRAY_A);
		$hctdd_sno = 0;
		$hctdd_sno = count($hctdd_community_list); $arr2 = array(); $arr1 = array(); $harr1 = array(); $harr2 = array();		

		if ($hctdd_sno !=0) { 	
			for($i = 0; $i < $hctdd_sno; $i++)
			{
				$arr1[]=array_values($hctdd_community_list[$i]);
			}

			$harr2 = $this->hctdd_arrayshift($arr1);
		}

		$chart_array = array_count_values($harr2);

		$gDataTitle = 'GenderStatus';
		$gDataTCount = 'GCount';

		$gChartData[] = array($gDataTitle, $gDataTCount);
		foreach($chart_array as $gkey => $gVal) {
			$gChartData[] = array($gkey, $gVal);
		}

		echo "<pre>";
		$jsonData=json_encode($gChartData);
		?>
		<script type="text/javascript">
			
			// Load the Visualization API and the piechart package.
			google.charts.load('current', {'packages':['corechart']});
			  
			// Set a callback to run when the Google Visualization API is loaded.
			google.charts.setOnLoadCallback(drawChart);
			
			
			function drawChart() {
				var options = {
					width: 800,
					height: 450,
					title: '<?php echo $chartTitle ?>',
					hAxis: {title: '<?php echo $hct_orderby ?>', titleTextStyle: {color: 'red'}}
				};
			  // Create our data table out of JSON data loaded from server.
			  var data = new google.visualization.arrayToDataTable(<?php echo $jsonData ?>);

			  // Instantiate and draw our chart, passing in some options.
			  var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
			  chart.draw(data, options);
			}

		</script>
<?php
	}
}

$hctChartClass = new hctdd_chart_class();

}