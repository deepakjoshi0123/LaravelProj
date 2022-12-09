<div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
  </script>
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">

      <div style="padding:1.5rem" class="modal-content" style="max-height:550px; overflow-y: scroll">
        <div id="append-comment-body">
          <div class="d-flex justify-content-center mt-2">
            <img src="https://upload.wikimedia.org/wikipedia/en/thumb/8/8c/Trello_logo.svg/1280px-Trello_logo.svg.png"
              height="28" alt="MDB Logo" loading="lazy" />
          </div>
          <hr>
          <div style="display: flex ">
            <hr>
            <div style="">
              <div class=" ">
              </div>
              <div>
                <div>
                  <label class="ms-2 mt-1" id="modal-title">Title *</label>
                  <input style="width:400px" id="task-title" class="form-control ms-2" />
                  <span id="tsk-title" class="mb-1"></span>
                  <label class="modal-desc ms-2 mt-3 mb-1" id="modal-desc">Description *</label>
                  <div style="width: 400px" id="task-modal-desc">
                    <textarea class="form-control ms-2 " id="task-desc" rows="3"></textarea>
                    <span id="tsk-desc"></span>
                  </div>
                  <h6 class="mt-4 ms-2">Attachment</h6>
                  <input class="ms-2" style="min-width:100px" class="mb-4" id="task-file" type="file" name="files[]"
                    multiple="multiple" />

                  <div id="attachment-on-edit">
                    <span id="file-error"></span>
                  </div>
                </div>
                <div>
                </div>
              </div>
            </div>

            <div class="">
              <div class="ms-5 mt-1">
                <label>Assign Task</label>
                <br>
                <select style="width:250px" id="assignTaskSelect2" class=" js-select2" multiple="multiple">
                </select>

                <h6 class="mb-2" id="task-status-label"></h6><br>
                <select style="width:250px" id="statusSelect2" class=" mt-1 js-example-basic-single">

                </select>
                <div id="modal-members"></div>
                <div>

                  <ul class="dropdown-menu">
                    <input onChange="getCustomTaskStatus()" id="custom-status" class="form-control"
                      placeholder="Custom Status ..." />

                    <li>
                      <hr class="dropdown-divider">
                    </li>

                    <li id="task-status">
                    </li>
                  </ul>
                </div>
              </div>
            </div>

          </div>
          <label class="ms-2 mt-3 ">Activity</label>
          <input style="width:95%" id="task-comment" class="ms-2 mb-3  form-control" placeholder="Add Comment ..." />
        </div>

        <div class="modal-footer me-2">
          <button onClick="resetModal()" type="button" class="btn btn-secondary"
            data-mdb-dismiss="modal">Cancel</button>
          <button id="save-task" type="button" class="ms-4 btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.1.min.js"
  integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $('#statusSelect2').select2({
        dropdownParent: $('#exampleModal ')
    });
  
  $('#assignTaskSelect2').select2({
        dropdownParent: $('#exampleModal')
    });  
</script>

