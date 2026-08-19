(function () {
  'use strict';

  function q(scope, selector) { return scope ? scope.querySelector(selector) : null; }
  function qa(scope, selector) { return scope ? Array.prototype.slice.call(scope.querySelectorAll(selector)) : []; }

  function openIvorySearchFallback() {
    var candidates = [
      '.is-search-icon',
      '.is-search-submit',
      '.ivory-search-icon',
      '.is-menu a',
      '.is-menu button',
      '.is-form-style button[type="submit"]'
    ];
    for (var i = 0; i < candidates.length; i++) {
      var el = document.querySelector(candidates[i]);
      if (el) {
        el.click();
        return true;
      }
    }
    return false;
  }

  function initAldia(scope) {
    if (!scope || scope.dataset.nsfpulsoReady === '1') return;
    scope.dataset.nsfpulsoReady = '1';

    var year = q(scope, '#footYear');
    if (year) year.textContent = new Date().getFullYear();

    var drawer = q(scope, '[data-nsfpulso-drawer]') || q(scope, '#drawer');
    var backdrop = q(scope, '[data-nsfpulso-backdrop]') || q(scope, '#backdrop, .drawer-backdrop');
    var burger = q(scope, '[data-nsfpulso-open]') || q(scope, '#burger');
    var drawerClose = q(scope, '[data-nsfpulso-close]') || q(scope, '#drawerClose');
    var searchOpen = q(scope, '[data-nsfpulso-search-open]');
    var searchMode = scope.getAttribute('data-search-mode') || 'ivory_modal';
    var searchModal = q(scope, '[data-nsfpulso-search-modal]');

    function openDrawer(focusSearch) {
      if (!drawer || !backdrop) return;
      drawer.classList.add('is-open');
      backdrop.classList.add('is-open');
      drawer.setAttribute('aria-hidden', 'false');
      if (burger) burger.setAttribute('aria-expanded', 'true');
      document.body.classList.add('nsfpulso-drawer-open');
      document.body.style.overflow = 'hidden';
      if (focusSearch) {
        window.setTimeout(function () {
          var input = drawer.querySelector('input[type="search"]');
          if (input) input.focus();
        }, 180);
      }
    }

    function closeDrawer() {
      if (!drawer || !backdrop) return;
      drawer.classList.remove('is-open');
      backdrop.classList.remove('is-open');
      drawer.setAttribute('aria-hidden', 'true');
      if (burger) burger.setAttribute('aria-expanded', 'false');
      document.body.classList.remove('nsfpulso-drawer-open');
      document.body.style.overflow = '';
    }

    function openSearchModal() {
      if (searchMode === 'trigger_ivory') {
        if (openIvorySearchFallback()) return;
      }
      if (searchMode === 'drawer') {
        openDrawer(true);
        return;
      }
      if (!searchModal) {
        openDrawer(true);
        return;
      }
      searchModal.classList.add('is-open');
      searchModal.setAttribute('aria-hidden', 'false');
      document.body.classList.add('nsfpulso-search-open');
      document.body.style.overflow = 'hidden';
      window.setTimeout(function () {
        var input = searchModal.querySelector('input[type="search"], input[type="text"], .is-search-input');
        if (input) input.focus();
      }, 120);
    }

    function closeSearchModal() {
      if (!searchModal) return;
      searchModal.classList.remove('is-open');
      searchModal.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('nsfpulso-search-open');
      if (!document.body.classList.contains('nsfpulso-drawer-open')) document.body.style.overflow = '';
    }

    if (burger) burger.addEventListener('click', function (e) { e.preventDefault(); openDrawer(false); });
    if (searchOpen) searchOpen.addEventListener('click', function (e) { e.preventDefault(); openSearchModal(); });
    if (drawerClose) drawerClose.addEventListener('click', function (e) { e.preventDefault(); closeDrawer(); });
    if (backdrop) backdrop.addEventListener('click', closeDrawer);
    qa(scope, '[data-nsfpulso-search-close]').forEach(function (el) {
      el.addEventListener('click', function (e) { e.preventDefault(); closeSearchModal(); });
    });
    if (drawer) {
      drawer.querySelectorAll('a').forEach(function (a) {
        a.addEventListener('click', closeDrawer);
      });
    }

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        if (drawer && drawer.classList.contains('is-open')) closeDrawer();
        if (searchModal && searchModal.classList.contains('is-open')) closeSearchModal();
      }
    });

    var views = {
      home: q(scope, '#view-home'),
      nota: q(scope, '#view-nota'),
      cat:  q(scope, '#view-cat')
    };
    var btns = qa(scope, '.view-switcher button');

    function showView(name, scrollToTop) {
      if (name === 'drawer') {
        openDrawer(false);
        return;
      }
      Object.keys(views).forEach(function (k) {
        if (views[k]) views[k].style.display = (k === name) ? '' : 'none';
      });
      btns.forEach(function (b) {
        b.classList.toggle('is-active', b.dataset.view === name);
      });
      if (scrollToTop) {
        try { window.scrollTo({ top: scope.getBoundingClientRect().top + window.scrollY - 20, behavior: 'smooth' }); } catch(e) {}
      }
    }

    btns.forEach(function (b) {
      b.addEventListener('click', function () { showView(b.dataset.view, true); });
    });

    var initialView = scope.getAttribute('data-nsfpulso-default-view') || 'home';
    if (initialView && views[initialView]) showView(initialView, false);

    var gtipClose = q(scope, '#gtipClose');
    var gtooltip = q(scope, '#gtooltip');
    if (gtipClose && gtooltip) {
      gtipClose.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        gtooltip.style.display = 'none';
      });
    }

    qa(scope, '.cat-subnav button').forEach(function (b) {
      b.addEventListener('click', function () {
        b.parentElement.querySelectorAll('button').forEach(function (x) { x.classList.remove('is-active'); });
        b.classList.add('is-active');
      });
    });
  }

  function initAll() {
    document.querySelectorAll('.nsfpulso-scope').forEach(initAldia);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
  } else {
    initAll();
  }

  if (window.elementorFrontend && window.elementorFrontend.hooks) {
    window.elementorFrontend.hooks.addAction('frontend/element_ready/global', initAll);
    window.elementorFrontend.hooks.addAction('frontend/element_ready/nsfpulso_header_news.default', initAll);
  }
})();
