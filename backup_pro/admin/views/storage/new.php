<div class='wrap'>
    <h2  class="accordion"><?=$view_helper->m62Lang('add_storage_location')?> </h2>
    <?php include '_submenu.php'; ?> <br clear="all" /><br />
    
    <h3><?=$view_helper->m62Lang($storage_engine['name'])?></h3>
    <p><?php echo $view_helper->m62Lang($storage_engine['desc']); ?></p>
    
    
    <form method="post">
    <?php echo wp_nonce_field( 'bpstorage' ); ?>
    <table class="form-table" >
        <?php include '_form.php'; ?>
    </table>
    <div class="tableFooter">
    	<div class="tableSubmit">
            <?php submit_button($view_helper->m62Lang('add_storage_location'));?>
    	</div>
    </div>	
    </form>
</div>