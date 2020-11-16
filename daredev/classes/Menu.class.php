<?php
/**
 * Mobile menu
 * Use it like that:
 * $menu = new \DareDev\Menu();
 * echo $menu->toggle();
 * or
 * $menu = new \DareDev\Menu::toggle('#ffffff', '#000000', 'My Menu', 'my-menu');
 * echo $menu->toggle();
 */

namespace DareDev;


class Menu {
	public $text;
	public $text_class;

	public function __construct(
		$text = 'Menu',
		$text_class = ''
	) {
		$this->text       = $text;
		$this->text_class = $text_class;
		self::styles();
		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
	}

	public static function button() {
		$menu = new Menu();

		return $menu->toggle();
	}

	public function toggle() {

		$text_class = ( $this->text_class ) ? $this->text_class : 'screen-reader-text';

		return '<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<span class="menu-icon">
						<span class="first">&nbsp;</span>
						<span class="second">&nbsp;</span>
						<span class="third">&nbsp;</span>
					</span>
					<span class="' . $text_class . '">' . $this->text . '</span>
				</button>';
	}

	private function styles() {
		wp_enqueue_style(
			'daredev-menu-styles',
			WPMU_PLUGIN_URL . '/daredev/css/menu-toggle.css'
		);
	}
}