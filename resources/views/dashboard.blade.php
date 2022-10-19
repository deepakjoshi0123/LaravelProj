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
  $(document).ready(function(){
    var project_id,task_id
    var editOrAddFlag
    // var member_id 
    var tasks = {};
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
 // add listener to add task on changing it's value we can enable or disable add task button
 // as without clicking on project add task should be disabled
$.ajax({
    url:'projects',
    data:{"member_id":"2"},
    type:'get',
    success:  function (response) {
        $.each(response,function(key,item){
            
            $('#side-bar').append(
                `<div   data-project-id=`+item.id+` 
                        style="background-color: #e9f1f7;border-radius: 30px 15px;"
                        class="project-item list-group-item list-group-item-action py-2 ripple ">
                        <i class="fab fa-medapps"></i><span>`+item.project_name+`</span></div>`
            )
        });
    },
    error: function(x,xs,xt){
       console.log(x);

    } 
    }); //prettier 
        $(document).on('click','.project-item',function(e){
          console.log(e.target)// fetch project name from e.target.value
          project_id = $(this).attr('data-project-id')
          document.getElementById("add-task").disabled = false;
            // console.log(e)
            // console.log($(this).attr('data-project-id'))
    $.ajax({
    url:'tasks',
    data:{"project_id":$(this).attr('data-project-id')},
    type:'get',
    success:  function (response) {
       
        $('#task-listing').html("")
        tasks=response
        $.each(response,function(key,item){
          $('#task-listing').append(`<span class="badge rounded-pill badge-primary" style="width: 10%";>`+key+`</span>`)
            $.each(item,function(key2,item2){
                $('#task-listing').append(
                  `<li class="list-group-item d-flex justify-content-between align-items-center">
                            <div data-task-id=`+item2.id+`
                             class="task-item" style="background-color:#eef3f7;border-radius: 30px 20px;width:80%; " >
                              <div class="fw-bold">`+item2.title+`</div>
                              <div class="text-muted">`+item2.description+`</div>
                            </div>
                            <i data-task-edit-id=`+item2.id+` class="edit-task far fa-edit fa-2x"></i>
                            <i style="margin-right: 60px;" data-task-del-id=`+item2.id+` class="del-task fas fa-skull-crossbones fa-2x"></i>
                      </li>`   
                 )
            })            
        });
    },
    error: function(x,xs,xt){
    }
    });
        })
    
        $(document).on('click','#add-task',function(e){
          editOrAddFlag="add"
          $('#exampleModal').modal('show')   
      });  
      function resetModal(){
        $("#task-title").val("")
        $("#task-desc").val("")
        $("#task-status").val("")
        $("#task-attachment").val("")
        $("#task-comment").val("")
        $("#modal-comments").html("")
      }
      $(document).on('click','#save-task',function(e){
        var data ={
          "title":$("#task-title").val(),
          "description":$("#task-desc").val(),
          "status":$("#task-status").val(),
          "attachment":$("#task-attachment").val(),
          "comment":$("#task-comment").val()
        }
        $('#exampleModal').modal('toggle')
        if(editOrAddFlag === "add"){
          data['project_id'] = project_id
        }
        else {
          data['task_id'] = task_id
        }
        resetModal()
        //  $.ajax({
        //     url:'task',
        //     data:{"id":id},
        //     type:'get',
        //     success:  function (task) {
        //     },
        //     error: function(x,xs,xt){}
        //   })
      });
      
      function modalForEditOrAdd(task){
        editOrAddFlag="edit"
        $("#task-title").val(task[0].title)
        $("#task-desc").val(task[0].description)
        $("#task-status").val(task[0].status)
        $("#task-attachment").val(task[0].attachment)
        $('#modal-body').append(`<h5 id ="modal-comments"> Comments <\h5>`)
              $.each(task[0].comments,function(key,cmnt){
        
                $('#modal-comments').append(`<h5 id="modal-desc">`+ cmnt.member_id +`<\h5>`)
                $('#modal-comments').append(`<h5 id="modal-desc">`+ cmnt.description +`<\h5>`)
              })
        $('#exampleModal').modal('show')
      }

      function editOrAddTask(id){
        task_id=id
        $.ajax({
            url:'task',
            data:{"id":id},
            type:'get',
            success:  function (task) {
              modalForEditOrAdd(task)
            },
            error: function(x,xs,xt){}
          })
      //[{"title":"","attachment":"","description":""}]
      }
      $(document).on('click','.edit-task',function(e){
            editOrAddTask($(this).attr('data-task-edit-id'))
          })
      $(document).on('click','.del-task',function(e){
            console.log($(this).attr('data-task-del-id'))
            //  var id =$(this).attr('data-task-id')
            //  var task= tasks.open.filter(function (task) {
            //     return task.id == id;
            //   })
          })
        })
</script>
<script src="{{ asset('js/mdb.min.js') }}">
</script>

</html>