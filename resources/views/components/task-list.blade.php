<div class=" ms-1 " id="project-task-{{$id}}">
    <div style="display:flex">
        <div class="card border-primary mt-1 mb-1 " style="width: 62.3rem;" data-task-id={{$id}}>
            <div style="padding:0.25rem 0.75rem" class="card-header d-flex">
                <div class="p-2 flex-grow-1">{{$title}}
                </div>
                <div class="me-2" id="modal-members-{{$id}}"></div>
                {{-- {{ $mem }} --}}
                {{-- {{ dd($mem) }} --}}
                @foreach((array)$mem as $index=>$key)
                {{'deepak'}}
                @endforeach

                <div>

                    <i data-task-edit-id={{$id}} class="edit-task fas fa-edit fa-sm mt-2 me-4" style="cursor: pointer"
                        onMouseOver="this.style.color='DodgerBlue'" onMouseOut="this.style.color='black'"></i>
                    <i data-task-del-id={{$id}} class="del-task far fa-trash-alt fa-sm mt-2 me-2"
                        style="cursor: pointer" onMouseOver="this.style.color='DodgerBlue'"
                        onMouseOut="this.style.color='black'"></i>
                </div>
            </div>
            <div style="padding:1rem 1rem;cursor: pointer" data-task-edit-id={{$id}}
                class="edit-task card-body text-primary">
                <p class="card-text">{{$description}}</p>
            </div>
        </div>
    </div>
</div>