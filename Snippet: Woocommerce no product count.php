/**
* @snippet       Hide category product count in product archives
* @author        Systemedic
* @URL			     https://systemedic.nl/
*/

add_filter( 'woocommerce_subcategory_count_html', '__return_false' );
