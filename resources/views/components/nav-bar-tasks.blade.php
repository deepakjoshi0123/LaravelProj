<div class="div" style="width:80%">
    {{-- <div class="alert alert-success">
        <strong>Info!</strong> This alert box could indicate a neutral informative change or action.
    </div> --}}
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg" style="background-color: #a1d0ef;padding:0.2rem">
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
                        <h6 id="project-title-nav" class="mt-1 ms-3" href="#">
                        </h6>
                    </li>
                    <i id="share-project-button" class="far fa-share-square fa-lg ms-3 mt-1"
                        style="color: black;cursor: pointer" data-mdb-toggle="tooltip" data-mdb-placement="bottom"
                        title="Add Member To Project" onMouseOver="this.style.color='DodgerBlue'"
                        onMouseOut="this.style.color='black'"></i>


                    <i id="add-task" class="fas fa-plus fa-lg ms-3 mt-1" style="color: black;cursor: pointer"
                        data-mdb-toggle="tooltip" data-mdb-placement="bottom" title="Add Task"
                        onMouseOver="this.style.color='DodgerBlue'" onMouseOut="this.style.color='black'"></i>
                </ul>
                <div class="d-flex justify-content-between">
                    <i id="add-status" class="fas fa-plus-circle fa-lg me-3 ms-3 mt-1"
                        style="color: black;cursor: pointer" data-mdb-toggle="tooltip" data-mdb-placement="bottom"
                        title="Add Custom Status" onMouseOver="this.style.color='DodgerBlue'"
                        onMouseOut="this.style.color='black'"></i>

                    <i id="navbarDropdownMenuAvatar-filter-task" data-mdb-toggle="tooltip" aria-expanded="false"
                        style="margin-left:5px;color: black;cursor: pointer"
                        class="dropdown-toggle d-flex align-items-center hidden-arrow fas fa-filter fa-lg mt-1"
                        title="Filter Tasks" onMouseOver="this.style.color='DodgerBlue'"
                        onMouseOut="this.style.color='black'"></i>

                </div>
                <!-- Left links -->
            </div>
    </nav>
    <div
        style="padding:1rem; background-color:#e9f1f7; width: 1023px; height: 522px; overflow-y: scroll; scrollbar-width: none;">
        <div id="task-list-msg-append"></div>
        <div class="list-group list-group-light" id="task-list">
        </div>
    </div>
    <!-- Navbar -->
</div>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script type="text/javascript">

</script>