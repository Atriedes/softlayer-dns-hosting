    <div class="sidebar-nav">
        <a href="{{ URL::to('dashboard/list-domain') }}" class="nav-header"><i class="icon-dashboard"></i>Dashboard</a>

        <a href="#domain-menu" class="nav-header collapsed" data-toggle="collapse"><i class="icon-briefcase"></i></i>Domain <i class="icon-chevron-up"></i></a>
        <ul id="domain-menu" class="nav nav-list collapse">
            <li ><a href="{{ URL::to('dashboard/list-domain') }}">List Domain</a></li>
            <li ><a href="{{ URL::to('dashboard/add-domain') }}">Add Domain</a></li>
        </ul>

        <a href="{{ URL::to('dashboard/help') }}" class="nav-header" ><i class="icon-question-sign"></i>Help</a>
    </div>