<section>
    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <!-- User Info -->
        <div class="user-info">
            <div class="image">
                <img src="{{ asset('/asset/images/user.png') }}" width="48" height="48" alt="User" />
            </div>
            <div class="info-container">
                <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }}</div>
                <div class="email">{{ Auth::user()->email }}</div>
                <div class="btn-group user-helper-dropdown">
                    <i class="material-icons new_icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="#"><i class="material-icons">person</i>Profile</a></li>
                        <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="material-icons">input</i>Sign Out </a>
                        </li>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </ul>
                </div>
            </div>
        </div>
        <!-- #User Info -->
        <!-- Menu -->
        <div class="menu">
            <ul class="list">
                <li class="header">MAIN NAVIGATION</li>
                <li class="active">
                    <a href="/home">
                        <i class="material-icons">local_post_office</i>
                        <span>Posts</span>
                    </a>
                </li>
            </ul>
        </div>


    </aside>
    <!-- #END# Left Sidebar -->

</section>