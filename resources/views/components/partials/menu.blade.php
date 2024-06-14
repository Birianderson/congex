<div class="d-flex flex-column flex-shrink-0 text-white bg-gray-100" style="width: 280px;">
    <a href="/" class=" ">
        <div class="text-start ms-5" style="height: 60px; width: 100%;">
            <img src="{{ asset('assets/image/logo.png') }}" alt="Logo" style="max-height: 100%; max-width: 100%; object-fit: contain;">
        </div>
    </a>
    <ul class="nav flex-column mb-auto mt-3" >
        <li class="nav-item">
            <a href="#" class="nav-link icon-md" aria-current="page">
                <i class="fa fa-home me-2"></i>
                Home
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white element-with-gradient">
                <i class="fa fa-home me-2"></i>
                Dashboard
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-black-50">
                <svg class="bi me-2" width="16" height="16"><use xlink:href="#table"></use></svg>
                Orders
            </a>
        </li>

    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
            <strong>mdo</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
            <li><a class="dropdown-item" href="#">New project...</a></li>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Sign out</a></li>
        </ul>
    </div>
</div>
