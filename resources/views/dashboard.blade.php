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
  </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
  integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script type="text/javascript">
  setInterval(() => {
    // 1 hour yoken expiry 
    // 
    $.ajax({
    url:'api/refresh',
    type:'post',
    success:  function (response) {
      console.log(response)
      localStorage.setItem('jwt-token',response)
    },
    error:    function(err){
      console.log('err----->',err)
    }
      })
  }, 57000);
  $(document).ready(function(){
    var project_id,task_id
    var editOrAddFlag
    
    // var member_id 
    var tasks = {};
    $.ajaxSetup({
      beforeSend: function (xhr) {
        xhr.setRequestHeader('Authorization', `Bearer ${localStorage.getItem('jwt-token')}` );
  },
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
    localStorage.setItem("comments",JSON.stringify([]));  //setting up temp array of comments for modal popup
    localStorage.setItem("proj_old_id",999999)
$.ajax({
    url:'api/projects',
    data:{"member_id":"2"},
    type:'get',
    success:  function (response) {
      $('#side-bar').append(`<h3>Projects List</h3>`)
        $.each(response,function(key,item){
            
            $('#side-bar').append(
                `<div  id="project-`+item.id+`" data-project-id=`+item.id+` 
                        style="background-color: #e9f1f7;border-radius: 30px 15px;"
                        class="project-item list-group-item list-group-item-action py-2 ripple ">
                        <i  class="fab fa-medapps"></i><span>`+item.project_name+`</span></div>`
            )
        });
    },
    error: function(x,xs,xt){
       console.log(x);

    } 
    }); //prettier 
        $(document).on('click','.project-item',function(e){
          var proj_id = $(this).attr('data-project-id') 
          //break this into another function
         //____________________________________________________________________________________________ 
          if(localStorage.getItem('proj_old_id')!= proj_id){
            if(document.getElementById(`project-${localStorage.getItem('proj_old_id')}`) != null){
              document.getElementById(`project-${localStorage.getItem('proj_old_id')}`).style.backgroundColor = '#e9f1f7'
            }
            
          }
          document.getElementById(`project-${proj_id}`).style.backgroundColor = '#00bfff'
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
    type:'get',
    success:  function (response) {
       
        $('#task-listing').html("")
        tasks=response
        
        localStorage.setItem("Available_Status",JSON.stringify(Object.keys(response)));
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
       error: function(x,xs,xt){
      }
     });
    })
  })
</script>
<script src="{{ asset('js/mdb.min.js') }}">
</script>

</html>