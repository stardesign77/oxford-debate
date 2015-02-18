<?php
/**
 *
 * Oxford Debates Wordpress
 * File: Settings
 *
 **/

if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (!class_exists("Oxd_Settings")) :

	class Oxd_Settings {

		function __construct() {
			
			add_action( 'add_meta_boxes', 'oxd_meta_box' );

		}
	}
		
	function oxd_meta_box()
	{
	    $args = array('test', array('some data', 'in here'), 3);
	    add_meta_box(
	        'moderator_box',
	        __('Debate Details', 'debate'),
	        'oxd_display_meta_box',
	        'debate',
	        'advanced',
	        'default'
	    );
	}

	function oxd_display_meta_box($post) {
		wp_nonce_field( basename( __FILE__ ), 'oxd_nonce' );
	    $prfx_stored_meta = get_post_meta( $post->ID );
	    ?>
	 
	    <p>
	        <label for="titlepa-text" class="oxd-row-title"><?php _e( 'Title Posture A', 'oxd-textdomain' )?></label>
	        <input type="text" name="titlepa-text" id="titlepa-text" value="<?php if ( isset ( $prfx_stored_meta['titlepa-text'] ) ) echo $prfx_stored_meta['titlepa-text'][0]; ?>" />
	    </p>
	    <p>
	        <label for="textpa-text" class="oxd-row-text"><?php _e( 'Text Posture A', 'oxd-textdomain' )?></label>
	        <textarea name="textpa-text" id="textpa-text"><?php if ( isset ( $prfx_stored_meta['textpa-text'] ) ) echo $prfx_stored_meta['textpa-text'][0]; ?></textarea>
	    </p>
	    <p>
	        <label for="titlepb-text" class="oxd-row-title"><?php _e( 'Title Posture B', 'oxd-textdomain' )?></label>
	        <input type="text" name="titlepb-text" id="titlepb-text" value="<?php if ( isset ( $prfx_stored_meta['titlepb-text'] ) ) echo $prfx_stored_meta['titlepb-text'][0]; ?>" />
	    </p>
	    <p>
	        <label for="textpb-text" class="oxd-row-text"><?php _e( 'Text Posture B', 'oxd-textdomain' )?></label>
	        <textarea name="textpb-text" id="textpb-text"><?php if ( isset ( $prfx_stored_meta['textpb-text'] ) ) echo $prfx_stored_meta['textpb-text'][0]; ?></textarea>
	    </p>
	    <p>
	    	<label for="duration-select" class="oxd-row-select"><?php _e( 'Duration (days)', 'oxd-textdomain' )?></label>
	        <input type="text" name="duration-select" id="duration-select" value="<?php if ( isset ( $prfx_stored_meta['duration-select'] ) ) echo $prfx_stored_meta['duration-select'][0]; ?>" />

	    </p>
	 
	    <?php
	    global $post;
		$custom = get_post_custom($post->ID);

		// prepare arguments
		$user_args  = array(
		// search only for Authors role
		'role' => 'Author',
		// order results by display_name
		'orderby' => 'display_name'
		);
		// Create the WP_User_Query object
		$wp_user_query = new WP_User_Query($user_args);
		// Get the results
		$authors = $wp_user_query->get_results();
		// Check for results
		if (!empty($authors))
		{
		    // Name is your custom field key
		    echo "Posture A user: <select name='usera'>";
		    // loop trough each author
		    foreach ($authors as $author)
		    {
		        // get all the user's data
		        $author_info = get_userdata($author->ID);
		        $author_id = get_post_meta($post->ID, 'usera', true);
		        if($author_id == $author_info->ID) { $author_selected = 'selected="selected"'; } else { $author_selected = ''; }
		        echo '<option value='.$author_info->ID.' '.$author_selected.'>'.$author_info->first_name.' '.$author_info->last_name.'</option>';
		    }
		    echo "</select>";

		    // USER B
		    // Name is your custom field key
		    echo "Posture B user: <select name='userb'>";
		    // loop trough each author
		    foreach ($authors as $author)
		    {
		        // get all the user's data
		        $author_info = get_userdata($author->ID);
		        $author_id = get_post_meta($post->ID, 'userb', true);
		        if($author_id == $author_info->ID) { $author_selected = 'selected="selected"'; } else { $author_selected = ''; }
		        echo '<option value='.$author_info->ID.' '.$author_selected.'>'.$author_info->first_name.' '.$author_info->last_name.'</option>';
		    }
		    echo "</select>";
		} else {
		    echo 'No authors found';
		}
		?>
		<p>
			<label for="votea" class="oxd-row-select"><?php _e( 'Votes A', 'oxd-textdomain' )?></label>
	        <input type="text" name="votea" id="votea" value="<?php if ( isset ( $prfx_stored_meta['votea'] ) ) echo $prfx_stored_meta['votea'][0]; ?>" />
	        <label for="voteb" class="oxd-row-select"><?php _e( 'Votes B', 'oxd-textdomain' )?></label>
	        <input type="text" name="voteb" id="voteb" value="<?php if ( isset ( $prfx_stored_meta['voteb'] ) ) echo $prfx_stored_meta['voteb'][0]; ?>" />
		</p>
		<?php
	}


	function oxd_meta_save( $post_id ) {
 
	    // Checks save status
	    $is_autosave = wp_is_post_autosave( $post_id );
	    $is_revision = wp_is_post_revision( $post_id );
	    $is_valid_nonce = ( isset( $_POST[ 'oxd_nonce' ] ) && wp_verify_nonce( $_POST[ 'oxd_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
	 
	    // Exits script depending on save status
	    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
	        return;
	    }
	 
	    // Checks for input and sanitizes/saves if needed
	    if( isset( $_POST[ 'titlepa-text' ] ) ) {
	        update_post_meta( $post_id, 'titlepa-text', sanitize_text_field( $_POST[ 'titlepa-text' ] ) );
	        update_post_meta( $post_id, 'textpa-text', sanitize_text_field( $_POST[ 'textpa-text' ] ) );
	        update_post_meta( $post_id, 'titlepb-text', sanitize_text_field( $_POST[ 'titlepb-text' ] ) );
	        update_post_meta( $post_id, 'textpb-text', sanitize_text_field( $_POST[ 'textpb-text' ] ) );
	        update_post_meta( $post_id, 'duration-select', sanitize_text_field( $_POST[ 'duration-select' ] ) );
	        update_post_meta( $post_id, "usera", $_POST["usera"]);
	        update_post_meta( $post_id, "userb", $_POST["userb"]);
	        update_post_meta( $post_id, "votea", $_POST["votea"]);
	        update_post_meta( $post_id, "voteb", $_POST["voteb"]);
	    }
	 
	}
	add_action( 'save_post', 'oxd_meta_save' );

	
	




endif;
?>