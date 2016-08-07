
<div>
<br clear="all" />
<h3 class="title"><?=$view_helper->m62Lang('rest_api_details')?></h3>
<p><?=$view_helper->m62Lang('rest_api_instructions')?></p>
<input type="hidden" value="0" name="enable_rest_api" />
<table class="form-table" >
<tr>
    <th scope="row">
        <label for="rest_api_route_entry"><?php echo $view_helper->m62Lang('rest_api_route_entry'); ?></label>
    </th>
    <td>
        <a href="<?php echo $rest_api_route_entry; ?>" target="_blank" id="rest_api_url_wrap"><?php echo $rest_api_route_entry; ?></a>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="enable_rest_api"><?php echo $view_helper->m62Lang('enable_rest_api'); ?></label>
    </th>
    <td>
        <fieldset><legend class="screen-reader-text"><span><?php echo $view_helper->m62Lang('enable_rest_api_instructions'); ?></span></legend><label for="enable_rest_api">
            <input name="enable_rest_api" id="enable_rest_api" value="1" type="checkbox" <?php echo checked( $form_data['enable_rest_api'], 1, true); ?>>
            <?php echo $view_helper->m62Lang('enable_rest_api_instructions'); ?></label></fieldset>
        <?php echo $view_helper->m62FormErrors($form_errors['enable_rest_api']); ?>
    </td>
</tr>
</table>

<div class="panel" id="rest_api_wrap" style="display:none; ">

<br clear="all" />
<h3 class="title"><?=$view_helper->m62Lang('rest_api_credentials')?></h3>
<p><?=$view_helper->m62Lang('rest_api_credentials_instructions')?></p>
<table class="form-table" >
<tr>
    <th scope="row">
        <label for="api_key"><?php echo $view_helper->m62Lang('api_key'); ?></label>
    </th>
    <td>
        <input name="api_key" type="text" id="api_key" value="<?php echo $form_data['api_key']; ?>" class="regular-text code" />
        <p class="description" id="api_key-description"><?php echo $view_helper->m62Lang('api_key_instructions'); ?></p>
        <?php echo $view_helper->m62FormErrors($form_errors['api_key']); ?>
    </td>
</tr>
<tr>
    <th scope="row">
        <label for="api_secret"><?php echo $view_helper->m62Lang('api_secret'); ?></label>
    </th>
    <td>
        <input name="api_secret" type="text" id="api_secret" value="<?php echo $form_data['api_secret']; ?>" class="regular-text code" />
        <p class="description" id="api_secret-description"><?php echo $view_helper->m62Lang('api_secret_instructions'); ?></p>
        <?php echo $view_helper->m62FormErrors($form_errors['api_secret']); ?>
    </td>
</tr>
</table>
</div>

</div>