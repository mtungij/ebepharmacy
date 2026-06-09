<?php
    $profile_img = (!empty($my) && !empty($my->img))
        ? base_url().'assets/admin/img/'.$my->img
        : base_url().'assets/admin/img/wateja.png';

    $CI =& get_instance();
    $shop_name = 'helixPos';
    $shop_logo_url = base_url('assets/images/helixpos.png');

    $low_stock_count = 0;
    $pending_order_count = 0;
    $today_sales_count = 0;

    if ($CI->db->table_exists('product')) {
        $low_stock_row = $CI->db->query(
            "SELECT COUNT(*) AS total FROM product WHERE stock_limit > 0 AND quantity <= stock_limit"
        )->row();
        $low_stock_count = (int) ($low_stock_row->total ?? 0);
    }

    if ($CI->db->table_exists('tbl_receipt')) {
        $pending_order_row = $CI->db->query(
            "SELECT COUNT(*) AS total FROM tbl_receipt WHERE order_status = 'pending'"
        )->row();
        $pending_order_count = (int) ($pending_order_row->total ?? 0);
    }

    if ($CI->db->table_exists('tbl_sell')) {
        $today_sales_row = $CI->db->query(
            "SELECT COUNT(*) AS total FROM tbl_sell WHERE sell_day = " . $CI->db->escape(date('Y-m-d'))
        )->row();
        $today_sales_count = (int) ($today_sales_row->total ?? 0);
    }

    $setting_url = ($_SESSION['role'] == 'admin')
        ? base_url('admin/setting')
        : base_url('seller/setting');
    $password_url = ($_SESSION['role'] == 'admin')
        ? base_url('admin/change_password')
        : base_url('seller/change_password');
    $pharmacy_info_url = base_url('admin/shop_info');
?>
<nav class="navbar navbar-fixed-top evamo-topbar">
<div class="container-fluid evamo-topbar-inner">
    <div class="evamo-topbar-left">
        <button type="button" class="btn-toggle-offcanvas evamo-drawer-btn" onclick="if (window.__evamoToggleSidebar) { return window.__evamoToggleSidebar(event); } document.body.classList.toggle('offcanvas-active'); var s=document.getElementById('left-sidebar'); if(s){ s.classList.toggle('evamo-open'); } return false;" aria-label="Toggle sidebar">
            <i class="icon-list"></i>
        </button>

        <a href="javascript:;" class="evamo-brand">
            <img src="<?php echo $shop_logo_url; ?>" alt="helixPos logo" class="evamo-brand-logo">
            <span class="evamo-brand-name"><?php echo $shop_name; ?></span>
        </a>

        <form action="#" method="GET" class="evamo-topbar-search">
            <i class="icon-magnifier"></i>
            <input type="text" placeholder="Search">
        </form>
    </div>

    <div class="evamo-topbar-right">
        <button type="button" class="evamo-icon-btn evamo-theme-toggle" data-theme-toggle aria-pressed="false" aria-label="Switch to dark mode" title="Switch theme">
            <svg class="evamo-theme-icon evamo-theme-icon-moon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 1 0 9.8 9.8z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <svg class="evamo-theme-icon evamo-theme-icon-sun" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.8"/>
                <path d="M12 2.5v2.2M12 19.3v2.2M4.7 4.7l1.6 1.6M17.7 17.7l1.6 1.6M2.5 12h2.2M19.3 12h2.2M4.7 19.3l1.6-1.6M17.7 6.3l1.6-1.6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
        </button>

        <button type="button" class="evamo-icon-btn evamo-install-app" title="Install App" aria-label="Install App">
            <i class="icon-cloud-download"></i>
            <span class="evamo-install-app-text">Install App</span>
        </button>

        <div class="dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle evamo-icon-btn" data-toggle="dropdown" aria-label="Notifications">
                <i class="icon-bell"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-right evamo-topbar-menu">
                <li class="evamo-topbar-menu-title">Notifications</li>
                <li><a href="javascript:void(0);"><i class="icon-basket"></i> Low stock items: <?php echo number_format($low_stock_count); ?></a></li>
                <li><a href="javascript:void(0);"><i class="icon-doc"></i> Pending receipts: <?php echo number_format($pending_order_count); ?></a></li>
                <li><a href="javascript:void(0);"><i class="icon-graph"></i> Today's sales records: <?php echo number_format($today_sales_count); ?></a></li>
            </ul>
        </div>

        <div class="dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle evamo-icon-btn" data-toggle="dropdown" aria-label="Quick apps">
                <i class="icon-grid"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right evamo-topbar-menu evamo-apps-menu">
                <div class="evamo-topbar-menu-title">Quick Apps</div>
                <div class="evamo-apps-grid">
                    <a href="<?php echo base_url("admin/index") ?>"><i class="icon-home"></i><span>Dashboard</span></a>
                    <a href="<?php echo base_url("admin/admin_sell") ?>"><i class="icon-list"></i><span>Sell</span></a>
                    <a href="<?php echo base_url("admin/all_product") ?>"><i class="icon-basket"></i><span>Products</span></a>
                    <a href="<?php echo base_url("admin/general_cashflowData") ?>"><i class="icon-graph"></i><span>Reports</span></a>
                </div>
            </div>
        </div>

        <div class="dropdown evamo-nav-profile-dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle evamo-icon-btn evamo-nav-profile-toggle" data-toggle="dropdown" aria-label="User information">
                <img src="<?php echo $profile_img; ?>" alt="User" class="evamo-nav-profile-avatar">

            </a>
            <ul class="dropdown-menu dropdown-menu-right evamo-topbar-menu">
                <li class="evamo-topbar-menu-title">User Information</li>
                <li><a href="<?php echo $setting_url; ?>"><i class="icon-user"></i> My Profile</a></li>
                <li><a href="<?php echo $password_url; ?>"><i class="icon-key"></i> Change Password</a></li>
                <li><a href="<?php echo $pharmacy_info_url; ?>"><i class="icon-home"></i> Pharmacy Information</a></li>
                <li><a href="<?php echo base_url("home/logout") ?>"><i class="icon-power"></i>Log out</a></li>
            </ul>
        </div>
    </div>
