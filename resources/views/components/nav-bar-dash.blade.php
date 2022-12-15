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
                            <a id="user-name" class="dropdown-item"></a>
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
    $('#user-name').text(`Hi ! ${localStorage.getItem('first_name')}  ${localStorage.getItem('last_name')}`)
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

    $(document).on('click','.show-more-search-tasks',function(e){
       
    // console.log($(this).attr('data-show-more-id'))
     let pageRec = JSON.parse(localStorage.getItem('page_rec'))
      pageRec[`${$(this).attr('data-show-more-id')}`].pageNo = pageRec[`${$(this).attr('data-show-more-id')}`].pageNo + 1 
      console.log(pageRec[`${$(this).attr('data-show-more-id')}`].pageNo)
      localStorage.setItem('page_rec',JSON.stringify(pageRec))

      $.ajax({
      url:'api/getNextSearchedTasks',
      data:{"project_id":localStorage.getItem('project_id'),
      "status_id":$(this).attr('data-show-more-id'),
      "pageNo":pageRec[`${$(this).attr('data-show-more-id')}`].pageNo,
      "add":pageRec[`${$(this).attr('data-show-more-id')}`].Add,
      "del":pageRec[`${$(this).attr('data-show-more-id')}`].del,
      "text":$('#search-task').val()
    },
      headers:{'Authorization': `Bearer ${localStorage.getItem('jwt-token')}`,
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      },
        type:'get',
        success: (response) => {
        //   console.log('got res ',response.tasks)
          showTask(response.tasks,response.tasks[0].status_id,JSON.parse(localStorage.getItem('page_rec'))[`${$(this).attr('data-show-more-id')}`].pageNo*2+1)
          showMore(response.tasks[0].status_id,response.len,'show-more-search-tasks',`show-more-search-tasks-${response.tasks[0].status_id}`)
        },
        error:  function(err){}
        })

    })

    function searchTask(){
        let res = Object.keys(JSON.parse(localStorage.getItem('page_rec')))
      
        let pageRec ={}
                for(let i=0;i<res.length;i++){
                  pageRec[res[i]] = {'pageNo':0,'del':0,'Add':0}
                }
        localStorage.setItem("page_rec",JSON.stringify(pageRec));
        // console.log(pageRec)
        // return 
        $.ajax({
            url:'api/searchTask',
            data:{"text":$('#search-task').val(),"project_id":localStorage.getItem('project_id'),
        },
            type:'get',
            headers:{'Authorization': `Bearer ${localStorage.getItem('jwt-token')}`,
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:  function (response) {
            // console.log(response)
            $('#task-list').html("")
            // console.log('no task to display')
            if(response.length === 0){
            // console.log('no task to display')
            $('#task-list').append(`<div id="no-task-msg"><h5 style="margin-top:20px;margin-left:250px">There are no tasks which matches the search criteria ...</h5></div>`)  
            return
             }
             console.log('check item',response)
            $.each(response,function(key,item){
                console.log(item[item.status],response[key].id)
                $('#task-list').append(`<div id="status-`+response[key].id+`"><div class="">
                  <div style="background-color:#009999" class="badge  ms-2 mt-2 mb-1" style="width:25%" >`+item.status+`</div>
                </div></div>
              `)
                showTask(item[item.status],response[key].id)
                showMore(response[key].id,response[key].len,'show-more-search-tasks',`show-more-search-tasks-${response[key].id}`)           
        });
     },
            error: function(res){}
        })
    }
    
</script>
<!-- Navbar -->