<?php
/**
 * Template Name: Q&A (community-qna)
 * 슬러그 'qna' 페이지에 적용.
 *
 * 본문 콘텐츠에 KBoard 숏코드를 입력해 사용한다. — [kboard list="..."]
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
		'subtitle'   => __( '도두리동물병원의 공지사항과 Q&A', 'doduri' ),
		'crumbs'     => array(
			array(
				'label' => __( '커뮤니티', 'doduri' ),
				'url'   => home_url( '/notice/' ),
			),
			array( 'label' => __( 'Q&A', 'doduri' ) ),
		),
		'tabs'       => array(
			array( 'key' => 'notice', 'label' => __( '공지사항', 'doduri' ), 'url' => home_url( '/notice/' ) ),
			array( 'key' => 'qna',    'label' => __( 'Q&A', 'doduri' ),       'url' => home_url( '/qna/' ) ),
		),
		'active_tab' => 'qna',
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
