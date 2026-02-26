(() => {
  const localeToggle = document.getElementById('locale-toggle');
  const modal = document.getElementById('enroll-modal');
  const modalBackdrop = document.getElementById('enroll-backdrop');
  const modalClose = document.getElementById('enroll-close');
  const enrollForm = document.getElementById('enroll-form');
  const enrollSubmit = document.getElementById('enroll-submit');
  const enrollTerms = document.getElementById('enroll-terms');
  const savedLocale = localStorage.getItem('home_locale');
  let locale = savedLocale || 'uk';
  let activeCourseTitle = '';

  const setText = (id, value) => {
    const el = document.getElementById(id);
    if (el) el.textContent = value || '';
  };

  const setHtml = (id, value) => {
    const el = document.getElementById(id);
    if (el) el.innerHTML = value || '';
  };

  const escapeHtml = (value = '') => String(value)
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#39;');

  const renderHeroBanner = (hero = {}) => {
    const uk = locale === 'uk';
    setText('hero-banner-title', uk
      ? (hero.title || 'ЦЕНТР ПІДГОТОВКИ ОПЕРАТОРІВ БПЛА «КРУК»')
      : (hero.title || 'KRUK UAV OPERATOR TRAINING CENTER'));
    setText('hero-banner-subtitle', uk
      ? (hero.subtitle || 'Готуємо операторів мультироторів та БпЛА типу “крило” за програмами, сертифікованими Міністерством Оборони України')
      : (hero.subtitle || 'We train multirotor and fixed-wing UAV operators under programs certified by the Ministry of Defense of Ukraine.'));
    setText('hero-banner-cta', uk ? (hero.cta_label || 'ПЕРЕГЛЯНУТИ КУРСИ') : (hero.cta_label || 'VIEW COURSES'));
    setText('hero-banner-count', String(hero.stats_count ?? 0));
    setText('hero-banner-count-label', uk
      ? (hero.stats_label || 'Кількість виданих сертифікатів')
      : (hero.stats_label || 'Issued certifications'));

    const ctaLink = document.getElementById('hero-banner-cta');
    if (ctaLink) ctaLink.href = normalizeLink(hero.cta_url || '#courses');
  };

  const applyModalLocale = () => {
    const uk = locale === 'uk';
    setText('enroll-modal-title', uk ? 'ФОРМА РЕЄСТРАЦІЇ' : 'REGISTRATION FORM');
    setText('enroll-label-name', uk ? "ПОВНЕ ІМ'Я" : 'FULL NAME');
    setText('enroll-label-member', uk ? 'СТАТУС' : 'MEMBER STATUS');
    setText('enroll-member-default', uk ? 'Оберіть варіант' : 'Choose one of the options');
    setText('enroll-member-military', uk ? 'Військовий' : 'Military');
    setText('enroll-member-civilian', uk ? 'Цивільний' : 'Civilian');
    setText('enroll-member-other', uk ? 'Інше' : 'Other');
    setText('enroll-label-phone', uk ? 'КОНТАКТНИЙ НОМЕР' : 'CONTACT NUMBER');
    setText('enroll-label-email', uk ? 'E-MAIL' : 'E-MAIL ADDRESS');
    setText('enroll-terms-text', uk ? 'Підтверджую, що ознайомився з публічною офертою' : 'I confirm that I have read the public offer');
    setText('enroll-submit', uk ? 'ЗАРЕЄСТРУВАТИСЯ' : 'REGISTER');

    const nameInput = document.getElementById('enroll-full-name');
    const phoneInput = document.getElementById('enroll-phone');
    const emailInput = document.getElementById('enroll-email');
    if (nameInput) nameInput.placeholder = uk ? "Введіть повне ім'я" : 'Enter the full name';
    if (phoneInput) phoneInput.placeholder = '+38 (000) 000 00 00';
    if (emailInput) emailInput.placeholder = uk ? 'Введіть email' : 'Enter your email address';
  };

  const updateModalCourseName = () => {
    const uk = locale === 'uk';
    setText('enroll-course-name', `${uk ? 'Курс:' : 'Course:'} ${activeCourseTitle}`);
  };

  const syncSubmitState = () => {
    if (!enrollSubmit || !enrollTerms) return;
    enrollSubmit.disabled = !enrollTerms.checked;
  };

  const openModal = (courseTitle = '') => {
    activeCourseTitle = courseTitle;
    const courseInput = document.getElementById('enroll-course');
    if (courseInput) {
      courseInput.value = courseTitle;
    }
    updateModalCourseName();
    applyModalLocale();
    if (modal) {
      modal.classList.add('is-open');
      modal.setAttribute('aria-hidden', 'false');
    }
    document.body.style.overflow = 'hidden';
    syncSubmitState();
  };

  const closeModal = () => {
    if (modal) {
      modal.classList.remove('is-open');
      modal.setAttribute('aria-hidden', 'true');
    }
    document.body.style.overflow = '';
  };

  const normalizeLink = (url) => {
    if (!url) return '#';

    const anchorAliases = {
      about: 'who',
      who: 'who',
      'who-we-are': 'who',
      mission: 'mission',
      courses: 'courses',
      specializations: 'specializations',
      press: 'press',
      partners: 'partners',
      contacts: 'footer',
      footer: 'footer',
    };

    const trimmed = String(url).trim();
    const lower = trimmed.toLowerCase();

    const toAnchor = (value) => {
      const key = value.replace(/^#/, '').replace(/^\/+/, '').replace(/\/+$/, '');
      return anchorAliases[key] ? `#${anchorAliases[key]}` : null;
    };

    const directAnchor = toAnchor(lower);
    if (directAnchor) return directAnchor;

    if (lower.startsWith('/#')) {
      const hashAnchor = toAnchor(lower.slice(1));
      if (hashAnchor) return hashAnchor;
      return trimmed;
    }

    if (trimmed.startsWith('http://') || trimmed.startsWith('https://') || trimmed.startsWith('#') || trimmed.startsWith('/')) return trimmed;
    return `/${url}`;
  };

  const normalizeCurrentHash = () => {
    const hash = window.location.hash;
    if (!hash) return;

    const normalized = normalizeLink(hash);
    if (normalized.startsWith('#') && normalized !== hash) {
      history.replaceState(null, '', `${window.location.pathname}${window.location.search}${normalized}`);
    }
  };

  const renderMenu = (items = []) => {
    const root = document.getElementById('menu');
    root.innerHTML = '';
    items.forEach((item) => {
      const li = document.createElement('li');
      const a = document.createElement('a');
      a.href = normalizeLink(item.url);
      a.textContent = item.label || '';
      li.appendChild(a);
      root.appendChild(li);
    });
  };

  const renderCards = (rootId, items = [], map) => {
    const root = document.getElementById(rootId);
    root.innerHTML = '';
    items.forEach((item) => {
      const card = document.createElement('article');
      card.className = 'card';
      card.innerHTML = map(item);
      root.appendChild(card);
    });
  };

  const getCourseImage = (title = '') => {
    const t = title.toLowerCase();
    if (t.includes('мультиротор') || t.includes('multirotor')) return 'https://a.storyblok.com/f/174597/960x1280/96f55f71e8/67c307ed03.jpeg';
    if (t.includes('крило') || t.includes('wing')) return 'https://a.storyblok.com/f/174597/1280x960/70f4ad9ef1/wing-course.jpeg';
    if (t.includes('fpv')) return 'https://a.storyblok.com/f/174597/960x1280/4b65f19a56/fpv-course.jpg';
    if (t.includes('топограф') || t.includes('topograph')) return 'https://a.storyblok.com/f/174597/960x1280/6db4f1b95f/topography-course.jpg';
    return 'https://kruk.in.ua/bg-1.webp';
  };

  const renderCourses = (items = []) => {
    const root = document.getElementById('courses-list');
    root.innerHTML = '';
    const cta = locale === 'uk' ? 'ЗАПИСАТИСЯ' : 'ENROLL';

    items.forEach((item) => {
      const card = document.createElement('article');
      card.className = 'course-card';
      const courseTitle = item.title || (locale === 'uk' ? item.title_uk : item.title_en) || item.title_uk || item.title_en || '';
      const img = item.image_url || getCourseImage(courseTitle);
      const ctaTitle = escapeHtml(courseTitle);
      card.innerHTML = `
        <div class="course-media">
          <img src="${img}" alt="${ctaTitle}">
        </div>
        <div class="course-content">
          <h3>${ctaTitle}</h3>
          <div class="rich-content">${item.description || ''}</div>
          <button type="button" class="course-cta" data-course-title="${encodeURIComponent(courseTitle)}">${cta}</button>
        </div>
      `;
      root.appendChild(card);
    });
  };

  const renderLogos = (rootId, items = []) => {
    const root = document.getElementById(rootId);
    root.innerHTML = '';
    items.forEach((item) => {
      const a = document.createElement('a');
      a.className = 'logo-item';
      a.href = normalizeLink(item.link_url);
      a.target = item.link_url && item.link_url.startsWith('http') ? '_blank' : '_self';
      a.rel = 'noreferrer';
      a.innerHTML = item.logo_url
        ? `<img src="${item.logo_url}" alt="${item.title || ''}">`
        : `<span>${item.title || ''}</span>`;
      root.appendChild(a);
    });
  };

  const renderFooterLinks = (items = []) => {
    const root = document.getElementById('footer-links');
    root.innerHTML = '';
    items.forEach((item) => {
      const li = document.createElement('li');
      li.innerHTML = `<a href="${normalizeLink(item.url)}">${item.label || ''}</a>`;
      root.appendChild(li);
    });
  };

  const render = (data) => {
    const logo = document.getElementById('logo');
    logo.src = data.logo_light_url || '/images/campus-crm-logo.png';

    setText('site-name', data.site_name || 'Campus CRM');
    renderHeroBanner(data.hero_section || {});

    renderMenu(data.menu || []);

    setText('who-title', data.who?.title || 'Who we are');
    setHtml('who-text', data.who?.text || '');
    setText('mission-title', data.mission?.title || 'Our mission');
    setHtml('mission-text', data.mission?.text || '');

    setText('specializations-title', data.specializations_section?.title || 'Specializations');
    setText('press-title', data.press_section?.title || 'Press');
    setText('partners-title', data.partners_section?.title || 'Partners');

    setText('courses-title', locale === 'uk' ? 'КУРСИ' : 'COURSES');
    renderCourses(data.courses_section?.items || []);

    renderCards('specializations-list', data.specializations_section?.items || [], (item) => `
      ${item.image_url ? `<img src="${item.image_url}" alt="${item.title || ''}">` : ''}
      <h4>${item.title || ''}</h4>
      <div class="rich-content">${item.description || ''}</div>
    `);

    renderLogos('press-list', data.press_section?.items || []);
    renderLogos('partners-list', data.partners_section?.items || []);

    setText('footer-title', data.footer?.title || 'Contacts');
    setHtml('footer-text', data.footer?.text || '');
    setText('footer-contact', [data.footer?.email, data.footer?.phone, data.footer?.address].filter(Boolean).join(' | '));
    renderFooterLinks(data.footer?.links || []);

    localeToggle.textContent = locale.toUpperCase();
    applyModalLocale();
    updateModalCourseName();
  };

  const loadData = async () => {
    try {
      const response = await fetch(`/api/cms/home?locale=${locale}`);
      if (!response.ok) throw new Error(`Failed to load CMS data (${response.status})`);
      const data = await response.json();
      render(data);
      normalizeCurrentHash();
    } catch (error) {
      console.error(error);
      setText('who-title', 'Failed to load homepage content');
      setText('who-text', 'Please check CMS API /api/cms/home and refresh this page.');
    }
  };

  localeToggle?.addEventListener('click', () => {
    locale = locale === 'uk' ? 'en' : 'uk';
    localStorage.setItem('home_locale', locale);
    loadData();
  });

  document.addEventListener('click', (event) => {
    const target = event.target;
    if (!(target instanceof HTMLElement)) return;

    const enrollButton = target.closest('.course-cta');
    if (enrollButton instanceof HTMLElement && enrollButton.dataset.courseTitle) {
      openModal(decodeURIComponent(enrollButton.dataset.courseTitle));
      return;
    }

    if (target === modalBackdrop || target === modalClose) {
      closeModal();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      closeModal();
    }
  });

  enrollTerms?.addEventListener('change', syncSubmitState);

  enrollForm?.addEventListener('submit', (event) => {
    event.preventDefault();
    closeModal();
    enrollForm.reset();
    syncSubmitState();
  });

  loadData();
})();
