<div class='wrap'>
    <h2>Backup Pro Database Backups</h2>
    <?php include '_includes/_backups_submenu.php'; ?>
<br clear="all" />
	
    <h2><?php echo $view_helper->m62Lang('restore_db'); ?></h2>
    
    <div id="_restore_details_table">
        <p><?php echo $view_helper->m62Lang('restore_db_question'); ?></p>
        <p style="color:red"><?php echo $view_helper->m62Lang('action_can_not_be_undone'); ?> <?php echo $view_helper->m62Lang('restore_double_speak'); ?></p>
    
    	<table border="0" cellspacing="0" cellpadding="0" class="widefat"  width="100%" >
    
    	<tbody>
    	<tr>
    		<td><strong><?php echo $view_helper->m62Lang('taken'); ?>:</strong></td>
    		<td><?php echo $view_helper->m62DateTime($backup['created_date']); ?></td>
    	</tr>
    	<tr>
    		<td><strong><?php echo $view_helper->m62Lang('backup_type'); ?>:</strong></td>
    		<td><?php echo $view_helper->m62Lang($backup['database_backup_type']); ?></td>
    	</tr>
    	<tr>
    		<td><strong><?php echo $view_helper->m62Lang('verified'); ?>:</strong></td>
    		<td>
    		<?php if( $backup['verified'] === 'success'): ?>
    			<span class=""><?php echo $view_helper->m62Lang('yes'); ?></span>
    		<?php else: ?>
    			<span style="color:red"><?php echo $view_helper->m62Lang('no'); ?></span>
    		<?php endif; ?>		
    		</td>
    	</tr>
    	<tr>
    		<td><strong><?php echo $view_helper->m62Lang('time_taken'); ?>:</strong></td>
    		<td><?php echo $view_helper->m62TimeFormat($backup['time_taken']); ?></td>
    	</tr>
    	<tr>
    		<td><strong><?php echo $view_helper->m62Lang('max_memory'); ?>:</strong></td>
    		<td><?php echo $view_helper->m62FileSize($backup['max_memory']); ?></td>
    	</tr>
    	<tr>
    		<td><strong><?php echo $view_helper->m62Lang('uncompressed_sql_size'); ?>:</strong></td>
    		<td><?php echo $view_helper->m62FileSize($backup['uncompressed_size']); ?></td>
    	</tr>
    	<tr>
    		<td><strong><?php echo $view_helper->m62Lang('total_tables'); ?>:</strong></td>
    		<td><?php echo $backup['item_count']; ?></td>
    	</tr>
    	<tr>
    		<td><strong><?php echo $view_helper->m62Lang('md5_hash'); ?>:</strong></td>
    		<td><?php echo $backup['hash']; ?></td>
    	</tr>
    	</tbody>
    	</table>
	</div>
	
	<div id="restore_running_details"  style="display:none" >
		<div id="backup_instructions">
		  <?php echo $view_helper->m62Lang('restore_in_progress_instructions'); ?>
		</div>			
		<br /><?php echo $view_helper->m62Lang('restore_in_progress'); ?>
		<img src="<?php echo $theme_folder_url; ?>backup_pro/admin/images/indicator.gif" id="animated_image" />
	</div>	

    <form action="<?php echo $url_base; ?>restore_database&id=<?php echo urlencode($view_helper->m62Encode($backup['details_file_name'])); ?>" method="post">
    <?php echo wp_nonce_field( 'restore_db_'.urlencode($view_helper->m62Encode($backup['details_file_name'])) ); ?>
    <p class="submit">
        <input name="submit" id="_restore_direct" class="button button-primary" value="Restore Database" type="submit">
    </p>
    
    </form>

</div>