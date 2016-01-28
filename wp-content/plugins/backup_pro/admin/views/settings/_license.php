
<div>
<br clear="all" />
<h3 class="title"><?=$view_helper->m62Lang('license_details')?></h3>
<p><?=$view_helper->m62Lang('license_details_instructions')?></p>
<table class="form-table" >
<tr>
    <th scope="row">
        <label for="license_number"><?php echo $view_helper->m62Lang('license_number'); ?></label>
    </th>
    <td>
        <input name="license_number" type="text" id="license_number" value="<?php echo $form_data['license_number']; ?>" class="regular-text code" />
        <p class="description" id="license_number-description"><?php echo $view_helper->m62Lang('license_number_instructions'); ?></p>
        <?php echo $view_helper->m62FormErrors($form_errors['license_number']); ?>
    </td>
</tr>
</table>

</div>