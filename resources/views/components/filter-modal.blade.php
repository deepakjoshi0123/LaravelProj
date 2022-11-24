<!-- Modal -->
<div>
    <div class="modal fade" id="filterModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Apply filters</h4>
                    <button id="filter-modal-close" type="button" class="btn-close" data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Members</h5>
                    <select style="width:100%" id="mySelect2" class="js-example-basic-multiple" name="states[]"
                        multiple="multiple">
                    </select>
                    <h5>Status</h5>
                    <select style="width:100%" id="mySelect3" class="js-example-basic-multiple" name="states[]"
                        multiple="multiple">
                    </select>
                </div>
                <div class="modal-footer">
                    <button id="save-filters" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $('#mySelect2').select2({
        dropdownParent: $('#filterModal')
    });
    $('#mySelect3').select2({
        dropdownParent: $('#filterModal')
    });
</script>
<script type="text/javascript">
    $(document).on('click','#filter-modal-close',function(){
        $('#mySelect3').html("")
        $('#mySelect2').html("")
  })
    $(document).on('click','#navbarDropdownMenuAvatar-filter-task',function(e){
        $('#filterModal').modal('toggle')
       
        $.ajax({
            url:'api/add/assignees',
            data:{"project_id":localStorage.getItem('project_id')},
            type:'get',
            headers:{'Authorization': `Bearer ${localStorage.getItem('jwt-token')}`,
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:  function (res) {
                console.log(res)
                $.each(res,function(key,mem){   
                    $('#mySelect2').append(`
                    <option value=`+mem.id+`>`+mem.email+`</option>      
                    `)
                })
            },
            error: function (err){}
            })   
        $.each(JSON.parse(localStorage.getItem('Available_Status')),function(key,mem){
           
            $('#mySelect3').append(`
            <option value=`+mem+`>`+mem+`</option>  
            `)
        })
    })
    $(document).on('click','#save-filters',function(e){
        $('#filterModal').modal('toggle')
        var filters = {"status":[''],"members":['']}; // it's sending null remove it at the backend before queryig to db
        for (const status of $('#mySelect3').val() ) 
            { filters.status.push(status) }
        for (const member of $('#mySelect2').val() ) 
            { filters.members.push(member) }
       
        $('#mySelect3').html("")
        $('#mySelect2').html("")
        data={"project_id":localStorage.getItem("project_id"),'filters':filters}
        // console.log('data->',data)
        // return
        $.ajax({
            url:'api/filterTask',
            data:data,
            type:'get',
            headers:{'Authorization': `Bearer ${localStorage.getItem('jwt-token')}`,
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:  function (response) {
            // console.log(res)
            $('#task-list').html("")
            $.each(response,function(key,item){
          
          $('#task-list').append(`<div id="status-`+key+`"><div  class="badge badge-dark ms-2 mt-2" style="width:10%" >`+key.replaceAll(' ','').replaceAll("'",'')+`</div></div>`)
            $.each(item,function(key2,item2){
                $(`#status-${key.replaceAll(' ','').replaceAll("'",'')}`).append(
                    `<x-task-list id=${item2.id} title=${item2.title} description=${item2.description}/>`
                 )
            })            
        });
           
            },
            error: function(err){}
        })
    
    })
</script>