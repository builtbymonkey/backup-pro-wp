<div class='wrap'>
<h2>Backup Pro Settings</h2>

<?php //$this->load->view('_includes/_errors'); ?>
<?php //$this->load->view('settings/_settings_nav'); ?>
<?php include 'settings/_settings_nav.php'; ?>

<div class="clear_left shun"></div>

<form method="post" action="<?php echo $url_base; ?>settings">
<?php echo wp_nonce_field( 'bpsettings' ); ?>
<input type="hidden" value="yes" name="go_settings" />
<input type="hidden" value="<?php echo $section; ?>" name="section" />
<?php 
switch($section)
{
	case 'cron':
	case 'db':
	case 'files':
	case 'license':
	case 'api':
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

<p class="submit">
    <input name="m62_settings_submit" id="m62_settings_submit" class="button m62_settings_submit" value="<?php echo$view_helper->m62Lang('update_settings'); ?>" type="submit">
</p>

</form>

<style>

li.notice { color: red; }
</style>
</div>