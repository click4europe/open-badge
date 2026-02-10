/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./js/app.js":
/*!*******************!*\
  !*** ./js/app.js ***!
  \*******************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
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

        // Mega dropdown elements (used on open-badge-home.html)
        var header = document.querySelector('header');
        var megaToggle = document.querySelector('[data-mega-toggle="caratteristiche"]');
        var megaPanel = document.getElementById('caratteristiche-mega');
        function openMegaPanel() {
          if (!megaPanel) return;
          megaPanel.classList.remove('hidden');
          megaPanel.classList.add('md:block');
        }
        function closeMegaPanel() {
          if (!megaPanel) return;
          megaPanel.classList.add('hidden');
          megaPanel.classList.remove('md:block');
        }
        if (mobileMenuButton && mobileMenu) {
          mobileMenuButton.addEventListener('click', function () {
            mobileMenu.classList.toggle('hidden');

            // Always close mega panel when opening mobile menu
            if (megaPanel) {
              megaPanel.classList.add('hidden');
            }
          });
        }

        // Toggle mega dropdown on desktop
        if (megaToggle && megaPanel) {
          megaToggle.addEventListener('click', function (e) {
            // Prevent smooth scroll when used as mega toggle
            if (this.dataset.skipScroll === 'true') {
              e.preventDefault();
            }
            var isHidden = megaPanel.classList.contains('hidden');
            if (isHidden) {
              openMegaPanel();
            } else {
              closeMegaPanel();
            }
          });
        }

        // Close mega dropdown when clicking outside header
        document.addEventListener('click', function (e) {
          if (!megaPanel || !header) return;
          if (!header.contains(e.target)) {
            closeMegaPanel();
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

              // Close mega panel on navigation
              if (megaPanel) {
                closeMegaPanel();
              }

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
              toolbarHeight = 79; // Toolbar + tray height
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

        // ========== MOBILE SOLUZIONI ACCORDION ==========
        var mobileSoluzioniToggle = document.getElementById('mobile-soluzioni-toggle');
        var mobileSoluzioniDropdown = document.getElementById('mobile-soluzioni-dropdown');
        var mobileSoluzioniArrow = document.getElementById('mobile-soluzioni-arrow');
        if (mobileSoluzioniToggle && mobileSoluzioniDropdown) {
          mobileSoluzioniToggle.addEventListener('click', function () {
            mobileSoluzioniDropdown.classList.toggle('hidden');
            if (mobileSoluzioniArrow) {
              mobileSoluzioniArrow.classList.toggle('rotate-180');
            }
          });
        }

        // ========== MEGA DROPDOWN HOVER FUNCTIONALITY ==========
        var soluzioniLink = document.querySelector('[data-mega-toggle="caratteristiche"]');
        var megaDropdown = document.getElementById('caratteristiche-mega');
        if (soluzioniLink && megaDropdown) {
          // Hide dropdown when mouse leaves the link or dropdown
          var hideDropdown = function hideDropdown() {
            megaDropdown.classList.add('hidden');
          };
          // Show dropdown on hover
          soluzioniLink.addEventListener('mouseenter', function () {
            megaDropdown.classList.remove('hidden');
          });
          soluzioniLink.addEventListener('mouseleave', function (e) {
            // Give time to move mouse to dropdown
            setTimeout(function () {
              if (!megaDropdown.matches(':hover')) {
                hideDropdown();
              }
            }, 100);
          });
          megaDropdown.addEventListener('mouseleave', hideDropdown);

          // Hide on click outside
          document.addEventListener('click', function (e) {
            if (!soluzioniLink.contains(e.target) && !megaDropdown.contains(e.target)) {
              hideDropdown();
            }
          });
        }

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

        // ========== COMPANIES PAGINATION ==========
        var companiesPages = document.querySelectorAll('.companies-page');
        var companyPageButtons = document.querySelectorAll('#companies-pagination .page-btn');
        var companiesPrevBtn = document.getElementById('companies-prev');
        var companiesNextBtn = document.getElementById('companies-next');
        if (companiesPages.length > 0 && companyPageButtons.length > 0) {
          var showCompanyPage = function showCompanyPage(pageNum) {
            currentCompanyPage = pageNum;
            companiesPages.forEach(function (page) {
              page.classList.add('hidden');
              if (parseInt(page.dataset.page) === pageNum) {
                page.classList.remove('hidden');
              }
            });
            companyPageButtons.forEach(function (btn) {
              btn.classList.remove('bg-[#0891b2]', 'text-white', 'shadow-md', 'scale-110');
              btn.classList.add('bg-white', 'border-2', 'border-slate-200', 'text-slate-600');
              if (parseInt(btn.dataset.page) === pageNum) {
                btn.classList.add('bg-[#0891b2]', 'text-white', 'shadow-md');
                btn.classList.remove('bg-white', 'border-2', 'border-slate-200', 'text-slate-600');
              }
            });
            var pageIndicator = document.getElementById('current-page-indicator');
            if (pageIndicator) {
              pageIndicator.textContent = pageNum;
            }
            if (companiesPrevBtn) {
              if (pageNum === 1) {
                companiesPrevBtn.disabled = true;
                companiesPrevBtn.classList.add('opacity-50', 'cursor-not-allowed');
              } else {
                companiesPrevBtn.disabled = false;
                companiesPrevBtn.classList.remove('opacity-50', 'cursor-not-allowed');
              }
            }
            if (companiesNextBtn) {
              if (pageNum === totalCompanyPages) {
                companiesNextBtn.disabled = true;
                companiesNextBtn.classList.add('opacity-50', 'cursor-not-allowed');
              } else {
                companiesNextBtn.disabled = false;
                companiesNextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
              }
            }
          };
          var currentCompanyPage = 1;
          var totalCompanyPages = companiesPages.length;
          companyPageButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
              showCompanyPage(parseInt(this.dataset.page));
            });
          });
          if (companiesPrevBtn) {
            companiesPrevBtn.addEventListener('click', function () {
              if (currentCompanyPage > 1) {
                showCompanyPage(currentCompanyPage - 1);
              }
            });
          }
          if (companiesNextBtn) {
            companiesNextBtn.addEventListener('click', function () {
              if (currentCompanyPage < totalCompanyPages) {
                showCompanyPage(currentCompanyPage + 1);
              }
            });
          }

          // Initialize first page
          showCompanyPage(1);
        }

        // ========== COSA SONO FAQ ITEMS ==========
        var faqItems = document.querySelectorAll('.faq-item');
        var faqImage = document.getElementById('faq-image');
        if (faqItems.length > 0 && faqImage) {
          var imageMap = {
            'mybadges': '/themes/custom/tailwind/images/public/IMG_openbadge/faq-1.png',
            'learning': '/themes/custom/tailwind/images/public/IMG_openbadge/faq-2.webp',
            'earners': '/themes/custom/tailwind/images/public/IMG_openbadge/faq-3.webp'
          };
          faqItems.forEach(function (item) {
            item.addEventListener('click', function () {
              var faqType = this.getAttribute('data-faq');
              var content = this.querySelector('.faq-content');
              faqItems.forEach(function (otherItem) {
                if (otherItem !== item) {
                  var otherContent = otherItem.querySelector('.faq-content');
                  if (otherContent) otherContent.classList.add('hidden');
                  var otherH3 = otherItem.querySelector('h3');
                  if (otherH3) otherH3.classList.remove('text-blue-600');
                }
              });
              if (content) content.classList.toggle('hidden');
              var h3 = this.querySelector('h3');
              if (h3) h3.classList.toggle('text-blue-600');
              if (content && !content.classList.contains('hidden') && imageMap[faqType]) {
                faqImage.src = imageMap[faqType];
              }
            });
          });

          // Auto-open first item on load
          if (faqItems.length > 0) {
            faqItems[0].click();
          }
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