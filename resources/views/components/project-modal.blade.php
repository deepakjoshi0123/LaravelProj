<!-- Modal -->
<div class="modal fade" id="projectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Create Project</h4>
                <button id="project-modal-close" type="button" class="btn-close" data-mdb-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label> Project Name</label>
                <input id="project-name" placeholder="Enter project name " class="form-control" />
                <span id="prj-title"></span>
            </div>
            <div class="modal-footer">
                <button id="save-project" type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).on('click','#project-modal-close',function(){
        $('#project-name').val("")
  })
</script>