<?php
/**
 * Template Name: 병원이야기 (community-story)
 * 슬러그 'story' 페이지에 적용.
 *
 * CPT `doduri_story` 글의 카드 그리드.
 * 카드 = 대표 이미지 + 제목, 클릭 시 외부 블로그(meta `_story_external_url`)로 이동.
 *
 * @package Doduri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

set_query_var(
	'doduri_sub_args',
	array(
		'bg'         => doduri_option( 'sub_community_bg', DODURI_THEME_URI . '/assets/images/facility/facility4.png' ),
		'title'      => __( '커뮤니티', 'doduri' ),
		'subtitle'   => __( '도두리동물병원의 일상과 소식', 'doduri' ),
		'crumbs'     => array(
			array(
				'label' => __( '커뮤니티', 'doduri' ),
				'url'   => home_url( '/story/' ),
			),
			array( 'label' => __( '병원이야기', 'doduri' ) ),
		),
		'tabs'       => array(
			array( 'key' => 'story',  'label' => __( '병원이야기', 'doduri' ), 'url' => home_url( '/story/' ) ),
			array( 'key' => 'notice', 'label' => __( '공지사항', 'doduri' ),   'url' => home_url( '/notice/' ) ),
			array( 'key' => 'qna',    'label' => __( 'Q&A', 'doduri' ),         'url' => home_url( '/qna/' ) ),
		),
		'active_tab' => 'story',
	)
);
get_template_part( 'template-parts/sub-page-header' );

$story_query = new WP_Query(
	array(
		'post_type'      => 'doduri_story',
		'posts_per_page' => 12,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);
?>

<main>
	<section class="section">
		<div class="container">
			<div class="section-header">
				<p class="section-tag"><?php esc_html_e( '병원이야기', 'doduri' ); ?></p>
			</div>

			<?php if ( $story_query->have_posts() ) : ?>
				<div class="story-grid">
					<?php
					while ( $story_query->have_posts() ) :
						$story_query->the_post();
						$external = get_post_meta( get_the_ID(), '_story_external_url', true );
						$has_link = ! empty( $external );
						$thumb    = get_the_post_thumbnail( get_the_ID(), 'large', array( 'class' => 'story-card-img' ) );
						?>
						<article class="story-card<?php echo $has_link ? ' has-link' : ''; ?>">
							<?php if ( $has_link ) : ?>
								<a href="<?php echo esc_url( $external ); ?>" class="story-card-link" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
									<div class="story-card-media">
										<?php
										if ( $thumb ) {
											echo $thumb; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										} else {
											?>
											<div class="story-card-placeholder"><i class="fas fa-image"></i></div>
											<?php
										}
										?>
									</div>
									<h3 class="story-card-title"><?php the_title(); ?></h3>
								</a>
							<?php else : ?>
								<div class="story-card-link">
									<div class="story-card-media">
										<?php
										if ( $thumb ) {
											echo $thumb; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										} else {
											?>
											<div class="story-card-placeholder"><i class="fas fa-image"></i></div>
											<?php
										}
										?>
									</div>
									<h3 class="story-card-title"><?php the_title(); ?></h3>
								</div>
							<?php endif; ?>
						</article>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			<?php else : ?>
				<p class="board-empty"><?php esc_html_e( '병원이야기를 준비 중입니다.', 'doduri' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
