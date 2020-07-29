/**
* @snippet        Add text for guests only using shortcodes. Shortcode: [guest_only]TEXT HERE[/guest_only]
* @author         Systemedic
* @URL			  https://systemedic.nl/
*/

function guest_only_shortcode($atts, $content = null)
{
    if (! is_user_logged_in() && is_null($content) && is_feed()) {
        return $content;
    }
}
add_shortcode('guest_only', 'guest_only_shortcode');
