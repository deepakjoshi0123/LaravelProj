<!-- Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="statusModalLabel">Create New Status</h4>
        <button id="status-modal-close" type="button" class="btn-close" data-mdb-dismiss="modal"
          aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label>Status</label>
        <input id="status-name" placeholder="Enter Custom Status ... " class="form-control" />
        <span id="status-err-title"></span>
      </div>
      <div class="modal-footer">
        <button id="save-status" type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).on('click','#status-modal-close',function(){
        $('#status-name').val("")
        $('#status-err-title').html("")
  })
  
  $(document).on('click','#add-status',function(){
    $('#statusModal').modal('show')
  })

  $(document).on('click','#save-status',function(){
    $.ajax({
            url:'api/create/status',
            data:{"project_id":localStorage.getItem('project_id'),'status':$('#status-name').val()},
            type:'post',
            success:  function (res) {
              console.log('status res',res)
              var message="Custom Status created sucessfully"
                            $(`#task-list-msg-append`).prepend(`<x-action-modal  message=${message}/>`)
                            setTimeout(() => {    
                                // console.log('webapi--')
                                $('#response-message').remove()
                            }, 2000);
                // console.log(res[0])
                $('#statusModal').modal('toggle')
                $('#status-name').val("")
                sts=JSON.parse(localStorage.getItem('Available_Status'))
                page_rec=JSON.parse(localStorage.getItem('page_rec'))
                page_rec[res.id]={'add':0,'del':0,'pageNo':0}
                console.log({"id":res.id,"status":res.status})
                sts.push({"id":res.id,"status":res.status})
                uniq = [...new Set(sts)];
                // console.log(uniq)
                localStorage.setItem("Available_Status",JSON.stringify(uniq));
                localStorage.setItem("page_rec",JSON.stringify(page_rec));
                $('#status-err-title').html("")
              },
            error: function(err){
                if(err.status == 400){
                  $('#status-err-title').html("")
                    if(JSON.parse(err.responseText)['status']){
                    $('#status-err-title').append(`<small class="" style="color:red">`+JSON.parse(err.responseText)['status'][0]+`</small>`)
                    }
                    else{
                        $('#status-err-title').append(`<small class="" style="color:red">`+(err.responseJSON)+`</small>`)
                    }
              }
            }
          })
    
  })
</script>