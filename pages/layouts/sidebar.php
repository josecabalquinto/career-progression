<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>

                    </span>
                </li>
                <li class="<?= ($_GET['page'] == 'dashboard') ? 'active' : '' ?>">
                    <a href="?page=dashboard">
                        <i class="la la-dashboard"></i> <span> Dashboard</span>
                    </a>
                </li>
                <li class="<?= ($_GET['page'] == 'graduate') ? 'active' : '' ?>">
                    <a href="?page=graduate">
                        <i class="fa fa-graduation-cap"></i> <span> Graduates</span>
                    </a>
                </li>
                <li class="<?= ($_GET['page'] == 'college') ? 'active' : '' ?>">
                    <a href="?page=college">
                        <i class="la la-university"></i> <span> Colleges</span>
                    </a>
                </li>
                <li class="<?= ($_GET['page'] == 'batch') ? 'active' : '' ?>">
                    <a href="?page=batch">
                        <i class="la la-archive"></i> <span> Batches</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>