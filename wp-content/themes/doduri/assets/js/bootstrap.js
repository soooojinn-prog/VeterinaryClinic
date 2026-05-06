/* =====================================================
   bootstrap.js — WordPress 환경에서 script.js 가 기대하는
   'app-ready' 이벤트를 DOM 준비 시점에 발화시킨다.
   (정적 HTML 시절에는 include.js 가 헤더/푸터 삽입 후 이 이벤트를 쏘았음.
    WP 에서는 헤더/푸터가 PHP 로 즉시 렌더되므로 DOM 준비 후 한 번만 쏘면 충분.)
===================================================== */
(function () {
  function fire() {
    document.dispatchEvent(new Event('app-ready'));
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', fire, { once: true });
  } else {
    // 이미 로드 완료된 상태라면 다음 틱에 발화
    setTimeout(fire, 0);
  }

  // 현재 페이지에 맞는 nav 항목에 active-menu 클래스 부여
  // (PHP 쪽 body_class 로도 가능하지만, 기존 .nav-item[data-page] 셀렉터 호환을 위해 유지)
  document.addEventListener('app-ready', function () {
    var body = document.body;
    var classMap = [
      { bodyCls: 'page-about',   key: 'about'   },
      { bodyCls: 'page-service', key: 'service' },
      { bodyCls: 'page-contact', key: 'contact' },
    ];
    for (var i = 0; i < classMap.length; i++) {
      if (body.classList.contains(classMap[i].bodyCls)) {
        var el = document.querySelector('.nav-item[data-page="' + classMap[i].key + '"]');
        if (el) el.classList.add('active-menu');
        break;
      }
    }
  });
})();
