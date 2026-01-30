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

        // Partner Carousel
        const carousel = document.getElementById('partner-carousel');
        const prevBtn = document.getElementById('carousel-prev');
        const nextBtn = document.getElementById('carousel-next');

        if (carousel && prevBtn && nextBtn) {
          let currentIndex = 0;
          const items = carousel.children;
          const totalItems = items.length;
          let autoSlideInterval;
          let isTransitioning = false;

          function getItemsPerView() {
            if (window.innerWidth >= 640) return 4;
            return 2;
          }

          function updateCarousel(smooth = true) {
            if (isTransitioning) return;
            
            const itemsPerView = getItemsPerView();
            const maxIndex = Math.max(0, totalItems - itemsPerView);
            currentIndex = Math.min(currentIndex, maxIndex);

            const itemWidth = items[0].offsetWidth;
            const gap = 12;
            const offset = -(currentIndex * (itemWidth + gap));
            
            if (smooth) {
              isTransitioning = true;
              carousel.style.transition = 'transform 0.5s ease-in-out';
              setTimeout(() => {
                isTransitioning = false;
              }, 500);
            } else {
              carousel.style.transition = 'none';
            }
            
            carousel.style.transform = `translateX(${offset}px)`;

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
          }

          function nextSlide() {
            const itemsPerView = getItemsPerView();
            const maxIndex = Math.max(0, totalItems - itemsPerView);
            if (currentIndex < maxIndex) {
              currentIndex++;
            } else {
              currentIndex = 0;
            }
            updateCarousel();
          }

          function prevSlide() {
            if (currentIndex > 0) {
              currentIndex--;
            } else {
              const itemsPerView = getItemsPerView();
              const maxIndex = Math.max(0, totalItems - itemsPerView);
              currentIndex = maxIndex;
            }
            updateCarousel();
          }

          function resetAutoSlide() {
            stopAutoSlide();
            startAutoSlide();
          }

          function startAutoSlide() {
            stopAutoSlide();
            autoSlideInterval = setInterval(nextSlide, 4000);
          }

          function stopAutoSlide() {
            if (autoSlideInterval) {
              clearInterval(autoSlideInterval);
              autoSlideInterval = null;
            }
          }

          prevBtn.addEventListener('click', function() {
            prevSlide();
            resetAutoSlide();
          });

          nextBtn.addEventListener('click', function() {
            nextSlide();
            resetAutoSlide();
          });

          carousel.addEventListener('mouseenter', stopAutoSlide);
          carousel.addEventListener('mouseleave', startAutoSlide);

          window.addEventListener('resize', function() {
            updateCarousel(false);
          });

          updateCarousel(false);
          startAutoSlide();
        }

        // ========== DRUPAL ADMIN TOOLBAR POSITIONING ==========
        function updateNavbarPosition() {
          const header = document.querySelector('header');
          const adminToolbar = document.querySelector('#toolbar-administration');
          
          // Check for admin toolbar with different selectors and methods
          let toolbarHeight = 0;
          
          if (adminToolbar) {
            toolbarHeight = adminToolbar.offsetHeight;
          } else {
            // Try other possible admin toolbar selectors
            const toolbarBar = document.querySelector('.toolbar-bar');
            if (toolbarBar) {
              toolbarHeight = toolbarBar.offsetHeight;
            }
          }
          
          // Also check if body has admin toolbar class
          const body = document.body;
          if (body && (body.classList.contains('toolbar-horizontal') || body.classList.contains('toolbar-tray-open'))) {
            // Admin toolbar is likely present, try to get its height
            const anyToolbar = document.querySelector('[role="toolbar"], .toolbar, #toolbar');
            if (anyToolbar) {
              toolbarHeight = anyToolbar.offsetHeight;
            }
          }
          
          if (header) {
            header.style.top = toolbarHeight + 'px';
          }
        }

        // Update on load with delay for admin toolbar
        setTimeout(updateNavbarPosition, 100);

        // Update when toolbar changes
        if (window.Drupal && window.Drupal.behaviors) {
          Drupal.behaviors.navbarPosition = {
            attach: function(context, settings) {
              updateNavbarPosition();
            }
          };
        }

        // Update on window resize
        window.addEventListener('resize', updateNavbarPosition);

        // ========== MEGA DROPDOWN HOVER FUNCTIONALITY ==========
        const soluzioniLink = document.querySelector('[data-mega-toggle="caratteristiche"]');
        const megaDropdown = document.getElementById('caratteristiche-mega');
        
        if (soluzioniLink && megaDropdown) {
          // Show dropdown on hover
          soluzioniLink.addEventListener('mouseenter', function() {
            megaDropdown.classList.remove('hidden');
          });
          
          // Hide dropdown when mouse leaves the link or dropdown
          function hideDropdown() {
            megaDropdown.classList.add('hidden');
          }
          
          soluzioniLink.addEventListener('mouseleave', function(e) {
            // Give time to move mouse to dropdown
            setTimeout(function() {
              if (!megaDropdown.matches(':hover')) {
                hideDropdown();
              }
            }, 100);
          });
          
          megaDropdown.addEventListener('mouseleave', hideDropdown);
          
          // Hide on click outside
          document.addEventListener('click', function(e) {
            if (!soluzioniLink.contains(e.target) && !megaDropdown.contains(e.target)) {
              hideDropdown();
            }
          });
        }

        // ========== VANTAGGI TAB FUNCTIONALITY ==========
        const vantaggiTabButtons = document.querySelectorAll('.ob-tab');
        const vantaggiTabPanels = document.querySelectorAll('.ob-tab-panel');
        
        if (vantaggiTabButtons.length > 0 && vantaggiTabPanels.length > 0) {
          vantaggiTabButtons.forEach((button, index) => {
            button.addEventListener('click', function() {
              // Remove active styles from all tabs
              vantaggiTabButtons.forEach(btn => {
                btn.classList.remove('bg-[#fdece2]', 'text-slate-900', 'border-t-2', 'border-t-blue-600');
                btn.classList.add('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
              });
              
              // Hide all panels
              vantaggiTabPanels.forEach(panel => {
                panel.classList.add('hidden');
              });
              
              // Add active styles to clicked tab
              button.classList.remove('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
              button.classList.add('bg-[#fdece2]', 'text-slate-900', 'border-t-2', 'border-t-blue-600');
              
              // Show corresponding panel
              const panelId = button.getAttribute('data-ob-tab');
              const targetPanel = document.querySelector(`[data-ob-panel="${panelId}"]`);
              if (targetPanel) {
                targetPanel.classList.remove('hidden');
              }
            });
          });
        }

        // ========== FAQ ACCORDION FUNCTIONALITY ==========
        const faqButtons = document.querySelectorAll('.faq-button');
        if (faqButtons.length > 0) {
          faqButtons.forEach(button => {
            button.addEventListener('click', function() {
              const content = this.nextElementSibling;
              const icon = this.querySelector('svg');
              content.classList.toggle('hidden');
              icon.classList.toggle('rotate-180');
              faqButtons.forEach(otherButton => {
                if (otherButton !== button) {
                  const otherContent = otherButton.nextElementSibling;
                  const otherIcon = otherButton.querySelector('svg');
                  otherContent.classList.add('hidden');
                  otherIcon.classList.remove('rotate-180');
                }
              });
            });
          });
        }

      });
    }
  };

})(once, Drupal, drupalSettings);
