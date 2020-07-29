/**
* @snippet       Change excerpt read more text
* @author        Systemedic
* @URL			        https://systemedic.nl/
*/

function systemedic_change_excerpt_more_text( $more ){
 global $post;
 return '&hellip; <a class="read-more" href="'.get_permalink($post->ID).'" title="'.esc_attr(get_the_title($post->ID)).'">'.'Read More &raquo;'.'</a>';
}
add_filter('excerpt_more', 'systemedic_change_excerpt_more_text');
