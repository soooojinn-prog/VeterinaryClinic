<?php
/**
 * 인덱스 폴백 (블로그 인덱스, 검색 등 specific 템플릿 없을 때).
 *
 * @package Doduri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="sub-page-header">
	<div class="sub-page-header-bg" style="background-image: url('https://images.unsplash.com/photo-1450778869180-41d0601e046e?w=1600&q=80')"></div>
	<div class="sub-page-header-overlay"></div>
	<div class="container sub-page-header-content">
		<h1>
			<?php
			if ( is_search() ) {
				printf( esc_html__( '검색 결과: %s', 'doduri' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
			} elseif ( is_archive() ) {
				the_archive_title();
			} else {
				esc_html_e( '게시물', 'doduri' );
			}
			?>
		</h1>
	</div>
</div>

<main>
	<section class="section">
		<div class="container">
			<?php if ( have_posts() ) : ?>
				<ul class="post-list">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<li>
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<div class="post-meta"><?php echo esc_html( get_the_date() ); ?></div>
							<div class="post-excerpt"><?php the_excerpt(); ?></div>
						</li>
					<?php endwhile; ?>
				</ul>

				<div class="pagination">
					<?php
					the_posts_pagination(
						array(
							'prev_text' => '<i class="fas fa-chevron-left"></i>',
							'next_text' => '<i class="fas fa-chevron-right"></i>',
						)
					);
					?>
				</div>
			<?php else : ?>
				<p><?php esc_html_e( '게시물이 없습니다.', 'doduri' ); ?></p>
				<?php get_search_form(); ?>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
