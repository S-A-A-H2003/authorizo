<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Authorizo')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #f3f4f8;
            --surface: #ffffff;
            --ink: #14161c;
            --muted: #6b7280;
            --line: #e6e7ec;
            --primary: #4f46e5;
            --primary-dark: #3f3ad1;
            --success: #16a34a;
            --success-bg: #ecfdf3;
            --success-line: #bbf0ce;
            --danger: #dc2626;
            --danger-bg: #fef2f2;
            --danger-line: #f8caca;
            --radius: 10px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--ink);
            font-family: 'Inter', system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 32px;
            background: var(--surface);
            border-bottom: 1px solid var(--line);
            position: sticky;
            top: 0;
            z-index: 20;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.02em;
        }

        .brand-mark {
            width: 26px;
            height: 26px;
            border-radius: 7px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .topbar h1 {
            margin: 0;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 16px;
            font-weight: 600;
            color: var(--muted);
        }

        .main-nav {
            display: flex;
            gap: 22px;
            padding: 0 32px;
            background: var(--surface);
            border-bottom: 1px solid var(--line);
            position: sticky;
            top: 57px;
            z-index: 19;
            overflow-x: auto;
        }

        .main-nav a {
            padding: 13px 2px;
            margin-bottom: -1px;
            border-bottom: 2px solid transparent;
            color: var(--muted);
            text-decoration: none;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
        }

        .main-nav a:hover { color: var(--ink); }
        .main-nav a.active { color: var(--primary); border-bottom-color: var(--primary); }

        .dash-hero { margin-bottom: 24px; }

        .dash-hero h2 {
            margin: 0 0 6px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 24px;
            font-weight: 700;
        }

        .dash-hero p { margin: 0; color: var(--muted); font-size: 14px; }

        .dash-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
        }

        .dash-card {
            display: flex;
            flex-direction: column;
            gap: 14px;
            padding: 22px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 14px;
            text-decoration: none;
            color: inherit;
            transition: border-color .15s ease, box-shadow .15s ease, transform .15s ease;
        }

        .dash-card:hover {
            border-color: var(--primary);
            box-shadow: 0 6px 18px rgba(79,70,229,.08);
            transform: translateY(-1px);
        }

        .dash-card-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(79,70,229,.1);
            font-size: 19px;
        }

        .dash-card-body h3 {
            margin: 0 0 4px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 16px;
            font-weight: 700;
        }

        .dash-card-body p { margin: 0; color: var(--muted); font-size: 13.5px; line-height: 1.5; }

        .dash-card-meta {
            margin-top: auto;
            font-size: 12px;
            font-weight: 600;
            color: var(--primary);
        }

        .wrap {
            max-width: 980px;
            margin: 0 auto;
            padding: 32px;
        }

        .banner {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: var(--radius);
            border: 1px solid;
            font-size: 14px;
            margin-bottom: 18px;
        }

        .banner-success { background: var(--success-bg); border-color: var(--success-line); color: #15803d; }
        .banner-danger { background: var(--danger-bg); border-color: var(--danger-line); color: #b91c1c; }
        .banner ul { margin: 0; padding-inline-start: 18px; }

        .toolbar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }

        .search-box { position: relative; flex: 1; }

        .search-box input {
            width: 100%;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            padding: 10px 14px 10px 38px;
            border: 1px solid var(--line);
            border-radius: 9px;
            background: var(--surface) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='7'/%3E%3Cpath d='M21 21l-3.5-3.5'/%3E%3C/svg%3E") no-repeat 14px center;
            color: var(--ink);
            transition: border-color 0.15s ease;
        }

        .search-box input:focus-visible {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.12);
        }

        .role-filter {
            appearance: none;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            padding: 9px 30px 9px 14px;
            border: 1px solid var(--line);
            border-radius: 9px;
            background: var(--surface) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%236b7280'/%3E%3C/svg%3E") no-repeat right 12px center;
            color: var(--ink);
            cursor: pointer;
        }

        .btn-create {
            padding: 9px 16px;
            border-radius: 8px;
            background: var(--primary);
            color: #fff;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-create:hover { background: var(--primary-dark); }

        .result-count { font-size: 12px; color: var(--muted); margin-bottom: 10px; }

        .panel {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 14px;
            overflow: hidden;
        }

        .panel--padded { padding: 28px; }

        .panel-header { margin-bottom: 26px; }

        .panel-header h2 {
            margin: 0 0 6px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 22px;
            font-weight: 700;
        }

        .panel-header p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
        }

        .field { margin-bottom: 20px; }

        .field label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
        }

        .field input {
            width: 100%;
            padding: 11px 13px;
            border: 1px solid var(--line);
            border-radius: 9px;
            background: #fff;
            color: var(--ink);
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .field input:focus-visible {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,.12);
        }

        .field small {
            display: block;
            margin-top: 7px;
            color: var(--muted);
            font-size: 12px;
        }

        .permissions-section { margin-bottom: 20px; }

        .permissions-section > label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
        }

        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 12px;
        }

        .permission-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 13px;
            border: 1px solid var(--line);
            border-radius: 9px;
            background: #fbfbfd;
            transition: border-color .15s ease, background .15s ease;
        }

        .permission-item:has(input:checked) {
            border-color: var(--primary);
            background: rgba(79,70,229,.05);
        }

        .permission-item input[type="checkbox"] {
            width: 17px;
            height: 17px;
            accent-color: var(--primary);
            cursor: pointer;
            flex-shrink: 0;
        }

        .permission-item label {
            margin: 0;
            font-size: 13.5px;
            font-weight: 500;
            cursor: pointer;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid var(--line);
        }

        .btn {
            padding: 9px 17px;
            border-radius: 8px;
            border: 1px solid var(--line);
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-secondary { background: #fff; color: var(--ink); }
        .btn-primary { background: var(--primary); border-color: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dark); }

        table { width: 100%; border-collapse: collapse; font-size: 14px; }

        thead th {
            text-align: start;
            padding: 14px 20px;
            border-bottom: 1px solid var(--line);
            background: #fafafe;
            color: var(--muted);
            font-family: 'Space Grotesk', sans-serif;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        tbody td { padding: 14px 20px; border-bottom: 1px solid var(--line); vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: #fafaff; }
        tbody tr.is-hidden { display: none; }

        .role-name { font-weight: 600; }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 5px 11px;
            border-radius: 999px;
            background: #f4f4fb;
            border: 1px solid var(--line);
            font-family: 'IBM Plex Mono', monospace;
            font-size: 12px;
            font-weight: 500;
        }

        .chip-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
        .chip-empty { color: var(--muted); font-style: normal; }

        .row-actions { display: flex; gap: 7px; }

        .btn-action {
            padding: 6px 11px;
            border-radius: 7px;
            border: 1px solid var(--line);
            background: #fff;
            color: var(--ink);
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-delete { color: var(--danger); border-color: var(--danger-line); background: var(--danger-bg); }

        .user-cell { display: flex; align-items: center; gap: 12px; }

        .avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 600;
            font-size: 13px;
            color: #fff;
            flex-shrink: 0;
        }

        .user-name { font-weight: 600; }
        .user-email { color: var(--muted); font-size: 13px; }

        .assign-form { display: flex; gap: 8px; align-items: center; }

        select.role-select {
            appearance: none;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            padding: 7px 12px;
            padding-right: 30px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%236b7280'/%3E%3C/svg%3E") no-repeat right 10px center;
            color: var(--ink);
            min-width: 150px;
            cursor: pointer;
            transition: border-color .15s ease;
        }

        select.role-select:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 1px;
            border-color: var(--primary);
        }

        .btn-save {
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 13px;
            padding: 7px 16px;
            border: none;
            border-radius: 8px;
            background: var(--primary);
            color: #fff;
            cursor: pointer;
            transition: background .15s ease, opacity .15s ease;
            min-width: 76px;
        }

        .btn-save:disabled { background: #d9dae2; color: #9498a3; cursor: not-allowed; }
        .btn-save:not(:disabled):hover { background: var(--primary-dark); }
        .btn-save:focus-visible { outline: 2px solid var(--primary-dark); outline-offset: 2px; }

        .empty-state { text-align: center; padding: 56px 20px; color: var(--muted); font-size: 14px; }

        @media (max-width: 560px) {
            .permissions-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 720px) {
            .wrap { padding: 18px; }
            .topbar { padding: 16px 18px; }
            .main-nav { padding: 0 18px; top: 53px; }
            .panel--padded { padding: 20px; }
            .toolbar { flex-wrap: wrap; }

            table, thead, tbody, tr, th, td { display: block; }
            thead { display: none; }
            tbody tr { padding: 14px 16px; border-bottom: 1px solid var(--line); }
            tbody td { border: none; padding: 5px 0; }

            .row-actions { margin-top: 8px; }
            .assign-form { margin-top: 8px; }
        }

        @media (prefers-reduced-motion: reduce) {
            * { transition: none !important; }
        }

        @yield('styles')
    </style>
</head>

<body>

<div class="topbar">
    <div class="brand">
        <span class="brand-mark">A</span>
        Authorizo
    </div>

    <h1>@yield('page-title')</h1>
</div>

<x-authorizo::nav />

<div class="wrap">
    @yield('content')
</div>

@stack('scripts')

</body>
</html>
