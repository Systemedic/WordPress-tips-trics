/****************************************************************
 * @snippet       Disable WooCommerce Ajax Cart Fragments Everywhere. 
 * @author        Systemedic
 * @URL		         https://systemedic.nl/
*****************************************************************/
 
add_action( 'wp_enqueue_scripts', 'systemedic_disable_woocommerce_cart_fragments', 11 ); 
 
function systemedic_disable_woocommerce_cart_fragments() { 
   wp_dequeue_script( 'wc-cart-fragments' ); 
}

/****************************************************************
 * @snippet       Disable WooCommerce Ajax Cart Fragments on posts and pages. 
 * @author        Systemedic
 * @URL		         https://systemedic.nl/
*****************************************************************/
add_action( 'wp_enqueue_scripts', 'dequeue_woocommerce_cart_fragments', 11);
function dequeue_woocommerce_cart_fragments() {
if (is_front_page() || is_single() ) wp_dequeue_script('wc-cart-fragments');
}

/****************************************************************
 * @snippet       Disable WooCommerce Ajax Cart Fragments on frontpage 
 * @author        Systemedic
 * @URL		         https://systemedic.nl/
*****************************************************************/
add_action( 'wp_enqueue_scripts', 'dequeue_woocommerce_cart_fragments', 11); 
function dequeue_woocommerce_cart_fragments() { if (is_front_page()) wp_dequeue_script('wc-cart-fragments'); }
