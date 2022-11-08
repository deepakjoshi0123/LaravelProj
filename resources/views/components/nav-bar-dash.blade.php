<nav class="navbar navbar-expand-lg" style="background-color: #80c4f1;">
    <!-- Container wrapper -->
    <div class="container-fluid">
        <!-- Toggle button -->
        <button class="navbar-toggler" type="button" data-mdb-toggle="collapse"
            data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Collapsible wrapper -->
        <div class="collapse navbar-collapse mt-1" id="navbarSupportedContent">
            <!-- Navbar brand -->
            <a class="navbar-brand mt-2 mt-lg-0" href="#">
                <img src="https://upload.wikimedia.org/wikipedia/en/thumb/8/8c/Trello_logo.svg/1280px-Trello_logo.svg.png"
                    height="18" alt="MDB Logo" loading="lazy" />
            </a>
            <!-- Left links -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="btn btn-primary" data-mdb-toggle="modal" data-mdb-target="#projectModal">
                        Add Project
                        <i class="fas fa-puzzle-piece"></i>
                    </a>
                </li>
                <form class="d-flex input-group w-auto" style="margin-left:700px">
                    <input id="search-task" type="search" class="form-control rounded" placeholder="Search"
                        aria-label="Search" aria-describedby="search-addon" onChange="searchTask()" disabled />
                    <span class="input-group-text border-0" id="search-addon">
                        <i class="fas fa-search"></i>
                    </span>
                </form>
            </ul>
            <!-- Left links -->
        </div>
        <!-- Collapsible wrapper -->

        <!-- Right elements -->
        <div class="d-flex align-items-center">
            <!-- Notifications -->
            <div class="dropdown">
                <a class="text-reset me-3 dropdown-toggle hidden-arrow" href="#" id="navbarDropdownMenuLink"
                    role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    <span class="badge rounded-pill badge-notification bg-danger">1</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                    <li>
                        <a class="dropdown-item" href="#">Web sockets Notifications pending</a>
                    </li>
                </ul>
            </div>
            <!-- Avatar -->
            <div class="dropdown">
                <a class="dropdown-toggle d-flex align-items-center hidden-arrow" href="#" id="navbarDropdownMenuAvatar"
                    role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                    <img src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp" class="rounded-circle" height="25"
                        alt="Black and White Portrait of a Man" loading="lazy" />
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">

                    <li>
                        <a id="logout" class="dropdown-item">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Right elements -->
    </div>
    <!-- Container wrapper -->
</nav>

<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $('#logout').on('click',function(){
        $.ajax({
            url:'logout',
            type:'get',
            success:  function (response) {
                localStorage.clear();
                window.location.href = "http://localhost:8000/login";
            },
            error: function(err){}
        })
    })
    function searchTask(){
        $.ajax({
            url:'api/searchTask',
            data:{"text":$('#search-task').val(),"project_id":localStorage.getItem('project_id')},
            type:'get',
            success:  function (response) {
            console.log(response)
            $('#task-listing').html("")
            $.each(response,function(key,item){
            $.each(item,function(key2,item2){
                $('#task-listing').append(
                  `
                  <div id="project-task-`+item2.id+`">
                  <a class="badge badge-dark mt-2 mb-2" style="width: 10%"; >`+key+`</a>
                  <div style="display:flex" >
                    <div class="card border-primary mb-3" style="max-width: 70rem;" data-task-id=`+item2.id+`>
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
            error: function(res){}
        })
    }
    $(document).on('click','#save-project',function(){
        // console.log($('#project-name').val())
        $.ajax({
            url:'api/createProject',
            data:{"owner":"3","name":$('#project-name').val()},
            type:'post',
            success:  function (res) {
              
                $('#side-bar').append(
                `<div data-project-id=`+res.data.id+` 
                        style="background-color: #e9f1f7;border-radius: 30px 15px;"
                        class="project-item list-group-item list-group-item-action py-2 ripple ">
                        <i class="fab fa-medapps"></i><span>`+res.data.project_name+`</span></div>`
            )
                $('#project-name').val("")
                $('#projectModal').modal('toggle')
                $('#prj-title').html("")
            
            },
            
            error: function(err){
                if(err.status == 400){
                if(JSON.parse(err.responseText)['name']){
                  $('#prj-title').append(`<span class="ms-5" style="color:red">`+JSON.parse(err.responseText)['name'][0]+`</span>`)
                }
              }
            }
          })    
    })
</script>
<!-- Navbar -->