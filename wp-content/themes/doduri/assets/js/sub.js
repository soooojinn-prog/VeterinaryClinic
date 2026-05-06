/* =====================================================
   sub.js — 서브 페이지 전용 스크립트
===================================================== */

document.addEventListener('app-ready', function () {

  /* ===== 스크롤 시 서브탭 active 자동 전환 ===== */
  const subTabLinks = document.querySelectorAll('.sub-tab-link');

  if (subTabLinks.length) {
    const sections = [];
    subTabLinks.forEach(link => {
      const href = link.getAttribute('href');
      const id = href.includes('#') ? href.split('#')[1] : null;
      if (id) {
        const el = document.getElementById(id);
        if (el) sections.push({ id, el, link });
      }
    });

    if (sections.length) {
      function updateActiveTab() {
        const scrollY = window.scrollY + 160;
        let current = sections[0];
        sections.forEach(s => { if (scrollY >= s.el.offsetTop) current = s; });
        subTabLinks.forEach(l => l.classList.remove('active'));
        if (current) current.link.classList.add('active');
      }
      window.addEventListener('scroll', updateActiveTab, { passive: true });
      updateActiveTab();
    }
  }

  /* ===== 시설 슬라이더 ===== */
  const slides     = document.querySelectorAll('.slider-slide');
  const thumbs     = document.querySelectorAll('.facility-thumb');
  const prevBtn    = document.querySelector('.slider-prev');
  const nextBtn    = document.querySelector('.slider-next');
  const currentEl  = document.querySelector('.slider-current');
  const sliderMain = document.querySelector('.facility-slider-main');

  if (!slides.length) return;

  let current   = 0;
  let autoTimer = null;

  function goToSlide(index) {
    slides[current].classList.remove('active');
    thumbs[current].classList.remove('active');
    current = (index + slides.length) % slides.length;
    slides[current].classList.add('active');
    thumbs[current].classList.add('active');
    if (currentEl) currentEl.textContent = current + 1;
  }

  function startAuto() {
    autoTimer = setInterval(() => goToSlide(current + 1), 4000);
  }
  function stopAuto()  { clearInterval(autoTimer); }
  function resetAuto() { stopAuto(); startAuto(); }

  prevBtn.addEventListener('click', () => { goToSlide(current - 1); resetAuto(); });
  nextBtn.addEventListener('click', () => { goToSlide(current + 1); resetAuto(); });

  thumbs.forEach(thumb => {
    thumb.addEventListener('click', () => {
      goToSlide(Number(thumb.dataset.index));
      resetAuto();
    });
  });

  sliderMain.addEventListener('mouseenter', stopAuto);
  sliderMain.addEventListener('mouseleave', startAuto);

  /* ── 마우스 드래그 슬라이드 ── */
  let dragStartX = 0;
  let wasDragged = false;          // click vs drag 구분용

  sliderMain.addEventListener('mousedown', e => {
    // 버튼 클릭은 드래그로 처리 안 함
    if (e.target.closest('.slider-btn')) return;
    dragStartX = e.clientX;
    wasDragged = false;
    sliderMain.style.cursor = 'grabbing';
    stopAuto();
    e.preventDefault();            // 이미지 drag 기본 동작 방지
  });

  window.addEventListener('mousemove', e => {
    if (!sliderMain.style.cursor || sliderMain.style.cursor !== 'grabbing') return;
    if (Math.abs(e.clientX - dragStartX) > 8) wasDragged = true;
  });

  window.addEventListener('mouseup', e => {
    if (sliderMain.style.cursor !== 'grabbing') return;
    const dx = e.clientX - dragStartX;
    if (wasDragged && Math.abs(dx) > 40) {
      goToSlide(dx < 0 ? current + 1 : current - 1);
    }
    sliderMain.style.cursor = '';
    startAuto();
  });

  /* ── 터치 스와이프 ── */
  let touchStartX = 0;
  let touchStartY = 0;

  sliderMain.addEventListener('touchstart', e => {
    touchStartX = e.touches[0].clientX;
    touchStartY = e.touches[0].clientY;
    stopAuto();
  }, { passive: true });

  sliderMain.addEventListener('touchend', e => {
    const dx = e.changedTouches[0].clientX - touchStartX;
    const dy = e.changedTouches[0].clientY - touchStartY;
    if (Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > 40) {
      goToSlide(dx < 0 ? current + 1 : current - 1);
    }
    startAuto();
  }, { passive: true });

  startAuto();

  /* ===== 라이트박스 ===== */
  const lightbox        = document.getElementById('facilityLightbox');
  const lightboxImg     = document.getElementById('lightboxImg');
  const lightboxCurrent = document.getElementById('lightboxCurrent');
  const lightboxTotal   = document.getElementById('lightboxTotal');
  const lightboxClose   = lightbox?.querySelector('.lightbox-close');
  const lightboxPrev    = lightbox?.querySelector('.lightbox-prev');
  const lightboxNext    = lightbox?.querySelector('.lightbox-next');

  if (!lightbox) return;

  const imgSrcs = [...slides].map(s => s.querySelector('img').src);
  let lbIndex = 0;
  if (lightboxTotal) lightboxTotal.textContent = imgSrcs.length;

  function openLightbox(index) {
    lbIndex = (index + imgSrcs.length) % imgSrcs.length;
    lightboxImg.src = imgSrcs[lbIndex];
    lightboxImg.alt = slides[lbIndex].querySelector('img').alt;
    if (lightboxCurrent) lightboxCurrent.textContent = lbIndex + 1;
    lightbox.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeLightbox() {
    lightbox.classList.remove('open');
    document.body.style.overflow = '';
  }

  // 메인 이미지 클릭 → 라이트박스 (드래그 후에는 열지 않음)
  sliderMain.addEventListener('click', e => {
    if (wasDragged) return;
    if (e.target.closest('.slider-btn')) return;
    openLightbox(current);
  });

  lightboxClose.addEventListener('click', closeLightbox);
  lightboxPrev.addEventListener('click', e => { e.stopPropagation(); openLightbox(lbIndex - 1); });
  lightboxNext.addEventListener('click', e => { e.stopPropagation(); openLightbox(lbIndex + 1); });

  // 배경 클릭 시 닫기
  lightbox.addEventListener('click', e => {
    if (e.target === lightbox) closeLightbox();
  });

  // 키보드 조작
  document.addEventListener('keydown', e => {
    if (!lightbox.classList.contains('open')) return;
    if (e.key === 'Escape')      closeLightbox();
    if (e.key === 'ArrowLeft')   openLightbox(lbIndex - 1);
    if (e.key === 'ArrowRight')  openLightbox(lbIndex + 1);
  });

}); // end app-ready
