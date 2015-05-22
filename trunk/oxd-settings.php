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
			
			add_action( 'add_meta_boxes', array($this,'oxd_meta_box') );
			add_action( 'admin_print_styles', array($this,'register_admin_styles') );
			add_action( 'admin_enqueue_scripts', array($this,'register_admin_scripts') );
			add_action( 'save_post', array($this,'oxd_meta_save') );
			
		}
	

		function register_admin_styles() {
		
			wp_enqueue_style( 'jquery-ui-datepicker', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css' );
			//wp_enqueue_style( 'wp-jquery-date-picker', plugins_url( '/oxd/css/admin.css' ) );	
			
		}

		function register_admin_scripts() {
		
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'wp-jquery-date-picker', plugins_url( '/oxford-debate/js/admin.js' ) );
			
		} 
			
		function oxd_meta_box()
		{
		    $args = array('test', array('some data', 'in here'), 3);
		    add_meta_box(
		        'moderator_box',
		        __('Debate Details', 'debate'),
		        array($this,'oxd_display_meta_box'),
		        'debate',
		        'advanced',
		        'default'
		    );
		}

		function oxd_display_meta_box($post) {
			wp_nonce_field( basename( __FILE__ ), 'oxd_nonce' );
		    $prfx_stored_meta = get_post_meta( $post->ID );
		    ?>

		    <table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="titlepa-text"><?php _e( 'Title Posture A', 'oxd' )?></label>
					</th>
					<td>
				        <input type="text" name="titlepa-text" id="titlepa-text" style="width: 80%;" value="<?php if ( isset ( $prfx_stored_meta['titlepa-text'] ) ) echo $prfx_stored_meta['titlepa-text'][0]; ?>" />
						<p class="description"></p>
					</td>
				</tr>
				<tr>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="textpa-text"><?php _e( 'Text Posture A', 'oxd' )?></label>
					</th>
					<td>
				        <textarea name="textpa-text" id="textpa-text" style="width: 80%; height: 150px;"><?php if ( isset ( $prfx_stored_meta['textpa-text'] ) ) echo $prfx_stored_meta['textpa-text'][0]; ?></textarea>
					<p class="description"></p>
					</td>
				</tr>
				<tr>	
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="titlepb-text"><?php _e( 'Title Posture B', 'oxd' )?></label>
					</th>
					<td>
						<input type="text" name="titlepb-text" id="titlepb-text" style="width: 80%;" value="<?php if ( isset ( $prfx_stored_meta['titlepb-text'] ) ) echo $prfx_stored_meta['titlepb-text'][0]; ?>" />
					<p class="description"></p>
					</td>
				</tr>
				<tr>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="textpb-text"><?php _e( 'Text Posture B', 'oxd' )?></label>
					</th>
					<td>
		        		<textarea name="textpb-text" id="textpb-text" style="width: 80%; height: 150px;"><?php if ( isset ( $prfx_stored_meta['textpb-text'] ) ) echo $prfx_stored_meta['textpb-text'][0]; ?></textarea>
					<p class="description"></p>
					</td>
				</tr>
				<tr>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="duration-check"><?php _e( 'Permanent', 'oxd' )?></label>
					</th>
					<td>
						<input type="checkbox" id="duration-check" name="initduration-text" value="<?php if ( isset ( $prfx_stored_meta['duration-check'] ) ) echo $prfx_stored_meta['duration-check'][0]; ?>" />
						<p class="description"></p>
					</td>
				</tr>
				<tr>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="initduration-text"><?php _e( 'Start Date (date format)', 'oxd' )?></label>
					</th>
					<td>
						<input type="text" id="initduration-text" name="initduration-text" value="<?php if ( isset ( $prfx_stored_meta['initduration-text'] ) ) echo $prfx_stored_meta['initduration-text'][0]; ?>" />
						<p class="description"></p>
					</td>
				</tr>
				<tr>
				</tr>

				<tr valign="top">
					<th scope="row">
						<label for="endduration-text"><?php _e( 'End Date (date format)', 'oxd' )?></label>
					</th>
					<td>
						<input type="text" id="endduration-text" name="endduration-text" value="<?php if ( isset ( $prfx_stored_meta['endduration-text'] ) ) echo $prfx_stored_meta['endduration-text'][0]; ?>" />
						<p class="description"></p>
					</td>
				</tr>
				<tr>
				</tr>

		    	<?php
		    	global $post;
				$custom = get_post_custom($post->ID);

				// prepare arguments
				$user_args  = array(
				// search only for Authors role
				//'role' => 'Author',
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

				?>

				<tr valign="top">
					<th scope="row">
						<label for="usera"><?php _e( 'Posture A user:', 'oxd' )?></label>
					</th>
					<td>
						<select name="usera">
						<?php
						// loop trough each author
			    		foreach ($authors as $author)
			    		{
			        		// get all the user's data
			        		$author_info = get_userdata($author->ID);
			        		$author_id = get_post_meta($post->ID, 'usera', true);
			        		if($author_id == $author_info->ID) { $author_selected = 'selected="selected"'; } else { $author_selected = ''; }
			        		echo '<option value='.$author_info->ID.' '.$author_selected.'>('.$author_info->nickname.') '.$author_info->first_name.' '.$author_info->last_name.'</option>';
			    		}
			    		echo "</select>";
			    		?>
						<p class="description"></p>
					</td>
				</tr>
				<tr>
				</tr>

				<tr valign="top">
					<th scope="row">
						<label for="userb"><?php _e( 'Posture B user:', 'oxd' )?></label>
					</th>
					<td>
						<select name="userb">
						<?php
						// loop trough each author
			    		foreach ($authors as $author)
			    		{
			        		// get all the user's data
			        		$author_info = get_userdata($author->ID);
			        		$author_id = get_post_meta($post->ID, 'userb', true);
			        		if($author_id == $author_info->ID) { $author_selected = 'selected="selected"'; } else { $author_selected = ''; }
			        		echo '<option value='.$author_info->ID.' '.$author_selected.'>('.$author_info->nickname.') '.$author_info->first_name.' '.$author_info->last_name.'</option>';
			    		}
			    		echo "</select>";
			    		?>
						<p class="description"></p>
					</td>
				</tr>
				<tr>
				</tr>

			    <?php
				} else {
			    	echo _e( 'No authors found', 'oxd' );
				}
				?>
				<tr valign="top">
					<th scope="row">
						<label for="votea"><?php _e( 'Votes A', 'oxd' )?></label>
					</th>
					<td>
						<input type="text" name="votea" id="votea" value="<?php if ( isset ( $prfx_stored_meta['votea'] ) ) echo $prfx_stored_meta['votea'][0]; else echo "0"?>" />
		        		<p class="description"></p>
					</td>
				</tr>
				<tr>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="votea"><?php _e( 'Votes B', 'oxd' )?></label>
					</th>
					<td>
						<input type="text" name="voteb" id="voteb" value="<?php if ( isset ( $prfx_stored_meta['voteb'] ) ) echo $prfx_stored_meta['voteb'][0]; else echo "0"?>" />
					</td>
				</tr>
				<tr>
				</tr>
			</tbody>
			</table>
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
		        update_post_meta( $post_id, 'duration-check', sanitize_text_field( $_POST[ 'duration-check' ] ) );
		        update_post_meta( $post_id, 'initduration-text', sanitize_text_field( $_POST[ 'initduration-text' ] ) );
		        update_post_meta( $post_id, 'endduration-text', sanitize_text_field( $_POST[ 'endduration-text' ] ) );
		        update_post_meta( $post_id, "usera", $_POST["usera"]);
		        update_post_meta( $post_id, "userb", $_POST["userb"]);
		        update_post_meta( $post_id, "votea", $_POST["votea"]);
		        update_post_meta( $post_id, "voteb", $_POST["voteb"]);
		    }
		 
		}
		

		
	}




endif;
?>