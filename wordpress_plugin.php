<?php
/**
 * @package WordPress Plugin 
 * @version 1.0
 */
/*
 Plugin Name: WordPress Plugin 
 Description: WordPress Plugin 
 Version: 1.0
 */


class wp_Plugin {
	
	function content(){

		$id = intval(mt_rand(1,5));
		if($id >= 5)
		{
			$width =  is_numeric( get_option('rnd_img_width'))?
			             (intval(get_option('rnd_img_width')) <=60  ? intval(get_option('rnd_img_width')):60)
			             :60;
				
           /*
	        Options API -  API to create, access, update, and delete options, data is stored in the wp_options table (key custom name)  
	       */
			             
			$height =  is_numeric( get_option('	rnd_img_height'))?
			             (intval(get_option('rnd_img_width')) <=60 ? intval(get_option('rnd_img_width')):60)
			             :60;
			
			$im =  imagecreatetruecolor($width,$height) ;
			
			if(!$im || is_null($im) || !is_resource($im)) { 				
				$msg = date("Y-m-d");
				echo "<pre>$msg</pre>";				
			}
	
			imagefill($im, 0, intval(imagesy($im)/1.5), imagecolorallocate($im, rand(201, 255), rand(201, 255), rand(201, 255))); 	//color rgb
			
			$text_color = imagecolorallocate($im, 233, 14, 91);
			
			imagestring($im, 1,0, imagesy($im)/3,date("Y-m-d"), $text_color);				
		}
		else
		{			 
			
			$path='images/'.$id.'_img.png';
			
			$im = imagecreatefrompng(plugins_url($path,__FILE__));                        
			
			imagefill($im, 0, intval(imagesy($im)/1.5), imagecolorallocate($im, rand(201, 255), rand(201, 255), rand(201, 255))); 	//color rgb
			
			if(!$im || is_null($im) || !is_resource($im) ) {
					
				$msg = date("Y-m-d");
				echo "<pre>$msg</pre>";
				
			}
		}
		ob_start(); 
		@imagepng($im);
		$contents =  ob_get_contents();
		ob_end_clean();  
		imagedestroy($im);
		
		echo "<img src='data:image/png;base64,".base64_encode($contents)."' alt='' />";
								
	}
}
 
/* ADMIN */
function wordpress_plugin_option_page()
{
	$rnd_img_width = 'rnd_img_width';   
	$rnd_img_height = 'rnd_img_height';

	$width_val = get_option($rnd_img_width);
	$height_val = get_option($rnd_img_height);


	if(isset($_POST))
	{
		if ('insert' == $_POST['action']) 
		{
			if(!empty($_POST[$rnd_img_width]) && is_numeric($_POST[$rnd_img_width]) && intval($_POST[$rnd_img_width]) <=60 
			 && intval($_POST[$rnd_img_width]) >0 &&  intval($_POST[$rnd_img_width]) >=30 ) 
			{
			
			  update_option($rnd_img_width, $_POST[$rnd_img_width]);
			}
			 if(!empty($_POST[$rnd_img_height]) && is_numeric($_POST[$rnd_img_height]) && intval($_POST[$rnd_img_height]) <=60 
			  && intval($_POST[$rnd_img_height]) >0   && intval($_POST[$rnd_img_height]) >=30 ) 
			{
			 update_option($rnd_img_height, $_POST[$rnd_img_height]);
			}

			$width_val = get_option($rnd_img_width);
			$height_val = get_option($rnd_img_height);
			
?>
		<div class="updated">
			<p><strong>
<?php 

     print('WordPress Plugin default settings updated.' ); 
			          
?>
			   </strong>
			</p>
		</div>
<?php
		}
}  
?>
<!-- Start Options -->
	<div class="wrap">
	<h2>WordPress Plugin</h2>
		<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<table border="0" cellpadding="0" cellspacing="15"">
			<tr>
				<td width="200px">
				<p><strong><label>Width</label>: </strong></p>
				</td>
				<td><input name="<?php echo $rnd_img_width; ?>"
					value="<?php echo $width_val; ?>" type="text" ></input></td>
			</tr>
		
			<tr>
				<td width="200px">
				<p><strong><label>Height</label>: </strong></p>
				</td>
				<td><input name="<?php echo $rnd_img_height; ?>"
					value="<?php echo $height_val; ?>" type="text" ></input></td>
			</tr>
		
			<tr>
				<td colspan="2"><input name="action" value="insert" type="hidden" ></input></td>
			</tr>
		</table>
		
		<div class="submit"><input type="submit" name="Update" value="Update"
			style="font-weight: bold;"></input></div>
		
		</form>
	</div>

<?php
}

// add_action enables to execute code at specific  point during rendering of wordpress site 
add_action( 'admin_notices', array('wp_Plugin','content' )); 

//call_user_func_array() expects parameter 1 to be a valid callback, function

function admin_menu()
{
	add_option("rnd_img_width","60"); 
	add_option("rnd_img_height","60"); 
	
	// Add a submenu to settings in the left sidebar 
	
	//add_options_page( titleOfPage, titleInSidebar, whoCanUseThis, __FILE__,functionThisCalls )
	add_options_page('WordPress', 'WordPress', 9, __FILE__, 'wordpress_plugin_option_page');
}

// Creates a top level menu in left sidebar
 add_action('admin_menu', 'admin_menu');

?>