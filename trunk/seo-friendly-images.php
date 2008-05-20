<?php 

/*
Plugin Name: SEO Friendly Images
Plugin URI: http://www.prelovac.com/vladimir/wordpress-plugins/seo-image
Description: Automatically adds alt and title attributes to all your images. Improves traffic from search results and makes them W3C/xHTML valid as well.
Version: 2.0
Author: Vladimir Prelovac
Author URI: http://www.prelovac.com/vladimir

To-Do: 
- localization


Copyright 2008  Vladimir Prelovac 

*/

$seo_friendly_images_localversion="2.0"; 
 
function seo_friendly_images_add_pages()
{
	add_options_page('SEO Friendly Images options', 'SEO Friendly Images', 8, __FILE__, 'seo_friendly_images_options_page');            
}

    

// Options Page
function seo_friendly_images_options_page()
{ 

	global $seo_friendly_images_localversion;
	
	$status=seo_friendly_images_getinfo();
			
	$theVersion = $status[1];
	$theMessage = $status[3];	
	
	if( (version_compare(strval($theVersion), strval($seo_friendly_images_localversion), '>') == 1) )
	{
		$msg = 'Latest version available '.' <strong>'.$theVersion.'</strong><br />'.$theMessage;	
		  _e('<div id="message" class="updated fade"><p>' . $msg . '</p></div>');			
	}
	
    // If form was submitted
	if (isset($_POST['seo_friendly_images_update'])) 
	{			
			$alt_text=(!isset($_POST['alttext'])? '': $_POST['alttext']);
			$title_text=(!isset($_POST['titletext'])? '': $_POST['titletext']);
			$override=(!isset($_POST['override'])? 'off': 'on');
			update_option('seo_friendly_images_alt', $alt_text);
			update_option('seo_friendly_images_title', $title_text );
			update_option('seo_friendly_images_override', $override );
			
			$msg_status = 'SEO Friendly Images options saved.';
							
		    // Show message
		   _e('<div id="message" class="updated fade"><p>' . $msg_status . '</p></div>');
		
	} 
	else 
	{

		// Fetch code from DB
		$alt_text = get_option('seo_friendly_images_alt');
		$title_text = get_option('seo_friendly_images_title');
		$override = get_option('seo_friendly_images_override');
		
	} 
    global $wp_version;	
		if(version_compare($wp_version,"2.5",">=")) {
		_e('
			<style type="text/css">
			.wrap {
			max-width:1000px !important;
			}

			div#moremeta {
				float:right;
				width:220px;
				margin-left:10px;
			}
			div#advancedstuff {
				width:770px;
			}
			div#poststuff {
				margin-top:10px;
			}
			fieldset.dbx-box {
				margin-bottom:5px;
			}
			
			</style>
			<!--[if lt IE 7]>
			<style type="text/css">
			div#advancedstuff {
				width:735px;
			}
			</style>
			<![endif]-->

			');
		}
    // Configuration Page
    _e('
 <div class="wrap" id="options-div">
 <form name="form_seo_friendly_images" method="post" action="' . $_SERVER['REQUEST_URI'] . '">
 <h2>SEO Friendly Images '.$seo_friendly_images_localversion.'</h2>
<div id="poststuff">
 <div id="moremeta"> 
 <div id="sidebarBlocks" class="dbx-group">
  <fieldset id="about" class="dbx-box">
 <h3 class="dbx-handle">Information</h3>
 <div id="dbx-content">
 <img src="'. trailingslashit(get_option('siteurl')). 'wp-content/plugins/seo-friendly-images/home.png"><a href="http://www.prelovac.com/vladimir/wordpress-plugins/seo-image"> SEO Friendly Images Home</a><br /><br />
 <img src="'. trailingslashit(get_option('siteurl')). 'wp-content/plugins/seo-friendly-images/idea.png"><a href="http://www.prelovac.com/vladimir/wordpress-plugins/seo-image#comments"> Suggest a Feature</a><br /><br />
 <img src="'. trailingslashit(get_option('siteurl')). 'wp-content/plugins/seo-friendly-images/more.png"><a href="http://www.prelovac.com/vladimir/wordpress-plugins"> My WordPress Plugins</a><br /><br />
 <br />

 <p align="center">
 <img src="'. trailingslashit(get_option('siteurl')). 'wp-content/plugins/seo-friendly-images/p1.png"></p>

<p> <img src="'. trailingslashit(get_option('siteurl')). 'wp-content/plugins/seo-friendly-images/help.png"><a href="http://www.prelovac.com/vladimir/services"> Need a WordPress Expert?</a></p>
 </div>
 </div>
 </div>

 <div id="advancedstuff">
 <div id="mainBlocks" class="dbx-group" >
 <div class="dbx-b-ox-wrapper">
 <fieldset id="block-description" class="dbx-box">

 <div class="dbx-h-andle-wrapper">
 <h3 class="dbx-handle">Options</h3>
 </div>
 
 <div class="dbx-c-ontent-wrapper">
 <div class="dbx-content">
 <p>SEO Friendly Images automatically adds alt and title attributes to all your images in all your posts specified by parameters below.</p>                         
<p>You can enter any text in the field including two special tags:</p>
<ul>
<li>%title - replaces post title</li>
<li>%name - replaces image file name (without extension)</li>
<li>%category - replaces post category</li>
</ul>



<p><strong>SEO Friendly Images options</strong></p>


<div>
<label for="alt_text"><b>ALT</b> attribute (example: %name %title)</label><br>
<input style="border:1px solid #D1D1D1; width:165px;"  id="alt_text" name="alttext" value="' .$alt_text.'"/>
</div><br>

<div>
<label for="title_text"><b>TITLE</b> attribute (example: %name photo)</label><br>
<input style="border:1px solid #D1D1D1;  width:165px;"  id="title_text" name="titletext" value="' .$title_text.'"/>
</div>

<br />
<div><input id="check1" type="checkbox" name="override"' .($override=="on"?'CHECKED':'').'/>
<label for="check1">Override default Wordpress alt (recommended)</label></div> 




<br/><br /><p>Example:<br />
In a post titled Car Pictures there is a picture named Ferrari.jpg<br /><br />
Setting alt attribute to "%name %title" will produce alt="Ferrari Car Pictures"<br />
Setting title attribute to "%name photo" will produce title="Ferrari photo"</p>


<p class="submit">
	<input type="submit" name="seo_friendly_images_update" value="Update Options &raquo;" />
</p>

</form>



		 

</div>
</div>

</fieldset>

</div>
</div>
</div>
<h4>plugin by <a href="http://www.prelovac.com/vladimir/">Vladimir Prelovac</a></h4>
</div>

');
    
}

