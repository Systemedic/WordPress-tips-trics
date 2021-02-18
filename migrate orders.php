<?php

//increase max execution time of this script to 150 min:
ini_set('max_execution_time', 9000);
//increase Allowed Memory Size of this script:
ini_set('memory_limit','960M');

// Copies woocommerce orders and users over from source to target.
// I use this on my local machine - loading both db's up there side by side
// could easily adjust the connect strings to connect elsewhere if needed.
// will change order ids

// My use case for this is when I've got a staging/test version of a site with new posts/products/pages etc, that needs
// to go live without the loss of any orders placed on the site site since we copied it to the staging site.

// names of source and target dbs.
define('NEW_DB', 'NEW DB NAME HERE');
define('OLD_DB', 'OLD DB NAME HERE');

$tablePrefixTarget = 'wp_';
$tablePrefixSource = 'wp_';

// enter the db credentials
echo 'Connecting to ' . NEW_DB . ' Server<br />';
$new_conn = mysqli_connect( 'localhost', 'DB-USER', 'YOUR-PASSWORD', NEW_DB );
if ( mysqli_connect_errno() ) {
	printf( "Connect to %s failed: %s<br />",
		NEW_DB,
		mysqli_connect_error()
	);
	exit();
} else {
	echo 'SUCCESS! Connected to ' . NEW_DB . ' Server<br />';
}
echo 'Connecting to ' . OLD_DB . ' Server<br />';
$old_conn = mysqli_connect( 'localhost', 'DB-USER', 'YOUR-PASSWORD', OLD_DB );
if ( mysqli_connect_errno() ) {
	printf( "Connect to %s failed: %s<br />",
		OLD_DB,
		mysqli_connect_error()
	);
	exit();
} else {
	echo 'SUCCESS! Connected to ' . OLD_DB . ' Server<br />';
}

echo 'Selecting default ' . NEW_DB . ' Database<br />';
mysqli_select_db( $new_conn, NEW_DB );
if ( $result = mysqli_query( $new_conn, "SELECT DATABASE()" ) ) {
	$row = mysqli_fetch_row( $result );
	printf( "Default %2 database is %s<br />", NEW_DB, $row[0] );
}

echo 'Selecting default ' . OLD_DB . ' Database<br />';
mysqli_select_db( $old_conn, OLD_DB );
if ( $result = mysqli_query( $old_conn, "SELECT DATABASE()" ) ) {
	$row = mysqli_fetch_row( $result );
	printf( "Default %2 database is %s<br />", OLD_DB, $row[0] );
}

// completely replace user and usermeta (orders are related to usermeta - this simplifies things)
echo 'Dumping ' . OLD_DB . ' users<br />';
$cmd = 'mysqldump -h localhost -u root %s %s | mysql -h localhost -u root %s';
exec(sprintf($cmd, OLD_DB, $tablePrefixSource . 'users', NEW_DB));
echo 'Dumping ' . OLD_DB . ' usermeta<br />';
exec(sprintf($cmd, OLD_DB, $tablePrefixSource . 'usermeta', NEW_DB));

// template for inserting the shop_order posts
$post_sql_template = "INSERT INTO `" . $tablePrefixTarget . "posts` (
            `post_author`,
            `post_date`,
            `post_date_gmt`,
            `post_content`,
            `post_title`,
            `post_excerpt`,
            `post_status`,
            `comment_status`,
            `ping_status`,
            `post_password`,
            `post_name`,
            `to_ping`,
            `pinged`,
            `post_modified`,
            `post_modified_gmt`,
            `post_content_filtered`,
            `post_parent`,
            `guid`,
            `menu_order`,
            `post_type`,
            `post_mime_type`,
            `comment_count`
        ) VALUES (
            %s, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %s, '%s', %s, '%s', '%s', %s);";

// template for inserting the order_items
$item_sql_template = "INSERT INTO `" . $tablePrefixTarget . "woocommerce_order_items` (`order_item_name`, `order_item_type`, `order_id`) VALUES ('%s', '%s', '%s')";

// If you want to remove all orders in the target db, and replace with those from source, uncomment this block.
// I don't usually do this as leaving it commented, orders common to both systems will be skipped and so
// retain their original ids

// $sql = "DELETE FROM " . $tablePrefixTarget . "woocommerce_order_itemmeta";
// mysqli_query( $new_conn, $sql );
// $sql = "DELETE FROM " . $tablePrefixTarget . "woocommerce_order_items";
// mysqli_query( $new_conn, $sql );
// $sql = "DELETE FROM " . $tablePrefixTarget . "posts WHERE post_type = 'shop_order'";
// mysqli_query( $new_conn, $sql );

echo 'Selecting ' . OLD_DB . ' Shop Orders<br />';
$sql = "SELECT * FROM " . $tablePrefixSource . "posts WHERE post_type = 'shop_order' ";
$order_res = mysqli_query($old_conn, $sql);
printf( "Select returned %d Shop Orders.<br />", $order_res->num_rows );
printf( "Processing Orders...<br />" );

