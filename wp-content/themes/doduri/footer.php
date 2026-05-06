<?php
/**
 * 공통 푸터.
 *
 * @package Doduri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$info = doduri_site_info();
?>
<!-- ===== FOOTER ===== -->
<footer>
	<div class="footer-body">
		<div class="container">

			<!-- 로고 + 병원명 -->
			<div class="footer-identity">
				<img src="<?php echo esc_url( DODURI_THEME_URI . '/assets/images/logo/doduri-symbol.png' ); ?>" alt="<?php echo esc_attr( $info['name_ko'] ); ?>" class="footer-symbol" />
				<div>
					<p class="footer-name"><?php echo esc_html( $info['name_ko'] ); ?></p>
					<p class="footer-name-en"><?php echo esc_html( $info['name_en'] ); ?></p>
				</div>
			</div>

			<!-- 사업자 정보 -->
			<div class="footer-biz">
				<p>
					<?php
					printf(
						/* translators: 1: 대표자명, 2: 대표번호 */
						esc_html__( '대표자 : %1$s &nbsp;|&nbsp; 대표번호 : %2$s', 'doduri' ),
						esc_html( $info['ceo'] ),
						esc_html( $info['phone'] )
					);
					?>
				</p>
				<p><?php printf( esc_html__( '주소 : %s', 'doduri' ), esc_html( $info['address'] ) ); ?></p>
				<?php if ( ! empty( $info['biz_no'] ) ) : ?>
					<p><?php printf( esc_html__( '사업자등록번호 : %s', 'doduri' ), esc_html( $info['biz_no'] ) ); ?></p>
				<?php endif; ?>
			</div>

			<!-- 빠른 링크 -->
			<div class="footer-quick">
				<a href="<?php echo esc_url( $info['phone_link'] ); ?>"><?php esc_html_e( '전화상담', 'doduri' ); ?></a>
				<span>|</span>
				<?php
				$kakao_href = ! empty( $info['kakao'] ) ? $info['kakao'] : home_url( '/contact/' );
				$kakao_target = ! empty( $info['kakao'] ) ? ' target="_blank" rel="noopener"' : '';
				?>
				<a href="<?php echo esc_url( $kakao_href ); ?>"<?php echo $kakao_target; // phpcs:ignore ?>><?php esc_html_e( '카톡상담', 'doduri' ); ?></a>
				<span>|</span>
				<a href="<?php echo esc_url( $info['blog'] ); ?>" target="_blank" rel="noopener"><?php esc_html_e( '블로그', 'doduri' ); ?></a>
			</div>

		</div>
	</div>
	<div class="footer-bottom">
		<div class="container">
			<p>&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php echo esc_html( $info['name_ko'] ); ?>. <?php esc_html_e( 'All Rights Reserved.', 'doduri' ); ?></p>
		</div>
	</div>
</footer>

<button class="back-to-top" id="backToTop" aria-label="<?php esc_attr_e( '맨 위로', 'doduri' ); ?>">
	<i class="fas fa-chevron-up"></i>
</button>

<div class="floating-buttons">
	<a href="<?php echo esc_url( $info['phone_link'] ); ?>" class="float-btn" aria-label="<?php esc_attr_e( '전화상담', 'doduri' ); ?>">
		<i class="fas fa-phone"></i>
		<span><?php esc_html_e( '전화상담', 'doduri' ); ?></span>
	</a>
	<a href="<?php echo esc_url( $kakao_href ); ?>" class="float-btn" aria-label="<?php esc_attr_e( '카톡상담', 'doduri' ); ?>"<?php echo $kakao_target; // phpcs:ignore ?>>
		<i class="fas fa-comment"></i>
		<span><?php esc_html_e( '카톡상담', 'doduri' ); ?></span>
	</a>
	<a href="<?php echo esc_url( $info['blog'] ); ?>" class="float-btn" aria-label="<?php esc_attr_e( '블로그', 'doduri' ); ?>" target="_blank" rel="noopener">
		<i class="fas fa-blog"></i>
		<span><?php esc_html_e( '블로그', 'doduri' ); ?></span>
	</a>
</div>

<?php wp_footer(); ?>
</body>
</html>