<script type="text/javascript">
  $('#statusSelect2').change(function(){
    localStorage.setItem("statusChangeFlag",true)
    console.log('whats this',$('#statusSelect2').val())
    localStorage.setItem('status',$('#statusSelect2').val())
    })

  localStorage.setItem("assignee","unassigned");
  function saveTask(data2){
    console.log(data2)
    taskFile.append('data',JSON.stringify(data2))
    
         $.ajax({
            url:data2.url,
            data:taskFile,
            dataType:'json',
            type:'post',
            contentType: false,
            processData: false,
            headers:{'Authorization': `Bearer ${localStorage.getItem('jwt-token')}`,
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:  function (res) {    
              console.log('show more del',res)   
                $('#no-task-msg').remove()                                      
              if(!res.edit){
                var message="Task Added sucessfully"
                  $(`#task-list-msg-append`).prepend(`<x-action-modal  message=${message}/>`)
                  setTimeout(() => {    
                    $('#response-message').remove()
                  }, 2000);
          }
          if(res.edit){
            var message="Task Updated sucessfully"
              $(`#task-list-msg-append`).prepend(`<x-action-modal  message=${message}/>`)
              setTimeout(() => {    
                $('#response-message').remove()
              }, 2000);
            if($(`#project-task-${res.id}`).parent().children().length == 2){ 
                    $(`#project-task-${res.id}`).parent().remove()
                    console.log($(`#show-more-${res.status_id}`))
                    $(`#show-more-${res.status_id}`).remove()

                  }
                  $(`#project-task-${res.id}`).remove()
              } 
            // console.log('error',res)
            if($(`#status-${res.status_id}`).children().length === 0){
                $('#task-list ').prepend(`<div id=status-`+res.status_id+`><div  class="badge ms-2 mt-2 d-flex justify-content-center" style="background-color:#009999;width:25%" >`+res.status[0].status+`</div></div>`)
              }
              $(`#status-${res.status_id}  > div:nth-child(`+(1)+`)`).after(
                `<x-task-list id=${res.id} title=${res.title} description=${res.description}/>`
              )
            $('#exampleModal').modal('toggle')
              resetModal()
          } ,
            error: function(err){ 
              if(JSON.parse(err.responseText)['extensionFlag'])
              {
                // console.log('yes itsworking')
              }
              if(err.status == 400){
                if(JSON.parse(err.responseText)['0']['data.title']){
                  $('#tsk-title').append(`<small class="ms-2 " style="color:red">`+JSON.parse(err.responseText)['0']['data.title'][0].replace('data.','')+`</small><br>`)
                }
                if(JSON.parse(err.responseText)['0']['data.description']){
                  $('#tsk-desc').append(`<small class="ms-2" style="color:red">`+JSON.parse(err.responseText)['0']['data.description'][0].replace('data.','')+`</small>`)
                }
                if(JSON.parse(err.responseText)['sizeFlag']){
                  $('#file-error').append(`<small class="ms-2" style="color:red">File size should be 10 mb MAX.</small><br>`)
                }
                if(JSON.parse(err.responseText)['extensionFlag']){
                  $('#file-error').append(`<small class="ms-2" style="color:red">File should be of png,jpeg,jpg type only</small>`)
                }
              }
            }
          })
  }

  $(document).on('click','#add-task',function(e){
    $.each(JSON.parse(localStorage.getItem("Available_Status")),function(key,val){
      // console.log(val)
      //man issues is this only as value not taking afer space
      $('#statusSelect2').append(`
        <option value=`+val.id+`>`+val.status+`</option>      
      `)
  })
    $.ajax({
            url:'api/add/assignees',
            data:{"project_id":localStorage.getItem('project_id')},
            type:'get',
            headers:{'Authorization': `Bearer ${localStorage.getItem('jwt-token')}`,
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:  function (res) {
              $.each(res,function(key,mem){
                $('#assignTaskSelect2').append(`
                    <option value=`+mem.id+`>`+mem.email+`</option>      
                `) 
              })

            },
            error: function(x){}
          })
          editOrAddFlag="add"
          $('#exampleModal').modal('show')   
      });  

      function resetModal(){
       
        localStorage.setItem("status",JSON.parse(localStorage.getItem("Available_Status"))[0].id);
        localStorage.setItem("assignee","unassigned");
        localStorage.setItem("statusChangeFlag",false);
        $('#statusSelect2').empty()
        
        $('#assignTaskSelect2').html("")
        // $('#statusSelect2').append('<option selected>Choose Status</option>')
        $('added-current-status').remove()
        $("#task-title").val("")
        $("#task-desc").val("")
        $("#custom-status").val("")
        $("#task-comment").val("")
        $("#task-file").val("")
        $("#comment-body").remove()
        $("#task-status").html("")

        $("#tsk-title").html("")
        $("#tsk-desc").html("")
        $('#file-error').html("")
        $('#datalistOptions').html("")
        $('#datalistOptions').append(`<option value="unassigned">Assign Task</option>`)
        $('#task-status-label').text("")
        $('#status-heading').text("")
        $('#attachment-on-edit').html("")
        
        
        $('#modal-members').html("")
        localStorage.setItem("comments",JSON.stringify([]))
      }
      $(document).on('click','#save-task',function(e){
        // console.log(  $('#statusSelect2').val())
        $("#tsk-title").html("")
        $("#tsk-desc").html("")
        $('#file-error').html("")
        taskFile = new FormData()
        taskFile.append('file',($('#task-file')[0].files[0]))
        // console.log($(this))
        for (var i = 0; i < $('#task-file').get(0).files.length; ++i) {
             taskFile.append('files[]', $('#task-file').get(0).files[i]);
        }
        // console.log(...taskFile)
        // return
        var data ={
          "title":$("#task-title").val(),
          "description":$("#task-desc").val(),
        }

        data2={
        "member_id":localStorage.getItem('member_id'),
        "data":data,
        "comments":JSON.parse(localStorage.getItem("comments")),
        "assignee":$('#assignTaskSelect2').val(),
      }
       
        if(editOrAddFlag === "add"){
          data['project_id'] = localStorage.getItem("project_id");
          data['status_id'] = localStorage.getItem("status")
          data2['url'] = 'api/addTask'
          saveTask(data2)
        }
        else {
          data['id'] = task_id
          statusSendFlag = localStorage.getItem("statusChangeFlag")

          if( statusSendFlag === 'true'){
            data['status_id'] = localStorage.getItem("status")
          }
          data2['url'] = 'api/updateTask'
          // console.log(data2)
          saveTask(data2)
        }

      });

      function renderComments(cmnt){

        $('#comment-body').append(`
              <div class="mt-1 ms-2 me-4" style="background-color:#e9f1f7;border-radius:0.5rem">
                <div class="mt-1 ms-1 d-flex justify-content-between-start " >
                  <i class="fas fa-user-tie mt-1"></i>
                  <small style="font-size:11px" class="ms-4 " id="modal-desc">`+ cmnt.first_name+` `+cmnt.last_name+`</small>
                  <small style="font-size:11px" class="ms-4 " id="modal-desc">`+ new Date(cmnt.updated_at).toLocaleString() +`</small>
                  </div>
                  <div style="font-size:10px" class="ms-5 fs-6 " id="modal-desc">`+ cmnt.description +`</div>
                </div> 
              </div>

                `)
      }

      function modalForEditOrAdd(task){

      //  console.log('k ',task)
        $.each(JSON.parse(localStorage.getItem("Available_Status")),function(key,val){
          if(val.status !== task[0].status ){
            $('#statusSelect2').append(`
            <option  value=`+val.id+`>`+val.status+`</option>      
           `)
          }
         
        })
        $('#statusSelect2').prepend(` <option selected value=`+task[0].status_id+`>`+task[0].status+`</option>   `)

        // console.log('going to apply--->',task[0].status)
    
        // $('#statusSelect2').val(task[0].status).trigger('change')
        
        JSON.parse(localStorage.getItem("Available_Status"))
        // console.log('heyyyy checking aviliable status')
        editOrAddFlag="edit"
        $("#task-title").val(task[0].title)
        $("#task-desc").val(task[0].description)
        
        if(task[0].attachments.length>0){
          $('#attachment-on-edit').append(`
            <div  id="modal-attachments">
            </div>          
              `)
        var srt=0;end=2
        for(i=0;i<=task[0].attachments.length/2;i++){

          $('#modal-attachments').append(
          `<div id=mdl-atch-${srt} class="row mt-3 "></div>`)
        for(j=srt;j<end;j++){
          if(!task[0].attachments[j])
            continue
            $(`#mdl-atch-${srt}`).append(`
            <div class="col-4 ms-5" style="display: flex ">
             <iframe seamless="seamless" scrolling="no" frameborder="0" allowtransparency="true" class="ms-4" height="65"  width="150" src=viewTaskAttachment/${task[0].attachments[j].attachment}" class="ms-4">
            </iframe>
            <a class="" href="http://localhost:8000/downloadTaskAttachment/${task[0].attachments[j].attachment}" target="_blank" >
              <i class="fas fa-file-download"></i>
            </a>
          </div>
            `)
          }
          srt=srt+2;
          end=end+2
        }
        }
        if(task[0].comments.length>0){
          $(`#append-comment-body`).append(`
        <div style="max-height: 150px; overflow-y: auto;" id="comment-body"></div>
       
        `)
        }
        $.each(task[0].comments,function(key,cmnt){
                renderComments(cmnt)
              }) 
        $('#exampleModal').modal('show')
      }
      function editOrAddTask(id){

        task_id=id
        $.ajax({
            url:'api/taskDetails',
            data:{"id":id},
            type:'get',
            headers:{'Authorization': `Bearer ${localStorage.getItem('jwt-token')}`,
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:  function (task) {
              // console.log(task)
              $('#status-heading').text('Status')
              // $('#task-status-label').append(`
              // <label class="mt-4">Current Status</label><br>
              // <a tittle="Status of Task" class="badge badge-dark mt-2" style="width: 97%"; >`+task[0].status+`</a>`)
             
              modalForEditOrAdd(task)
            },
            error: function(x,xs,xt){}
          })
      //[{"title":"","attachment":"","description":""}]
      }
      
      $(document).on("click", "#task-status li", function() {

        // console.log($(this).text().toLowerCase())
        // console.log('yes i am changed')

        localStorage.setItem("status",$(this).text());  
      });
           
     function getCustomTaskStatus(){
      // console.log('yes i am changed --custom')
      localStorage.setItem("statusChangeFlag",true)
      localStorage.setItem("status",$("#custom-status").val().toLowerCase());
      }
     
      $('#task-comment').bind('keypress', function(e) {
        if(e.keyCode==13){
            if($("#task-comment").val()!=""){
              cmnts = JSON.parse(localStorage.getItem("comments")) 
              cmnts.push($("#task-comment").val())
              localStorage.setItem("comments",JSON.stringify(cmnts));
              cmnt = {"description":$("#task-comment").val(),"first_name":localStorage.getItem('first_name'),"last_name":localStorage.getItem('last_name'),"updated_at":new Date()}
             
              if($('#comment-body').length==0){
                $(`#append-comment-body`).append(`
                  <div style="max-height: 150px; overflow-y: auto;" id="comment-body"></div>
                `)
              }
              // console.log()
              renderComments(cmnt)
            }
        $("#task-comment").val("")
              
        }
      });

      $(document).on('click','.edit-task',function(e){
        // $.each(JSON.parse(localStorage.getItem("Available_Status")),function(key,status){
      // console.log(status)
      // $('#statusSelect2').append(`
      //   <option  value=`+status.replaceAll(' ','-')+`>`+status+`</option>      
      // `)
  // })
              $.ajax({
                    url:'api/members',
                    data:{"task_id":$(this).attr('data-task-edit-id'),"project_id":localStorage.getItem('project_id')},
                    type:'get',
                    headers:{'Authorization': `Bearer ${localStorage.getItem('jwt-token')}`,
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success:  function (res) {
                      localStorage.setItem('project-assignee-members',JSON.stringify(res))
                    },
                    error: function(){}
                  })

        $.ajax({
            url:'api/add/assignees',
            data:{"project_id":localStorage.getItem('project_id'),"task_id":$(this).attr('data-task-edit-id')},
            type:'get',
            headers:{'Authorization': `Bearer ${localStorage.getItem('jwt-token')}`,
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:  function (res) {
              $.each(res,function(key,mem){
                $('#assignTaskSelect2').append(`
                    <option value=`+mem.id+`>`+mem.email+`</option>      
                `) 
              })
              selectedMembers = []
              $.each(JSON.parse(localStorage.getItem('project-assignee-members')),function(key,item){
                selectedMembers.push(item.id)
              })
              $('#assignTaskSelect2').val(selectedMembers)
              console.log('check selectedMembers',selectedMembers)
            },
            error: function(x,xs,xt){}
          })
          $('#exampleModal').modal('show')
            editOrAddTask($(this).attr('data-task-edit-id'))
          })
      $('#datalistOptions').on('change' ,function(){
            localStorage.setItem("assignee",$(this).val())
           
      });
      $(document).on('click','.del-task',function(e){
        $('#confirmModalDel').modal('show')
        localStorage.setItem('tsk-del-id',$(this).attr('data-task-del-id'))
      })

      $(document).on('click','#cancel-task-del',function(e){
        $('#confirmModalDel').modal('toggle')
      })
      $(document).on('click','#del-task-final',function(e){
        $('#confirmModalDel').modal('toggle')
          deleteTask(localStorage.getItem('tsk-del-id'))
      })
      function deleteTask(id){
            var id=id
            console.log('--->',id)
            // return
            $.ajax({
            url:'api/delTask',
            data:{"task_id":id},
            type:'delete',
            headers:{'Authorization': `Bearer ${localStorage.getItem('jwt-token')}`,
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:  function (res) {
              console.log('del',res)
              var message="Task Deleted sucessfully"
              $(`#task-list-msg-append`).prepend(`<x-action-modal  message=${message}/>`)
              setTimeout(() => {    
                // console.log('webapi--')
                $('#response-message').remove()
              }, 2000);
              $('#responseModalMsg').modal('show')
              setTimeout(() => {
                console.log('webapi--')
                $('#responseModalMsg').modal('toggle')
              }, 1000);

              $(`#project-task-${id}`).remove()
            
              if($(`#status-${res.status_id}`).children().length == 1){     
                $(`#status-${res.status_id}`).html("")
                $(`#show-more-${res.status_id}`).html("")
              }
            },
            error: function(x){}
           })
        }
</script>