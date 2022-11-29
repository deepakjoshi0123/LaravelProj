<nav class="navbar navbar-expand-lg" style="background-color: #80c4f1;padding:0.2rem">
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
            <div class="d-flex justify-content-between">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <form class="d-flex input-group w-auto" style="margin-left:880px">
                        <input style="width:250px" id="search-task" type="search" class="form-control rounded"
                            placeholder="Search" aria-label="Search" aria-describedby="search-addon"
                            onChange="searchTask()" disabled />

                        {{-- <i class="fas fa-search"></i> --}}
                        </span>
                    </form>
                </ul>
                <!-- Left links -->
            </div>
            <!-- Collapsible wrapper -->

            <!-- Right elements -->
            <div>
                <!-- Notifications -->

                <!-- Avatar -->
                <div class="dropdown">
                    <a class="dropdown-toggle d-flex align-items-center hidden-arrow ms-3" href="#"
                        id="navbarDropdownMenuAvatar" role="button" data-mdb-toggle="dropdown" aria-expanded="false">
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
            headers:{'Authorization': `Bearer ${localStorage.getItem('jwt-token')}`,
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
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
            headers:{'Authorization': `Bearer ${localStorage.getItem('jwt-token')}`,
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:  function (response) {
            console.log(response)
            $('#task-list').html("")
            console.log('no task to display')
            if(response.length === 0){
            console.log('no task to display')
            $('#task-list').append(`<div id="no-task-msg"><h5 style="margin-top:120px;margin-left:150px">There are no tasks which matches the search criteria ...</h5></div>`)  
            return
             }

            $.each(response,function(key,item){
            $('#task-list').append(`<div id="status-`+key.replaceAll(' ','').replaceAll("'",'')+`"><div  class="badge badge-dark ms-2 mt-2 mb-1" style="width:10%" >`+key+`</div></div>`)
            $.each(item,function(key2,item2){
                $(`#status-${key.replaceAll(' ','').replaceAll("'",'')}`).append(
                    `<x-task-list id=${item2.id} title=${item2.title} description=${item2.description}/>`
                 )
            })            
        });
     },
            error: function(res){}
        })
    }
    
</script>
<!-- Navbar -->