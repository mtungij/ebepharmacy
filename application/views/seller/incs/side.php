<?php
    $CI =& get_instance();
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
                'building-office' => '<svg class="evamo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 21h16.5M5.25 21V6.75A2.25 2.25 0 0 1 7.5 4.5h9A2.25 2.25 0 0 1 18.75 6.75V21M9 9.75h.008v.008H9V9.75Zm0 3.75h.008v.008H9V13.5Zm0 3.75h.008v.008H9v-.008Zm3-7.5h.008v.008H12V9.75Zm0 3.75h.008v.008H12V13.5Zm0 3.75h.008v.008H12v-.008Zm3-7.5h.008v.008H15V9.75Zm0 3.75h.008v.008H15V13.5Zm0 3.75h.008v.008H15v-.008Z"/></svg>',
                'chart' => '<svg class="evamo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3v18h18M7.5 15.75v-4.5m4.5 4.5v-9m4.5 9v-6"/></svg>',
                'banknotes' => '<svg class="evamo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 7.5h19.5v9h-19.5v-9Zm9.75 6a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm-7.5-4.5h.75m13.5 0h.75m-15 6h.75m13.5 0h.75"/></svg>',
                'archive-box' => '<svg class="evamo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 4.5h16.5v3h-16.5v-3Zm1.5 3v11.25A2.25 2.25 0 0 0 7.5 21h9a2.25 2.25 0 0 0 2.25-2.25V7.5M9.75 12h4.5"/></svg>',
                'squares' => '<svg class="evamo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 3.75h6.5v6.5h-6.5v-6.5Zm10 0h6.5v6.5h-6.5v-6.5Zm-10 10h6.5v6.5h-6.5v-6.5Zm10 0h6.5v6.5h-6.5v-6.5Z"/></svg>'
            );

            return isset($icons[$name]) ? $icons[$name] : $icons['squares'];
        }
    }
?>
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
                <?php foreach ($privillage as $privillages): ?>
                <?php if ($privillages->privillage == 'seller') { ?>
                <ul class="main-menu metismenu">
                    <li><a href="<?php echo base_url("seller/index") ?>"><?php echo evamo_heroicon('home'); ?><span>Sale</span></a></li>
                    <li><a href="javascript:void(0);" class="has-arrow"><?php echo evamo_heroicon('chart'); ?><span>Today sales</span></a>
                        <ul>
                            <li><a href="<?php echo base_url("seller/today_salles") ?>">General sales Report</a></li>
                            <li><a href="<?php echo base_url("seller/retail_sale") ?>">RetailSale Report</a></li>
                            <li><a href="<?php echo base_url("seller/whore_sale"); ?>">WholeSale Report</a></li>
                        </ul>
                    </li>
                    <li><a href="<?php echo base_url("seller/cash_flow") ?>"><?php echo evamo_heroicon('banknotes'); ?><span>Cash Flow</span></a></li>

                <?php } elseif ($privillages->privillage == 'store') { ?>
                    <li><a href="javascript:void(0);" class="has-arrow"><?php echo evamo_heroicon('archive-box'); ?><span>Store</span></a>
                        <ul>
                            <li><a href="<?php echo base_url("admin/produc_available_store") ?>">Store Product Available</a></li>
                            <li><a href="<?php echo base_url("admin/dispency_product") ?>">Dispency</a></li>
                            <li><a href="<?php echo base_url("admin/all_productStore") ?>">Pharmacy Products Available</a></li>
                            <li><a href="<?php echo base_url("admin/view_product_movement") ?>">Product Stock Movement</a></li>
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