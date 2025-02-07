<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!-- User details -->


        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Prime Gurkha</li>

                <li>
                    <a href="{{ url('/dashboard') }}" class="waves-effect">
                        <i class="ri-home-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>





                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-shield-user-fill"></i>
                        <span>Manage Customers</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('customer.all') }}">All Customers</a></li>

                    </ul>
                </li>
                
                <li class="mb-2">
                    <a href="javascript:void(0);" class="has-arrow flex items-center px-4 py-3 rounded-lg bg-gray-800 hover:bg-orange-500 hover:text-black transition">
                        <i class="ri-group-fill text-xl"></i>
                        <span class="ml-3 sidebar-text">Manage Staff</span>
                    </a>
                    <ul class="sub-menu ml-6 mt-1">
                        <li>
                            <a href="#">All Staff</a>
                        </li>
                    </ul>
                </li>











            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>