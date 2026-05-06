<?php
/**
 * 404 — 페이지를 찾을 수 없음.
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
		<h1><?php esc_html_e( '페이지를 찾을 수 없습니다', 'doduri' ); ?></h1>
		<p><?php esc_html_e( '요청하신 페이지가 존재하지 않거나 이동되었습니다.', 'doduri' ); ?></p>
	</div>
</div>

<main>
	<section class="section">
		<div class="container" style="text-align:center;">
			<p style="margin-bottom:24px;font-size:15px;color:#5a5149;">
				<?php esc_html_e( '주소를 다시 한 번 확인해 주세요. 문제가 계속되면 아래 연락처로 문의해 주세요.', 'doduri' ); ?>
			</p>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( '홈으로 돌아가기', 'doduri' ); ?></a>
		</div>
	</section>
</main>

<?php get_footer(); ?>
