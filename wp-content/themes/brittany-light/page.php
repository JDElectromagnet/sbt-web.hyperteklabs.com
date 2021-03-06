<?php get_header(); ?>

<main class="main">
	<div class="container">
		<div class="row">
			<div id="site-content" class="col-md-8">
				<?php while ( have_posts() ) : the_post(); ?>
					<article id="entry-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
						<h1 class="section-title">
							<?php the_title(); ?>
						</h1>

						<?php if ( has_post_thumbnail() ) : ?>
							<div class="entry-thumb">
								<a class="ci-lightbox" href="<?php echo esc_url( wp_get_attachment_image_url( get_post_thumbnail_id(), 'large' ) ); ?>">
									<?php the_post_thumbnail(); ?>
								</a>
							</div>
						<?php endif; ?>

						<div class="entry-content">
							<?php the_content(); ?>
							<?php wp_link_pages(); ?>
						</div>

						<?php comments_template(); ?>
					</article>
				<?php endwhile; ?>
			</div>

			<div class="col-md-4">
				<?php get_sidebar(); ?>
			</div>

			<?php get_template_part( 'part-prefooter' ); ?>
		</div>
	</div>
</main>

<?php get_footer();
