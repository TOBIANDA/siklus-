@extends('layouts.app')

@section('content')
<div class="live-search-page">

    {{-- ===== HEADER ===== --}}
    <div class="ls-header">
        <h1 class="ls-title">🔍 Cari Buku</h1>
        <p class="ls-subtitle">Temukan buku berdasarkan judul, penulis, atau kategori</p>
    </div>

    {{-- ===== SEARCH BAR ===== --}}
    <div class="ls-search-wrap">
        <div class="ls-search-box" id="searchBox">
            <span class="ls-search-icon">🔍</span>
            <input
                type="text"
                id="liveSearchInput"
                class="ls-search-input"
                placeholder="Ketik judul, penulis, atau kategori..."
                autocomplete="off"
                autofocus
                value="{{ request('q') }}"
            >
            <button class="ls-clear-btn" id="clearBtn" title="Hapus pencarian">✕</button>
        </div>

        {{-- Filters --}}
        <div class="ls-filters" id="filtersRow">
            <select id="categoryFilter" class="ls-select">
                <option value="">Semua Kategori</option>
            </select>
            <select id="sortFilter" class="ls-select">
                <option value="popular">Terpopuler</option>
                <option value="rating">Rating Tertinggi</option>
                <option value="newest">Terbaru</option>
            </select>
        </div>
    </div>

    {{-- ===== STATUS BAR ===== --}}
    <div class="ls-status" id="statusBar">
        <span id="statusText" class="ls-status-text"></span>
        <div class="ls-spinner" id="spinner" style="display:none;"></div>
    </div>

    {{-- ===== RESULTS ===== --}}
    <div class="ls-results-wrap">
        {{-- Empty state (initial) --}}
        <div class="ls-empty-state" id="emptyState">
            <div class="ls-empty-icon">📚</div>
            <p class="ls-empty-title">Mulai pencarian</p>
            <p class="ls-empty-sub">Ketik minimal 1 karakter untuk mulai mencari buku</p>
        </div>

        {{-- No results state --}}
        <div class="ls-no-results" id="noResults" style="display:none;">
            <div class="ls-empty-icon">😕</div>
            <p class="ls-empty-title" id="noResultsTitle">Tidak ada buku yang ditemukan</p>
            <p class="ls-empty-sub">Coba kata kunci lain atau ubah filter</p>
        </div>

        {{-- Book grid --}}
        <div class="book-grid ls-book-grid" id="resultsGrid" style="display:none; flex-wrap:wrap;"></div>
    </div>

</div>

{{-- ===== STYLES ===== --}}
<style>
/* === Page Layout === */
.live-search-page {
    padding: 28px 32px;
    max-width: 1200px;
    margin: 0 auto;
}

