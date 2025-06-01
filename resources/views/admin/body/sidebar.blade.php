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
                <li>
                    <a href="{{ route('customer.all') }}" class="waves-effect">
                        <i class="ri-shield-user-fill" aria-hidden="true"></i>
                        <span>Manage Customers</span>
                    </a>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-map-pin-line text-xl"></i>
                        <span>Tracking</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('trackings.index') }}">Manage Tracking</a></li>
                        <li><a href="{{ route('trackings.parcel_status') }}">Parcel Status</a></li>

                        <li><a href="https://primegorkha.aftership.com"
                                onclick="window.open(this.href, '_blank'); return false;">Track</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-wallet-line text-xl"></i>
                        <span>Payment</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('payments.dashboard') }}">Payment Dashboard</a></li>
                        <li><a href="{{ route('payments.index') }}">Manage Payment</a></li>
                        <li><a href="{{ route('payments.details') }}">Expenses Management</a></li>
                        <li><a href="{{ route('payments.manage') }}">Agencies Expenses</a></li>


                    </ul>
                </li>
                <li>
                    <a href="{{ route('usermgmt.index') }}" class="waves-effect">
                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                        <span>Manage User</span>
                    </a>
                </li>
              
                <li>
                    <a href="{{ route('dispatch.shipment') }}" class="waves-effect">
                        <i class="ri-shield-user-fill" aria-hidden="true"></i>
                        <span>Shipment</span>
                    </a>
                <li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fa-solid fa-briefcase" aria-hidden="true"></i>
                        <span>Agencies</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('agencies.index') }}"> Agencies Details</a></li>
                        <li><a href="{{ route('agencies.dispatch') }}">Agencies Dispatch </a></li>
                        <li><a href="{{ route('agencies.payment') }}"> Agencies Payment</a></li>
                        <li><a href="{{ route('agencies.shipment') }}">Agencies Shipment</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fa-solid fa-briefcase" aria-hidden="true"></i>
                        <span>Airline </span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('airline.index') }}"> Airline Details</a></li>
                        <li><a href="{{ route('airline.dispatch') }}">Airline Dispatch </a></li>
                        <li><a href="{{ route('airline.payment') }}"> Airline Payment</a></li>
                        <li><a href="{{ route('airline.shipment') }}">Airline Shipment</a></li>

                    </ul>
                </li>


                <li>
                    <a href="{{ route('customer.recyclebin') }}" class="waves-effect">

                        <i class="fas fa-trash" aria-hidden="true"></i>
                        <span>Recycle User</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>