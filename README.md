Here you will find PHP snippets, CSS and other tips and trics for WordPress.
It's a combination of snippets I found myself and adjusted a bit to my needs and stuff that I just copied.

For placing the snippets you can use the following plugin: https://nl.wordpress.org/plugins/code-snippets/ or simply putting them in the functions.php of your child! theme.

To add snippets to the head of your pages use:

add_action( 'wp_head', function () { ?>

	<!-- header code goes here -->

<?php } );


To add snippets to the footer of your pages use:

add_action( 'wp_footer', function () { ?>

	<!-- footer code goes here -->

<?php } );

Have fun :-)
