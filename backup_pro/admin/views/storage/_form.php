<input type="hidden" value="0" name="storage_location_status" />
<input type="hidden" value="0" name="storage_location_file_use" />
<input type="hidden" value="0" name="storage_location_db_use" />
<input type="hidden" value="0" name="storage_location_include_prune" />

<tr>
    <th scope="row">
        <label for="storage_location_name"><?php echo $view_helper->m62Lang('storage_location_name'); ?></label>
    </th>
    <td>
        <input name="storage_location_name" type="text" id="storage_location_name" value="<?php echo $form_data['storage_location_name']; ?>" class="regular-text code" />
        <p class="description" id="storage_location_name-description"><?php echo $view_helper->m62Lang('storage_location_name_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['storage_location_name']); ?>
    </td>
</tr>

<?php include $_form_template; ?>

<tr>
    <th scope="row">
        <label for="storage_location_status"><?php echo $view_helper->m62Lang('storage_location_status'); ?></label>
    </th>
    <td>
        <fieldset><legend class="screen-reader-text"><span><?php echo $view_helper->m62Lang('storage_location_status_instructions'); ?></span></legend><label for="storage_location_status">
            <input name="storage_location_status" id="storage_location_status" value="1" type="checkbox" <?php echo checked( $form_errors['storage_location_status'], 1, true); ?>>
            <?php echo $view_helper->m62Lang('storage_location_status_instructions'); ?></label></fieldset>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['storage_location_status']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="storage_location_file_use"><?php echo $view_helper->m62Lang('storage_location_file_use'); ?></label>
    </th>
    <td>
        <fieldset><legend class="screen-reader-text"><span><?php echo $view_helper->m62Lang('storage_location_file_use_instructions'); ?></span></legend><label for="storage_location_file_use">
            <input name="storage_location_file_use" id="storage_location_file_use" value="1" type="checkbox" <?php echo checked( $form_errors['storage_location_file_use'], 1, true); ?>>
            <?php echo $view_helper->m62Lang('storage_location_file_use_instructions'); ?></label></fieldset>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['storage_location_file_use']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="storage_location_db_use"><?php echo $view_helper->m62Lang('storage_location_db_use'); ?></label>
    </th>
    <td>
        <fieldset><legend class="screen-reader-text"><span><?php echo $view_helper->m62Lang('storage_location_db_use_instructions'); ?></span></legend><label for="storage_location_db_use">
            <input name="storage_location_db_use" id="storage_location_db_use" value="1" type="checkbox" <?php echo checked( $form_errors['storage_location_db_use'], 1, true); ?>>
            <?php echo $view_helper->m62Lang('storage_location_db_use_instructions'); ?></label></fieldset>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['storage_location_db_use']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="storage_location_include_prune"><?php echo $view_helper->m62Lang('storage_location_include_prune'); ?></label>
    </th>
    <td>
        <fieldset><legend class="screen-reader-text"><span><?php echo $view_helper->m62Lang('storage_location_include_prune_instructions'); ?></span></legend><label for="storage_location_include_prune">
            <input name="storage_location_include_prune" id="storage_location_include_prune" value="1" type="checkbox" <?php echo checked( $form_errors['storage_location_include_prune'], 1, true); ?>>
            <?php echo $view_helper->m62Lang('storage_location_include_prune_instructions'); ?></label></fieldset>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['storage_location_include_prune']); ?>
    </td>
</tr>