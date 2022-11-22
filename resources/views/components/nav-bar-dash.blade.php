<nav class="navbar navbar-expand-lg" style="background-color: #80c4f1;">
    <!-- Container wrapper -->
    <div class="container-fluid ">
        <!-- Toggle button -->
        <button class="navbar-toggler" type="button" data-mdb-toggle="collapse"
            data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Collapsible wrapper -->
        <div class="collapse navbar-collapse " id="navbarSupportedContent">
            <!-- Navbar brand -->
            <a class="navbar-brand mt-1 mt-lg-0" href="#">
                <img src="https://upload.wikimedia.org/wikipedia/en/thumb/8/8c/Trello_logo.svg/1280px-Trello_logo.svg.png"
                    height="18" alt="MDB Logo" loading="lazy" />
            </a>
            <!-- Left links -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="" data-mdb-toggle="modal" data-mdb-target="#projectModal">
                        <i class="far fa-plus-square fa-lg" style="color: black" data-mdb-toggle="tooltip"
                            data-mdb-placement="bottom" title="Add Project"></i>
                    </a>
                </li>

                <form class="d-flex input-group w-auto" style="margin-left:830px">
                    <input style="width:250px" id="search-task" type="search" class="form-control rounded"
                        placeholder="Search" aria-label="Search" aria-describedby="search-addon" onChange="searchTask()"
                        disabled />
                    <span class="input-group-text border-0" id="search-addon">
                        {{-- <i class="fas fa-search"></i> --}}
                    </span>
                </form>
            </ul>
            <!-- Left links -->
        </div>
        <!-- Collapsible wrapper -->

        <!-- Right elements -->
        <div class="d-flex align-items-center">
            <!-- Notifications -->

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
            $('#task-list').html("")
            $.each(response,function(key,item){
          
          $('#task-list').append(`<div id="status-`+key.replaceAll(' ','').replaceAll("'",'')+`"><div  class="badge badge-dark ms-2 mt-2" style="width:10%" >`+key+`</div></div>`)
            $.each(item,function(key2,item2){
                $(`#status-${key.replaceAll(' ','').replaceAll("'",'')}`).append(
                  `
                  <div class=" ms-1 " id="project-task-`+item2.id+`">
                  <div style="display:flex" >
                    <div class="card border-primary mt-1 mb-1 " style="width: 62.3rem;" data-task-id=`+item2.id+`>
                      <div class="card-header d-flex justify-content-between">`+item2.title+` 
                        
                        <i data-task-del-id=`+item2.id+` class="del-task fa fa-times fa-sm ms-5 "></i>
                       </div>
                      
                      <div  data-task-edit-id=`+item2.id+` class="edit-task card-body text-primary">
                        <p class="card-text">`+item2.description+`</p>
                      </div>
                    </div>                       
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
                    `<div  id="project-`+res.data.id+`" data-project-id=`+res.data.id+` 
                        style="background-color: #e9f1f7;border-radius: 10px 10px;"
                        class=" project-item list-group-item list-group-item-action py-2 ripple ">
                        <i  class="me-2 fab fa-medapps"></i><span id="project-title`+res.data.id+`">`+res.data.project_name+`</span>
                  </div>`

            )
                $('#project-name').val("")
                $('#projectModal').modal('toggle')
                $('#prj-title').html("")
            
            },
            
            error: function(err){
                if(err.status == 400){
                  $('#prj-title').html("")
                if(JSON.parse(err.responseText)['name']){
                  $('#prj-title').append(`<small class="" style="color:red">`+JSON.parse(err.responseText)['name'][0]+`</small>`)
                }
              }
            }
          })    
    })
</script>
<!-- Navbar -->