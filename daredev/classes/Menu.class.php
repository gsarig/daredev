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

	public $initial_color;
	public $clicked_color;
	public $text;
	public $text_class;

	public function __construct( $initial_color = '#000000', $clicked_color = '#666666', $text = 'Menu', $text_class = '' ) {
		$this->initial_color = $initial_color;
		$this->clicked_color = $clicked_color;
		$this->text          = $text;
		$this->text_class    = $text_class;
		self::styles();
		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
	}

	public function toggle() {

		$text_class = ( $this->text_class ) ? $this->text_class : 'screen-reader-text';

		return '<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<span class="menu-icon">
						<span class="first"></span>
						<span class="second"></span>
						<span class="third"></span>
					</span>
					<span class="' . $text_class . '">' . $this->text . '</span>
				</button>';
	}

	private function styles() {
		wp_enqueue_style(
			'daredev-menu-styles',
			plugin_dir_url( __DIR__ ) . 'css/custom.css'
		);
		$menu_css = "
				.menu-toggle > .menu-icon {
					display: inline-block;
					width: 40px;
					height: 36px;
					padding: 5px 7px;
					border-radius: 3px;
				}
				
				.main-navigation.toggled .menu-toggle > .menu-icon > span {
					background: {$this->clicked_color};
				}
				
				.menu-toggle > .menu-icon > span {
					display: block;
					height: 3px;
					width: 100%;
					margin: 5px 0;
					background: {$this->initial_color};
					-webkit-transition: all 0.5s ease;
					transition: all 0.5s ease;
				}
				
				.main-navigation.toggled .menu-icon > .first {
					-webkit-transform: rotate(45deg);
					transform: rotate(45deg);
					margin-left: 0;
					margin-top: 14px;
					width: 27px;
				}
				
				.main-navigation.toggled .menu-icon > .second {
					-webkit-transform: rotate(-45deg);
					transform: rotate(-45deg);
					margin-left: 0;
					margin-top: -8px;
					width: 27px;
				}
				
				.main-navigation.toggled .menu-icon > .third {
					display: none;
				}
                ";
		wp_add_inline_style( 'daredev-menu-styles', $menu_css );
	}
}