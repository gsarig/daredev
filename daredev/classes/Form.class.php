<?php
/**
 * Form-related methods.
 */

namespace DareDev;

use domDocument;
use DOMXPath;

class Form {

	/**
	 * Clean-up Gravity Form's markup.
	 *
	 * This will clean-up the main HTML markup, but the fields' containers will remain.
	 * To clean them up too, you must use some of the following filters in your theme:
	 *
	 * gform_field_input: If you need to add extra classes to the inputs themselves.
	 * gform_field_container: To remove the <li></li> markup from the inputs and maybe remove the labels as well.
	 * gform_submit_button: To make modifications to the submit button.
	 *
	 * @param int $form_id
	 * @param bool $footer_wrap
	 * @param object $form_obj
	 *
	 * @return string
	 */
	public static function gravity( $form_id, $footer_wrap = false, $form_obj = null ) {

		// If a specific form object is given by the user, use it. Otherwise use our default options.
		$form = $form_obj ?
			$form_obj :
			gravity_form( $form_id, false, false, null, null, true, 1, false );

		// Create a new dom object.
		$dom = new domDocument();

		// Load the form's html into the object.
		$dom->loadHTML( $form );

		// Discard white space.
		$dom->preserveWhiteSpace = false;

		// Get the main form Element by ID.
		$gform = $dom->getElementById( 'gform_' . $form_id );

		// Use DomXPath to find elements by regular expressions (classname and id).
		$xpath = new DomXPath( $dom );

		// Get the form's body and footer contents and store them in variables.
		$gform_ul             = $xpath->query( '//ul[@id="gform_fields_' . $form_id . '"]/*' );
		$gform_footer_content = $xpath->query( '//div[contains(@class, "gform_footer")]/*' );

		// Get the form's body and footer nodes in order to modify them.
		$gform_body   = $xpath->query( '//div[contains(@class, "gform_body")]' )->item( 0 );
		$gform_footer = $xpath->query( '//div[contains(@class, "gform_footer")]' )->item( 0 );


		// Remove the body container and append the contents that we saved earlier.
		$gform->removeChild( $gform_body );
		foreach ( $gform_ul as $node ) {
			$gform->appendChild( $node );
		}

		// Remove the footer container.
		$gform->removeChild( $gform_footer );
		// If the user wants it to have wrap, put it back, at the end of the form (append), and set its extra classes.
		if ( false !== $footer_wrap ) {
			$gform->appendChild( $gform_footer );
			$gform_footer->setAttribute( 'class', $footer_wrap );
			// Else keep it removed and append its contents (those that we saved earlier) to the form.
		} else {
			foreach ( $gform_footer_content as $node ) {
				$gform->appendChild( $node );
			}
		}

		return $dom->saveHTML();
	}

}