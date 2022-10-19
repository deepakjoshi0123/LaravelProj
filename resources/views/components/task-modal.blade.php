<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div style="display: flex ">
                <div>
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title">Ttile</h5>
                        <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div id="modal-body">
                        <input id="task-title" class="form-control" />
                        <h6 class="modal-desc" id="modal-desc">Descriptiom</h6>
                        <input class="form-control" id="task-desc" />
                        <h6 class="modal-attach" id="modal-desc">Attachment</h6>
                        <input class="form-control" id="task-attachment" />
                        <h6 class="modal-status" id="modal-desc">Status</h6>
                        <input class="form-control" id="task-status" />
                        <input id="task-comment" class="form-control" placeholder="Add Comment ..." />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Cancel</button>
                        <button id="save-task" type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
                <div style="width:60%">
                    <h6>right side content for assignee and task status</h6>
                </div>
            </div>
        </div>
    </div>
</div>