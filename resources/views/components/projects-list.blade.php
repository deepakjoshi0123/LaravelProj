<div class="div"
    style="width:20%;height:548px ;overflow-y: scroll; scrollbar-width: thin;scrollbar-color: blue orange;">
    <nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse bg-white">
        <div class="position-sticky">
            <div id="project-heading-title"></div>
            <div class="list-group list-group-flush " id="side-bar">
                <div style="background-color:#a1d0ef;" class=" d-flex justify-content-between ">
                    <div class="ms-4" style="pdding:0.2rem">
                        <h5 class=" ms-5 mt-2  ">Projects</h5>
                    </div>
                    <div class="nav-item mt-1 me-3">
                        <a class="" data-mdb-toggle="modal" data-mdb-target="#projectModal">
                            <i class="far fa-plus-square fa-lg mt-2" style="color: black;cursor: pointer"
                                data-mdb-toggle="tooltip" data-mdb-placement="bottom" title="Add Project"
                                onMouseOver="this.style.color='DodgerBlue'" onMouseOut="this.style.color='black'"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>
<script type="text/javascript">
    $(document).on('click','#save-project',function(){
        // console.log($('#project-name').val())

        $.ajax({
            url:'api/createProject',
            data:{"owner":"3","name":$('#project-name').val()},
            type:'post',
            headers:{'Authorization': `Bearer ${localStorage.getItem('jwt-token')}`,
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:  function (res) {
              
                $('#side-bar').append(
                    `<div  id="project-`+res.data.id+`" data-project-id=`+res.data.id+` 
                        style="background-color: #e9f1f7;border-radius: 10px 10px;"
                        class=" project-item list-group-item list-group-item-action py-2 ripple ">
                        <i  class="me-2 fab fa-medapps"></i><span id="project-title`+res.data.id+`">`+res.data.project_name+`</span>
                  </div>`
            )
                $('#project-name').val("")
                $('#projectModal').modal('toggle')
                $('#prj-title').html("")
            
            },
            error: function(err){
                if(err.status == 400){
                  $('#prj-title').html("")
                if(JSON.parse(err.responseText)['name']){
                  $('#prj-title').append(`<small class="" style="color:red">`+JSON.parse(err.responseText)['name'][0]+`</small>`)
                }
              }
            }
          })    
    })
</script>