/**
* @snippet       Custom Redirect after logging in to my account for Woocommerce
* @author        Systemedic
* @URL			     https://systemedic.nl/
*/

add_filter('woocommerce_login_redirect', 'pro_login_redirect');
function pro_login_redirect( $redirect_to ) {
$redirect_to = get_permalink( 700 ); //Fill in the page ID between the ()
return $redirect_to;
}
