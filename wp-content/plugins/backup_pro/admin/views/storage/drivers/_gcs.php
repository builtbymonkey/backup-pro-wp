<tr>
    <th scope="row">
        <label for="gcs_access_key"><?php echo $view_helper->m62Lang('gcs_access_key'); ?></label>
    </th>
    <td>
        <input name="gcs_access_key" type="text" id="gcs_access_key" value="<?php echo $form_data['gcs_access_key']; ?>" class="regular-text code" />
        <p class="description" id="gcs_access_key-description"><?php echo $view_helper->m62Lang('gcs_access_key_instructions'); ?></p>
        <?php echo $view_helper->m62FormErrors($form_errors['gcs_access_key']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="gcs_secret_key"><?php echo $view_helper->m62Lang('gcs_secret_key'); ?></label>
    </th>
    <td>
        <input name="gcs_secret_key" type="password" id="gcs_secret_key" value="<?php echo $form_data['gcs_secret_key']; ?>" class="regular-text code" />
        <p class="description" id="gcs_secret_key-description"><?php echo $view_helper->m62Lang('gcs_secret_key_instructions'); ?></p>
        <?php echo $view_helper->m62FormErrors($form_errors['gcs_secret_key']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="gcs_bucket"><?php echo $view_helper->m62Lang('gcs_bucket'); ?></label>
    </th>
    <td>
        <input name="gcs_bucket" type="text" id="gcs_bucket" value="<?php echo $form_data['gcs_bucket']; ?>" class="regular-text code" />
        <p class="description" id="gcs_bucket-description"><?php echo $view_helper->m62Lang('gcs_bucket_instructions'); ?></p>
        <?php echo $view_helper->m62FormErrors($form_errors['gcs_bucket']); ?>
    </td>
</tr>