/**
* @snippet        Add text for guests only using shortcodes. Shortcode: [visitor_access]TEXT HERE[/visitor_access]
* @author         Systemedic
* @URL			  https://systemedic.nl/
*/

function visitor_access($attr, $content = null) {
	extract(shortcode_atts(array(
		'deny' => '',
	), $attr));
	if ((!is_user_logged_in() && !is_null($content)) || is_feed()) return $content;
	return $deny;
}
add_shortcode('visitor_access', 'visitor_access');
