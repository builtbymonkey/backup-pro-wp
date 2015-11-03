<input type="hidden" value="0" name="ftp_passive" />
<tr>
    <th scope="row">
        <label for="ftp_hostname"><?php echo $view_helper->m62Lang('ftp_hostname'); ?></label>
    </th>
    <td>
        <input name="ftp_hostname" type="text" id="ftp_hostname" value="<?php echo $form_data['ftp_hostname']; ?>" class="regular-text code" />
        <p class="description" id="ftp_hostname-description"><?php echo $view_helper->m62Lang('ftp_hostname_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['ftp_hostname']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="ftp_username"><?php echo $view_helper->m62Lang('ftp_username'); ?></label>
    </th>
    <td>
        <input name="ftp_username" type="text" id="ftp_username" value="<?php echo $form_data['ftp_username']; ?>" class="regular-text code" />
        <p class="description" id="ftp_username-description"><?php echo $view_helper->m62Lang('ftp_username_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['ftp_username']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="ftp_password"><?php echo $view_helper->m62Lang('ftp_password'); ?></label>
    </th>
    <td>
        <input name="ftp_password" type="password" id="ftp_password" value="<?php echo $form_data['ftp_password']; ?>" class="regular-text code" />
        <p class="description" id="email_storage_attach_threshold-description"><?php echo $view_helper->m62Lang('ftp_password_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['ftp_password']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="ftp_port"><?php echo $view_helper->m62Lang('ftp_port'); ?></label>
    </th>
    <td>
        <input name="ftp_port" type="text" id="ftp_port" value="<?php echo $form_data['ftp_port']; ?>" class="regular-text code" />
        <p class="description" id="ftp_port-description"><?php echo $view_helper->m62Lang('ftp_port_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['ftp_port']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="ftp_store_location"><?php echo $view_helper->m62Lang('ftp_store_location'); ?></label>
    </th>
    <td>
        <input name="ftp_store_location" type="text" id="ftp_store_location" value="<?php echo $form_data['ftp_store_location']; ?>" class="regular-text code" />
        <p class="description" id="ftp_store_location-description"><?php echo $view_helper->m62Lang('ftp_store_location_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['ftp_store_location']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="ftp_passive"><?php echo $view_helper->m62Lang('ftp_passive'); ?></label>
    </th>
    <td>
        <fieldset><legend class="screen-reader-text"><span><?php echo $view_helper->m62Lang('ftp_passive_instructions'); ?></span></legend><label for="ftp_passive">
            <input name="ftp_passive" id="ftp_passive" value="1" type="checkbox" <?php echo checked( $form_errors['ftp_passive'], 1, true); ?>>
            <?php echo $view_helper->m62Lang('ftp_passive_instructions'); ?></label></fieldset>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['ftp_passive']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="ftp_ssl"><?php echo $view_helper->m62Lang('ftp_ssl'); ?></label>
    </th>
    <td>
        <fieldset><legend class="screen-reader-text"><span><?php echo $view_helper->m62Lang('ftp_ssl_instructions'); ?></span></legend><label for="ftp_ssl">
            <input name="ftp_ssl" id="ftp_ssl" value="1" type="checkbox" <?php echo checked( $form_errors['ftp_ssl'], 1, true); ?>>
            <?php echo $view_helper->m62Lang('ftp_ssl_instructions'); ?></label></fieldset>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['ftp_ssl']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="ftp_timeout"><?php echo $view_helper->m62Lang('ftp_timeout'); ?></label>
    </th>
    <td>
        <input name="ftp_timeout" type="text" id="ftp_timeout" value="<?php echo $form_data['ftp_timeout']; ?>" class="regular-text code" />
        <p class="description" id="ftp_timeout-description"><?php echo $view_helper->m62Lang('ftp_timeout_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['ftp_timeout']); ?>
    </td>
</tr>