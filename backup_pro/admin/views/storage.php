<div class='wrap'>
<h2>Backup Pro Settings</h2>

<?php include 'storage/_submenu.php'; ?>

    <br clear="all" />
	<h3><?php echo $view_helper->m62Lang('existing_storage_locations'); ?></h3>
	<table border="0" cellspacing="0" cellpadding="0" class="widefat"  width="100%" >
	<thead>
	<tr>
		<th><div style="float:left"><?php echo $view_helper->m62Lang('storage_location_name'); ?></div></th>
		<th><div style="float:right"><?php echo $view_helper->m62Lang('type'); ?></div></th>
		<th><div style="float:right"><?php echo $view_helper->m62Lang('status'); ?></div></th>
		<th><div style="float:right"><?php echo $view_helper->m62Lang('created_date'); ?></div></th>
		<th></th>
	</tr>
	</thead>
	<tbody>
	
	<?php if(count($storage_details) == 0): ?>
		<tr>
			<td colspan="5"><div class="no_backup_found"><?php echo $view_helper->m62Lang('no_storage_locations_created_yet')?></div></td>
		</tr>
	<?php else: ?>
        <?php foreach($storage_details AS $key => $storage): ?>
		<tr>
			<td><a href="<?php echo $url_base.'settings&section=storage&action=edit&id='.$key; ?>"><?php echo $storage['storage_location_name']; ?></a></td>
			<td><div style="float:right"><img src="<?php echo $theme_folder_url; ?>backup_pro/admin/images/storage/<?php echo $storage['storage_location_driver']?>.png" class="" title="<?php echo $storage['storage_location_name']; ?>"></div></td>
			<td><div style="float:right"><?php if ($storage['storage_location_status'] == '1'):?>Active<?php else: ?>Inactive<?php endif;?></div></td>
			<td><div style="float:right"><?php echo $view_helper->m62DateTime($storage['storage_location_create_date']); ?></div></td>
			<td><div style="float:right">
			<?php if($can_remove): ?>
			<a href="<?php echo $url_base.'settings&section=storage&action=remove&id='.$key; ?>" class="delete icon" title="Delete" role="button">Delete</a>
			<?php endif; ?>
			</div></td>
		</tr>
		<?php endforeach; ?>
	<?php endif; ?>
	</tbody>
	</table>
	
</div>