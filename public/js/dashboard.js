function parseJwt(token) {
    return JSON.parse(atob(token.split(".")[1]));
}
function setHeaders() {
    $.ajaxSetup({
        beforeSend: function (xhr) {
            console.log("check");
            xhr.setRequestHeader(
                "Authorization",
                `Bearer ${
                    document.cookie.split(`jwt-token=`).pop().split(";")[0]
                }`
            );
            xhr.setRequestHeader(
                "X-CSRF-TOKEN",
                $('meta[name="csrf-token"]').attr("content")
            );
        },
    });
}

function reqPreFilter() {
    $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
        if (
            options.url === "api/refresh" ||
            options.url === "/logout" ||
            options.refreshRequest
        ) {
            return;
        }
        jqXHR.abort();
        const token = document.cookie.split(`jwt-token=`).pop().split(";")[0];
        var ttl;
        if (token) {
            ttl =
                (new Date(parseJwt(token).exp * 1000) - new Date(Date.now())) /
                1000;
        }
        if (ttl / 60 < 15 && ttl / 60 > 0) {
            options.refreshRequest = true;
            $.ajax({
                url: "api/refresh",
                type: "get",
                success: function (res) {
                    options["headers"]["Authorization"] = `Bearer ${
                        document.cookie.split(`jwt-token=`).pop().split(";")[0]
                    }`;
                    $.ajax(options);
                },
                error: function (err) {},
            });
        } else {
            options.refreshRequest = true;
            $.ajax(options);
        }
    });
}

function getUserInfo() {
    $.ajax({
        url: "api/getUserInfo",
        type: "get",
        success: function (res) {
            localStorage.setItem("member_id", res.id);
            localStorage.setItem("first_name", res.first_name);
            localStorage.setItem("last_name", res.last_name);
            $("#user-name").text(`Hi ! ${res.first_name}  ${res.last_name}`);
        },
        error: function (err) {},
    });
}

function showProjects(response) {
    if (response.length === 0) {
        $("#side-bar").append(
            `<div id="no-projects-title"><h6  style="margin-top:150px" class="ms-2"> You don't have any Projects yet.</h6></div>`
        );
    }
    $.each(response, function (key, item) {
        $("#side-bar").append(
            `<div onMouseOver="this.style.color='blue'"
                        onMouseOut="this.style.color='black'" id="project-` +
                item.id +
                `" data-project-id=` +
                item.id +
                ` 
                          style="background-color: #e9f1f7;cursor: pointer"
                          class="project-item list-group-item list-group-item-action py-2 ripple 
                          data-mdb-toggle="tooltip" data-mdb-placement="bottom" title="Created by ${item.first_name} ${item.last_name}"
                          ">
                          <i  class="me-2 fab fa-medapps"></i><span id="project-title` +
                item.id +
                `">` +
                item.project_name +
                `</span>
                    </div>`
        );
    });
    $(`#project-${response[0].id}`).click();
}

function getProjects() {
    $.ajax({
        url: "api/projects",
        type: "get",
        success: (response) => showProjects(response),
        error: function (x) {
            console.log(x);
        },
    });
}
function setLocalStorage() {
    localStorage.setItem("comments", JSON.stringify([]));
    localStorage.setItem("proj_old_id", 999999);
}

function showMoreTasksPg() {
    let pageRec = JSON.parse(localStorage.getItem("page_rec"));
    pageRec[`${$(this).attr("data-show-more-id")}`].pageNo =
        pageRec[`${$(this).attr("data-show-more-id")}`].pageNo + 1;
    console.log(pageRec[`${$(this).attr("data-show-more-id")}`].pageNo);
    localStorage.setItem("page_rec", JSON.stringify(pageRec));

    $.ajax({
        url: `api/projects/${localStorage.getItem("project_id")}/tasks`,
        data: {
            status_id: $(this).attr("data-show-more-id"),
            pageNo: pageRec[`${$(this).attr("data-show-more-id")}`].pageNo,
            add: pageRec[`${$(this).attr("data-show-more-id")}`].Add,
            del: pageRec[`${$(this).attr("data-show-more-id")}`].del,
        },
        type: "get",
        success: (response) => {
            let index = Object.keys(response)[0];
            showTask(
                response[index][response[index].status],
                response[index][response[index].status][0].status_id,
                JSON.parse(localStorage.getItem("page_rec"))[
                    `${$(this).attr("data-show-more-id")}`
                ].pageNo *
                    2 +
                    1
            );
            showMore(
                response[index][response[index].status][0].status_id,
                response[index].len,
                "show-more-tasks",
                `show-more-tasks-${
                    response[index][response[index].status][0].status_id
                }`
            );
        },
        error: function (err) {},
    });
}

function showProjectTasks() {
    var proj_id = $(this).attr("data-project-id");
    $(`#project-title-nav`).text($(`#project-title${proj_id}`).text());

    if (localStorage.getItem("proj_old_id") != proj_id) {
        if (
            document.getElementById(
                `project-${localStorage.getItem("proj_old_id")}`
            ) != null
        ) {
            document.getElementById(
                `project-${localStorage.getItem("proj_old_id")}`
            ).style.backgroundColor = "#e9f1f7";
        }
    }
    document.getElementById(`project-${proj_id}`).style.backgroundColor =
        "white";
    localStorage.setItem("proj_old_id", proj_id);
    localStorage.setItem("project_id", $(this).attr("data-project-id"));
    document.getElementById("add-task").disabled = false;
    $("#search-task").prop("disabled", false);

    taskRefresh();

    $.ajax({
        url: "api/getCustomStatus",
        data: { project_id: $(this).attr("data-project-id") },
        type: "get",
        success: function (res) {
            // console.log('check res', res)
            let pageRec = {};
            for (let i = 0; i < res.length; i++) {
                pageRec[res[i].id] = { pageNo: 0, del: 0, Add: 0 };
            }

            // let pageRec = sts.reduce((keys, val) => ({ ...keys, [val]: 0}), {})
            // console.log('check my response',pageRec)
            localStorage.setItem("Available_Status", JSON.stringify(res));
            localStorage.setItem("page_rec", JSON.stringify(pageRec));
            localStorage.setItem("status", res[0].id);
            // console.log('yet this is only',localStorage.getItem('Available_Status'))
        },
        error: function (err) {},
    });
}

function taskRefresh() {
    $.ajax({
        url: `api/projects/${localStorage.getItem("project_id")}/tasks`,
        data: {
            pageNo: 0,
            add: 0,
            del: 0,
        },
        type: "get",
        success: function (response) {
            console.log("checking ress", response);
            $("#task-list").html("");
            tasks = response;
            // console.log('its wrong',localStorage.getItem('Available_Status'))
            if (response.length === 0) {
                // console.log('no task to display')
                $("#task-list").append(
                    `<div id="no-task-msg"><h5 style="margin-top:20px;margin-left:250px">There are no tasks in this project Yet ...</h5></div>`
                );
                return;
            }
            $.each(response, function (key, item) {
                // console.log('pa',item)
                $("#task-list").append(
                    `<div id="status-` +
                        response[key].id +
                        `"><div class="">
                  <div style="background-color:#009999" class="badge  ms-2 mt-2 mb-1" style="width:25%" >` +
                        response[key].status +
                        `</div>
                  </div></div>
                  `
                );
                // console.log()
                showTask(item[item.status], response[key].id);
                showMore(
                    response[key].id,
                    response[key].len,
                    "show-more-tasks",
                    `show-more-tasks-${response[key].id}`
                );
            });
        },
        error: function (x, xs, xt) {},
    });
}
