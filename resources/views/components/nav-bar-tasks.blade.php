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
                        <h5 id="project-title-nav" class="font-monospace" href="#">
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
    $(document).on('click','#navbarDropdownMenuAvatar-filter-task',function(e){
        var filters = {"status":[],"members":[]};
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
                           <input id="filter-member" class="form-check-input" checked type="checkbox" value=`+mem.id+`
                            id="flexCheckDefault" />
                         <label class="form-check-label" for="flexCheckDefault">`+mem.email+`</label>
                    </div>        
                    `)
                })
            },
            error: function (err){}
            })   
        $.each(JSON.parse(localStorage.getItem('Available_Status')),function(key,mem){
           
            $('#status-list-filter').append(`
            <div class="ms-2 form-check">
                     <input id="filter-status" class="form-check-input" checked type="checkbox" value="`+mem+`"
                        id="flexCheckDefault" />
                <label class="form-check-label" for="flexCheckDefault">`+mem+`</label>
            </div>
            `)
        })
    })
    $(document).on('click','#save-filters',function(e){
        var filters = {"status":[''],"members":['']}; // it's sending null remove it at the backend before queryig to db
        // why filters not getting passed when check boxes are not clicked
            console.log('filter task clicked',filters)
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
                 $('#task-listing').html("")
            $.each(res,function(key,item){
            $.each(item,function(key2,item2){
                $('#task-listing').append(
                  `
                  <div class="ms-2 me-2" id="project-task-`+item2.id+`">
                  <a class="badge badge-dark mt-2 mb-2" style="width: 10%"; >`+key+`</a>
                  <div style="display:flex" >
                    <div class="card border-primary mb-3" style="width: 51rem;" data-task-id=`+item2.id+`>
                      <div class="card-header">`+item2.title+`
                       
                      </div>
                      <div class="card-body text-primary">
                        <p class="card-text">`+item2.description+`</p>
                      </div>
                    </div>
                    <i data-task-edit-id=`+item2.id+` class="edit-task far fa-edit fa-sm  ms-4 me-4">edit</i>
                        <i data-task-del-id=`+item2.id+` class="del-task fas fa-skull-crossbones fa-sm  ms-3">delete</i>  
                  </div>
                  </div>
                  `
                 )
            })            
        });
            },
            error: function(err){}
        })
    
    })
</script>