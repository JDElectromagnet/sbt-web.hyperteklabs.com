<?php
/**
 * The template for displaying the footer
 * Contains the closing of the body tag and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * @since Constrution Gravity 1.0.0
 */
?>	
		<?php if( !business_gravity_get_option( 'disable_footer_widget') ){
			?>
				<section class="wrapper block-top-footer">
					<div class="container">
						<div class="row">
						<?php 
							$count = 0;
							for( $i = 1; $i <= 4; $i++ ){
								?>
									<?php if ( is_active_sidebar( 'business-gravity-footer-sidebar-'.$i  ) ) : ?>
										<div class="col-lg-3 col-md-6 col-12 footer-widget-item">
										<?php dynamic_sidebar( 'business-gravity-footer-sidebar-'.$i  ); ?>
										</div>
									<?php endif; ?>
								<?php
							}
							if( $count > 0 ){
								$return = true;
							}else{
								$return = false;
							}
						?>
						</div>
					</div>
				</section>
			<?php
				}
			?>

		<footer class="wrapper site-footer" role="contentinfo">
			<div class="container">
				<div class="footer-inner">
					<div class="footer-menu">
						<?php business_gravity_get_menu( 'footer' ); ?>
					</div>
					<?php 
					if ( has_nav_menu( 'social' ) ) { ?>
						<div class="footer-social">
							<div class="socialgroup">
								<?php business_gravity_get_menu( 'social' ); ?>
							</div>
						</div>
					<?php } ?>
					<?php get_template_part('template-parts/footer/site', 'info'); ?>
				</div>
			</div>
		</footer>

		<?php wp_footer(); ?>
	</body>
</html>