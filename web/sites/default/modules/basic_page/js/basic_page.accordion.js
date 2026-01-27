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
    const faqButtons = once('basic-page-faq', '.faq-button', context);
    
    faqButtons.forEach(function(button) {
      button.addEventListener('click', function() {
        const content = this.nextElementSibling;
        const icon = this.querySelector('svg');
        
        // Toggle content
        content.classList.toggle('hidden');
        
        // Rotate icon
        icon.classList.toggle('rotate-180');
        
        // Close other FAQs
        faqButtons.forEach(function(otherButton) {
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
