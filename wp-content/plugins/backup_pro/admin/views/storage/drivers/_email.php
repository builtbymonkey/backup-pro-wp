<tr>
    <th scope="row">
        <label for="email_storage_emails"><?php echo $view_helper->m62Lang('email_storage_emails'); ?></label>
    </th>
    <td>
        <textarea name="email_storage_emails" rows="10" cols="50" id="email_storage_emails" class="large-text code"><?php echo $form_data['email_storage_emails']; ?></textarea>
        <p class="description" id="email_storage_emails-description"><?php echo $view_helper->m62Lang('email_storage_emails_instructions'); ?></p>
        <?php echo $view_helper->m62FormErrors($form_errors['email_storage_emails']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="email_storage_attach_threshold"><?php echo $view_helper->m62Lang('email_storage_attach_threshold'); ?></label>
    </th>
    <td>
        <input name="email_storage_attach_threshold" type="text" id="email_storage_attach_threshold" value="<?php echo $form_data['email_storage_attach_threshold']; ?>" class="regular-text code" />
        <p class="description" id="email_storage_attach_threshold-description"><?php echo $view_helper->m62Lang('email_storage_attach_threshold_instructions'); ?></p>
        <?php echo $view_helper->m62FormErrors($form_errors['email_storage_attach_threshold']); ?>
    </td>
</tr>