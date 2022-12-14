// function hello(name) {
//     console.log(`hello this is helper ${name}`);
// }

function showTask(item, key) {
    // console.log("show task", item, key, len);
    $.each(item, function (key2, item2) {
        //  var memToSend = JSON.stringify(item2.members)
        // console.log("2nd loop", item2, key2);
        var mmnt = "";
        if (item2.members.length === 0) {
            mmnt = `No members added in this task`;
        }
        if (item2.members.length === 1) {
            mmnt = `${item2.members[0].first_name} added in this task`;
        }
        if (item2.members.length === 2) {
            mmnt = `${item2.members[0].first_name} and ${item2.members[1].first_name} added in this task`;
        }
        if (item2.members.length > 2) {
            mmnt = `${item2.members[0].first_name},${
                item2.members[1].first_name
            } and ${item2.members.length - 2} more added in this task`;
        }
        $(`#status-${key}  > div:nth-child(` + 1 + `)`).after(
            `<div class=" ms-1 " id="project-task-` +
                item2.id +
                `">
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
        <div class="card border-primary mt-1 mb-1 " style="width: 62.3rem;" data-task-id=` +
                item2.id +
                `>
            <div style="padding:0.15rem 0.75rem" class="card-header d-flex">
                <div class="p-2 flex-grow-1">` +
                item2.title +
                `
                </div>
                <div class="me-2" id="modal-members-` +
                item2.id +
                `">

                </div>

                <div>
                    <div class="tooltipCus">
                        <i class="fas fa-users ms-4 me-4 mt-2" onMouseOver="this.style.color='DodgerBlue'"
                            onMouseOut="this.style.color='black'" style="cursor: pointer"></i>
                        <small class="tooltiptext">` +
                mmnt +
                `</small>
                    </div>

                    <i data-task-edit-id=` +
                item2.id +
                ` class="edit-task fas fa-edit fa-sm mt-2 me-4" style="cursor: pointer"
                        onMouseOver="this.style.color='DodgerBlue'" onMouseOut="this.style.color='black'"></i>
                    <i data-task-del-id=` +
                item2.id +
                ` class="del-task far fa-trash-alt fa-sm mt-2 me-2"
                        style="cursor: pointer" onMouseOver="this.style.color='DodgerBlue'"
                        onMouseOut="this.style.color='black'"></i>

                </div>
            </div>
            <div style="padding:1rem 1rem;cursor: pointer" data-task-edit-id=` +
                item2.id +
                `
                class="edit-task card-body text-primary">
                <p class="card-text">` +
                item2.description +
                `</p>
            </div>
        </div>
    </div>
</div>`
        );
    });
}

function showMore(key, len, clsName, id) {
    // if () {
    $(`#${id}`).remove();
    // }

    if (len > 0) {
        $(`#status-${key}`).append(
            `<div style="cursor:pointer;font-size:12px;color:blue" data-show-more-id="` +
                key +
                `" id=` +
                id +
                ` class="ms-2 ${clsName} ">see ` +
                len +
                ` more </div>`
        );
    }
}

function resetModal() {
    localStorage.setItem(
        "status",
        JSON.parse(localStorage.getItem("Available_Status"))[0].id
    );
    localStorage.setItem("assignee", "unassigned");
    localStorage.setItem("statusChangeFlag", false);
    $("#statusSelect2").empty();

    $("#assignTaskSelect2").html("");
    // $('#statusSelect2').append('<option selected>Choose Status</option>')
    $("added-current-status").remove();
    $("#task-title").val("");
    $("#task-desc").val("");
    $("#custom-status").val("");
    $("#task-comment").val("");
    $("#task-file").val("");
    $("#comment-body").remove();
    $("#task-status").html("");

    $("#tsk-title").html("");
    $("#tsk-desc").html("");
    $("#file-error").html("");
    $("#datalistOptions").html("");
    $("#datalistOptions").append(
        `<option value="unassigned">Assign Task</option>`
    );
    $("#task-status-label").text("");
    $("#status-heading").text("");
    $("#attachment-on-edit").html("");

    $("#modal-members").html("");
    localStorage.setItem("comments", JSON.stringify([]));
}

function deleteTask(id) {
    var id = id;
    console.log("--->", id);
    // return
    // console.log('del  --> ',JSON.parse(localStorage.getItem('page_rec'))[id])
    // return
    $.ajax({
        url: "api/delTask",
        data: { task_id: id },
        type: "delete",
        headers: {
            Authorization: `Bearer ${localStorage.getItem("jwt-token")}`,
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (res) {
            let page_rec = JSON.parse(localStorage.getItem("page_rec"));
            page_rec[res.status_id].del =
                JSON.parse(localStorage.getItem("page_rec"))[res.status_id]
                    .del + 1;
            localStorage.setItem("page_rec", JSON.stringify(page_rec));
            // console.log('del  --> ',localStorage.getItem('page_rec'))
            var message = "Task Deleted sucessfully";
            $(`#task-list-msg-append`).prepend(
                `<x-action-modal  message=${message}/>`
            );
            setTimeout(() => {
                // console.log('webapi--')
                $("#response-message").remove();
            }, 2000);
            $("#responseModalMsg").modal("show");
            setTimeout(() => {
                console.log("webapi--");
                $("#responseModalMsg").modal("toggle");
            }, 1000);

            $(`#project-task-${id}`).remove();

            if ($(`#status-${res.status_id}`).children().length == 1) {
                $(`#status-${res.status_id}`).html("");
                $(`#show-more-${res.status_id}`).html("");
            }
        },
        error: function (x) {},
    });
}

function renderComments(cmnt) {
    $("#comment-body").append(
        `
          <div class="mt-1 ms-2 me-4" style="background-color:#e9f1f7;border-radius:0.5rem">
            <div class="mt-1 ms-1 d-flex justify-content-between-start " >
              <i class="fas fa-user-tie mt-1"></i>
              <small style="font-size:11px" class="ms-4 " id="modal-desc">` +
            cmnt.first_name +
            ` ` +
            cmnt.last_name +
            `</small>
              <small style="font-size:11px" class="ms-4 " id="modal-desc">` +
            new Date(cmnt.updated_at).toLocaleString() +
            `</small>
              </div>
              <div style="font-size:10px" class="ms-5 fs-6 " id="modal-desc">` +
            cmnt.description +
            `</div>
            </div> 
          </div>

            `
    );
}
