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
	 * Set the name of the submenu item.
	 *
	 * @var string
	 */
	private $name = 'Submenu item';

	/**
	 * Set the slug of the submenu item.
	 *
	 * @var string
	 */
	protected $child_slug = 'submenu-item-slug';

	/**
	 * Set the page title of the submenu item.
	 *
	 * @var string
	 */
	private $page_title = 'Page Submenu Item';

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
		 * We allways have to ensure the user has the same capability
		 * to see the content of the submenu.
		 */
		if ( \current_user_can( $this->admin_menu->capability ) ) {
			echo '<h1>Submenu here!</h1>';
		}
	}
}
