<?php
/**
 * CPT — doduri_story (병원이야기 카드 게시글).
 *
 * 각 글은 대표 이미지 + 제목 + 외부 블로그 URL(meta `_story_external_url`) 만 보유.
 * page-story.php 에서 WP_Query 로 카드 그리드 출력. 카드 클릭 시 외부 블로그로 이동.
 *
 * @package Doduri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function doduri_register_cpt_story() {
	$labels = array(
		'name'               => __( '병원이야기', 'doduri' ),
		'singular_name'      => __( '병원이야기', 'doduri' ),
		'menu_name'          => __( '병원이야기', 'doduri' ),
		'add_new'            => __( '새 글 추가', 'doduri' ),
		'add_new_item'       => __( '새 병원이야기 글', 'doduri' ),
		'edit_item'          => __( '병원이야기 글 편집', 'doduri' ),
		'new_item'           => __( '새 병원이야기 글', 'doduri' ),
		'view_item'          => __( '병원이야기 글 보기', 'doduri' ),
		'search_items'       => __( '병원이야기 검색', 'doduri' ),
		'not_found'          => __( '글이 없습니다.', 'doduri' ),
		'not_found_in_trash' => __( '휴지통에 글이 없습니다.', 'doduri' ),
	);

	register_post_type(
		'doduri_story',
		array(
			'labels'              => $labels,
			'public'              => true,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-format-aside',
			'has_archive'         => false,
			'rewrite'             => false,
			'exclude_from_search' => true,
			'supports'            => array( 'title', 'thumbnail' ),
			'show_in_rest'        => false,
		)
	);
}
add_action( 'init', 'doduri_register_cpt_story' );

/**
 * 외부 URL 메타박스 — 대표 이미지 클릭 시 이동할 네이버 블로그(또는 외부) URL.
 */
function doduri_story_add_meta_box() {
	add_meta_box(
		'doduri_story_external_url',
		__( '외부 블로그 URL', 'doduri' ),
		'doduri_story_render_meta_box',
		'doduri_story',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'doduri_story_add_meta_box' );

function doduri_story_render_meta_box( $post ) {
	$value = get_post_meta( $post->ID, '_story_external_url', true );
	wp_nonce_field( 'doduri_story_meta', 'doduri_story_meta_nonce' );
	?>
	<p>
		<label for="doduri_story_external_url"><?php esc_html_e( '카드 클릭 시 이동할 URL', 'doduri' ); ?></label>
	</p>
	<input
		type="url"
		id="doduri_story_external_url"
		name="doduri_story_external_url"
		value="<?php echo esc_attr( $value ); ?>"
		placeholder="https://blog.naver.com/doduri_ah/..."
		style="width:100%;"
	/>
	<p class="description"><?php esc_html_e( '비워두면 카드는 클릭되지 않습니다.', 'doduri' ); ?></p>
	<?php
}

function doduri_story_save_meta( $post_id ) {
	if ( ! isset( $_POST['doduri_story_meta_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['doduri_story_meta_nonce'] ) ), 'doduri_story_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['doduri_story_external_url'] ) ) {
		$url = esc_url_raw( wp_unslash( $_POST['doduri_story_external_url'] ) );
		if ( $url ) {
			update_post_meta( $post_id, '_story_external_url', $url );
		} else {
			delete_post_meta( $post_id, '_story_external_url' );
		}
	}
}
add_action( 'save_post_doduri_story', 'doduri_story_save_meta' );
