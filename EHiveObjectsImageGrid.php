<?php
/*
	Plugin Name: eHive Objects Image Grid
	Plugin URI: http://developers.ehive.com/wordpress-plugins/
	Author: Vernon Systems limited
	Description: A grid of eHive Object images. The <a href="http://developers.ehive.com/wordpress-plugins#ehiveaccess" target="_blank">eHiveAccess plugin</a> must be installed.
	Version: 2.1.0
	Author URI: http://vernonsystems.com
	License: GPL2+
*/
/*
	Copyright (C) 2012 Vernon Systems Limited

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
if (in_array('ehive-access/EHiveAccess.php', (array) get_option('active_plugins', array()))) {

	class EHiveObjectsImageGrid {

		function __construct() {

			add_action("admin_init", array(&$this, "ehive_objects_image_grid_admin_options_init"));			 
			
			add_action("admin_menu", array(&$this, "ehive_objects_image_grid_admin_menu"));
			
			add_action( 'wp_print_styles', array(&$this,'enqueue_styles'));
							 
			add_shortcode('ehive_objects_image_grid', array(&$this, 'ehive_objects_image_grid_shortcode'));
		}

		/*
		 * Admin init
		 */
		function ehive_objects_image_grid_admin_options_init() {
				
			wp_enqueue_script( 'jquery' );
				
			wp_enqueue_style( 'farbtastic' );
			wp_enqueue_script( 'farbtastic' );
				
			wp_register_script($handle = 'eHiveObjectsImageGridOptions', $src = plugins_url('options.js', '/ehive-objects-image-grid/js/options.js'), $deps = array('jquery'), $ver = '1.0.0', false);
			wp_enqueue_script( 'eHiveObjectsImageGridOptions' );
				
			register_setting('ehive_objects_image_grid_options', 'ehive_objects_image_grid_options', array(&$this, 'plugin_options_validate') );
		
			add_settings_section('comment_section', '', array(&$this, 'comment_section_fn'), __FILE__);
				
			add_settings_section('image_grid_section', 'Objects Image Grid', array(&$this, 'image_grid_section_fn'), __FILE__);
				
			add_settings_section('css_section', 'CSS - stylesheet', array(&$this, 'css_section_fn'), __FILE__);
				
			add_settings_section('css_inline_section', 'CSS - inline', array(&$this, 'css_inline_section_fn'), __FILE__);
				
		}
		
		/*
		 * Validation
		*/
		function plugin_options_validate($input) {
			add_settings_error('ehive_objects_image_grid_options', 'updated', 'eHive Object Image Grid settings saved.', 'updated');
			return $input;
		}
		
		/*
		 * Options page content
		 */
		function  comment_section_fn() {
			echo "<p><em>An overview of the plugin and shortcode documentation is available in the help.</em></p>";
		}
		
		function  image_grid_section_fn() {
			add_settings_field('image_size', 'Image size', array(&$this, 'image_size_fn'), __FILE__, 'image_grid_section');
			add_settings_field('name_enabled', 'Display the name/title', array(&$this, 'name_enabled_fn'), __FILE__, 'image_grid_section');
			add_settings_field('explore_type', 'Explore type', array(&$this, 'explore_type_fn'), __FILE__, 'image_grid_section');
			add_settings_field('search_term', 'Search term', array(&$this, 'search_term_fn'), __FILE__, 'image_grid_section');
			add_settings_field('sort_type', 'Sort by', array(&$this, 'sort_type_fn'), __FILE__, 'image_grid_section');
			add_settings_field('sort_direction', 'Sort direction', array(&$this, 'sort_direction_fn'), __FILE__, 'image_grid_section');
		}
		
		function  css_section_fn() {
			add_settings_field('plugin_css_enabled', 'Enable plugin stylesheet', array(&$this, 'plugin_css_enabled_fn'), __FILE__, 'css_section');
			add_settings_field('rows', 'Rows', array(&$this, 'rows_fn'), __FILE__, 'css_section');
			add_settings_field('columns', 'Columns', array(&$this, 'columns_fn'), __FILE__, 'css_section');
			add_settings_field('css_class', 'Custom class selector', array(&$this, 'css_class_fn'), __FILE__, 'css_section');
		}
		
		function  css_inline_section_fn() {
			add_settings_field('item_background_colour', 'Item background colour', array(&$this, 'item_background_colour_fn'), __FILE__, 'css_inline_section');
			add_settings_field('item_border_colour', 'Item border colour', array(&$this, 'item_border_colour_fn'), __FILE__, 'css_inline_section');
			add_settings_field('item_border_width', 'Item border width', array(&$this, 'item_border_width_fn'), __FILE__, 'css_inline_section');
			add_settings_field('image_background_colour', 'Image background colour', array(&$this, 'image_background_colour_fn'), __FILE__, 'css_inline_section');
			add_settings_field('image_padding', 'Image padding', array(&$this, 'image_padding_fn'), __FILE__, 'css_inline_section');
			add_settings_field('image_border_colour', 'Image border colour', array(&$this, 'image_border_colour_fn'), __FILE__, 'css_inline_section');
			add_settings_field('image_border_width', 'Image border width', array(&$this, 'image_border_width_fn'), __FILE__, 'css_inline_section');
			echo "<div class='ehive-options-demo-image'><img src='/wp-content/plugins/ehive-objects-image-grid/images/grid_item.png' /></div>";
		}
		
		/**********************
		 * IMAGE GRID SECTION *
		**********************/
		function  image_size_fn() {
			$options = get_option('ehive_objects_image_grid_options');
			$imageSize = $options['image_size'];
				
			$items = array("NS" =>  "Nano Square: 45 x 45 pixels",
						   "TS" =>  "Tiny Square: 75 x 75 pixels",
						   "T"  =>  "Tiny: max. 75 x 75 pixels",
						   "S"  =>  "Small: max. 150 x 150 pixels",
						   "M"  =>  "Medium: max. 400 x 400 pixels",
						   "L"  =>  "Large: max. 800 x 800 pixels");
				
			echo "<select id='image_size' name='ehive_objects_image_grid_options[image_size]'>";
			foreach($items as $item => $key) {
				$selected = ($options['image_size']==$item) ? 'selected="selected"' : '';
				echo "<option value='$item' $selected>$key</option>";
			}
			echo "</select>";
		}
		
		function name_enabled_fn() {
			$options = get_option('ehive_objects_image_grid_options');
			if(isset($options['name_enabled']) && $options['name_enabled'] == 'on') {
				$checked = ' checked="checked" ';
			}
			echo "<input ".$checked." id='name_enabled' name='ehive_objects_image_grid_options[name_enabled]' type='checkbox' />";
		}
		
		function  explore_type_fn() {
			$options = get_option('ehive_objects_image_grid_options');
			$items = array('all' => "All", 'interesting' => "Interesting", 'popular' => "Popular", 'recent' => "Recent");
						
			echo "<select id='explore_type' name='ehive_objects_image_grid_options[explore_type]'>";
			foreach($items as $item => $key) {
				$selected = ($options['explore_type']==$item) ? 'selected="selected"' : '';
				echo "<option value='$item' $selected>$key</option>";
			}
			echo "</select>";
		}
		
		function search_term_fn() {
			$options = get_option('ehive_objects_image_grid_options');
			echo "<input class='regular-text' id='search_term' name='ehive_objects_image_grid_options[search_term]' type='text' value='{$options['search_term']}' />";
		}
		
		function  sort_type_fn() {
			$options = get_option('ehive_objects_image_grid_options');
			$items = array(	'',
							'named_collection' => 'Collection',
							'account_name' => 'eHive Account',
							'cat_type' => 'eHive Catalogue Type',
							'primary_creator_maker' => 'Maker',
						   	'name' => 'Name',
							'object_number' => 'Object Number',
							'object_type' => 'Object Type',
			   				'place_made' => 'Place Made',
							'portfolio_title' => 'Protfolio Title',
						   	'series_title' => 'Series Title',
							'taxonomic_classification' => 'Taxonomic Classification');
		
			echo "<select id='sort_type' name='ehive_objects_image_grid_options[sort_type]'>";
			foreach($items as $item => $key) {
				$selected = ($options['sort_type']==$item) ? 'selected="selected"' : '';
				echo "<option value='$item' $selected>$key</option>";
			}
			echo "</select>";
		}
		
		function  sort_direction_fn() {
			$options = get_option('ehive_objects_image_grid_options');
			$items = array(	'asc' => "A-Z",
							'desc' => "Z-A");
					
			echo "<select id='sort_direction' name='ehive_objects_image_grid_options[sort_direction]'>";
			foreach($items as $item => $key) {
				$selected = ($options['sort_direction']==$item) ? 'selected="selected"' : '';
				echo "<option value='$item' $selected>$key</option>";
			}
			echo "</select>";
			echo '<p><em>A sort direction must be selected if a "Sort by" field is selected.</em></p>';
		}
		
		/***************
		 * CSS SECTION *
		 ***************/
		function plugin_css_enabled_fn() {
			$options = get_option('ehive_objects_image_grid_options');
			if(isset($options['plugin_css_enabled']) && $options['plugin_css_enabled'] == 'on') {
				$checked = ' checked="checked" ';
			}
			echo "<input ".$checked." id='plugin_css_enabled' name='ehive_objects_image_grid_options[plugin_css_enabled]' type='checkbox' />";
		}
		
		function rows_fn() {
			$options = get_option('ehive_objects_image_grid_options');
					echo "<input class='small-text' id='rows' name='ehive_objects_image_grid_options[rows]' type='number' value='{$options['rows']}' />";
					echo "<p>*This is the minimum number of rows to display</p>";
		}
		
		function columns_fn() {
			$options = get_option('ehive_objects_image_grid_options');
			echo "<input class='small-text' id='columns' name='ehive_objects_image_grid_options[columns]' type='number' value='{$options['columns']}' />";
			echo "<p>*This is the maximum number of columns to display</p>";
		}
		
		function css_class_fn() {
			$options = get_option('ehive_objects_image_grid_options');
			echo "<input class='regular-text' id='css_class' name='ehive_objects_image_grid_options[css_class]' type='text' value='{$options['css_class']}' />";
			echo '<p>Adds a class name to the ehive-objects-image-grid div.';
		}
		
		/**************
		 * CSS INLINE *
		 **************/
		function item_background_colour_fn() {
			$options = get_option('ehive_objects_image_grid_options');
			if(isset($options['item_background_colour_enabled']) && $options['item_background_colour_enabled'] == 'on') {
				$checked = ' checked="checked" ';
			}
		
			echo "<input class='medium-text' id='item_background_colour' name='ehive_objects_image_grid_options[item_background_colour]' type='text' value='{$options['item_background_colour']}' />";
			echo '<div id="item_background_colourpicker"></div>';
			echo "<td><input ".$checked." id='item_background_colour_enabled' name='ehive_objects_image_grid_options[item_background_colour_enabled]' type='checkbox' /></td>";
		}
		
		function item_border_colour_fn() {
			$options = get_option('ehive_objects_image_grid_options');
				if(isset($options['item_border_colour_enabled']) && $options['item_border_colour_enabled'] == 'on') {
				$checked = ' checked="checked" ';
			}
		
			echo "<input class='medium-text' id='item_border_colour' name='ehive_objects_image_grid_options[item_border_colour]' type='text' value='{$options['item_border_colour']}' />";
			echo '<div id="item_border_colourpicker"></div>';
			echo "<td rowspan='2'><input ".$checked." id='item_border_colour_enabled' name='ehive_objects_image_grid_options[item_border_colour_enabled]' type='checkbox' /></td>";
		}
		
		function item_border_width_fn() {
			$options = get_option('ehive_objects_image_grid_options');
			if(isset($options['item_border_width_enabled']) && $options['item_border_width_enabled'] == 'on') {
				$checked = ' checked="checked" ';
			}

			echo "<input class='small-text' id='item_border_width' name='ehive_objects_image_grid_options[item_border_width]' type='number' value='{$options['item_border_width']}' />";
		}
		
		function image_background_colour_fn() {
			$options = get_option('ehive_objects_image_grid_options');
			if(isset($options['image_background_colour_enabled']) && $options['image_background_colour_enabled'] == 'on') {
				$checked = ' checked="checked" ';
			}
		
			echo "<input class='medium-text' id='image_background_colour' name='ehive_objects_image_grid_options[image_background_colour]' type='text' value='{$options['image_background_colour']}' />";
			echo '<div id="image_background_colourpicker"></div>';
			echo "<td><input ".$checked." id='image_background_colour_enabled' name='ehive_objects_image_grid_options[image_background_colour_enabled]' type='checkbox' /></td>";
		}
		
		function image_padding_fn() {
			$options = get_option('ehive_objects_image_grid_options');
			if(isset($options['image_padding_enabled']) && $options['image_padding_enabled'] == 'on') {
				$checked = ' checked="checked" ';
			}
		
			echo "<input class='small-text' id='image_padding' name='ehive_objects_image_grid_options[image_padding]' type='number' value='{$options['image_padding']}' />";
			echo "<td><input ".$checked." id='image_padding_enabled' name='ehive_objects_image_grid_options[image_padding_enabled]' type='checkbox' /></td>";
		}
		
		function image_border_colour_fn() {
			$options = get_option('ehive_objects_image_grid_options');
				if(isset($options['image_border_colour_enabled']) && $options['image_border_colour_enabled'] == 'on') {
				$checked = ' checked="checked" ';
			}
		
			echo "<input class='medium-text' id='image_border_colour' name='ehive_objects_image_grid_options[image_border_colour]' type='text' value='{$options['image_border_colour']}' />";
			echo '<div id="image_border_colourpicker"></div>';
			echo "<td rowspan='2'><input ".$checked." id='image_border_colour_enabled' name='ehive_objects_image_grid_options[image_border_colour_enabled]' type='checkbox' /></td>";
		}
		
		function image_border_width_fn() {
			$options = get_option('ehive_objects_image_grid_options');
			if(isset($options['image_border_width_enabled']) && $options['image_border_width_enabled'] == 'on') {
				$checked = ' checked="checked" ';
			}
		
			echo "<input class='small-text' id='image_border_width' name='ehive_objects_image_grid_options[image_border_width]' type='number' value='{$options['image_border_width']}' />";
		}
		
		/*
		 * Admin menu setup
		 */
		function ehive_objects_image_grid_admin_menu() {

			global $ehive_objects_image_grid_options_page;

			$ehive_objects_image_grid_options_page = add_submenu_page('ehive_access', 'eHive Objects Image Grid', 'Objects Image Grid', 'manage_options', 'ehive_objects_image_grid', array(&$this, 'ehive_objects_image_grid_options_page'));

			add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'ehive_objects_image_grid_plugin_action_links'), 10, 2);
				
			add_action("admin_print_styles-" . $ehive_objects_image_grid_options_page, array(&$this, "ehive_objects_image_grid_admin_enqueue_styles") );			
			
			add_action("load-$ehive_objects_image_grid_options_page",array(&$this, "ehive_objects_image_grid_options_help"));
			
		}
		
		/*
		 * Options page setup
		*/
		function ehive_objects_image_grid_options_page() {
			?>
		    <div class="wrap">
				<div class="icon32" id="icon-options-ehive"><br></div>
				<h2>eHive Objects Image Grid Settings</h2>      
				<?php settings_errors();?>  		
				<form action="options.php" method="post">
					<?php settings_fields('ehive_objects_image_grid_options'); ?>
					<?php do_settings_sections(__FILE__); ?>
					<p class="submit">
						<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
					</p>
				</form>
			</div>
			<?php
		}
		
		/*
		 * Admin menu link
		 */
		function ehive_objects_image_grid_plugin_action_links($links, $file) {
			$settings_link = '<a href="admin.php?page=ehive_objects_image_grid">' . __('Settings') . '</a>';
			array_unshift($links, $settings_link); // before other links
			return $links;
		}
		
		/*
		 * Add admin stylesheet
		 */
		function ehive_objects_image_grid_admin_enqueue_styles() {
			wp_enqueue_style('eHiveAdminCSS');
		}
		
		/*
		 * Plugin options help setup
		 */
		function ehive_objects_image_grid_options_help() {
			global $ehive_objects_image_grid_options_page;

			$screen = get_current_screen();
			if ($screen->id != $ehive_objects_image_grid_options_page) {
				return;
			}
			
			$htmlOverview = '<p>The eHive Objects Image Grid plugin displays a grid of images.</p>';
			$htmlOverview.= '<p>The images selected are either the most interesting, popular, recent or selected using a search term.</p>';
			$htmlOverview.= '<p>When the plugin CSS is enabled, the total number of images displayed is calculated as the number of rows by the number of columns.</p>';
			$htmlOverview.= '<p>Some popular uses of the eHive image grid require some additional styling rules to be added to your theme\'s CSS stylesheet. Here are some popular scenarios that can be achieve with minimal additional CSS rules:<br></p>';
			$htmlOverview.= '<p><b><u>An image grid that fills the full width of an article:</u></b><br>';
			$htmlOverview.= 'This can be achieved by adding the following CSS rules to your theme\'s stylesheet:<br>';
			$htmlOverview.= '<em><pre>div.ehive-objects-image-grid {
	height:100%;
	width:100%;
	float:left;
}</pre></em></p>';
			$htmlOverview.= '<p><b><u>Reduce the horizontal spacing between images:</u></b><br>';
			$htmlOverview.= 'This can be achieved by adding the following CSS rules to your theme\'s stylesheet:<br>';
			$htmlOverview.= '<em><pre>div.ehive-objects-image-grid {
	height:100%;
	width:100%;
	float:left;
}
div.ehive-objects-image-grid div.ehive-view {
	width:70%;
	margin:auto;
}</pre></em>';
			$htmlOverview.= 'Note: This code snippet includes the CSS rule from the previous scenario. This rule will not need to be added a second time.</p>';
			$htmlOverview.= '<p>The "<em>width:70%</em>" percentage value can be adjusted to change the amount of horizontal spacing between your images. The "<em>margin:auto</em>" rule centers the image grid within your article. This can be removed if you want your image grid to left align.</p>';
			$htmlOverview.= '<p><b><u>Add a different colored border to images when the mouse hovers over them:</u></b><br>';
			$htmlOverview.= 'This can be achieved by unchecking the "Enable inline styles" option in the "CSS - inline section below" and adding the following CSS rules to your theme\'s stylesheet:<br>';
			$htmlOverview.= '<em><pre>div.ehive-objects-image-grid div.ehive-image-grid div.ehive-item div.ehive-item-image-wrap img:hover {
	border: 2px solid #444;
}
div.ehive-objects-image-grid div.ehive-image-grid div.ehive-item div.ehive-item-image-wrap img {
	border: 2px solid #999;
}</pre></em></p>';
			$htmlOverview.= '<p>Border colors can be changed by editing the HTML color code value in the border rule (the value corresponing to the text in bold: "<em>border: 2px solid <b>#999</b></em>"). This HTML color code can be changed to any color you would like. To find a HTML color code you can use our color picker in the "CSS - inline" section below. Simply click in the box beside the "Image background color" option, select a color then copy the generated HTML color code and paste it into the CSS border rule.</p>';
			
			$screen->add_help_tab( array('id'		=> 'ehive-objects-image-grid-overview',
										 'title'	=> 'Overview',
										 'content'	=> $htmlOverview
								 ));
			
			$htmlShortcode = "<p><strong>Shortcode</strong> [ehive_objects_image_grid]</p>";
			$htmlShortcode.= "<p><strong>Attributes:</strong></p>";
			$htmlShortcode.= "<ul>";

			$htmlShortcode.= '<li><strong>css_class</strong> - Adds a custom class selector to the plugin markup.</li>';
				
			$htmlShortcode.= "<li><strong>rows</strong> - The number of rows in the grid, defaults to options setting Rows.</li>";
			$htmlShortcode.= "<li><strong>columns</strong> - The number of columns in the grid, defaults to options setting Columns.</li>";
			
			$htmlShortcode.= '<li><p><strong>image_size</strong> - The image size to display, defaults to options setting Image size.</p>';
			$htmlShortcode.= '<ul>';
			$htmlShortcode.= '<li>"<strong>NS</strong>" - Nano Square: 45 x 45 pixels</li>';
			$htmlShortcode.= '<li>"<strong>TS</strong>" - Tiny Square: 75 x 75 pixels</li>';
			$htmlShortcode.= '<li>"<strong>T</strong>" - Tiny: max. 75 x 75 pixels</li>';
			$htmlShortcode.= '<li>"<strong>S</strong>" - Small: max. 150 x 150 pixels</li>';
			$htmlShortcode.= '<li>"<strong>M</strong>" - Medium: max. 400 x 400 pixels</li>';
			$htmlShortcode.= '<li>"<strong>L</strong>" - Large: max. 800 x 800 pixels</li>';
			$htmlShortcode.= '</ul></li>';

			$htmlShortcode.= '<li><p><strong>name</strong> - Displays the name/title for the object, attribute value is "on".</p></li>';				
			
			$htmlShortcode.= "<li><p><strong>explore_type</strong> - The type of objects to display, defaults to the options setting Explore type.</p>";
			$htmlShortcode.= '<ul>';
			$htmlShortcode.= '<li>"<strong>all</strong>"</li>';
			$htmlShortcode.= '<li>"<strong>interesting</strong>"</li>';
			$htmlShortcode.= '<li>"<strong>popular</strong>"</li>';
			$htmlShortcode.= '<li>"<strong>recent</strong>"</li>';
			$htmlShortcode.= '</ul></li>';
				
			$htmlShortcode.= '<li><p><strong>search_term</strong> - Any valid search term that can be used with the <a href="http://ehive.com/esearch/objects" target="_blank">ehive objects search</a>. The explore_type must be set to "all" to use the search_term attribute.</p></li>';
			
			$htmlShortcode.= '<li><p><strong>sort</strong> - The eHive field to sort on, defaults to options setting Sort by. The sort attribute can only be set when using the search_term attribute.</p>';
			$htmlShortcode.= '<ul>';
			$htmlShortcode.= '<li>"<strong>named_collection</strong>"</li>';
			$htmlShortcode.= '<li>"<strong>account_name</strong>"</li>';
			$htmlShortcode.= '<li>"<strong>cat_type</strong>"</li>';
			$htmlShortcode.= '<li>"<strong>primary_creator_maker</strong>"</li>';
			$htmlShortcode.= '<li>"<strong>name</strong>"</li>';
			$htmlShortcode.= '<li>"<strong>object_number</strong>"</li>';
			$htmlShortcode.= '<li>"<strong>object_type</strong>"</li>';
			$htmlShortcode.= '<li>"<strong>place_made</strong>"</li>';
			$htmlShortcode.= '<li>"<strong>portfolio_title</strong>"</li>';
			$htmlShortcode.= '<li>"<strong>series_title</strong>"</li>';
			$htmlShortcode.= '<li>"<strong>taxonomic_classification</strong>"</li>';				
			$htmlShortcode.= '</ul></li>';
				
			$htmlShortcode.= "<li><p><strong>direction</strong> - Ascending or descending sort direction, defaults to options setting Sort direction. The direction attribute must be set when using the sort attribute.</p>";
			$htmlShortcode.= '<ul>';
			$htmlShortcode.= '<li>"<strong>asc</strong>" - Sort direction A - Z</li>';
			$htmlShortcode.= '<li>"<strong>desc</strong>" - Sort direction Z - A</li>';
			$htmlShortcode.= '</ul></li>';
			
			$htmlShortcode.= '<p><strong>Examples:</strong></p>';
			$htmlShortcode.= '<p>[ehive_objects_image_grid]<br/>Shortcode with no attributes. Attributes default to the options settings.</p>';			
			$htmlShortcode.= '<p>[ehive_objects_image_grid  rows="3" columns="4"]<br/>A grid with three rows and four columns. The rows and columns attributes override the options settings.</p>';
			$htmlShortcode.= '<p>[ehive_objects_image_grid  rows="4" columns="4" explore_type="recent"]<br/>A grid with four rows and four columns displaying the first 16 recent objects.</p>';
			$htmlShortcode.= '<p>[ehive_objects_image_grid  rows="4" columns="4" explore_type="all"]<br/>A grid with four rows and four columns displaying 16 objects.</p>';
			$htmlShortcode.= '<p>[ehive_objects_image_grid  rows="1" columns="1" explore_type="all" search_term="no:123" image_size="M" ]<br/>Display a single medium sized image with object number 123.</p>';
			$htmlShortcode.= '<p>[ehive_objects_image_grid  explore_type="all" search_term="vase" image_size="NS" sort="name" direction="desc"]<br/>Display nano square images in descending order by name, objects that contain the search term vase. The size of the grid defaults to the option settings.</p>';
				
			$htmlShortcode.= "</ul>";
						
			$screen->add_help_tab( array('id'		=> 'ehive-objects-image-grid-shortcode',
										 'title'	=> 'Shortcode',
										 'content'	=> $htmlShortcode
								 ));
			
			$screen->set_help_sidebar('<p><strong>For more information:</strong></p><p><a href="http://developers.ehive.com/wordpress-plugins#ehiveobjectsimagegrid/" target="_blank">Documentation for eHive plugins</a></p><p><a href="http://en.wiki.ehive.com/wiki/Searching" target="_blank">How to construct search terms in eHive</a></p>');
			
		}

		/*
		 * Add stylesheet
		 */
		public function enqueue_styles() {
			$options = get_option('ehive_objects_image_grid_options');
			if ($options[plugin_css_enabled] == 'on') {
				wp_register_style($handle = 'eHiveObjectsImageGridCSS', $src = plugins_url('eHiveObjectsImageGrid.css', '/ehive-objects-image-grid/css/eHiveObjectsImageGrid.css'), $deps = array(), $ver = '0.0.1', $media = 'all');
				wp_enqueue_style( 'eHiveObjectsImageGridCSS');
			}
		}
		
		/*
		 * Shortcode setup
		 */
		public function ehive_objects_image_grid_shortcode($atts) {
			
			global $eHiveAccess;
								
			$options = get_option('ehive_objects_image_grid_options');
			
			$siteType = $eHiveAccess->getSiteType();
			$accountId = $eHiveAccess->getAccountId();
			$communityId = $eHiveAccess->getCommunityId();
			
			// Default the shortcode attributes to the options settings.
			extract(shortcode_atts(array('image_size'	=> array_key_exists('image_size', $options) ? $options['image_size'] : 'TS',
										 'name_enabled'	=> array_key_exists('name_enabled', $options) ? $options['name_enabled'] : '',
										 'explore_type'	=> array_key_exists('explore_type', $options) ? $options['explore_type'] : 'recent',
										 'search_term'	=> array_key_exists('search_term', $options) ? $options['search_term'] : '',
										 'sort_type'	=> array_key_exists('sort_type', $options) ? $options['sort_type'] : '',
										 'direction'	=> array_key_exists('direction', $options) ? $options['direction'] : 'asc',
										 'rows'			=> array_key_exists('rows', $options) ? $options['rows'] : '3', 
										 'columns'		=> array_key_exists('columns', $options) ? $options['columns'] : '7',
										 'css_class'	=> array_key_exists('css_class', $options) ? $options['css_class'] : '',
										 'item_background_colour'			=> array_key_exists('item_background_colour', $options) ? $options['item_background_colour'] : '#f3f3f3',
										 'item_background_colour_enabled'	=> array_key_exists('item_background_colour_enabled', $options) ? $options['item_background_colour_enabled'] : 'on',
										 'item_border_colour'				=> array_key_exists('item_border_colour', $options) ? $options['item_border_colour'] : '#666666',
										 'item_border_colour_enabled'		=> array_key_exists('item_border_colour_enabled', $options) ? $options['item_border_colour_enabled'] : '',
										 'item_border_width' 				=> array_key_exists('item_border_width', $options) ? $options['item_border_width'] : '2',
										 'image_background_colour'			=> array_key_exists('image_background_colour', $options) ? $options['image_background_colour'] : '#ffffff',
										 'image_background_colour_enabled'	=> array_key_exists('image_background_colour_enabled', $options) ? $options['image_background_colour_enabled'] : '',
										 'image_padding' 					=> array_key_exists('image_padding', $options) ? $options['image_padding'] : '1',
										 'image_padding_enabled' 			=> array_key_exists('image_padding_enabled', $options) ? $options['image_padding_enabled'] : '',
										 'image_border_colour'				=> array_key_exists('image_border_colour', $options) ? $options['image_border_colour'] : '#666666',
										 'image_border_colour_enabled'		=> array_key_exists('image_border_colour_enabled', $options) ? $options['image_border_colour_enabled'] : '',
										 'image_border_width' 				=> array_key_exists('image_border_width', $options) ? $options['image_border_width'] : '2'
										), $atts));
			
				$limit = (int) $rows * (int) $columns;
				
				if ($explore_type == 'all' || $explore_type == 'interesting' || $explore_type == 'recent' || $explore_type == 'popular') {
									
					try {
						if ($explore_type == 'all') {
							
							$eHiveApi = $eHiveAccess->eHiveApi();
							
							$query = $options['search_term'];
							
							if (isset($options['sort_type']) && $options['sort_type'] != 0) {
								$sort = $options['sort_type'];
							}
							if (isset($options['sort_direction']) && $options['sort_direction'] != 0) {
								$direction = $options['sort_direction'];
							} 
							
							switch($siteType){
								case 'Account':
									$objectRecordsCollection = $eHiveApi->getObjectRecordsInAccount($accountId, $query, true, $sort, $direction, 0, $limit);
									break;
								case 'Community':
									$objectRecordsCollection = $eHiveApi->getObjectRecordsInCommunity($communityId, $query, true, $sort, $direction, 0, $limit);
									break;
								default:
									$objectRecordsCollection = $eHiveApi->getObjectRecordsInEHive($query, true, $sort, $direction, 0, $limit);
									break;
							}
						}				
		
						if ($explore_type == 'interesting') {
		
							$eHiveApi = $eHiveAccess->eHiveApi();
							
							switch($siteType){
							case 'Account':
								$objectRecordsCollection = $eHiveApi->getInterestingObjectRecordsInAccount($accountId, "", true, 0, $limit);
								break;
							case 'Community':
								$objectRecordsCollection = $eHiveApi->getInterestingObjectRecordsInCommunity($communityId, "", true, 0, $limit);
								break;
							default: 
								$objectRecordsCollection = $eHiveApi->getInterestingObjectRecordsInEHive("", true, 0, $limit);
								break;
							}
						}
										
						if ($explore_type == 'popular'){
							
							$eHiveApi = $eHiveAccess->eHiveApi();
							
							switch($siteType){
							case 'Account':
								$objectRecordsCollection = $eHiveApi->getPopularObjectRecordsInAccount($accountId, "", true, 0, $limit);
								break;
							case 'Community':
								$objectRecordsCollection = $eHiveApi->getPopularObjectRecordsInCommunity($communityId, "", true, 0, $limit);
								break;
							default: 
								$objectRecordsCollection = $eHiveApi->getPopularObjectRecordsInEHive("", true, 0, $limit);
								break;
							}
						}
						
						if ($explore_type == 'recent') {
		
							$eHiveApi = $eHiveAccess->eHiveApi();
						
							switch($siteType){
							case 'Account':
								$objectRecordsCollection = $eHiveApi->getRecentObjectRecordsInAccount($accountId, "", true, 0, $limit);
								break;
							case 'Community':
								$objectRecordsCollection = $eHiveApi->getRecentObjectRecordsInCommunity($communityId, "", true, 0, $limit);
								break;
							default: 
								$objectRecordsCollection = $eHiveApi->getRecentObjectRecordsInEHive("", true, 0, $limit);
								break;
							}
						}	
					} catch (Exception $exception) {
						error_log('EHive Objects image grid plugin returned and error while accessing the eHive API: ' . $exception->getMessage());
						$eHiveApiErrorMessage = " ";
						if ($eHiveAccess->getIsErrorNotificationEnabled()) {
							$eHiveApiErrorMessage = $eHiveAccess->getErrorMessage();
						}
					}
				}
				
			$imageSize = 'image_' . strtolower($options['image_size']);
	
			$templateToFind = 'eHiveObjectsImageGrid.php' ;
			
			$template = locate_template(array($templateToFind));
			if ('' == $template) {
				$template = "templates/$templateToFind";
			}
			
			ob_start();
			require($template);
			return apply_filters('ehive_objects_image_grid', ob_get_clean());
		}
							
		/*
		 * On plugin activate
		 */
		public function activate() {
			$arr = array("image_size"=>"TS",
						 "name_enabled"=>'',
						 "explore_type"=>"recent",
						 "search_term"=>"",
						 "sort_type"=>"",
						 "direction"=>"asc",
						 "plugin_css_enabled"=>"on",
						 "rows"=>"3",
						 "columns"=>"7",
						 "css_class"=>"",
						 "item_background_colour"=>"#f3f3f3",
						 "item_background_colour_enabled"=>'on',
						 "item_border_colour"=>"#666666",
						 "item_border_colour_enabled"=>'',
						 "item_border_width"=>"2",
						 "image_background_colour"=>"#ffffff",
						 "image_background_colour_enabled"=>'on',
						 "image_padding"=>"1",
						 "image_padding_enabled"=>"on",
						 "image_border_colour"=>"#666666",
						 "image_border_colour_enabled"=>'',
						 "image_border_width"=>"2" );
		
			update_option('ehive_objects_image_grid_options', $arr);		
		}
		
		/*
		 * On plugin deactivate
		 */
		public function deactivate() {
			delete_option('ehive_objects_image_grid_options');
		}
	}
	
	$eHiveObjectsImageGrid = new EHiveObjectsImageGrid();
		
	add_action('activate_ehive-objects-image-grid/EHiveObjectsImageGrid.php', array(&$eHiveObjectsImageGrid, 'activate'));
	add_action('deactivate_ehive-objects-image-grid/EHiveObjectsImageGrid.php', array(&$eHiveObjectsImageGrid, 'deactivate'));
}
?>