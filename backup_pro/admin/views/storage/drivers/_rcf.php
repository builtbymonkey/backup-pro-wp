<tr>
    <th scope="row">
        <label for="rcf_username"><?php echo $view_helper->m62Lang('rcf_username'); ?></label>
    </th>
    <td>
        <input name="rcf_username" type="text" id="rcf_username" value="<?php echo $form_data['rcf_username']; ?>" class="regular-text code" />
        <p class="description" id="rcf_username-description"><?php echo $view_helper->m62Lang('rcf_username_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['rcf_username']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="rcf_api"><?php echo $view_helper->m62Lang('rcf_api'); ?></label>
    </th>
    <td>
        <input name="rcf_api" type="password" id="rcf_api" value="<?php echo $form_data['rcf_api']; ?>" class="regular-text code" />
        <p class="description" id="rcf_api-description"><?php echo $view_helper->m62Lang('rcf_api_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['rcf_api']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="rcf_container"><?php echo $view_helper->m62Lang('rcf_container'); ?></label>
    </th>
    <td>
        <input name="rcf_container" type="text" id="rcf_container" value="<?php echo $form_data['rcf_container']; ?>" class="regular-text code" />
        <p class="description" id="rcf_container-description"><?php echo $view_helper->m62Lang('rcf_container_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['rcf_container']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="rcf_location"><?php echo $view_helper->m62Lang('rcf_location'); ?></label>
    </th>
    <td>
        <select name="rcf_location" id="rcf_location">
        <?php 
        $cf_location_options = array('us' => 'US', 'uk' => 'UK');
        foreach($cf_location_options AS $key => $value): ?>
            <option value="<?php echo $key; ?>" <?php selected( $form_data['rcf_location'], $key); ?>><?php echo $value; ?></option>
        <?php endforeach; ?>
        </select>
        <p class="description" id="db_backup_method-description"><?php echo $view_helper->m62Lang('rcf_location_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['rcf_location']); ?>
    </td>
</tr>
