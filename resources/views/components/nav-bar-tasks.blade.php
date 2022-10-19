<div class="div" style="width:80%">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg" style="background-color: #e9f1f7;">
        <!-- Container wrapper -->
        <div class="container-fluid">
            <!-- Toggle button -->
            <button class="navbar-toggler" type="button" data-mdb-toggle="collapse"
                data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Collapsible wrapper -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item" style="margin-right:20px">
                        <a class="nav-link link-info" href="#">Project Name</a>
                    </li>
                    <li class="nav-item">
                        <img src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp" class="rounded-circle" height="40"
                            alt="Black and White Portrait of a Man" loading="lazy" />
                    </li>
                    <li class="nav-item">
                        <img src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp" class="rounded-circle" height="40"
                            style="margin-left:4px" alt="Black and White Portrait of a Man" loading="lazy" />
                    </li>
                    <button style="margin-left:10px" type="button" class="btn btn-info">Share
                        <i class="far fa-share-square"></i>
                    </button>
                    <button disabled id="add-task" style="margin-left:10px" type="button" class="btn btn-info">Add Task
                        <i class="fas fa-tasks"></i>
                    </button>
                </ul>
                <div class="dropdown">
                    <a class="dropdown-toggle d-flex align-items-center hidden-arrow" href="#"
                        id="navbarDropdownMenuAvatar" role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                        <button style="margin-left:5px" type="button" class="btn btn-info">Filters
                            <i class="fas fa-filter"></i>
                        </button>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
                        <li>
                            <h6>Members</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                                <label class="form-check-label" for="flexCheckDefault">Default checkbox</label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                                <label class="form-check-label" for="flexCheckDefault">Default checkbox</label>
                            </div>
                        </li>
                        <li>
                            <h5>Status</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                                <label class="form-check-label" for="flexCheckDefault">Default checkbox</label>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- Left links -->
            </div>

    </nav>
    <div class="div" style="overflow: auto">
        <ul class="list-group list-group-light" id="task-listing">
        </ul>

    </div>
    <!-- Navbar -->
</div>