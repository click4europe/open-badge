(function(once, Drupal, drupalSettings) {
  "use strict";

  Drupal.behaviors.edkSito = {
    attach: function (context, drupalSettings) {
      //var elements;
      once('mySito', 'html', context).forEach(function () {

        // ========== SCROLL TO TOP BUTTON ==========
        (function initScrollToTop() {
          const scrollThreshold = 300;
          const btn = document.createElement('button');
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

          btn.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
          });
          btn.addEventListener('mouseenter', function() {
            btn.style.transform = 'translateY(0) scale(1.1)';
            btn.style.background = '#003d47';
          });
          btn.addEventListener('mouseleave', function() {
            btn.style.transform = window.scrollY > scrollThreshold ? 'translateY(0)' : 'translateY(16px)';
            btn.style.background = '#004f59';
          });

          window.addEventListener('scroll', update);
        })();
        // ========== END SCROLL TO TOP BUTTON ==========

        // Footer year replacement (was inline on open-badge-home.html)
        const yearSpan = document.getElementById('year');
        if (yearSpan) {
          yearSpan.textContent = new Date().getFullYear();
        }

        // Mobile menu toggle + global UI interactions (from scripts.js)
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        // Mega dropdown elements (used on open-badge-home.html)
        const header = document.querySelector('header');
        const megaToggle = document.querySelector('[data-mega-toggle="caratteristiche"]');
        const megaPanel = document.getElementById('caratteristiche-mega');

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
          mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');

            // Always close mega panel when opening mobile menu
            if (megaPanel) {
              megaPanel.classList.add('hidden');
            }
          });
        }

        // Toggle mega dropdown on desktop
        if (megaToggle && megaPanel) {
          megaToggle.addEventListener('click', function(e) {
            // Prevent smooth scroll when used as mega toggle
            if (this.dataset.skipScroll === 'true') {
              e.preventDefault();
            }

            const isHidden = megaPanel.classList.contains('hidden');
            if (isHidden) {
              openMegaPanel();
            } else {
              closeMegaPanel();
            }
          });
        }

        // Close mega dropdown when clicking outside header
        document.addEventListener('click', function(e) {
          if (!megaPanel || !header) return;

          if (!header.contains(e.target)) {
            closeMegaPanel();
          }
        });

        // Smooth scrolling for anchor links (except mega-toggle links that opt-out)
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
          anchor.addEventListener('click', function(e) {
            const skipScroll = this.dataset && this.dataset.skipScroll === 'true';
            if (skipScroll) {
              return; // handled by dedicated logic (e.g., mega dropdown)
            }

            e.preventDefault();

            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

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
        const tabsContainers = document.querySelectorAll('[data-ob-tabs]');
        tabsContainers.forEach(container => {
          const tabButtons = container.querySelectorAll('.ob-tab');
          const panels = container.querySelectorAll('[data-ob-panel]');
          const images = container.querySelectorAll('[data-ob-image]');

          function activateTab(key) {
            tabButtons.forEach(button => {
              const isActive = button.getAttribute('data-ob-tab') === key;
              if (isActive) {
                button.classList.add('bg-[#fdece2]', 'text-slate-900', 'border-t', 'border-l', 'border-r', 'border-slate-200', '-mb-px');
                button.classList.remove('bg-transparent', 'text-slate-600', 'border-b', 'border-transparent');
              } else {
                button.classList.remove('bg-[#fdece2]', 'text-slate-900', 'border-t', 'border-l', 'border-r', 'border-slate-200', '-mb-px');
                button.classList.add('bg-transparent', 'text-slate-600', 'border-b', 'border-transparent');
              }
            });

            panels.forEach(panel => {
              const match = panel.getAttribute('data-ob-panel') === key;
              panel.classList.toggle('hidden', !match);
            });

            images.forEach(image => {
              const match = image.getAttribute('data-ob-image') === key;
              image.classList.toggle('hidden', !match);
            });
          }

          tabButtons.forEach(button => {
            button.addEventListener('click', function () {
              const key = this.getAttribute('data-ob-tab');
              if (!key) return;
              activateTab(key);
            });
          });

          const firstTab = tabButtons[0];
          if (firstTab) {
            const initialKey = firstTab.getAttribute('data-ob-tab');
            if (initialKey) {
              activateTab(initialKey);
            }
          }
        });

        // Steps (data-ob-steps) from scripts.js
        const stepsContainers = document.querySelectorAll('[data-ob-steps]');
        stepsContainers.forEach(container => {
          const stepButtons = container.querySelectorAll('[data-ob-step]');
          const panels = container.querySelectorAll('[data-ob-step-panel]');
          const images = container.querySelectorAll('[data-ob-step-image]');
          const videos = container.querySelectorAll('[data-ob-step-video]');

          function activateStep(key) {
            stepButtons.forEach(button => {
              const isActive = button.getAttribute('data-ob-step') === key;
              if (isActive) {
                button.classList.add('font-semibold', 'text-[#1d4ed8]');
                button.classList.remove('text-slate-800');
              } else {
                button.classList.remove('font-semibold', 'text-[#1d4ed8]');
                button.classList.add('text-slate-800');
              }
            });

            panels.forEach(panel => {
              const match = panel.getAttribute('data-ob-step-panel') === key;
              panel.classList.toggle('hidden', !match);
            });

            images.forEach(image => {
              const match = image.getAttribute('data-ob-step-image') === key;
              image.classList.toggle('hidden', !match);
            });

            videos.forEach(videoContainer => {
              const match = videoContainer.getAttribute('data-ob-step-video') === key;
              videoContainer.classList.toggle('hidden', !match);
              const video = videoContainer.querySelector('video');
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

          stepButtons.forEach(button => {
            button.addEventListener('click', function () {
              const key = this.getAttribute('data-ob-step');
              if (!key) return;
              activateStep(key);
            });
          });

          const firstStep = stepButtons[0];
          if (firstStep) {
            const initialKey = firstStep.getAttribute('data-ob-step');
            if (initialKey) {
              activateStep(initialKey);
            }
          }
        });

      });
    }
  };

})(once, Drupal, drupalSettings);
