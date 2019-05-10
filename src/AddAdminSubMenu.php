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
use JacoBaldrich\WPAdminMenu\AddAdminMenu;

/**
 * This class creates the admin menu and links to its view.
 */
final class AddAdminSubMenu extends AddAdminMenu {

	/**
	 * The variable that will host the AdminMenuCreator object.
	 *
	 * @var AdminMenuCreator
	 */
	protected $admin_menu;

	/**
	 * The slug of the parent.
	 *
	 * @var string
	 */
	private $parent;

	/**
	 * The Page Hook Suffix of the submenu item.
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
		if ( ! empty( $this->slug ) ) {
			$this->parent = $this->slug;
		}
		$this->slug       = 'submenu-item-slug';
		$this->name       = 'Submenu item';
		$this->page_title = 'Page Submenu Item';
		$this->admin_menu = AdminMenuCreator::run( $this->name, $this->child_slug, [ $this, 'render' ] );
	}

	/**
	 * Add the submenu item and set the hook_suffix.
	 *
	 * @return void
	 */
	public function add_menu(): void {
		$this->hook_suffix = $this->admin_menu
			->capability( $this->capability )
			->parent( $this->slug )
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
		echo '<h1>Submenu here!</h1>';
	}
}
