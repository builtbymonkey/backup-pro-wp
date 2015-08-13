<div class='wrap'>
    <h2>Backup Pro</h2>
    <h3>Backup Database</h3>
    
    <div id="backup_instructions">
    <?php echo $view_helper->m62Lang('backup_in_progress_instructions'); ?>
    </div>
    
    <form action="<?php echo $url_base; ?>backup_database&noheader=true" method="post">
    <?php submit_button($view_helper->m62Lang('start_backup'));?>
    <?php echo wp_nonce_field( 'backup_db' ); ?>
    <div id="backup_running_details"  style="display:none" >
    <?php echo $view_helper->m62Lang('backup_in_progress'); ?>
    <img src="<?php echo $theme_folder_url; ?>backup_pro/images/indicator.gif" id="animated_image" />
    </div>		
    
    
    
    <input type="hidden" name="go_db" value="ok" />
    <input type="hidden" id="__backup_proc_url" value="<?php echo $proc_url; ?>">
    <input type="hidden" id="__url_base" value="<?php echo $url_base; ?>">
    <input type="hidden" id="__backup_type" value="<?php echo $backup_type; ?>">
    <input type="hidden" id="__lang_backup_progress_bar_stop" value="<?php echo $view_helper->m62Lang('backup_progress_bar_stop'); ?>">
    <input type="hidden" id="__lang_backup_progress_bar_running" value="<?php echo $view_helper->m62Lang('backup_in_progress'); ?>">
    
    <div id="progress_bar_container" style="display:none">
    	<span id="active_item"></span> <br />
    	<div id="progressbar"></div>
    	Total Items: <span id="item_number"></span> of <span id="total_items"></span> <br />
    	<span id="backup_complete"></span>
    </div>
    
    </form>
</div>