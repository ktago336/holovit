<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="treeview <?php if(isset($actdashboard)): ?><?php echo e('active'); ?><?php endif; ?>">
                <a href="<?php echo e(HTTP_PATH); ?>/admin/admins/dashboard">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
           
            <li class="treeview <?php if(isset($actchangeusername) || isset($actchangepassword) || isset($actchangeemail) || isset($actsitesetting) || isset($actwhybuy)): ?><?php echo e('active'); ?><?php endif; ?>">
                <a href="javascript:void(0)">
                    <i class="fa fa-gears"></i> <span>Configuration</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php if(isset($actchangeusername)): ?><?php echo e('active'); ?><?php endif; ?>"><a href="<?php echo e(HTTP_PATH); ?>/admin/admins/change-username"><i class="fa fa-circle-o"></i> Change Username</a></li>
                    <li class="<?php if(isset($actchangepassword)): ?><?php echo e('active'); ?><?php endif; ?>"><a href="<?php echo e(HTTP_PATH); ?>/admin/admins/change-password"><i class="fa fa-circle-o"></i> Change Password</a></li>
                    <li class="<?php if(isset($actchangeemail)): ?><?php echo e('active'); ?><?php endif; ?>"><a href="<?php echo e(HTTP_PATH); ?>/admin/admins/change-email"><i class="fa fa-circle-o"></i> Change Email</a></li>
                    <li class="<?php if(isset($actsitesetting)): ?><?php echo e('active'); ?><?php endif; ?>"><a href="<?php echo e(HTTP_PATH); ?>/admin/admins/site-settings"><i class="fa fa-circle-o"></i> Site Settings</a></li>
                    <li class="<?php if(isset($actwhybuy)): ?><?php echo e('active'); ?><?php endif; ?>"><a href="<?php echo e(HTTP_PATH); ?>/admin/admins/why-buy"><i class="fa fa-circle-o"></i> Why Buy Section</a></li>
                
                </ul>
            </li> 
            
            <li class="treeview <?php if(isset($actmerchants)): ?><?php echo e('active'); ?><?php endif; ?>">
                <a href="<?php echo e(HTTP_PATH); ?>/admin/admins/merchant">
                    <i class="fa fa-users"></i> <span>Merchants</span> 
                </a>
            </li> 
            
              
           
            <li class="treeview <?php if(isset($actusers)): ?><?php echo e('active'); ?><?php endif; ?>">
                <a href="<?php echo e(HTTP_PATH); ?>/admin/users">
                    <i class="fa fa-user"></i> <span>Customers</span> 
                </a>
               
            </li> 
