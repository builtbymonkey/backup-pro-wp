
<ul class="subsubsub">
<?php $count = 1; foreach($menu_data AS $key => $value): ?>
	<li class="all"><a href="<?php echo $url_base.$value['url']; ?>"><?php echo $view_helper->m62Lang($key.'_bp_dashboard_menu')?></a> <?php if( $count != count($menu_data) ): ?>| <?php endif; ?></li>
<?php $count++; endforeach; ?>	
</ul>