<?php
if (!isset($CI)) {
    $CI =& get_instance();
}
$shop_info = $CI->queries->get_shop_infoData();
$shop_name = !empty($shop_info->shop_name) ? $shop_info->shop_name : 'Shop';

$shop_logo = '';
if (!empty($shop_info->shop_logo)) {
    $shop_logo = $shop_info->shop_logo;
} elseif (!empty($shop_info->logo)) {
    $shop_logo = $shop_info->logo;
} elseif (!empty($shop_info->image)) {
    $shop_logo = $shop_info->image;
}

if (!function_exists('evamo_heroicon')) {
    function evamo_heroicon($name)
    {
        $icons = array(
            'x-mark' => '<svg class="evamo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18 18 6M6 6l12 12"/></svg>',
            'home' => '<svg class="evamo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m2.25 12 8.954-8.955a1.125 1.125 0 0 1 1.591 0L21.75 12M4.5 9.75V19.5A1.5 1.5 0 0 0 6 21h3.75v-5.25a1.5 1.5 0 0 1 1.5-1.5h1.5a1.5 1.5 0 0 1 1.5 1.5V21H18a1.5 1.5 0 0 0 1.5-1.5V9.75"/></svg>',
            'shopping-bag' => '<svg class="evamo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m2.25 7.5 1.591 10.182A2.25 2.25 0 0 0 6.066 19.5h11.868a2.25 2.25 0 0 0 2.225-1.818L21.75 7.5H2.25ZM9 7.5V6a3 3 0 1 1 6 0v1.5"/></svg>',
            'users' => '<svg class="evamo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18 18.72a8.94 8.94 0 0 0 3.75.78 8.94 8.94 0 0 0-3.75-.78Zm0 0a5.966 5.966 0 0 0-5.25-5.91m5.25 5.91a5.966 5.966 0 0 1-5.25-5.91m0 0a5.966 5.966 0 0 0-5.25 5.91m5.25-5.91a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm-8.25 5.91a8.966 8.966 0 0 1 3.75.78 8.966 8.966 0 0 1-3.75-.78Zm0 0A5.966 5.966 0 0 1 9 12.81"/></svg>',
            'squares' => '<svg class="evamo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 3.75h6.5v6.5h-6.5v-6.5Zm10 0h6.5v6.5h-6.5v-6.5Zm-10 10h6.5v6.5h-6.5v-6.5Zm10 0h6.5v6.5h-6.5v-6.5Z"/></svg>',
            'chart' => '<svg class="evamo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3v18h18M7.5 15.75v-4.5m4.5 4.5v-9m4.5 9v-6"/></svg>',
            'document' => '<svg class="evamo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19.5 14.25V6.75a2.25 2.25 0 0 0-2.25-2.25h-10.5A2.25 2.25 0 0 0 4.5 6.75v10.5a2.25 2.25 0 0 0 2.25 2.25H12m3-7.5h6m-6 3h6m-6 3h6"/></svg>',
            'banknotes' => '<svg class="evamo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 7.5h19.5v9h-19.5v-9Zm9.75 6a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm-7.5-4.5h.75m13.5 0h.75m-15 6h.75m13.5 0h.75"/></svg>',
            'archive-box' => '<svg class="evamo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 4.5h16.5v3h-16.5v-3Zm1.5 3v11.25A2.25 2.25 0 0 0 7.5 21h9a2.25 2.25 0 0 0 2.25-2.25V7.5M9.75 12h4.5"/></svg>',
            'check-badge' => '<svg class="evamo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12.75 11.25 15l3.75-3.75M8.25 3.75h7.5l.75 1.5 1.5.75V9l1.5 2.25-1.5 2.25v3l-1.5.75-.75 1.5h-7.5l-.75-1.5-1.5-.75v-3L4.5 11.25 6 9V6l1.5-.75.75-1.5Z"/></svg>',
            'building-office' => '<svg class="evamo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 21h16.5M5.25 21V6.75A2.25 2.25 0 0 1 7.5 4.5h9A2.25 2.25 0 0 1 18.75 6.75V21M9 9.75h.008v.008H9V9.75Zm0 3.75h.008v.008H9V13.5Zm0 3.75h.008v.008H9v-.008Zm3-7.5h.008v.008H12V9.75Zm0 3.75h.008v.008H12V13.5Zm0 3.75h.008v.008H12v-.008Zm3-7.5h.008v.008H15V9.75Zm0 3.75h.008v.008H15V13.5Zm0 3.75h.008v.008H15v-.008Z"/></svg>',
        );

        return isset($icons[$name]) ? $icons[$name] : $icons['squares'];
    }
}

