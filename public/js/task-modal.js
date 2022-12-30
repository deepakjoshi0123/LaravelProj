function statusChangeHandler() {
    localStorage.setItem("statusChangeFlag", true);
    localStorage.setItem("status", $("#statusSelect2").val());
}

function saveTask(data2) {
    console.log(data2);
    taskFile.append("data", JSON.stringify(data2));

    $.ajax({
        url: data2.url,
        data: taskFile,
        dataType: "json",
        type: "post",
        contentType: false,
        processData: false,
        success: function (res) {
            $("#no-task-msg").remove();
            if (!res.edit) {
                let page_rec = JSON.parse(localStorage.getItem("page_rec"));
                console.log("new", page_rec, page_rec[res.status_id]);
                page_rec[res.status_id].Add = page_rec[res.status_id].Add + 1;
                localStorage.setItem("page_rec", JSON.stringify(page_rec));
                var message = "Task Added sucessfully";
                $(`#task-list-msg-append`).prepend(
                    `<x-action-modal  message=${message}/>`
                );
                setTimeout(() => {
                    $("#response-message").remove();
                }, 2000);
            }

            if (res.edit) {
                var message = "Task Updated sucessfully";
                $(`#task-list-msg-append`).prepend(
                    `<x-action-modal  message=${message}/>`
                );
                setTimeout(() => {
                    $("#response-message").remove();
                }, 2000);
                if (
                    $(`#project-task-${res.id}`).parent().children().length == 2
                ) {
                    $(`#project-task-${res.id}`).parent().remove();
                    console.log($(`#show-more-${res.status_id}`));
                }

                $(`#project-task-${res.id}`).remove();
            }
            if ($(`#status-${res.status_id}`).children().length === 0) {
                $("#task-list").append(
                    `<div id="status-` +
                        res.status_id +
                        `"><div class="">
                  <div style="background-color:#009999" class="badge  ms-2 mt-2 mb-1" style="width:25%" >` +
                        res.status[0].status +
                        `</div>
                </div></div>
              `
                );
            }
            let resArr = [res];
            showTask(resArr, res.status_id);
            $("#exampleModal").modal("toggle");
            resetModal();
        },
        error: function (err) {
            if (JSON.parse(err.responseText)["extensionFlag"]) {
            }
            if (err.status == 400) {
                if (JSON.parse(err.responseText)["0"]["data.title"]) {
                    $("#tsk-title").append(
                        `<small class="ms-2 " style="color:red">` +
                            JSON.parse(err.responseText)["0"][
                                "data.title"
                            ][0].replace("data.", "") +
                            `</small><br>`
                    );
                }
                if (JSON.parse(err.responseText)["0"]["data.description"]) {
                    $("#tsk-desc").append(
                        `<small class="ms-2" style="color:red">` +
                            JSON.parse(err.responseText)["0"][
                                "data.description"
                            ][0].replace("data.", "") +
                            `</small>`
                    );
                }
                if (JSON.parse(err.responseText)["sizeFlag"]) {
                    $("#file-error").append(
                        `<small class="ms-2" style="color:red">File size should be 10 mb MAX.</small><br>`
                    );
                }
                if (JSON.parse(err.responseText)["extensionFlag"]) {
                    $("#file-error").append(
                        `<small class="ms-2" style="color:red">File should be of png,jpeg,jpg type only</small>`
                    );
                }
            }
        },
    });
}

function addTask(e) {
    $.each(
        JSON.parse(localStorage.getItem("Available_Status")),
        function (key, val) {
            $("#statusSelect2").append(
                `
        <option value=` +
                    val.id +
                    `>` +
                    val.status +
                    `</option>      
      `
            );
        }
    );
    $.ajax({
        url: `api/projects/${localStorage.getItem("project_id")}/members`,
        type: "get",
        success: function (res) {
            $.each(res, function (key, mem) {
                $("#assignTaskSelect2").append(
                    `
                    <option value=` +
                        mem.id +
                        `>` +
                        mem.email +
                        `</option>      
                `
                );
            });
        },
        error: function (x) {},
    });
    editOrAddFlag = "add";
    $("#exampleModal").modal("show");
}

function saveTaskHandler(e) {
    $("#tsk-title").html("");
    $("#tsk-desc").html("");
    $("#file-error").html("");
    taskFile = new FormData();
    taskFile.append("file", $("#task-file")[0].files[0]);
    for (var i = 0; i < $("#task-file").get(0).files.length; ++i) {
        taskFile.append("files[]", $("#task-file").get(0).files[i]);
    }
    var data = {
        title: $("#task-title").val(),
        description: $("#task-desc").val(),
    };

    data2 = {
        member_id: localStorage.getItem("member_id"),
        data: data,
        comments: JSON.parse(localStorage.getItem("comments")),
        assignee: $("#assignTaskSelect2").val(),
    };
    if (editOrAddFlag === "add") {
        data["status_id"] = localStorage.getItem("status");
        data2["url"] = `api/projects/${localStorage.getItem(
            "project_id"
        )}/task`;
        saveTask(data2);
    } else {
        statusSendFlag = localStorage.getItem("statusChangeFlag");
        if (statusSendFlag === "true") {
            data["status_id"] = localStorage.getItem("status");
        }
        data2["url"] = `api/projects/${localStorage.getItem(
            "project_id"
        )}/task/${task_id}`;
        saveTask(data2);
    }
}

