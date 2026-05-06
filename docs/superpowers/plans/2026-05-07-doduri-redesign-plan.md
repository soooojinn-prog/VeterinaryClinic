# 도두리동물병원 리디자인 v2 구현 계획

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** 정적 HTML 1:1 이식한 v1 WordPress 테마를 PDF (2026-05-04) 협의 기준 v2 구조(상담/예약 폐지 → 커뮤니티, 메인 슬라이드, KBoard 게시판, 푸터/플로팅 정비)로 재구성한다.

**Architecture:** 기존 WordPress 테마 `wp-content/themes/doduri/` 의 page-{slug}.php 템플릿 계층을 유지하면서, 새 페이지 템플릿(`page-notice.php`, `page-qna.php`) 추가, 메뉴/푸터/플로팅 버튼 재배치, 메인 페이지 히어로 슬라이드+공지 미리보기 보강. KBoard 플러그인이 게시판 기능을 담당하고 테마는 출력 컨테이너만 제공. ACF 옵션 페이지 헬퍼(`doduri_option`)를 통해 모든 콘텐츠는 fallback 기본값을 가진다.

**Tech Stack:** PHP 7.4+, WordPress 6.x, ACF Pro (선택), KBoard (무료), Naver Maps SDK (VPC), Font Awesome 6.5.0, vanilla JS.

**Spec:** `docs/superpowers/specs/2026-05-07-doduri-redesign-design.md`

---

## File Structure

### Created

| 경로 | 책임 |
|---|---|
| `wp-content/themes/doduri/page-notice.php` | 공지사항 페이지 템플릿. 서브헤더 + 커뮤니티 탭 + `the_content()` (KBoard 숏코드 출력 영역) |
| `wp-content/themes/doduri/page-qna.php` | Q&A 페이지 템플릿. 위와 동일 구조 |
| `wp-content/themes/doduri/template-parts/home-notices.php` | 메인 페이지 공지 최신 2~3건 미리보기 위젯 |
| `wp-content/themes/doduri/template-parts/floating-buttons.php` | 사이드 플로팅 버튼 (전화/카톡/블로그) — 푸터에서 include |
| `wp-content/themes/doduri/assets/css/floating-buttons.css` | 플로팅 버튼 스타일 (footer.php 인라인 영역 분리) |
| `wp-content/themes/doduri/assets/css/kboard-override.css` | KBoard 기본 스킨 도두리 톤(베이지/워머) 통일 |

### Modified

| 경로 | 변경 |
|---|---|
| `wp-content/themes/doduri/inc/menus.php` | 폴백 메뉴: 상담/예약 → 커뮤니티(공지사항/Q&A). 진료안내 하위에 진료시간 앵커 추가 |
| `wp-content/themes/doduri/inc/theme-setup.php` | `doduri_body_classes`: `contact` → `notice`/`qna` 슬러그 매핑 (`page-community` 클래스) |
| `wp-content/themes/doduri/inc/acf-options.php` | `doduri_site_info()` 의 `kakao` fallback 값 `http://pf.kakao.com/_lwlTX` 추가. `home_hero_slides` 헬퍼 추가 |
| `wp-content/themes/doduri/inc/enqueue.php` | `floating-buttons.css`, `kboard-override.css` enqueue. notice/qna 페이지에서만 KBoard CSS 로드 |
| `wp-content/themes/doduri/footer.php` | 인라인 `floating-buttons` 블록을 `get_template_part` 호출로 치환. `kakao_href` 폴백 정리 |
| `wp-content/themes/doduri/front-page.php` | 정적 hero 1장 → `home_hero_slides` 다중 슬라이드 + fade JS. 진료과목 미니카드 + home-notices 인클루드 + 진료시간/오시는길 요약 박스 추가 |
| `wp-content/themes/doduri/page-location.php` | 진료시간 섹션에 `id="hours"` 앵커. 카톡 채널 버튼 추가. `naver_map_url` fallback `https://naver.me/5nhPYsQU`로 갱신 |

### Deleted

| 경로 | 이유 |
|---|---|
| `wp-content/themes/doduri/page-contact.php` | 상담/예약 페이지 폐지. WP 페이지(`contact`)는 이미 휴지통 처리됨 |

### Manual (WordPress 관리자 작업)

- WP 페이지 신설: `공지사항` (slug `notice`) / `QnA` (slug `qna`) — 페이지 본문에 KBoard 숏코드 입력
- KBoard 플러그인 설치 + 게시판 2개 생성 (공지/Q&A) + 옵션 설정
- ACF 옵션 페이지에서 `site_biz_no = 409-26-52253` / `site_kakao_url = http://pf.kakao.com/_lwlTX` / `naver_map_url = https://naver.me/5nhPYsQU` / `home_hero_slides` 등록

---

## 사전 준비

이 계획을 실행하기 전:

