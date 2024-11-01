<?php get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php if ( have_posts() ) { ?>

			<header class="page-header">
				<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header>

			<?php
			//Show Calendar
			$calendar = new Spirit_Calendar();
			echo $calendar->show();

		// If no content, include the "No posts found" template.
		} else {
			get_template_part( 'template-parts/content', 'none' );
		}
		?>

		</main>
	</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
