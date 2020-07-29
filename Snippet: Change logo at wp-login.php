<php
//Change logo at wp-login.php page. Ideal size for logo: 274 x 63 pixels
function custom_admin_logo() {
echo '<style type="text/css">
#header-logo { background-image: url('.get_bloginfo('template_directory').'/images/admin_logo.png) !important; }
</style>';
}
add_action('admin_head', 'custom_admin_logo');
