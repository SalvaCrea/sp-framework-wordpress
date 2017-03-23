<?php
/**
 * Class sp_core
 */

namespace salva_powa;
use \Medoo;

class sp_core
{
			/**
			 *  this is the root folder
			 * @var string
			 */
			var $uri_folder;
			/**
			 * this a web url
			 * @var string
			 */
			var $url_folder;
			/**
			 * The value of configuration for sp powa
			 * @var array
			 */
			var $config;
			/**
			 * The current module actif on the view
			 * @var object class
			 */
			var $current_module;
			/**
			 * The current sub module actif on the view
			 * @var string
			 */
			var $current_sub_module;
			/**
			 * The url current, a fusion beetwen slug, current_module and $current_sub_module
			 * @var string
			 */
			var $current_url;
			/**
			 * The slug is very pratice
			 * @var string
			 */
			var $slug = 'salva_powa';
			/**
			 * Contain the class module manager and list all modules
			 * @var object class
			 */
			var $module_manager;
			/**
			 * The medoo Class for manipule data base
			 * @var object class
			 */
			var $data;
			/**
			 * __construct first action
			 */
			function __construct()
			{
				global $wpdb;

				$this->uri_folder = dirname( dirname(__FILE__) );
				$this->url_folder = plugins_url( 'salva-powa-wordpress' );

				$this->config = json_decode ( get_option( $this->slug ), 1 );

				add_action('admin_menu', array( $this, 'wp_admin_do' ));

				// create un object medoo for manipule data base
				$this->data = new Medoo(
						array(
							'database_type' => 'mysql',
							'database_name' => $wpdb->dbname,
							'server' => $wpdb->dbhost,
							'username' => $wpdb->dbuser,
							'password' => $wpdb->dbpassword,
							'charset' => $wpdb->charset
					)
			);

			}

			/**
			 * This function load all modules
			 */
			public function run()
			{

				$this->module_manager = new sp_module_manager();
				$this->module_manager->search_modules();

			}
			/**
			 * Find the current module by the url
			 * @return [string] return the current module
			 */
			public function find_current_module()
			{

				if ( isset( $_GET['module'] ) && !empty( $_GET['module'] ) )
				{
							$this->current_module = $this->get_module( $_GET['module'] );
				}
				else
				{
						 $this->current_module = $this->get_module( 'home' );
				}

				if ( isset( $_GET['sub_module'] ) && !empty( $_GET['sub_module'] ) ) {
						$this->current_sub_module = $_GET['sub_module'];
				}


			}
			/**
			 * This function return un module by the name
			 * @param  [string] $module the name of the module
			 * @return [object Class]   Return the module
			 */
			public function get_module( $module )
			{
					return $this->
									module_manager->
									list_modules[ $module ];
			}
			/**
			 * Contains the tasks to be executed in the wordpress administration part
			 */
			public function wp_admin_do()
			{
					// load the ressources int the wp -admin
					if ( $_GET['page'] == $this->slug )
							 $this->sp_ressource();

					 // find the current module
 					 $this->find_current_module();

					 // find current url
					 $this->find_current_url();

					 // add a menu compatible Wordpress
					 add_menu_page(
						 'Salva Powa',
						 'Salva Powa',
						 'administrator',
						 $this->slug,
						 array( $this,'create_view' ),
						 'dashicons-hammer',
						 10
					 );

			}
			/**
			 * Create view for the wp_admin
			 */
			public function create_view()
			{

					 $view = new \sp_home();
					 $view->view_back_sp();

			}
			/**
			 * General a url for the wp-admin
			 * @return string
			 */
			public function find_current_url()
			{

					$url = "/wp-admin/admin.php?page={$this->slug}";

					if ( !empty( $this->current_module->slug ))
						$url .= "&module={$this->current_module->slug}";

					if ( !empty( $this->current_sub_module ) )
						$url .= "&sub_module={$this->current_sub_module}";

					$this->current_url = $url;

					return $url;

			}
			/**
			 * List of ressource necessary for the good fonctionnement
			 */
			public function sp_ressource()
			{

		    wp_deregister_script( 'jquery' );

				wp_enqueue_script(
					'Jquery',
					$this->url_folder . '/bower_components/jquery/dist/jquery.min.js'
				);

				wp_enqueue_script(
					'Angular',
					$this->url_folder . '/bower_components/angular/angular.min.js'
				);

				// for the form
				wp_enqueue_script(
					'Angular-sanitize',
					 $this->url_folder . '/bower_components/angular-sanitize/angular-sanitize.min.js'
				 );

				wp_enqueue_script(
					'tv4',
					 $this->url_folder . '/bower_components/tv4/tv4.js'
				 );

				wp_enqueue_script(
					'objectpath',
					 $this->url_folder . '/bower_components/objectpath/lib/ObjectPath.js'
				 );

				wp_enqueue_script(
					'schema-form',
					 $this->url_folder . '/bower_components/angular-schema-form/dist/schema-form.min.js'
				 );

				wp_enqueue_script(
					'bootstrap-decorator',
					 $this->url_folder . '/bower_components/angular-schema-form/dist/bootstrap-decorator.min.js'
				 );

		    wp_enqueue_style(
					'sp_styleCss',
					 $this->url_folder . '/assets/css/style.css'
				 );

		    wp_enqueue_style(
					'sp_boostrapCss',
					 $this->url_folder . '/bower_components/bootstrap/dist/css/bootstrap.min.css'
				 );

		    wp_enqueue_script(
					'sp_boostrapJs',
					 $this->url_folder . '/bower_components/bootstrap/dist/js/bootstrap.js'
				 );

				 wp_enqueue_style(
 					'sp_dataTable_Css',
 					 '//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css'
 				 );

 		    wp_enqueue_script(
 					'sp_dataTable_Js',
 					 '//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js'
 				 );


				wp_enqueue_style(
					'font_awesome',
					 $this->url_folder . '/bower_components/font-awesome/css/font-awesome.css'
				 );
				wp_enqueue_style(
					'font_material_icon',
					 '//fonts.googleapis.com/css?family=Roboto:300,400,500,700'
				 );

				wp_enqueue_style( 'font_roboto', 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900');


			}
}