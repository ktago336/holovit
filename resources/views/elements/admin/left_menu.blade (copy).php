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
            <li class="treeview @if(isset($actstaffs)){{'active'}}@endif">
                <a href="javascript:void(0)">
                    <i class="fa fa-users"></i> <span>Manage Staffs</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="@if(isset($actstaffs)){{'active'}}@endif"><a href="{{HTTP_PATH}}/admin/admins/staff"><i class="fa fa-circle-o"></i>Staffs List</a></li>
                </ul>
            </li> 
            <li class="treeview @if(isset($actusers)){{'active'}}@endif">
                <a href="javascript:void(0)">
                    <i class="fa fa-users"></i> <span>Manage Users</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="@if(isset($actusers)){{'active'}}@endif"><a href="{{HTTP_PATH}}/admin/users"><i class="fa fa-circle-o"></i>Users List</a></li>
                </ul>
            </li>  

            <li class="treeview @if(isset($actpages)){{'active'}}@endif">
                <a href="javascript:void(0)">
                    <i class="fa fa-file-text-o"></i> <span>Manage Pages</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="@if(isset($actpages)){{'active'}}@endif"><a href="{{HTTP_PATH}}/admin/pages"><i class="fa fa-circle-o"></i>Page List</a></li>
                </ul>
            </li> 
        </ul>
    </section>
</aside>