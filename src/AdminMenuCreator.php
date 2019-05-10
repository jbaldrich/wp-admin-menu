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

/**
 * Admin Menu Creator Class.
 */
final class AdminMenuCreator {

	/**
	 * The text to be displayed in the title tags of the page when the menu is selected.
	 *
	 * @var string
	 */
	private $page_title;

	/**
	 * The text to be used for the menu.
	 *
	 * @var string
	 */
	private $title;

	/**
	 * The capability required for this menu to be displayed to the user.
	 *
	 * @var string
	 */
	public $capability;

	/**
	 * The slug name to refer to this menu by.
	 * Should be unique for this menu page and only include lowercase alphanumeric,
	 * dashes, and underscores characters to be compatible with sanitize_key().
	 *
	 * @see https://developer.wordpress.org/reference/functions/sanitize_key/
	 *
	 * @var string
	 */
	private $slug;

	/**
	 * The URL to the icon to be used for this menu.
	 * * Pass a base64-encoded SVG using a data URI,
	 *   which will be colored to match the color scheme.
	 *   This should begin with 'data:image/svg+xml;base64,'.
	 * * Pass the name of a Dashicons helper class to use a font icon, e.g. 'dashicons-chart-pie'.
	 * * Pass 'none' to leave div.wp-menu-image empty so an icon can be added via CSS.
	 *
	 * @var string
	 */
	private $icon_url;

	/**
	 * The position in the menu order this one should appear.
	 *
	 * @var int
	 */
	private $position;

	/**
	 * The slug name for the parent menu (or the file name of a standard WordPress admin page).
	 *
	 * @var string
	 */
	private $parent;

	/**
	 * The view to show when navigating to the menu item.
	 *
	 * @var callable
	 */
	private $view;

	/**
	 * The constructor sets up the properties.
	 *
	 * @param string   $title      The menu title.
	 * @param string   $slug       The slug.
	 * @param callable $view       The function to be called to output the content for this page.
	 * @param string   $capability The capability to see it.
	 * @param string   $icon_url   The url, dashicon slug or none.
	 * @param int      $position   The position in the menu.
	 * @param string   $parent     The name of the parent menu.
	 * @param string   $page_title The page title.
	 */
	private function __construct(
		string $title,
		string $slug,
		callable $view,
		string $capability,
		string $icon_url,
		int $position = null,
		string $parent,
		string $page_title
	) {
		$this->title      = $title;
		$this->slug       = $slug;
		$this->view       = $view;
		$this->capability = $capability;
		$this->icon_url   = $icon_url;
		$this->position   = $position;
		$this->parent     = $parent;
		$this->page_title = $page_title;
	}

	/**
	 * Named constructor.
	 *
	 * @param string   $title The title of the menu item.
	 * @param string   $slug  The slug of the menu item.
	 * @param callable $view  The function that renders the view.
	 * @return AdminMenuCreator
	 */
	public static function run( string $title, string $slug, callable $view ): AdminMenuCreator {
		// Default parameters.
		$capability = 'read';
		$icon_url   = '';
		$position   = null;
		$parent     = '';
		$page_title = $title;

		return new static(
			$title,
			$slug,
			$view,
			$capability,
			$icon_url,
			$position,
			$parent,
			$page_title
		);
	}

	/**
	 * Creates the menu item.
	 *
	 * @return string The resulting page's hook_suffix.
	 */
	public function create(): string {

		if ( empty( $this->parent ) ) {
			return $this->add_menu_page();
		}
		return $this->add_submenu_page();
	}

	/**
	 * Registers the menu item into the WordPress execution flow.
	 *
	 * @param callable $function The function to execute.
	 * @param string   $hook     The hook when the function will execute.
	 */
	public function register( callable $function, string $hook = 'admin_menu' ): void {

		\add_action( $hook, $function );

	}

	/**
	 * Sets capability.
	 *
	 * @param string $capability The capability.
	 */
	public function capability( string $capability ): self {

		$this->capability = $capability;
		return $this;
	}

	/**
	 * Sets icon url.
	 *
	 * @param string $icon_url The icon url.
	 */
	public function icon_url( string $icon_url ): self {

		$this->icon_url = $icon_url;
		return $this;
	}

	/**
	 * Sets position.
	 *
	 * @param int $position The position.
	 */
	public function position( int $position ): self {

		$this->position = $position;
		return $this;
	}

	/**
	 * Sets parent.
	 *
	 * @param string $parent The parent.
	 */
	public function parent( string $parent ): self {

		$this->parent = $parent;
		return $this;
	}

	/**
	 * Sets page_title.
	 *
	 * @param string $page_title The page_title.
	 */
	public function page_title( string $page_title ): self {

		$this->page_title = $page_title;
		return $this;
	}

	/**
	 * Renders the view.
	 */
	private function render(): void {

		if ( \current_user_can( $this->capability ) ) {
			call_user_func( $this->view );
		}

	}

	/**
	 * Creates the menu item.
	 *
	 * @return string The resulting page's hook_suffix.
	 */
	private function add_menu_page(): string {

		$hook_suffix = \add_menu_page(
			$this->page_title,
			$this->title,
			$this->capability,
			$this->slug,
			function () {
				$this->render();
			},
			$this->icon_url,
			$this->position
		);
		if ( false === $hook_suffix ) {
			$hook_suffix = '';
		}
		return $hook_suffix;
	}

	/**
	 * Creates the submenu item.
	 *
	 * @return string The resulting page's hook_suffix.
	 */
	private function add_submenu_page(): string {

		$hook_suffix = \add_submenu_page(
			$this->parent,
			$this->page_title,
			$this->title,
			$this->capability,
			$this->slug,
			function () {
				$this->render();
			}
		);
		if ( false === $hook_suffix ) {
			$hook_suffix = '';
		}
		return $hook_suffix;
	}

}
