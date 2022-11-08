<div class="div" style="width:80%">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg" style="background-color: #b3d7ef;">
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
                        <div class="nav-link link-primary font-weight-bolder" href="#">Project Name</div>
                    </li>
                    <li class="nav-item">
                        <img src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp" class="rounded-circle" height="40"
                            alt="Black and White Portrait of a Man" loading="lazy" />
                    </li>
                    <li class="nav-item">
                        <img src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp" class="rounded-circle" height="40"
                            style="margin-left:4px" alt="Black and White Portrait of a Man" loading="lazy" />
                    </li>
                    <button style="margin-left:10px" type="button" class="btn btn-primary">Share
                        <i class="far fa-share-square"></i>
                    </button>
                    <button disabled id="add-task" style="margin-left:10px" type="button" class="btn btn-primary">Add
                        Task
                        <i class="fas fa-tasks"></i>
                    </button>
                </ul>
                <div class="dropdown">
                    <button class="btn btn-primary  dropdown-toggle d-flex align-items-center hidden-arrow"
                        id="navbarDropdownMenuAvatar-filter-task" role="button" data-mdb-toggle="dropdown"
                        aria-expanded="false" disabled style="margin-left:5px" type="button">
                        Filters
                        <i class="fas fa-filter"></i>
                    </button>
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
    <div class="div" style="width: 1010px; height: 500px; overflow-y: scroll; scrollbar-width: none;">
        <ul class="list-group list-group-light" id="task-listing">
        </ul>

    </div>
    <!-- Navbar -->
</div>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).on('click','#navbarDropdownMenuAvatar-filter-task',function(e){

        $('#members-list-filter').html("")
        $('#status-list-filter').html("")
        $.ajax({
            url:'api/assignees',
            data:{"project_id":localStorage.getItem('project_id')},
            type:'get',
            success:  function (res) {
                $.each(res,function(key,mem){
                    
                    $('#members-list-filter').append(`
                    <div class="ms-2 form-check">
                           <input id="filter-member" class="form-check-input" type="checkbox" value=`+mem.id+`
                            id="flexCheckDefault" />
                         <label class="form-check-label" for="flexCheckDefault">`+mem.first_name+`</label>
                    </div>
                                        
                    `)
                })
            },
            error: function (err){}
            })   
        $.each(JSON.parse(localStorage.getItem('Available_Status')),function(key,mem){
           
            $('#status-list-filter').append(`
            <div class="ms-2 form-check">
                     <input id="filter-status" class="form-check-input" type="checkbox" value="`+mem+`"
                        id="flexCheckDefault" />
                <label class="form-check-label" for="flexCheckDefault">`+mem+`</label>
            </div>
            `)
        })
    })
    $(document).on('click','#save-filters',function(e){
       
        var filters = {"status":[],"members":[]};
            $("#filter-status:checked").each(function() {
                filters['status'].push($(this).val());
            });
            $("#filter-member:checked").each(function() {
                filters['members'].push($(this).val());
            });
        data={"project_id":localStorage.getItem("project_id"),'filters':filters}
        

        $.ajax({
            url:'api/filterTask',
            data:data,
            type:'get',
            success:  function (res) {
                console.log(res)
            },
            error: function(err){}
        })
       
    })
</script>