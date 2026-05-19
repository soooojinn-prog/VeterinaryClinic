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

			<!-- 좌: 로고 + 병원명 + 사업자 정보 -->
			<div class="footer-left">

				<div class="footer-identity">
					<img src="<?php echo esc_url( DODURI_THEME_URI . '/assets/images/logo/doduri-symbol.png' ); ?>" alt="<?php echo esc_attr( $info['name_ko'] ); ?>" class="footer-symbol" />
					<div>
						<p class="footer-name"><?php echo esc_html( $info['name_ko'] ); ?></p>
						<p class="footer-name-en"><?php echo esc_html( $info['name_en'] ); ?></p>
					</div>
				</div>

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

			</div>

			<!-- 우: 진료비 안내 + 빠른 링크 -->
			<div class="footer-right">

				<?php
				$fee_img  = doduri_fee_guide_image_url();
				$fee_img2 = doduri_fee_guide_image_url_2();
				?>
				<div class="footer-fee-guide">
					<button class="fee-guide-btn" id="feeGuideBtn"
						<?php if ( $fee_img ) : ?>data-img="<?php echo esc_attr( $fee_img ); ?>"<?php endif; ?>
						<?php if ( $fee_img2 ) : ?>data-img2="<?php echo esc_attr( $fee_img2 ); ?>"<?php endif; ?>
					>[ 진료비 안내 ]</button>
				</div>

				<div class="footer-quick">
					<a href="<?php echo esc_url( $info['phone_link'] ); ?>"><?php esc_html_e( '전화상담', 'doduri' ); ?></a>
					<span>|</span>
					<?php
					$kakao_href   = ! empty( $info['kakao'] ) ? $info['kakao'] : 'http://pf.kakao.com/_lwlTX';
					$kakao_target = ' target="_blank" rel="noopener"';
					?>
					<a href="<?php echo esc_url( $kakao_href ); ?>"<?php echo $kakao_target; // phpcs:ignore ?>><?php esc_html_e( '카톡상담', 'doduri' ); ?></a>
					<span>|</span>
					<a href="<?php echo esc_url( $info['blog'] ); ?>" target="_blank" rel="noopener"><?php esc_html_e( '블로그', 'doduri' ); ?></a>
				</div>

			</div>

			<!-- 진료비 안내 모달 -->
			<div class="fee-guide-modal" id="feeGuideModal" hidden>
				<div class="fee-guide-modal-inner">
					<button class="fee-guide-close" id="feeGuideClose" aria-label="닫기">&times;</button>
					<div class="fee-guide-scroll">
						<img src="" alt="진료비 안내 1" id="feeGuideImg" />
						<img src="" alt="진료비 안내 2" id="feeGuideImg2" hidden />
					</div>
				</div>
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

<?php get_template_part( 'template-parts/floating-buttons' ); ?>

<?php wp_footer(); ?>
</body>
</html>
