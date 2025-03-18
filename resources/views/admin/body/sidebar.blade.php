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
                
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-map-pin-line text-xl"></i>
                        <span>Tracking</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('trackings.index') }}">Manage Tracking</a></li>
                        <li><a href="https://primegorkha.aftership.com" onclick="window.open(this.href, '_blank'); return false;">Track</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-wallet-line text-xl"></i>
                        <span>Payment</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('payments.index') }}">Manage Payment</a></li>
                        <li><a  href="{{ route('payments.details') }}"   >Payment Details</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>