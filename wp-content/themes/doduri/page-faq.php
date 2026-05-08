<?php
/**
 * Template Name: FAQ (community-faq)
 * 슬러그 'faq' 페이지에 적용.
 *
 * REQ-016 매핑 — 아코디언 형태, 카테고리별 분류.
 *
 * 사용법:
 *   페이지 본문 (워드프레스 에디터)에 다음 형식으로 작성:
 *
 *   <h3>카테고리명</h3>           ← 카테고리 헤더 (예: 진료 안내)
 *   <h4>질문 1</h4>                ← 질문 (h4 가 토글 타이틀)
 *   <p>답변 본문...</p>            ← 답변 (h4 다음 형제 요소들이 펼치기 영역)
 *   <h4>질문 2</h4>
 *   <p>답변 본문...</p>
 *   <h3>다른 카테고리</h3>
 *   <h4>질문...</h4>
 *
 * 본문이 비어있으면 안내 메시지가 표시됨.
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
		'subtitle'   => __( '자주 묻는 질문을 모았습니다', 'doduri' ),
		'crumbs'     => array(
			array(
				'label' => __( '커뮤니티', 'doduri' ),
				'url'   => home_url( '/story/' ),
			),
			array( 'label' => __( 'FAQ', 'doduri' ) ),
		),
		'tabs'       => array(
			array( 'key' => 'story', 'label' => __( '병원이야기', 'doduri' ), 'url' => home_url( '/story/' ) ),
			array( 'key' => 'faq',   'label' => __( 'FAQ', 'doduri' ),         'url' => home_url( '/faq/' ) ),
		),
		'active_tab' => 'faq',
	)
);
get_template_part( 'template-parts/sub-page-header' );
?>

<main>
	<section class="section">
		<div class="container">
			<div class="faq-wrap">
				<?php
				$has_content = false;
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post();
						$content = trim( get_the_content() );
						if ( $content !== '' ) {
							$has_content = true;
							echo '<div class="faq-accordion">';
							the_content();
							echo '</div>';
						}
					endwhile;
				endif;
				if ( ! $has_content ) :
					?>
					<p class="board-empty"><?php esc_html_e( '자주 묻는 질문을 준비 중입니다.', 'doduri' ); ?></p>
					<?php
				endif;
				?>
			</div>
		</div>
	</section>
</main>

<script>
(function () {
	function init() {
		var roots = document.querySelectorAll('.faq-accordion');
		roots.forEach(function (root) {
			// h4 를 클릭 가능한 토글로 변환. 그 다음 형제(다음 h3/h4 전까지)를 패널로 묶음.
			var nodes = Array.prototype.slice.call(root.children);
			var groups = [];
			var current = null;
			nodes.forEach(function (n) {
				if (n.tagName === 'H4') {
					current = { q: n, panel: [] };
					groups.push(current);
				} else if (current && n.tagName !== 'H3') {
					current.panel.push(n);
				} else if (n.tagName === 'H3') {
					current = null; // 카테고리 헤더는 그대로 둠
				}
			});
			groups.forEach(function (g, i) {
				g.q.classList.add('faq-q');
				g.q.setAttribute('role', 'button');
				g.q.setAttribute('tabindex', '0');
				g.q.setAttribute('aria-expanded', 'false');
				var panel = document.createElement('div');
				panel.className = 'faq-a';
				panel.hidden = true;
				g.panel.forEach(function (el) { panel.appendChild(el); });
				g.q.parentNode.insertBefore(panel, g.q.nextSibling);

				function toggle() {
					var open = panel.hidden;
					panel.hidden = !open;
					g.q.setAttribute('aria-expanded', open ? 'true' : 'false');
					g.q.classList.toggle('open', open);
				}
				g.q.addEventListener('click', toggle);
				g.q.addEventListener('keydown', function (e) {
					if (e.key === 'Enter' || e.key === ' ') {
						e.preventDefault();
						toggle();
					}
				});
			});
		});
	}
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
</script>

<?php get_footer(); ?>
