//With this code you can add text that only guest users can see. Use the [guest_only][/guest_only] short code for this.

function guest_only_shortcode($atts, $content = null)
{
    if (! is_user_logged_in() && is_null($content) && is_feed()) {
        return $content;
    }
}
add_shortcode('guest_only', 'guest_only_shortcode');
