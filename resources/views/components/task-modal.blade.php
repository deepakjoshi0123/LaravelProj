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
              <h6 class="modal-desc ms-4 mt-3" id="modal-desc">Descriptiom</h6>
              <div id="task-modal-desc">
                <textarea class="form-control ms-4  mt-3" id="task-desc" rows="3"></textarea>
              </div>
              <input id="task-comment" class="ms-4 mb-3 mt-5 form-control" placeholder="Add Comment ..." />
              <div id="modal-body"></div>
            </div>
          </div>
          <div class="ms-5 mt-4">
            <div class="ms-5">
              <h5>Add to Task</h5>
              <div class="mt-3 btn-group dropdown">
                <button style="min-width: 180px;" type="button" class="btn btn-primary dropdown-toggle"
                  data-mdb-toggle="dropdown" aria-expanded="false">
                  Members
                  <i class="fas fa-users ms-2"></i>
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Action</a></li>
                  <li><a class="dropdown-item" href="#">Another action</a></li>
                  <li><a class="dropdown-item" href="#">Something else here</a></li>
                  <li>
                    <hr class="dropdown-divider" />
                  </li>
                  <li><a class="dropdown-item" href="#">Separated link</a></li>
                </ul>
              </div>
              <h6 class="mt-4">Assign Task</h6>

              <input style="width:60%" class="form-control" list="datalistOptions" id="exampleDataList"
                placeholder="Type to assign...">
              <datalist id="datalistOptions">
                <option value="Deepak">
                <option value="ankit">
                <option value="ram">
                <option value="shyaam">
                <option value="john">
              </datalist>
              <h6 class="mt-4">Attachment</h6>
              <input type="file" />
              <h6 class="mt-4">Status</h6>
              <div class="btn-group">
                <button style="min-width: 180px;" type="button" class="btn btn-primary dropdown-toggle "
                  data-bs-toggle="dropdown" aria-expanded="false">
                  Action
                </button>
                <ul class="dropdown-menu" id="task-status">
                  <li><a class="dropdown-item" href="#">Open</a></li>
                  <li><a class="dropdown-item" href="#">pending</a></li>
                  <li><a class="dropdown-item" href="#">Closed</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>

                  <input onChange="getCustomTaskStatus()" id="custom-status" class="form-control"
                    placeholder="Custom Status ..." />

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
  $(document).on('click','#add-task',function(e){
          editOrAddFlag="add"
          $('#exampleModal').modal('show')   
      });  
      console.log('modal is used')
      function resetModal(){
        localStorage.setItem("status","");
        $("#task-title").val("")
        $("#task-desc").val("")
        $("#custom-status").val("")
        $("#task-comment").val("")
        $("#modal-body").html("")
        // localStorage.setItem("comments",[])
      }
      $(document).on('click','#save-task',function(e){
        var data ={
          "title":$("#task-title").val(),
          "description":$("#task-desc").val(),
          "status":localStorage.getItem("status"),
          // "attachment":$("#task-attachment").val(),
          "comments":localStorage.getItem("comments")
        }
        $('#exampleModal').modal('toggle')
        if(editOrAddFlag === "add"){
          data['project_id'] = localStorage.getItem("project_id");
        }
        else {
          data['task_id'] = task_id
        }
        console.log(data,)
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
      
      function renderComments(cmnt){
        $('#modal-body').append(`
                <div class="mt-3 ms-4" style="display:flex">
                  <i class="fas fa-user-tie"></i>
                  <div class="ms-4 " id="modal-desc">`+ "member name" +`</div>
                    <div class="ms-4 " id="modal-desc">`+ "12:28 pm" +`</div>
                </div> 
                  <div class="ms-5 fs-6 text-muted" id="modal-desc">`+ cmnt.description +`</div>
                </div>
                
                `)
      }

      function modalForEditOrAdd(task){
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
      
      $("#task-status li").click(function() {
        localStorage.setItem("status",$(this).text());         
      })
      
     function getCustomTaskStatus(){
      localStorage.setItem("status",$("#custom-status").val());
      }
     
      $('#task-comment').bind('keypress', function(e) {
        if(e.keyCode==13){
            if($("#task-comment").val()!=""){
              cmnts = JSON.parse(localStorage.getItem("comments")) 
              cmnts.push($("#task-comment").val())
              localStorage.setItem("comments",JSON.stringify(cmnts));
              cmnt = {"description":$("#task-comment").val()}
              // console.log(cmnt)
              renderComments(cmnt)
            }
        $("#task-comment").val("")
              
        }
      });

      $(document).on('click','.edit-task',function(e){
            editOrAddTask($(this).attr('data-task-edit-id'))
          })
</script>