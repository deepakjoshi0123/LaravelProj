<div class=" ms-1 " id="project-task-{{$id}}">
    <style>
        /* Tooltip container */
        .tooltipCus {
            position: relative;
            display: inline-block;

        }

        /* Tooltip text */
        .tooltipCus .tooltiptext {
            visibility: hidden;
            width: 220px;
            background-color: rgb(83, 80, 80);
            color: #fff;
            text-align: center;
            padding: 5px 0;
            border-radius: 6px;
            top: -5px;
            right: 105%;

            /* Position the tooltip text - see examples below! */
            position: absolute;
            z-index: 1;
        }

        /* Show the tooltip text when you mouse over the tooltip container */
        .tooltipCus:hover .tooltiptext {
            visibility: visible;
        }
    </style>

    <div style="display:flex">
        <div class="card border-primary mt-1 mb-1 " style="width: 62.3rem;" data-task-id={{$id}}>
            <div style="padding:0.15rem 0.75rem" class="card-header d-flex">
                <div class="p-2 flex-grow-1">{{$title}}
                </div>
                <div class="me-2" id="modal-members-{{$id}}">

                </div>

                <div>
                    <div class="tooltipCus">
                        <i class="fas fa-users ms-4 me-4 mt-2" onMouseOver="this.style.color='DodgerBlue'"
                            onMouseOut="this.style.color='black'" style="cursor: pointer"></i>
                        <small class="tooltiptext">{{ $memToShow }}</small>
                    </div>

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