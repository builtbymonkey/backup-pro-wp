<tr>
    <th scope="row">
        <label for="dropbox_access_token"><?php echo $view_helper->m62Lang('dropbox_access_token'); ?></label>
    </th>
    <td>
        <input name="dropbox_access_token" type="password" id="dropbox_access_token" value="<?php echo $form_data['dropbox_access_token']; ?>" class="regular-text code" />
        <p class="description" id="dropbox_access_token-description"><?php echo $view_helper->m62Lang('dropbox_access_token_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['dropbox_access_token']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="dropbox_app_secret"><?php echo $view_helper->m62Lang('dropbox_app_secret'); ?></label>
    </th>
    <td>
        <input name="dropbox_app_secret" type="password" id="dropbox_app_secret" value="<?php echo $form_data['dropbox_app_secret']; ?>" class="regular-text code" />
        <p class="description" id="dropbox_app_secret-description"><?php echo $view_helper->m62Lang('dropbox_app_secret_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['dropbox_app_secret']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="dropbox_prefix"><?php echo $view_helper->m62Lang('dropbox_prefix'); ?></label>
    </th>
    <td>
        <input name="dropbox_prefix" type="text" id="dropbox_prefix" value="<?php echo $form_data['dropbox_prefix']; ?>" class="regular-text code" />
        <p class="description" id="dropbox_prefix-description"><?php echo $view_helper->m62Lang('dropbox_prefix_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['dropbox_prefix']); ?>
    </td>
</tr>