
<tr>
    <th scope="row">
        <label for="sftp_host"><?php echo $view_helper->m62Lang('sftp_host'); ?></label>
    </th>
    <td>
        <input name="sftp_host" type="text" id="sftp_host" value="<?php echo $form_data['sftp_host']; ?>" class="regular-text code" />
        <p class="description" id="sftp_host-description"><?php echo $view_helper->m62Lang('sftp_host_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['sftp_host']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="sftp_username"><?php echo $view_helper->m62Lang('sftp_username'); ?></label>
    </th>
    <td>
        <input name="sftp_username" type="text" id="sftp_username" value="<?php echo $form_data['sftp_username']; ?>" class="regular-text code" />
        <p class="description" id="sftp_username-description"><?php echo $view_helper->m62Lang('sftp_username_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['sftp_username']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="sftp_password"><?php echo $view_helper->m62Lang('sftp_password'); ?></label>
    </th>
    <td>
        <input name="sftp_password" type="password" id="sftp_password" value="<?php echo $form_data['sftp_password']; ?>" class="regular-text code" />
        <p class="description" id="sftp_password-description"><?php echo $view_helper->m62Lang('sftp_password_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['sftp_password']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="sftp_private_key"><?php echo $view_helper->m62Lang('sftp_private_key'); ?></label>
    </th>
    <td>
        <input name="sftp_private_key" type="text" id="sftp_private_key" value="<?php echo $form_data['sftp_private_key']; ?>" class="regular-text code" />
        <p class="description" id="sftp_private_key-description"><?php echo $view_helper->m62Lang('sftp_private_key_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['sftp_private_key']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="sftp_port"><?php echo $view_helper->m62Lang('sftp_port'); ?></label>
    </th>
    <td>
        <input name="sftp_port" type="text" id="sftp_port" value="<?php echo $form_data['sftp_port']; ?>" class="regular-text code" />
        <p class="description" id="sftp_port-description"><?php echo $view_helper->m62Lang('sftp_port_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['sftp_port']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="sftp_root"><?php echo $view_helper->m62Lang('sftp_root'); ?></label>
    </th>
    <td>
        <input name="sftp_root" type="text" id="sftp_root" value="<?php echo $form_data['sftp_root']; ?>" class="regular-text code" />
        <p class="description" id="sftp_root-description"><?php echo $view_helper->m62Lang('sftp_root_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['sftp_root']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="sftp_timeout"><?php echo $view_helper->m62Lang('sftp_timeout'); ?></label>
    </th>
    <td>
        <input name="sftp_timeout" type="text" id="sftp_timeout" value="<?php echo $form_data['sftp_timeout']; ?>" class="regular-text code" />
        <p class="description" id="sftp_timeout-description"><?php echo $view_helper->m62Lang('sftp_timeout_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['sftp_timeout']); ?>
    </td>
</tr>