if ($_SESSION['role'] == 'admin') {
 ?>
<script>
(function () {
    if (window.__evamoNativeSidebarToggleBound) {
        return;
    }
    window.__evamoNativeSidebarToggleBound = true;

    function hasClass(el, className) {
        return !!el && !!el.className && (' ' + el.className + ' ').indexOf(' ' + className + ' ') > -1;
    }

    function findToggleAnchor(target) {
        while (target && target !== document) {
            if (target.tagName === 'A' && hasClass(target, 'has-arrow')) {
                return target;
            }
            target = target.parentNode;
        }
        return null;
    }

    function firstDirectChildByTag(el, tagName) {
        if (!el || !el.children) {
            return null;
        }
        for (var i = 0; i < el.children.length; i++) {
            if (el.children[i].tagName === tagName) {
                return el.children[i];
            }
        }
        return null;
    }

    function closeSiblingMenus(parentList) {
        if (!parentList || !parentList.children) {
            return;
        }

        for (var i = 0; i < parentList.children.length; i++) {
            var li = parentList.children[i];
            if (!li || li.nodeType !== 1) {
                continue;
            }

            var childUl = firstDirectChildByTag(li, 'UL');
            var childA = firstDirectChildByTag(li, 'A');

            if (childUl) {
                childUl.style.display = 'none';
                childUl.style.height = '';
                childUl.style.overflow = '';
                childUl.className = childUl.className
                    .replace(/\bin\b/g, '')
                    .replace(/\bshow\b/g, '')
                    .replace(/\bcollapsing\b/g, '')
                    .replace(/\bmm-collapsing\b/g, '')
                    .trim();
            }
            if (childA && hasClass(childA, 'has-arrow')) {
                childA.setAttribute('aria-expanded', 'false');
            }
            if (li.classList) {
                li.classList.remove('active');
            } else {
                li.className = li.className.replace(/\bactive\b/g, '').trim();
            }
        }
    }

    function openMenu(li, anchor, ul) {
        ul.style.display = 'block';
        ul.style.height = 'auto';
        ul.style.overflow = 'visible';
        if ((' ' + ul.className + ' ').indexOf(' in ') === -1) {
            ul.className += ' in';
        }
        if ((' ' + ul.className + ' ').indexOf(' show ') === -1) {
            ul.className += ' show';
        }
        ul.className = ul.className
            .replace(/\bcollapsing\b/g, '')
            .replace(/\bmm-collapsing\b/g, '')
            .trim();
        anchor.setAttribute('aria-expanded', 'true');
        if (li.classList) {
            li.classList.add('active');
        } else if ((' ' + li.className + ' ').indexOf(' active ') === -1) {
            li.className += ' active';
        }
    }

    function closeMenu(li, anchor, ul) {
        ul.style.display = 'none';
        ul.style.height = '';
        ul.style.overflow = '';
        ul.className = ul.className
            .replace(/\bin\b/g, '')
            .replace(/\bshow\b/g, '')
            .replace(/\bcollapsing\b/g, '')
            .replace(/\bmm-collapsing\b/g, '')
            .trim();
        anchor.setAttribute('aria-expanded', 'false');
        if (li.classList) {
            li.classList.remove('active');
        } else {
            li.className = li.className.replace(/\bactive\b/g, '').trim();
        }
    }

    document.addEventListener('click', function (event) {
        var anchor = findToggleAnchor(event.target);
        if (!anchor) {
            return;
        }

        var li = anchor.parentNode;
        var ul = li ? firstDirectChildByTag(li, 'UL') : null;
        var parentList = li ? li.parentNode : null;

        if (!li || !ul || !parentList || parentList.className.indexOf('main-menu') === -1) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();
        if (event.stopImmediatePropagation) {
            event.stopImmediatePropagation();
        }

        var isOpen = ul.style.display === 'block' || (li.className && li.className.indexOf('active') !== -1);
        closeSiblingMenus(parentList);

        if (!isOpen) {
            openMenu(li, anchor, ul);
        } else {
            closeMenu(li, anchor, ul);
        }
    }, true);
})();
</script>
<aside id="left-sidebar" class="sidebar evamo-sidebar" aria-label="Sidenav">
<div class="sidebar-scroll">
    <div class="mt-3">
     
        
    </div>

    <div class="sidebar-tab-switch-container">
        <div class="sidebar-tab-switch">
            <button type="button" class="sidebar-tab-btn active" data-sidebar-tab-target="#sidebar-menu-tab">Menu</button>
            <button type="button" class="sidebar-tab-btn" data-sidebar-tab-target="#sidebar-report-tab">Report</button>
        </div>

        <div class="tab-content p-l-0 p-r-0">
            <div class="tab-pane sidebar-tab-pane active" id="sidebar-menu-tab">
                <nav class="sidebar-nav">
                    <ul class="main-menu metismenu">
                        <li><a href="<?php echo base_url("admin/index") ?>"><?php echo evamo_heroicon('home'); ?><span>Dashboard</span></a></li>
                        <li><a href="<?php echo base_url("admin/admin_sell") ?>"><?php echo evamo_heroicon('shopping-bag'); ?><span>Sell</span></a></li>

                        <li><a href="javascript:void(0);" class="has-arrow"><?php echo evamo_heroicon('users'); ?><span>Users</span></a>
                            <ul>
                                <li><a href="<?php echo base_url("admin/users") ?>">Register</a></li>
                                <li><a href="<?php echo base_url("admin/payRol") ?>">Payrol</a></li>
                            </ul>
                        </li>

                        <li><a href="javascript:void(0);" class="has-arrow"><?php echo evamo_heroicon('squares'); ?><span>Products</span></a>
                            <ul>
                                <li><a href="<?php echo base_url("admin/product") ?>">Add products</a></li>
                                <li><a href="<?php echo base_url("admin/all_product") ?>">All products</a></li>
                                <li><a href="<?php echo base_url("admin/stock_balance_history") ?>">Stock Balance History</a></li>
                            </ul>
                        </li>

                        <li><a href="javascript:void(0);" class="has-arrow"><?php echo evamo_heroicon('archive-box'); ?><span>Store</span></a>
                            <ul>
                                <li><a href="<?php echo base_url("admin/all_productStore") ?>">Products Available</a></li>
                            </ul>
                        </li>

                        <li><a href="<?php echo base_url("admin/view_product_movement") ?>"><?php echo evamo_heroicon('chart'); ?><span>Frequency Movement</span></a></li>

                        <li><a href="javascript:void(0);" class="has-arrow"><?php echo evamo_heroicon('chart'); ?><span>Today Sales</span></a>
                            <ul>
                                <li><a href="<?php echo base_url("admin/sales_today") ?>">General sales</a></li>
                                <li><a href="<?php echo base_url("admin/retail_sales") ?>">RetailSales Report</a></li>
                                <li><a href="<?php echo base_url("admin/whole_sale") ?>">WholeSales Report</a></li>
                                <li><a href="<?php echo base_url("admin/privious_data") ?>">Previous Retail & WholeSales Report</a></li>
                                <li><a href="<?php echo base_url("admin/general_sells_product"); ?>">Seller Report</a></li>
                            </ul>
                        </li>

                        <li><a href="<?php echo base_url("admin/inventory"); ?>"><?php echo evamo_heroicon('archive-box'); ?><span>Inventory</span></a></li>
                        <li><a href="<?php echo base_url("admin/get_all_cashFlow") ?>"><?php echo evamo_heroicon('banknotes'); ?><span>Cash Flow System</span></a></li>

                        <li><a href="javascript:void(0);" class="has-arrow"><?php echo evamo_heroicon('document'); ?><span>Daily purchase</span></a>
                            <ul>
                                <li><a href="<?php echo base_url("admin/supplier") ?>">Register Supplier</a></li>
                                <li><a href="<?php echo base_url("admin/place_order") ?>">Place Order</a></li>
                                <li><a href="<?php echo base_url("admin/order_record") ?>">Order History</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="tab-pane sidebar-tab-pane" id="sidebar-report-tab">
                <nav class="sidebar-nav">
                    <ul class="main-menu metismenu">
                        <li><a href="<?php echo base_url("admin/today_salesReport") ?>"><?php echo evamo_heroicon('chart'); ?><span>Today's Sales</span></a></li>
                        <li><a href="<?php echo base_url("admin/today_cashflowData") ?>"><?php echo evamo_heroicon('banknotes'); ?><span>Today's Cashflow</span></a></li>
                        <li><a href="<?php echo base_url("admin/general_cashflowData") ?>"><?php echo evamo_heroicon('banknotes'); ?><span>General Cashflow</span></a></li>
                        <li><a href="<?php echo base_url("admin/all_productData") ?>"><?php echo evamo_heroicon('squares'); ?><span>All Product</span></a></li>
                        <li><a href="<?php echo base_url("admin/sales_productData") ?>"><?php echo evamo_heroicon('squares'); ?><span>Selling Price</span></a></li>
                        <li><a href="<?php echo base_url("admin/buying_price") ?>"><?php echo evamo_heroicon('squares'); ?><span>Buying Price</span></a></li>
                        <li><a href="<?php echo base_url("admin/empty_productData") ?>"><?php echo evamo_heroicon('squares'); ?><span>Empty Product</span></a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
</aside>
<?php } else { ?>
<aside id="left-sidebar" class="sidebar evamo-sidebar" aria-label="Sidenav">
<div class="sidebar-scroll">

    <div class="sidebar-shop-header">
        <button type="button" class="evamo-sidebar-close" aria-label="Close sidebar">
            <?php echo evamo_heroicon('x-mark'); ?>
        </button>
        <div class="sidebar-shop-meta">
            <span class="sidebar-shop-icon"><?php echo evamo_heroicon('building-office'); ?></span>
            <div class="sidebar-shop-name"><?php echo $shop_name; ?></div>
        </div>
    </div>

    <div class="tab-content p-l-0 p-r-0">
        <div class="tab-pane active" id="menu">
            <nav class="sidebar-nav">
                <ul class="main-menu metismenu">
                    <li><a href="<?php echo base_url("seller/index") ?>"><?php echo evamo_heroicon('home'); ?><span>Sale</span></a></li>

                    <?php foreach ($privillage as $privillages): ?>
                    <?php if ($privillages->privillage == 'seller') { ?>
                    <li><a href="javascript:void(0);" class="has-arrow"><?php echo evamo_heroicon('chart'); ?><span>Today sales</span></a>
                        <ul>
                            <li><a href="<?php echo base_url("seller/today_salles") ?>">General sales Report</a></li>
                            <li><a href="<?php echo base_url("seller/retail_sale") ?>">RetailSale Report</a></li>
                            <li><a href="<?php echo base_url("seller/whore_sale"); ?>">WholeSale Report</a></li>
                        </ul>
                    </li>
                    <li><a href="<?php echo base_url("seller/cash_flow") ?>"><?php echo evamo_heroicon('banknotes'); ?><span>Cash Flow</span></a></li>
                    <?php } elseif ($privillages->privillage == 'store') { ?>
                    <li><a href="javascript:void(0);" class="has-arrow"><?php echo evamo_heroicon('archive-box'); ?><span>Adjustment</span></a>
                        <ul>
                            <li><a href="<?php echo base_url("admin/all_productStore") ?>">Products Available</a></li>
                        </ul>
                    </li>
                    <?php } elseif ($privillages->privillage == 'product') { ?>
                    <li><a href="javascript:void(0);" class="has-arrow"><?php echo evamo_heroicon('squares'); ?><span>Products</span></a>
                        <ul>
                            <li><a href="<?php echo base_url("admin/product") ?>">Add products</a></li>
                            <li><a href="<?php echo base_url("admin/all_product") ?>">All products</a></li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
    </div>

</div>
</aside>
<?php } ?>