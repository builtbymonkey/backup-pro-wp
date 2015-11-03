<div>
<br clear="all" />
<h3 class="title"><?=$view_helper->m62Lang('integrity_agent_cron')?></h3>

<input type="hidden" name="cron_notify_member_ids[]" value="" />

<table class="widefat" border="0" cellspacing="0" cellpadding="0">
<thead>
	<tr>
		<th width='50%'></th>
		<th width='30%'><?php echo $view_helper->m62Lang('cron_commands'); ?></th>
		<th width='20%'><?php echo $view_helper->m62Lang('test'); ?></th>
	</tr>
</thead>
<tbody>
<?php foreach($ia_cron_commands AS $key => $cron): ?>
<tr class="even">
	<td width='50%' style="width:50%;"><?php echo $view_helper->m62Lang($key); ?></td>
	<td style="width:50%;">
		<div class="select_all"><?php echo $cron['cmd']; ?></div>
	</td>
	<td style="width:50%;">
		<a href="<?php echo $cron['url']; ?>" class="test_cron" rel="<?php echo $key; ?>">
			<img src="<?php echo $theme_folder_url; ?>/backup_pro/admin/images/test.png" />
		</a> <img src="<?php echo $theme_folder_url; ?>/backup_pro/admin/images/indicator.gif" id="animated_<?php echo $key; ?>" style="display:none" />
	</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<h3  class="accordion"><?=$view_helper->m62Lang('configure_integrity_agent_verification')?></h3>
<input type="hidden" name="backup_missed_schedule_notify_member_ids[]" value="" />
<table class="form-table" >
<tr>
    <th scope="row">
        <label for="db_verification_db_name"><?php echo $view_helper->m62Lang('db_verification_db_name'); ?></label>
    </th>
    <td>
        <input name="db_verification_db_name" type="text" id="db_verification_db_name" value="<?php echo $form_data['db_verification_db_name']; ?>" class="regular-text code" />
        <p class="description" id="db_verification_db_name-description"><?php echo $view_helper->m62Lang('db_verification_db_name_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['db_verification_db_name']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="total_verifications_per_execution"><?php echo $view_helper->m62Lang('total_verifications_per_execution'); ?></label>
    </th>
    <td>
        <input name="total_verifications_per_execution" type="text" id="total_verifications_per_execution" value="<?php echo $form_data['total_verifications_per_execution']; ?>" class="regular-text code" />
        <p class="description" id="total_verifications_per_execution-description"><?php echo $view_helper->m62Lang('total_verifications_per_execution_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['total_verifications_per_execution']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="check_backup_state_cp_login"><?php echo $view_helper->m62Lang('check_backup_state_cp_login'); ?></label>
    </th>
    <td>
        <fieldset><legend class="screen-reader-text"><span><?php echo $view_helper->m62Lang('check_backup_state_cp_login_instructions'); ?></span></legend><label for="check_backup_state_cp_login">
            <input name="check_backup_state_cp_login" id="check_backup_state_cp_login" value="1" type="checkbox" <?php echo checked( $form_errors['check_backup_state_cp_login'], 1, true); ?>>
            <?php echo $view_helper->m62Lang('check_backup_state_cp_login_instructions'); ?></label></fieldset>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['check_backup_state_cp_login']); ?>
    </td>
</tr>
</table>

	
<h3  class="accordion"><?=$view_helper->m62Lang('configure_integrity_agent_backup_missed_schedule')?></h3>
<div>

<table class="form-table" >
<tr>
    <th scope="row">
        <label for="backup_missed_schedule_notify_email_interval"><?php echo $view_helper->m62Lang('backup_missed_schedule_notify_email_interval'); ?></label>
    </th>
    <td>
        <input name="backup_missed_schedule_notify_email_interval" type="text" id="backup_missed_schedule_notify_email_interval" value="<?php echo $form_data['backup_missed_schedule_notify_email_interval']; ?>" class="regular-text code" />
        <p class="description" id="backup_missed_schedule_notify_email_interval-description"><?php echo $view_helper->m62Lang('backup_missed_schedule_notify_email_interval_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['backup_missed_schedule_notify_email_interval']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="backup_missed_schedule_notify_emails"><?php echo $view_helper->m62Lang('backup_missed_schedule_notify_emails'); ?></label>
    </th>
    <td>
        <textarea name="backup_missed_schedule_notify_emails" rows="10" cols="50" id="backup_missed_schedule_notify_emails" class="large-text code"><?php echo $form_data['backup_missed_schedule_notify_emails']; ?></textarea>
        <p class="description" id="backup_missed_schedule_notify_emails-description"><?php echo $view_helper->m62Lang('backup_missed_schedule_notify_emails_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['backup_missed_schedule_notify_emails']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="backup_missed_schedule_notify_email_mailtype"><?php echo $view_helper->m62Lang('backup_missed_schedule_notify_email_mailtype'); ?></label>
    </th>
    <td>
        <select name="backup_missed_schedule_notify_email_mailtype" id="backup_missed_schedule_notify_email_mailtype">
        <?php foreach(array('html' => 'html', 'text' => 'text') AS $key => $value): ?>
            <option value="<?php echo $key; ?>" <?php selected( $form_data['backup_missed_schedule_notify_email_mailtype'], $key); ?>><?php echo $value; ?></option>
        <?php endforeach; ?>
        </select>
        <p class="description" id="backup_missed_schedule_notify_email_mailtype-description"><?php echo $view_helper->m62Lang('backup_missed_schedule_notify_email_mailtype_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['backup_missed_schedule_notify_email_mailtype']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="backup_missed_schedule_notify_email_subject"><?php echo $view_helper->m62Lang('backup_missed_schedule_notify_email_subject'); ?></label>
    </th>
    <td>
        <input name="backup_missed_schedule_notify_email_subject" type="text" id="backup_missed_schedule_notify_email_subject" value="<?php echo $form_data['backup_missed_schedule_notify_email_subject']; ?>" class="regular-text code" />
        <p class="description" id="backup_missed_schedule_notify_email_subject-description"><?php echo $view_helper->m62Lang('backup_missed_schedule_notify_email_subject_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['backup_missed_schedule_notify_email_subject']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="backup_missed_schedule_notify_email_message"><?php echo $view_helper->m62Lang('backup_missed_schedule_notify_email_message'); ?></label>
    </th>
    <td>
        <textarea name="backup_missed_schedule_notify_email_message" rows="10" cols="50" id="backup_missed_schedule_notify_email_message" class="large-text code"><?php echo $form_data['backup_missed_schedule_notify_email_message']; ?></textarea>
        <p class="description" id="backup_missed_schedule_notify_email_message-description"><?php echo $view_helper->m62Lang('backup_missed_schedule_notify_email_message_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['backup_missed_schedule_notify_email_message']); ?>
    </td>
</tr>
</table>
</div>
</div>