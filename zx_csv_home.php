<?php

function zx_csv_plugin_home()
{
?>
<html>
	<div id="wrap">
	<br>
	<br>
	<form action="" name="ZXcontent" method="post">
		<tr valign="top">
				<th scope="row"><?php _e('Select Database Table:','zx_csv_upload'); ?></th>
				<td>
					<select id="table_select" name="table_select" value="">
						<option name="" value=""></option>
						
						<?php  // Get all db table names
						global $wpdb;
						$sql = "SHOW TABLES";
						$results = $wpdb->get_results($sql);
						$repop_table = isset($_POST['table_select']) ? $_POST['table_select'] : null;
						
						foreach($results as $index => $value) {
							foreach($value as $tableName) {
								?><option name="<?php echo $tableName ?>" value="<?php echo $tableName ?>" <?php if($repop_table === $tableName) { echo 'selected="selected"'; } ?>><?php echo $tableName ?></option><?php
							}
						}
						?>
					</select>
				</td> 
			</tr>
			<tr><td>
				From <input type="text" placeholder="Starting Row Number" name="rsltfrom"> 
				To <input type="text" placeholder="Ending Row Number" name="rsltto">
				<input type="submit" name="rsltsbmt">
			</td></tr>
	</form>
	<p> By default it shows First 10 Rows </p>
<?php
if(isset($_POST['rsltsbmt']))
{
	$table_name = $_POST['table_select'];
	$rlfrom=$_POST['rsltfrom'];
	$rlto=$_POST['rsltto'];
}
else
{
	//$table_name = $_POST['table_select'];
	$rlfrom=0;
	$rlto=10;
}
global $wpdb;
//$tname=$wpdb->prefix.'currency';
$result = $wpdb->get_results( "SELECT * FROM $table_name limit $rlfrom,$rlto");
?>
	<p>Rows from <?php echo $rlfrom;?> to <?php echo $rlto;?></p>
	<table name="tbl_content">
	<?php $column_names = $wpdb->get_col( 'DESC ' . $table_name, 0 );
		foreach ( $column_names as $column_name ) { ?>
			<th><strong><?php echo $column_name; ?></strong></th>
		<?php	}

	?>
		<!--th>ID</th><th>Currency Name</th><th>Currency Code</th><th>Rate</th-->
		<?php 
		foreach($result as $row)
		{
			echo "<tr>";
			foreach($row as $key=>$val)
			{
				
		?>
		
			<td><?php echo $val ?></td>
			<!--td><?php //echo $row->id ?></td>
			<td><?php// echo $row->curr_name ?></td>
			<td><?php //echo $row->curr_code ?></td>
			<td><?php //echo $row->curr_rate ?></td-->
			
		
		<?php } echo "</tr>";
		}
		?>
	</table>
	</div>
<?php
}
?>
