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
                <span id="status-title-err"></span>
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
  })
  
  $(document).on('click','#add-status',function(){
    $('#statusModal').modal('show')
  })

  $(document).on('click','#save-status',function(){
    $.ajax({
            url:'api/create/status',
            data:{"project_id":localStorage.getItem('project_id'),'status':$('#status-name').val()},
            type:'post',
            headers:{'Authorization': `Bearer ${localStorage.getItem('jwt-token')}`,
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:  function (res) {
                // console.log(res[0])
                $('#statusModal').modal('toggle')
                $('#status-name').val("")
                sts=JSON.parse(localStorage.getItem('Available_Status'))
                for(let i=0;i<res.length;i++){
                    sts.push(res[i].status.toUpperCase())
                }
                uniq = [...new Set(sts)];
                // console.log(uniq)
                localStorage.setItem("Available_Status",JSON.stringify(uniq));
              },
            error: function(err){}
          })
    
  })
</script>