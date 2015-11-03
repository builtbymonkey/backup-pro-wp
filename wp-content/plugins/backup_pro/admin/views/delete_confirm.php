<div class='wrap'>
<h2>Backup Pro Dashboard</h2>


<?php //include '_includes/_errors.php'; ?>
<?php include '_includes/_backups_submenu.php'; ?>
<br clear="all" />

<h3><?php echo $view_helper->m62Lang('delete_backup'); ?> ( <?php echo count($backups); ?> )</h3>

<p><?php echo $view_helper->m62Lang('delete_backup_confirm'); ?></p>

<p class="notice"><?php echo $view_helper->m62Lang('action_can_not_be_undone'); ?></p>
<form name="remove_backups" action="<?php echo $url_base; ?>remove_backup" method="post"  />
	<input type="hidden" value="<?php echo $backup_type; ?>" name="type" />
	<?php echo wp_nonce_field( 'really_for_reals_remove_bp_backups' ); ?>
	<?php 
	$options = array('enable_type' => 'yes', 'enable_editable_note' => 'no', 'enable_actions' => 'no', 'enable_delete' => 'no');
	//$this->load->view('_includes/_backup_table', $options);
	extract($options);
	include '_includes/_backup_table.php';	
	?>

	<div class="buttons right">
        <?php submit_button($view_helper->m62Lang('delete'));?>
	</div>

</form>

</div>