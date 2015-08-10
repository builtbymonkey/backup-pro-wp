
<ul class="subsubsub">
<?php 
$count = 1; 
foreach($menu_data AS $key => $value): 
?>
	<li class="all">
	   <?php if($value['url'] == $section): ?>
	       <?php echo $view_helper->m62Lang('settings_breadcrumb_'.$key)?>
	   <?php else: ?>
	   <a href="<?php echo $url_base.'settings&section='.$value['url']; ?>"><?php echo $view_helper->m62Lang('settings_breadcrumb_'.$key)?></a> 
	   <?php endif; ?>
	   <?php if( $count != count($menu_data) ): ?>| <?php endif; ?>
    </li>
<?php 
$count++; 
endforeach; 
?>	
</ul>