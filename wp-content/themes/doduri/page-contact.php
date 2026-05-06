<?php
/**
 * Template Name: 상담/예약 (contact)
 * 슬러그 'contact' 페이지에 적용.
 *
 * 게시판은 KBoard 플러그인 설치 후, 페이지 본문에 [kboard id="..."] 숏코드를
 * 삽입하면 자동으로 본문 출력 영역에 렌더된다.
 * 숏코드/콘텐츠가 비어 있으면 placeholder 가 표시된다.
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
		'bg'         => doduri_option( 'sub_contact_bg', 'https://images.unsplash.com/photo-1450778869180-41d0601e046e?w=1600&q=80' ),
		'title'      => __( '상담/예약', 'doduri' ),
		'subtitle'   => __( '도두리동물병원에 문의해 주세요', 'doduri' ),
		'crumbs'     => array(
			array( 'label' => __( '상담/예약', 'doduri' ) ),
		),
		'tabs'       => array(),
		'active_tab' => '',
	)
);
get_template_part( 'template-parts/sub-page-header' );
?>

<main>
	<section class="section">
		<div class="container">
			<div class="section-header">
				<p class="section-tag"><?php esc_html_e( '상담/예약', 'doduri' ); ?></p>
				<h2 class="section-title"><?php esc_html_e( '언제든지 문의해 주세요', 'doduri' ); ?></h2>
			</div>

			<?php
			$has_content = false;

			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					$body = trim( get_the_content() );
					if ( $body !== '' ) {
						$has_content = true;
						the_content();
					}
				endwhile;
			endif;

			if ( ! $has_content ) : ?>
				<div class="content-placeholder">
					<i class="fas fa-comments"></i>
					<p><?php esc_html_e( '상담/예약 게시판은 관리자 페이지에서 KBoard 플러그인 설치 후 [kboard id="..."] 숏코드로 추가됩니다.', 'doduri' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
