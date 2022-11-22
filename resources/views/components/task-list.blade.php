<div class=" ms-1 " id="project-task-{{$id}}">
    <div style="display:flex">
        <div class="card border-primary mt-1 mb-1 " style="width: 62.3rem;" data-task-id={{$id}}>
            <div class="card-header d-flex justify-content-between">
                <div class="">{{$title}}
                </div>
                <i data-task-del-id={{$id}} class="del-task fa fa-times fa-sm mt-2 me-2"></i>
            </div>
            <div data-task-edit-id={{$id}} class="edit-task card-body text-primary">
                <p class="card-text">{{$description}}</p>
            </div>
        </div>
    </div>
</div>