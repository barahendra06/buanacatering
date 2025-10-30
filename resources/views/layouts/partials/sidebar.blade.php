<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar custom-sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div>
	    	<a href="{{ route('member-dashboard') }}">
                <img width="180px" height="180px" style="object-fit:cover" src="{{ route('image-show', ['medium', $currentMember->avatar_path_view]) }}" class="img-circle center-block" alt="User Image" />
            </a>
            </div>
            <div class="profile-name" style="margin-top:10px">
                {{ ucWords(strToLower($currentMember->name)) }} <br> ({{ ucWords($currentMember->user->role->role) }})
            </div>
        </div>

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <!-- Optionally, you can add icons to the links -->            
            
            <li class="@if(Route::current()->getName() == 'edit-profile' 
                            or Route::current()->getName() == 'change-password' 
                            or Route::current()->getName() == 'member-dashboard'
                            or Route::current()->getName() == 'member-activity'
                            or Route::current()->getName() == 'get-notification-all'
                            or Route::current()->getName() == 'conversation-list'
                            or Route::current()->getName() == 'redeem-history') active @endif treeview">
                <a href="#"><i class='fa fa-dashboard'></i> <span>My Account</span> <i class="fa fa-angle-right pull-right"></i></a>
                <ul class="treeview-menu">
                    @if($currentMember->user->isAdmin())
                    <li><a class="child-tree" href="{{ route('member-dashboard') }}">Dashboard</a></li>
                    @endif
                    <li><a class="child-tree" href="{{ route('edit-profile') }}">Edit Profile</a></li>
                    <li><a class="child-tree" href="{{ route('change-password') }}">Change Password</a></li>
                    <li><a class="child-tree" href="{{ route('get-notification-all', $currentMember->id) }}">Notifications</a></li>
                </ul>
            </li>
            @if($currentMember->user->isAdmin() or $currentMember->user->isEditor())	
            <li class="@if(Route::current()->getName() == 'short-url-create' or 
                            Route::current()->getName() == 'member-list' or 
                           Route::current()->getName() == 'setting-list' or 
                           Route::current()->getName() == 'newsletter-create' or
                           Route::current()->getName() == 'newsletter-list' or 
                           Route::current()->getName() == 'report-list' or 
                           Route::current()->getName() == 'redeem-list'
                        )
                           active 
                       @endif treeview custom-li">
                <a href="#"><i class='fa fa-user-secret'></i> <span>Admin</span> <i class="fa fa-angle-right pull-right"></i></a>
                <ul class="treeview-menu">
                @if($currentMember->user->isAdmin())
                    <li><a class="child-tree" href="{{ route('member-list') }}">Member List</a></li>
                    <li><a class="child-tree" href="{{ route('member-create') }}">Add User</a></li>
                @endif
                </ul>
            </li>      
            @endif 
            @can('list', \App\HseElement::class)
             <li class="@if(Route::current()->getName() == 'product-index' 
                            or Route::current()->getName() == 'product-create' 
                            or Route::current()->getName() == 'product-edit'
                            or Route::current()->getName() == 'product-category-index' 
                            or Route::current()->getName() == 'product-category-create' 
                            or Route::current()->getName() == 'product-category-edit') active @endif treeview">
                <a href="#"><i class='fa fa-book'></i> <span>Product</span> <i class="fa fa-angle-right pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a class="child-tree" href="{{ route('product-index') }}">Product List</a></li>
                    <li><a class="child-tree" href="{{ route('product-category-index') }}">Product Category</a></li>

                </ul>
            </li>
            <li class="@if(Route::current()->getName() == 'package-index'
                            or Route::current()->getName() == 'package-create'
                            or Route::current()->getName() == 'package-print'
                            or Route::current()->getName() == 'package-edit') active @endif treeview">
                <a href="#"><i class='fa fa-clock-o'></i> <span>Product Package</span> <i class="fa fa-angle-right pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a class="child-tree" href="{{ route('package-index') }}">Product Package List</a></li>
                </ul>
            </li>
            @endcan
        </ul>
    </section>
</aside>