while ($row = mysqli_fetch_assoc($order_res)) {
    $old_id = $row['ID'];

    // check to see if a shop_order post with this id exists - if so, skip
    // (see note on deleting above - this won't happen if that block is uncommented)
	 echo 'Checking ' . NEW_DB . ' for existing order ' . $old_id .'<br />';
    $sql = "SELECT * FROM " . $tablePrefixTarget . "posts WHERE post_type = 'shop_order' AND ID = " . $old_id;
    $count_res = mysqli_query($new_conn, $sql);
    if (mysqli_num_rows($count_res)) {
        echo "Skipping " . $old_id . "...<br />";
        continue;
    }
    echo "Processing " . $old_id . "...<br />";

    // add the post
    $post_sql = sprintf($post_sql_template,
        $row['post_author'],
        $row['post_date'],
        $row['post_date_gmt'],
        $row['post_content'],
        $row['post_title'],
        $row['post_excerpt'],
        $row['post_status'],
        $row['comment_status'],
        $row['ping_status'],
        $row['post_password'],
        $row['post_name'],
        $row['to_ping'],
        $row['pinged'],
        $row['post_modified'],
        $row['post_modified_gmt'],
        $row['post_content_filtered'],
        $row['post_parent'],
        $row['guid'],
        $row['menu_order'],
        $row['post_type'],
        $row['post_mime_type'],
        $row['comment_count']
    );

	 echo 'Inserting ' . NEW_DB . ' Order<br />';
	 $insert_post_res = mysqli_query($new_conn, $post_sql);
    $new_id = mysqli_insert_id($new_conn);

    // and the postmeta
	 echo 'Inserting ' . NEW_DB . ' Postmeta<br />';
    $get = "SELECT meta_key, meta_value FROM " . OLD_DB . "." . $tablePrefixSource . "postmeta old WHERE old.post_id = " . $old_id;
	 $get_old_data = mysqli_query($old_conn, $get);

    while ($get_old_data_row = mysqli_fetch_assoc($get_old_data)) {
		$sql = "INSERT INTO " . NEW_DB . "." . $tablePrefixTarget . "postmeta (post_id, meta_key, meta_value) VALUES ('$new_id', '" . $get_old_data_row['meta_key'] . "', '" . $get_old_data_row['meta_value'] . "' )";
		mysqli_query($new_conn, $sql);
    }

    // and term relationships (stores order status)
	 echo 'Inserting ' . NEW_DB . ' Order Status (Term Relationship)<br />';
    $get = "SELECT " . $new_id . ", term_taxonomy_id, term_order FROM " . OLD_DB . "." . $tablePrefixSource . "term_relationships old WHERE old.object_id = " . $old_id;
	 $get_old_data = mysqli_query($old_conn, $get);

    while ($get_old_data_row = mysqli_fetch_assoc($get_old_data)) {
    	$sql = "INSERT INTO " . NEW_DB . "." . $tablePrefixTarget . "term_relationships (object_id, term_taxonomy_id, term_order) VALUES ('$new_id', '" . $get_old_data_row['term_taxonomy_id'] . "', '" . $get_old_data_row['term_order'] . "' )";
    	mysqli_query($new_conn, $sql);
	}

    // and the comments - which store order nots
	 echo 'Inserting ' . NEW_DB . ' Comments<br />';
    $get = "SELECT $new_id, `comment_author`, `comment_author_email`, `comment_author_url`, `comment_author_IP`, `comment_date`, `comment_date_gmt`, `comment_content`, `comment_karma`, `comment_approved`, `comment_agent`, `comment_type`, `comment_parent`, `user_id` FROM " . OLD_DB . "." . $tablePrefixSource . "comments old WHERE old.comment_post_ID = " . $old_id;
	 $get_old_data = mysqli_query($old_conn, $get);

    while ($get_old_data_row = mysqli_fetch_assoc($get_old_data)) {
    	$sql = "INSERT INTO " . NEW_DB . "." . $tablePrefixTarget . "comments (`comment_post_ID`, `comment_author`, `comment_author_email`, `comment_author_url`, `comment_author_IP`, `comment_date`, `comment_date_gmt`, `comment_content`, `comment_karma`, `comment_approved`, `comment_agent`, `comment_type`, `comment_parent`, `user_id`) VALUES ('$new_id', '" . $get_old_data_row['comment_author'] . "', '" . $get_old_data_row['comment_author_email'] . "', '" . $get_old_data_row['comment_author_url'] . "', '" . $get_old_data_row['comment_author_IP'] . "', '" . $get_old_data_row['comment_date'] . "', '" . $get_old_data_row['comment_date_gmt'] . "', '" . $get_old_data_row['comment_content'] . "', '" . $get_old_data_row['comment_karma'] . "', '" . $get_old_data_row['comment_approved'] . "', '" . $get_old_data_row['comment_agent'] . "', '" . $get_old_data_row['comment_type'] . "', '" . $get_old_data_row['comment_parent'] . "', '" . $get_old_data_row['user_id'] . "' )";
    	mysqli_query($new_conn, $sql);
	}

    // and then order items and order item meta
	 echo 'Inserting ' . NEW_DB . ' Order Items & Order Items Meta<br />';
    $sql = sprintf("SELECT * FROM " . $tablePrefixSource . "woocommerce_order_items WHERE order_id = %s", $old_id);
    $item_res = mysqli_query($old_conn, $sql);
    while ($item_row = mysqli_fetch_assoc($item_res)) {
        $old_item_id = $item_row['order_item_id'];

        $item_sql = sprintf($item_sql_template,
            $item_row['order_item_name'],
            $item_row['order_item_type'],
            $new_id
        );
		mysqli_query($new_conn, $item_sql);
        $new_item_id = mysqli_insert_id($new_conn);

        $get = "SELECT meta_key, meta_value FROM " . OLD_DB . "." . $tablePrefixSource . "woocommerce_order_itemmeta old WHERE old.order_item_id = " . $old_item_id;
		$get_old_data = mysqli_query($old_conn, $get);
		while ($get_old_data_row = mysqli_fetch_assoc($get_old_data)) {
        	$sql = "INSERT INTO " . NEW_DB . "." . $tablePrefixTarget . "woocommerce_order_itemmeta (order_item_id, meta_key, meta_value) VALUES ('$new_item_id', '" . $get_old_data_row['meta_key'] . "', '" . $get_old_data_row['meta_value'] . "')";
			mysqli_query($new_conn, $sql);
		}
    }

}
