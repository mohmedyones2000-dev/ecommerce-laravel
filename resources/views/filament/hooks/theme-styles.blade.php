<style>
    /* ==========================================
       VARIABLES
       ========================================== */
    :root {
        /* Brand colors */
        --c-light: #10b981;
        --c-light-hover: #059669;
        --c-dark: #064e3b;
        --c-dark-hover: #065f46;
        --c-rgb-light: 16, 185, 129;
        --c-rgb-dark: 6, 78, 59;

        /* Day mode (default) */
        --topbar-bg: var(--c-light);
        --topbar-bg-hover: rgba(255, 255, 255, 0.15);
        --topbar-text: #ffffff;
        --topbar-text-rgb: 255, 255, 255;

        --search-bg: #ffffff;
        --search-border: #d1d5db;
        --search-text: #000000;
        --search-placeholder: #6b7280;
        --search-focus-ring: rgba(0, 0, 0, 0.08);

        --dropdown-bg: #ffffff;
        --dropdown-text: #1e293b;
        --dropdown-border: rgba(0, 0, 0, 0.08);
        --dropdown-shadow: 0 10px 25px rgba(0, 0, 0, 0.12), 0 4px 8px rgba(0, 0, 0, 0.06);
        --dropdown-header-bg: #f8fafc;
        --dropdown-header-text: #0f172a;
        --dropdown-item-hover-bg: rgba(var(--c-rgb-light), 0.1);
        --dropdown-item-hover-text: var(--c-light-hover);
    }

    .dark {
        --topbar-bg: var(--c-dark);
        --topbar-bg-hover: rgba(255, 255, 255, 0.12);
        --topbar-text: #ffffff;
        --topbar-text-rgb: 255, 255, 255;

        --search-bg: rgba(255, 255, 255, 0.1);
        --search-border: rgba(255, 255, 255, 0.2);
        --search-text: #ffffff;
        --search-placeholder: rgba(255, 255, 255, 0.6);
        --search-focus-ring: rgba(255, 255, 255, 0.12);

        --dropdown-bg: #1f1f23;
        --dropdown-text: #e4e4e7;
        --dropdown-border: #27272a;
        --dropdown-shadow: 0 10px 25px rgba(0, 0, 0, 0.5), 0 4px 8px rgba(0, 0, 0, 0.3);
        --dropdown-header-bg: #18181b;
        --dropdown-header-text: #f4f4f5;
        --dropdown-item-hover-bg: rgba(var(--c-rgb-light), 0.2);
        --dropdown-item-hover-text: #6ee7b7;
    }

    /* ==========================================
       TOPBAR
       ========================================== */
    header.fi-topbar,
    .fi-topbar,
    body .fi-topbar,
    .fi-layout .fi-topbar,
    .fi-topbar>nav,
    .fi-topbar>div {
        background-color: var(--topbar-bg) !important;
        background-image: none !important;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08) !important;
        box-shadow: none !important;
        transition: background-color 0.2s ease !important;
    }

    /* Topbar text - WHITE in both modes */
    .fi-topbar a,
    .fi-topbar button,
    .fi-topbar span,
    .fi-topbar svg,
    .fi-topbar .fi-icon-btn-icon,
    .fi-topbar .fi-icon-btn-label,
    .fi-topbar .fi-topbar-item-label {
        color: var(--topbar-text) !important;
    }

    /* ==========================================
       TOPBAR - BUTTONS
       ========================================== */
    .fi-topbar .fi-icon-btn,
    .fi-topbar .fi-dropdown-trigger,
    .fi-topbar>div>a,
    .fi-topbar>div>button {
        border-radius: 10px !important;
        transition: background-color 0.15s ease !important;
        background-color: transparent !important;
    }

    .fi-topbar .fi-icon-btn:hover,
    .fi-topbar .fi-dropdown-trigger:hover,
    .fi-topbar>div>a:hover,
    .fi-topbar>div>button:hover {
        background-color: var(--topbar-bg-hover) !important;
    }

    .fi-topbar button:focus-visible,
    .fi-topbar a:focus-visible {
        outline: 2px solid rgba(var(--topbar-text-rgb), 0.5) !important;
        outline-offset: 2px !important;
    }

    /* ==========================================
       TOPBAR - USER MENU
       ========================================== */
    .fi-topbar .fi-user-menu-trigger {
        border-radius: 10px !important;
        padding: 0.35rem 0.65rem !important;
        transition: background-color 0.15s ease !important;
        background-color: transparent !important;
    }

    .fi-topbar .fi-user-menu-trigger:hover,
    .fi-topbar .fi-user-menu-trigger[aria-expanded="true"] {
        background-color: var(--topbar-bg-hover) !important;
    }

    .fi-topbar .fi-avatar,
    .fi-topbar .fi-user-avatar {
        border: 2px solid rgba(var(--topbar-text-rgb), 0.5) !important;
    }

    /* ==========================================
       TOPBAR - SIDEBAR TOGGLES
       ========================================== */
    .fi-topbar .fi-topbar-open-sidebar-btn,
    .fi-topbar .fi-topbar-close-sidebar-btn,
    .fi-topbar .fi-topbar-toggle-sidebar-btn {
        border-radius: 10px !important;
    }

    .fi-topbar .fi-topbar-open-sidebar-btn:hover,
    .fi-topbar .fi-topbar-close-sidebar-btn:hover,
    .fi-topbar .fi-topbar-toggle-sidebar-btn:hover {
        background-color: var(--topbar-bg-hover) !important;
    }

    /* ==========================================
       TOPBAR - BADGES
       ========================================== */
    .fi-topbar .fi-badge {
        background-color: #ef4444 !important;
        color: #ffffff !important;
        border: 2px solid var(--topbar-bg) !important;
        font-weight: 700 !important;
    }

    /* ==========================================
       SEARCH INPUT - the only place with black text in day mode
       ========================================== */
    .fi-topbar input[type="search"],
    .fi-topbar input[type="text"],
    .fi-topbar input[type="email"],
    .fi-topbar .fi-input {
        background-color: var(--search-bg) !important;
        border: 1px solid var(--search-border) !important;
        color: var(--search-text) !important;
        -webkit-text-fill-color: var(--search-text) !important;
        caret-color: var(--search-text) !important;
        border-radius: 10px !important;
        transition: all 0.2s ease !important;
        color-scheme: light;
    }

    .dark .fi-topbar input[type="search"],
    .dark .fi-topbar input[type="text"],
    .dark .fi-topbar input[type="email"],
    .dark .fi-topbar .fi-input {
        color-scheme: dark;
    }

    .fi-topbar input::placeholder,
    .fi-topbar .fi-input::placeholder {
        color: var(--search-placeholder) !important;
        -webkit-text-fill-color: var(--search-placeholder) !important;
        opacity: 1 !important;
    }

    .fi-topbar input:focus,
    .fi-topbar .fi-input:focus {
        border-color: var(--c-light-hover) !important;
        outline: none !important;
        box-shadow: 0 0 0 3px var(--search-focus-ring) !important;
    }

    .fi-topbar input:-webkit-autofill,
    .fi-topbar input:-webkit-autofill:hover,
    .fi-topbar input:-webkit-autofill:focus {
        -webkit-text-fill-color: var(--search-text) !important;
        -webkit-box-shadow: 0 0 0 1000px var(--search-bg) inset !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    /* ==========================================
       DROPDOWN PANEL
       ========================================== */
    .fi-topbar .fi-dropdown-panel {
        background-color: var(--dropdown-bg) !important;
        color: var(--dropdown-text) !important;
        border: 1px solid var(--dropdown-border) !important;
        border-radius: 12px !important;
        box-shadow: var(--dropdown-shadow) !important;
        margin-top: 0.5rem !important;
        overflow: hidden !important;
    }

    /* Dropdown header */
    .fi-topbar .fi-dropdown-header {
        background-color: var(--dropdown-header-bg) !important;
        color: var(--dropdown-header-text) !important;
        padding: 0.85rem 1rem !important;
        border-bottom: 1px solid var(--dropdown-border) !important;
    }

    .fi-topbar .fi-dropdown-header span,
    .fi-topbar .fi-dropdown-header p,
    .fi-topbar .fi-dropdown-header div,
    .fi-topbar .fi-dropdown-header label {
        color: var(--dropdown-header-text) !important;
    }

    /* Dropdown items */
    .fi-topbar .fi-dropdown-list-item {
        border-radius: 8px !important;
        margin-inline: 0.35rem;
        margin-block: 2px;
        transition: background-color 0.15s ease !important;
        color: var(--dropdown-text) !important;
        padding: 0.6rem 0.85rem !important;
        background-color: transparent !important;
    }

    .fi-topbar .fi-dropdown-list-item:hover {
        background-color: var(--dropdown-item-hover-bg) !important;
        color: var(--dropdown-item-hover-text) !important;
    }

    .fi-topbar .fi-dropdown-list-item svg {
        color: var(--dropdown-text) !important;
        opacity: 0.7 !important;
        transition: color 0.15s ease, opacity 0.15s ease !important;
    }

    .fi-topbar .fi-dropdown-list-item:hover svg {
        color: var(--dropdown-item-hover-text) !important;
        opacity: 1 !important;
    }

    .fi-topbar .fi-dropdown-list-item .fi-dropdown-list-item-label,
    .fi-topbar .fi-dropdown-list-item span {
        color: inherit !important;
        font-weight: 500 !important;
        background: transparent !important;
    }

    /* Danger / logout */
    .fi-topbar .fi-dropdown-list-item.fi-color-danger,
    .fi-topbar .fi-dropdown-list-item[type="button"] {
        color: #dc2626 !important;
    }

    .fi-topbar .fi-dropdown-list-item.fi-color-danger:hover,
    .fi-topbar .fi-dropdown-list-item[type="button"]:hover {
        background-color: rgba(220, 38, 38, 0.1) !important;
        color: #b91c1c !important;
    }

    .dark .fi-topbar .fi-dropdown-list-item.fi-color-danger,
    .dark .fi-topbar .fi-dropdown-list-item[type="button"] {
        color: #f87171 !important;
    }

    .dark .fi-topbar .fi-dropdown-list-item.fi-color-danger:hover,
    .dark .fi-topbar .fi-dropdown-list-item[type="button"]:hover {
        background-color: rgba(220, 38, 38, 0.2) !important;
        color: #fca5a5 !important;
    }

    /* ==========================================
       SIDEBAR
       ========================================== */
    .fi-sidebar {
        border-inline-end: 1px solid var(--border-light, #e5e7eb);
    }

    .fi-sidebar-group-label {
        text-transform: uppercase;
        font-size: 0.7rem !important;
        letter-spacing: 0.06em;
        font-weight: 700;
        color: #94a3b8 !important;
    }

    .fi-sidebar-item-button {
        border-radius: 8px !important;
        margin-inline: 8px;
        transition: all 0.15s ease;
    }

    .fi-sidebar-item.fi-active>.fi-sidebar-item-button {
        background-color: #f0fdf4 !important;
        border-inline-start: 3px solid var(--c-light) !important;
    }

    .fi-sidebar-item.fi-active>.fi-sidebar-item-button .fi-sidebar-item-icon,
    .fi-sidebar-item.fi-active>.fi-sidebar-item-button .fi-sidebar-item-label {
        color: var(--c-light-hover) !important;
        font-weight: 600;
    }

    .fi-sidebar-item-button:hover {
        background-color: rgba(var(--c-rgb-light), 0.06) !important;
    }

    .dark .fi-sidebar-item.fi-active>.fi-sidebar-item-button {
        background-color: rgba(var(--c-rgb-light), 0.15) !important;
        border-inline-start-color: var(--c-light) !important;
    }

    .dark .fi-sidebar-item.fi-active>.fi-sidebar-item-button .fi-sidebar-item-icon,
    .dark .fi-sidebar-item.fi-active>.fi-sidebar-item-button .fi-sidebar-item-label {
        color: #6ee7b7 !important;
    }

    .dark .fi-sidebar-item-button:hover {
        background-color: rgba(var(--c-rgb-light), 0.12) !important;
    }

    /* ==========================================
       SECTIONS & CARDS
       ========================================== */
    .fi-section {
        border-radius: 12px !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.04) !important;
        transition: box-shadow 0.2s ease;
    }

    .fi-section-header {
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 12px;
        margin-bottom: 16px;
    }

    .dark .fi-section-header {
        border-bottom-color: #27272a;
    }

    .fi-btn {
        border-radius: 8px !important;
        font-weight: 600;
    }

    .fi-input,
    .fi-select-input,
    .fi-textarea {
        border-radius: 8px !important;
    }

    /* ==========================================
       WIDGETS - STATS
       ========================================== */
    .fi-wi-stats-overview .fi-wi-stats-overview-stats-ctn {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 1rem;
    }

    @media (max-width: 1280px) {
        .fi-wi-stats-overview .fi-wi-stats-overview-stats-ctn {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .fi-wi-stats-overview .fi-wi-stats-overview-stats-ctn {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 480px) {
        .fi-wi-stats-overview .fi-wi-stats-overview-stats-ctn {
            grid-template-columns: 1fr;
        }
    }

    .fi-wi-stats-overview-stat {
        padding: 1.25rem !important;
        border-radius: 14px !important;
        background: #ffffff;
        border: 1px solid #f1f5f9;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04), 0 1px 2px rgba(15, 23, 42, 0.02);
        transition: box-shadow 0.2s ease, transform 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .fi-wi-stats-overview-stat::before {
        content: '';
        position: absolute;
        inset-inline-start: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: var(--c-light);
        opacity: 0.85;
    }

    .fi-wi-stats-overview-stat:hover {
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08), 0 2px 4px rgba(15, 23, 42, 0.04);
        transform: translateY(-2px);
    }

    .fi-wi-stats-overview-stat-label {
        font-size: 0.8rem !important;
        font-weight: 500 !important;
        color: #64748b !important;
        margin-bottom: 0.35rem;
    }

    .fi-wi-stats-overview-stat-value {
        font-size: 1.75rem !important;
        font-weight: 700 !important;
        color: #0f172a !important;
        letter-spacing: -0.02em;
        line-height: 1.15;
    }

    .fi-wi-stats-overview-stat-description {
        font-size: 0.75rem !important;
        margin-top: 0.6rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-weight: 500;
    }

    .fi-wi-stats-overview-stat-chart {
        margin-top: 0.75rem;
        height: 40px;
    }

    .dark .fi-wi-stats-overview-stat {
        background: #18181b;
        border-color: #27272a;
    }

    .dark .fi-wi-stats-overview-stat:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }

    .dark .fi-wi-stats-overview-stat-value {
        color: #f4f4f5 !important;
    }

    .dark .fi-wi-stats-overview-stat-label {
        color: #a1a1aa !important;
    }

    /* ==========================================
       TABLES
       ========================================== */
    .fi-ta {
        border-radius: 14px !important;
        border: 1px solid #f1f5f9;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04), 0 1px 2px rgba(15, 23, 42, 0.02);
        overflow: hidden;
    }

    .fi-ta-header {
        padding: 1.25rem 1.5rem !important;
        border-bottom: 1px solid #f1f5f9 !important;
    }

    .fi-ta-header-heading {
        font-size: 1.05rem !important;
        font-weight: 700 !important;
        color: #0f172a !important;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .fi-ta-header-heading::before {
        content: '';
        width: 4px;
        height: 20px;
        border-radius: 2px;
        background: var(--c-light);
        display: inline-block;
    }

    .fi-ta-table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .fi-ta-header-cell {
        background: #f8fafc !important;
        color: #64748b !important;
        font-weight: 600 !important;
        font-size: 0.78rem !important;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 0.85rem 1rem !important;
        border-bottom: 1px solid #f1f5f9 !important;
    }

    .fi-ta-cell {
        padding: 1rem !important;
        border-bottom: 1px solid #f1f5f9 !important;
        font-size: 0.875rem;
        color: #334155;
        vertical-align: middle;
    }

    .fi-ta-row:last-child .fi-ta-cell {
        border-bottom: none !important;
    }

    .fi-ta-row:hover .fi-ta-cell {
        background: #f0fdf4 !important;
    }

    .fi-ta-text-item-icon {
        color: var(--c-light) !important;
    }

    .fi-ta-empty-state {
        padding: 3rem 1rem;
    }

    .fi-ta-empty-state-icon {
        color: #cbd5e1;
    }

    .fi-ta-empty-state-heading {
        color: #64748b !important;
        font-weight: 600;
    }

    .fi-ta-empty-state-description {
        color: #94a3b8 !important;
        font-size: 0.85rem;
    }

    /* ==========================================
       BADGES
       ========================================== */
    .fi-badge {
        border-radius: 6px !important;
        font-weight: 600 !important;
        font-size: 0.72rem !important;
        padding: 0.25rem 0.6rem !important;
        letter-spacing: 0.01em;
    }

    /* ==========================================
       WIDGET TABLES (LowStockAlert)
       ========================================== */
    .fi-wi-table .fi-ta-header {
        background: #fafbfc;
    }

    .fi-wi-table:has(.fi-ta-record[data-stock-critical]) .fi-ta-header-heading::before {
        background: #ef4444;
    }

    .fi-wi-table:has(.fi-ta-record[data-stock-critical]) .fi-ta-header {
        background: #fef2f2;
    }

    .fi-wi-table:has(.fi-ta-record[data-stock-critical]) .fi-ta-header-heading {
        color: #991b1b !important;
    }

    .dark .fi-wi-table .fi-ta-header {
        background: #1f1f23;
    }

    .dark .fi-wi-table:has(.fi-ta-record[data-stock-critical]) .fi-ta-header {
        background: #2a1515;
    }

    /* ==========================================
       DARK MODE - TABLES
       ========================================== */
    .dark .fi-ta {
        background: #18181b;
        border-color: #27272a;
    }

    .dark .fi-ta-header {
        border-bottom-color: #27272a !important;
    }

    .dark .fi-ta-header-heading {
        color: #f4f4f5 !important;
    }

    .dark .fi-ta-header-cell {
        background: #1f1f23 !important;
        color: #a1a1aa !important;
        border-bottom-color: #27272a !important;
    }

    .dark .fi-ta-cell {
        border-bottom-color: #27272a !important;
        color: #d4d4d8;
    }

    .dark .fi-ta-row:hover .fi-ta-cell {
        background: rgba(var(--c-rgb-light), 0.1) !important;
    }

    /* ==========================================
       TOPBAR ACTION BUTTON
       ========================================== */
    .topbar-action-btn {
        background-color: rgba(255, 255, 255, 0.15) !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.25) !important;
    }

    .topbar-action-btn:hover {
        background-color: rgba(255, 255, 255, 0.25) !important;
        border-color: rgba(255, 255, 255, 0.4) !important;
        transform: translateY(-1px);
    }

    .topbar-action-btn:active {
        transform: translateY(0);
    }
</style>