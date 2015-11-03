<div style="float:left">
    <h2 class="nav-tab-wrapper">
    <?php 
    $count = 1; 
    foreach($menu_data AS $key => $value): 
    ?>    
        <a class="nav-tab <?php echo ($value['url'] == $section ? 'nav-tab-active' : '') ?>" href="<?php echo $url_base.'settings&section='.$value['url']; ?>"><?php echo $view_helper->m62Lang('settings_breadcrumb_'.$key)?></a>
    <?php 
    $count++; 
    endforeach; 
    ?>	      
    </h2>
</div>

<div style="float: right;">
<select name="NewStorageDropdown" id="NewStorageDropdown" >
     <option value="0">New Storage Location</option>
     <?php foreach($available_storage_engines AS $section): ?>
     <option data-imagesrc="<?php echo $theme_folder_url.'backup_pro/images/storage/'.$section['icon']; ?>.png" 
             value="<?php echo $url_base.'settings&section=storage&action=new&engine='.$section['short_name']; ?>"><?php echo $view_helper->m62Lang($section['name']); ?></option>
     <?php endforeach; ?>
</select>
</div>