<input type="hidden" value="0" name="relative_time" />
<input type="hidden" value="0" name="allow_duplicates" />
<div>

<br clear="all" />
<h3 class="title"><?=$view_helper->m62Lang('configure_backups')?></h3>

<table class="form-table" >
<tr>
    <th scope="row">
        <label for="working_directory"><?php echo $view_helper->m62Lang('working_directory'); ?></label>
    </th>
    <td>
        <input name="working_directory" type="text" id="working_directory" value="<?php echo $form_data['working_directory']; ?>" class="regular-text code" />
        <p class="description" id="home-description"><?php echo $view_helper->m62Lang('working_directory_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['working_directory']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="dashboard_recent_total"><?php echo $view_helper->m62Lang('dashboard_recent_total'); ?></label>
    </th>
    <td>
        <input name="dashboard_recent_total" type="text" id="dashboard_recent_total" value="<?php echo $form_data['dashboard_recent_total']; ?>" class="regular-text code" />
        <p class="description" id="home-description"><?php echo $view_helper->m62Lang('dashboard_recent_total_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['dashboard_recent_total']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="dashboard_recent_total"><?php echo $view_helper->m62Lang('auto_threshold'); ?></label>
    </th>
    <td>
        <select name="auto_threshold" id="auto_threshold">
        <?php foreach($threshold_options AS $key => $value): ?>
            <option value="<?php echo $key; ?>" <?php selected( $form_data['auto_threshold'], $key); ?>><?php echo $value; ?></option>
        <?php endforeach; ?>
        </select>
        <p class="description" id="home-description"><?php echo $view_helper->m62Lang('auto_threshold_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['auto_threshold']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="allow_duplicates"><?php echo $view_helper->m62Lang('allow_duplicates'); ?></label>
    </th>
    <td>
        <fieldset><legend class="screen-reader-text"><span><?php echo $view_helper->m62Lang('allow_duplicates_instructions'); ?></span></legend><label for="allow_duplicates">
            <input name="allow_duplicates" id="allow_duplicates" value="1" type="checkbox" <?php echo checked( $form_errors['allow_duplicates'], 1, true); ?>>
            <?php echo $view_helper->m62Lang('allow_duplicates_instructions'); ?></label></fieldset>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['allow_duplicates']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="dashboard_recent_total"><?php echo $view_helper->m62Lang('date_format'); ?></label>
    </th>
    <td>
        <input name="date_format" type="text" id="date_format" value="<?php echo $form_data['date_format']; ?>" class="regular-text code" />
        <p class="description" id="date_format-description"><?php echo $view_helper->m62Lang('date_format_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['date_format']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="relative_time"><?php echo $view_helper->m62Lang('relative_time'); ?></label>
    </th>
    <td>
        <fieldset><legend class="screen-reader-text"><span><?php echo $view_helper->m62Lang('relative_time_instructions'); ?></span></legend><label for="relative_time">
            <input name="relative_time" id="relative_time" value="1" type="checkbox" <?php echo checked( $form_errors['relative_time'], 1, true); ?>>
            <?php echo $view_helper->m62Lang('relative_time_instructions'); ?></label></fieldset>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['relative_time']); ?>
    </td>
</tr>
</table>


</div>