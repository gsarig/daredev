<?php

namespace DareDev;

class Helper {

	/**
	 * Get page template ID by template name
	 *
	 * @param $page_template_name
	 *
	 * @return string
	 */
	public static function page_template_id( $page_template_name, $post_type = 'page', $path = 'page-templates/' ) {
		$args    = [
			'post_type'    => $post_type,
			'fields'       => 'ids',
			'nopaging'     => true,
			'hierarchical' => 0,
			'meta_key'     => '_wp_page_template',
			'meta_value'   => $path . $page_template_name . '.php',
		];
		$entries = get_posts( $args );

		return $entries ? $entries[0] : '';
	}

	/**
	 * Sanitize HTML
	 * All options: https://core.trac.wordpress.org/browser/tags/5.2.1/src/wp-includes/kses.php#L0
	 *
	 * @return array
	 */
	public static function kses_allow_html() {
		return [
			'div'    => [
				'class'    => true,
				'style'    => true,
				'id'       => true,
				'data-min' => true,
				'data-max' => true,
			],
			'span'   => [
				'class' => true,
				'style' => true,
				'id'    => true,
			],
			'h1'     => [
				'class' => true,
				'style' => true,
				'id'    => true,
			],
			'h2'     => [
				'class' => true,
				'style' => true,
				'id'    => true,
			],
			'h3'     => [
				'class' => true,
				'style' => true,
				'id'    => true,
			],
			'h4'     => [
				'class' => true,
				'style' => true,
				'id'    => true,
			],
			'h5'     => [
				'class' => true,
				'style' => true,
				'id'    => true,
			],
			'h6'     => [
				'class' => true,
				'style' => true,
				'id'    => true,
			],
			'button' => [
				'class' => true,
				'style' => true,
				'id'    => true,
			],
			'ul'     => [
				'class' => true,
				'style' => true,
				'id'    => true,
			],
			'li'     => [],
			'input'  => [
				'type'        => true,
				'class'       => true,
				'id'          => true,
				'name'        => true,
				'value'       => true,
				'readonly'    => true,
				'checked'     => true,
				'data-number' => true,
			],
			'label'  => [
				'for'   => true,
				'class' => true,
			],
			'a'      => [
				'href'   => true,
				'target' => true,
				'id'     => true,
				'class'  => true,
			],
			'p'      => [
				'class' => true,
				'style' => true,
			],
			'img'    => [
				'alt'      => true,
				'align'    => true,
				'border'   => true,
				'height'   => true,
				'hspace'   => true,
				'longdesc' => true,
				'vspace'   => true,
				'src'      => true,
				'usemap'   => true,
				'width'    => true,
			],
			'br'     => true,
		];
	}
}