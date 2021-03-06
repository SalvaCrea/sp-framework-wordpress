<?php

namespace Chocolatine\Component;

/**
 * Element Module
 */

class Module
{
		/**
		 * The font awesome icon
		 * @var string
		 */
		var $icon;
		/**
		 * for the good rangement
		 * @var string
		 */
		var $categorie;
		/**
		 *  this is the root folder
		 * @var string
		 */
		var $path_folder;
		/**
		 * this a web url
		 * @var string
		 */
		var $url_folder;

		function __get($name)
		{

			if ($name == 'core')
				 return \Chocolatine\get_core();

			if ($name == 'db')
		 			return \Chocolatine\get_service('database')->database;

		}
		/**
		 * Function call then when call the function get_module in module manager
		 * @return mixed boolean false if not use;
		 */
		function getter()
		{
				return false;
		}
		/**
		 * The view loader
		 * @return boolean or mixed, return false if is empty
		 */
		public function after_factory()
		{
				return false;
		}
		/**
		 * The main view for administration
		 * @return boolean or string, if definy return a string
		 */
		public function view_back()
		{
				return false;
		}
		/**
		* Function use only component
		* @return boolean return false, only component return the father
		*/
	 function get_father() { return false; }

}