/* === Header === */
.ls-header { margin-bottom: 28px; }
.ls-title { font-size: 24px; font-weight: 800; margin: 0 0 6px; color: var(--dark, #111); }
.ls-subtitle { font-size: 14px; color: var(--gray, #6B7280); margin: 0; }

/* === Search Box === */
.ls-search-wrap { display: flex; flex-direction: column; gap: 12px; margin-bottom: 16px; }

.ls-search-box {
    display: flex;
    align-items: center;
    background: #fff;
    border: 2px solid #E5E7EB;
    border-radius: 16px;
    padding: 0 16px;
    gap: 10px;
    transition: border-color .2s, box-shadow .2s;
}
.ls-search-box:focus-within {
    border-color: var(--blue, #2563EB);
    box-shadow: 0 0 0 4px rgba(37,99,235,.12);
}

.ls-search-icon { font-size: 18px; flex-shrink: 0; opacity: .5; }

.ls-search-input {
    flex: 1;
    border: none;
    outline: none;
    font-size: 17px;
    font-family: inherit;
    padding: 16px 0;
    background: transparent;
    color: var(--dark, #111);
}
.ls-search-input::placeholder { color: #9CA3AF; }

.ls-clear-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 14px;
    color: #9CA3AF;
    padding: 4px 8px;
    border-radius: 8px;
    transition: background .15s, color .15s;
    display: none;
}
.ls-clear-btn:hover { background: #F3F4F6; color: #374151; }
.ls-clear-btn.visible { display: block; }

/* === Filters Row === */
.ls-filters { display: flex; gap: 10px; flex-wrap: wrap; }

.ls-select {
    padding: 8px 14px;
    border: 1.5px solid #E5E7EB;
    border-radius: 10px;
    font-size: 13px;
    font-family: inherit;
    background: #fff;
    color: var(--dark, #111);
    cursor: pointer;
    transition: border-color .2s;
    outline: none;
    min-width: 150px;
}
.ls-select:focus { border-color: var(--blue, #2563EB); }

/* === Status Bar === */
.ls-status {
    display: flex;
    align-items: center;
    gap: 10px;
    min-height: 28px;
    margin-bottom: 20px;
}
.ls-status-text {
    font-size: 13px;
    color: var(--gray, #6B7280);
    font-weight: 500;
}

/* === Spinner === */
.ls-spinner {
    width: 18px;
    height: 18px;
    border: 2.5px solid #E5E7EB;
    border-top-color: var(--blue, #2563EB);
    border-radius: 50%;
    animation: lsSpin .6s linear infinite;
    flex-shrink: 0;
}
@keyframes lsSpin { to { transform: rotate(360deg); } }

/* === Empty States === */
.ls-empty-state, .ls-no-results {
    text-align: center;
    padding: 80px 0;
    color: var(--gray, #6B7280);
    animation: lsFadeIn .3s ease;
}
.ls-empty-icon { font-size: 64px; margin-bottom: 16px; }
.ls-empty-title { font-size: 17px; font-weight: 700; margin: 0 0 6px; color: var(--dark, #111); }
.ls-empty-sub { font-size: 14px; margin: 0; }

/* === Book Grid (extends existing .book-grid) === */
.ls-book-grid {
    animation: lsFadeIn .3s ease;
}

/* === Book Card Entrance === */
@keyframes lsFadeIn {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Book cards from the existing stylesheet get their styles from book-card class */
.ls-book-grid .book-card {
    animation: lsFadeIn .3s ease both;
}

/* === Dark Theme Support === */
[data-theme="dark"] .ls-search-box {
    background: #1F2937;
    border-color: #374151;
}
[data-theme="dark"] .ls-search-input { color: #F9FAFB; }
[data-theme="dark"] .ls-select { background: #1F2937; border-color: #374151; color: #F9FAFB; }
[data-theme="dark"] .ls-title { color: #F9FAFB; }
[data-theme="dark"] .ls-empty-title { color: #F9FAFB; }
[data-theme="dark"] .ls-clear-btn:hover { background: #374151; color: #F9FAFB; }
</style>

{{-- ===== AJAX LIVE SEARCH SCRIPT ===== --}}
<script>
(function () {
    'use strict';

    /* ---- DOM refs ---- */
    const input      = document.getElementById('liveSearchInput');
    const clearBtn   = document.getElementById('clearBtn');
    const spinner    = document.getElementById('spinner');
    const statusText = document.getElementById('statusText');
    const emptyState = document.getElementById('emptyState');
    const noResults  = document.getElementById('noResults');
    const noResultsTitle = document.getElementById('noResultsTitle');
    const grid       = document.getElementById('resultsGrid');
    const catFilter  = document.getElementById('categoryFilter');
    const sortFilter = document.getElementById('sortFilter');

    /* ---- State ---- */
    let debounceTimer = null;
    let currentQuery  = '';
    let categoriesLoaded = false;

    /* ---- Live Search API endpoint ---- */
    const SEARCH_URL = '{{ route("books.live-search") }}';

    /* ---- Helpers ---- */
    function showSpinner()  { spinner.style.display = 'block'; }
    function hideSpinner()  { spinner.style.display = 'none'; }

    function showSection(which) {
        // which = 'empty' | 'noResults' | 'grid'
        emptyState.style.display = which === 'empty'     ? '' : 'none';
        noResults.style.display  = which === 'noResults' ? '' : 'none';
        grid.style.display       = which === 'grid'      ? '' : 'none';
    }

    function buildCardHTML(book, idx) {
        const statusClass = book.book_status === 'available'
            ? 'status-available'
            : book.book_status === 'on_loan' ? 'status-on-loan' : 'status-returned';

        const delay = Math.min(idx * 50, 400); // staggered entrance

        return `
        <div class="book-card" style="animation-delay:${delay}ms">
            <a href="${book.url}" style="text-decoration:none;color:inherit;" class="book-link">
                <img src="${book.cover_url}"
                     alt="${escHtml(book.title)}"
                     class="cover"
                     onerror="this.style.background='linear-gradient(135deg,#1a3a5c,#2563EB)';this.removeAttribute('src')">
                <div class="card-body">
                    <div class="card-title">${escHtml(book.title)}</div>
                    <div class="card-author">${escHtml(book.author)}</div>
                    <div class="card-footer">
                        <span style="font-size:11px;color:var(--blue);font-weight:600;">${escHtml(book.category)}</span>
                        <span style="font-size:11px;color:var(--yellow, #F59E0B);font-weight:600;">⭐ ${book.rating}</span>
                    </div>
                    <div style="margin-top:6px;">
                        <span class="${statusClass}" style="font-size:10px;font-weight:600;padding:2px 8px;border-radius:20px;
                              background:${book.book_status === 'available' ? 'rgba(16,185,129,.12)' : 'rgba(239,68,68,.12)'};
                              color:${book.book_status === 'available' ? '#10B981' : '#EF4444'};">
                            ${escHtml(book.status_label)}
                        </span>
                    </div>
                </div>
            </a>
        </div>`;
    }

    function escHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function populateCategories(categories) {
        if (categoriesLoaded) return;
        categoriesLoaded = true;
        const current = catFilter.value;
        // keep the first "all" option
        while (catFilter.options.length > 1) catFilter.remove(1);
        categories.forEach(cat => {
            const opt = document.createElement('option');
            opt.value = cat;
            opt.textContent = cat;
            if (cat === current) opt.selected = true;
            catFilter.appendChild(opt);
        });
    }

    /* ---- Core fetch function ---- */
    async function doSearch() {
        const q        = input.value.trim();
        const category = catFilter.value;
        const sort     = sortFilter.value;

        currentQuery = q;

        // Show loading
        showSpinner();

        // Build URL
        const url = new URL(SEARCH_URL, window.location.origin);
        url.searchParams.set('q', q);
        if (category) url.searchParams.set('category', category);
        url.searchParams.set('sort', sort);

        try {
            const response = await fetch(url.toString(), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) throw new Error('Network error: ' + response.status);

            const data = await response.json();

            hideSpinner();

            // Populate categories once
            if (data.categories && data.categories.length) {
                populateCategories(data.categories);
            }

            // Render results
            if (data.total === 0) {
                if (q === '' && category === '') {
                    statusText.textContent = '';
                    showSection('empty');
                } else {
                    noResultsTitle.textContent = `Buku "${q}" tidak ditemukan`;
                    statusText.textContent = '0 buku ditemukan';
                    showSection('noResults');
                }
            } else {
                const label = q
                    ? `${data.total} buku ditemukan untuk "${q}"`
                    : `${data.total} buku tersedia`;
                statusText.textContent = label;

                grid.innerHTML = data.results
                    .map((book, idx) => buildCardHTML(book, idx))
                    .join('');

                showSection('grid');
            }

        } catch (err) {
            hideSpinner();
            statusText.textContent = '⚠ Gagal memuat hasil. Coba lagi.';
            console.error('Live search error:', err);
        }
    }

    /* ---- Debounced input ---- */
    function onInput() {
        const val = input.value.trim();
        clearBtn.classList.toggle('visible', val.length > 0);

        clearTimeout(debounceTimer);
        // Show status feedback immediately
        if (val.length > 0) {
            statusText.textContent = 'Mencari...';
            showSpinner();
        }
        debounceTimer = setTimeout(doSearch, 350); // 350ms debounce
    }

    /* ---- Clear button ---- */
    clearBtn.addEventListener('click', function () {
        input.value = '';
        clearBtn.classList.remove('visible');
        statusText.textContent = '';
        hideSpinner();
        clearTimeout(debounceTimer);
        showSection('empty');
        input.focus();
        // Reset category filter too
        catFilter.value = '';
    });

    /* ---- Filters change ---- */
    catFilter.addEventListener('change', function () {
        clearTimeout(debounceTimer);
        doSearch();
    });
    sortFilter.addEventListener('change', function () {
        clearTimeout(debounceTimer);
        doSearch();
    });

    /* ---- Wire up input ---- */
    input.addEventListener('input', onInput);

    /* ---- Keyboard: ESC to clear ---- */
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') clearBtn.click();
    });

    /* ---- Init: if URL has ?q=... pre-fill and search ---- */
    const urlQ = new URLSearchParams(window.location.search).get('q') || '';
    if (urlQ) {
        input.value = urlQ;
        clearBtn.classList.add('visible');
        doSearch();
    } else {
        // Load categories for the dropdown even before user types
        doSearch();          // fetch with empty query to populate category list
        showSection('empty'); // but still show empty state
    }

})();
</script>

@endsection