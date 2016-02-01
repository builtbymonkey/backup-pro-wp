<input type="hidden" value="0" name="gcs_reduced_redundancy" />
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
<tr>
    <th scope="row">
        <label for="gcs_optional_prefix"><?php echo $view_helper->m62Lang('gcs_optional_prefix'); ?></label>
    </th>
    <td>
        <input name="gcs_optional_prefix" type="text" id="gcs_optional_prefix" value="<?php echo $form_data['gcs_optional_prefix']; ?>" class="regular-text code" />
        <p class="description" id="gcs_optional_prefix-description"><?php echo $view_helper->m62Lang('gcs_optional_prefix_instructions'); ?></p>
        <?php echo $view_helper->m62FormErrors($form_errors['gcs_optional_prefix']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="gcs_reduced_redundancy"><?php echo $view_helper->m62Lang('gcs_reduced_redundancy'); ?></label>
    </th>
    <td>
        <fieldset><legend class="screen-reader-text"><span><?php echo $view_helper->m62Lang('gcs_reduced_redundancy_instructions'); ?></span></legend><label for="gcs_reduced_redundancy">
            <input name="gcs_reduced_redundancy" id="gcs_reduced_redundancy" value="1" type="checkbox" <?php echo checked( $form_data['gcs_reduced_redundancy'], 1, true); ?>>
            <?php echo $view_helper->m62Lang('gcs_reduced_redundancy_instructions'); ?></label></fieldset>
        <?php echo $view_helper->m62FormErrors($form_errors['gcs_reduced_redundancy']); ?>
    </td>
</tr>