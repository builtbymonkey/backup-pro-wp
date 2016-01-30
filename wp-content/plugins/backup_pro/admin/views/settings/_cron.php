<h3  class="accordion"><?=$view_helper->m62Lang('configure_cron')?></h3>

<table class="widefat" border="0" cellspacing="0" cellpadding="0">
<thead>
	<tr>
		<th width='50%'></th>
		<th width='30%'><?php echo $view_helper->m62Lang('cron_commands'); ?></th>
		<th width='20%'><?php echo $view_helper->m62Lang('test'); ?></th>
	</tr>
</thead>
<tbody>
<?php foreach($backup_cron_commands AS $key => $cron): ?>
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
<h3  class="accordion"><?=$view_helper->m62Lang('configure_cron_notification')?></h3>
<table class="form-table" >
<tr>
    <th scope="row">
        <label for="cron_notify_emails"><?php echo $view_helper->m62Lang('cron_notify_emails'); ?></label>
    </th>
    <td>
        <textarea name="cron_notify_emails" rows="10" cols="50" id="cron_notify_emails" class="large-text code"><?php echo $form_data['cron_notify_emails']; ?></textarea>
        <p class="description" id="cron_notify_emails-description"><?php echo $view_helper->m62Lang('cron_notify_emails_instructions'); ?></p>
        <?php echo $view_helper->m62FormErrors($form_errors['cron_notify_emails']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="cron_notify_email_mailtype"><?php echo $view_helper->m62Lang('cron_notify_email_mailtype'); ?></label>
    </th>
    <td>
        <select name="cron_notify_email_mailtype" id="cron_notify_email_mailtype">
        <?php foreach($view_helper->m62Options('email_type') AS $key => $value): ?>
            <option value="<?php echo $key; ?>" <?php selected( $form_data['cron_notify_email_mailtype'], $key); ?>><?php echo $value; ?></option>
        <?php endforeach; ?>
        </select>
        <p class="description" id="cron_notify_email_mailtype-description"><?php echo $view_helper->m62Lang('cron_notify_email_mailtype_instructions'); ?></p>
        <?php echo $view_helper->m62FormErrors($form_errors['cron_notify_email_mailtype']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="cron_notify_email_subject"><?php echo $view_helper->m62Lang('cron_notify_email_subject'); ?></label>
    </th>
    <td>
        <input name="cron_notify_email_subject" type="text" id="cron_notify_email_subject" value="<?php echo $form_data['cron_notify_email_subject']; ?>" class="regular-text code" />
        <p class="description" id="cron_notify_email_subject-description"><?php echo $view_helper->m62Lang('cron_notify_email_subject_instructions'); ?></p>
        <?php echo $view_helper->m62FormErrors($form_errors['cron_notify_email_subject']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="cron_notify_email_message"><?php echo $view_helper->m62Lang('cron_notify_email_message'); ?></label>
    </th>
    <td>
        <textarea name="cron_notify_email_message" rows="10" cols="50" id="cron_notify_email_message" class="large-text code"><?php echo $form_data['cron_notify_email_message']; ?></textarea>
        <p class="description" id="cron_notify_email_message-description"><?php echo $view_helper->m62Lang('cron_notify_email_message_instructions'); ?></p>
        <?php echo $view_helper->m62FormErrors($form_errors['cron_notify_email_message']); ?>
    </td>
</tr>
</table>
