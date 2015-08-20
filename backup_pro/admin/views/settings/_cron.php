<h3  class="accordion"><?=$view_helper->m62Lang('configure_cron')?></h3>
<?php 
if(count($backup_cron_commands) >= 1)
{
	$this->table->set_heading(
		array('data' => $view_helper->m62Lang('backup_type'), 'width' => '50%'), 
		array('data' => $view_helper->m62Lang('cron_commands'), 'width' => '30%'), 
		array('data' => $view_helper->m62Lang('test'), 'width' => '20%')
	);
	foreach($backup_cron_commands AS $key => $value)
	{
		$this->table->add_row(
			array('data' => $view_helper->m62Lang($key), 'width' => '50%'), 
			'<div class="select_all">'.$value['cmd'].'</div>',
			'<a href="'.$value['url'].'" class="test_cron" rel="'.$key.'"><img src="'.$theme_folder_url.'backup_pro/images/test.png" /></a> <img src="'.$theme_folder_url.'backup_pro/images/indicator.gif" id="animated_'.$key.'" style="display:none" />');
	}
	echo $this->table->generate();
	$this->table->clear();	
}	
//
?>

<h3  class="accordion"><?=$view_helper->m62Lang('configure_cron_notification')?></h3>
<table class="form-table" >
<tr>
    <th scope="row">
        <label for="cron_notify_emails"><?php echo $view_helper->m62Lang('cron_notify_emails'); ?></label>
    </th>
    <td>
        <textarea name="cron_notify_emails" rows="10" cols="50" id="cron_notify_emails" class="large-text code"><?php echo $form_data['cron_notify_emails']; ?></textarea>
        <p class="description" id="cron_notify_emails-description"><?php echo $view_helper->m62Lang('cron_notify_emails_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['cron_notify_emails']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="cron_notify_email_mailtype"><?php echo $view_helper->m62Lang('cron_notify_email_mailtype'); ?></label>
    </th>
    <td>
        <select name="cron_notify_email_mailtype" id="cron_notify_email_mailtype">
        <?php foreach(array('html' => 'html', 'text' => 'text') AS $key => $value): ?>
            <option value="<?php echo $key; ?>" <?php selected( $form_data['cron_notify_email_mailtype'], $key); ?>><?php echo $value; ?></option>
        <?php endforeach; ?>
        </select>
        <p class="description" id="cron_notify_email_mailtype-description"><?php echo $view_helper->m62Lang('cron_notify_email_mailtype_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['cron_notify_email_mailtype']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="cron_notify_email_subject"><?php echo $view_helper->m62Lang('cron_notify_email_subject'); ?></label>
    </th>
    <td>
        <input name="cron_notify_email_subject" type="text" id="cron_notify_email_subject" value="<?php echo $form_data['cron_notify_email_subject']; ?>" class="regular-text code" />
        <p class="description" id="cron_notify_email_subject-description"><?php echo $view_helper->m62Lang('cron_notify_email_subject_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['cron_notify_email_subject']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="cron_notify_email_message"><?php echo $view_helper->m62Lang('cron_notify_email_message'); ?></label>
    </th>
    <td>
        <textarea name="cron_notify_email_message" rows="10" cols="50" id="cron_notify_email_message" class="large-text code"><?php echo $form_data['cron_notify_email_message']; ?></textarea>
        <p class="description" id="cron_notify_email_message-description"><?php echo $view_helper->m62Lang('cron_notify_email_message_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['cron_notify_email_message']); ?>
    </td>
</tr>
</table>
