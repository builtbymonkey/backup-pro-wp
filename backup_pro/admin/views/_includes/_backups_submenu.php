
<ul class="subsubsub">
<?php 
$count = 1; 
foreach($menu_data AS $key => $value): 
?>
	<li class="all">
	   <?php if($value['url'] == $section): ?>
	       <?php echo $view_helper->m62Lang($key.'_bp_dashboard_menu')?>
	   <?php else: ?>
	   <a href="<?php echo $url_base.'&section='.$value['url']; ?>"><?php echo $view_helper->m62Lang($key.'_bp_dashboard_menu')?></a> 
	   <?php endif; ?>
	   <?php if( $count != count($menu_data) ): ?>| <?php endif; ?>
    </li>
<?php 
$count++; 
endforeach; 
?>	
</ul>