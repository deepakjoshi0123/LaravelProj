<!-- Modal -->
<div class="modal fade" id="confirmModalDel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="confirmModalLabel">Delete Task</h4>
                <button id="project-modal-close" type="button" class="btn-close" data-mdb-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label>Are you sure wants to delete this task ?</label>
            </div>
            <div class="modal-footer">
                <button id="cancel-task-del" type="button" class="btn btn-secondary">cancel</button>
                <button id="del-task-final" type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).on('click','#project-modal-close',function(){
        $('#project-name').val("")
  })
</script>