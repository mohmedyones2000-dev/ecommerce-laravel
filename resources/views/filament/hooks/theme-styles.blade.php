<style>
    :root {
        --admin-topbar-bg: #005a96;
        --admin-topbar-bg-hover: #004578;
        --admin-topbar-text: #ffffff;
        --admin-topbar-input-bg: rgba(255, 255, 255, 0.15);
        --admin-topbar-input-border: rgba(255, 255, 255, 0.25);
        --admin-sidebar-active-bg: #f0fdfa;
        --admin-sidebar-active-text: #0d9488;
        --admin-sidebar-active-border: #14b8a6;
        --admin-card-radius: 12px;
        --admin-card-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.04);
        --admin-card-shadow-hover: 0 6px 16px rgba(0, 0, 0, 0.1);
    }

    .fi-topbar {
        background-color: var(--admin-topbar-bg) !important;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08) !important;
    }

    .fi-topbar a,
    .fi-topbar button,
    .fi-topbar span,
    .fi-topbar svg,
    .fi-topbar .fi-icon-btn-icon,
    .fi-topbar .fi-icon-btn-label {
        color: var(--admin-topbar-text) !important;
    }

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
        background-color: var(--admin-topbar-bg-hover) !important;
    }

    .fi-topbar button:focus,
    .fi-topbar button:focus-visible,
    .fi-topbar a:focus,
    .fi-topbar a:focus-visible {
        outline: 2px solid rgba(255, 255, 255, 0.4) !important;
        outline-offset: 2px !important;
        background-color: var(--admin-topbar-bg-hover) !important;
    }

    .fi-topbar .fi-dropdown-panel {
        border-radius: 12px !important;
        margin-top: 0.5rem !important;
    }

    .fi-topbar .fi-dropdown-list-item {
        border-radius: 8px !important;
        margin-inline: 0.35rem;
        transition: background-color 0.15s ease;
    }

    .fi-topbar .fi-dropdown-header,
    .fi-topbar .fi-dropdown-list-item-label,
    .fi-topbar .fi-dropdown-list-item-icon {
        color: inherit;
    }

    .fi-topbar .fi-user-menu-trigger {
        border-radius: 10px !important;
        padding: 0.35rem 0.65rem !important;
        transition: background-color 0.15s ease !important;
        background-color: transparent !important;
    }

    .fi-topbar .fi-user-menu-trigger:hover {
        background-color: var(--admin-topbar-bg-hover) !important;
        border-radius: 10px !important;
    }

    .fi-topbar .fi-avatar,
    .fi-topbar .fi-user-avatar {
        border: 2px solid rgba(255, 255, 255, 0.4) !important;
    }

    .fi-topbar .fi-topbar-open-sidebar-btn,
    .fi-topbar .fi-topbar-close-sidebar-btn,
    .fi-topbar .fi-topbar-toggle-sidebar-btn {
        border-radius: 10px !important;
    }

    .fi-topbar .fi-topbar-open-sidebar-btn:hover,
    .fi-topbar .fi-topbar-close-sidebar-btn:hover,
    .fi-topbar .fi-topbar-toggle-sidebar-btn:hover {
        background-color: var(--admin-topbar-bg-hover) !important;
    }

    .fi-topbar .fi-badge {
        background-color: #ef4444 !important;
        color: #ffffff !important;
        border: 2px solid var(--admin-topbar-bg) !important;
        font-weight: 700 !important;
    }

    .fi-topbar input[type="search"],
    .fi-topbar .fi-input {
        background-color: var(--admin-topbar-input-bg) !important;
        border-color: var(--admin-topbar-input-border) !important;
        color: var(--admin-topbar-text) !important;
        border-radius: 10px !important;
    }

    .fi-topbar input[type="search"]::placeholder,
    .fi-topbar .fi-input::placeholder {
        color: rgba(255, 255, 255, 0.65) !important;
    }

    .fi-topbar input[type="search"]:focus,
    .fi-topbar .fi-input:focus {
        border-color: rgba(255, 255, 255, 0.5) !important;
        background-color: var(--admin-topbar-bg-hover) !important;
    }

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
        background-color: var(--admin-sidebar-active-bg) !important;
        border-inline-start: 3px solid var(--admin-sidebar-active-border) !important;
    }

    .fi-sidebar-item.fi-active>.fi-sidebar-item-button .fi-sidebar-item-icon,
    .fi-sidebar-item.fi-active>.fi-sidebar-item-button .fi-sidebar-item-label {
        color: var(--admin-sidebar-active-text) !important;
        font-weight: 600;
    }

    .fi-sidebar-item-button:hover {
        background-color: rgba(20, 184, 166, 0.06) !important;
    }

    .fi-section {
        border-radius: var(--admin-card-radius) !important;
        box-shadow: var(--admin-card-shadow) !important;
        transition: box-shadow 0.2s ease;
    }

    .fi-section-header {
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 12px;
        margin-bottom: 16px;
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
        background: var(--primary-500, #14b8a6);
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
        background: var(--primary-500, #14b8a6);
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
        background: #fafbfc !important;
    }

    .fi-ta-text-item-icon {
        color: var(--primary-500, #14b8a6) !important;
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

    .fi-badge {
        border-radius: 6px !important;
        font-weight: 600 !important;
        font-size: 0.72rem !important;
        padding: 0.25rem 0.6rem !important;
        letter-spacing: 0.01em;
    }

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

    .dark {
        --admin-sidebar-active-bg: rgba(20, 184, 166, 0.12);
        --admin-sidebar-active-text: #5eead4;
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

    .dark .fi-section-header {
        border-bottom-color: #27272a;
    }

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
        background: #1f1f23 !important;
    }

    .dark .fi-wi-table .fi-ta-header {
        background: #1f1f23;
    }

    .dark .fi-wi-table:has(.fi-ta-record[data-stock-critical]) .fi-ta-header {
        background: #2a1515;
    }

    .topbar-action-btn {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
    }

    .topbar-action-btn:hover {
        background-color: var(--admin-topbar-bg-hover) !important;
        border-color: var(--admin-topbar-bg-hover) !important;
        transform: translateY(-1px);
    }

    .topbar-action-btn:active {
        transform: translateY(0);
    }
</style>