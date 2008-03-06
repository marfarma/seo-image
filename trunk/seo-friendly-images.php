<?php /*
Plugin Name: SEO Friendly Images
Plugin URI: http://www.prelovac.com/vladimir/wordpress-plugins/seo-image
Description: Automatically adds alt and title attributes to all your images. Improves traffic from search results and makes them W3C/xHTML valid as well.
Version: 1.1
Author: Vladimir Prelovac
Author URI: http://www.prelovac.com/vladimir

Copyright 2008  Vladimir Prelovac */

 
function seo_friendly_images_add_pages()
{
	add_options_page('SEO Friendly Images options', 'SEO Friendly Images', 8, __FILE__, 'seo_friendly_images_options_page');            
}

    

// Options Page
function seo_friendly_images_options_page()
{ 
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
    
    // Configuration Page
    _e('
 <div class="wrap" id="options-div">
 <h2>SEO Friendly Images</h2>
 <div id="poststuff">
 <div id="moremeta"> 
 <div id="sidebarBlocks" class="dbx-group">
 <fieldset id="about" class="dbx-box">
 <h3 class="dbx-handle">Information</h3>
 <div class="dbx-content">
Worth a cup of coffee for the author? Donate via Paypal.
 <br />
 <br />
 <a align=center href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=vprelovac%40multimagic%2ecom&item_name=Wordpress%20Plugin%3a%20SEO%20Image&no_shipping=1&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8">
 <img src="'. trailingslashit(get_option('siteurl')). 'wp-content/plugins/seo-image/cafe.gif" alt="Make a Donation!" /> Paypal</a>      
 <br /><br />
 <br /><br />
 <a href="http://www.prelovac.com/vladimir/wordpress-plugins/seo-image">SEO Friendly Images Homepage</a><br /><br />
 <a href="http://www.prelovac.com/vladimir/wordpress-plugins">More fine Wordpress plugins and themes</a>
 </div>
 </fieldset>
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
                           
<p>Enter options below. You can enter any text in the field including two special tags:</p>
<ul>
<li>%title - replaced with post title</li>
<li>%name - replaced with image file name (without extension)</li>
</ul>

			

<form name="form_seo_friendly_images" method="post" action="' . $_SERVER['REQUEST_URI'] . '">
<fieldset class="options">			

<p><strong>SEO Friendly Images options</strong></p>


<div>
<label for="alt_text"><b>ALT</b> attribute (example: %name %title)</label><br>
<input id="alt_text" name="alttext" value="' .$alt_text.'"/>
</div><br>

<div>
<label for="title_text"><b>TITLE</b> attribute (example: %name photo)</label><br>
<input id="title_text" name="titletext" value="' .$title_text.'"/>
</div>


<div><input id="check1" type="checkbox" name="override"' .($override=="on"?'CHECKED':'').'/>
<label for="check1">Override default Wordpress alt (recommended)</label></div> 

</fieldset>

<i>
<p>Example:<br />
In a post titled Car Pictures there is a picture named Ferrari.jpg<br />
Setting alt attribute to "<b>%name %title</b>" will produce <b>alt="Ferrari Car Pictures"</b><br />
Setting title attribute to "<b>%name photo</b>" will produce <b>title="Ferrari photo"</b></p>
</i>

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

</div>
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
		
		
		if (!in_array('title=', $pieces)) {
			$titletext_rep=str_replace("%title", $post->post_title, $titletext_rep);
			$titletext_rep=str_replace("%name", $source[0], $titletext_rep);
			
		
			$titletext_rep=str_replace('"', '', $titletext_rep);
			$titletext_rep=str_replace("'", "", $titletext_rep);
			
		
			array_push($pieces, 'title="' . $titletext_rep . '"');
		}
		if (!in_array('alt=', $pieces) ) {
			$alttext_rep=str_replace("%title", $post->post_title, $alttext_rep);
			$alttext_rep=str_replace("%name", $source[0], $alttext_rep);
			
			$alttext_rep=str_replace("\"", "", $alttext_rep);
			$alttext_rep=str_replace("'", "", $alttext_rep);
		
			array_push($pieces, 'alt="' . $alttext_rep . '"');
		}
		else
		{
		
			$key=array_search('alt=',$pieces);
							
			if (($pieces[$key+1]=='""') || (strpos($saved, str_replace('"','',$pieces[$key+1])) && $override=="on"))
			{
			
				$alttext_rep=str_replace("%title", $post->post_title, $alttext_rep);
				$alttext_rep=str_replace("%name", $source[0], $alttext_rep);
				
				$alttext_rep=str_replace("\"", "", $alttext_rep);
				$alttext_rep=str_replace("'", "", $alttext_rep);
				
				$pieces[$key+1]='"'.$alttext_rep.'"';
				
			}
		}
	
		return implode(' ', $pieces).' /';
	}

function seo_friendly_images($content) {
	return preg_replace_callback('/<img[^>]+/', 'seo_friendly_images_process', $content);
}


add_filter('the_content', 'seo_friendly_images');

?>