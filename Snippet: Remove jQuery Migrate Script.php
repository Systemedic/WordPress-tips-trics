CURRENTLY THIS IS GIVING PROBLEMS WITH ELEMENTOR

/**
* @snippet        Remove jQuery Migrate Script.php
* @author         Systemedic
* @URL            https://systemedic.nl/
*/

if ( ! function_exists( 'evolution_remove_jquery_migrate' ) ) :

function evolution_remove_jquery_migrate( &$scripts) {
    if(!is_admin()) {
        $scripts->remove( 'jquery');
        $scripts->add( 'jquery', false, array( 'jquery-core' ), '1.12.4' );
    }
}
add_filter( 'wp_default_scripts', 'evolution_remove_jquery_migrate' );
endif;
