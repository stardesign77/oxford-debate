<?php
/**
 * Oxd displaying Comments
 *
 *
 */

if ( post_password_required() )
	return;
?>

<div id="comments" class="comments-area">

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php _e('Comments','oxd'); ?>
		</h2>

		<ol class="commentlist">
			<?php
				$arrcommA = array(
				'post_id' => get_the_ID()
				);
				$comments = get_comments($arrcommA);
			?>
			
			
			<?php wp_list_comments(); ?>

		
		</ol><!-- .commentlist -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="navigation" role="navigation">
			<h1 class="assistive-text section-heading"><?php _e( 'Comment navigation', 'oxd' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( _e( '&larr; Older Comments', 'oxd' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( _e( 'Newer Comments &rarr;', 'oxd' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

		<?php
		/* If there are no comments and comments are closed, let's leave a note.
		 * But we only want the note on posts and pages that had comments in the first place.
		 */
		if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="nocomments"><?php _e( 'Comments are closed.' , 'oxd' ); ?></p>
		<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php $comment_args = array( 'title_reply'=>'Got Something To Say:',

	'fields' => apply_filters( 'comment_form_default_fields', array(
	'author' => '<p class="comment-form-author">' . '<label for="author">' . _e( 'Your Good Name', 'oxd' ) . '</label> ' . ( $req ? '<span>*</span>' : '' ) .
        '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" /></p>',   
    'email'  => '<p class="comment-form-email">' .
                '<label for="email">' . _e( 'Your Email Please', 'oxd' ) . '</label> ' .
                ( $req ? '<span>*</span>' : '' ) .
                '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" />'.'</p>',
    'url'    => '' ) ),
    'comment_field' => 
		'<p>' . 
		'<label for="posture">'. _e('Posture', 'oxd') . '</label>' .
    	'<span class="required">*</span>' .
    	'<select name="posture"> ' .
   		'	<option value="a">A</option> ' .
  		'	<option value="b">B</option> ' .
  		'	<option value="nothing">'. _e('Other', 'oxd') . '</option> ' .
		'</select> </p>' .
		'<p>' .
        '<label for="comment">' . _e( 'Let us know what you have to say:', 'oxd' ) . '</label>' .
        '<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>' .
        '</p>',
    'comment_notes_after' => '',
);

comment_form($comment_args); 
?>

</div><!-- #comments .comments-area -->
