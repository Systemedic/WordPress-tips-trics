// this will remove add to cart button and add a read more button to all products in the cart. and remove the add to cart everywhere. 
// only for logged out users 
 
add_action( 'init', 'shessvy_hide_price_add_cart_not_logged_in' );
 
function shessvy_hide_price_add_cart_not_logged_in() {
    if ( ! is_user_logged_in() ) {
 
        add_filter( 'woocommerce_is_purchasable', '__return_false');
        add_action( 'woocommerce_single_product_summary', 'shessvy_print_login_to_see', 31 );
        add_action( 'woocommerce_after_shop_loop_item', 'shessvy_print_login_to_see', 11 );
        add_action( 'woocommerce_simple_add_to_cart', 'shessvy_print_login_to_see', 30 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
 		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
    }
}
 
function shessvy_print_login_to_see() {
    echo '<a href="' . get_permalink(wc_get_page_id('myaccount')) . '" rel="nofollow ugc">' . __('Login or register to see prices', 'theme_name') . '</a>';
}
