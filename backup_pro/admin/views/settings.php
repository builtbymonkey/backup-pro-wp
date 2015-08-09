<div class='wrap'>
<h2>Backup Pro Settings</h2>

<?php //$this->load->view('_includes/_errors'); ?>
<?php //$this->load->view('settings/_settings_nav'); ?>
<?php include 'settings/_settings_nav.php'; ?>

<div class="clear_left shun"></div>

<?php //echo form_open($query_base.'settings', array('id'=>'my_accordion'))?>
<input type="hidden" value="yes" name="go_settings" />
<input type="hidden" value="<?php echo $section; ?>" name="section" />
<?php 
switch($section)
{
	case 'cron':
	case 'db':
	case 'files':
	case 'license':
	case 'integrity_agent':
		$this->load->view('settings/_'.$section, array('settings' => $settings));
		break;

	default:
		//$this->load->view('settings/_general');
		break;
}

?>
<div class="tableFooter">
	<div class="tableSubmit">
		<?php //echo form_submit(array('name' => 'submit', 'value' => $lang->__('update_settings'), 'class' => 'submit'));?>
	</div>
</div>	
<?php //echo form_close()?>

<style>

#mainContent .pageContents {
    overflow: visible !important;
}
.group::after {
    visibility: visible !important;
}
</style>
</div>