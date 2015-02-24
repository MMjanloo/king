<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Ultimate
 * @since Ultimate 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php ult_entry_top(); ?>

	<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
		<div class="featured-post">
			<h2><?php _e( 'Featured Post', 'ultimate' ); ?></h2>
		</div>
	<?php endif; ?>

	<header class="entry-header">

		<?php if ( has_post_thumbnail() || ultimate_post_social() ) : ?>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="blog-featured-media">
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_post_thumbnail('full'); ?></a>
				</div>
			<?php elseif ( ultimate_post_social() && !is_single() ) : ?>
				<?php echo ultimate_post_social(); ?>
			<?php endif; ?>

		<?php endif; ?>

        <?php if( !is_single() ) : ?>
        	<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
        <?php else : ?>        	
        	<h1 class="entry-title"><?php the_title(); ?></h1>
        <?php endif; ?>
	</header><!-- .entry-header -->

	<?php if ( !is_single() ) : ?>
		<?php if ( !ultimate_post_social() ) : ?>
	        <div class="entry-summary">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->
		<?php endif; ?>
	<?php else : ?>
		<div class="entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'ultimate' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'ultimate' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
	<?php endif; ?>		

<?php ult_entry_bottom(); ?>
</article><!-- #post -->