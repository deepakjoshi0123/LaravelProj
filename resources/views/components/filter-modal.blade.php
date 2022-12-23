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
                    <label>Members</label>
                    <small style="font-size:10px">[Select Email of members whose you want to see the tasks]</small>
                    <select style="width:100%" id="mySelect2" class="js-example-basic-multiple" name="states[]"
                        multiple="multiple">
                    </select>
                    <label class="mt-3 ">Status</label>
                    <small style="font-size:10px">[Choose Status to filter the tasks]</small>
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

<script type="text/javascript" src="{{ URL::asset('js/helpers.js') }}"></script>
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
       console.log('filter modal clicked')
        $.ajax({
            url:'api/add/assignees',
            data:{"project_id":localStorage.getItem('project_id')},
            type:'get',
            headers:{'Authorization': `Bearer ${localStorage.getItem('jwt-token')}`,
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:  function (res) {
                // console.log(res)
                $.each(res,function(key,mem){   
                    $('#mySelect2').append(`
                    <option value=`+mem.id+`>`+mem.email+`</option>      
                    `)
                })
            },
            error: function (err){}
            })   
        $.each(JSON.parse(localStorage.getItem('Available_Status')),function(key,mem){
        //    console.log(mem)
            $('#mySelect3').append(`
            <option value=`+mem.id+`>`+mem.status+`</option>  
            `)
        })
    })
    $(document).on('click','#save-filters',function(e){
        $('#filterModal').modal('toggle')
        var filters = {"status":[''],"members":['']}; // it's sending null remove it at the backend before queryig to db
        for (const status of $('#mySelect3').val() ) 
            { filters.status.push(status.replaceAll('-',' ')) }
        for (const member of $('#mySelect2').val() ) 
            { filters.members.push(member) }
            let res = Object.keys(JSON.parse(localStorage.getItem('page_rec')))
      
      let pageRec ={}
          for(let i=0;i<res.length;i++){
            pageRec[res[i]] = {'pageNo':0,'del':0,'Add':0}
          }
      localStorage.setItem("page_rec",JSON.stringify(pageRec));
        $('#mySelect3').html("")
        $('#mySelect2').html("")
        data={"project_id":localStorage.getItem("project_id"),'filters':filters,
        "pageNo":0,
        "add":0,
        "del":0
    }
       localStorage.setItem('filterData',JSON.stringify(data))
       
        // console.log(pageRec)
        // return 
        $.ajax({
            url:'api/filterTask',
            data:data,
            type:'get',
            success:  function (response) {
            if(!response.length){
                    response = Object.values(response)
                }
            console.log(response , typeof response , response.length)
            // return
            $('#task-list').html("")
            // console.log('no task to display')
            if(response.length === 0){
            // console.log('no task to display')
            $('#task-list').append(`<div id="no-task-msg"><h5 style="margin-top:20px;margin-left:250px">There are no tasks which matches the search criteria ...</h5></div>`)  
            return
          }
            console.log('check item',response)
            $.each(response,function(key,item){
                console.log(item[item.status],response[key].id)
                $('#task-list').append(`<div id="status-`+response[key].id+`"><div class="">
                  <div style="background-color:#009999" class="badge  ms-2 mt-2 mb-1" style="width:25%" >`+item.status+`</div>
                </div></div>
              `)
            showTask(item[item.status],response[key].id)
            showMore(response[key].id,response[key].len,'show-more-filter-tasks',`show-more-filter-tasks-${response[key].id}`)           
        });
     },
        error: function(res){}
    })
    
    })

    $(document).on('click','.show-more-filter-tasks',function(e){
       
       console.log($(this).attr('data-show-more-id'),'hi filter')
        let pageRec = JSON.parse(localStorage.getItem('page_rec'))
         pageRec[`${$(this).attr('data-show-more-id')}`].pageNo = pageRec[`${$(this).attr('data-show-more-id')}`].pageNo + 1 
         console.log(pageRec[`${$(this).attr('data-show-more-id')}`].pageNo)
         localStorage.setItem('page_rec',JSON.stringify(pageRec))
        // return
      $.ajax({
         url:'api/filterTask',
         data:{"filters":JSON.parse(localStorage.getItem('filterData'))['filters'],
         "status_id":$(this).attr('data-show-more-id'),
         "pageNo":pageRec[`${$(this).attr('data-show-more-id')}`].pageNo,
         "add":pageRec[`${$(this).attr('data-show-more-id')}`].Add,
         "del":pageRec[`${$(this).attr('data-show-more-id')}`].del,
         "project_id":localStorage.getItem('project_id')
       },
           type:'get',
           success: (response) => {
             console.log('got res ',response[0][response[0].status][0].status_id,response[0].status,response)
             showTask(response[0][response[0].status],response[0][response[0].status][0].status_id,JSON.parse(localStorage.getItem('page_rec'))[`${$(this).attr('data-show-more-id')}`].pageNo*2+1)
             showMore(response[0][response[0].status][0].status_id,response[0].len,'show-more-filter-tasks',`show-more-filter-tasks-${response[0][response[0].status][0].status_id}`)
           },
           error:  function(err){}
           })
       })

</script>