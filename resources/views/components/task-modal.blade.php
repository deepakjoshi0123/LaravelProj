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


<script type="text/javascript" src="{{ URL::asset('js/helpers.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/task-modal.js') }}"></script>

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
  $('#statusSelect2').change(statusChangeHandler)

  $(document).on('click','#add-task',addTask);  
  $(document).on('click','#save-task',saveTaskHandler);
  $(document).on("click", "#task-status li", taskStatusChange);
  $('#task-comment').bind('keypress', addCommentHnadler);
  $(document).on('click','.edit-task',editTaskHandler)
      
  $('#datalistOptions').on('change' ,function(){
      localStorage.setItem("assignee",$(this).val())
  });
  $(document).on('click','.del-task',delTaskHandler)

  $(document).on('click','#cancel-task-del',cancelTaskHandler)
  $(document).on('click','#del-task-final', delAfterConfirmation)
     
</script>