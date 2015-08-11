<div class='wrap'>
<h2>Backup Pro Settings</h2>

<?php include 'storage/_submenu.php'; ?>

    <br clear="all" />
	<h2><?php echo $view_helper->m62Lang('existing_storage_locations'); ?></h2>
	<table border="0" cellspacing="0" cellpadding="0" class="data "  width="100%" >
	<thead>
	<tr>
		<th><?php echo $view_helper->m62Lang('storage_location_name'); ?></th>
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
		{% for key, storage in storage_details %}
		<tr>
			<td><a href="{{ url('backuppro/settings/storage/edit') }}?id={{ key }}">{{ storage.storage_location_name }}</a></td>
			<td><div style="float:right"><img src="{{ resourceUrl('backuppro/images/storage/'~storage.storage_location_driver~'.png') }}" class="" title="{{ storage.storage_location_name }}"></div></td>
			<td><div style="float:right">{% if storage.storage_location_status == '1' %}Active{% else %}Inactive{% endif %}</div></td>
			<td><div style="float:right">{{ storage.storage_location_create_date|m62DateTime|raw }}</div></td>
			<td><div style="float:right">
			{% if can_remove %}
			<a href="{{ url('backuppro/settings/storage/remove') }}?id={{ key }}" class="delete icon" title="Delete" role="button"></a>
			{% endif %}
			</div></td>
		</tr>
		{% endfor %}
	<?php endif; ?>
	</tbody>
	</table>
	
</div>