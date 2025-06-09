@extends('admin.layouts.app')

@section('title', 'Manajemen Pengembalian')

@push('styles')
<style>
    /* CSS Custom Properties */
    :root {
        --primary-color: #f97316; /* Tailwind orange-500 */
        --primary-dark: #ea580c; /* Tailwind orange-600 */
        --primary-light: #fed7aa; /* Tailwind orange-200 */
        --secondary-color: #4b5563; /* Tailwind gray-700 */
        --accent-color: #10b981; /* Tailwind green-500 */
        --warning-color: #f59e0b; /* Tailwind yellow-500 */
        --danger-color: #ef4444; /* Tailwind red-500 */
        --info-color: #3b82f6; /* Tailwind blue-500 */
        --light-color: #f8fafc; /* Tailwind gray-50 */
        --dark-color: #1f2937; /* Tailwind gray-900 */
        --border-color: #e5e7eb; /* Tailwind gray-200 */

        --gradient-primary: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        --gradient-secondary: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        --gradient-success: linear-gradient(135deg, var(--accent-color) 0%, #059669 100%);
        --gradient-warning: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
        --gradient-danger: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
        --gradient-info: linear-gradient(135deg, var(--info-color) 0%, #2563eb 100%);
        --gradient-dark: linear-gradient(135deg, var(--dark-color) 0%, #111827 100%);

        --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --box-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-heavy: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);

        --border-radius-sm: 0.5rem;
        --border-radius: 0.75rem;
        --border-radius-lg: 1.5rem;

        --transition-fast: all 0.2s ease-out;
        --transition-normal: all 0.3s ease-in-out;
        --transition-slow: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    /* Base Styles */
    body {
        background-color: var(--light-color);
        font-family: 'Inter', sans-serif;
        color: var(--secondary-color);
    }

    .container-fluid {
        padding: 1.5rem;
    }

    /* Page Header */
    .animated-header {
        background: var(--gradient-primary);
        color: white;
        padding: 2.5rem;
        border-radius: var(--border-radius-lg);
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
        animation: slideInDown var(--transition-slow) forwards;
        box-shadow: var(--shadow-heavy);
        backdrop-filter: blur(5px);
    }

    .animated-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle at 70% 30%, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
        pointer-events: none;
    }

    .animated-header::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.08'%3E%3Ccircle cx='12' cy='12' r='3'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        pointer-events: none;
        opacity: 0.6;
    }

    /* Animations for Header */
    @keyframes slideInDown {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-5px); }
    }

    /* Stats Grid - Compact Design */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.25rem 1rem;
        box-shadow: var(--box-shadow);
        transition: var(--transition-normal);
        position: relative;
        overflow: hidden;
        border: 1px solid var(--border-color);
        animation: fadeInUp var(--transition-slow) forwards;
        animation-fill-mode: both;
        cursor: pointer;
        text-align: center;
        min-height: 110px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }
    .stat-card:nth-child(4) { animation-delay: 0.4s; }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-heavy);
        border-color: rgba(249, 115, 22, 0.3);
    }

    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: white;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
        margin-bottom: 0.75rem;
        background: var(--gradient-primary);
        transition: var(--transition-normal);
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.25);
    }

    .stat-number {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--dark-color);
        margin-bottom: 0.25rem;
        display: block;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.8rem;
        color: var(--secondary-color);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 600;
        line-height: 1.2;
    }

    /* Specific stat-icon colors */
    .stat-card.menunggu .stat-icon { background: var(--gradient-warning); }
    .stat-card.disetujui .stat-icon { background: var(--gradient-success); }
    .stat-card.ditolak .stat-icon { background: var(--gradient-danger); }
    .stat-card.total .stat-icon { background: var(--gradient-info); }

    /* Filter Form - Simple & Clean */
    .filter-form-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 1.75rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border-color);
        animation: slideInLeft var(--transition-normal) forwards;
        animation-delay: 0.5s;
        position: relative;
        overflow: hidden;
    }

    .filter-form-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
    }

    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-30px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .form-group-filter {
        margin-bottom: 1.25rem;
    }

    .form-label-filter {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 0.75rem;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .form-control-filter {
        width: 100%;
        padding: 1rem 1.25rem;
        border: 2px solid #e2e8f0;
        border-radius: var(--border-radius-sm);
        background: #f8fafc;
        font-size: 0.95rem;
        color: var(--dark-color);
        transition: var(--transition-normal);
        font-weight: 500;
    }

    .form-control-filter:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        background: white;
        transform: translateY(-1px);
    }

    .btn-filter {
        padding: 1rem 1.75rem;
        border-radius: var(--border-radius-sm);
        font-weight: 700;
        font-size: 0.9rem;
        transition: var(--transition-normal);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        border: none;
        cursor: pointer;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        min-height: 48px;
    }

    .btn-filter-primary {
        background: var(--gradient-primary);
        color: white;
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.25);
    }

    .btn-filter-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(249, 115, 22, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-filter-secondary {
        background: #f1f5f9;
        color: var(--secondary-color);
        border: 2px solid #e2e8f0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .btn-filter-secondary:hover {
        transform: translateY(-1px);
        background: #e2e8f0;
        border-color: #cbd5e1;
        color: var(--secondary-color);
        text-decoration: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Main Table */
    .main-table-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        overflow: hidden;
        border: 1px solid var(--border-color);
        animation: slideInRight var(--transition-normal) forwards;
        animation-delay: 0.6s;
        margin-bottom: 2rem;
    }

    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(30px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .table-header {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-color);
    }

    .table-header h5 {
        color: var(--dark-color);
        font-weight: 700;
        font-size: 1.25rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .table-header h5::before {
        content: '\f0c5';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        color: var(--primary-color);
        font-size: 1.5rem;
    }

    .table th {
        padding: 0.75rem 0.5rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--secondary-color);
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        border-bottom: 2px solid var(--border-color);
        position: relative;
        white-space: nowrap;
    }

    .table th::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        opacity: 0.3;
    }

    .table td {
        padding: 0.75rem 0.5rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.8rem;
        color: var(--dark-color);
        transition: var(--transition-fast);
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .table tbody tr {
        transition: var(--transition-fast);
        border-left: 3px solid transparent;
    }

    .table tbody tr:hover {
        background: linear-gradient(135deg, rgba(249, 115, 22, 0.03) 0%, rgba(249, 115, 22, 0.08) 100%);
        border-left-color: var(--primary-color);
        transform: translateX(3px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    /* User Avatar - Compact */
    .user-avatar-placeholder {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 0.75rem;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        border: 2px solid white;
    }

    /* Compact Column Styles */
    .id-column { width: 60px; }
    .order-column { width: 100px; }
    .customer-column { width: 140px; }
    .amount-column { width: 90px; }
    .complaint-column { width: 180px; }
    .status-column { width: 100px; }
    .date-column { width: 90px; }
    .action-column { width: 120px; }

    .order-link {
        color: var(--primary-color);
        font-weight: 600;
        text-decoration: none;
        font-size: 0.85rem;
    }

    .order-link:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    .refund-amount {
        color: var(--danger-color);
        font-weight: 700;
        font-size: 0.85rem;
    }

    .complaint-info span {
        font-size: 0.8rem;
        line-height: 1.3;
        display: block;
        margin-bottom: 0.25rem;
    }

    .photo-badge {
        background: var(--info-color);
        color: white !important; /* Force white color */
        padding: 0.2rem 0.5rem;
        border-radius: 10px;
        font-size: 0.65rem;
        font-weight: 600;
    }

    /* Status Badges within Table */
    .table-status-badge {
        padding: 0.5rem 1rem;
        border-radius: 1.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .table-status-badge.menunggu, .table-status-badge.menunggu-review {
        background: #fef3c7;
        color: #a16207;
    }
    .table-status-badge.disetujui {
        background: #dcfce7;
        color: #15803d;
    }
    .table-status-badge.ditolak {
        background: #fee2e2;
        color: #b91c1c;
    }

    /* Action Buttons within Table - Compact */
    .btn-action-table {
        padding: 0.4rem 0.6rem;
        border-radius: var(--border-radius-sm);
        font-size: 0.7rem;
        font-weight: 600;
        transition: var(--transition-normal);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.2rem;
        border: none;
        cursor: pointer;
        text-decoration: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        min-width: 28px;
        height: 28px;
    }

    .btn-action-table.view {
        background: var(--info-color);
        color: white;
    }
    .btn-action-table.view:hover {
        background: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        color: white;
    }

    .btn-action-table.approve {
        background: var(--accent-color);
        color: white;
    }
    .btn-action-table.approve:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        color: white;
    }

    .btn-action-table.reject {
        background: var(--danger-color);
        color: white;
    }
    /* Table Content Styling */
    .order-link {
        color: var(--info-color);
        text-decoration: none;
        font-weight: 600;
        transition: var(--transition-fast);
    }

    .order-link:hover {
        color: #2563eb;
        text-decoration: underline;
    }

    .refund-amount {
        color: var(--danger-color);
        font-weight: 700;
        font-size: 1.05rem;
    }

    .complaint-info {
        color: var(--secondary-color);
        line-height: 1.4;
    }

    .photo-badge {
        color: var(--info-color);
        font-weight: 600;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .photo-badge::before {
        content: '\f03e';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
    }

    /* Pagination */
    .pagination-container {
        padding: 1.5rem;
        border-top: 1px solid var(--border-color);
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 0 0 var(--border-radius) var(--border-radius);
    }

    .pagination-info {
        font-size: 0.9rem;
        color: var(--secondary-color);
        font-weight: 600;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .empty-state i {
        color: #cbd5e1;
        margin-bottom: 1.5rem;
        display: block;
    }

    .empty-state h5 {
        color: var(--secondary-color);
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .empty-state p {
        color: #94a3b8;
        font-size: 0.95rem;
        max-width: 400px;
        margin: 0 auto;
    }

    /* Enhanced Header Elements Styling */
    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        padding: 0 1rem;
        min-height: 120px;
    }

    .header-icon-container {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 2rem;
    }

    .header-icon-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.15);
        border: 3px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
        animation: iconFloat 3s ease-in-out infinite;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .header-main-icon {
        font-size: 3rem;
        color: white;
        z-index: 2;
        position: relative;
        animation: iconPulse 2s ease-in-out infinite;
        text-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .icon-pulse {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        animation: pulseRing 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    .header-text {
        position: relative;
        z-index: 2;
        flex: 1;
        max-width: 600px;
    }

    .header-title {
        font-size: 3rem;
        font-weight: 900;
        line-height: 1.1;
        margin: 0 0 1rem 0;
        text-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        letter-spacing: -0.02em;
    }

    .title-primary {
        color: white;
        background: linear-gradient(135deg, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 0.8) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: inline-block;
    }

    .title-secondary {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 700;
        display: inline-block;
        margin-left: 0.5rem;
    }

    .header-subtitle {
        font-size: 1.25rem;
        color: rgba(255, 255, 255, 0.95);
        line-height: 1.6;
        margin: 0 0 1.5rem 0;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        font-weight: 400;
        max-width: 500px;
    }

    .header-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1rem;
    }

    .feature-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 30px;
        color: white;
        font-size: 0.9rem;
        font-weight: 600;
        backdrop-filter: blur(15px);
        transition: all 0.3s ease;
        animation: badgeFloat 4s ease-in-out infinite;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }

    .feature-badge:nth-child(even) {
        animation-delay: 0.5s;
    }

    .feature-badge:nth-child(3n) {
        animation-delay: 1s;
    }

    .feature-badge:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        border-color: rgba(255, 255, 255, 0.4);
    }

    .header-actions {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 1.5rem;
    }

    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        align-items: flex-end;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.5rem;
        background: rgba(255, 255, 255, 0.15);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 15px;
        color: white;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        backdrop-filter: blur(15px);
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        min-width: 180px;
        justify-content: center;
    }

    .action-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-3px);
        color: white;
        text-decoration: none;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        border-color: rgba(255, 255, 255, 0.5);
    }

    .action-btn.primary {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.8) 0%, rgba(5, 150, 105, 0.8) 100%);
        border-color: rgba(16, 185, 129, 0.6);
    }

    .action-btn.primary:hover {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.9) 0%, rgba(5, 150, 105, 0.9) 100%);
        border-color: rgba(16, 185, 129, 0.8);
    }

    .action-btn.secondary {
        background: rgba(107, 114, 128, 0.8);
        border-color: rgba(107, 114, 128, 0.6);
    }

    .action-btn.secondary:hover {
        background: rgba(107, 114, 128, 0.9);
        border-color: rgba(107, 114, 128, 0.8);
    }

    .quick-stats-mini {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        align-items: flex-end;
    }

    .mini-stat {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 0.75rem 1rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        color: white;
        backdrop-filter: blur(10px);
        animation: miniStatPulse 3s ease-in-out infinite;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        min-width: 120px;
        text-align: center;
    }

    .mini-number {
        font-size: 1.1rem;
        font-weight: 800;
        color: white;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    .mini-label {
        font-size: 0.75rem;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.8);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-top: 0.25rem;
    }

    .header-decoration {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
        overflow: hidden;
        z-index: 1;
    }

    .floating-element {
        position: absolute;
        color: rgba(255, 255, 255, 0.08);
        font-size: 2rem;
        animation: elementFloat 8s ease-in-out infinite;
    }

    .floating-element::before {
        content: '\f53a'; /* Money bill wave icon */
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
    }

    .element-1 {
        top: 15%;
        right: 12%;
        animation-delay: 0s;
        font-size: 2.5rem;
    }

    .element-1::before {
        content: '\f53a'; /* Money bill wave */
    }

    .element-2 {
        top: 65%;
        right: 20%;
        font-size: 1.8rem;
        animation-delay: 2s;
    }

    .element-2::before {
        content: '\f2b5'; /* Handshake */
    }

    .element-3 {
        top: 35%;
        left: 8%;
        font-size: 2.2rem;
        animation-delay: 4s;
    }

    .element-3::before {
        content: '\f080'; /* Chart bar */
    }

    /* Header Animations - Simplified */
    @keyframes iconFloat {
        0%, 100% {
            transform: translateY(0px) scale(1);
        }
        50% {
            transform: translateY(-5px) scale(1.05);
        }
    }

    @keyframes iconPulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.95; }
    }

    @keyframes pulseRing {
        0% {
            transform: translate(-50%, -50%) scale(0.8);
            opacity: 0.8;
        }
        100% {
            transform: translate(-50%, -50%) scale(1.5);
            opacity: 0;
        }
    }

    @keyframes badgeFloat {
        0%, 100% {
            transform: translateY(0px);
            opacity: 1;
        }
        50% {
            transform: translateY(-3px);
            opacity: 0.95;
        }
    }

    @keyframes miniStatPulse {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.02);
            opacity: 0.9;
        }
    }

    @keyframes elementFloat {
        0%, 100% {
            transform: translateY(0px) scale(1);
            opacity: 0.05;
        }
        50% {
            transform: translateY(-10px) scale(1.1);
            opacity: 0.1;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .animated-header {
            padding: 1.5rem;
        }

        .header-container {
            flex-direction: column;
            align-items: center;
            gap: 2rem;
            text-align: center;
            min-height: auto;
            padding: 0;
        }

        .header-text {
            text-align: center;
            max-width: 100%;
            order: 1;
        }

        .header-title {
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
        }

        .header-subtitle {
            font-size: 1rem;
            max-width: 100%;
        }

        .header-badges {
            justify-content: center;
            gap: 0.75rem;
        }

        .feature-badge {
            font-size: 0.8rem;
            padding: 0.6rem 1rem;
        }

        .header-actions {
            align-items: center;
            width: 100%;
            order: 2;
        }

        .action-buttons {
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.75rem;
            width: 100%;
            margin-bottom: 1rem;
        }

        .action-btn {
            min-width: 150px;
            font-size: 0.85rem;
            padding: 0.8rem 1.2rem;
        }

        .quick-stats-mini {
            flex-direction: row;
            justify-content: center;
            gap: 1rem;
            width: 100%;
        }

        .mini-stat {
            min-width: 100px;
            padding: 0.6rem 0.8rem;
        }

        .mini-number {
            font-size: 1rem;
        }

        .mini-label {
            font-size: 0.7rem;
        }

        .floating-element {
            font-size: 1.5rem;
        }

        .element-1 {
            top: 10%;
            right: 5%;
            font-size: 1.8rem;
        }

        .element-2 {
            top: 70%;
            right: 10%;
            font-size: 1.4rem;
        }

        .element-3 {
            top: 40%;
            left: 5%;
            font-size: 1.6rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .stat-card {
            padding: 1rem;
            min-height: 100px;
        }
        .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
        }
        .stat-number {
            font-size: 1.5rem;
        }
        .stat-label {
            font-size: 0.7rem;
        }

        .filter-form-card {
            padding: 1rem;
        }
        .form-group-filter {
            margin-bottom: 0.75rem;
        }
        .btn-filter {
            width: 100%;
            padding: 0.8rem 1rem;
            font-size: 0.85rem;
        }

        .table-responsive {
            overflow-x: auto;
        }
        .table th, .table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.85rem;
        }
        .table-status-badge {
            font-size: 0.65rem;
            padding: 0.4rem 0.8rem;
        }
        .btn-action-table {
            font-size: 0.7rem;
            padding: 0.4rem 0.6rem;
        }
        .user-avatar-placeholder {
            width: 35px;
            height: 35px;
            font-size: 0.8rem;
        }
        .refund-amount {
            font-size: 0.9rem;
        }
    }

    @media (max-width: 576px) {
        .header-title {
            font-size: 1.8rem;
        }

        .header-subtitle {
            font-size: 0.9rem;
        }

        .feature-badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.8rem;
        }

        .action-btn {
            min-width: 120px;
            font-size: 0.8rem;
        }

        .header-icon-wrapper {
            width: 70px;
            height: 70px;
        }

        .header-main-icon {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="animated-header">
        <div class="header-container">
            <!-- Header Text Content -->
            <div class="header-text">
                <h1 class="header-title">
                    <span class="title-primary">Manajemen</span>
                    <span class="title-secondary">Pengembalian</span>
                </h1>
                <p class="header-subtitle">Kelola & monitor seluruh pengajuan pengembalian dana dengan sistem terintegrasi</p>
            </div>

            <!-- Header Actions -->
            <div class="header-actions">
                <div class="action-buttons">
                    <a href="{{ route('admin.reports.financial') }}" class="action-btn primary">
                        <i class="fas fa-money-bill"></i>
                        <span>Catatan Keuangan</span>
                    </a>
                </div>

                <!-- Quick Stats Mini -->
                <div class="quick-stats-mini">
                    <div class="mini-stat">
                        <span class="mini-number">Rp {{ number_format($pengembalian->where('status_pengembalian', 'Disetujui')->sum('jumlah_klaim'), 0, ',', '.') }}</span>
                        <span class="mini-label">Total Disetujui</span>
                    </div>
                </div>
            </div>

            <!-- Floating Decoration Elements -->
            <div class="header-decoration">
                <div class="floating-element element-1"></div>
                <div class="floating-element element-2"></div>
                <div class="floating-element element-3"></div>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card menunggu" onclick="applyFilter('Menunggu Review')">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <span class="stat-number">{{ $stats['menunggu'] }}</span>
            <span class="stat-label">Menunggu Review</span>
        </div>
        <div class="stat-card disetujui" onclick="applyFilter('Disetujui')">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <span class="stat-number">{{ $stats['disetujui'] }}</span>
            <span class="stat-label">Disetujui</span>
        </div>
        <div class="stat-card ditolak" onclick="applyFilter('Ditolak')">
            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
            <span class="stat-number">{{ $stats['ditolak'] }}</span>
            <span class="stat-label">Ditolak</span>
        </div>
        <div class="stat-card total" onclick="applyFilter('')">
            <div class="stat-icon"><i class="fas fa-list-alt"></i></div>
            <span class="stat-number">{{ $stats['total'] }}</span>
            <span class="stat-label">Total Pengajuan</span>
        </div>
    </div>

    <div class="main-table-card">
        <div class="table-header">
            <h5 class="mb-0 text-gray-800">Daftar Pengajuan Pengembalian</h5>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Jumlah Refund</th>
                        <th>Alasan Keluhan</th>
                        <th>Status</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengembalian as $item)
                    <tr>
                        <td>
                            <a href="{{ route('admin.pesanan.show', $item->pesanan->id_pesanan) }}" class="order-link">
                                #{{ $item->pesanan->id_pesanan ?? 'N/A' }}
                            </a>
                            <br>
                            <small class="text-muted">Total: Rp {{ number_format($item->pesanan->total_harga ?? 0, 0, ',', '.') }}</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div>
                                    {{ $item->user->name ?? 'N/A' }}
                                    <br>
                                    <small class="text-muted">{{ $item->user->email ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="refund-amount">Rp {{ number_format($item->jumlah_klaim, 0, ',', '.') }}</td>
                        <td>
                            <div class="complaint-info">
                                <span>{{ Str::limit($item->jenis_keluhan, 40) }}</span>
                                @if($item->deskripsi_keluhan)
                                    <br>
                                    <small class="text-muted">{{ Str::limit($item->deskripsi_keluhan, 50) }}</small>
                                @endif
                                @if($item->foto_bukti)
                                    <br>
                                    <small class="photo-badge" style="color: white !important;">Ada Bukti Foto</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            @php
                                $statusClass = strtolower(str_replace(' ', '-', $item->status_pengembalian));
                            @endphp
                            <span class="table-status-badge {{ $statusClass }}">
                                {{ $item->status_pengembalian }}
                            </span>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                            <br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB</small>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.pengembalian.show', $item->id_pengembalian) }}" class="btn-action-table view">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($item->status_pengembalian == 'Menunggu Review')
                                    <button onclick="approveRefund({{ $item->id_pengembalian }})" class="btn-action-table approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button onclick="rejectRefund({{ $item->id_pengembalian }})" class="btn-action-table reject">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-state">
                            <i class="fas fa-inbox fa-3x"></i>
                            <h5>Tidak ada pengajuan pengembalian yang ditemukan</h5>
                            <p>Coba ubah filter Anda atau tunggu pengajuan pengembalian baru dari pelanggan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($pengembalian, 'links') && $pengembalian->hasPages())
        <div class="pagination-container d-flex justify-content-between align-items-center flex-wrap">
            <span class="pagination-info">
                Menampilkan {{ $pengembalian->firstItem() }} - {{ $pengembalian->lastItem() }} dari {{ $pengembalian->total() }} data
            </span>
            <div>
                {{ $pengembalian->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Apply filter from stats cards
    window.applyFilter = function(status) {
        document.getElementById('status').value = status;
        document.getElementById('filter-form').submit();
    };

    // Approve Refund (SweetAlert2)
    window.approveRefund = function(id) {
        Swal.fire({
            title: 'Setujui Pengembalian',
            html: `
                <div class="text-center">
                    <p class="text-muted mb-3">Apakah Anda yakin ingin menyetujui pengembalian <strong>#${id}</strong>?</p>
                    <p class="text-success fw-bold"><i class="fas fa-info-circle me-1"></i> Ini akan memberitahu pelanggan dan memulai proses pengembalian dana.</p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Setujui',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#10b981',
            customClass: {
                popup: 'rounded-lg',
                confirmButton: 'rounded-md',
                cancelButton: 'rounded-md'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Sedang menyetujui pengembalian...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                // Submit form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/pengembalian/${id}/approve`;
                form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    };

    // Reject Refund (SweetAlert2)
    window.rejectRefund = function(id) {
        Swal.fire({
            title: 'Tolak Pengembalian',
            html: `
                <div class="text-left">
                    <p class="text-muted mb-3">Mohon berikan alasan penolakan untuk pengembalian <strong>#${id}</strong>:</p>
                    <textarea id="reason" class="form-control" rows="4" placeholder="Alasan penolakan..." required></textarea>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Tolak',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#ef4444',
            customClass: {
                popup: 'rounded-lg',
                confirmButton: 'rounded-md',
                cancelButton: 'rounded-md'
            },
            preConfirm: () => {
                const reason = document.getElementById('reason').value;
                if (!reason) {
                    Swal.showValidationMessage('Alasan penolakan tidak boleh kosong!');
                    return false;
                }
                return reason;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Sedang menolak pengembalian...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                // Submit form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/pengembalian/${id}/reject`;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="alasan" value="${result.value}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    };

    // Add user avatar placeholder for table
    document.querySelectorAll('.user-avatar-placeholder').forEach(el => {
        const char = el.textContent.trim().charAt(0);
        if (char) {
            el.textContent = char.toUpperCase();
        } else {
            el.innerHTML = '<i class="fas fa-user"></i>'; // Default icon
        }
    });
</script>
@endpush
