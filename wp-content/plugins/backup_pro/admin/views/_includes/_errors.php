<?php
if(count($this->errors) >= 1)
{
	
	foreach($errors AS $key => $error)
	{
		echo '<div class="backup_pro_system_error" id="backup_pro_system_error_'.$key.'">';
        echo $view_helper->m62Lang($error);
		echo 'f';
		if( $key == 'no_storage_locations_setup' )
		{
		    echo ' <a href="'.$url_base.'new_storage&engine=local">Setup Storage Location</a>';
		}
		elseif( $key == 'license_number' )
		{
		    echo ' <a href="'.$url_base.'settings&section=license">Enter License</a> or <a href="https://mithra62.com/projects/view/backup-pro">Purchase a License</a>';
		}
		elseif( $key == 'no_db_backups_exist_yet' )
		{
		    echo ' <a href="'.$url_base.'settings&section=license">'.$view_helper->m62Lang('would_you_like_to_backup_database_now').'</a>';
		}
		else 
		{
		}
		
		echo $key;
		echo '</div>';
	}
}