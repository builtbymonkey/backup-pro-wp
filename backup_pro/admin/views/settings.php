<div class='wrap'>
<h2>Backup Pro Settings</h2>

<?php //$this->load->view('_includes/_errors'); ?>
<?php //$this->load->view('settings/_settings_nav'); ?>
<?php include 'settings/_settings_nav.php'; ?>

<div class="clear_left shun"></div>

<form method="post">
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
		include 'settings/_'.$section.'.php';
		break;

	default:
		include 'settings/_general.php';
		break;
}

?>
<div class="tableFooter">
	<div class="tableSubmit">
		<?php //echo form_submit(array('name' => 'submit', 'value' => $lang->__('update_settings'), 'class' => 'submit'));?>
	</div>
</div>	
<?php submit_button($view_helper->m62Lang('update_settings'));?>

</form>

</div>