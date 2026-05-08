<?php
/**
 * Template Name: Q&A (community-qna)
 * 슬러그 'qna' 페이지에 적용.
 *
 * 페이지 본문(에디터)에 KBoard Q&A 숏코드를 입력해 사용. — [kboard id="X"]
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
		'subtitle'   => __( '궁금한 점을 남겨주시면 답변해 드리겠습니다', 'doduri' ),
		'crumbs'     => array(
			array(
				'label' => __( '커뮤니티', 'doduri' ),
				'url'   => home_url( '/story/' ),
			),
			array( 'label' => __( 'Q&A', 'doduri' ) ),
		),
		'tabs'       => array(
			array( 'key' => 'story', 'label' => __( '병원이야기', 'doduri' ), 'url' => home_url( '/story/' ) ),
			array( 'key' => 'qna',   'label' => __( 'Q&A', 'doduri' ),        'url' => home_url( '/qna/' ) ),
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
						echo do_shortcode( get_the_content() );
					endwhile;
				else :
					echo '<p class="board-empty">' . esc_html__( 'Q&A 게시판을 준비 중입니다.', 'doduri' ) . '</p>';
				endif;
				?>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