// Add Options Page
add_action('admin_menu', 'seo_friendly_images_add_pages');


function remove_extension($name) {
  return preg_replace('/(.+)\..*$/', '$1', $name);
} 

function seo_friendly_images_process($matches) {
	
		global $post;

		$title = $post->post_title;

		$alttext_rep = get_option('seo_friendly_images_alt');
		$titletext_rep = get_option('seo_friendly_images_title');
		$override= get_option('seo_friendly_images_override');
					
					
		
		
//		echo $post->post_title;
		### Normalize spacing around attributes.
		$matches[0] = preg_replace('/\s*=\s*/', '=', substr($matches[0],0,strlen($matches[0])-2));
		### Get source.
		preg_match('/src\s*=\s*([\'"])?((?(1).+?|[^\s>]+))(?(1)\1)/', $matches[0], $source);
		$saved=$source[2];
		
		### Swap with file's base name.
		preg_match('%[^/]+(?=\.[a-z]{3}\z)%', $source[2], $source);
		### Separate URL by attributes.
		$pieces = preg_split('/(\w+=)/', $matches[0], -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
		### Add missing pieces.
		
		$cats=get_the_category();
		if (!in_array('title=', $pieces)) {
			$titletext_rep=str_replace("%title", $post->post_title, $titletext_rep);
			$titletext_rep=str_replace("%name", $source[0], $titletext_rep);
			$titletext_rep=str_replace("%category", $cats[0]->slug, $titletext_rep);
			
		
			$titletext_rep=str_replace('"', '', $titletext_rep);
			$titletext_rep=str_replace("'", "", $titletext_rep);
			
		
			array_push($pieces, ' title="' . $titletext_rep . '"');
		}
		if (!in_array('alt=', $pieces) ) {
			$alttext_rep=str_replace("%title", $post->post_title, $alttext_rep);
			$alttext_rep=str_replace("%name", $source[0], $alttext_rep);
			$alttext_rep=str_replace("%category", $cats[0]->slug, $alttext_rep);
			
			$alttext_rep=str_replace("\"", "", $alttext_rep);
			$alttext_rep=str_replace("'", "", $alttext_rep);
		
			array_push($pieces, ' alt="' . $alttext_rep . '"');
		}
		else
		{
			
			$key=array_search('alt=',$pieces);
			
			if ((trim($pieces[$key+1])=='""') || (strpos($saved, str_replace('"','',trim($pieces[$key+1]))) && $override=="on"))
			{
				
				$alttext_rep=str_replace("%title", $post->post_title, $alttext_rep);
				$alttext_rep=str_replace("%name", $source[0], $alttext_rep);
				$alttext_rep=str_replace("%category", $cats[0]->slug, $alttext_rep);
				
				$alttext_rep=str_replace("\"", "", $alttext_rep);
				$alttext_rep=str_replace("'", "", $alttext_rep);
				
				$pieces[$key+1]='"'.$alttext_rep.'" ';
				
			}
		}
	
	
		return implode('', $pieces).' /';
	}

function seo_friendly_images($content) {
	return preg_replace_callback('/<img[^>]+/', 'seo_friendly_images_process', $content);
}


add_filter('the_content', 'seo_friendly_images', 50);

add_action( 'after_plugin_row', 'seo_friendly_images_check_plugin_version' );

function seo_friendly_images_getinfo()
{
		$checkfile = "http://svn.wp-plugins.org/seo-image/trunk/seo-friendly-images.chk";		
		
		$status=array();
		return $status;
		$vcheck = wp_remote_fopen($checkfile);
				
		if($vcheck)
		{
			$version = $seo_friendly_images_localversion;
									
			$status = explode('@', $vcheck);
			return $status;				
		}					
}

function seo_friendly_images_check_plugin_version($plugin)
{
	global $plugindir, $seo_friendly_images_localversion;
	
 	if( strpos($plugin,'seo-friendly-images.php')!==false )
 	{
			

			$status=seo_friendly_images_getinfo();
			
			$theVersion = $status[1];
			$theMessage = $status[3];	
	
			if( (version_compare(strval($theVersion), strval($seo_friendly_images_localversion), '>') == 1) )
			{
				$msg = 'Latest version available '.' <strong>'.$theVersion.'</strong><br />'.$theMessage;				
				echo '<td colspan="5" class="plugin-update" style="line-height:1.2em;">'.$msg.'</td>';
			} else {
				return;
			}
		
	}
}



function seo_friendly_images_install(){		
  if(!get_option('seo_friendly_images_alt')){
    add_option('seo_friendly_images_alt', '%name %title');
  }
  if(!get_option('seo_friendly_images_title')){
    add_option('seo_friendly_images_title', '%title');
  }
  if(get_option('seo_friendly_images_override' == '') || !get_option('seo_friendly_images_override')){
    add_option('seo_friendly_images_override', 'on');
  }
  
}


add_action( 'plugins_loaded', 'seo_friendly_images_install' );

?>