<!--            <li class="treeview <?php if(isset($actservices)): ?><?php echo e('active'); ?><?php endif; ?>">
                <a href="<?php echo e(HTTP_PATH); ?>/admin/services">
                    <i class="fa fa-list"></i> <span>Services</span> 
                </a>
               
            </li>-->
			<li class="treeview <?php if(isset($actcountries)): ?><?php echo e('active'); ?><?php endif; ?>">
                <a href="<?php echo e(HTTP_PATH); ?>/admin/countries">
                    <i class="fa fa-map"></i> <span>Countries</span> 
                </a>
            </li> 
           <li class="treeview <?php if(isset($actcategories)): ?><?php echo e('active'); ?><?php endif; ?>">
                <a href="<?php echo e(HTTP_PATH); ?>/admin/categories">
                    <i class="fa fa-sitemap"></i> <span>Categories</span> 
                </a>
            </li>  
           <!--<li class="treeview <?php if(isset($actbrands)): ?><?php echo e('active'); ?><?php endif; ?>">
                <a href="<?php echo e(HTTP_PATH); ?>/admin/brands">
                    <i class="fa fa-suitcase"></i> <span>Brands</span> 
                </a>
            </li>  
           <li class="treeview <?php if(isset($actlocations)): ?><?php echo e('active'); ?><?php endif; ?>">
                <a href="<?php echo e(HTTP_PATH); ?>/admin/locations">
                    <i class="fa fa-location-arrow"></i> <span>Locations</span> 
                </a>
            </li>  
             <li class="treeview <?php if(isset($actbusiness_types)): ?><?php echo e('active'); ?><?php endif; ?>">
                <a href="<?php echo e(HTTP_PATH); ?>/admin/business_types">
                    <i class="fa fa-legal"></i> <span>Business Types</span> 
                </a>
            </li> 
             <li class="treeview <?php if(isset($actproducts)): ?><?php echo e('active'); ?><?php endif; ?>">
                <a href="<?php echo e(HTTP_PATH); ?>/admin/products">
                    <i class="fa fa-tags"></i> <span>Products</span> 
                </a>
               
            </li>-->
            <li class="treeview <?php if(isset($actdeals)): ?><?php echo e('active'); ?><?php endif; ?>">
                <a href="<?php echo e(HTTP_PATH); ?>/admin/deals">
                    <i class="fa fa-tags"></i> <span>Deals</span> 
                </a>
               
            </li>
            <!--<li class="treeview <?php if(isset($actcoupons)): ?><?php echo e('active'); ?><?php endif; ?>">
                <a href="<?php echo e(HTTP_PATH); ?>/admin/coupons">
                    <i class="fa fa-tags"></i> <span>Coupons</span> 
                </a>
               
            </li>-->
             <li class="treeview <?php if(isset($actorders)): ?><?php echo e('active'); ?><?php endif; ?>">
                <a href="<?php echo e(HTTP_PATH); ?>/admin/orders">
                    <i class="fa fa-shopping-bag"></i> <span>Orders</span> 
                </a>
              
            </li> 
			<li class="treeview <?php if(isset($actpayments)): ?><?php echo e('active'); ?><?php endif; ?>">
                <a href="<?php echo e(HTTP_PATH); ?>/admin/payments">
                    <i class="fa fa-history"></i> <span>Payments</span> 
                </a>
              
            </li> 
			<li class="treeview <?php if(isset($actwithdrawals)): ?><?php echo e('active'); ?><?php endif; ?>">
                <a href="<?php echo e(HTTP_PATH); ?>/admin/wallets/withdrawals">
                    <i class="fa fa-money"></i> <span>Withdrawals</span> 
                </a>
              
            </li> 
         
<!--             <li class="treeview <?php if(isset($actreports)): ?><?php echo e('active'); ?><?php endif; ?>">
                <a href="<?php echo e(HTTP_PATH); ?>/admin/reports">
                    <i class="fa fa-file-text"></i> <span>Account Reports</span> 
                </a>
       
            </li> -->
           
<!--            <li class="treeview <?php if(isset($acttestimonials)): ?><?php echo e('active'); ?><?php endif; ?>">
                <a href="<?php echo e(HTTP_PATH); ?>/admin/testimonials">
                    <i class="fa fa-list"></i> <span>Testimonials</span> 
                </a>
            </li>  -->
           
			<li class="treeview <?php if(isset($actbanners)): ?><?php echo e('active'); ?><?php endif; ?>">
                <a href="<?php echo e(HTTP_PATH); ?>/admin/banners">
                    <i class="fa fa-bullhorn"></i> <span>Banners</span> 
                </a>
            
            </li>
            <li class="treeview <?php if(isset($actpages)): ?><?php echo e('active'); ?><?php endif; ?>">
                <a href="<?php echo e(HTTP_PATH); ?>/admin/pages">
                    <i class="fa fa-file-text-o"></i> <span>Pages</span> 
                </a>
            
            </li> 
          
        </ul>
    </section>
</aside><?php /**PATH /home/holovitr/domains/holovit.ru/public_html/resources/views/elements/admin/left_menu.blade.php ENDPATH**/ ?>