</div>
</nav>

    <style>
    .evamo-install-app {
        gap: 6px;
        width: auto;
        padding: 0 10px;
        border-radius: 999px;
    }

    .evamo-install-app-text {
        font-size: 12px;
        font-weight: 600;
    }

    @media (max-width: 767.98px) {
        .evamo-install-app-text {
            display: none;
        }

        .evamo-install-app {
            width: 36px;
            padding: 0;
            justify-content: center;
        }
    }
    </style>
    <script>
    (function () {
        var deferredInstallPrompt = null;
        var installButtons = document.querySelectorAll('.evamo-install-app');
        var isIOS = /iphone|ipad|ipod/i.test(window.navigator.userAgent);
        var isStandalone = (window.matchMedia && window.matchMedia('(display-mode: standalone)').matches) || window.navigator.standalone === true;

        var setInstallButtonsHidden = function (hidden) {
            installButtons.forEach(function (btn) {
                btn.style.display = hidden ? 'none' : '';
                btn.disabled = hidden;
            });
        };

        if (!installButtons.length) {
            return;
        }

        if (isStandalone) {
            setInstallButtonsHidden(true);
            return;
        }

        installButtons.forEach(function (btn) {
            btn.addEventListener('click', async function () {
                if (deferredInstallPrompt) {
                    deferredInstallPrompt.prompt();
                    var choiceResult = await deferredInstallPrompt.userChoice;
                    deferredInstallPrompt = null;
                    if (choiceResult && choiceResult.outcome === 'accepted') {
                        setInstallButtonsHidden(true);
                    }
                    return;
                }

                if (isIOS) {
                    alert('On iPhone, open Share menu and tap Add to Home Screen.');
                    return;
                }

                alert('Install is not ready yet in this browser session. In Chrome, refresh once and try again.');
            });
        });

        window.addEventListener('beforeinstallprompt', function (event) {
            event.preventDefault();
            deferredInstallPrompt = event;
        });

        window.addEventListener('appinstalled', function () {
            setInstallButtonsHidden(true);
        });
    })();
    </script>
