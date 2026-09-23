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
export default class OrganizationsList {
  static defaults = {
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
      empty: 'Nessuna organizzazione trovata.',
    },
  };

  constructor(elementId, options = {}) {
    this.container = document.getElementById(elementId);
    if (!this.container) {
      return;
    }

    const dataset = this.container.dataset;
    this.options = {
      ...OrganizationsList.defaults,
      ...(dataset.apiUrl && { apiUrl: dataset.apiUrl }),
      ...(dataset.paginationId && { paginationId: dataset.paginationId }),
      ...options,
      labels: { ...OrganizationsList.defaults.labels, ...options.labels },
    };

    this.pagination = this.options.paginationId
      ? document.getElementById(this.options.paginationId)
      : null;
    this.currentPage = 1;
    this.lastPage = 1;
    this.cache = new Map();
    this.requestId = 0;

    this.goToPage(1, false);
  }

  async goToPage(page, scroll = this.options.scrollOnPageChange) {
    const requestId = ++this.requestId;
    this.setLoading(true);

    try {
      const result = await this.fetchPage(page);
      // Ignore responses from requests superseded by a newer click.
      if (requestId !== this.requestId) {
        return;
      }
      this.currentPage = result.meta?.current_page ?? page;
      this.lastPage = result.meta?.last_page ?? 1;
      this.renderLogos(result.data ?? []);
      this.renderPagination();
      if (scroll) {
        this.container.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    } catch (error) {
      if (requestId === this.requestId) {
        console.error('OrganizationsList:', error);
        this.renderMessage(this.options.labels.error);
      }
    } finally {
      if (requestId === this.requestId) {
        this.setLoading(false);
      }
    }
  }

  async fetchPage(page) {
    if (this.cache.has(page)) {
      return this.cache.get(page);
    }
    const url = new URL(this.options.apiUrl);
    url.searchParams.set('page', page);

    const response = await fetch(url, { headers: { Accept: 'application/json' } });
    if (!response.ok) {
      throw new Error(`HTTP ${response.status}`);
    }
    const json = await response.json();
    this.cache.set(page, json);
    return json;
  }

  setLoading(isLoading) {
    this.container.setAttribute('aria-busy', String(isLoading));
    this.container.classList.toggle('opacity-50', isLoading);
    if (isLoading && !this.container.hasChildNodes()) {
      this.renderMessage(this.options.labels.loading);
    }
  }

  renderMessage(text) {
    const p = document.createElement('p');
    p.className = 'text-center text-slate-500 py-8';
    p.textContent = text;
    this.container.replaceChildren(p);
    if (this.pagination) {
      this.pagination.replaceChildren();
    }
  }

  renderLogos(organizations) {
    if (organizations.length === 0) {
      this.renderMessage(this.options.labels.empty);
      return;
    }

    const grid = document.createElement('div');
    grid.className = 'image-logo-showcase';

    organizations.forEach((org) => {
      if (!org.logo_path) {
        return;
      }
      const img = document.createElement('img');
      img.className = 'logo-customer';
      img.src = org.logo_path;
      img.alt = org.name ?? '';
      img.title = org.name ?? '';
      img.loading = 'lazy';
      img.decoding = 'async';

      const wrap = document.createElement('div');
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

  renderPagination() {
    if (!this.pagination) {
      return;
    }
    if (this.lastPage <= 1) {
      this.pagination.replaceChildren();
      return;
    }

    const { labels } = this.options;
    const wrapper = document.createElement('div');
    wrapper.className = 'flex items-center justify-center gap-3';

    wrapper.appendChild(this.createNavButton('prev', labels.prev, this.currentPage - 1, this.currentPage === 1));

    const middle = document.createElement('div');
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

    const pages = document.createElement('div');
    pages.className = 'flex items-center gap-1.5';
    for (let i = 1; i <= this.lastPage; i++) {
      pages.appendChild(this.createPageButton(i));
    }
    middle.appendChild(pages);
    wrapper.appendChild(middle);

    wrapper.appendChild(this.createNavButton('next', labels.next, this.currentPage + 1, this.currentPage === this.lastPage));

    this.pagination.replaceChildren(wrapper);
  }

  createPageButton(page) {
    const isActive = page === this.currentPage;
    const button = document.createElement('button');
    button.type = 'button';
    button.textContent = page;
    button.className = 'flex-shrink-0 w-10 h-10 min-w-[2.5rem] rounded-xl font-semibold text-sm transform transition-all duration-300 hover:scale-110 '
      + (isActive
        ? 'bg-[#0891b2] text-white shadow-md'
        : 'bg-white border-2 border-slate-200 text-slate-600 hover:border-[#0891b2] hover:text-[#0891b2] hover:shadow-md');
    if (isActive) {
      button.setAttribute('aria-current', 'page');
    }
    button.addEventListener('click', () => {
      if (!isActive) {
        this.goToPage(page);
      }
    });
    return button;
  }

  createNavButton(direction, label, targetPage, disabled) {
    const arrow = direction === 'prev' ? 'M15.75 19.5L8.25 12l7.5-7.5' : 'M8.25 4.5L15.75 12l-7.5 7.5';
    const icon = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="${arrow}"></path></svg>`;
    const text = `<span class="hidden sm:inline">${label}</span>`;

    const button = document.createElement('button');
    button.type = 'button';
    button.disabled = disabled;
    button.setAttribute('aria-label', label);
    button.className = 'px-4 py-2 rounded-full bg-white border-2 border-slate-200 text-slate-600 font-medium transition-all duration-300 hover:border-[#0891b2] hover:text-[#0891b2] hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed';
    button.innerHTML = `<span class="flex items-center gap-2">${direction === 'prev' ? icon + text : text + icon}</span>`;
    button.addEventListener('click', () => this.goToPage(targetPage));
    return button;
  }
}
