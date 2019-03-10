<?php

/**
 * The template loader class select wich template has to display for the content
 *
 *
 * @version:    1.0.0
 * @author      Angel Porras <robinwebdesign@hotmail.com>
 * @author URI: https://webdesign.portfoliobox.net/
 * @license:    GPL2
 * @license URI:https://www.gnu.org/licenses/gpl-2.0.html
 */
 
 
class VRE_Vacationa_template_loader{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;	 
	 
	
	/**
	 * The path of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_path;
	
	
	/**
	 * The template path of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $template_path;
	
	/**
	 * The template of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $template;
	
	
	
	public static function vre_locate_template($template_name,$section='')
	{
		$path_template=false;
		$path_template=VRE_PLUGIN_ROOT.'templates/';
		
		if($section)
			$path_template=$path_template.$section."/";
						
		$template=locate_template($template_name);
		
			if ($template)
			{
				return $template;
			}
		
		return $path_template.$template_name;
	}
	 
}

$vre_template_loader= new VRE_Vacationa_template_loader();