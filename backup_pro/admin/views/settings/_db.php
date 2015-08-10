<input type="hidden" name="db_backup_ignore_tables[]" value="" />
<input type="hidden" name="db_backup_ignore_table_data[]" value="" />
<?php 
$db_backup_methods = array('php' => 'PHP', 'mysqldump' => 'MySQLDUMP');
$db_restore_methods = array('php' => 'PHP', 'mysql' => 'MySQL');
?>

<div>

<br clear="all" />
<h3 class="title"><?=$view_helper->m62Lang('config_db')?></h3>

<table class="form-table" >
<tr>
    <th scope="row">
        <label for="max_db_backups"><?php echo $view_helper->m62Lang('max_db_backups'); ?></label>
    </th>
    <td>
        <input name="max_db_backups" type="text" id="max_db_backups" value="<?php echo $form_data['max_db_backups']; ?>" class="regular-text code" />
        <p class="description" id="max_db_backups-description"><?php echo $view_helper->m62Lang('max_db_backups_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['max_db_backups']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="db_backup_alert_threshold"><?php echo $view_helper->m62Lang('db_backup_alert_threshold'); ?></label>
    </th>
    <td>
        <input name="db_backup_alert_threshold" type="text" id="db_backup_alert_threshold" value="<?php echo $form_data['db_backup_alert_threshold']; ?>" class="regular-text code" />
        <p class="description" id="db_backup_alert_threshold-description"><?php echo $view_helper->m62Lang('db_backup_alert_threshold_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['db_backup_alert_threshold']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="db_backup_method"><?php echo $view_helper->m62Lang('db_backup_method'); ?></label>
    </th>
    <td>
        <select name="db_backup_method" id="db_backup_method">
        <?php foreach($db_backup_methods AS $key => $value): ?>
            <option value="<?php echo $key; ?>" <?php selected( $form_data['db_backup_method'], $key); ?>><?php echo $value; ?></option>
        <?php endforeach; ?>
        </select>
        <p class="description" id="db_backup_method-description"><?php echo $view_helper->m62Lang('db_backup_method_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['db_backup_method']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="db_restore_method"><?php echo $view_helper->m62Lang('db_restore_method'); ?></label>
    </th>
    <td>
        <select name="db_restore_method" id="db_restore_method">
        <?php foreach($db_restore_methods AS $key => $value): ?>
            <option value="<?php echo $key; ?>" <?php selected( $form_data['db_restore_method'], $key); ?>><?php echo $value; ?></option>
        <?php endforeach; ?>
        </select>
        <p class="description" id="db_restore_method-description"><?php echo $view_helper->m62Lang('db_restore_method_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['db_restore_method']); ?>
    </td>
</tr>
</table>


<h3 class="title"><?=$view_helper->m62Lang('config_ignore_sql')?></h3>
<table class="form-table" >
<tr>
    <th scope="row">
        <label for="db_backup_ignore_tables"><?php echo $view_helper->m62Lang('db_backup_ignore_tables'); ?></label>
    </th>
    <td>
        <select name="db_backup_ignore_tables[]" id="db_backup_ignore_tables" multiple>
        <?php foreach($db_tables AS $key => $value): ?>
            <option value="<?php echo $key; ?>" <?php //selected( $form_data['db_backup_ignore_tables'], $key); ?>><?php echo $value; ?></option>
        <?php endforeach; ?>
        </select>
        <p class="description" id="db_backup_ignore_tables-description"><?php echo $view_helper->m62Lang('db_backup_ignore_tables_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['db_backup_ignore_tables']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="db_backup_ignore_table_data"><?php echo $view_helper->m62Lang('db_backup_ignore_table_data'); ?></label>
    </th>
    <td>
        <select name="db_backup_ignore_table_data[]" id="db_backup_ignore_table_data" multiple>
        <?php foreach($db_tables AS $key => $value): ?>
            <option value="<?php echo $key; ?>" <?php //selected( $form_data['db_backup_ignore_tables'], $key); ?>><?php echo $value; ?></option>
        <?php endforeach; ?>
        </select>
        <p class="description" id="db_backup_ignore_table_data-description"><?php echo $view_helper->m62Lang('db_backup_ignore_table_data_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['db_backup_ignore_table_data']); ?>
    </td>
</tr>
</table>


<h3 class="title"><?=$view_helper->m62Lang('config_extra_archive_sql')?></h3>
<table class="form-table" >
<tr>
    <th scope="row">
        <label for="db_backup_archive_pre_sql"><?php echo $view_helper->m62Lang('db_backup_archive_pre_sql'); ?></label>
    </th>
    <td>
        <textarea name="db_backup_archive_pre_sql" rows="10" cols="50" id="db_backup_archive_pre_sql" class="large-text code"><?php echo $form_data['db_backup_archive_pre_sql']; ?></textarea>
        <p class="description" id="db_backup_archive_pre_sql-description"><?php echo $view_helper->m62Lang('db_backup_archive_pre_sql_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['db_backup_archive_pre_sql']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="db_backup_archive_post_sql"><?php echo $view_helper->m62Lang('db_backup_archive_post_sql'); ?></label>
    </th>
    <td>
        <textarea name="db_backup_archive_post_sql" rows="10" cols="50" id="exclude_paths" class="large-text code"><?php echo $form_data['db_backup_archive_post_sql']; ?></textarea>
        <p class="description" id="db_backup_archive_post_sql-description"><?php echo $view_helper->m62Lang('db_backup_archive_post_sql_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['db_backup_archive_post_sql']); ?>
    </td>
</tr>
</table>



<h3 class="title"><?=$view_helper->m62Lang('config_execute_sql')?></h3>

<table class="form-table" >
<tr>
    <th scope="row">
        <label for="db_backup_execute_pre_sql"><?php echo $view_helper->m62Lang('db_backup_execute_pre_sql'); ?></label>
    </th>
    <td>
        <textarea name="db_backup_execute_pre_sql" rows="10" cols="50" id="db_backup_execute_pre_sql" class="large-text code"><?php echo $form_data['db_backup_execute_pre_sql']; ?></textarea>
        <p class="description" id="db_backup_execute_pre_sql-description"><?php echo $view_helper->m62Lang('db_backup_execute_pre_sql_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['db_backup_execute_pre_sql']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="db_backup_execute_post_sql"><?php echo $view_helper->m62Lang('db_backup_execute_post_sql'); ?></label>
    </th>
    <td>
        <textarea name="db_backup_execute_post_sql" rows="10" cols="50" id="db_backup_execute_post_sql" class="large-text code"><?php echo $form_data['db_backup_execute_post_sql']; ?></textarea>
        <p class="description" id="db_backup_execute_post_sql-description"><?php echo $view_helper->m62Lang('db_backup_execute_post_sql_instructions'); ?></p>
        <?php echo $this->backup_lib->displayFormErrors($form_errors['db_backup_execute_post_sql']); ?>
    </td>
</tr>
</table>

</div>