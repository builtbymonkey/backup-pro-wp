<tr>
    <th scope="row">
        <label for="backup_store_location"><?php echo $view_helper->m62Lang('backup_store_location'); ?></label>
    </th>
    <td>
        <input name="backup_store_location" type="text" id="backup_store_location" value="<?php echo $form_data['backup_store_location']; ?>" class="regular-text code" />
        <p class="description" id="backup_store_location-description"><?php echo $view_helper->m62Lang('backup_store_location_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['backup_store_location']); ?>
    </td>
</tr>