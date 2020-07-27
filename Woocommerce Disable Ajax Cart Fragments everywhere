/**
 * @snippet       Disable WooCommerce Ajax Cart Fragments Everywhere
 * @author        Systemedic
 * @compatible    WooCommerce 3.6.4 and up
 * @URL		        https://systemedic.nl/
 */
 
add_action( 'wp_enqueue_scripts', 'bbloomer_disable_woocommerce_cart_fragments', 11 ); 
 
function bbloomer_disable_woocommerce_cart_fragments() { 
   wp_dequeue_script( 'wc-cart-fragments' ); 
}
