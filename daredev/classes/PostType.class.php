<?php

namespace DareDev;

class PostType {

	public $type;
	public $slug;
	public $options = [];
	public $labels  = [];
	public $greek;

	public function __construct( $type, $options = [], $labels = [], $slug = false, $greek = false ) {
		$this->type      = $type;
		$this->slug      = $slug;
		$this->greek     = $greek;
		$default_options = [
			'public'      => true,
			'supports'    => [ 'title', 'editor', 'revisions' ],
			'has_archive' => true,
			'rewrite'     => [ 'slug' => ( false !== $this->slug ) ? strtolower( $this->slug ) : strtolower( $this->type ) ],
		];
		$required_labels = [
			'singular_name' => ucwords( $this->type ),
			'plural_name'   => ucwords( $this->type ),
			'singular_case' => ucwords( $this->type ),
			'plural_case'   => ucwords( $this->type )
		];
		$this->options   = $options + $default_options;
		$this->labels    = $labels + $required_labels;

		$this->options['labels'] = $this->labels + $this->defaultLabels();

		add_action( 'init', array( $this, 'register' ) );

	}

	public function register() {
		register_post_type( $this->type, $this->options );
	}

	public function defaultLabels() {
		$label = ( 'greek' === $this->greek ) ?
			[
				$this->labels['singular_case'],
				$this->labels['plural_case'],
				'Προσθήκη ',
				'Επεξεργασία ',
				'Προσθήκη ',
				'Προβολή ',
				'Αναζήτηση ',
				'Δεν βρέθηκαν ',
				'',
				'Δεν βρέθηκαν ',
				' στον κάδο',
				'Γονέας: '
			] :
			[
				$this->labels['singular_name'],
				$this->labels['plural_name'],
				'Add new ',
				'Edit ',
				'New ',
				'View ',
				'Search ',
				'No matching ',
				' found',
				'No ',
				' found in trash',
				'Parent '
			];

		return [
			'name'               => $this->labels['plural_name'],
			'singular_name'      => $this->labels['singular_name'],
			'add_new'            => $label[2] . $label[0],
			'add_new_item'       => $label[2] . $label[0],
			'edit_item'          => $label[3] . $label[0],
			'new_item'           => $label[4] . $label[0],
			'view_item'          => $label[5] . $label[0],
			'search_items'       => $label[6] . $label[1],
			'not_found'          => $label[7] . $this->labels['plural_name'] . $label[8],
			'not_found_in_trash' => $label[9] . $label[1] . $label[10],
			'parent_item_colon'  => $label[11] . $this->labels['singular_name'],
			'menu_name'          => $this->labels['plural_name']
		];
	}
}

/* Call it like that (bare minimum):

    $cpt_name = new \DareDev\PostType('cpt_name' );

    And a full example:
    
    $cpt_name = new \DareDev\PostType(
        'cpt_name', 
        [
            'taxonomies' => ['post_tag', 'category'],
            'has_archive' => true, 
            'supports' => ['title', 'editor'] 
        ], 
        [
            'singular_name' => 'Όνομα Ενικός', 
            'plural_name'   => 'Όνομα Πληθυντικός', 
            'singular_case' => 'Γενική Ενικού', 
            'plural_case'   => 'Γενική Πληθυντικού'
        ], 
        'archive_slug', 
        'greek'
    );
*/