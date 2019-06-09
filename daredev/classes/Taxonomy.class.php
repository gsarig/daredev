<?php

namespace DareDev;

class Taxonomy {

	public $name;
	public $cpt = [];
	public $slug;
	public $labels = [];
	public $greek;
	public $column;
	public $rest;

	public function __construct(
		$name,
		$cpt = [],
		$labels = [],
		$slug = false,
		$greek = false,
		$column = false,
		$rest = false
	) {
		$this->name       = $name;
		$required_labels  = [
			'singular_name' => ucwords( $this->name ),
			'plural_name'   => ucwords( $this->name ),
			'singular_case' => ucwords( $this->name ),
			'plural_case'   => ucwords( $this->name ),
		];
		$this->labels     = $labels + $required_labels;
		$this->cpt        = $cpt;
		$this->slug       = $slug;
		$this->greek      = $greek;
		$this->get_labels = $this->labels + $this->defaultLabels();
		$this->column     = $column;
		$this->rest       = $rest;
		$this->options    = [
			'labels'            => $this->get_labels,
			'hierarchical'      => true,
			'rewrite'           => [ 'slug' => ( false !== $this->slug ) ? strtolower( $this->slug ) : strtolower( $this->name ) ],
			'show_admin_column' => $this->column,
			'show_in_rest'      => $this->rest,
		];

		add_action( 'init', array( $this, 'register' ) );

	}


	public function register() {
		register_taxonomy( $this->name, $this->cpt, $this->options );
	}

	public function defaultLabels() {

		$label = ( 'greek' === $this->greek ) ?
			[
				$this->labels['singular_case'],
				$this->labels['plural_case'],
				'Αναζήτηση ',
				'Προβολή όλων των ',
				'Ανήκει: ',
				'Επεξεργασία ',
				'Ενημέρωση ',
				'Προσθήκη ',
				'Νέο όνομα ',
			] :
			[
				$this->labels['singular_name'],
				$this->labels['plural_name'],
				'Search ',
				'All ',
				'Parent ',
				'Edit ',
				'Update ',
				'Add New ',
				'New Name for ',
			];

		return [
			'name'              => $this->labels['plural_name'],
			'singular_name'     => $this->labels['singular_name'],
			'search_items'      => $label[2] . $label[1],
			'all_items'         => $label[3] . $label[1],
			'parent_item'       => $label[4] . $label[0],
			'parent_item_colon' => $label[4] . $label[0],
			'edit_item'         => $label[5] . $label[0],
			'update_item'       => $label[6] . $label[0],
			'add_new_item'      => $label[7] . $label[0],
			'new_item_name'     => $label[8] . $label[0],
			'menu_name'         => $this->labels['plural_name'],
		];
	}

}

/* Call it like that (bare minimum):

    $cpt_name = new Taxonomy('taxonomy_name' );

    And a full example:

    $cpt_name = new \DareDev\Taxonomy(
        'taxonomy_name',
        [
            'cpt_1',
            'cpt_2'
        ],
        [
            'singular_name' => 'Όνομα Ενικός',
            'plural_name'   => 'Όνομα Πληθυντικός',
            'singular_case' => 'Γενική Ενικού',
            'plural_case'   => 'Γενική Πληθυντικού'
        ],
        'archive_slug',
        'greek',
        true
    );
*/