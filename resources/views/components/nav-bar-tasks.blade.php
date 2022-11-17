<div class="div" style="width:80%">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg" style="background-color: #a1d0ef;">
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
                        <h5 id="project-title-nav" class="" href="#">
                        </h5>
                    </li>
                    <i id="share-project-button" class="far fa-share-square fa-2x ms-3" style="color: black"
                        data-mdb-toggle="tooltip" data-mdb-placement="bottom" title="Share Project"></i>

                    <i id="add-task" class="fas fa-ad fa-2x ms-3" style="color: black" data-mdb-toggle="tooltip"
                        data-mdb-placement="bottom" title="Add Task"></i>

                </ul>
                <div class="dropdown">

                    <i id="navbarDropdownMenuAvatar-filter-task" data-mdb-toggle="dropdown" aria-expanded="false"
                        style="margin-left:5px;color: black"
                        class="dropdown-toggle d-flex align-items-center hidden-arrow fas fa-filter fa-2x"
                        title="Filter Tasks"></i>

                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar-filter-task">
                        <li>
                            <h5 class="ms-2">Members</h5>
                            <div id="members-list-filter"></div>
                        </li>
                        <li>
                            <h5 class="ms-2">Status</h5>
                            <div id="status-list-filter"></div>
                        </li>
                        <button id="save-filters" type="button" class="ms-2 btn btn-primary">Save</button>
                    </ul>
                </div>
                <!-- Left links -->
            </div>
    </nav>
    <div class="div" style="width: 1024px; height: 480px; overflow-y: scroll; scrollbar-width: none;">
        <div class="list-group list-group-light" id="task-list">
        </div>
    </div>
    <!-- Navbar -->
</div>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script type="text/javascript">

</script>