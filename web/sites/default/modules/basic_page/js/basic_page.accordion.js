/**
 * @file
 * Accordion functionality for FAQ sections.
 */

(function (Drupal, once) {
  'use strict';

  /**
   * Initialize accordion functionality.
   */
  function initAccordion(context) {
    const accordions = once('basic-page-accordion', '.accordion-item', context);
    
    accordions.forEach(function(item) {
      const button = item.querySelector('button');
      const content = item.querySelector('.accordion-content');
      
      if (button && content) {
        button.addEventListener('click', function(e) {
          e.preventDefault();
          
          // Toggle current item
          const isOpen = item.classList.contains('open');
          
          // Close all items
          accordions.forEach(function(otherItem) {
            otherItem.classList.remove('open');
            const otherContent = otherItem.querySelector('.accordion-content');
            if (otherContent) {
              otherContent.style.display = 'none';
            }
          });
          
          // Open clicked item if it wasn't open
          if (!isOpen) {
            item.classList.add('open');
            content.style.display = 'block';
          }
        });
        
        // Initially hide content
        content.style.display = 'none';
      }
    });
  }

  /**
   * Initialize FAQ accordion functionality.
   */
  function initFaqAccordion(context) {
    const faqButtons = once('basic-page-faq', '.faq-question', context);
    
    faqButtons.forEach(function(button, index) {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        
        const content = this.nextElementSibling;
        const iconPlus = this.querySelector('.faq-icon-plus');
        const iconX = this.querySelector('.faq-icon-x');
        
        // Check if currently open by checking display style
        const isCurrentlyOpen = content && content.style.display === 'block';
        
        // Close all OTHER FAQs (not this one)
        faqButtons.forEach(function(otherButton) {
          if (otherButton !== button) {
            const otherContent = otherButton.nextElementSibling;
            const otherIconPlus = otherButton.querySelector('.faq-icon-plus');
            const otherIconX = otherButton.querySelector('.faq-icon-x');
            if (otherContent) {
              otherContent.style.display = 'none';
            }
            otherButton.setAttribute('aria-expanded', 'false');
            // Show + icon, hide X icon
            if (otherIconPlus) otherIconPlus.classList.remove('hidden');
            if (otherIconX) otherIconX.classList.add('hidden');
          }
        });
        
        // Toggle current FAQ
        if (isCurrentlyOpen) {
          // Close it
          content.style.display = 'none';
          this.setAttribute('aria-expanded', 'false');
          // Show + icon, hide X icon
          if (iconPlus) iconPlus.classList.remove('hidden');
          if (iconX) iconX.classList.add('hidden');
        } else {
          // Open it
          content.style.display = 'block';
          this.setAttribute('aria-expanded', 'true');
          // Hide + icon, show X icon
          if (iconPlus) iconPlus.classList.add('hidden');
          if (iconX) iconX.classList.remove('hidden');
        }
      });
    });
  }

  /**
   * Behavior to initialize accordion.
   */
  Drupal.behaviors.basicPageAccordion = {
    attach: function (context, settings) {
      initAccordion(context);
      initFaqAccordion(context);
    }
  };

})(Drupal, once);
