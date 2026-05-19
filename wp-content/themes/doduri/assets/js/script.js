/* =====================================================
   script.js — 공통 스크립트 (전 페이지)
   include.js가 헤더/푸터를 삽입한 뒤 app-ready 이벤트로 초기화
===================================================== */

document.addEventListener('app-ready', function () {

  /* ===== HEADER SCROLL ===== */
  const header = document.getElementById('header');
  window.addEventListener('scroll', () => {
    header.classList.toggle('scrolled', window.scrollY > 50);
  });

  /* ===== MOBILE NAV ===== */
  const hamburger   = document.getElementById('hamburger');
  const mobileNav   = document.getElementById('mobileNav');
  const mobileOverlay = document.getElementById('mobileOverlay');
  const mobileClose = document.getElementById('mobileClose');
  const mobileLinks = document.querySelectorAll('.mobile-link');

  function openMobileNav() {
    mobileNav.classList.add('open');
    mobileOverlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function closeMobileNav() {
    mobileNav.classList.remove('open');
    mobileOverlay.classList.remove('open');
    document.body.style.overflow = '';
  }

  hamburger.addEventListener('click', openMobileNav);
  mobileClose.addEventListener('click', closeMobileNav);
  mobileOverlay.addEventListener('click', closeMobileNav);

  mobileLinks.forEach(l => {
    if (l.classList.contains('mobile-parent')) {
      l.addEventListener('click', function (e) {
        e.preventDefault();
        const sub = this.nextElementSibling;
        const isOpen = this.classList.contains('open');
        // 다른 열린 항목 닫기
        document.querySelectorAll('.mobile-parent.open').forEach(p => {
          p.classList.remove('open');
          if (p.nextElementSibling) p.nextElementSibling.classList.remove('open');
        });
        if (!isOpen) {
          this.classList.add('open');
          if (sub) sub.classList.add('open');
        }
      });
    } else {
      l.addEventListener('click', closeMobileNav);
    }
  });

  /* ===== HERO SLIDER ===== */
  const slides   = document.querySelectorAll('.hero-slide');
  const dots     = document.querySelectorAll('.dot');
  const heroPrev = document.querySelector('.hero-prev');
  const heroNext = document.querySelector('.hero-next');

  if (slides.length > 1 && heroPrev && heroNext) {
    let currentSlide = 0;
    let autoplayTimer;

    function goToSlide(index) {
      const prev = slides[currentSlide];
      dots[currentSlide].classList.remove('active');
      currentSlide = (index + slides.length) % slides.length;
      prev.classList.remove('active');
      const next = slides[currentSlide];
      next.classList.remove('active');
      void next.offsetWidth; // reflow 강제
      next.classList.add('active');
      dots[currentSlide].classList.add('active');
    }

    function startAutoplay() {
      autoplayTimer = setInterval(() => goToSlide(currentSlide + 1), 6000);
    }
    function resetAutoplay() {
      clearInterval(autoplayTimer);
      startAutoplay();
    }

    heroPrev.addEventListener('click', () => { goToSlide(currentSlide - 1); resetAutoplay(); });
    heroNext.addEventListener('click', () => { goToSlide(currentSlide + 1); resetAutoplay(); });
    dots.forEach((dot, i) => {
      dot.addEventListener('click', () => { goToSlide(i); resetAutoplay(); });
    });

    // 마우스 드래그
    const hero = document.getElementById('hero');
    let dragStartX = 0, isDragging = false;
    hero.addEventListener('mousedown', e => {
      if (e.target.closest('.hero-prev, .hero-next, .dot')) return;
      dragStartX = e.clientX; isDragging = false;
      hero.style.cursor = 'grabbing';
    });
    hero.addEventListener('mousemove', e => {
      if (!e.buttons) return;
      if (Math.abs(e.clientX - dragStartX) > 5) isDragging = true;
    });
    hero.addEventListener('mouseup', e => {
      hero.style.cursor = '';
      if (!isDragging) return;
      const diff = dragStartX - e.clientX;
      if (Math.abs(diff) > 40) { goToSlide(diff > 0 ? currentSlide + 1 : currentSlide - 1); resetAutoplay(); }
      isDragging = false;
    });
    hero.addEventListener('mouseleave', () => { hero.style.cursor = ''; isDragging = false; });

    // 터치 스와이프
    let touchStartX = 0;
    hero.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; }, { passive: true });
    hero.addEventListener('touchend', e => {
      const diff = touchStartX - e.changedTouches[0].clientX;
      if (Math.abs(diff) > 40) { goToSlide(diff > 0 ? currentSlide + 1 : currentSlide - 1); resetAutoplay(); }
    });

    // 히어로 벗어나면 화살표 숨김
    const heroEl = document.getElementById('hero');
    if (heroEl) {
      const heroArrows = document.querySelectorAll('.hero-prev, .hero-next');
      const toggleArrows = () => {
        const bottom = heroEl.getBoundingClientRect().bottom;
        heroArrows.forEach(a => a.style.visibility = bottom > 60 ? '' : 'hidden');
      };
      window.addEventListener('scroll', toggleArrows, { passive: true });
      toggleArrows();
    }

    startAutoplay();
  }

  /* ===== STATS COUNTER ===== */
  const statNums = document.querySelectorAll('.stat-num');
  let statsAnimated = false;

  function animateStats() {
    if (statsAnimated) return;
    const statsSection = document.querySelector('.stats-section');
    if (!statsSection) return;

    const rect = statsSection.getBoundingClientRect();
    if (rect.top < window.innerHeight * 0.85) {
      statsAnimated = true;
      statNums.forEach(num => {
        const target = parseInt(num.dataset.target, 10);
        const duration = 1800;
        const start = performance.now();
        function update(now) {
          const elapsed = now - start;
          const progress = Math.min(elapsed / duration, 1);
          const ease = 1 - Math.pow(1 - progress, 3);
          num.textContent = Math.floor(ease * target).toLocaleString();
          if (progress < 1) requestAnimationFrame(update);
          else num.textContent = target.toLocaleString();
        }
        requestAnimationFrame(update);
      });
    }
  }

  /* ===== BACK TO TOP ===== */
  const backToTop = document.getElementById('backToTop');
  window.addEventListener('scroll', () => {
    backToTop.classList.toggle('visible', window.scrollY > 200);
  }, { passive: true });
  backToTop.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  /* ===== SMOOTH SCROLL FOR ANCHOR LINKS ===== */
  document.querySelectorAll('a[href^="#"]').forEach(link => {
    link.addEventListener('click', e => {
      const target = document.querySelector(link.getAttribute('href'));
      if (target) {
        e.preventDefault();
        const top = target.getBoundingClientRect().top + window.scrollY - 80;
        window.scrollTo({ top, behavior: 'smooth' });
      }
    });
  });

  /* ===== 스크롤 진행 바 ===== */
  const scrollProgressBar = document.getElementById('scrollProgress');
  window.addEventListener('scroll', () => {
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    const pct = docHeight > 0 ? (window.scrollY / docHeight) * 100 : 0;
    if (scrollProgressBar) scrollProgressBar.style.width = pct + '%';
  }, { passive: true });

  /* ===== 커서 글로우 ===== */
  const cursorGlow = document.getElementById('cursorGlow');
  if (cursorGlow && window.matchMedia('(hover: hover)').matches) {
    document.addEventListener('mousemove', e => {
      cursorGlow.style.left = e.clientX + 'px';
      cursorGlow.style.top = e.clientY + 'px';
    });
  }

  /* ===== 히어로 타이프라이터 효과 ===== */
  (function initTypewriter() {
    const firstSlide = document.querySelector('.hero-slide[data-index="0"] h1');
    if (!firstSlide) return;

    let done = false;
    function runTypewriter() {
      if (done) return;
      done = true;
      const em = firstSlide.querySelector('em');
      const emText = em ? em.textContent : '';
      const plainText = firstSlide.textContent.replace(emText, '').trim();

      firstSlide.innerHTML = '';
      const cursor = document.createElement('span');
      cursor.className = 'tw-cursor';
      firstSlide.appendChild(cursor);

      let i = 0;
      function typeMain() {
        if (i <= plainText.length) {
          firstSlide.innerHTML = plainText.slice(0, i).replace('\n', '<br>');
          i++;
          if (i <= plainText.length) {
            setTimeout(typeMain, 55);
          } else {
            firstSlide.innerHTML += '<br>';
            const emEl = document.createElement('em');
            emEl.style.opacity = '0';
            emEl.style.transition = 'opacity 0.5s ease';
            emEl.textContent = emText;
            firstSlide.appendChild(emEl);
            firstSlide.appendChild(cursor);
            setTimeout(() => {
              emEl.style.opacity = '1';
              setTimeout(() => cursor.remove(), 800);
            }, 120);
          }
        }
      }
      setTimeout(typeMain, 600);
    }

    const firstSlideEl = document.querySelector('.hero-slide[data-index="0"]');
    if (firstSlideEl && firstSlideEl.classList.contains('active')) {
      setTimeout(runTypewriter, 800);
    }
  })();

  /* ===== 카드 3D 틸트 효과 ===== */
  function addTiltEffect(selector, maxDeg = 8) {
    document.querySelectorAll(selector).forEach(card => {
      card.addEventListener('mousemove', e => {
        const rect = card.getBoundingClientRect();
        const x = (e.clientX - rect.left) / rect.width - 0.5;
        const y = (e.clientY - rect.top) / rect.height - 0.5;
        card.style.transition = 'transform 0.1s ease';
        card.style.transform = `perspective(900px) rotateX(${-y * maxDeg}deg) rotateY(${x * maxDeg}deg) translateY(-8px) scale(1.01)`;
      });
      card.addEventListener('mouseleave', () => {
        card.style.transition = 'transform 0.45s cubic-bezier(0.34,1.56,0.64,1)';
        card.style.transform = '';
      });
    });
  }

  addTiltEffect('.service-card', 7);
  addTiltEffect('.testimonial-card', 5);

  /* ===== 인터섹션 옵저버 (fade-up) ===== */
  document.querySelectorAll(
    '.service-card, .process-step, .testimonial-card, .health-item, .stat-item, .news-item'
  ).forEach(el => el.classList.add('fade-up'));

  const io = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const idx = Array.from(document.querySelectorAll('.fade-up')).indexOf(entry.target);
        setTimeout(() => entry.target.classList.add('visible'), (idx % 5) * 70);
        io.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

  document.querySelectorAll('.fade-up').forEach(el => io.observe(el));

  /* ===== 숫자 카운터 (인터섹션 기반) ===== */
  const statsIo = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting && !statsAnimated) {
        animateStats();
        statsIo.disconnect();
      }
    });
  }, { threshold: 0.5 });

  const statsSection = document.querySelector('.stats-section');
  if (statsSection) statsIo.observe(statsSection);

  /* ===== 섹션 헤더 등장 애니메이션 ===== */
  const headerIo = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.animation = 'fade-slide-in 0.6s cubic-bezier(0.34,1.2,0.64,1) both';
        headerIo.unobserve(entry.target);
      }
    });
  }, { threshold: 0.3 });

  document.querySelectorAll('.section-header').forEach(h => headerIo.observe(h));

  /* ===== 홈 시설 슬라이더 ===== */
  (function () {
    const slider = document.querySelector('.home-facility-slider');
    if (!slider) return;

    const slides    = slider.querySelectorAll('.home-fac-slide');
    const prevBtn   = slider.querySelector('.home-fac-prev');
    const nextBtn   = slider.querySelector('.home-fac-next');
    const currentEl = slider.querySelector('.home-fac-current');
    let current = 0;
    let timer;

    function goTo(idx) {
      slides[current].classList.remove('active');
      current = (idx + slides.length) % slides.length;
      slides[current].classList.add('active');
      if (currentEl) currentEl.textContent = current + 1;
    }

    function startAuto() { timer = setInterval(() => goTo(current + 1), 5000); }
    function resetAuto()  { clearInterval(timer); startAuto(); }

    prevBtn.addEventListener('click', () => { goTo(current - 1); resetAuto(); });
    nextBtn.addEventListener('click', () => { goTo(current + 1); resetAuto(); });

    let touchStartX = 0;
    slider.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; }, { passive: true });
    slider.addEventListener('touchend', e => {
      const diff = touchStartX - e.changedTouches[0].clientX;
      if (Math.abs(diff) > 40) { goTo(diff > 0 ? current + 1 : current - 1); resetAuto(); }
    });

    startAuto();
  })();

  /* ===== 진료비 안내 모달 ===== */
  (function () {
    const btn    = document.getElementById('feeGuideBtn');
    const modal  = document.getElementById('feeGuideModal');
    const img1   = document.getElementById('feeGuideImg');
    const img2   = document.getElementById('feeGuideImg2');
    const close  = document.getElementById('feeGuideClose');
    if (!btn || !modal) return;

    function openModal() {
      const isMobile = window.matchMedia('(max-width: 768px)').matches;
      const srcPc    = btn.dataset.imgPc;
      const src1     = btn.dataset.img;
      const src2     = btn.dataset.img2;

      if (isMobile) {
        // 모바일: ①② 스크롤
        if (!src1) return;
        img1.src = src1;
        img1.hidden = false;
        if (src2) {
          img2.src = src2;
          img2.hidden = false;
        } else {
          img2.removeAttribute('src');
          img2.hidden = true;
        }
      } else {
        // PC: PC 전용 이미지 1장 (없으면 ① 사용)
        const pcSrc = srcPc || src1;
        if (!pcSrc) return;
        img1.src = pcSrc;
        img1.hidden = false;
        img2.removeAttribute('src');
        img2.hidden = true;
      }

      modal.querySelector('.fee-guide-scroll').scrollTop = 0;
      modal.hidden = false;
      document.body.style.overflow = 'hidden';
    }
    function closeModal() {
      modal.hidden = true;
      document.body.style.overflow = '';
    }

    btn.addEventListener('click', openModal);
    close.addEventListener('click', closeModal);
    modal.addEventListener('click', function (e) {
      if (e.target === modal) closeModal();
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !modal.hidden) closeModal();
    });
  })();

}); // end app-ready
