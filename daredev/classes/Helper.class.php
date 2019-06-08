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
	 * @param array $elements Elements to be sanitized.
	 *
	 * @param array $custom Custom tags.
	 *
	 * @return array
	 */
	public static function kses_allow_html( $elements = [], $custom = [] ) {
		$all    = [
			'address'    => [],
			'a'          => [
				'id'       => true,
				'class'    => true,
				'href'     => true,
				'rel'      => true,
				'rev'      => true,
				'name'     => true,
				'target'   => true,
				'download' => [
					'valueless' => 'y',
				],
			],
			'abbr'       => [],
			'acronym'    => [],
			'area'       => [
				'alt'    => true,
				'coords' => true,
				'href'   => true,
				'nohref' => true,
				'shape'  => true,
				'target' => true,
			],
			'article'    => [
				'align'    => true,
				'dir'      => true,
				'lang'     => true,
				'xml:lang' => true,
			],
			'aside'      => [
				'align'    => true,
				'dir'      => true,
				'lang'     => true,
				'xml:lang' => true,
			],
			'audio'      => [
				'autoplay' => true,
				'controls' => true,
				'loop'     => true,
				'muted'    => true,
				'preload'  => true,
				'src'      => true,
			],
			'b'          => [],
			'bdo'        => [
				'dir' => true,
			],
			'big'        => [],
			'blockquote' => [
				'cite'     => true,
				'lang'     => true,
				'xml:lang' => true,
			],
			'br'         => [],
			'button'     => [
				'disabled'      => true,
				'name'          => true,
				'type'          => true,
				'value'         => true,
				'class'         => true,
				'aria-controls' => true,
				'aria-expanded' => true,
				'id'            => true,
				'style'         => true,
			],
			'caption'    => [
				'align' => true,
			],
			'cite'       => [
				'dir'  => true,
				'lang' => true,
			],
			'code'       => [],
			'col'        => [
				'align'   => true,
				'char'    => true,
				'charoff' => true,
				'span'    => true,
				'dir'     => true,
				'valign'  => true,
				'width'   => true,
			],
			'colgroup'   => [
				'align'   => true,
				'char'    => true,
				'charoff' => true,
				'span'    => true,
				'valign'  => true,
				'width'   => true,
			],
			'del'        => [
				'datetime' => true,
			],
			'dd'         => [],
			'dfn'        => [],
			'details'    => [
				'align'    => true,
				'dir'      => true,
				'lang'     => true,
				'open'     => true,
				'xml:lang' => true,
			],
			'div'        => [
				'class'    => true,
				'style'    => true,
				'id'       => true,
				'align'    => true,
				'dir'      => true,
				'lang'     => true,
				'xml:lang' => true,
			],
			'dl'         => [],
			'dt'         => [],
			'em'         => [],
			'fieldset'   => [],
			'figure'     => [
				'align'    => true,
				'dir'      => true,
				'lang'     => true,
				'xml:lang' => true,
			],
			'figcaption' => [
				'align'    => true,
				'dir'      => true,
				'lang'     => true,
				'xml:lang' => true,
			],
			'font'       => [
				'color' => true,
				'face'  => true,
				'size'  => true,
			],
			'footer'     => [
				'align'    => true,
				'dir'      => true,
				'lang'     => true,
				'xml:lang' => true,
			],
			'h1'         => [
				'align' => true,
				'class' => true,
				'style' => true,
				'id'    => true,
			],
			'h2'         => [
				'align' => true,
				'class' => true,
				'style' => true,
				'id'    => true,
			],
			'h3'         => [
				'align' => true,
				'class' => true,
				'style' => true,
				'id'    => true,
			],
			'h4'         => [
				'align' => true,
				'class' => true,
				'style' => true,
				'id'    => true,
			],
			'h5'         => [
				'align' => true,
				'class' => true,
				'style' => true,
				'id'    => true,
			],
			'h6'         => [
				'align' => true,
				'class' => true,
				'style' => true,
				'id'    => true,
			],
			'header'     => [
				'align'    => true,
				'dir'      => true,
				'lang'     => true,
				'xml:lang' => true,
			],
			'hgroup'     => [
				'align'    => true,
				'dir'      => true,
				'lang'     => true,
				'xml:lang' => true,
			],
			'hr'         => [
				'align'   => true,
				'noshade' => true,
				'size'    => true,
				'width'   => true,
			],
			'i'          => [],
			'img'        => [
				'alt'      => true,
				'class'    => true,
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
			'ins'        => [
				'datetime' => true,
				'cite'     => true,
			],
			'kbd'        => [],
			'label'      => [
				'for' => true,
			],
			'legend'     => [
				'align' => true,
			],
			'li'         => [
				'align' => true,
				'value' => true,
			],
			'map'        => [
				'name' => true,
			],
			'mark'       => [],
			'menu'       => [
				'type' => true,
			],
			'nav'        => [
				'align'    => true,
				'dir'      => true,
				'lang'     => true,
				'xml:lang' => true,
			],
			'p'          => [
				'class'    => true,
				'id'       => true,
				'align'    => true,
				'dir'      => true,
				'lang'     => true,
				'xml:lang' => true,
			],
			'pre'        => [
				'width' => true,
			],
			'q'          => [
				'cite' => true,
			],
			's'          => [],
			'samp'       => [],
			'span'       => [
				'class'    => true,
				'dir'      => true,
				'align'    => true,
				'lang'     => true,
				'xml:lang' => true,
			],
			'section'    => [
				'align'    => true,
				'dir'      => true,
				'lang'     => true,
				'xml:lang' => true,
			],
			'small'      => [],
			'strike'     => [],
			'strong'     => [],
			'sub'        => [],
			'summary'    => [
				'align'    => true,
				'dir'      => true,
				'lang'     => true,
				'xml:lang' => true,
			],
			'sup'        => [],
			'table'      => [
				'align'       => true,
				'bgcolor'     => true,
				'border'      => true,
				'cellpadding' => true,
				'cellspacing' => true,
				'dir'         => true,
				'rules'       => true,
				'summary'     => true,
				'width'       => true,
			],
			'tbody'      => [
				'align'   => true,
				'char'    => true,
				'charoff' => true,
				'valign'  => true,
			],
			'td'         => [
				'abbr'    => true,
				'align'   => true,
				'axis'    => true,
				'bgcolor' => true,
				'char'    => true,
				'charoff' => true,
				'colspan' => true,
				'dir'     => true,
				'headers' => true,
				'height'  => true,
				'nowrap'  => true,
				'rowspan' => true,
				'scope'   => true,
				'valign'  => true,
				'width'   => true,
			],
			'textarea'   => [
				'cols'     => true,
				'rows'     => true,
				'disabled' => true,
				'name'     => true,
				'readonly' => true,
			],
			'tfoot'      => [
				'align'   => true,
				'char'    => true,
				'charoff' => true,
				'valign'  => true,
			],
			'th'         => [
				'abbr'    => true,
				'align'   => true,
				'axis'    => true,
				'bgcolor' => true,
				'char'    => true,
				'charoff' => true,
				'colspan' => true,
				'headers' => true,
				'height'  => true,
				'nowrap'  => true,
				'rowspan' => true,
				'scope'   => true,
				'valign'  => true,
				'width'   => true,
			],
			'thead'      => [
				'align'   => true,
				'char'    => true,
				'charoff' => true,
				'valign'  => true,
			],
			'title'      => [],
			'tr'         => [
				'align'   => true,
				'bgcolor' => true,
				'char'    => true,
				'charoff' => true,
				'valign'  => true,
			],
			'track'      => [
				'default' => true,
				'kind'    => true,
				'label'   => true,
				'src'     => true,
				'srclang' => true,
			],
			'tt'         => [],
			'u'          => [],
			'ul'         => [
				'type' => true,
			],
			'ol'         => [
				'start'    => true,
				'type'     => true,
				'reversed' => true,
			],
			'var'        => [],
			'video'      => [
				'autoplay' => true,
				'controls' => true,
				'height'   => true,
				'loop'     => true,
				'muted'    => true,
				'poster'   => true,
				'preload'  => true,
				'src'      => true,
				'width'    => true,
			],
		];
		$output = [];
		if ( $elements ) {
			foreach ( $elements as $element ) {
				$output[ $element ] = $all[ $element ];
			}
		}

		return ( $output || $custom ) ? array_merge( $output, $custom ) : $all;
	}
}