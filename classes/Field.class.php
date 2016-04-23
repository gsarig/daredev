<?php

namespace DareDev;

use WPCF7_Shortcode;


class Field {

	public $tagname;
	public $data;
	public $subfield;
	public $id;

	/**
	 * Field constructor.
	 *
	 * If the second parameter is an array, it will show the passed options.
	 * To get ACF repeater fields, use it like that:
	 * $myfield = new \DareDev\Field( 'fieldname', 'acf_repeater_field', 'acf_repeater_subfield' );
	 * $myfield->select();
	 *
	 * @param string $tagname
	 * @param string $data
	 * @param string $subfield
	 * @param integer $id
	 */
	public function __construct( $tagname, $data, $subfield = '', $id = null ) {
		$this->name     = $tagname;
		$this->subfield = $subfield;
		$this->data     = $data;
		$this->id       = $id;
	}

	public function getData() {
		$id     = ( $this->id ) ? $this->id : get_the_ID();
		$output = [ ];
		if ( is_string( $this->data ) && have_rows( $this->data, $id ) ):
			while ( have_rows( $this->data, $id ) ) : the_row();
				$output[] = get_sub_field( $this->subfield );
			endwhile;
		endif;

		return ( is_string( $this->data ) ) ? $output : $this->data;
	}

	/*
	 * Select Field
	 */
	public function select() {
		add_action( 'wpcf7_init', [ $this, 'daredev_add_shortcode_select' ] );
		add_filter( 'wpcf7_validate_select', [ $this, 'daredev_select_validation_filter' ], 10, 2 );
	}

	public function daredev_add_shortcode_select() {
		wpcf7_add_shortcode( [ $this->name ], [ $this, 'prepareSelect' ] );
	}

	public function prepareSelect( $tag ) {
		$tag      = new WPCF7_Shortcode( $tag );
		$name     = ( $tag->has_option( 'multiple' ) ) ? $this->name . '[]' : $this->name;
		$multiple = ( $tag->has_option( 'multiple' ) ) ? ' multiple="multiple"' : '';
		$options  = '';
		if ( $this->data ) {
			$output = '';
			foreach ( self::getData() as $option ) {
				$output .= '<option value="' . $option . '">
							' . $option . '
						</option>';
			}
			$options = '<span class="wpcf7-form-control-wrap ' . $name . '">
				<select name="' . $name . '" class="wpcf7-form-control wpcf7-select" aria-invalid="false"' . $multiple . '>'
			           . $output .
			           '</select>
			</span>';
		}

		return $options;
	}

	public function daredev_select_validation_filter( $result, $tag ) {
		$tag  = new WPCF7_Shortcode( $tag );
		$name = $tag->name;
		if ( $this->name == $name ) {
			if ( isset( $_POST[ $name ] ) && is_array( $_POST[ $name ] ) ) {
				foreach ( $_POST[ $name ] as $key => $value ) {
					if ( '' === $value ) {
						unset( $_POST[ $name ][ $key ] );
					}
				}
			}
			$empty = ! isset( $_POST[ $name ] ) || empty( $_POST[ $name ] ) && '0' !== $_POST[ $name ];

			if ( $tag->is_required() && $empty ) {
				$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
			}
		}

		return $result;
	}
}