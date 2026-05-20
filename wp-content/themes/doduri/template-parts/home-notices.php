<?php
/**
 * 메인 페이지 — 공지사항 최신 미리보기 (최대 3건).
 *
 * KBoard 가 설치되어 있고 '도두리 공지사항'(또는 '공지사항') 게시판이 존재하면
 * KBoard 데이터를 우선 사용. 그렇지 않으면 일반 WP 게시글(category 'notice') fallback.
 *
 * @package Doduri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$notices = array();

// 1) KBoard 데이터 시도 — 게시판 이름 후보를 순서대로 매칭
if ( class_exists( 'KBContent' ) ) {
	global $wpdb;
	$boards_table  = $wpdb->prefix . 'kboard_board_setting';
	$content_table = $wpdb->prefix . 'kboard_board_content';

	$board_id          = 0;
	$board_candidates  = array( '도두리 공지사항', '공지사항' );
	foreach ( $board_candidates as $candidate ) {
		$board_id = (int) $wpdb->get_var(
			$wpdb->prepare( "SELECT uid FROM {$boards_table} WHERE board_name = %s LIMIT 1", $candidate )
		);
		if ( $board_id > 0 ) {
			break;
		}
	}

	if ( $board_id > 0 ) {
		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT uid, title FROM {$content_table} WHERE board_id = %d AND status = '' ORDER BY uid DESC LIMIT 3",
				$board_id
			)
		);
		foreach ( (array) $rows as $row ) {
			$notices[] = array(
				'title' => $row->title,
				'url'   => add_query_arg(
					array(
						'mod' => 'document',
						'uid' => (int) $row->uid,
					),
					home_url( '/notice/' )
				),
			);
		}
	}
}

// 2) Fallback — 일반 Post 중 카테고리 'notice'
if ( empty( $notices ) ) {
	$query = new WP_Query(
		array(
			'post_type'      => 'post',
			'posts_per_page' => 3,
			'category_name'  => 'notice',
			'no_found_rows'  => true,
		)
	);
	while ( $query->have_posts() ) {
		$query->the_post();
		$notices[] = array(
			'title' => get_the_title(),
			'url'   => get_permalink(),
		);
	}
	wp_reset_postdata();
}
?>
<section class="home-notices section">
	<div class="container">
		<div class="section-header">
			<p class="section-tag"><?php esc_html_e( '공지사항', 'doduri' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( '도두리 새소식', 'doduri' ); ?></h2>
		</div>

		<?php if ( ! empty( $notices ) ) : ?>
			<ul class="home-notice-list">
				<?php foreach ( $notices as $n ) : ?>
					<li>
						<a href="<?php echo esc_url( $n['url'] ); ?>">
							<i class="fas fa-bullhorn" aria-hidden="true"></i>
							<span class="hn-title"><?php echo esc_html( $n['title'] ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="home-notice-more">
				<a href="<?php echo esc_url( home_url( '/notice/' ) ); ?>" class="btn btn-outline btn-sm"><?php esc_html_e( '공지사항 전체보기', 'doduri' ); ?></a>
			</div>
		<?php else : ?>
			<p class="home-notice-empty"><?php esc_html_e( '등록된 공지가 아직 없습니다.', 'doduri' ); ?></p>
		<?php endif; ?>
	</div>
</section>
