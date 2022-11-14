<!DOCTYPE html>
<html lang="en">
<x-title />

<body>
  <div class="div">
    <div class="div">
      <!-- Navbar -->
      <x-nav-bar-dash />
    </div>
    <div style="display: flex ">
      <x-projects-list />
      <x-nav-bar-tasks />
    </div>
    <!-- Modal -->
    <x-task-modal />
    <x-project-Modal />
    <x-share-proj />
  </div>
</body>


<script src="https://code.jquery.com/jquery-3.6.1.min.js"
  integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script type="text/javascript">
  $(document).ready(function(){

    function parseJwt (token) {
       return JSON.parse(atob(token.split('.')[1]));
    }

    var project_id,task_id
    var editOrAddFlag
    // var member_id 

    var tasks = {};
    $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
    
    console.log(options.url)
      if(options.url === 'api/refresh' || options.url === '/logout' || options.refreshRequest  ){
        // console.log(originalOptions)
        return ;
      }
      
      jqXHR.abort();

      const token = localStorage.getItem('jwt-token');
        var ttl = (new Date(parseJwt(token).exp*1000) - new Date(Date.now()))/1000;
        // console.log(ttl/60)
        if(ttl/60<10 && ttl/60>0){
          options.refreshRequest = true
          // console.log('inside --- > refresh block')
          $.ajax({
            url:'api/refresh',
            headers:{'Authorization': `Bearer ${localStorage.getItem('jwt-token')}` },
            type:'get',
            success:  function (res) {
              //  console.log('going to refresh')
               localStorage.setItem('jwt-token',res) 
               options['headers']['Authorization']=`Bearer ${localStorage.getItem('jwt-token')}`
               $.ajax(options) 
            },
            error: function(err){}
          })
        }
        else {
          options.refreshRequest = true
            $.ajax(options)
        }
  });

   
    localStorage.setItem("comments",JSON.stringify([]));  //setting up temp array of comments for modal popup
    localStorage.setItem("proj_old_id",999999)
    localStorage.setItem("status","open");
$.ajax({
    url:'api/projects',
    data:{"member_id":"1"},
    headers:{'Authorization': `Bearer ${localStorage.getItem('jwt-token')}`,
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
  },
    type:'get',
    success:  function (response) {
      //check here for response if 
      $('#side-bar').append(`<div  style="background-color:#a1d0ef;height:51px"><h4 style="color:black" class="font-monospace ms-5 mt-2 ">Projects</h4></div>`)
      
      $.each(response,function(key,item){
            
            $('#side-bar').append(
                `<div onMouseOver="this.style.color='blue'"
   onMouseOut="this.style.color='black'" id="project-`+item.id+`" data-project-id=`+item.id+` 
                        style="background-color: #e9f1f7;"
                        class="font-monospace project-item list-group-item list-group-item-action py-2 ripple 
                        data-mdb-toggle="tooltip" data-mdb-placement="bottom" title="See Tasks"
                        ">
                        <i  class="me-2 fab fa-medapps"></i><span id="project-title`+item.id+`">`+item.project_name+`</span>
                  </div>`
            )
        });
        // console.log(response[0].id)
      $(`#project-${response[0].id}`).click()
    },
    error: function(x,xs,xt){
          console.log(x);
        } 
    }); //prettier 
        $(document).on('click','.project-item',function(e){
          var proj_id = $(this).attr('data-project-id')
          $(`#project-title-nav`).text($(`#project-title${proj_id}`).text())
          //break this into another function
         //____________________________________________________________________________________________ 
          if(localStorage.getItem('proj_old_id')!= proj_id){
            if(document.getElementById(`project-${localStorage.getItem('proj_old_id')}`) != null){
              document.getElementById(`project-${localStorage.getItem('proj_old_id')}`).style.backgroundColor = '#e9f1f7'
            }
            
          }
          document.getElementById(`project-${proj_id}`).style.backgroundColor = '#a1d0ef'
          localStorage.setItem('proj_old_id',proj_id)
         
          localStorage.setItem("project_id", $(this).attr('data-project-id'));
          //___________________________________________________________________________________________ 
          document.getElementById("add-task").disabled = false;
          document.getElementById("navbarDropdownMenuAvatar-filter-task").disabled = false;
          

          $("#search-task").prop('disabled', false);
          
            // console.log(e)
            // console.log($(this).attr('data-project-id'))
    $.ajax({
    url:'api/tasks',
    data:{"project_id":$(this).attr('data-project-id')},
     headers:{'Authorization': `Bearer ${localStorage.getItem('jwt-token')}`,
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
  },
    type:'get',
    success:  function (response) {
       
        $('#task-listing').html("")
        tasks=response
        
        localStorage.setItem("Available_Status",JSON.stringify(Object.keys(response)));
        $.each(response,function(key,item){
            $.each(item,function(key2,item2){
                $('#task-listing').append(
                  `
                  <div class="ms-2 me-2" id="project-task-`+item2.id+`">
                  <a class="badge badge-dark mt-2 mb-2" style="width: 10%"; >`+key+`</a>
                  <div style="display:flex" >
                    <div class="card border-primary mb-3 w-200" style="width: 51rem;" data-task-id=`+item2.id+`>
                      <div class="card-header">`+item2.title+`
                       
                      </div>
                      <div class="card-body text-primary">
                        <p class="card-text">`+item2.description+`</p>
                      </div>
                    </div>
                    <i data-task-edit-id=`+item2.id+` class="edit-task far fa-edit fa-sm mt-5 ms-4 me-4">edit</i>
                        <i data-task-del-id=`+item2.id+` class="del-task fas fa-skull-crossbones fa-sm mt-5 ms-3">delete</i>  
                  </div>
                  </div>
                  `
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
<script src="{{ asset('js/mdb.min.js') }}">
</script>

</html>