![DareDev](https://www.gsarigiannidis.gr/wp-content/uploads/2020/08/daredev.jpg)
# Description
DareDev is a WordPress MU (Must-Use) plugin that facilitates some development tasks. This is a helper plugin, which means that you don't have to update it on finished projects. I use it to speed up my development process and I update it based on my own needs, so it is only tested on my preferred development environment, which includes tools like Advanced Custom Fields, _s starter theme, Gravity Forms, WPML. Most of its functionality is build with Classes, though, so if you don't want to install it as it is, you can probably grab parts of it and incorporate them in your own projects.

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
### Social share links
Easily display social sharing links. On its simplest form, this:
```
echo DareDev\Element::share();
```
Would output a list of icons that share the current url on Social networks. Available options are Facebook, Twitter, LinkedIn, Pinterest and Email. You can restrict it to specific networks like so:

```
echo DareDev\Element::share(['facebook','twitter']);
```
If you don't like the default icons, or you want to have full control over the output, you can pass your own anchor text for each network, customize the trigger button, pass a specific post id to be shared and modify the container class. Here is a full example:
```
echo DareDev\Element::share(
[
    'facebook' => '<span class="my-facebook">Share me on Facebook</span>',
    'twitter' => '<span class="my-twitter">Share me on Twitter</span>',
],
'<span class="my-button">My Custom share trigger</span>',
$post_id,
'my-container-class'
);
```
### Social links with icons
Similarly, a quick way to display SVG icons with links to your social network profiles:
```
$links = [
    'facebook'  => 'https://www.facebook.com',
    'twitter'   => 'https://www.twitter.com',
    'instagram' => 'https://www.instagram.com',
    'vimeo'     => 'https://www.vimeo.com',
    'youtube'   => 'https://www.youtube.com',
];
echo DareDev\Element::social( $links );
```
The array could come from a group of ACF URLs and currently the SVG icons that are included are facebook, instagram, linkedin, pinterest, twitter, vimeo and youtube.
### Add numeric pagination
Numeric pagination with no need for additional plugins. For example this:
``` 
 echo \DareDev\Element::numericPagination();
```
would output a pagination like that:

&laquo; 1 2 3 ... 6 &raquo;

You can use it with a custom query, use your own prev/next links and assign a custom class name like so:
 
``` 
 echo \DareDev\Element::numericPagination($custom_query, '<span>previous</span>', '<span>next</span>', 'my-class');
```

### Fetch media from Instagram
 Get Instagram media on WordPress using the current Instagram (Facebook) API ([Read the details](https://www.gsarigiannidis.gr/instagram-feed-api-after-june-2020/)). Example:
  
 ``` 
 $media = \DareDev\Instagram::media(TOKEN, USER_ID);
```
### Language switcher
If you are using WPML, you can easily call a custom language switcher wherever you want on your theme, with:
```
echo \DareDev\Language::switcher();
```
This should output a markup similar to this:
```
<div class="dd-lang-container">
    <ul>
        <li class="lang-en">
            <a rel="alternate" hreflang="en" href="http://example.com/en">English</a>
        </li>
        <li class="lang-el current">
            <a rel="alternate" hreflang="el" href="http://example.com/">Greek</a>
        </li>
    </ul>
</div>
```
Full list of parameters:
```
\DareDev\Language::switcher(
    $type = 'wpml',
    $before = '',
    $after = '',
    $params = 'skip_missing=0&orderby=custom'
);
```

`$type` should be `wpml` (the only one supported at the moment).

`$before` and `$after` is any extra content to be displayed before and after the `<ul></ul>` list. 

`$params` include any extra parameters to the language switcher, as described on the [WPML documetation](https://wpml.org/documentation/getting-started-guide/language-setup/custom-language-switcher/).

## ACF Helper methods
### Display an ACF Google Map
For example:
``` 
$map = get_field('acf_map_field');
echo \DareDev\Map::acf_map_single( $map );
```

Similarly, to display a map with multiple markers from an ACF repeater field:
``` 
$locations = get_field('acf_map_repeater_field');
echo \DareDev\Map::acf_map_repeater( $locations, 'subfield_with_location_data' );
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
### Built a list of links with icons
This can be especially handy if you have a list of social icons in an ACF Group of fields which outputs its data in an array like:
``` 
$links = [
    'facebook' => 'https://www.facebook.com/example',
    'instagram' => 'https://www.instagram.com/example',
    'linkedin-o' => 'https://www.linkedin.com/example',
]
```
...where the array key is the icon name (FontAwesome or whatever) and the value is the URL. So, this:
``` 
echo \DareDev\Element::icon_links($links);
```
would output that: 
``` 
<ul>
    <li class="facebook">
        <a href="https://www.facebook.com/example" target="_blank">
           <span class="icon icon-facebook"></span>
        </a>
    </li>
    <li class="instagram">
        <a href="https://www.instagram.com/example" target="_blank">
           <span class="icon icon-instagram"></span>
        </a>
    </li>
    <li class="linkedin-o">
        <a href="https://www.linkedin.com/example" target="_blank">
           <span class="icon icon-linkedin-o"></span>
        </a>
    </li>
</ul>
```
The function can accept a few parameters, so, on a full example, given an array like this:
``` 
$links = [
    'facebook' => [
        'url' => 'https://www.facebook.com/example',
        'text' => Follow us on Facebook',    
    ],
    'instagram' => [
        'url' => 'https://www.instagram.com/example',
        'text' => 'Follow us on Instagram',
    ],
    'linkedin-o' => [
        'url' => 'https://www.linkedin.com/example',
        'text' => 'Follow us on LinkedIn',
    ],
]
```
the first value of each entry would be considered the URL and the second some additional text that should be displayed. So, with this:
``` 
echo \DareDev\Element::icon_links(
    $links,
    '<ul class="social">',
    '</ul>,
    'fa fab-'
);
```
we get that:
``` 
<ul class="social">
    <li class="facebook">
        <a href="https://www.facebook.com/example" target="_blank">
           <span class="fa fab-facebook">
                <span>Follow us on Facebook</span>
           </span>
        </a>
    </li>
    <li class="instagram">
        <a href="https://www.instagram.com/example" target="_blank">
            <span class="fa fab-instagram">
               <span>Follow us on Instagram</span>
          </span>
        </a>
    </li>
    <li class="linkedin-o">
        <a href="https://www.linkedin.com/example" target="_blank">
           <span class="fa fab-linkedin-o">
                <span>Follow us on LinkedIn</span>
           </span>
        </a>
    </li>
</ul>
```
## Gutenberg Helper methods
### Get specific blocks from a post
It's like getting a post's featured image, only with a specific Gutenberg block instead of the photo ([Read the details](https://www.gsarigiannidis.gr/wordpress-gutenberg-block-featured-content/)). In its simplest form all you have to do is this:
```
echo \DareDev\Block::get( [ 'core/gallery' ] );
```
The above will show the first gallery block of the current post within a loop. The method's full parameters include:
```
\DareDev\Block::get(
    $block_names = [], 
    $selector = 'first|last|all'|integer, 
    $post_id = null
);
```
Where `$block_names` is the array of the blocks that you want to search for. 

`$selector` parameter accepts 4 types of values: `first` will get you only the first block that it finds. `last` will show the last and `all` will get you every instance of the given blocks. To make it more specific, you can  pass an integer with the exact position of the block that you want to fetch. For example, on a post with multiple galleries, this:
```
echo \DareDev\Block::get( [ 'core/gallery' ], 2 );
```
Will display the third gallery of the post (remember that count starts from 0).

With `$post_id` you can pass the id of a specific post. Leaving it empty will get the post of the loop that the method gets called in.

## Other Helper functions
### Get inline SVG from an image ID or URL
Inline SVG elements allow more flexibility with styling. To get such an inline element from an existing SVG file, use:
``` 
echo \DareDev\Element::inline_svg( $icon_id_path_or_url, 'example-class', 'example-id' );
```
Examples:
``` 
\DareDev\Element::inline_svg( 10 );
```
...would display the svg icon uploaded to WordPress, with the ID 10.
``` 
\DareDev\Element::inline_svg( WPMU_PLUGIN_DIR . '/daredev/icons/caret-right.svg' );
```
...would display the icon found on a specific path on the server.
Finally, if you want to display one of the [icons included in the plugin](https://github.com/gsarig/daredev/tree/master/daredev/icons) you can simply pass the name of icon like so (without the .svg prefix):
``` 
\DareDev\Element::inline_svg( 'email' );
```
(The list of available icons will probably get updated everytime I need something new for some new project)
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
### Get site name from URL
For example, this:
``` 
\DareDev\Helper::name_from_url( 'https://www.example.com' );
```
will return that:
``` 
Example
```
### Format date
Get the post date and apply different style to each part. 

For example, this:
``` 
\DareDev\Helper::date(
    [ 
        '<span class="mon"></span>',    
        '<span class="day"></span>' 
    ],
    'M j'
);
``` 
Will return that: 
``` 
<span class="mon">Dec</span>
<span class="day">13</span>	
```
### Search a multidimentional array recursively
```
$new_arr = \Helper\array_search($array, $key = '', $value = [] );
```
is an alternative to PHP's `array_search()` which can search for multiple values inside a multidimentional array of unknown depth. It will return a new flattened array with the results that it found. 

## Customize settings
The plugin has a few settings that you can manually turn on. To do so, there is the `daredev_settings` filter, which you can use in your theme/plugin like so:

```
function my_custom_settings( $options ) {

    // Hides ACF Settings from the admin, if WP-DEBUG is off
    $options['acf_hide'] = true;

    // Enables script injections
    $options['custom_scripts'] = true;

    // Adds additional ACF json paths
    $options['acf_paths'] = [
        '/my-path-1/acf-json',
        '/my-path-2/folder',
    ];

    return $options;
}

add_filter( 'daredev_settings', 'my_custom_settings' );
```