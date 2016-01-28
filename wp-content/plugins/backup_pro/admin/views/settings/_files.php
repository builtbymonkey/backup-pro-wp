<div>
<input type="hidden" name="regex_file_exclude" value="0" />

<br clear="all" />
<h3 class="title"><?=$view_helper->m62Lang('config_files')?></h3>

<table class="form-table" >
<tr>
    <th scope="row">
        <label for="max_file_backups"><?php echo $view_helper->m62Lang('max_file_backups'); ?></label>
    </th>
    <td>
        <input name="max_file_backups" type="text" id="max_file_backups" value="<?php echo $form_data['max_file_backups']; ?>" class="regular-text code" />
        <p class="description" id="max_file_backups-description"><?php echo $view_helper->m62Lang('max_file_backups_instructions'); ?></p>
        <?php echo $view_helper->m62FormErrors($form_errors['max_file_backups']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="file_backup_alert_threshold"><?php echo $view_helper->m62Lang('file_backup_alert_threshold'); ?></label>
    </th>
    <td>
        <input name="file_backup_alert_threshold" type="text" id="file_backup_alert_threshold" value="<?php echo $form_data['file_backup_alert_threshold']; ?>" class="regular-text code" />
        <p class="description" id="file_backup_alert_threshold-description"><?php echo $view_helper->m62Lang('file_backup_alert_threshold_instructions'); ?></p>
        <?php echo $view_helper->m62FormErrors($form_errors['file_backup_alert_threshold']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="backup_file_location"><?php echo $view_helper->m62Lang('backup_file_locations'); ?></label>
    </th>
    <td>
        <textarea name="backup_file_location" rows="10" cols="50" id="backup_file_location" class="large-text code"><?php echo $form_data['backup_file_location']; ?></textarea>
        <p class="description" id="backup_file_location-description"><?php echo $view_helper->m62Lang('backup_file_location_instructions'); ?></p>
        <?php echo $view_helper->m62FormErrors($form_errors['backup_file_location']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="exclude_paths"><?php echo $view_helper->m62Lang('exclude_paths'); ?></label>
    </th>
    <td>
        <textarea name="exclude_paths" rows="10" cols="50" id="exclude_paths" class="large-text code"><?php echo $form_data['exclude_paths']; ?></textarea>
        <p class="description" id="exclude_paths-description"><?php echo $view_helper->m62Lang('exclude_paths_instructions'); ?></p>
        <?php echo $view_helper->m62FormErrors($form_errors['exclude_paths']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="regex_file_exclude"><?php echo $view_helper->m62Lang('regex_file_exclude'); ?></label>
    </th>
    <td>
        <fieldset><legend class="screen-reader-text"><span><?php echo $view_helper->m62Lang('regex_file_exclude_instructions'); ?></span></legend><label for="regex_file_exclude">
            <input name="regex_file_exclude" id="regex_file_exclude" value="1" type="checkbox" <?php echo checked( $form_data['regex_file_exclude'], 1, true); ?>>
            <?php echo $view_helper->m62Lang('regex_file_exclude_instructions'); ?></label></fieldset>
        <?php echo $view_helper->m62FormErrors($form_errors['regex_file_exclude']); ?>
    </td>
</tr>
</table>

</div>