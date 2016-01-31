<input type="hidden" value="0" name="s3_reduced_redundancy" />
<tr>
    <th scope="row">
        <label for="s3_access_key"><?php echo $view_helper->m62Lang('s3_access_key'); ?></label>
    </th>
    <td>
        <input name="s3_access_key" type="text" id="s3_access_key" value="<?php echo $form_data['s3_access_key']; ?>" class="regular-text code" />
        <p class="description" id="s3_access_key-description"><?php echo $view_helper->m62Lang('s3_access_key_instructions'); ?></p>
        <?php echo $view_helper->m62FormErrors($form_errors['s3_access_key']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="s3_secret_key"><?php echo $view_helper->m62Lang('s3_secret_key'); ?></label>
    </th>
    <td>
        <input name="s3_secret_key" type="password" id="s3_secret_key" value="<?php echo $form_data['s3_secret_key']; ?>" class="regular-text code" />
        <p class="description" id="rcf_api-description"><?php echo $view_helper->m62Lang('s3_secret_key_instructions'); ?></p>
        <?php echo $view_helper->m62FormErrors($form_errors['s3_secret_key']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="s3_bucket"><?php echo $view_helper->m62Lang('s3_bucket'); ?></label>
    </th>
    <td>
        <input name="s3_bucket" type="text" id="s3_bucket" value="<?php echo $form_data['s3_bucket']; ?>" class="regular-text code" />
        <p class="description" id="s3_bucket-description"><?php echo $view_helper->m62Lang('s3_bucket_instructions'); ?></p>
        <?php echo $view_helper->m62FormErrors($form_errors['s3_bucket']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="s3_region"><?php echo $view_helper->m62Lang('s3_region'); ?></label>
    </th>
    <td>
        <select name="s3_region" id="s3_region">
        <?php 
        $regions = $view_helper->m62Options('s3_regions', false);
        foreach($regions AS $key => $value): ?>
            <option value="<?php echo $key; ?>" <?php selected( $form_data['s3_region'], $key); ?>><?php echo $value; ?></option>
        <?php endforeach; ?>
        </select>
        <p class="description" id="s3_region-description"><?php echo $view_helper->m62Lang('s3_region_instructions'); ?></p>
        <?php echo $view_helper->m62FormErrors($form_errors['s3_region']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="s3_optional_prefix"><?php echo $view_helper->m62Lang('s3_optional_prefix'); ?></label>
    </th>
    <td>
        <input name="s3_optional_prefix" type="text" id="s3_optional_prefix" value="<?php echo $form_data['s3_optional_prefix']; ?>" class="regular-text code" />
        <p class="description" id="s3_optional_prefix-description"><?php echo $view_helper->m62Lang('s3_optional_prefix_instructions'); ?></p>
        <?php echo $view_helper->m62FormErrors($form_errors['s3_optional_prefix']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="s3_reduced_redundancy"><?php echo $view_helper->m62Lang('s3_reduced_redundancy'); ?></label>
    </th>
    <td>
        <fieldset><legend class="screen-reader-text"><span><?php echo $view_helper->m62Lang('s3_reduced_redundancy_instructions'); ?></span></legend><label for="s3_reduced_redundancy">
            <input name="s3_reduced_redundancy" id="s3_reduced_redundancy" value="1" type="checkbox" <?php echo checked( $form_data['s3_reduced_redundancy'], 1, true); ?>>
            <?php echo $view_helper->m62Lang('s3_reduced_redundancy_instructions'); ?></label></fieldset>
        <?php echo $view_helper->m62FormErrors($form_errors['s3_reduced_redundancy']); ?>
    </td>
</tr>
