# Description
DareDev is a WordPress MU (Must-Use) plugin which facilitates some development tasks. This is a helper plugin, which means that you don't have to update it on finished projects. I use it to speed-up my development process and I update it based on my own needs, so it is only tested on my preferred development environment, which includes tools like Advanced Custom Fields, _s starter theme, Gravity Forms, WPML. Most of its functionality is build with Classes, though, so if you don't want to install it as it is, you can probably grab parts of it and incorporate them in you own projects. 

# Examples
The following is only a part of the plugin's available functionality. I will try to update the documentation as frequently as I can. 
### Easily register custom post types
``` 
$cpt_name = new \DareDev\PostType('cpt_name' );
```
And a full example with Greek labels:
```    
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
```
### Easily register custom taxonomies
``` 
 $cpt_name = new Taxonomy('taxonomy_name' );

```
And a full example with Greek labels:
```
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
```
## ACF Helper methods
### Display an ACF Google Map
For example:
``` 
$map = get_field('acf_map_field');
echo \DareDev\Map::acf_map_single( $map );
```
Full list of parameters:
``` 
\DareDev\Map::acf_map_single(
    $field,
    $map_id = 'map',
    $class = 'map',
    $width = '100%',
    $height = '500px',
    $more_txt = '',
    $icon = null,
    $colors = null,
    $zoom = 14
);
```
`$icon` can be a custom URL pointing to your custom marker icon.
`$colors` is an array of 3 colors which will be applied to the map. for example:
`['#cccccc', '#000000', '#ffffff']`
### Construct Link HTML from ACF Link field. 
For example this:
```
$link = get_field('my-acf-link-field');
echo DareDev\Field::link( $link, 'custom-class', '<span>', '</span>' );
```
Will output that:
```
<a href="{acf_link_url}" target="{acf_link_target}" class="custom-class">
    <span>{acf_link_text}</span>
</a>
```
### Take a textarea field and give each line a different markup
For example this:
```
$text = get_field('my-acf-textarea-field');
echo DareDev\Field::textarea( $text, [ '<h5></h5>', '<p></p>' ] );
```
Will output that:
```
<h5>First line of the textarea</h5>
<p>Second line of the textarea</p>
```
### Get event date range
If you have an event with a "Start" and "End" date, the following method will take the values and properly display the event's date: sets a separator and if the "Start" date is the same as the "End" date, it will display it only once. 
``` 
DareDev\Field::date_range(
    $from,
    $to = '',
    $separator = '-',
    $markup = '',
    $date_format = 'd/m/Y g:i a'
);
```
## Other Helper functions
### Limit the displayed excerpt length
A replacement of `get_the_excerpt()` which allows you to set the character length. If an actual excerpt has been set by the user, it will not trim it. Trimming only occurs when no excerpt is set and WordPress uses a part of the content. Example usage: 
``` 
echo \DareDev\Excerpt::limit(40, 'more...');	 
```
### Get page template ID by template name
Give a page template's name and it will return the ID of the page that it's using it (If it finds multiple pages, it will only return the first one).
``` 
DareDev\Helper::page_template_id( 
    $page_template_name, 
    $post_type = 'page', 
    $path = 'page-templates/' 
);
```
### Obfuscate emails found on a given text
Obfuscate all the emails found on a given content using WordPress' antispambot() function.
``` 
\DareDev\Helper::obfuscate_email( $content );
```
### Sanitize HTML
Easily add elements to be sanitized on WordPress' wp_kses() function. 
For example, the following would only allow `a` and `img` tags:
``` 
wp_kses(
    $content,
    \DareDev\Helper::kses_allow_html( [ 'a', 'img' ] )
);
```
### Remove Accents from a text
This is useful for Greek text, where you might want to transform text to uppercase with CSS and you don't want to keep the accents:
```
\DareDev\Transform::removeAccents( $string );
```
