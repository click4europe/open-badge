/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./js/OrganizationsList.js":
/*!*********************************!*\
  !*** ./js/OrganizationsList.js ***!
  \*********************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ OrganizationsList)
/* harmony export */ });
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _regenerator() { /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */ var e, t, r = "function" == typeof Symbol ? Symbol : {}, n = r.iterator || "@@iterator", o = r.toStringTag || "@@toStringTag"; function i(r, n, o, i) { var c = n && n.prototype instanceof Generator ? n : Generator, u = Object.create(c.prototype); return _regeneratorDefine2(u, "_invoke", function (r, n, o) { var i, c, u, f = 0, p = o || [], y = !1, G = { p: 0, n: 0, v: e, a: d, f: d.bind(e, 4), d: function d(t, r) { return i = t, c = 0, u = e, G.n = r, a; } }; function d(r, n) { for (c = r, u = n, t = 0; !y && f && !o && t < p.length; t++) { var o, i = p[t], d = G.p, l = i[2]; r > 3 ? (o = l === n) && (u = i[(c = i[4]) ? 5 : (c = 3, 3)], i[4] = i[5] = e) : i[0] <= d && ((o = r < 2 && d < i[1]) ? (c = 0, G.v = n, G.n = i[1]) : d < l && (o = r < 3 || i[0] > n || n > l) && (i[4] = r, i[5] = n, G.n = l, c = 0)); } if (o || r > 1) return a; throw y = !0, n; } return function (o, p, l) { if (f > 1) throw TypeError("Generator is already running"); for (y && 1 === p && d(p, l), c = p, u = l; (t = c < 2 ? e : u) || !y;) { i || (c ? c < 3 ? (c > 1 && (G.n = -1), d(c, u)) : G.n = u : G.v = u); try { if (f = 2, i) { if (c || (o = "next"), t = i[o]) { if (!(t = t.call(i, u))) throw TypeError("iterator result is not an object"); if (!t.done) return t; u = t.value, c < 2 && (c = 0); } else 1 === c && (t = i["return"]) && t.call(i), c < 2 && (u = TypeError("The iterator does not provide a '" + o + "' method"), c = 1); i = e; } else if ((t = (y = G.n < 0) ? u : r.call(n, G)) !== a) break; } catch (t) { i = e, c = 1, u = t; } finally { f = 1; } } return { value: t, done: y }; }; }(r, o, i), !0), u; } var a = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} t = Object.getPrototypeOf; var c = [][n] ? t(t([][n]())) : (_regeneratorDefine2(t = {}, n, function () { return this; }), t), u = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(c); function f(e) { return Object.setPrototypeOf ? Object.setPrototypeOf(e, GeneratorFunctionPrototype) : (e.__proto__ = GeneratorFunctionPrototype, _regeneratorDefine2(e, o, "GeneratorFunction")), e.prototype = Object.create(u), e; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, _regeneratorDefine2(u, "constructor", GeneratorFunctionPrototype), _regeneratorDefine2(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = "GeneratorFunction", _regeneratorDefine2(GeneratorFunctionPrototype, o, "GeneratorFunction"), _regeneratorDefine2(u), _regeneratorDefine2(u, o, "Generator"), _regeneratorDefine2(u, n, function () { return this; }), _regeneratorDefine2(u, "toString", function () { return "[object Generator]"; }), (_regenerator = function _regenerator() { return { w: i, m: f }; })(); }
function _regeneratorDefine2(e, r, n, t) { var i = Object.defineProperty; try { i({}, "", {}); } catch (e) { i = 0; } _regeneratorDefine2 = function _regeneratorDefine(e, r, n, t) { function o(r, n) { _regeneratorDefine2(e, r, function (e) { return this._invoke(r, n, e); }); } r ? i ? i(e, r, { value: n, enumerable: !t, configurable: !t, writable: !t }) : e[r] = n : (o("next", 0), o("throw", 1), o("return", 2)); }, _regeneratorDefine2(e, r, n, t); }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _defineProperty(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }
function _classCallCheck(a, n) { if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function"); }
function _defineProperties(e, r) { for (var t = 0; t < r.length; t++) { var o = r[t]; o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o); } }
function _createClass(e, r, t) { return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", { writable: !1 }), e; }
function _defineProperty(e, r, t) { return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, { value: t, enumerable: !0, configurable: !0, writable: !0 }) : e[r] = t, e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
/**
 * Renders a paginated grid of organization logos fetched from the
 * Open Badge public API.
 *
 * Usage:
 *   new OrganizationsList('companies-pages', {
 *     paginationId: 'companies-pagination',
 *   });
 *
 * Options can also be set as data attributes on the container, e.g.
 *   <div id="companies-pages" data-api-url="..." data-pagination-id="...">
 */
var OrganizationsList = /*#__PURE__*/function () {
  function OrganizationsList(elementId) {
    var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
    _classCallCheck(this, OrganizationsList);
    this.container = document.getElementById(elementId);
    if (!this.container) {
      return;
    }
    var dataset = this.container.dataset;
    this.options = _objectSpread(_objectSpread(_objectSpread(_objectSpread(_objectSpread({}, OrganizationsList.defaults), dataset.apiUrl && {
      apiUrl: dataset.apiUrl
    }), dataset.paginationId && {
      paginationId: dataset.paginationId
    }), options), {}, {
      labels: _objectSpread(_objectSpread({}, OrganizationsList.defaults.labels), options.labels)
    });
    this.pagination = this.options.paginationId ? document.getElementById(this.options.paginationId) : null;
    this.currentPage = 1;
    this.lastPage = 1;
    this.cache = new Map();
    this.requestId = 0;
    this.goToPage(1, false);
  }
  return _createClass(OrganizationsList, [{
    key: "goToPage",
    value: function () {
      var _goToPage = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee(page) {
        var scroll,
          requestId,
          _result$meta$current_,
          _result$meta,
          _result$meta$last_pag,
          _result$meta2,
          _result$data,
          result,
          _args = arguments,
          _t;
        return _regenerator().w(function (_context) {
          while (1) switch (_context.p = _context.n) {
            case 0:
              scroll = _args.length > 1 && _args[1] !== undefined ? _args[1] : this.options.scrollOnPageChange;
              requestId = ++this.requestId;
              this.setLoading(true);
              _context.p = 1;
              _context.n = 2;
              return this.fetchPage(page);
            case 2:
              result = _context.v;
              if (!(requestId !== this.requestId)) {
                _context.n = 3;
                break;
              }
              return _context.a(2);
            case 3:
              this.currentPage = (_result$meta$current_ = (_result$meta = result.meta) === null || _result$meta === void 0 ? void 0 : _result$meta.current_page) !== null && _result$meta$current_ !== void 0 ? _result$meta$current_ : page;
              this.lastPage = (_result$meta$last_pag = (_result$meta2 = result.meta) === null || _result$meta2 === void 0 ? void 0 : _result$meta2.last_page) !== null && _result$meta$last_pag !== void 0 ? _result$meta$last_pag : 1;
              this.renderLogos((_result$data = result.data) !== null && _result$data !== void 0 ? _result$data : []);
              this.renderPagination();
              if (scroll) {
                this.container.scrollIntoView({
                  behavior: 'smooth',
                  block: 'start'
                });
              }
              _context.n = 5;
              break;
            case 4:
              _context.p = 4;
              _t = _context.v;
              if (requestId === this.requestId) {
                console.error('OrganizationsList:', _t);
                this.renderMessage(this.options.labels.error);
              }
            case 5:
              _context.p = 5;
              if (requestId === this.requestId) {
                this.setLoading(false);
              }
              return _context.f(5);
            case 6:
              return _context.a(2);
          }
        }, _callee, this, [[1, 4, 5, 6]]);
      }));
      function goToPage(_x) {
        return _goToPage.apply(this, arguments);
      }
      return goToPage;
    }()
  }, {
    key: "fetchPage",
    value: function () {
      var _fetchPage = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee2(page) {
        var url, response, json;
        return _regenerator().w(function (_context2) {
          while (1) switch (_context2.n) {
            case 0:
              if (!this.cache.has(page)) {
                _context2.n = 1;
                break;
              }
              return _context2.a(2, this.cache.get(page));
            case 1:
              url = new URL(this.options.apiUrl);
              url.searchParams.set('page', page);
              _context2.n = 2;
              return fetch(url, {
                headers: {
                  Accept: 'application/json'
                }
              });
            case 2:
              response = _context2.v;
              if (response.ok) {
                _context2.n = 3;
                break;
              }
              throw new Error("HTTP ".concat(response.status));
            case 3:
              _context2.n = 4;
              return response.json();
            case 4:
              json = _context2.v;
              this.cache.set(page, json);
              return _context2.a(2, json);
          }
        }, _callee2, this);
      }));
      function fetchPage(_x2) {
        return _fetchPage.apply(this, arguments);
      }
      return fetchPage;
    }()
  }, {
    key: "setLoading",
    value: function setLoading(isLoading) {
      this.container.setAttribute('aria-busy', String(isLoading));
      this.container.classList.toggle('opacity-50', isLoading);
      if (isLoading && !this.container.hasChildNodes()) {
        this.renderMessage(this.options.labels.loading);
      }
    }
  }, {
    key: "renderMessage",
    value: function renderMessage(text) {
      var p = document.createElement('p');
      p.className = 'text-center text-slate-500 py-8';
      p.textContent = text;
      this.container.replaceChildren(p);
      if (this.pagination) {
        this.pagination.replaceChildren();
      }
    }
  }, {
    key: "renderLogos",
    value: function renderLogos(organizations) {
      if (organizations.length === 0) {
        this.renderMessage(this.options.labels.empty);
        return;
      }
      var grid = document.createElement('div');
      grid.className = 'image-logo-showcase';
      organizations.forEach(function (org) {
        var _org$name, _org$name2;
        if (!org.logo_path) {
          return;
        }
        var img = document.createElement('img');
        img.className = 'logo-customer';
        img.src = org.logo_path;
        img.alt = (_org$name = org.name) !== null && _org$name !== void 0 ? _org$name : '';
        img.title = (_org$name2 = org.name) !== null && _org$name2 !== void 0 ? _org$name2 : '';
        img.loading = 'lazy';
        img.decoding = 'async';
        var wrap = document.createElement('div');
        wrap.className = 'wrapper-logo-customer';
        wrap.appendChild(img);
        grid.appendChild(wrap);

        /*
        per ora visualizzo solo le immagini
        */

        // if (this.options.linkLogos && org.public_url) {
        //   const link = document.createElement('a');
        //   link.href = org.public_url;
        //   link.target = '_blank';
        //   link.rel = 'noopener';
        //   link.appendChild(img);
        //   grid.appendChild(link);
        // } else {

        //}
      });
      this.container.replaceChildren(grid);
    }
  }, {
    key: "renderPagination",
    value: function renderPagination() {
      if (!this.pagination) {
        return;
      }
      if (this.lastPage <= 1) {
        this.pagination.replaceChildren();
        return;
      }
      var labels = this.options.labels;
      var wrapper = document.createElement('div');
      wrapper.className = 'flex items-center justify-center gap-3';
      wrapper.appendChild(this.createNavButton('prev', labels.prev, this.currentPage - 1, this.currentPage === 1));
      var middle = document.createElement('div');
      middle.className = 'flex items-center gap-2';

      /*
      const indicator = document.createElement('div');
      indicator.className = 'hidden sm:flex items-center gap-1 px-3 py-1 text-sm';
      indicator.innerHTML = `
        <span class="text-slate-500">${labels.page}</span>
        <span class="font-semibold text-[#0891b2]">${this.currentPage}</span>
        <span class="text-slate-500">${labels.of}</span>
        <span class="font-semibold text-slate-700">${this.lastPage}</span>`;
      middle.appendChild(indicator);
      */

      var pages = document.createElement('div');
      pages.className = 'flex items-center gap-1.5';
      for (var i = 1; i <= this.lastPage; i++) {
        pages.appendChild(this.createPageButton(i));
      }
      middle.appendChild(pages);
      wrapper.appendChild(middle);
      wrapper.appendChild(this.createNavButton('next', labels.next, this.currentPage + 1, this.currentPage === this.lastPage));
      this.pagination.replaceChildren(wrapper);
    }
  }, {
    key: "createPageButton",
    value: function createPageButton(page) {
      var _this = this;
      var isActive = page === this.currentPage;
      var button = document.createElement('button');
      button.type = 'button';
      button.textContent = page;
      button.className = 'flex-shrink-0 w-10 h-10 min-w-[2.5rem] rounded-xl font-semibold text-sm transform transition-all duration-300 hover:scale-110 ' + (isActive ? 'bg-[#0891b2] text-white shadow-md' : 'bg-white border-2 border-slate-200 text-slate-600 hover:border-[#0891b2] hover:text-[#0891b2] hover:shadow-md');
      if (isActive) {
        button.setAttribute('aria-current', 'page');
      }
      button.addEventListener('click', function () {
        if (!isActive) {
          _this.goToPage(page);
        }
      });
      return button;
    }
  }, {
    key: "createNavButton",
    value: function createNavButton(direction, label, targetPage, disabled) {
      var _this2 = this;
      var arrow = direction === 'prev' ? 'M15.75 19.5L8.25 12l7.5-7.5' : 'M8.25 4.5L15.75 12l-7.5 7.5';
      var icon = "<svg class=\"w-4 h-4\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2.5\" d=\"".concat(arrow, "\"></path></svg>");
      var text = "<span class=\"hidden sm:inline\">".concat(label, "</span>");
      var button = document.createElement('button');
      button.type = 'button';
      button.disabled = disabled;
      button.setAttribute('aria-label', label);
      button.className = 'px-4 py-2 rounded-full bg-white border-2 border-slate-200 text-slate-600 font-medium transition-all duration-300 hover:border-[#0891b2] hover:text-[#0891b2] hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed';
      button.innerHTML = "<span class=\"flex items-center gap-2\">".concat(direction === 'prev' ? icon + text : text + icon, "</span>");
      button.addEventListener('click', function () {
        return _this2.goToPage(targetPage);
      });
      return button;
    }
  }]);
}();
_defineProperty(OrganizationsList, "defaults", {
  apiUrl: 'https://app.open-badge.eu/api/v1/public/organizations',
  // ID of the element that receives the pagination controls (optional).
  paginationId: null,
  // Open the organization public page when clicking a logo.
  linkLogos: true,
  // Scroll back to the container top when changing page.
  scrollOnPageChange: true,
  labels: {
    prev: 'Precedente',
    next: 'Successiva',
    page: 'Pagina',
    of: 'di',
    loading: 'Caricamento...',
    error: 'Impossibile caricare le organizzazioni.',
    empty: 'Nessuna organizzazione trovata.'
  }
});


