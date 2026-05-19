<?php
/**
 * 사이드 플로팅 버튼 (전화 / 카톡 채널 / 블로그) — 모든 페이지 공통.
 *
 * 스타일은 assets/css/style.css 의 ===== FLOATING BUTTONS ===== 블록 참조.
 *
 * @package Doduri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$info       = doduri_site_info();
$kakao_href = ! empty( $info['kakao'] ) ? $info['kakao'] : 'http://pf.kakao.com/_lwlTX';
?>
<div class="floating-buttons">
	<a href="<?php echo esc_url( $info['phone_link'] ); ?>" class="float-btn" aria-label="<?php esc_attr_e( '전화상담', 'doduri' ); ?>">
		<i class="fas fa-phone"></i>
		<span><?php esc_html_e( '전화', 'doduri' ); ?></span>
	</a>
	<a href="<?php echo esc_url( $kakao_href ); ?>" class="float-btn" aria-label="<?php esc_attr_e( '카톡 채널', 'doduri' ); ?>" target="_blank" rel="noopener">
		<i class="fas fa-comment"></i>
		<span><?php esc_html_e( '카톡', 'doduri' ); ?></span>
	</a>
	<a href="<?php echo esc_url( $info['blog'] ); ?>" class="float-btn" aria-label="<?php esc_attr_e( '블로그', 'doduri' ); ?>" target="_blank" rel="noopener">
		<i class="fas fa-blog"></i>
		<span><?php esc_html_e( '블로그', 'doduri' ); ?></span>
	</a>
	<?php if ( ! empty( $info['instagram'] ) ) : ?>
		<a href="<?php echo esc_url( $info['instagram'] ); ?>" class="float-btn" aria-label="<?php esc_attr_e( '인스타그램', 'doduri' ); ?>" target="_blank" rel="noopener">
			<i class="fab fa-instagram"></i>
			<span><?php esc_html_e( '인스타', 'doduri' ); ?></span>
		</a>
	<?php endif; ?>
</div>