1. **로컬 WordPress 환경 동작 확인** — http://doduri.local/ 접속 가능
2. **현재 git 상태 확인** — `wp-content/themes/doduri/` 가 워킹 디렉토리에 존재. 필요 시 `wp-content/`를 git add 하기 전에 `.gitignore` 정리 (이 계획은 별도 task #7 에 위임, 본 계획은 워킹 디렉토리에서 진행 후 commit 만 수행)
3. **PHP CLI 동작 확인** — `php --version` 가 7.4 이상

각 task 완료 시 commit 한다. 실패하면 진행 중지하고 사용자에게 보고.

**작업 디렉토리:** `C:\AISpace\Animal_Clinic`. 모든 상대 경로는 이 기준이다.

**Local Sites 동기화 (선택):** 사용자가 `C:\Users\804\Local Sites\doduri\app\public\wp-content\themes\doduri\` 를 별도 위치로 운영 중일 경우, 각 task 종료 후 변경된 파일을 동일 경로에 복사한다. (이 계획에서는 워킹 디렉토리만 다룬다.)

---

## Task 1: 메뉴 폴백 구조 — 커뮤니티 신설

**Files:**
- Modify: `wp-content/themes/doduri/inc/menus.php`

- [ ] **Step 1: 파일 현재 상태 확인**

```bash
php -l wp-content/themes/doduri/inc/menus.php
```

Expected: `No syntax errors detected`

- [ ] **Step 2: `doduri_render_primary_menu()` 폴백 변경**

`wp-content/themes/doduri/inc/menus.php` 라인 32~51 (폴백 `<ul class="nav-list">` 블록)을 다음으로 교체:

```php
	<ul class="nav-list">
		<li class="nav-item has-dropdown" data-page="about">
			<a href="<?php echo esc_url( home_url( '/greeting/' ) ); ?>"><?php esc_html_e( '병원소개', 'doduri' ); ?></a>
			<ul class="dropdown">
				<li><a href="<?php echo esc_url( home_url( '/greeting/' ) ); ?>"><?php esc_html_e( '인사말', 'doduri' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/doctor/' ) ); ?>"><?php esc_html_e( '의료진소개', 'doduri' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/facility/' ) ); ?>"><?php esc_html_e( '시설소개', 'doduri' ); ?></a></li>
			</ul>
		</li>
		<li class="nav-item has-dropdown" data-page="service">
			<a href="<?php echo esc_url( home_url( '/service-subject/' ) ); ?>"><?php esc_html_e( '진료안내', 'doduri' ); ?></a>
			<ul class="dropdown">
				<li><a href="<?php echo esc_url( home_url( '/service-subject/' ) ); ?>"><?php esc_html_e( '진료과목', 'doduri' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/location/#hours' ) ); ?>"><?php esc_html_e( '진료시간', 'doduri' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/location/' ) ); ?>"><?php esc_html_e( '오시는 길', 'doduri' ); ?></a></li>
			</ul>
		</li>
		<li class="nav-item has-dropdown" data-page="community">
			<a href="<?php echo esc_url( home_url( '/notice/' ) ); ?>"><?php esc_html_e( '커뮤니티', 'doduri' ); ?></a>
			<ul class="dropdown">
				<li><a href="<?php echo esc_url( home_url( '/notice/' ) ); ?>"><?php esc_html_e( '공지사항', 'doduri' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/qna/' ) ); ?>"><?php esc_html_e( 'Q&amp;A', 'doduri' ); ?></a></li>
			</ul>
		</li>
	</ul>
```

- [ ] **Step 3: `doduri_render_mobile_menu()` 폴백 변경**

라인 75~92 (폴백 `<ul>` 블록)을 다음으로 교체:

```php
	<ul>
		<li>
			<a href="<?php echo esc_url( home_url( '/greeting/' ) ); ?>" class="mobile-link mobile-parent"><?php esc_html_e( '병원소개', 'doduri' ); ?></a>
			<ul class="mobile-sub">
				<li><a href="<?php echo esc_url( home_url( '/greeting/' ) ); ?>" class="mobile-link"><?php esc_html_e( '인사말', 'doduri' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/doctor/' ) ); ?>" class="mobile-link"><?php esc_html_e( '의료진소개', 'doduri' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/facility/' ) ); ?>" class="mobile-link"><?php esc_html_e( '시설소개', 'doduri' ); ?></a></li>
			</ul>
		</li>
		<li>
			<a href="<?php echo esc_url( home_url( '/service-subject/' ) ); ?>" class="mobile-link mobile-parent"><?php esc_html_e( '진료안내', 'doduri' ); ?></a>
			<ul class="mobile-sub">
				<li><a href="<?php echo esc_url( home_url( '/service-subject/' ) ); ?>" class="mobile-link"><?php esc_html_e( '진료과목', 'doduri' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/location/#hours' ) ); ?>" class="mobile-link"><?php esc_html_e( '진료시간', 'doduri' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/location/' ) ); ?>" class="mobile-link"><?php esc_html_e( '오시는 길', 'doduri' ); ?></a></li>
			</ul>
		</li>
		<li>
			<a href="<?php echo esc_url( home_url( '/notice/' ) ); ?>" class="mobile-link mobile-parent"><?php esc_html_e( '커뮤니티', 'doduri' ); ?></a>
			<ul class="mobile-sub">
				<li><a href="<?php echo esc_url( home_url( '/notice/' ) ); ?>" class="mobile-link"><?php esc_html_e( '공지사항', 'doduri' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/qna/' ) ); ?>" class="mobile-link"><?php esc_html_e( 'Q&amp;A', 'doduri' ); ?></a></li>
			</ul>
		</li>
	</ul>
```

- [ ] **Step 4: PHP 문법 검증**

```bash
php -l wp-content/themes/doduri/inc/menus.php
```

Expected: `No syntax errors detected`

- [ ] **Step 5: 브라우저 확인**

http://doduri.local/ 새로고침. PC 헤더 메뉴: 병원소개 / 진료안내 / 커뮤니티. 진료안내 hover 시 `진료과목 / 진료시간 / 오시는 길` 3개. 커뮤니티 hover 시 `공지사항 / Q&A`. 햄버거 클릭 시 모바일 메뉴도 동일.

- [ ] **Step 6: Commit**

```bash
git add wp-content/themes/doduri/inc/menus.php
git commit -m "feat(menu): 폴백 메뉴를 커뮤니티 구조로 재배치"
```

---

## Task 2: body_class 그룹 갱신 — page-community

**Files:**
- Modify: `wp-content/themes/doduri/inc/theme-setup.php:61-81`

- [ ] **Step 1: `doduri_body_classes` 함수 교체**

`wp-content/themes/doduri/inc/theme-setup.php` 라인 61~81을 다음으로 교체:

```php
function doduri_body_classes( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'page-home';
		return $classes;
	}

	if ( is_page() ) {
		$slug = get_post_field( 'post_name', get_queried_object_id() );

		if ( in_array( $slug, array( 'greeting', 'doctor', 'facility' ), true ) ) {
			$classes[] = 'page-about';
		} elseif ( in_array( $slug, array( 'service-subject', 'location' ), true ) ) {
			$classes[] = 'page-service';
		} elseif ( in_array( $slug, array( 'notice', 'qna' ), true ) ) {
			$classes[] = 'page-community';
		}
	}

	return $classes;
}
add_filter( 'body_class', 'doduri_body_classes' );
```

- [ ] **Step 2: bootstrap.js 의 active 메뉴 매핑 확인**

`wp-content/themes/doduri/assets/js/bootstrap.js` 를 열어 `data-page` 매핑 코드를 확인. `page-contact` → `data-page="contact"` 매핑이 있으면 `page-community` → `data-page="community"` 로 변경하거나 추가한다.

(파일을 Read 한 뒤 적절한 라인을 찾아 Edit. body class 와 nav-item data-page 가 짝이 맞아야 한다.)

- [ ] **Step 3: PHP 문법 검증**

```bash
php -l wp-content/themes/doduri/inc/theme-setup.php
```

Expected: `No syntax errors detected`

- [ ] **Step 4: 브라우저 확인**

http://doduri.local/notice/ 접속. DOM 검사로 `<body>` 에 `page-community` 클래스 존재 확인. 헤더의 "커뮤니티" 메뉴 항목에 `active-menu` 클래스 적용되는지 확인 (bootstrap.js 처리 기준).

- [ ] **Step 5: Commit**

```bash
git add wp-content/themes/doduri/inc/theme-setup.php wp-content/themes/doduri/assets/js/bootstrap.js
git commit -m "feat(body-class): page-contact -> page-community 갱신"
```

---

## Task 3: page-contact.php 삭제

**Files:**
- Delete: `wp-content/themes/doduri/page-contact.php`

- [ ] **Step 1: 파일 삭제**

```bash
git rm wp-content/themes/doduri/page-contact.php
```

- [ ] **Step 2: 사용처 grep**

```bash
grep -rn "page-contact" wp-content/themes/doduri/ --include="*.php"
grep -rn "/contact/" wp-content/themes/doduri/ --include="*.php"
```

`/contact/` 참조가 남아있으면 검토. footer.php 의 `home_url( '/contact/' )` fallback 은 Task 4 에서 정리.

- [ ] **Step 3: 브라우저 확인**

http://doduri.local/contact/ 접속 시 404 반환되어야 함 (WP 페이지가 휴지통 처리됨 + 템플릿 삭제됨).

- [ ] **Step 4: Commit**

```bash
git commit -m "chore: page-contact.php 삭제 (상담/예약 폐지)"
```

---

## Task 4: 카카오 fallback URL 정리 + footer.php

**Files:**
- Modify: `wp-content/themes/doduri/inc/acf-options.php:64`
- Modify: `wp-content/themes/doduri/footer.php:50-54, 72-85`

- [ ] **Step 1: ACF 헬퍼의 kakao fallback 값 갱신**

`wp-content/themes/doduri/inc/acf-options.php` 의 64번 라인을 다음으로 교체:

```php
		'kakao'      => doduri_option( 'site_kakao_url', 'http://pf.kakao.com/_lwlTX' ),
```

(빈 문자열 fallback → 카카오 채널 URL fallback)

- [ ] **Step 2: footer.php 의 `kakao_href` 폴백 정리**

`wp-content/themes/doduri/footer.php` 의 50~54번 라인을 다음으로 교체:

```php
				<?php
				$kakao_href   = ! empty( $info['kakao'] ) ? $info['kakao'] : 'http://pf.kakao.com/_lwlTX';
				$kakao_target = ' target="_blank" rel="noopener"';
				?>
```

(이제 항상 외부 링크. `home_url('/contact/')` fallback 제거)

- [ ] **Step 3: 플로팅 버튼 블록 추출**

`wp-content/themes/doduri/footer.php` 의 72~85번 라인 (`<div class="floating-buttons">...</div>`) 전체를 다음으로 교체:

```php
<?php get_template_part( 'template-parts/floating-buttons' ); ?>
```

(실제 마크업은 Task 5 에서 신설할 partial 로 이동)

- [ ] **Step 4: PHP 문법 검증**

```bash
php -l wp-content/themes/doduri/inc/acf-options.php
php -l wp-content/themes/doduri/footer.php
```

Expected: 두 파일 모두 `No syntax errors detected`

- [ ] **Step 5: 임시 빈 partial 생성 (Task 5 전 깨짐 방지)**

```bash
mkdir -p wp-content/themes/doduri/template-parts
```

`wp-content/themes/doduri/template-parts/floating-buttons.php` 파일을 생성하고 다음 한 줄 입력:

```php
<?php // placeholder — Task 5 에서 채움 ?>
```

- [ ] **Step 6: 브라우저 확인**

http://doduri.local/ 새로고침. 푸터 빠른 링크에 카톡상담이 외부 카카오 채널 링크로 동작 (DevTools Network 또는 hover 로 확인). 플로팅 버튼은 일시적으로 사라진 상태 (Task 5 에서 복원).

- [ ] **Step 7: Commit**

```bash
git add wp-content/themes/doduri/inc/acf-options.php wp-content/themes/doduri/footer.php wp-content/themes/doduri/template-parts/floating-buttons.php
git commit -m "refactor(footer): 카카오 fallback URL 갱신 + 플로팅 버튼 partial 추출"
```

---

## Task 5: floating-buttons partial + CSS 분리

**Files:**
- Create: `wp-content/themes/doduri/template-parts/floating-buttons.php`
- Create: `wp-content/themes/doduri/assets/css/floating-buttons.css`
- Modify: `wp-content/themes/doduri/inc/enqueue.php`

- [ ] **Step 1: floating-buttons.php 작성 (placeholder 덮어쓰기)**

`wp-content/themes/doduri/template-parts/floating-buttons.php` 전체 내용을 다음으로 교체:

```php
<?php
/**
 * 사이드 플로팅 버튼 (전화 / 카톡 채널 / 블로그) — 모든 페이지 공통.
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
	<a href="<?php echo esc_url( $info['phone_link'] ); ?>" class="float-btn float-btn-phone" aria-label="<?php esc_attr_e( '전화상담', 'doduri' ); ?>">
		<i class="fas fa-phone"></i>
		<span><?php esc_html_e( '전화', 'doduri' ); ?></span>
	</a>
	<a href="<?php echo esc_url( $kakao_href ); ?>" class="float-btn float-btn-kakao" aria-label="<?php esc_attr_e( '카톡 채널', 'doduri' ); ?>" target="_blank" rel="noopener">
		<i class="fas fa-comment"></i>
		<span><?php esc_html_e( '카톡', 'doduri' ); ?></span>
	</a>
	<a href="<?php echo esc_url( $info['blog'] ); ?>" class="float-btn float-btn-blog" aria-label="<?php esc_attr_e( '블로그', 'doduri' ); ?>" target="_blank" rel="noopener">
		<i class="fas fa-blog"></i>
		<span><?php esc_html_e( '블로그', 'doduri' ); ?></span>
	</a>
</div>
```

- [ ] **Step 2: floating-buttons.css 작성**

`wp-content/themes/doduri/assets/css/floating-buttons.css` 파일을 생성하고 내용:

```css
/* 사이드 플로팅 버튼 — Task 5 분리 */
.floating-buttons {
	position: fixed;
	right: 20px;
	bottom: 80px;
	display: flex;
	flex-direction: column;
	gap: 10px;
	z-index: 90;
}

.floating-buttons .float-btn {
	width: 56px;
	height: 56px;
	border-radius: 50%;
	background: #fff;
	box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	color: #5a5149;
	text-decoration: none;
	font-size: 11px;
	font-weight: 700;
	gap: 2px;
	transition: transform 0.18s ease, box-shadow 0.18s ease;
}

.floating-buttons .float-btn i {
	font-size: 18px;
}

.floating-buttons .float-btn:hover {
	transform: translateY(-2px);
	box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

.floating-buttons .float-btn-phone i { color: #2c7d3f; }
.floating-buttons .float-btn-kakao i { color: #fae100; background: #3c1e1e; padding: 4px; border-radius: 4px; font-size: 12px; }
.floating-buttons .float-btn-blog i { color: #03c75a; }

@media (min-width: 901px) {
	.floating-buttons {
		right: 24px;
		bottom: auto;
		top: 50%;
		transform: translateY(-50%);
	}
}

@media (max-width: 900px) {
	.floating-buttons {
		right: 14px;
		bottom: 90px; /* 모바일 하단 탭바 위로 */
	}
	.floating-buttons .float-btn {
		width: 48px;
		height: 48px;
	}
	.floating-buttons .float-btn i {
		font-size: 16px;
	}
	.floating-buttons .float-btn span {
		display: none;
	}
}
```

- [ ] **Step 3: inc/enqueue.php 에 CSS enqueue**

`wp-content/themes/doduri/inc/enqueue.php` 의 `doduri_enqueue_assets()` 함수 안에서 `doduri-style-main` enqueue 직후 (라인 45 이후) 다음 블록 추가:

```php
	wp_enqueue_style(
		'doduri-floating-buttons',
		DODURI_THEME_URI . '/assets/css/floating-buttons.css',
		array( 'doduri-style-main' ),
		$ver
	);
```

또한 기존 메인 `style.css` 안에 정의된 `.floating-buttons` 또는 `.float-btn` 규칙이 있으면 충돌 방지를 위해 임시로 `assets/css/style.css` 에서 해당 블록을 grep 으로 확인:

```bash
grep -n "\.floating-buttons\|\.float-btn" wp-content/themes/doduri/assets/css/style.css
```

겹치는 규칙이 있으면 후 enqueue 된 floating-buttons.css 가 우선이 되도록 Step 4 의 순서를 따른다 (이미 `array('doduri-style-main')` 의존이라 OK).

- [ ] **Step 4: PHP 문법 검증**

```bash
php -l wp-content/themes/doduri/template-parts/floating-buttons.php
php -l wp-content/themes/doduri/inc/enqueue.php
```

Expected: 두 파일 모두 `No syntax errors detected`

- [ ] **Step 5: 브라우저 확인**

http://doduri.local/ 우측에 3개 플로팅 버튼 (전화/카톡/블로그) 노출. PC: 우측 중앙 세로 배치. 모바일(DevTools Toolbar 너비 ≤900px): 우측 하단, 라벨 숨김. 카톡 클릭 시 `pf.kakao.com/_lwlTX` 새 탭 오픈.

- [ ] **Step 6: Commit**

```bash
git add wp-content/themes/doduri/template-parts/floating-buttons.php wp-content/themes/doduri/assets/css/floating-buttons.css wp-content/themes/doduri/inc/enqueue.php
git commit -m "feat(floating): 플로팅 버튼 partial + 전용 CSS 분리"
```

---

## Task 6: ACF 옵션 — home_hero_slides + biz_no fallback

**Files:**
- Modify: `wp-content/themes/doduri/inc/acf-options.php`

- [ ] **Step 1: `doduri_site_info()` 의 `biz_no` fallback 갱신**

라인 65를 다음으로 교체:

```php
		'biz_no'     => doduri_option( 'site_biz_no', '409-26-52253' ),
```

(빈 문자열 → 사업자번호 fallback)

- [ ] **Step 2: 히어로 슬라이드 헬퍼 함수 추가**

`wp-content/themes/doduri/inc/acf-options.php` 파일 맨 끝에 (마지막 `}` 다음, 단, 마지막 `?>` 가 없는지 확인 후) 다음 함수 추가:

```php

/**
 * 메인 히어로 슬라이드 이미지 URL 배열 — fallback: 시설 사진 5장.
 *
 * @return array<string>
 */
function doduri_hero_slide_urls() {
	$slides = doduri_option( 'home_hero_slides', array() );

	$urls = array();
	if ( is_array( $slides ) && ! empty( $slides ) ) {
		foreach ( $slides as $item ) {
			if ( is_array( $item ) && ! empty( $item['url'] ) ) {
				$urls[] = $item['url'];
			} elseif ( is_string( $item ) && $item !== '' ) {
				$urls[] = $item;
			}
		}
	}

	if ( ! empty( $urls ) ) {
		return $urls;
	}

	// fallback: 시설 사진 5장
	$fallback = array();
	for ( $i = 1; $i <= 5; $i++ ) {
		$fallback[] = DODURI_THEME_URI . '/assets/images/facility/facility' . $i . '.png';
	}
	return $fallback;
}
```

- [ ] **Step 3: PHP 문법 검증**

```bash
php -l wp-content/themes/doduri/inc/acf-options.php
```

Expected: `No syntax errors detected`

- [ ] **Step 4: 푸터에서 사업자번호 확인**

http://doduri.local/ 푸터에 `사업자등록번호 : 409-26-52253` 표시 확인. (footer.php 라인 41~43 에 `! empty( $info['biz_no'] )` 조건이 이미 있음)

- [ ] **Step 5: Commit**

```bash
git add wp-content/themes/doduri/inc/acf-options.php
git commit -m "feat(acf): biz_no/hero-slides fallback 헬퍼 추가"
```

---

## Task 7: 메인 페이지 히어로 슬라이드

**Files:**
- Modify: `wp-content/themes/doduri/front-page.php`

- [ ] **Step 1: 슬라이드 초기화 데이터 추가**

`wp-content/themes/doduri/front-page.php` 의 14~24번 라인 (히어로 변수 블록) 다음에 추가:

```php
$hero_slides = doduri_hero_slide_urls(); // 항상 1개 이상 반환
```

- [ ] **Step 2: 히어로 마크업 교체**

라인 26~50 (`<section id="hero">...</section>`) 전체를 다음으로 교체:

```php
<!-- ===== HERO ===== -->
<section id="hero">
	<div class="hero-slides" data-interval="4500">
		<?php foreach ( $hero_slides as $idx => $img_url ) : ?>
			<div class="hero-slide<?php echo 0 === $idx ? ' active' : ''; ?>" data-index="<?php echo (int) $idx; ?>">
				<div class="hero-bg" style="background-image: url('<?php echo esc_url( $img_url ); ?>')"></div>
				<div class="hero-overlay"></div>
				<?php if ( 0 === $idx ) : ?>
					<div class="hero-content">
						<p class="hero-tag"><?php echo esc_html( $hero_tag ); ?></p>
						<h1>
							<?php echo esc_html( $hero_title_line1 ); ?><br>
							<em><?php echo esc_html( $hero_title_em ); ?></em>
						</h1>
						<p class="hero-desc"><?php echo nl2br( esc_html( $hero_desc ) ); ?></p>
						<div class="hero-btns">
							<a href="<?php echo esc_url( $hero_btn1_url ); ?>" class="btn btn-primary"><?php echo esc_html( $hero_btn1_label ); ?></a>
							<a href="<?php echo esc_url( $hero_btn2_url ); ?>" class="btn btn-outline"><?php echo esc_html( $hero_btn2_label ); ?></a>
						</div>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
	<?php if ( count( $hero_slides ) > 1 ) : ?>
		<div class="hero-dots">
			<?php foreach ( $hero_slides as $idx => $img_url ) : ?>
				<button type="button" class="hero-dot<?php echo 0 === $idx ? ' active' : ''; ?>" data-index="<?php echo (int) $idx; ?>" aria-label="슬라이드 <?php echo (int) ( $idx + 1 ); ?>"></button>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<div class="hero-scroll-hint">
		<span><?php esc_html_e( '스크롤', 'doduri' ); ?></span>
		<i class="fas fa-chevron-down"></i>
	</div>
</section>
```

- [ ] **Step 3: 메인 히어로 슬라이드 JS 추가**

`wp-content/themes/doduri/assets/js/script.js` 또는 `bootstrap.js` 안에서 hero 슬라이드 자동재생 로직 검색:

```bash
grep -n "hero-slide\|hero-slides" wp-content/themes/doduri/assets/js/*.js
```

기존 슬라이더 코드가 없으면, `assets/js/script.js` 맨 아래에 다음 추가:

```javascript
/* ===== Hero Slideshow (Task 7) ===== */
(function () {
	var root = document.querySelector('#hero .hero-slides');
	if (!root) return;
	var slides = root.querySelectorAll('.hero-slide');
	if (slides.length < 2) return;
	var dots = document.querySelectorAll('#hero .hero-dot');
	var interval = parseInt(root.dataset.interval || '4500', 10);
	var idx = 0;

	function show(next) {
		slides[idx].classList.remove('active');
		if (dots[idx]) dots[idx].classList.remove('active');
		idx = (next + slides.length) % slides.length;
		slides[idx].classList.add('active');
		if (dots[idx]) dots[idx].classList.add('active');
	}

	dots.forEach(function (d, i) {
		d.addEventListener('click', function () { show(i); });
	});

	setInterval(function () { show(idx + 1); }, interval);
})();
```

- [ ] **Step 4: 슬라이드 fade CSS 추가**

`wp-content/themes/doduri/assets/css/style.css` 에 기존 `.hero-slide` 정의가 있는지 확인:

```bash
grep -n "\.hero-slide" wp-content/themes/doduri/assets/css/style.css
```

기존 정의에 fade transition 이 없거나 슬라이드 다중 처리가 없으면 `style.css` 맨 아래에 추가:

```css
/* Hero slide fade transition (Task 7) */
#hero .hero-slides { position: relative; width: 100%; height: 100%; }
#hero .hero-slide { position: absolute; inset: 0; opacity: 0; transition: opacity 0.9s ease; pointer-events: none; }
#hero .hero-slide.active { opacity: 1; pointer-events: auto; }
#hero .hero-content { z-index: 2; }
#hero .hero-dots { position: absolute; bottom: 24px; left: 50%; transform: translateX(-50%); display: flex; gap: 8px; z-index: 5; }
#hero .hero-dot { width: 10px; height: 10px; border-radius: 50%; background: rgba(255,255,255,0.5); border: none; cursor: pointer; padding: 0; transition: background 0.2s; }
#hero .hero-dot.active { background: #fff; }
```

- [ ] **Step 5: PHP 문법 검증**

```bash
php -l wp-content/themes/doduri/front-page.php
```

Expected: `No syntax errors detected`

- [ ] **Step 6: 브라우저 확인**

http://doduri.local/ 새로고침. 4.5초마다 facility1.png ~ facility5.png 가 fade 전환. 첫 슬라이드에는 hero-content (태그/제목/버튼) 표시, 나머지 슬라이드는 배경만. 하단 도트 5개, 클릭 시 해당 슬라이드 전환.

- [ ] **Step 7: Commit**

```bash
git add wp-content/themes/doduri/front-page.php wp-content/themes/doduri/assets/js/script.js wp-content/themes/doduri/assets/css/style.css
git commit -m "feat(home): 히어로 정적 배경 → 다중 슬라이드 자동재생"
```

---

## Task 8: home-notices template-part (공지 미리보기)

**Files:**
- Create: `wp-content/themes/doduri/template-parts/home-notices.php`

KBoard 우선, 미설치 시 일반 WP Post fallback. KBoard 의 데이터 모델은 플러그인 활성화 후 확인되며, 활성화 전에는 일반 Post 가 출력된다.

- [ ] **Step 1: 파일 생성**

`wp-content/themes/doduri/template-parts/home-notices.php`:

```php
<?php
/**
 * 메인 페이지 — 공지사항 최신 미리보기 (2~3건).
 *
 * KBoard 가 설치되어 있고 게시판이 존재하면 KBoard 데이터를 우선 사용.
 * 그렇지 않으면 일반 WP 게시글(category 가 'notice' 또는 slug 'notice') fallback.
 *
 * @package Doduri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$notices = array();

// 1) KBoard 데이터 시도
if ( class_exists( 'KBContent' ) && function_exists( 'kboard_content_search' ) ) {
	global $wpdb;
	$boards_table  = $wpdb->prefix . 'kboard_board_setting';
	$content_table = $wpdb->prefix . 'kboard_board_content';
	$board_id      = (int) $wpdb->get_var(
		$wpdb->prepare( "SELECT uid FROM {$boards_table} WHERE board_name = %s LIMIT 1", '도두리 공지사항' )
	);
	if ( $board_id > 0 ) {
		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT uid, title, date FROM {$content_table} WHERE board_id = %d AND status='' ORDER BY uid DESC LIMIT 3",
				$board_id
			)
		);
		foreach ( (array) $rows as $row ) {
			$notices[] = array(
				'title' => $row->title,
				'date'  => mysql2date( 'Y.m.d', $row->date ),
				'url'   => add_query_arg( array( 'mod' => 'document', 'uid' => (int) $row->uid ), home_url( '/notice/' ) ),
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
			'date'  => get_the_date( 'Y.m.d' ),
			'url'   => get_permalink(),
		);
	}
	wp_reset_postdata();
}

// 3) 그래도 비면 placeholder 메시지
?>
<section class="home-notices section">
	<div class="container">
		<div class="section-header">
			<p class="section-tag"><?php esc_html_e( '공지사항', 'doduri' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( '병원 소식', 'doduri' ); ?></h2>
		</div>

		<?php if ( ! empty( $notices ) ) : ?>
			<ul class="home-notice-list">
				<?php foreach ( $notices as $n ) : ?>
					<li>
						<a href="<?php echo esc_url( $n['url'] ); ?>">
							<span class="hn-title"><?php echo esc_html( $n['title'] ); ?></span>
							<span class="hn-date"><?php echo esc_html( $n['date'] ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="home-notice-more">
				<a href="<?php echo esc_url( home_url( '/notice/' ) ); ?>" class="btn btn-outline btn-sm"><?php esc_html_e( '공지사항 전체보기', 'doduri' ); ?></a>
			</div>
		<?php else : ?>
			<p class="home-notice-empty"><?php esc_html_e( '등록된 공지사항이 아직 없습니다.', 'doduri' ); ?></p>
		<?php endif; ?>
	</div>
</section>
```

- [ ] **Step 2: 스타일 추가**

`wp-content/themes/doduri/assets/css/style.css` 맨 아래에 추가:

```css
/* Home notices (Task 8) */
.home-notices { background: #fff; }
.home-notice-list { list-style: none; padding: 0; margin: 24px 0 0; display: flex; flex-direction: column; gap: 1px; background: #ece6dc; border-radius: 12px; overflow: hidden; }
.home-notice-list li { background: #fff; }
.home-notice-list a { display: flex; justify-content: space-between; align-items: center; padding: 18px 22px; text-decoration: none; color: #2a2520; gap: 16px; transition: background 0.2s; }
.home-notice-list a:hover { background: #faf6ef; }
.home-notice-list .hn-title { font-size: 15px; font-weight: 600; flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.home-notice-list .hn-date { font-size: 13px; color: #8a7e72; flex-shrink: 0; }
.home-notice-more { margin-top: 18px; text-align: center; }
.home-notice-empty { text-align: center; color: #8a7e72; font-size: 14px; padding: 30px 0; }
```

- [ ] **Step 3: PHP 문법 검증**

```bash
php -l wp-content/themes/doduri/template-parts/home-notices.php
```

Expected: `No syntax errors detected`

- [ ] **Step 4: Commit**

```bash
git add wp-content/themes/doduri/template-parts/home-notices.php wp-content/themes/doduri/assets/css/style.css
git commit -m "feat(home): 공지 미리보기 partial 추가 (KBoard 우선, Post fallback)"
```

---

## Task 9: 메인 페이지 — 진료과목 미니카드 + 공지 + 요약 박스 통합

**Files:**
- Modify: `wp-content/themes/doduri/front-page.php`

- [ ] **Step 1: `<main>` 영역 교체**

`wp-content/themes/doduri/front-page.php` 의 `<main>...</main>` 블록 (Task 7 적용 후 라인 위치 변동 가능, `<!-- ===== MAIN CONTENT` 주석부터 `</main>` 까지) 전체를 다음으로 교체:

```php
<!-- ===== MAIN CONTENT ===== -->
<main>

	<!-- 진료과목 3개 클리닉 미니카드 -->
	<section class="home-clinics section">
		<div class="container">
			<div class="section-header">
				<p class="section-tag"><?php esc_html_e( '진료안내', 'doduri' ); ?></p>
				<h2 class="section-title"><?php esc_html_e( '도두리의 3개 클리닉', 'doduri' ); ?></h2>
			</div>
			<ul class="home-clinic-grid">
				<li>
					<i class="fas fa-stethoscope"></i>
					<h3><?php esc_html_e( '건강검진 클리닉', 'doduri' ); ?></h3>
					<p><?php esc_html_e( '체계적인 건강검진으로 질병을 조기에 발견합니다.', 'doduri' ); ?></p>
				</li>
				<li>
					<i class="fas fa-heartbeat"></i>
					<h3><?php esc_html_e( '내과 클리닉', 'doduri' ); ?></h3>
					<p><?php esc_html_e( '내과 질환의 정확한 진단과 맞춤 치료.', 'doduri' ); ?></p>
				</li>
				<li>
					<i class="fas fa-user-md"></i>
					<h3><?php esc_html_e( '순환기·시니어 클리닉', 'doduri' ); ?></h3>
					<p><?php esc_html_e( '심장 질환과 고령 동물 전문 진료.', 'doduri' ); ?></p>
				</li>
			</ul>
			<div class="home-clinic-more">
				<a href="<?php echo esc_url( home_url( '/service-subject/' ) ); ?>" class="btn btn-outline btn-sm"><?php esc_html_e( '진료과목 자세히 보기', 'doduri' ); ?></a>
			</div>
		</div>
	</section>

	<!-- 공지사항 미리보기 -->
	<?php get_template_part( 'template-parts/home-notices' ); ?>

	<!-- 진료시간 / 오시는길 요약 -->
	<?php
	$info_for_home   = doduri_site_info();
	$hours_open_h    = doduri_option( 'hours_open', '오전 10시 ~ 오후 7시' );
	$hours_break_h   = doduri_option( 'hours_break', '오후 1시 ~ 오후 2시' );
	$hours_closed_h  = doduri_option( 'hours_closed_notice', '※ 목요일, 일요일은 휴진입니다.' );
	$kakao_url_h     = ! empty( $info_for_home['kakao'] ) ? $info_for_home['kakao'] : 'http://pf.kakao.com/_lwlTX';
	$naver_url_h     = doduri_option( 'naver_map_url', 'https://naver.me/5nhPYsQU' );
	?>
	<section class="home-summary section">
		<div class="container">
			<div class="home-summary-grid">

				<div class="home-summary-card">
					<h3><i class="far fa-clock"></i> <?php esc_html_e( '진료시간', 'doduri' ); ?></h3>
					<dl>
						<dt><?php esc_html_e( '진료', 'doduri' ); ?></dt><dd><?php echo esc_html( $hours_open_h ); ?></dd>
						<dt><?php esc_html_e( '휴게', 'doduri' ); ?></dt><dd><?php echo esc_html( $hours_break_h ); ?></dd>
					</dl>
					<p class="home-summary-notice"><?php echo esc_html( $hours_closed_h ); ?></p>
				</div>

				<div class="home-summary-card">
					<h3><i class="fas fa-map-marker-alt"></i> <?php esc_html_e( '오시는 길', 'doduri' ); ?></h3>
					<p class="home-summary-addr"><?php echo esc_html( $info_for_home['address'] ); ?></p>
					<p class="home-summary-tel"><a href="<?php echo esc_url( $info_for_home['phone_link'] ); ?>"><?php echo esc_html( $info_for_home['phone'] ); ?></a></p>
					<div class="home-summary-btns">
						<a href="<?php echo esc_url( $naver_url_h ); ?>" target="_blank" rel="noopener" class="btn btn-sm"><?php esc_html_e( '네이버 지도', 'doduri' ); ?></a>
						<a href="<?php echo esc_url( $kakao_url_h ); ?>" target="_blank" rel="noopener" class="btn btn-sm"><?php esc_html_e( '카톡 채널', 'doduri' ); ?></a>
					</div>
				</div>

			</div>
		</div>
	</section>

	<?php
	// 관리자 본문 콘텐츠 (있을 때만)
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			$content = trim( get_the_content() );
			if ( $content !== '' ) {
				echo '<section class="section"><div class="container">';
				the_content();
				echo '</div></section>';
			}
		endwhile;
	endif;
	?>
</main>
```

- [ ] **Step 2: 스타일 추가**

`wp-content/themes/doduri/assets/css/style.css` 맨 아래에 추가:

```css
/* Home clinics + summary (Task 9) */
.home-clinic-grid { list-style: none; padding: 0; margin: 24px 0 0; display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
.home-clinic-grid li { background: #faf6ef; padding: 26px 22px; border-radius: 14px; text-align: center; }
.home-clinic-grid li i { font-size: 32px; color: #b08651; margin-bottom: 12px; display: block; }
.home-clinic-grid li h3 { font-size: 17px; font-weight: 700; color: #2a2520; margin: 0 0 8px; }
.home-clinic-grid li p { font-size: 13px; color: #5a5149; margin: 0; line-height: 1.6; }
.home-clinic-more { text-align: center; margin-top: 20px; }

.home-summary { background: #faf6ef; }
.home-summary-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.home-summary-card { background: #fff; padding: 28px; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); }
.home-summary-card h3 { font-size: 17px; font-weight: 700; margin: 0 0 16px; color: #2a2520; display: flex; align-items: center; gap: 8px; }
.home-summary-card h3 i { color: #b08651; }
.home-summary-card dl { display: grid; grid-template-columns: auto 1fr; column-gap: 18px; row-gap: 6px; margin: 0 0 12px; font-size: 14px; color: #5a5149; }
.home-summary-card dt { font-weight: 700; color: #2a2520; }
.home-summary-card dd { margin: 0; }
.home-summary-notice { font-size: 12px; color: #b08651; font-weight: 600; margin: 0; }
.home-summary-addr { font-size: 14px; color: #5a5149; margin: 0 0 6px; }
.home-summary-tel { font-size: 16px; font-weight: 700; margin: 0 0 14px; }
.home-summary-tel a { color: #2a2520; text-decoration: none; }
.home-summary-btns { display: flex; gap: 8px; }
.home-summary-btns .btn-sm { padding: 8px 14px; font-size: 13px; }

@media (max-width: 900px) {
	.home-clinic-grid { grid-template-columns: 1fr; }
	.home-summary-grid { grid-template-columns: 1fr; }
}
```

- [ ] **Step 3: PHP 문법 검증**

```bash
php -l wp-content/themes/doduri/front-page.php
```

Expected: `No syntax errors detected`

- [ ] **Step 4: 브라우저 확인**

http://doduri.local/ 에서 위→아래 순으로:
1. 히어로 슬라이드 (Task 7)
2. "도두리의 3개 클리닉" — 3개 카드
3. "공지사항" — placeholder ("등록된 공지사항이 아직 없습니다") 또는 KBoard 데이터
4. 진료시간 + 오시는길 카드 2열 (모바일은 세로)

각 섹션 반응형 동작 (DevTools 너비 변경) 확인.

- [ ] **Step 5: Commit**

```bash
git add wp-content/themes/doduri/front-page.php wp-content/themes/doduri/assets/css/style.css
git commit -m "feat(home): 진료과목 미니카드 + 공지 미리보기 + 요약 박스 통합"
```

---

## Task 10: page-location.php — 진료시간 앵커, 카톡 버튼, 지도 URL

**Files:**
- Modify: `wp-content/themes/doduri/page-location.php`

- [ ] **Step 1: 네이버 지도 fallback URL 갱신**

`wp-content/themes/doduri/page-location.php` 라인 52를 다음으로 교체:

```php
$naver_map_url = doduri_option( 'naver_map_url', 'https://naver.me/5nhPYsQU' );
```

- [ ] **Step 2: 카톡 채널 변수 추가**

라인 53 직후 (kakao_map_url 정의 다음) 에 다음 추가:

```php
$kakao_channel_url = ! empty( $info['kakao'] ) ? $info['kakao'] : 'http://pf.kakao.com/_lwlTX';
```

- [ ] **Step 3: 지도 앱 버튼에 카톡 채널 버튼 추가**

라인 120~127 (`<div class="location-map-btns">...</div>`) 를 다음으로 교체:

```php
					<div class="location-map-btns">
						<a href="<?php echo esc_url( $naver_map_url ); ?>" target="_blank" rel="noopener" class="map-btn naver">
							<?php esc_html_e( '네이버 지도', 'doduri' ); ?>
						</a>
						<a href="<?php echo esc_url( $kakao_map_url ); ?>" target="_blank" rel="noopener" class="map-btn kakao">
							<?php esc_html_e( '카카오 지도', 'doduri' ); ?>
						</a>
						<a href="<?php echo esc_url( $kakao_channel_url ); ?>" target="_blank" rel="noopener" class="map-btn kakao-channel">
							<?php esc_html_e( '카톡 채널', 'doduri' ); ?>
						</a>
					</div>
```

- [ ] **Step 4: 진료시간 섹션에 `id="hours"` 앵커 추가**

라인 131 (`<div class="loc-hours">`) 를 다음으로 교체:

```php
					<div class="loc-hours" id="hours">
```

- [ ] **Step 5: 카톡 채널 버튼 스타일 추가**

`wp-content/themes/doduri/page-location.php` 의 `<style>` 블록 (라인 70~71 부근) 의 `.map-btn.kakao { ... }` 다음 줄에 추가:

```css
.map-btn.kakao-channel { background:#3c1e1e; color:#fae100; }
```

- [ ] **Step 6: anchor 스크롤 오프셋 보정 (헤더 가림 방지)**

`wp-content/themes/doduri/page-location.php` 의 `<style>` 블록 끝부분에 추가 (`@media` 직전):

```css
.loc-hours { scroll-margin-top: 100px; }
```

- [ ] **Step 7: PHP 문법 검증**

```bash
php -l wp-content/themes/doduri/page-location.php
```

Expected: `No syntax errors detected`

- [ ] **Step 8: 브라우저 확인**

1. http://doduri.local/location/ 접속 — 지도 정상 로드, 버스 정보 (`계산중학교 / 부평동초등학교 / 대동아파트 정류장 하차 (도보 5분 이내)`) 표시 확인
2. 지도 앱 버튼 3개 (네이버/카카오/카톡 채널). 카톡 채널 버튼 클릭 → `pf.kakao.com/_lwlTX` 새 탭
3. http://doduri.local/location/#hours 접속 — 페이지 로드 후 진료시간 섹션으로 자동 스크롤. 헤더에 가리지 않는지 확인
4. 헤더 메뉴 "진료시간" 클릭 → 동일 동작

- [ ] **Step 9: Commit**

```bash
git add wp-content/themes/doduri/page-location.php
git commit -m "feat(location): hours 앵커 + 카톡 채널 버튼 + 네이버지도 short URL"
```

---

## Task 11: page-notice.php 신설

**Files:**
- Create: `wp-content/themes/doduri/page-notice.php`

- [ ] **Step 1: 파일 생성**

`wp-content/themes/doduri/page-notice.php`:

```php
<?php
/**
 * Template Name: 공지사항 (notice)
 * 슬러그 'notice' 페이지에 적용.
 *
 * 게시판은 KBoard 플러그인 설치 후, 페이지 본문에 [kboard id="..."] 숏코드를
 * 삽입하면 본문 출력 영역에 자동 렌더된다.
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
		'title'      => __( '커뮤니티', 'doduri' ),
		'subtitle'   => __( '도두리동물병원의 소식과 문의를 한 곳에서.', 'doduri' ),
		'crumbs'     => array(
			array(
				'label' => __( '커뮤니티', 'doduri' ),
				'url'   => home_url( '/notice/' ),
			),
			array( 'label' => __( '공지사항', 'doduri' ) ),
		),
		'tabs'       => array(
			array( 'key' => 'notice', 'label' => __( '공지사항', 'doduri' ), 'url' => home_url( '/notice/' ) ),
			array( 'key' => 'qna',    'label' => __( 'Q&A', 'doduri' ),       'url' => home_url( '/qna/' ) ),
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
				<h2 class="section-title"><?php esc_html_e( '도두리동물병원 소식', 'doduri' ); ?></h2>
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
					<i class="fas fa-bullhorn"></i>
					<p><?php esc_html_e( '공지사항 게시판은 관리자 페이지에서 KBoard 플러그인 설치 후 [kboard id="..."] 숏코드로 추가됩니다.', 'doduri' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
```

- [ ] **Step 2: PHP 문법 검증**

```bash
php -l wp-content/themes/doduri/page-notice.php
```

Expected: `No syntax errors detected`

- [ ] **Step 3: WP 페이지 생성 확인**

(WP 관리자) `/wp-admin/edit.php?post_type=page` 에서 슬러그 `notice` 페이지가 존재하는지 확인. 없으면 새 페이지 추가:
- 제목: `공지사항`
- 슬러그: `notice`
- 본문: 비워둠 (KBoard 숏코드는 Task 13 에서 입력)
- 페이지 속성 > 템플릿: `공지사항 (notice)` 자동 매칭 (page-notice.php 가 슬러그 매칭으로 자동 사용됨)

- [ ] **Step 4: 브라우저 확인**

http://doduri.local/notice/ 접속. 서브 헤더 (커뮤니티 → 공지사항 breadcrumb), 공지사항/Q&A 탭 (공지사항 active), placeholder 메시지 표시. body class `page-community` 확인 (DevTools).

- [ ] **Step 5: Commit**

```bash
git add wp-content/themes/doduri/page-notice.php
git commit -m "feat(community): 공지사항 페이지 템플릿 추가"
```

---

## Task 12: page-qna.php 신설

**Files:**
- Create: `wp-content/themes/doduri/page-qna.php`

- [ ] **Step 1: 파일 생성**

`wp-content/themes/doduri/page-qna.php`:

```php
<?php
/**
 * Template Name: Q&A (qna)
 * 슬러그 'qna' 페이지에 적용.
 *
 * 게시판은 KBoard 플러그인 설치 후, 페이지 본문에 [kboard id="..."] 숏코드를
 * 삽입하면 본문 출력 영역에 자동 렌더된다.
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
		'title'      => __( '커뮤니티', 'doduri' ),
		'subtitle'   => __( '도두리동물병원의 소식과 문의를 한 곳에서.', 'doduri' ),
		'crumbs'     => array(
			array(
				'label' => __( '커뮤니티', 'doduri' ),
				'url'   => home_url( '/notice/' ),
			),
			array( 'label' => __( 'Q&A', 'doduri' ) ),
		),
		'tabs'       => array(
			array( 'key' => 'notice', 'label' => __( '공지사항', 'doduri' ), 'url' => home_url( '/notice/' ) ),
			array( 'key' => 'qna',    'label' => __( 'Q&A', 'doduri' ),       'url' => home_url( '/qna/' ) ),
		),
		'active_tab' => 'qna',
	)
);
get_template_part( 'template-parts/sub-page-header' );
?>

<main>
	<section class="section">
		<div class="container">
			<div class="section-header">
				<p class="section-tag"><?php esc_html_e( 'Q&A', 'doduri' ); ?></p>
				<h2 class="section-title"><?php esc_html_e( '문의하기', 'doduri' ); ?></h2>
				<p class="section-desc"><?php esc_html_e( '비밀글로 작성하시면 작성자와 관리자만 볼 수 있습니다.', 'doduri' ); ?></p>
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
					<p><?php esc_html_e( 'Q&A 게시판은 관리자 페이지에서 KBoard 플러그인 설치 후 [kboard id="..."] 숏코드로 추가됩니다.', 'doduri' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
```

- [ ] **Step 2: PHP 문법 검증**

```bash
php -l wp-content/themes/doduri/page-qna.php
```

Expected: `No syntax errors detected`

- [ ] **Step 3: WP 페이지 생성**

(WP 관리자) 새 페이지 추가:
- 제목: `QnA`
- 슬러그: `qna`
- 본문: 비워둠
- 자동 템플릿 매칭

- [ ] **Step 4: 브라우저 확인**

http://doduri.local/qna/ 접속. 서브 헤더, Q&A active 탭, placeholder. 공지사항 탭 클릭 시 `/notice/` 로 이동하는지 확인.

- [ ] **Step 5: Commit**

```bash
git add wp-content/themes/doduri/page-qna.php
git commit -m "feat(community): Q&A 페이지 템플릿 추가"
```

---

## Task 13: KBoard 플러그인 설치 + 게시판 셋업 (수동)

이 task 는 코드 변경이 아닌 WordPress 관리자 GUI 작업이다. 사용자에게 정확한 단계를 안내하고 결과를 검증한다.

- [ ] **Step 1: KBoard 플러그인 설치**

(WP 관리자) `/wp-admin/plugin-install.php?s=KBoard&tab=search&type=term`
- "KBoard" 검색 → "KBoard - WordPress Bulletin Board, Forum" 설치 → 활성화

활성화 후 좌측 메뉴에 **KBoard** 가 나타남.

- [ ] **Step 2: 공지사항 게시판 생성**

(WP 관리자) KBoard > 게시판 > 게시판 등록:
- 게시판 이름: `도두리 공지사항`
- 게시판 스킨: 기본
- 페이지당 글 수: `10`

생성 후 `게시판 설정`:
- 글쓰기 권한: 관리자
- 댓글: OFF
- 비밀글: OFF
- 비회원 글쓰기: OFF
- 첨부파일: 이미지만, 최대 3개, 2MB/파일

게시판 ID (uid) 를 메모. 예: `1`

- [ ] **Step 3: Q&A 게시판 생성**

KBoard > 게시판 등록:
- 게시판 이름: `도두리 Q&A`
- 페이지당 글 수: `15`

게시판 설정:
- 글쓰기 권한: 누구나 (비회원 포함)
- 비회원 글쓰기: ON (이름 + 비밀번호 필수)
- 비밀글: ON
- 첨부파일: OFF
- 댓글: ON
- 욕설 필터: ON
- 스팸 방지: KBoard 자체 캡차 (1단계)

게시판 ID 메모. 예: `2`

- [ ] **Step 4: 페이지 본문에 숏코드 입력**

(WP 관리자) 페이지 > `공지사항` 편집:
- 본문에 다음 숏코드 입력 (게시판 ID 는 Step 2 메모 값으로):
  ```
  [kboard id="1"]
  ```
- 업데이트

페이지 > `QnA` 편집:
- 본문에 (게시판 ID 는 Step 3 메모 값):
  ```
  [kboard id="2"]
  ```
- 업데이트

- [ ] **Step 5: 브라우저 확인 — 공지사항**

http://doduri.local/notice/ 접속:
- 관리자 로그인 상태 → 글쓰기 버튼 보임
- 글쓰기 클릭 → 제목/내용/이미지 첨부 → 저장
- 목록에 새 글 표시
- 메인 페이지 http://doduri.local/ → "공지사항" 섹션에 방금 작성한 글 노출 (Task 8 의 home-notices)

- [ ] **Step 6: 브라우저 확인 — Q&A**

브라우저 시크릿 창 (비로그인 상태)으로 http://doduri.local/qna/ 접속:
- 글쓰기 버튼 보임 (비회원 작성 ON)
- 글쓰기 → 이름/비밀번호 입력 → 비밀글 체크 → 저장
- 목록에 자물쇠 아이콘으로 비밀글 표시
- 비밀번호 입력 시 본문 열람 가능
- 관리자 로그인 후 동일 글에 댓글 작성 → 비밀글 답글 사이클 검증

- [ ] **Step 7: 진행 기록**

수동 task 라 git commit 없음. 다음 task 로 진행.

---

## Task 14: KBoard CSS 오버라이드

**Files:**
- Create: `wp-content/themes/doduri/assets/css/kboard-override.css`
- Modify: `wp-content/themes/doduri/inc/enqueue.php`

- [ ] **Step 1: 오버라이드 CSS 파일 생성**

`wp-content/themes/doduri/assets/css/kboard-override.css`:

```css
/**
 * KBoard 기본 스킨 오버라이드 — 도두리 톤(베이지/워머).
 *
 * 실제 KBoard 출력 마크업 클래스에 맞춰 점진적으로 보강한다.
 * 기본 색만 우선 통일하고, 상세 디자인은 클라이언트 피드백 후 보강.
 */

/* 컨테이너 */
.kboard-default-list,
.kboard-default-document,
.kboard-document {
	font-family: 'Noto Sans KR', sans-serif;
	color: #2a2520;
}

/* 헤더 행 */
.kboard-default-list table thead {
	background: #faf6ef;
}
.kboard-default-list table thead th {
	color: #2a2520;
	font-weight: 700;
	border-bottom: 2px solid #b08651 !important;
}

/* 행 hover */
.kboard-default-list table tbody tr:hover {
	background: #faf6ef;
}

/* 버튼 (글쓰기, 검색, 페이지네이션) */
.kboard-default-list .kboard-control-search button,
.kboard-default-list a.kboard-default-button-write,
.kboard-default-document a.kboard-default-button-list,
.kboard-default-document button[type="submit"] {
	background: #b08651 !important;
	color: #fff !important;
	border: none !important;
	border-radius: 6px !important;
	padding: 8px 18px !important;
	font-weight: 700 !important;
	transition: filter 0.2s;
}
.kboard-default-list .kboard-control-search button:hover,
.kboard-default-list a.kboard-default-button-write:hover {
	filter: brightness(0.92);
}

/* 페이지네이션 active */
.kboard-pagination a.active,
.kboard-pagination .active a {
	background: #b08651;
	color: #fff;
}

/* 비밀글 자물쇠 컬러 */
.kboard-default-list .kboard-locked i,
.kboard-default-list .kboard-locked .kboard-attribute-locked {
	color: #b08651;
}

/* 모바일 */
@media (max-width: 768px) {
	.kboard-default-list table { font-size: 13px; }
}
```

- [ ] **Step 2: enqueue.php 에 KBoard CSS 추가**

`wp-content/themes/doduri/inc/enqueue.php` 의 `doduri_enqueue_assets()` 함수 끝부분 (네이버 지도 enqueue 직전, 라인 94 부근) 에 추가:

```php
	// KBoard 오버라이드 CSS — notice/qna 페이지에서만
	if ( is_page( array( 'notice', 'qna' ) ) ) {
		wp_enqueue_style(
			'doduri-kboard-override',
			DODURI_THEME_URI . '/assets/css/kboard-override.css',
			array( 'doduri-style-main' ),
			$ver
		);
	}
```

- [ ] **Step 3: PHP 문법 검증**

```bash
php -l wp-content/themes/doduri/inc/enqueue.php
```

Expected: `No syntax errors detected`

- [ ] **Step 4: 브라우저 확인**

http://doduri.local/notice/ 와 http://doduri.local/qna/ — 글쓰기 버튼이 베이지(#b08651) 톤. 테이블 헤더 배경 #faf6ef. 행 hover 효과. 다른 페이지(`/`, `/greeting/` 등)에는 KBoard CSS 미로드 확인 (DevTools Network).

- [ ] **Step 5: Commit**

```bash
git add wp-content/themes/doduri/assets/css/kboard-override.css wp-content/themes/doduri/inc/enqueue.php
git commit -m "feat(kboard): 도두리 톤 CSS 오버라이드 (notice/qna 한정)"
```

---

## Task 15: 사진 자료 적용 (수동)

PDF p.24 Drive 폴더에서 병원 사진 다운로드 후 등록. 코드 변경 없음.

- [ ] **Step 1: Drive 사진 다운로드**

PDF에 명시된 Drive 폴더 링크 접근 → 병원 외관/내부 사진 5장 이상 다운로드. 권한 거절되면 클라이언트에 요청.

- [ ] **Step 2: WP 미디어 라이브러리 업로드**

(WP 관리자) `/wp-admin/upload.php` → 미디어 추가 → 사진 5장 일괄 업로드.

- [ ] **Step 3: ACF 옵션 페이지에서 home_hero_slides 등록**

ACF Pro 가 활성화되어 있어야 함. (없으면 fallback 으로 facility1~5.png 가 자동 사용됨 — Skip 가능)

ACF Pro 활성화 시:
- ACF > 필드 그룹 > "도두리 사이트 옵션" 그룹 편집
- 필드 추가:
  - 필드 라벨: `메인 히어로 슬라이드`
  - 필드 이름: `home_hero_slides`
  - 필드 유형: `Repeater` (Pro 전용) 또는 `Gallery`
  - Gallery 권장 (간단)
- 위치: `옵션 페이지` = `도두리 사이트 옵션`
- 게시

사이트 옵션 페이지 진입 → `메인 히어로 슬라이드` 에 업로드한 사진 5장 선택 → 저장

- [ ] **Step 4: facility 사진 교체 (선택)**

기존 `wp-content/themes/doduri/assets/images/facility/facility[1-5].png` 가 더미면 실제 사진으로 교체. 파일명을 동일하게 유지하거나, ACF `facility_gallery` 옵션에 새 이미지 등록.

- [ ] **Step 5: 브라우저 확인**

- http://doduri.local/ 히어로 슬라이드 → 실제 병원 사진 5장 표시
- http://doduri.local/facility/ → 시설 사진 정상

- [ ] **Step 6: Commit (asset 변경된 경우만)**

`assets/images/facility/` 의 png 가 교체됐다면:

```bash
git add wp-content/themes/doduri/assets/images/facility/
git commit -m "chore(assets): 시설 사진 실 자료로 교체"
```

ACF 옵션 등록만 했으면 git 변경 없음 (DB 저장).

---

## 완료 검증 (전체)

모든 task 완료 후 다음 체크리스트로 회귀 테스트:

- [ ] http://doduri.local/ — 히어로 슬라이드 자동재생, 진료과목 미니카드 3개, 공지 미리보기, 진료시간/오시는길 요약 박스
- [ ] http://doduri.local/greeting/ — 인사말 정상
- [ ] http://doduri.local/doctor/ — 의료진 소개 정상
- [ ] http://doduri.local/facility/ — 시설 사진 정상
- [ ] http://doduri.local/service-subject/ — 진료과목 3개 클리닉 정상
- [ ] http://doduri.local/location/ — 지도 로드 (Naver Maps), 카톡 채널 버튼 동작
- [ ] http://doduri.local/location/#hours — 진료시간 섹션으로 자동 스크롤
- [ ] http://doduri.local/notice/ — 공지사항 게시판 노출
- [ ] http://doduri.local/qna/ — 비회원 작성 + 비밀글 사이클 검증
- [ ] http://doduri.local/contact/ → 404
- [ ] 헤더 메뉴: 병원소개 / 진료안내 / 커뮤니티 (3개)
- [ ] 모바일(≤900px): 햄버거 메뉴, 하단 탭바, 플로팅 버튼 우측 하단
- [ ] PC(≥901px): 플로팅 버튼 우측 중앙
- [ ] 푸터: 사업자번호 `409-26-52253`, 카톡 빠른 링크 외부 채널 이동
- [ ] 모든 페이지에서 플로팅 버튼 (전화/카톡/블로그) 노출
- [ ] DevTools Console 에러 없음
- [ ] PHP 에러 로그 없음 (`wp-content/debug.log` 비어 있음)

이 회귀 테스트가 통과하면 STEP 7~11 (GitHub repo, CI/CD, 배포, 보안 감사, 인계 매뉴얼) 로 진행 — 별도 task #7~#11.
