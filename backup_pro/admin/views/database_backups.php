<div class='wrap'>
<h2>Backup Pro Database Backups</h2>


<?php //include '_includes/_errors.php'; ?>
<?php include '_includes/_backups_submenu.php'; ?>

	<table class="widefat"  width="100%"  border="0" cellpadding="0" cellspacing="0">
	<thead>
		<tr class="even">
			<th><?php echo $view_helper->m62Lang('total_backups')?></th>
			<th style="width:65%"><?php echo $view_helper->m62Lang('total_space_used'); ?></th>
			<th><div style="float:right"><?php echo $view_helper->m62Lang('last_backup_taken'); ?></div></th>
			<th><div style="float:right"><?php echo $view_helper->m62Lang('first_backup_taken'); ?></div></th>
		</tr>
	</thead>
	<tbody>
		<tr class="odd">
			<td><?php echo $backup_meta['database']['total_backups']; ?></td>
			<td><?php echo $backup_meta['database']['total_space_used']; ?></td>
			<td><?php echo ($backup_meta['database']['newest_backup_taken'] != '' ? $view_helper->m62DateTime($backup_meta['database']['newest_backup_taken']) : $view_helper->m62Lang('na')); ?></td>
			<td><?php echo ($backup_meta['database']['oldest_backup_taken'] != '' ? $view_helper->m62DateTime($backup_meta['database']['oldest_backup_taken']) : $view_helper->m62Lang('na')); ?></td>
		</tr>
	</tbody>
	</table>	
	
	<h3><?=$view_helper->m62Lang('database_backups').' ('.count($backups['database']).')';?></h3>
	
	<?php if(count($backups['database']) == 0): ?>
		<div class="no_backup_found"><?php echo $view_helper->m62Lang('no_database_backups')?> <a href="<?php echo $nav_links['backup_db']; ?>"><?php echo $view_helper->m62Lang('would_you_like_to_backup_now')?></a></div>
	<?php else: ?>
	
	
		<form name="update_settings" action="{{ url('backuppro/delete/confirm') }}" method="post" accept-charset="UTF-8" />

		<input type="hidden" name="type" id="hidden_backup_type" value="database" />	
			{% include 'backuppro/_includes/_backup_table' with {'enable_type': 'no', '_backups': backups.database, 'enable_delete':'yes', 'enable_editable_note':'yes', 'enable_actions':'yes' } %}
		
		<div class="buttons right">
			<input type="submit" value="{{ "delete_backups"|m62Lang|t }}" class="btn submit" >
		</div>
		
		</form>
							
	<?php endif; ?>
</div>