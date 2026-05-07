<?php
/**
 * Template Name: 치료 케이스 (community-cases)
 * 슬러그 'cases' 페이지에 적용.
 *
 * 본문에 KBoard 숏코드를 입력해 사용한다. — [kboard id="2"]
 * REQ-015 매핑 (사진+텍스트, 카테고리 분류).
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
		'bg'         => doduri_option( 'sub_community_bg', 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=1600&q=80' ),
		'title'      => __( '커뮤니티', 'doduri' ),
		'subtitle'   => __( '실제 진료·치료 사례를 공유합니다', 'doduri' ),
		'crumbs'     => array(
			array(
				'label' => __( '커뮤니티', 'doduri' ),
				'url'   => home_url( '/story/' ),
			),
			array( 'label' => __( '치료 케이스', 'doduri' ) ),
		),
		'tabs'       => array(
			array( 'key' => 'story', 'label' => __( '병원이야기', 'doduri' ), 'url' => home_url( '/story/' ) ),
			array( 'key' => 'cases', 'label' => __( '치료 케이스', 'doduri' ), 'url' => home_url( '/cases/' ) ),
			array( 'key' => 'faq',   'label' => __( 'FAQ', 'doduri' ),         'url' => home_url( '/faq/' ) ),
		),
		'active_tab' => 'cases',
	)
);
get_template_part( 'template-parts/sub-page-header' );
?>

<main>
	<section class="section">
		<div class="container">
			<div class="board-wrap">
				<?php
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post();
						the_content();
					endwhile;
				else :
					echo '<p class="board-empty">' . esc_html__( '게시판을 준비 중입니다.', 'doduri' ) . '</p>';
				endif;
				?>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
