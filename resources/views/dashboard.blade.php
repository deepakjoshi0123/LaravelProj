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
    <x-filter-modal />

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
    data:{"member_id":"3"},
    headers:{'Authorization': `Bearer ${localStorage.getItem('jwt-token')}`,
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
  },
    type:'get',
    success:  function (response) {
      //check here for response if 
      $('#side-bar').append(``)
      
      $.each(response,function(key,item){
            
            $('#side-bar').append(
                `<div onMouseOver="this.style.color='blue'"
   onMouseOut="this.style.color='black'" id="project-`+item.id+`" data-project-id=`+item.id+` 
                        style="background-color: #e9f1f7;"
                        class="project-item list-group-item list-group-item-action py-2 ripple 
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
          document.getElementById(`project-${proj_id}`).style.backgroundColor = 'white'
          localStorage.setItem('proj_old_id',proj_id)
         
          localStorage.setItem("project_id", $(this).attr('data-project-id'));
          //___________________________________________________________________________________________ 
          document.getElementById("add-task").disabled = false;
          // document.getElementById("navbarDropdownMenuAvatar-filter-task").disabled = false;
          

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
        // console.log(response)
        $('#task-list').html("")
        tasks=response
        
        localStorage.setItem("Available_Status",JSON.stringify(Object.keys(response)));
        $.each(response,function(key,item){
          
          $('#task-list').append(`<div id="status-`+key.replaceAll(' ','').replaceAll("'",'')+`"><div  class="badge badge-dark ms-2 mt-2" style="width:10%" >`+key+`</div></div>`)
            $.each(item,function(key2,item2){
                $(`#status-${key.replaceAll(' ','').replaceAll("'",'')}`).append(
                  `
                  <div class=" ms-1 " id="project-task-`+item2.id+`">
                  <div style="display:flex" >
                    <div class="card border-primary mt-1 mb-1 " style="width: 62.3rem;" data-task-id=`+item2.id+`>
                      <div class="card-header d-flex justify-content-between">
                      <div class="">`+item2.title+` 
                       </div>
                       <i data-task-del-id=`+item2.id+` class="del-task fa fa-times fa-sm mt-2 me-2"></i>
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
       error: function(x,xs,xt){
      }
     });
    })
  })
</script>
<script src="{{ asset('js/mdb.min.js') }}">
</script>

</html>