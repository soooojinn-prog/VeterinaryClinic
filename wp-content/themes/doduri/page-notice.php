<?php
/**
 * Template Name: 공지사항 (community-notice)
 * 슬러그 'notice' 페이지에 적용.
 *
 * 본문에 KBoard 공지사항 숏코드를 입력해 사용. — [kboard id="X"]
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
		'subtitle'   => __( '도두리동물병원의 새로운 소식을 전합니다', 'doduri' ),
		'crumbs'     => array(
			array(
				'label' => __( '커뮤니티', 'doduri' ),
				'url'   => home_url( '/story/' ),
			),
			array( 'label' => __( '공지사항', 'doduri' ) ),
		),
		'tabs'       => array(
			array( 'key' => 'story',  'label' => __( '병원이야기', 'doduri' ), 'url' => home_url( '/story/' ) ),
			array( 'key' => 'notice', 'label' => __( '공지사항', 'doduri' ),   'url' => home_url( '/notice/' ) ),
			array( 'key' => 'qna',    'label' => __( 'Q&A', 'doduri' ),         'url' => home_url( '/qna/' ) ),
		),
		'active_tab' => 'notice',
	)
);
get_template_part( 'template-parts/sub-page-header' );
?>

<main>
	<section class="section">
		<div class="container">
			<div class="section-header">
				<p class="section-tag"><?php esc_html_e( '공지사항', 'doduri' ); ?></p>
			</div>
			<div class="board-wrap">
				<?php
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post();
						echo do_shortcode( get_the_content() );
					endwhile;
				else :
					echo '<p class="board-empty">' . esc_html__( '공지사항을 준비 중입니다.', 'doduri' ) . '</p>';
				endif;
				?>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
