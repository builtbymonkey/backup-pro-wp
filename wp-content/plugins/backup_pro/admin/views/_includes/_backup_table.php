<?php if(isset($enable_delete) && $enable_delete != 'yes' ): ?>
	<?php foreach($backups AS $backup): ?>
		<input type="hidden" name="backups[]" value="<?php echo urlencode($view_helper->m62Encode($backup['file_name'])); ?>" />
	<?php endforeach; ?>
<?php endif; ?>
<table width="100%" class="data existing_backups widefat" id="mainTable" border="0" cellpadding="0" cellspacing="0">
<thead>
	<tr class="odd">
		<th></th>
	
		<?php if(isset($enable_delete) && $enable_delete == 'yes' ): ?>
		<th style="padding:0;"><input name="bp_toggle_all" value="1" type="checkbox" class="bp_toggle_all" /></th>
		<?php endif; ?>
		<th></th>
		<th></th>
		<th class=""><?php echo $view_helper->m62Lang('taken'); ?></th>
		<?php if(isset($enable_type) && $enable_type == 'yes' ): ?>
		<th class=""><?php echo $view_helper->m62Lang('type'); ?></th>
		<?php endif; ?>
		<th class=""><?php echo $view_helper->m62Lang('file_size'); ?></th>
		<th class=""><?php echo $view_helper->m62Lang('time'); ?></th>
		<th><?php echo $view_helper->m62Lang('memory'); ?></th>
		<?php if(isset($enable_actions) && $enable_actions == 'yes' ): ?>
		<th class=""></th>
		<?php endif; ?>
	</tr>
</thead>
<tbody>
<?php 

    $count = 0;
    foreach($backups AS $backup): 

	if($backup['verified'] == '0')
	{
		$status_class = 'backup_pro_backup_warn';
	}
	elseif($backup['verified'] == 'success')
	{
		$status_class = 'backup_pro_backup_success';
	}
	elseif($backup['verified'] == 'fail')
	{
		$status_class = 'backup_pro_backup_fail';
	}
?>
<tr class="odd">
	<td class=" backup_pro_backup_status <?php echo $status_class; ?>"></td>
	<?php if(isset($enable_delete) && $enable_delete == 'yes' ): ?>
	<td><input name="backups[]" id="backup_check_<?php echo $count; ?>" value="<?php echo urlencode($view_helper->m62Encode($backup['file_name'])); ?>" type="checkbox" class="bp_toggle_check">
	
	</td>
	<?php endif; ?>
	<td style="white-space: nowrap">
    	<?php if(isset($backup['storage_locations']) && is_array($backup['storage_locations']) ): ?>
    		<?php foreach($backup['storage_locations'] AS $location_id => $storage): ?>
    			<img src="<?php echo $theme_folder_url; ?>backup_pro/admin/images/storage/<?php echo $view_helper->m62Escape($storage['icon']); ?>.png" class="" title="<?php echo $view_helper->m62Escape($storage['storage_location_name']); ?>">
    		<?php endforeach; ?>
    	<?php endif; ?>
	</td>
	<td style="width:55%">
		<?php if(isset($enable_editable_note) && $enable_editable_note == 'yes' ): ?>
		<div class="bp_editable" rel="<?php echo $view_helper->m62Escape($backup['hash']); ?>" id="note_div_<?php echo $backup['hash']; ?>"><?php if($backup['note'] == ''): ?><?php echo $view_helper->m62Lang('click_to_add_note');?><?php else: ?><?php echo $view_helper->m62Escape($backup['note']); ?> <?php endif; ?></div>
		<input name="note_<?php echo $view_helper->m62Escape($backup['hash']); ?>" value="<?php echo $backup['note']; ?>" id="note_<?php echo $view_helper->m62Escape($backup['hash']); ?>" data-backup-type="<?php echo $view_helper->m62Escape($backup['backup_type']); ?>" class="note_container" rel="<?php echo urlencode($view_helper->m62Encode($backup['file_name'])); ?>" style="display:none; width:100%" type="text">
		
		<?php else: ?>
            <?php echo ($backup['note'] == '' ? $view_helper->m62Lang('na') : $backup['note']); ?>
		<?php endif; ?>
	</td>
	<td style="white-space: nowrap">
		<!-- <?php echo $backup['created_date']; ?> --><?php echo $view_helper->m62DateTime($backup['created_date']); ?>
	</td>
	<?php if(isset($enable_type) && $enable_type == 'yes' ): ?>
	<td><?php echo $view_helper->m62Lang($backup['backup_type']); ?></td>
	<?php endif; ?>
	<td style="white-space: nowrap"><!-- <?php echo $view_helper->m62Escape($backup['compressed_size']); ?> --><?php echo $view_helper->m62FileSize($backup['compressed_size']); ?></td>
	<td style="white-space: nowrap"><!-- <?php echo $view_helper->m62Escape($backup['time_taken']); ?> --><?php echo $view_helper->m62TimeFormat($backup['time_taken']); ?></td>
	<td style="white-space: nowrap"><!-- <?php echo $view_helper->m62Escape($backup['max_memory']); ?> --><?php echo $view_helper->m62FileSize($backup['max_memory']); ?></td>
		<?php if(isset($enable_actions) && $enable_actions == 'yes' ): ?>
	<td align="right" style="width:40px; white-space: nowrap">
		<div style="float:right">
		
            <?php if( $backup['backup_type'] == 'database'): ?> 
            
            <?php if( $backup['can_restore'] ): ?>
    			<a class="btn btn-default"  href="<?php echo $url_base;?>&section=restore&type=database&id=<?php echo urlencode($view_helper->m62Encode($backup['details_file_name'])); ?>&type=<?php echo $view_helper->m62Escape($backup['backup_type']); ?>" title="<?php echo $view_helper->m62Lang('restore'); ?>" id="restore_link_<?php echo $count; ?>">
    				<img src="<?php echo $theme_folder_url; ?>backup_pro/admin/images/restore.png" alt="<?php echo $view_helper->m62Lang('restore'); ?>" class="">
    			</a> 
            <?php else: ?>
                <img src="<?php echo $theme_folder_url; ?>backup_pro/admin/images/restore.png" alt="<?php echo $view_helper->m62Lang('restore'); ?>" class="desaturate">
            <?php endif; ?>
			
		<?php endif; ?>
        <?php if( $backup['can_download'] ): 
        $encoded_name = urlencode($view_helper->m62Encode($backup['details_file_name']));
        $download_url = wp_nonce_url($url_base.'download&noheader=true&id='.$encoded_name.'&type='.$backup['backup_type'], $encoded_name);
        ?>
    		<a href="<?php echo $download_url;?>" title="<?php echo $view_helper->m62Lang('download'); ?>" id="download_link_<?php echo $count; ?>">
    			<img src="<?php echo $theme_folder_url; ?>backup_pro/admin/images/download.png" alt="<?php echo $view_helper->m62Lang('download'); ?>" class="">
    		</a> 
		<?php else: ?>
			<img src="<?php echo $theme_folder_url; ?>backup_pro/admin/images/download.png" alt="<?php echo $view_helper->m62Lang('download'); ?>" class="desaturate">
		<?php endif; ?>
		</div>
	</td>
	<?php endif; ?>	
</tr>
<?php $count++; endforeach; ?>
</tbody>
</table>