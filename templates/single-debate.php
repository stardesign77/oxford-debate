<?php 
wp_register_style( 'oxd-bootstrap', plugins_url( '/oxford-debate/css/bootstrap.min.css' ) );
wp_enqueue_style( 'oxd-bootstrap' );
?>

<?php
if (isset($_REQUEST['vote'])){	
	$debateid = get_the_ID();
	if (array_key_exists('oxdvoted',$_COOKIE) && in_array($debateid, $_COOKIE['oxdvoted'])){
		// already voted
	}else{
		if ($_REQUEST['vote']=='a'){
			$vote=get_post_meta( $debateid, 'votea', true )+1;
			update_post_meta( $debateid, "votea", $vote);
		}
		else if ($_REQUEST['vote']=='b'){
			$vote=get_post_meta( $debateid, 'voteb', true )+1;
			update_post_meta( $debateid, "voteb", $vote);
		}
		setcookie('oxdvoted[$debateid]',$debateid);
	}

}
?>
<?php
/**
 * Template Name: Debate Page
 *
 * 
 */

get_header();
?>
<?php while ( have_posts() ) : the_post(); ?>
<?php $post = get_post(get_the_ID());?>



<article id="post-<?php get_the_ID()?>" class="container">
	<header>
		<h1><?php echo $post->post_title; ?></h1>	
	</header>
	<p><?php echo $post->post_content;?></p>

	<section>
		<h2 style="display:none"><?php _e('Postures','oxd'); ?></h2>
		<!-- title -->
		<div class="row">
			<div class="col-xs-6">
				<h3><?php echo get_post_meta( get_the_ID(), 'titlepa-text', true );?></h3>
			</div>
			<div class="col-xs-6">
				<h3><?php echo get_post_meta( get_the_ID(), 'titlepb-text', true );?></h3>
			</div>
		</div>
		<!-- text -->
		<div class="row">
			<div class="col-xs-6">
				<p><?php echo get_post_meta( get_the_ID(), 'textpa-text', true );?></p>
				<p><?php 
				$usera = get_userdata(get_post_meta( get_the_ID(), 'usera', true ));
				echo $usera->user_login;
				?></p>
			</div>
			<div class="col-xs-6">
				<p><?php echo get_post_meta( get_the_ID(), 'textpb-text', true );?></p>
				<p><?php 
				$userb = get_userdata(get_post_meta( get_the_ID(), 'userb', true ));
				echo $userb->user_login;
				?></p>
			</div>
		</div>
		<!-- vote -->
		<div class="row">
			<div class="col-xs-6">
				<p><?php echo get_post_meta( get_the_ID(), 'votea', true );?>&nbsp;
				<a href="?p=<?php the_ID(); ?>&vote=a"><?php _e('Vote A','oxd'); ?></a></p>
			</div>
			<div class="col-xs-6">
				<p><?php echo get_post_meta( get_the_ID(), 'voteb', true );?>&nbsp;
				<a href="?p=<?php the_ID(); ?>&vote=b"><?php _e('Vote B','oxd'); ?></a></p>
			</div>
		</div>
		<!-- dutation -->
		<div class="row">
			<div class="col-xs-12">
				<p><?php _e('Duration:','oxd'); ?>&nbsp;<?php echo get_post_meta( get_the_ID(), 'duration-select', true );?>&nbsp;<?php _e('days','oxd'); ?></p>
			</div>
		</div>
	</section>


<?php comments_template( $file = plugin_path() . '/comments-debate.php', $separate_comments = false ); ?>


</article>

 <?php endwhile; // end of the loop. ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
