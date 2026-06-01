(function ($) {
  'use strict';

  var OVERLAY_ID = 'offcanvas-overlay';

  function isMobileWidth() {
    return window.matchMedia('(max-width: 1200px)').matches;
  }

  function getOverlay() {
    var $overlay = $('#' + OVERLAY_ID);

    if (!$overlay.length) {
      $overlay = $('<div/>', { id: OVERLAY_ID, class: 'offcanvas-overlay' });
      $('body').append($overlay);
    }

    return $overlay;
  }

  function syncOverlay() {
    var $overlay = getOverlay();
    var showOverlay = isMobileWidth() && $('body').hasClass('offcanvas-active');

    if (showOverlay) {
      $overlay.addClass('show');
    } else {
      $overlay.removeClass('show');
    }
  }

  function setSidebarState(open) {
    var $sidebar = $('#left-sidebar');

    if (!$sidebar.length) {
      return;
    }

    if (isMobileWidth()) {
      $('body').toggleClass('offcanvas-active', !!open);
      $sidebar.toggleClass('evamo-open', !!open);
      $sidebar.css({
        left: '0px',
        transform: open ? 'translateX(0)' : 'translateX(-104%)',
        visibility: open ? 'visible' : 'hidden',
        pointerEvents: open ? 'auto' : 'none'
      });
    } else {
      $sidebar.removeClass('evamo-open');
      $sidebar.css({
        left: '',
        transform: '',
        visibility: '',
        pointerEvents: ''
      });
      if (!open) {
        $('body').removeClass('offcanvas-active');
      }
    }

    syncOverlay();
  }

  function toggleSidebarFromButton(e) {
    if (e && e.preventDefault) {
      e.preventDefault();
      e.stopPropagation();
    }

    var isOpen = $('body').hasClass('offcanvas-active');
    setSidebarState(!isOpen);
    return false;
  }

  function closeAllDropdowns() {
    $('.dropdown, .btn-group').removeClass('show open');
    $('.dropdown-menu').removeClass('show').hide();
    $('.dropdown-toggle').attr('aria-expanded', 'false');
  }

  function closeMobileSidebar() {
    if (isMobileWidth()) {
      setSidebarState(false);
    }
  }

  function bindOffcanvasToggle() {
    $(document)
      .off('click.layoutFallbackOffcanvas', '.btn-toggle-offcanvas')
      .on('click.layoutFallbackOffcanvas', '.btn-toggle-offcanvas', function (e) {
        toggleSidebarFromButton(e);
      });

    $(document)
      .off('click.layoutFallbackOutside')
      .on('click.layoutFallbackOutside', function (e) {
        var $target = $(e.target);
        var isInsideSidebar = $target.closest('#left-sidebar').length > 0;
        var isToggleButton = $target.closest('.btn-toggle-offcanvas').length > 0;

        if (!isInsideSidebar && !isToggleButton) {
          closeMobileSidebar();
        }
      });

    $(document)
      .off('click.layoutFallbackOverlay', '#' + OVERLAY_ID)
      .on('click.layoutFallbackOverlay', '#' + OVERLAY_ID, function () {
        closeMobileSidebar();
      });

    $(document)
      .off('click.layoutFallbackSidebarClose', '.evamo-sidebar-close')
      .on('click.layoutFallbackSidebarClose', '.evamo-sidebar-close', function (e) {
        e.preventDefault();
        e.stopPropagation();
        closeMobileSidebar();
      });

    $(window)
      .off('resize.layoutFallbackOverlay')
      .on('resize.layoutFallbackOverlay', function () {
        if (!isMobileWidth()) {
          setSidebarState(false);
        } else {
          var shouldBeOpen = $('body').hasClass('offcanvas-active');
          setSidebarState(shouldBeOpen);
        }
      });
  }

  function bindNavbarDropdown() {
    // Use fallback only when Bootstrap's dropdown plugin/data-api is unavailable.
    if ($.fn.dropdown && $.fn.dropdown.Constructor) {
      return;
    }

    $(document)
      .off('click.layoutFallbackDropdown', '[data-toggle="dropdown"], .dropdown-toggle')
      .on('click.layoutFallbackDropdown', '[data-toggle="dropdown"], .dropdown-toggle', function (e) {
        var $toggle = $(this);
        var $dropdown = $toggle.closest('.dropdown');
        var $group = $toggle.closest('.btn-group');
        var $menu = $toggle.siblings('.dropdown-menu').first();

        if (!$menu.length) {
          $menu = $dropdown.children('.dropdown-menu').first();
        }

        if (!$menu.length) {
          $menu = $dropdown.find('.dropdown-menu').first();
        }

        if (!$menu.length) {
          return;
        }

        e.preventDefault();
        e.stopPropagation();
        if (e.stopImmediatePropagation) {
          e.stopImmediatePropagation();
        }

        var isOpen = $dropdown.hasClass('show') || $group.hasClass('show') || $toggle.attr('aria-expanded') === 'true';
        closeAllDropdowns();

        if (!isOpen) {
          $dropdown.addClass('show');
          $group.addClass('show open');
          $toggle.attr('aria-expanded', 'true');
          $menu.addClass('show').show();
        }
      });

    $(document)
      .off('click.layoutFallbackDropdownClose')
      .on('click.layoutFallbackDropdownClose', function (e) {
        if ($(e.target).closest('.dropdown, .btn-group').length === 0) {
          closeAllDropdowns();
        }
      });
  }

  function isDarkModeEnabled() {
    return document.documentElement.classList.contains('evamo-dark');
  }

  function updateThemeToggleUi() {
    var darkEnabled = isDarkModeEnabled();

    $('[data-theme-toggle]').each(function () {
      var $btn = $(this);
      var $icon = $btn.find('i').first();

      $btn.attr('aria-pressed', darkEnabled ? 'true' : 'false');
      $btn.attr('aria-label', darkEnabled ? 'Switch to light mode' : 'Switch to dark mode');
      $btn.attr('title', darkEnabled ? 'Switch to light mode' : 'Switch to dark mode');
      $btn.toggleClass('is-dark', darkEnabled);
      $btn.attr('data-theme', darkEnabled ? 'dark' : 'light');
    });
  }

  function setTheme(mode) {
    var useDark = mode === 'dark';
    document.documentElement.classList.toggle('evamo-dark', useDark);

    try {
      localStorage.setItem('evamo-theme', useDark ? 'dark' : 'light');
    } catch (e) {}

    updateThemeToggleUi();
  }

  function bindThemeToggle() {
    updateThemeToggleUi();

    $(document)
      .off('click.layoutFallbackThemeToggle', '[data-theme-toggle]')
      .on('click.layoutFallbackThemeToggle', '[data-theme-toggle]', function (e) {
        e.preventDefault();
        var nextMode = isDarkModeEnabled() ? 'light' : 'dark';
        setTheme(nextMode);
      });
  }

  function disableLegacyMetisMenu() {
    var $menus = $('.sidebar-nav .main-menu, .sidebar-nav .metismenu, .sidebar-nav .evamo-main-menu');

    $menus.each(function () {
      var $menu = $(this);

      // Dispose plugin instance when available (metisMenu v2+), then remove residual handlers/state.
      if (typeof $menu.metisMenu === 'function') {
        try {
          $menu.metisMenu('dispose');
        } catch (err) {
          // Ignore: older builds may not support dispose, we'll still clear handlers below.
        }
      }

      $menu.removeData('metisMenu');
      $menu.off('.metisMenu');

      $menu.find('li').removeClass('mm-active');
      $menu.find('a').removeAttr('aria-expanded');
      $menu.find('ul')
        .removeClass('mm-collapse mm-collapsing collapsing')
        .css({ height: '', overflow: '' });
    });
  }

  function toggleSidebarSubmenu($link, e) {
    var $li = $link.parent('li');
    var $submenu = $li.children('ul');

    if (!$submenu.length) {
      return true;
    }

    if (e && e.preventDefault) {
      e.preventDefault();
      e.stopPropagation();
      if (e.stopImmediatePropagation) {
        e.stopImmediatePropagation();
      }
    }

    var isActive = $li.hasClass('active');

    $li
      .siblings('li')
      .removeClass('active')
      .children('ul')
      .removeClass('in')
      .stop(true, true)
      .slideUp(150)
      .css({ display: 'none', height: '', overflow: '' });

    $li.siblings('li').children('.has-arrow').attr('aria-expanded', 'false');

    if (isActive) {
      $li.removeClass('active');
      $link.attr('aria-expanded', 'false');
      $submenu
        .removeClass('in')
        .stop(true, true)
        .slideUp(150)
        .css({ display: 'none', height: '', overflow: '' });
    } else {
      $li.addClass('active');
      $link.attr('aria-expanded', 'true');
      $submenu
        .addClass('in')
        .stop(true, true)
        .slideDown(150)
        .css({ display: 'block', height: 'auto', overflow: 'visible' });
    }

    return false;
  }

  function bindSidebarSubmenu() {
    $('.main-menu > li > ul, .metismenu > li > ul, .evamo-main-menu > li > ul').each(function () {
      var $submenu = $(this);

      // Keep legacy metismenu collapse semantics for CSS compatibility.
      if (!$submenu.hasClass('collapse')) {
        $submenu.addClass('collapse');
      }

      if ($submenu.parent('li').hasClass('active')) {
        $submenu.addClass('in').css({ display: 'block', height: 'auto', overflow: 'visible' });
      } else {
        $submenu.removeClass('in').css({ display: 'none', height: '', overflow: '' });
      }
    });

    $('.main-menu .has-arrow, .metismenu .has-arrow, .evamo-main-menu .has-arrow').attr('aria-expanded', 'false');

    $(document)
      .off('click.layoutFallbackSubmenu', '.main-menu .has-arrow, .metismenu .has-arrow, .evamo-main-menu .has-arrow')
      .on('click.layoutFallbackSubmenu', '.main-menu .has-arrow, .metismenu .has-arrow, .evamo-main-menu .has-arrow', function (e) {
        return toggleSidebarSubmenu($(this), e);
      });
  }

  function bindSidebarTabs() {
    $(document)
      .off('click.layoutFallbackSidebarTabs', '[data-sidebar-tab-target]')
      .on('click.layoutFallbackSidebarTabs', '[data-sidebar-tab-target]', function (e) {
        e.preventDefault();

        var $button = $(this);
        var targetSelector = $button.attr('data-sidebar-tab-target');
        var $container = $button.closest('.sidebar-tab-switch-container');

        if (!targetSelector || !$container.length) {
          return;
        }

        $container.find('[data-sidebar-tab-target]').removeClass('active');
        $button.addClass('active');

        $container.find('.sidebar-tab-pane').removeClass('active').hide();
        $container.find(targetSelector).addClass('active').show();
      });
  }

  $(function () {
    window.__evamoToggleSidebar = toggleSidebarFromButton;
    window.__evamoToggleSubmenu = function (linkEl, eventObj) {
      return toggleSidebarSubmenu($(linkEl), eventObj);
    };
    getOverlay();
    disableLegacyMetisMenu();
    bindOffcanvasToggle();
    bindNavbarDropdown();
    bindThemeToggle();
    bindSidebarSubmenu();
    bindSidebarTabs();
    setSidebarState($('body').hasClass('offcanvas-active'));
  });
})(jQuery);