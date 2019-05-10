<?php declare( strict_types=1 );

/**
 * WP Admin Menu.
 *
 * @package   JacoBaldrich\WPAdminMenu
 * @author    Jaco Baldrich <hello@jacobaldrich.com>
 * @license   MIT
 * @link      https://jacobaldrich.com/
 * @copyright 2019 Jaco Baldrich
 */

namespace JacoBaldrich\WPAdminMenu;

use JacoBaldrich\WPAdminMenu\AdminMenuCreator;

/**
 * This class creates the admin menu and links to its view.
 */
class AddAdminMenu {

	/**
	 * The variable that will host the AdminMenuCreator object.
	 *
	 * @var AdminMenuCreator
	 */
	protected $admin_menu;

	/**
	 * Set the name of the menu item.
	 *
	 * @var string
	 */
	protected $name = 'Menu item';

	/**
	 * Set the slug of the menu item.
	 *
	 * @var string
	 */
	protected $slug = 'menu-item-slug';

	/**
	 * Set the icon URL of the menu item.
	 *
	 * @var string
	 */
	private $icon_url = 'dashicons-menu';

	/**
	 * Set the capability needed to view the menu item.
	 *
	 * @var string
	 */
	protected $capability = 'manage_options';

	/**
	 * Set the position of the menu item.
	 *
	 * @var int
	 */
	private $position = 70;

	/**
	 * Set the page title of the menu item.
	 *
	 * @var string
	 */
	protected $page_title = 'Page Menu Item';

	/**
	 * The Page Hook Suffix of the menu item.
	 *
	 * @see https://codex.wordpress.org/Adding_Administration_Menus#Page_Hook_Suffix
	 *
	 * @var string
	 */
	public $hook_suffix;

	/**
	 * The constructor creates a new instance of the AdminMenuCreator.
	 */
	public function __construct() {
		$this->admin_menu = AdminMenuCreator::run( $this->name, $this->slug, [ $this, 'render' ] );
	}

	/**
	 * Register the use case into the WordPress execution flow.
	 *
	 * @return void
	 */
	public function register(): void {
		$this->admin_menu->register( [ $this, 'add_menu' ] );
	}

	/**
	 * Add the menu item and set the hook_suffix.
	 *
	 * @return void
	 */
	public function add_menu(): void {
		$this->hook_suffix = $this->admin_menu
			->icon_url( $this->icon_url )
			->capability( $this->capability )
			->position( $this->position )
			->page_title( $this->page_title )
			->create();
	}

	/**
	 * Render the view.
	 *
	 * @return void
	 */
	public function render(): void {
		/*
		 * The package already ensures that the user has the same capability
		 * to see the content of the menu.
		 */
		echo '<h1>Menu here!</h1>';
	}
}
