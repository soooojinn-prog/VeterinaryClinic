<?php
/**
 * Template Name: 병원이야기 (community-story)
 * 슬러그 'story' 페이지에 적용.
 *
 * 본문에 KBoard 숏코드를 입력해 사용한다. — [kboard id="1"]
 * REQ-014 매핑.
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
		'subtitle'   => __( '도두리동물병원의 이야기와 치료 사례', 'doduri' ),
		'crumbs'     => array(
			array(
				'label' => __( '커뮤니티', 'doduri' ),
				'url'   => home_url( '/story/' ),
			),
			array( 'label' => __( '병원이야기', 'doduri' ) ),
		),
		'tabs'       => array(
			array( 'key' => 'story', 'label' => __( '병원이야기', 'doduri' ), 'url' => home_url( '/story/' ) ),
			array( 'key' => 'qna',   'label' => __( 'Q&A', 'doduri' ),          'url' => home_url( '/qna/' ) ),
		),
		'active_tab' => 'story',
	)
);
get_template_part( 'template-parts/sub-page-header' );
?>

<main>
	<section class="section">
		<div class="container">
			<div class="section-header">
				<p class="section-tag"><?php esc_html_e( '병원이야기', 'doduri' ); ?></p>
			</div>
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