function modalForEditOrAdd(task) {
    $.each(
        JSON.parse(localStorage.getItem("Available_Status")),
        function (key, val) {
            if (val.status !== task[0].status) {
                $("#statusSelect2").append(
                    `
        <option  value=` +
                        val.id +
                        `>` +
                        val.status +
                        `</option>      
       `
                );
            }
        }
    );
    $("#statusSelect2").prepend(
        ` <option selected value=` +
            task[0].status_id +
            `>` +
            task[0].status +
            `</option>   `
    );
    JSON.parse(localStorage.getItem("Available_Status"));
    editOrAddFlag = "edit";
    $("#task-title").val(task[0].title);
    $("#task-desc").val(task[0].description);

    if (task[0].attachments.length > 0) {
        $("#attachment-on-edit").append(`
        <div  id="modal-attachments">
        </div>          
          `);
        var srt = 0;
        end = 2;
        for (i = 0; i <= task[0].attachments.length / 2; i++) {
            $("#modal-attachments").append(
                `<div id=mdl-atch-${srt} class="row mt-3 "></div>`
            );

            for (j = srt; j < end; j++) {
                if (!task[0].attachments[j]) continue;
                $(`#mdl-atch-${srt}`).append(`
        <div class="col-4 ms-5" style="display: flex ">
         <iframe seamless="seamless" scrolling="no" frameborder="0" allowtransparency="true" class="ms-4" height="65"  width="150" src=/projects/${localStorage.getItem(
             "project_id"
         )}/tasks/${task[0].id}/attachment/${
                    task[0].attachments[j].attachment
                }" class="ms-4">
        </iframe>
        <a class="" href="http://localhost:8000/projects/${localStorage.getItem(
            "project_id"
        )}/tasks/${task[0].id}/attachment/${
                    task[0].attachments[j].attachment
                }/download" target="_blank" >
          <i class="fas fa-file-download"></i>
        </a>
      </div>
        `);
            }
            srt = srt + 2;
            end = end + 2;
        }
    }
    if (task[0].comments.length > 0) {
        $(`#append-comment-body`).append(`
    <div style="max-height: 150px; overflow-y: auto;" id="comment-body"></div>
   
    `);
    }
    $.each(task[0].comments, function (key, cmnt) {
        renderComments(cmnt);
    });
    $("#exampleModal").modal("show");
}
function editOrAddTask(id) {
    task_id = id;
    $.ajax({
        url: `api/projects/${localStorage.getItem("project_id")}/tasks/${id}`,
        type: "get",
        success: function (task) {
            $("#status-heading").text("Status");
            modalForEditOrAdd(task);
        },
        error: function (x, xs, xt) {},
    });
}

function getCustomTaskStatus() {
    localStorage.setItem("statusChangeFlag", true);
    localStorage.setItem("status", $("#custom-status").val().toLowerCase());
}

function taskStatusChange() {
    localStorage.setItem("status", $(this).text());
}

function addCommentHnadler(e) {
    if (e.keyCode == 13) {
        if ($("#task-comment").val() != "") {
            cmnts = JSON.parse(localStorage.getItem("comments"));
            cmnts.push($("#task-comment").val());
            localStorage.setItem("comments", JSON.stringify(cmnts));
            cmnt = {
                description: $("#task-comment").val(),
                first_name: localStorage.getItem("first_name"),
                last_name: localStorage.getItem("last_name"),
                updated_at: new Date(),
            };

            if ($("#comment-body").length == 0) {
                $(`#append-comment-body`).append(`
              <div style="max-height: 150px; overflow-y: auto;" id="comment-body"></div>
            `);
            }
            renderComments(cmnt);
        }
        $("#task-comment").val("");
    }
}

function editTaskHandler() {
    $.ajax({
        url: `api/projects/${localStorage.getItem("project_id")}/tasks/${$(
            this
        ).attr("data-task-edit-id")}/members`,
        type: "get",
        success: function (res) {
            localStorage.setItem(
                "project-assignee-members",
                JSON.stringify(res)
            );
        },
        error: function () {},
    });

    $.ajax({
        url: `api/projects/${localStorage.getItem("project_id")}/members`,
        type: "get",
        success: function (res) {
            $.each(res, function (key, mem) {
                $("#assignTaskSelect2").append(
                    `
              <option value=` +
                        mem.id +
                        `>` +
                        mem.email +
                        `</option>      
          `
                );
            });
            selectedMembers = [];
            $.each(
                JSON.parse(localStorage.getItem("project-assignee-members")),
                function (key, item) {
                    selectedMembers.push(item.id);
                }
            );
            $("#assignTaskSelect2").val(selectedMembers);
            console.log("check selectedMembers", selectedMembers);
        },
        error: function (x, xs, xt) {},
    });
    $("#exampleModal").modal("show");
    editOrAddTask($(this).attr("data-task-edit-id"));
}

function cancelTaskHandler() {
    $("#confirmModalDel").modal("toggle");
}

function delTaskHandler() {
    $("#confirmModalDel").modal("show");
    localStorage.setItem("tsk-del-id", $(this).attr("data-task-del-id"));
}

function delAfterConfirmation(e) {
    $("#confirmModalDel").modal("toggle");
    deleteTask(localStorage.getItem("tsk-del-id"));
}
