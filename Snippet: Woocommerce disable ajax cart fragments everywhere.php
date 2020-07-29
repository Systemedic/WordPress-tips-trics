/**
 * @snippet       Disable WooCommerce Ajax Cart Fragments Everywhere. 
 * @author        Systemedic
 * @URL		         https://systemedic.nl/
 */
 
add_action( 'wp_enqueue_scripts', 'systemedic_disable_woocommerce_cart_fragments', 11 ); 
 
function systemedic_disable_woocommerce_cart_fragments() { 
   wp_dequeue_script( 'wc-cart-fragments' ); 
}
