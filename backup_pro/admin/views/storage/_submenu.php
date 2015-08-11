<div style="float:left">

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
</div>

<div style="float: right;">
<select name="NewStorageDropdown" id="NewStorageDropdown" >
     <option value="0">New Storage Location</option>
     <?php foreach($available_storage_engines AS $section): ?>
     <option data-imagesrc="<?php echo $theme_folder_url.'backup_pro/images/storage/'.$section['icon']; ?>.png" 
             value="<?php echo $url_base.'new_storage&engine='.$section['short_name']; ?>"><?php echo $view_helper->m62Lang($section['name']); ?></option>
     <?php endforeach; ?>
</select>
</div>