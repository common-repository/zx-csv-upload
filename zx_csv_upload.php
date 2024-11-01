<?php

function upload_new()
{
	global $wpdb;
	if(isset($_POST['upfile']))
	{
		if(empty($_POST['table_select'])) { ?>
			<h1>No Database Table was selected. Please select a Database Table</h1>
		<?php } else {
			$table_name = $_POST['table_select'];
		}
	
	if ($_FILES['uploaded'][size] > 0) { 

		//get the csv file 
		$fext=$file = $_FILES['uploaded']['name']; 
		$file = $_FILES['uploaded']['tmp_name']; 
		$ext = pathinfo($fext, PATHINFO_EXTENSION);
		if($ext=='csv')
		{
		/* $handle = fopen($file,"r"); 
		 //$table_name = $wpdb->prefix.'currency';
		$trunc= $wpdb->query("TRUNCATE TABLE '$table_name'");
		$wpdb->get_results( "SELECT * FROM '$table_name'");
		$nrows=$wpdb->num_rows;
		if($trunc || $nrows==0 )
		{
		 fgetcsv($handle);
		 //loop through the csv file and insert into database 
		do { 
			if ($data[0]) { 
				$wpdb->insert($table_name, array('id'=>$data[0],'curr_name'=>$data[1],'curr_code'=>$data[2],'curr_rate'=>$data[3]),array('%d','%s','%s','%s')); 
				} 
		} while ($data = fgetcsv($handle,1000,",","'")); 
		}
		else
		{
		  echo "Old Data Can't be Deleted";
		} */
		
		$handle = fopen($file,"r"); 
		
		$values = array();
		
		while(( $row = fgetcsv($handle)) !== false)  {  
			$values[] = '("' . implode('", "', $row) . '")';  
		}
		$db_cols = $wpdb->get_col( "DESC " . $table_name, 0 );
		$db_cols_implode = implode(',', $db_cols);				
		$values_implode = implode(',', $values);
		$sql = 'INSERT INTO '.$table_name . ' (' . $db_cols_implode . ') ' . 'VALUES ' . $values_implode;
		$db_query_insert = $wpdb->query($sql);
		if($db_query_insert){
		?>
		<div> <h1>Data Uploaded Successfully</h1></div>
		<?php }
		}
		else
		{
		?>
		<h1>You Must Upload Only CSV with .CSV Extension</h1>
		<?php
		}
	}
	else
	{
	?><h1>Oops! Something Went Wrong!</h1>Try again if problem persists contact Developer <?php
	}
	}
	

	if (isset($_POST['update_db'])) {							
			//get the csv file 
		$fext=$file = $_FILES['uploaded']['name']; 
		$file = $_FILES['uploaded']['tmp_name']; 
		$ext = pathinfo($fext, PATHINFO_EXTENSION);
		if($ext=='csv')
		{
		$handle = fopen($file,"r"); 
		
		$values = array();
		
		while(( $row = fgetcsv($handle)) !== false)  {  
			$values[] = '("' . implode('", "', $row) . '")';  
		} 
		}
		
		$updateOnDuplicate = ' ON DUPLICATE KEY UPDATE ';
		$values_implode = implode(',', $values);
		$db_cols = $wpdb->get_col( "DESC " . $_POST['table_select'], 0 );
		$db_cols_implode = implode(',', $db_cols);
		foreach ($db_cols as $db_col) {
			$updateOnDuplicate .= "$db_col=VALUES($db_col),";
		}
		$updateOnDuplicate = rtrim($updateOnDuplicate, ',');
		
		
		$sql = 'INSERT INTO '.$_POST['table_select'] . ' (' . $db_cols_implode . ') ' . 'VALUES ' . $values_implode.$updateOnDuplicate;
		$db_query_update = $wpdb->query($sql);
	}
	else {
		$sql = 'INSERT INTO '.$_POST['table_select'] . ' (' . $db_cols_implode . ') ' . 'VALUES ' . $values_implode;
		$db_query_insert = $wpdb->query($sql);
	}
	
	// If db db_query_update is successful
	if ($db_query_update) { ?>
		<h1>Congratulations!  The database has been updated successfully</h1>
	<?php }
	
	?>
	<div class="wrap">
		<form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			<table class="form-table">
				<h3>Import CSV Data</h3>
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
				<tr>
				<th scope="row"><?php _e('CSV File:','zx_csv_upload'); ?></th>
				<td>
					<input name="uploaded" type="file" id="csvfile"/>
				</td>
				</tr>
				<tr><th scope="row"></th><td><input type="submit" name="upfile" value="Upload File"></td><td><input type="submit" name="update_db" value="Update DB"></td></tr>
			</table>
		</form>
	</div>
<?php
}
add_action('upload_new', 'upload_new');
?>