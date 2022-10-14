<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link href="{{ asset('css/mdb.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">

    </head>
<body>
   <div class="div">
    <div class="div">
        <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <!-- Container wrapper -->
    <div class="container-fluid">
      <!-- Toggle button -->
      <button
        class="navbar-toggler"
        type="button"
        data-mdb-toggle="collapse"
        data-mdb-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <i class="fas fa-bars"></i>
      </button>
  
      <!-- Collapsible wrapper -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Navbar brand -->
        <a class="navbar-brand mt-2 mt-lg-0" href="#">
          <img
            src="https://mdbcdn.b-cdn.net/img/logo/mdb-transaprent-noshadows.webp"
            height="18"
            alt="MDB Logo"
            loading="lazy"
          />
        </a>
        <!-- Left links -->
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="#" data-mdb-toggle="modal" data-mdb-target="#exampleModal">Create Project</a>
          </li>
          <form class="d-flex input-group w-auto" style="margin-left:730px">
            <input
              type="search"
              class="form-control rounded"
              placeholder="Search"
              aria-label="Search"
              aria-describedby="search-addon"
            />
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
          <a
            class="text-reset me-3 dropdown-toggle hidden-arrow"
            href="#"
            id="navbarDropdownMenuLink"
            role="button"
            data-mdb-toggle="dropdown"
            aria-expanded="false"
          >
            <i class="fas fa-bell"></i>
            <span class="badge rounded-pill badge-notification bg-danger">1</span>
          </a>
          <ul
            class="dropdown-menu dropdown-menu-end"
            aria-labelledby="navbarDropdownMenuLink"
          >
            <li>
              <a class="dropdown-item" href="#">Some news</a>
            </li>
            <li>
              <a class="dropdown-item" href="#">Another news</a>
            </li>
            <li>
              <a class="dropdown-item" href="#">Something else here</a>
            </li>
          </ul>
        </div>
        <!-- Avatar -->
        <div class="dropdown">
          <a
            class="dropdown-toggle d-flex align-items-center hidden-arrow"
            href="#"
            id="navbarDropdownMenuAvatar"
            role="button"
            data-mdb-toggle="dropdown"
            aria-expanded="false"
          >
            <img
              src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp"
              class="rounded-circle"
              height="25"
              alt="Black and White Portrait of a Man"
              loading="lazy"
            />
          </a>
          <ul
            class="dropdown-menu dropdown-menu-end"
            aria-labelledby="navbarDropdownMenuAvatar"
          >
            <li>
              <a class="dropdown-item" href="#">My profile</a>
            </li>
            <li>
              <a class="dropdown-item" href="#">Settings</a>
            </li>
            <li>
              <a class="dropdown-item" href="#">Logout</a>
            </li>
          </ul>
        </div>
      </div>
      <!-- Right elements -->
    </div>
    <!-- Container wrapper -->
  </nav>
  <!-- Navbar -->
    </div>
    <div style="display: flex " >
        <div class="div" style="width:30% ">
            <nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse bg-white">
                <div class="position-sticky">
                  <div class="list-group list-group-flush mx-3 mt-4" id="side-bar">
                   
                  </div>
                </div>
              </nav>
        </div>
        <div class="div" style="width:80%">
            <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-dark bg-primary" >
                    <!-- Container wrapper -->
                    <div class="container-fluid">
                    <!-- Toggle button -->
                    <button
                        class="navbar-toggler"
                        type="button"
                        data-mdb-toggle="collapse"
                        data-mdb-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent"
                        aria-expanded="false"
                        aria-label="Toggle navigation"
                    >
                        <i class="fas fa-bars"></i>
                    </button>
                
                    <!-- Collapsible wrapper -->
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left links -->
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item" style="margin-right:20px">
                            <a class="nav-link link-info" href="#">Project Name</a>
                        </li>
                        <li class="nav-item" >
                            <img
                            src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp"
                            class="rounded-circle"
                            height="40"
                            alt="Black and White Portrait of a Man"
                            loading="lazy"
                          />
                        </li>
                        <li class="nav-item">
                            <img
                            src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp"
                            class="rounded-circle"
                            height="40"
                            style="margin-left:4px"
                            alt="Black and White Portrait of a Man"
                            loading="lazy"
                          />
                        </li>
                        <button style="margin-left:10px" type="button" class="btn btn-info">Share
                            <i class="far fa-share-square"></i>
                        </button>
                        <button style="margin-left:10px" type="button" class="btn btn-info">Add Task
                            <i class="fas fa-tasks"></i>
                        </button>
                        </ul>
                        <div class="dropdown">
                            <a
                              class="dropdown-toggle d-flex align-items-center hidden-arrow"
                              href="#"
                              id="navbarDropdownMenuAvatar"
                              role="button"
                              data-mdb-toggle="dropdown"
                              aria-expanded="false"
                              
                            >
                            <button style="margin-left:5px" type="button" class="btn btn-info">Filters
                                <i class="fas fa-filter"></i>
                            </button>
                            </a>
                            
                            <ul
                              class="dropdown-menu dropdown-menu-end"
                              aria-labelledby="navbarDropdownMenuAvatar"
                            >
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
    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                            <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">...</div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                        </div>
                    </div>
   </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script type="text/javascript">
$(document).ready(function(){
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$.ajax({
    url:'projects',
    data:{"member_id":"1"},
    type:'get',
    // crossDomain: true,
    success:  function (response) {
        $.each(response,function(key,item){
            
            $('#side-bar').append(
                `<div   data-project-id=`+item.project_id+` class="project-item list-group-item list-group-item-action py-2 ripple "><i class="fab fa-medapps"></i><span>`+item.project[0].project_name+`</span></div>`
            )
        });
    },
    error: function(x,xs,xt){
       console.log(x);

    }
    }); //prettier 
        $(document).on('click','.project-item',function(e){
            console.log($(this).attr('data-project-id'))
    $.ajax({
    url:'tasks',
    data:{"project_id":$(this).attr('data-project-id')},
    type:'get',
    success:  function (response) {
        $('#task-listing').html("")
        $.each(response,function(key,item){
            $.each(item,function(key2,item2){
                $('#task-listing').append(
                ` <span class="badge rounded-pill badge-primary" style="width: 10%">`+key+`</span>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                              <div class="fw-bold">`+item2.title+`</div>
                              <div class="text-muted">`+item2.description+`</div>
                            </div>
                          </li>`   
                 )
            })
           
            
        });
    },
    error: function(x,xs,xt){
         
    }
    });
        })
       
        })
</script>
<script  src="{{ asset('js/mdb.min.js') }}">
</script>


</html>