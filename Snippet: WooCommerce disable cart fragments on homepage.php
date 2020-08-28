/**
 * @snippet       Disable WooCommerce Ajax Cart Fragments on homepage. 
 * @author        Systemedic
 * @URL		        https://systemedic.nl/
 */

add_action( 'wp_enqueue_scripts', 'systemedic_disable_woocommerce_cart_fragments', 11 ); 
 
function systemedic_disable_woocommerce_cart_fragments() { 
   if ( is_front_page() ) wp_dequeue_script( 'wc-cart-fragments' ); 
}
