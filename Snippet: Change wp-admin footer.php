/**
* @snippet        Snippet: Change wp-admin footer
* @author         Systemedic
* @URL            https://systemedic.nl/
*/

function remove_footer_admin () {
 
echo 'Ontwikkeling door <a href="https://systemedic.nl" target="_blank">Systemedic</a> | Vragen & Ondersteuning: <a href="https://support.systemedic.nl" target="_blank">Systemedic Support</a></p>';
 
}
 
add_filter('admin_footer_text', 'remove_footer_admin');