/***/ }),

/***/ "./js/app.js":
/*!*******************!*\
  !*** ./js/app.js ***!
  \*******************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _OrganizationsList_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./OrganizationsList.js */ "./js/OrganizationsList.js");

(function (once, Drupal, drupalSettings) {
  "use strict";

  Drupal.behaviors.edkSito = {
    attach: function attach(context, drupalSettings) {
      //var elements;
      once('mySito', 'html', context).forEach(function () {
        // ========== SCROLL TO TOP BUTTON ==========
        (function initScrollToTop() {
          var scrollThreshold = 300;
          var btn = document.createElement('button');
          btn.id = 'scroll-to-top';
          btn.type = 'button';
          btn.setAttribute('aria-label', 'Scroll to top');
          btn.innerHTML = '<svg style="width:20px;height:20px;margin:auto;display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>';
          Object.assign(btn.style, {
            position: 'fixed',
            bottom: '24px',
            right: '24px',
            zIndex: '9999',
            width: '48px',
            height: '48px',
            borderRadius: '50%',
            background: '#004f59',
            color: '#fff',
            border: 'none',
            cursor: 'pointer',
            boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
            opacity: '0',
            visibility: 'hidden',
            transform: 'translateY(16px)',
            transition: 'all 0.3s ease'
          });
          document.body.appendChild(btn);
          function update() {
            if (window.scrollY > scrollThreshold) {
              btn.style.opacity = '1';
              btn.style.visibility = 'visible';
              btn.style.transform = 'translateY(0)';
            } else {
              btn.style.opacity = '0';
              btn.style.visibility = 'hidden';
              btn.style.transform = 'translateY(16px)';
            }
          }
          btn.addEventListener('click', function () {
            window.scrollTo({
              top: 0,
              behavior: 'smooth'
            });
          });
          btn.addEventListener('mouseenter', function () {
            btn.style.transform = 'translateY(0) scale(1.1)';
            btn.style.background = '#003d47';
          });
          btn.addEventListener('mouseleave', function () {
            btn.style.transform = window.scrollY > scrollThreshold ? 'translateY(0)' : 'translateY(16px)';
            btn.style.background = '#004f59';
          });
          window.addEventListener('scroll', update);
        })();
        // ========== END SCROLL TO TOP BUTTON ==========

        // Footer year replacement (was inline on open-badge-home.html)
        var yearSpan = document.getElementById('year');
        if (yearSpan) {
          yearSpan.textContent = new Date().getFullYear();
        }

        // Mobile menu toggle + global UI interactions (from scripts.js)
        var mobileMenuButton = document.getElementById('mobile-menu-button');
        var mobileMenu = document.getElementById('mobile-menu');

        // Generic mega dropdown support (dynamic menu items)
        var header = document.querySelector('header');
        var allMegaToggles = document.querySelectorAll('[data-mega-toggle]');
        var allMegaPanels = document.querySelectorAll('.mega-dropdown');
        function closeAllMegaPanels() {
          allMegaPanels.forEach(function (panel) {
            panel.classList.add('hidden');
            panel.classList.remove('md:block');
          });
        }
        if (mobileMenuButton && mobileMenu) {
          mobileMenuButton.addEventListener('click', function () {
            mobileMenu.classList.toggle('hidden');
            closeAllMegaPanels();
          });
        }

        // Toggle mega dropdown on desktop (click)
        allMegaToggles.forEach(function (toggle) {
          var panelId = toggle.getAttribute('data-mega-toggle');
          var panel = document.getElementById(panelId);
          if (!panel) return;
          toggle.addEventListener('click', function (e) {
            if (this.dataset.skipScroll === 'true') {
              e.preventDefault();
            }
            var isHidden = panel.classList.contains('hidden');
            closeAllMegaPanels();
            if (isHidden) {
              panel.classList.remove('hidden');
              panel.classList.add('md:block');
            }
          });
        });

        // Close mega dropdown when clicking outside header
        document.addEventListener('click', function (e) {
          if (!header) return;
          if (!header.contains(e.target)) {
            closeAllMegaPanels();
          }
        });

        // Smooth scrolling for anchor links (except mega-toggle links that opt-out)
        document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
          anchor.addEventListener('click', function (e) {
            var skipScroll = this.dataset && this.dataset.skipScroll === 'true';
            if (skipScroll) {
              return; // handled by dedicated logic (e.g., mega dropdown)
            }
            e.preventDefault();
            var targetId = this.getAttribute('href');
            var targetElement = document.querySelector(targetId);
            if (targetElement) {
              // Close mobile menu if open
              if (mobileMenu) {
                mobileMenu.classList.add('hidden');
              }

              // Close mega panels on navigation
              closeAllMegaPanels();

              // Scroll to the target
              window.scrollTo({
                top: targetElement.offsetTop,
                behavior: 'smooth'
              });
            }
          });
        });

        // Tabs (data-ob-tabs) from scripts.js
        var tabsContainers = document.querySelectorAll('[data-ob-tabs]');
        tabsContainers.forEach(function (container) {
          var tabButtons = container.querySelectorAll('.ob-tab');
          var panels = container.querySelectorAll('[data-ob-panel]');
          var images = container.querySelectorAll('[data-ob-image]');
          function activateTab(key) {
            tabButtons.forEach(function (button) {
              var isActive = button.getAttribute('data-ob-tab') === key;
              if (isActive) {
                button.classList.add('bg-[#fdece2]', 'text-slate-900', 'border-t', 'border-l', 'border-r', 'border-slate-200', '-mb-px');
                button.classList.remove('bg-transparent', 'text-slate-600', 'border-b', 'border-transparent');
              } else {
                button.classList.remove('bg-[#fdece2]', 'text-slate-900', 'border-t', 'border-l', 'border-r', 'border-slate-200', '-mb-px');
                button.classList.add('bg-transparent', 'text-slate-600', 'border-b', 'border-transparent');
              }
            });
            panels.forEach(function (panel) {
              var match = panel.getAttribute('data-ob-panel') === key;
              panel.classList.toggle('hidden', !match);
            });
            images.forEach(function (image) {
              var match = image.getAttribute('data-ob-image') === key;
              image.classList.toggle('hidden', !match);
            });
          }
          tabButtons.forEach(function (button) {
            button.addEventListener('click', function () {
              var key = this.getAttribute('data-ob-tab');
              if (!key) return;
              activateTab(key);
            });
          });
          var firstTab = tabButtons[0];
          if (firstTab) {
            var initialKey = firstTab.getAttribute('data-ob-tab');
            if (initialKey) {
              activateTab(initialKey);
            }
          }
        });

        // Steps (data-ob-steps) from scripts.js
        var stepsContainers = document.querySelectorAll('[data-ob-steps]');
        stepsContainers.forEach(function (container) {
          var stepButtons = container.querySelectorAll('[data-ob-step]');
          var panels = container.querySelectorAll('[data-ob-step-panel]');
          var images = container.querySelectorAll('[data-ob-step-image]');
          var videos = container.querySelectorAll('[data-ob-step-video]');
          function activateStep(key) {
            stepButtons.forEach(function (button) {
              var isActive = button.getAttribute('data-ob-step') === key;
              if (isActive) {
                button.classList.add('font-semibold', 'text-[#1d4ed8]');
                button.classList.remove('text-slate-800');
              } else {
                button.classList.remove('font-semibold', 'text-[#1d4ed8]');
                button.classList.add('text-slate-800');
              }
            });
            panels.forEach(function (panel) {
              var match = panel.getAttribute('data-ob-step-panel') === key;
              panel.classList.toggle('hidden', !match);
            });
            images.forEach(function (image) {
              var match = image.getAttribute('data-ob-step-image') === key;
              image.classList.toggle('hidden', !match);
            });
            videos.forEach(function (videoContainer) {
              var match = videoContainer.getAttribute('data-ob-step-video') === key;
              videoContainer.classList.toggle('hidden', !match);
              var video = videoContainer.querySelector('video');
              if (video) {
                if (match) {
                  video.currentTime = 0;
                  video.play();
                } else {
                  video.pause();
                }
              }
            });
          }
          stepButtons.forEach(function (button) {
            button.addEventListener('click', function () {
              var key = this.getAttribute('data-ob-step');
              if (!key) return;
              activateStep(key);
            });
          });
          var firstStep = stepButtons[0];
          if (firstStep) {
            var initialKey = firstStep.getAttribute('data-ob-step');
            if (initialKey) {
              activateStep(initialKey);
            }
          }
        });

        // Partner Carousel
        var carousel = document.getElementById('partner-carousel');
        var prevBtn = document.getElementById('carousel-prev');
        var nextBtn = document.getElementById('carousel-next');
        if (carousel && prevBtn && nextBtn) {
          var getItemsPerView = function getItemsPerView() {
            if (window.innerWidth >= 640) return 4;
            return 2;
          };
          var updateCarousel = function updateCarousel() {
            var smooth = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;
            if (isTransitioning) return;
            var itemsPerView = getItemsPerView();
            var maxIndex = Math.max(0, totalItems - itemsPerView);
            currentIndex = Math.min(currentIndex, maxIndex);
            var itemWidth = items[0].offsetWidth;
            var gap = 12;
            var offset = -(currentIndex * (itemWidth + gap));
            if (smooth) {
              isTransitioning = true;
              carousel.style.transition = 'transform 0.5s ease-in-out';
              setTimeout(function () {
                isTransitioning = false;
              }, 500);
            } else {
              carousel.style.transition = 'none';
            }
            carousel.style.transform = "translateX(".concat(offset, "px)");
            if (currentIndex === 0) {
              prevBtn.classList.add('opacity-50', 'pointer-events-none');
            } else {
              prevBtn.classList.remove('opacity-50', 'pointer-events-none');
            }
            if (currentIndex >= maxIndex) {
              nextBtn.classList.add('opacity-50', 'pointer-events-none');
            } else {
              nextBtn.classList.remove('opacity-50', 'pointer-events-none');
            }
          };
          var nextSlide = function nextSlide() {
            var itemsPerView = getItemsPerView();
            var maxIndex = Math.max(0, totalItems - itemsPerView);
            if (currentIndex < maxIndex) {
              currentIndex++;
            } else {
              currentIndex = 0;
            }
            updateCarousel();
          };
          var prevSlide = function prevSlide() {
            if (currentIndex > 0) {
              currentIndex--;
            } else {
              var itemsPerView = getItemsPerView();
              var maxIndex = Math.max(0, totalItems - itemsPerView);
              currentIndex = maxIndex;
            }
            updateCarousel();
          };
          var resetAutoSlide = function resetAutoSlide() {
            stopAutoSlide();
            startAutoSlide();
          };
          var startAutoSlide = function startAutoSlide() {
            stopAutoSlide();
            autoSlideInterval = setInterval(nextSlide, 4000);
          };
          var stopAutoSlide = function stopAutoSlide() {
            if (autoSlideInterval) {
              clearInterval(autoSlideInterval);
              autoSlideInterval = null;
            }
          };
          var currentIndex = 0;
          var items = carousel.children;
          var totalItems = items.length;
          var autoSlideInterval;
          var isTransitioning = false;
          prevBtn.addEventListener('click', function () {
            prevSlide();
            resetAutoSlide();
          });
          nextBtn.addEventListener('click', function () {
            nextSlide();
            resetAutoSlide();
          });
          carousel.addEventListener('mouseenter', stopAutoSlide);
          carousel.addEventListener('mouseleave', startAutoSlide);
          window.addEventListener('resize', function () {
            updateCarousel(false);
          });
          updateCarousel(false);
          startAutoSlide();
        }

        // ========== DRUPAL ADMIN TOOLBAR POSITIONING ==========
        function updateNavbarPosition() {
          var header = document.querySelector('.site-header');
          if (!header) return;
          var body = document.body;
          var toolbarHeight = 0;

          // Check if body has toolbar classes (most reliable method)
          if (body.classList.contains('toolbar-fixed')) {
            toolbarHeight = 39; // Standard Drupal toolbar height

            // Check if tray is open (horizontal)
            if (body.classList.contains('toolbar-horizontal') && body.classList.contains('toolbar-tray-open')) {
              toolbarHeight = 80; // Toolbar + tray height
            }
          }
          header.style.top = toolbarHeight + 'px';
        }

        // Update on load with delay for admin toolbar to initialize
        setTimeout(updateNavbarPosition, 100);
        setTimeout(updateNavbarPosition, 500);

        // Update when toolbar changes via Drupal behaviors
        if (window.Drupal && window.Drupal.behaviors) {
          Drupal.behaviors.navbarPosition = {
            attach: function attach(context, settings) {
              setTimeout(updateNavbarPosition, 50);
            }
          };
        }

        // Update on window resize
        window.addEventListener('resize', updateNavbarPosition);

        // ========== MOBILE MENU ACCORDIONS (generic) ==========
        document.querySelectorAll('.mobile-menu-toggle').forEach(function (toggle) {
          toggle.addEventListener('click', function () {
            var dropdown = this.nextElementSibling;
            var arrow = this.querySelector('.mobile-menu-arrow');
            if (dropdown && dropdown.classList.contains('mobile-menu-dropdown')) {
              dropdown.classList.toggle('hidden');
              if (arrow) {
                arrow.classList.toggle('rotate-180');
              }
            }
          });
        });

        // ========== MEGA DROPDOWN HOVER FUNCTIONALITY (generic) ==========
        allMegaToggles.forEach(function (toggle) {
          var panelId = toggle.getAttribute('data-mega-toggle');
          var panel = document.getElementById(panelId);
          if (!panel) return;
          toggle.addEventListener('mouseenter', function () {
            closeAllMegaPanels();
            panel.classList.remove('hidden');
          });
          toggle.addEventListener('mouseleave', function () {
            setTimeout(function () {
              if (!panel.matches(':hover')) {
                panel.classList.add('hidden');
              }
            }, 100);
          });
          panel.addEventListener('mouseleave', function () {
            panel.classList.add('hidden');
          });
        });

        // ========== VANTAGGI TAB FUNCTIONALITY ==========
        var vantaggiTabButtons = document.querySelectorAll('.ob-tab');
        var vantaggiTabPanels = document.querySelectorAll('.ob-tab-panel');
        if (vantaggiTabButtons.length > 0 && vantaggiTabPanels.length > 0) {
          vantaggiTabButtons.forEach(function (button, index) {
            button.addEventListener('click', function () {
              // Remove active styles from all tabs
              vantaggiTabButtons.forEach(function (btn) {
                btn.classList.remove('bg-[#fdece2]', 'text-slate-900', 'border-t-2', 'border-t-blue-600');
                btn.classList.add('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
              });

              // Hide all panels
              vantaggiTabPanels.forEach(function (panel) {
                panel.classList.add('hidden');
              });

              // Add active styles to clicked tab
              button.classList.remove('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
              button.classList.add('bg-[#fdece2]', 'text-slate-900', 'border-t-2', 'border-t-blue-600');

              // Show corresponding panel
              var panelId = button.getAttribute('data-ob-tab');
              var targetPanel = document.querySelector("[data-ob-panel=\"".concat(panelId, "\"]"));
              if (targetPanel) {
                targetPanel.classList.remove('hidden');
              }
            });
          });
        }

        // ========== FAQ ACCORDION FUNCTIONALITY ==========
        var faqButtons = document.querySelectorAll('.faq-button');
        if (faqButtons.length > 0) {
          faqButtons.forEach(function (button) {
            button.addEventListener('click', function () {
              var content = this.nextElementSibling;
              var icon = this.querySelector('svg');
              content.classList.toggle('hidden');
              icon.classList.toggle('rotate-180');
              faqButtons.forEach(function (otherButton) {
                if (otherButton !== button) {
                  var otherContent = otherButton.nextElementSibling;
                  var otherIcon = otherButton.querySelector('svg');
                  otherContent.classList.add('hidden');
                  otherIcon.classList.remove('rotate-180');
                }
              });
            });
          });
        }

        // ========== PRICING TOGGLE & DROPDOWN ==========
        var monthlyToggle = document.getElementById('monthly-toggle');
        var yearlyToggle = document.getElementById('yearly-toggle');
        var monthlyPricing = document.getElementById('monthly-pricing');
        var yearlyPricing = document.getElementById('yearly-pricing');
        if (monthlyToggle && yearlyToggle) {
          monthlyToggle.addEventListener('click', function () {
            monthlyToggle.classList.add('bg-blue-600', 'text-white');
            monthlyToggle.classList.remove('text-slate-600');
            yearlyToggle.classList.remove('bg-blue-600', 'text-white');
            yearlyToggle.classList.add('text-slate-600');
            if (monthlyPricing) monthlyPricing.classList.remove('hidden');
            if (yearlyPricing) yearlyPricing.classList.add('hidden');
          });
          yearlyToggle.addEventListener('click', function () {
            yearlyToggle.classList.add('bg-blue-600', 'text-white');
            yearlyToggle.classList.remove('text-slate-600');
            monthlyToggle.classList.remove('bg-blue-600', 'text-white');
            monthlyToggle.classList.add('text-slate-600');
            if (yearlyPricing) yearlyPricing.classList.remove('hidden');
            if (monthlyPricing) monthlyPricing.classList.add('hidden');
          });
        }

        // Pricing Dropdown Toggle with Animation
        var dropdownToggles = document.querySelectorAll('.pricing-dropdown-toggle');
        dropdownToggles.forEach(function (toggle) {
          toggle.addEventListener('click', function () {
            var targetId = this.getAttribute('data-target');
            var targetContent = document.getElementById(targetId);
            var icon = this.querySelector('.dropdown-icon');
            var card = this.closest('.pricing-card');
            if (targetContent) {
              var isExpanding = targetContent.classList.contains('hidden');
              if (isExpanding) {
                targetContent.classList.remove('hidden');
                setTimeout(function () {
                  return targetContent.classList.add('show');
                }, 10);
                if (card) card.classList.add('card-expanded');
                if (icon) icon.style.transform = 'rotate(180deg)';
              } else {
                targetContent.classList.remove('show');
                if (card) card.classList.remove('card-expanded');
                if (icon) icon.style.transform = 'rotate(0deg)';
                setTimeout(function () {
                  return targetContent.classList.add('hidden');
                }, 400);
              }
            }
          });
        });

        // ========== ORGANIZATIONS LIST ==========
        new _OrganizationsList_js__WEBPACK_IMPORTED_MODULE_0__["default"]('companies-pages', {
          paginationId: 'companies-pagination'
        });

        // ========== COSA SONO FAQ ITEMS ==========
        var faqItems = document.querySelectorAll('.faq-item');
        var faqImage = document.getElementById('faq-image');
        if (faqItems.length > 0 && faqImage) {
          faqItems.forEach(function (item) {
            item.addEventListener('click', function () {
              var content = this.querySelector('.faq-content');
              var faqImageUrl = this.getAttribute('data-faq-image');
              var faqImageAlt = this.getAttribute('data-faq-alt');

              // Close all other FAQ items
              faqItems.forEach(function (otherItem) {
                if (otherItem !== item) {
                  var otherContent = otherItem.querySelector('.faq-content');
                  if (otherContent) otherContent.classList.add('hidden');
                  var otherH3 = otherItem.querySelector('h3');
                  if (otherH3) otherH3.classList.remove('text-blue-600');
                }
              });

              // Toggle current FAQ item
              if (content) content.classList.toggle('hidden');
              var h3 = this.querySelector('h3');
              if (h3) h3.classList.toggle('text-blue-600');

              // Update image if FAQ is open and has an image URL
              if (content && !content.classList.contains('hidden') && faqImageUrl) {
                faqImage.src = faqImageUrl;
                if (faqImageAlt) {
                  faqImage.alt = faqImageAlt;
                }
              }
            });
          });

          // Auto-open first item on load
          if (faqItems.length > 0) {
            faqItems[0].click();
          }
        }

        // ========== VIDEO MODAL ==========
        var modal = document.getElementById('video-modal');
        var openBtn = document.getElementById('open-video-modal');
        var closeBtn = document.getElementById('close-video-modal');
        var backdrop = document.getElementById('video-modal-backdrop');
        var iframe = document.getElementById('vimeo-player');
        if (modal && openBtn && closeBtn && backdrop && iframe) {
          var openModal = function openModal(e) {
            e.preventDefault();
            var videoUrl = openBtn.getAttribute('data-video-url');
            if (videoUrl) {
              // Add autoplay parameter if it's a Vimeo URL
              var separator = videoUrl.includes('?') ? '&' : '?';
              var finalUrl = videoUrl.includes('autoplay=') ? videoUrl : videoUrl + separator + 'autoplay=1';
              iframe.src = finalUrl;
            }
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
          };
          var closeModal = function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            iframe.src = '';
            document.body.style.overflow = '';
          };
          openBtn.addEventListener('click', openModal);
          closeBtn.addEventListener('click', closeModal);
          backdrop.addEventListener('click', closeModal);
          document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
              closeModal();
            }
          });
        }
      });
    }
  };

  // Lazy loading for sections with IntersectionObserver
  Drupal.behaviors.lazyLoadSections = {
    attach: function attach(context, settings) {
      if (typeof IntersectionObserver === 'undefined') return;
      var lazyElements = once('lazy-sections', '[data-lazy-load]', context);
      if (!lazyElements.length) return;
      var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('lazy-loaded');
            entry.target.classList.remove('lazy-hidden');
            observer.unobserve(entry.target);
          }
        });
      }, {
        rootMargin: '100px 0px',
        threshold: 0.1
      });
      lazyElements.forEach(function (el) {
        el.classList.add('lazy-hidden');
        observer.observe(el);
      });
    }
  };

  // Lazy load images that come into viewport (for dynamically loaded content)
  Drupal.behaviors.lazyLoadImages = {
    attach: function attach(context, settings) {
      if (typeof IntersectionObserver === 'undefined') return;
      var lazyImages = once('lazy-images', 'img[data-src]', context);
      if (!lazyImages.length) return;
      var imageObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            var img = entry.target;
            if (img.dataset.src) {
              img.src = img.dataset.src;
              img.removeAttribute('data-src');
            }
            if (img.dataset.srcset) {
              img.srcset = img.dataset.srcset;
              img.removeAttribute('data-srcset');
            }
            img.classList.add('lazy-loaded');
            imageObserver.unobserve(img);
          }
        });
      }, {
        rootMargin: '50px 0px',
        threshold: 0.01
      });
      lazyImages.forEach(function (img) {
        return imageObserver.observe(img);
      });
    }
  };

  // FAQ Page - Accordion functionality
  Drupal.behaviors.faqPage = {
    attach: function attach(context, settings) {
      once('faq-page', '.faq-item', context).forEach(function (item) {
        var questionBtn = item.querySelector('.faq-question');
        var answerDiv = item.querySelector('.faq-answer');
        var icon = item.querySelector('.faq-icon');
        if (questionBtn && answerDiv) {
          questionBtn.addEventListener('click', function () {
            var isExpanded = questionBtn.getAttribute('aria-expanded') === 'true';
            if (isExpanded) {
              // Close
              answerDiv.classList.remove('block');
              answerDiv.classList.add('hidden');
              questionBtn.setAttribute('aria-expanded', 'false');
              icon.style.transform = '';
            } else {
              // Open
              answerDiv.classList.remove('hidden');
              answerDiv.classList.add('block');
              questionBtn.setAttribute('aria-expanded', 'true');
              icon.style.transform = 'rotate(180deg)';
            }
          });
        }
      });
    }
  };
})(once, Drupal, drupalSettings);

/***/ }),

/***/ "./src/input.css":
/*!***********************!*\
  !*** ./src/input.css ***!
  \***********************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/js/main": 0,
/******/ 			"css/style": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunktailwind"] = self["webpackChunktailwind"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["css/style"], () => (__webpack_require__("./js/app.js")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["css/style"], () => (__webpack_require__("./src/input.css")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;