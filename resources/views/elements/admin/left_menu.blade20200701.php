<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="treeview @if(isset($actdashboard)){{'active'}}@endif">
                <a href="{{HTTP_PATH}}/admin/admins/dashboard">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
           
            <li class="treeview @if(isset($actchangeusername) || isset($actchangepassword) || isset($actchangeemail) || isset($actsitesetting)){{'active'}}@endif">
                <a href="javascript:void(0)">
                    <i class="fa fa-gears"></i> <span>Configuration</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="@if(isset($actchangeusername)){{'active'}}@endif"><a href="{{HTTP_PATH}}/admin/admins/change-username"><i class="fa fa-circle-o"></i> Change Username</a></li>
                    <li class="@if(isset($actchangepassword)){{'active'}}@endif"><a href="{{HTTP_PATH}}/admin/admins/change-password"><i class="fa fa-circle-o"></i> Change Password</a></li>
                    <li class="@if(isset($actchangeemail)){{'active'}}@endif"><a href="{{HTTP_PATH}}/admin/admins/change-email"><i class="fa fa-circle-o"></i> Change Email</a></li>
                    <li class="@if(isset($actsitesetting)){{'active'}}@endif"><a href="{{HTTP_PATH}}/admin/admins/site-settings"><i class="fa fa-circle-o"></i> Site Settings</a></li>
                
                </ul>
            </li> 
            
            <li class="treeview @if(isset($actmerchants)){{'active'}}@endif">
                <a href="{{HTTP_PATH}}/admin/admins/merchant">
                    <i class="fa fa-users"></i> <span>Merchants</span> 
                </a>
            </li> 
            
              
           
            <li class="treeview @if(isset($actusers)){{'active'}}@endif">
                <a href="{{HTTP_PATH}}/admin/users">
                    <i class="fa fa-user"></i> <span>Users</span> 
                </a>
               
            </li> 
<!--            <li class="treeview @if(isset($actservices)){{'active'}}@endif">
                <a href="{{HTTP_PATH}}/admin/services">
                    <i class="fa fa-list"></i> <span>Services</span> 
                </a>
               
            </li>-->
			<li class="treeview @if(isset($actcountries)){{'active'}}@endif">
                <a href="{{HTTP_PATH}}/admin/countries">
                    <i class="fa fa-map"></i> <span>Countries</span> 
                </a>
            </li> 
           <li class="treeview @if(isset($actcategories)){{'active'}}@endif">
                <a href="{{HTTP_PATH}}/admin/categories">
                    <i class="fa fa-sitemap"></i> <span>Categories</span> 
                </a>
            </li>  
           <!--<li class="treeview @if(isset($actbrands)){{'active'}}@endif">
                <a href="{{HTTP_PATH}}/admin/brands">
                    <i class="fa fa-suitcase"></i> <span>Brands</span> 
                </a>
            </li>  
           <li class="treeview @if(isset($actlocations)){{'active'}}@endif">
                <a href="{{HTTP_PATH}}/admin/locations">
                    <i class="fa fa-location-arrow"></i> <span>Locations</span> 
                </a>
            </li>  
             <li class="treeview @if(isset($actbusiness_types)){{'active'}}@endif">
                <a href="{{HTTP_PATH}}/admin/business_types">
                    <i class="fa fa-legal"></i> <span>Business Types</span> 
                </a>
            </li> 
             <li class="treeview @if(isset($actproducts)){{'active'}}@endif">
                <a href="{{HTTP_PATH}}/admin/products">
                    <i class="fa fa-tags"></i> <span>Products</span> 
                </a>
               
            </li>-->
            <li class="treeview @if(isset($actdeals)){{'active'}}@endif">
                <a href="{{HTTP_PATH}}/admin/deals">
                    <i class="fa fa-tags"></i> <span>Deals</span> 
                </a>
               
            </li>
            <!--<li class="treeview @if(isset($actcoupons)){{'active'}}@endif">
                <a href="{{HTTP_PATH}}/admin/coupons">
                    <i class="fa fa-tags"></i> <span>Coupons</span> 
                </a>
               
            </li>-->
             <li class="treeview @if(isset($actorders)){{'active'}}@endif">
                <a href="{{HTTP_PATH}}/admin/orders">
                    <i class="fa fa-shopping-bag"></i> <span>Orders</span> 
                </a>
              
            </li> 
			<li class="treeview @if(isset($actpayments)){{'active'}}@endif">
                <a href="{{HTTP_PATH}}/admin/payments">
                    <i class="fa fa-history"></i> <span>Payments</span> 
                </a>
              
            </li> 
         
<!--             <li class="treeview @if(isset($actreports)){{'active'}}@endif">
                <a href="{{HTTP_PATH}}/admin/reports">
                    <i class="fa fa-file-text"></i> <span>Account Reports</span> 
                </a>
       
            </li> -->
           
<!--            <li class="treeview @if(isset($acttestimonials)){{'active'}}@endif">
                <a href="{{HTTP_PATH}}/admin/testimonials">
                    <i class="fa fa-list"></i> <span>Testimonials</span> 
                </a>
            </li>  -->
           
         
            <li class="treeview @if(isset($actpages)){{'active'}}@endif">
                <a href="{{HTTP_PATH}}/admin/pages">
                    <i class="fa fa-file-text-o"></i> <span>Pages</span> 
                </a>
            
            </li> 
          
        </ul>
    </section>
</aside>