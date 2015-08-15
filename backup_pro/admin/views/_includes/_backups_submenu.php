<h2 class="nav-tab-wrapper">
    <?php 
    $count = 1; 
    foreach($menu_data AS $key => $value): 
    ?>    
          <a class="nav-tab <?php echo ($value['url'] == $section ? 'nav-tab-active' : '') ?>" href="<?php echo $url_base.'&section='.$value['url']; ?>"><?php echo $view_helper->m62Lang($key.'_bp_dashboard_menu')?></a>
    <?php 
    $count++; 
    endforeach; 
    ?>	      
</h2>
