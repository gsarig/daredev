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
	function page_template_id( $page_template_name ) {
		$args    = [
			'post_type'    => 'page',
			'fields'       => 'ids',
			'nopaging'     => true,
			'hierarchical' => 0,
			'meta_key'     => '_wp_page_template',
			'meta_value'   => 'page-templates/' . $page_template_name . '.php',
		];
		$entries = get_posts( $args );

		return $entries ? $entries[0] : '';
	}

	/**
	 * Sanitize HTML
	 *
	 * @return array
	 */
	public static function kses_allow_html() {
		return [
			'div'    => [
				'class'    => true,
				'id'       => true,
				'data-min' => true,
				'data-max' => true,
			],
			'span'   => [
				'class' => true,
				'id'    => true,
			],
			'h1'     => [
				'class' => true,
				'id'    => true,
			],
			'h2'     => [
				'class' => true,
				'id'    => true,
			],
			'h3'     => [
				'class' => true,
				'id'    => true,
			],
			'h4'     => [
				'class' => true,
				'id'    => true,
			],
			'h5'     => [
				'class' => true,
				'id'    => true,
			],
			'h6'     => [
				'class' => true,
				'id'    => true,
			],
			'button' => [
				'class' => true,
				'id'    => true,
			],
			'ul'     => [
				'class' => true,
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
		];
	}
}