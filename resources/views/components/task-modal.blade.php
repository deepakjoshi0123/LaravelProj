<div>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
  </script>
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div style="display: flex ">
          <div style="width: 80%">
            <div class="modal-header ">
              <h4 class="ms-4 " id="modal-title">Trello Clone</h4>
            </div>
            <div>
              <h6 class="ms-4 mt-1" id="modal-title">Ttile</h6>
              <input id="task-title" class="form-control ms-4 mt-3" />
              <span id="tsk-title"></span>
              <h6 class="modal-desc ms-4 mt-3" id="modal-desc">Descriptiom</h6>
              <div id="task-modal-desc">
                <textarea class="form-control ms-4  mt-3" id="task-desc" rows="3"></textarea>
                <span id="tsk-desc"></span>
              </div>
              <input id="task-comment" class="ms-4 mb-3 mt-5 form-control" placeholder="Add Comment ..." />
              <div id="modal-body"></div>
            </div>
          </div>
          <div class="ms-5 mt-4">
            <div class="ms-5">
              <h5 id="status-heading" class="mt-3"></h5>
              <h6 class="mb-3" style="color:blue" id="task-status-label"></h6>
              <div id="modal-members"></div>


              <select style="width:60%" id="datalistOptions" class="form-select" aria-label="Default select example">
                <option disabled selected>Assign Task</option>
              </select>

              <h6 class="mt-4">Attachment</h6>
              <input id="task-file" type="file" name="file" />

              <div class="btn-group">
                <button style="min-width: 180px;" type="button" class="mt-4 btn btn-primary dropdown-toggle "
                  data-bs-toggle="dropdown" aria-expanded="false">
                  Choose Status
                </button>
                <ul class="dropdown-menu">
                  <input onChange="getCustomTaskStatus()" id="custom-status" class="form-control"
                    placeholder="Custom Status ..." />

                  <li>
                    <hr class="dropdown-divider">
                  </li>

                  <div id="task-status">
                    <li><a class="dropdown-item">unassigned</a></li>

                  </div>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer me-5">
          <button onClick="resetModal()" type="button" class="btn btn-secondary"
            data-mdb-dismiss="modal">Cancel</button>
          <button id="save-task" type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
  integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script type="text/javascript">
  // var assignee = "Unassigned";
  localStorage.setItem("assignee","unassigned");
  
   
  $(document).on('click','#add-task',function(e){
    $.each(JSON.parse(localStorage.getItem("Available_Status")),function(key,status){
          $('#task-status').append(`
          <li><a class="dropdown-item">`+status+`</a></li>
          `)
        })
    $.ajax({
            url:'api/assignees',
            data:{"project_id":localStorage.getItem('project_id')},
            type:'get',
            success:  function (res) {
              $.each(res,function(key,mem){
                $('#datalistOptions').append(`<option  value="`+mem.id+`">`+mem.email+`</option>`) 
              })
            },
            error: function(x,xs,xt){}
          })
          editOrAddFlag="add"
          $('#exampleModal').modal('show')   
      });  

      function resetModal(){
        console.log('function clicked')
        localStorage.setItem("status","open");
        localStorage.setItem("assignee","unassigned");
        $("#task-title").val("")
        $("#task-desc").val("")
        $("#custom-status").val("")
        $("#task-comment").val("")
        $("#task-file").val("")
        $("#modal-body").html("")
        $("#task-status").html("")
        $("#task-status").append(`<li><a class="dropdown-item">unassigned</a></li>`)
        $("#tsk-title").html("")
        $("#tsk-desc").html("")
        $('#datalistOptions').html("")
        $('#datalistOptions').append(`<option value="unassigned">Assign Task</option>`)
        $('#task-status-label').text("")
        $('#status-heading').text("")
        
        
        $('#modal-members').html("")
        // localStorage.setItem("comments",[])
      }
      $(document).on('click','#save-task',function(e){
        $("#tsk-title").html("")
        $("#tsk-desc").html("")
        taskFile = new FormData()
        taskFile.append('file',($('#task-file').val()))
        // console.log(taskFile,$('#task-file').val())
        var data ={
          "title":$("#task-title").val(),
          "description":$("#task-desc").val(),
          "status":localStorage.getItem("status"),
        }
        data2={
        "attachment":taskFile,
        "member_id":"2",
        "data":data,
        "comments":JSON.parse(localStorage.getItem("comments")),
        "assignee":localStorage.getItem("assignee"),
      }
        
        // taskFile = new FormData()
        // taskFile.append

        if(editOrAddFlag === "add"){
          data['project_id'] = localStorage.getItem("project_id");
        }
        else {
          data['id'] = task_id
        }
       
        // console.log(data2)
       
        // return
         $.ajax({
            url:'api/addTask',
            data:JSON.stringify(data2),
            dataType:'json',
            type:'post',
            contentType: "application/json; charset=utf-8",
            success:  function (res) {
              console.log(res)
              $('#exampleModal').modal('toggle')
              resetModal()
              // console.log(res.status)
            },
            error: function(err){
              // console.log(err.status)
              if(err.status == 400){
                if(JSON.parse(err.responseText)['data.title']){
                  $('#tsk-title').append(`<span class="ms-5" style="color:red">`+JSON.parse(err.responseText)['data.title'][0].replace('data.','')+`</span>`)
                }
                if(JSON.parse(err.responseText)['data.description']){
                  $('#tsk-desc').append(`<span class="ms-5" style="color:red">`+JSON.parse(err.responseText)['data.description'][0].replace('data.','')+`</span>`)
                }
              }
            }
          })
      });
      
      function renderComments(cmnt){
        console.log('render',cmnt.get_member.last_name)
        $('#modal-body').append(`
                <div class="mt-3 ms-4" style="display:flex">
                  <i class="fas fa-user-tie"></i>
                  <div class="ms-4 " id="modal-desc">`+ cmnt.get_member.first_name+` `+cmnt.get_member.last_name+`</div>
                    <div class="ms-4 " id="modal-desc">`+ cmnt.get_member.updated_at +`</div>
                  </div> 
                  <div class="ms-5 fs-6 text-muted" id="modal-desc">`+ cmnt.description +`</div>
                </div>
                `)
      }

      function modalForEditOrAdd(task){
        console.log('status should be added')
       
        $.each(JSON.parse(localStorage.getItem("Available_Status")),function(key,status){
          $('#task-status').append(`
          <li><a class="dropdown-item">`+status+`</a></li>
          `)
        })

        JSON.parse(localStorage.getItem("Available_Status"))
        // console.log('heyyyy checking aviliable status')
        editOrAddFlag="edit"
        $("#task-title").val(task[0].title)
        $("#task-desc").val(task[0].description)
        $('#modal-body').append(`
            <div id="modal-attachments">
              <h6 class="modal-attach ms-4 mt-2" >Attachments</h6>
              <div class="ms-4 mt-2">attachments feature pending ...</div>
              <div class="ms-4 mt-2 ">attachments feature pending ...</div>
            </div>
              `)
        $('#modal-body').append(`<div  class="ms-4 overflow-auto" id ="modal-comments"> Comments </div>`)
              $.each(task[0].comments,function(key,cmnt){
                renderComments(cmnt)
              }) 
        $('#exampleModal').modal('show')
      }
      function editOrAddTask(id){

        // $.ajax({
        //     url:'api/members',
        //     data:{"task_id":id},
        //     type:'get',
        //     success:  function (res) {
        //       // console.log(res)
        //       $('#modal-members').append(`
        //       <div class="mt-3 mb-3 btn-group dropdown">
        //         <button style="min-width: 180px;" type="button" class="btn btn-primary dropdown-toggle"
        //           data-mdb-toggle="dropdown" aria-expanded="false">
        //           Members
        //           <i class="fas fa-users ms-2"></i>
        //         </button>
        //         <ul class="dropdown-menu">
        //         </ul>
        //       </div>
        //       `)
        //       $.each(res,function(key,item){
        //         $('.dropdown-menu').append(`
        //           <li >`+item.email+`</li>
        //       `)
        //       })
             
        //     },
        //     error: function(){}
        //   })

        task_id=id
        $.ajax({
            url:'api/task',
            data:{"id":id},
            type:'get',
            success:  function (task) {
              $('#task-status-label').text(task[0].status)
              $('#status-heading').text("Status")
              console.log(task[0].status)
              modalForEditOrAdd(task)
            },
            error: function(x,xs,xt){}
          })
      //[{"title":"","attachment":"","description":""}]
      }
      
      $(document).on("click", "#task-status li", function() {
        // console.log($(this).text().toLowerCase())
        localStorage.setItem("status",$(this).text());  
      });
      // $("#task-status li").click(function(e) {
      //   console.log(e,$(this).text())
      // })
      
     function getCustomTaskStatus(){
      localStorage.setItem("status",$("#custom-status").val().toLowerCase());
      }
     
      $('#task-comment').bind('keypress', function(e) {
        if(e.keyCode==13){
            if($("#task-comment").val()!=""){
              cmnts = JSON.parse(localStorage.getItem("comments")) 
              cmnts.push($("#task-comment").val())
              localStorage.setItem("comments",JSON.stringify(cmnts));
              cmnt = {"description":$("#task-comment").val(),"get_member":{"first_name":"Missouri ","last_name":"Jacobs","updated_at":"9:00 AM"}}
              // console.log(cmnt)
              renderComments(cmnt)
            }
        $("#task-comment").val("")
              
        }
      });

      $(document).on('click','.edit-task',function(e){
        $.ajax({
            url:'api/assignees',
            data:{"project_id":localStorage.getItem('project_id'),"task_id":$(this).attr('data-task-edit-id')},
            type:'get',
            success:  function (res) {
              console.log(res)
              $.each(res,function(key,mem){
                $('#datalistOptions').append(`<option value="`+mem.id+`">`+mem.email+`</option>`) 
              })
            },
            error: function(x,xs,xt){}
          })
          $('#exampleModal').modal('show')
            editOrAddTask($(this).attr('data-task-edit-id'))
          })
      $('#datalistOptions').on('change' ,function(){
            localStorage.setItem("assignee",$(this).val())
            console.log($(this).val())
      });
      $(document).on('click','.del-task',function(e){
            console.log($(this).attr('data-task-del-id'))
            var id=$(this).attr('data-task-del-id')
            $.ajax({
            url:'api/delTask',
            data:{"task_id":id},
            type:'delete',
            success:  function (res) {
              console.log(res)
              $(`#project-task-${id}`).html("")
            },
            error: function(x,xs,xt){}
           })
        })
</script>