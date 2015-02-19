<?php
/**
 * Template Name: Debate Page
 *
 * 
 */

get_header();
?>
<?php while ( have_posts() ) : the_post(); ?>

	<?php 
//get_template_part( 'content', get_post_format() ); 
$post = get_post(get_the_ID()); 
?>
<article id="post-6" class="post-6 debate type-debate status-publish hentry">
	<header class="entry-header">
		<h1 class="entry-title"><?php echo $post->post_title; ?></h1>	</header>



	<div class="entry-content">

<p><?php echo $post->post_content;?></p>

	</div><!-- .entry-content -->

	



<?php
if (isset($_REQUEST['vote'])){

if ($_REQUEST['vote']=='a'){
$vote=get_post_meta( get_the_ID(), 'votea', true )+1;
update_post_meta( get_the_ID(), "votea", $vote);
echo 'vota a';
}
else if ($_REQUEST['vote']=='b'){
$vote=get_post_meta( get_the_ID(), 'voteb', true )+1;
update_post_meta( get_the_ID(), "voteb", $vote);
echo 'vota b';
}
}
?>
<div style="position:relative; padding: 0 9%">
<div style="padding: 10px; float: left; width: 45%;">
<h3><?php echo get_post_meta( get_the_ID(), 'titlepa-text', true );?></h3>
<p><br><?php echo get_post_meta( get_the_ID(), 'textpa-text', true );?></p>
<p><br><?php 
$usera = get_userdata(get_post_meta( get_the_ID(), 'usera', true ));
echo $usera->user_login;
?></p>
<p><br><?php echo get_post_meta( get_the_ID(), 'votea', true );?>&nbsp;
<a href="?p=<?php the_ID(); ?>&vote=a">Vote A</a>
</p>
</div>

<div style="padding: 10px; float: right; width: 45%;">
<h3><?php echo get_post_meta( get_the_ID(), 'titlepb-text', true );?></h3>
<p><br><?php echo get_post_meta( get_the_ID(), 'textpb-text', true );?></p>
<p><br><?php 
$userb = get_userdata(get_post_meta( get_the_ID(), 'userb', true ));
echo $userb->user_login;
?></p>

<p><br><?php echo get_post_meta( get_the_ID(), 'voteb', true );?>&nbsp;
<a href="?p=<?php the_ID(); ?>&vote=b">Vote B</a>
</p>
</div>

<p>Duration:&nbsp;<?php echo get_post_meta( get_the_ID(), 'duration-select', true );?> days</p>
</div>



<?php comments_template( $file = plugin_path() . '/comments-debate.php', $separate_comments = false ); ?>


</article>

 <?php endwhile; // end of the loop. ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
