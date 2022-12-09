<!DOCTYPE html>
<html lang="en">
<x-title />

<body>
  <style>
    /* width */
    ::-webkit-scrollbar {
      width: 8px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
      box-shadow: inset 0 0 5px grey;
      border-radius: 5px;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
      background: rgb(183, 207, 222);
      border-radius: 5px;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
      background: #b4c5d2;
    }
  </style>
  <div class="div">
    <div class="div">
      <!-- Navbar -->
      <x-nav-bar-dash />
    </div>
    <div style="display: flex ">
      <x-projects-list />
      <x-nav-bar-tasks />
    </div>
    <!-- Modal -->
    <x-task-modal />
    <x-project-Modal />
    <x-share-proj />
    <x-filter-modal />
    <x-status-create-modal />
    <x-confirmmation-modal />

  </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.1.min.js"
  integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script type="text/javascript">
  $(document).ready(function(){
    
    function parseJwt (token) {
       return JSON.parse(atob(token.split('.')[1]));
    }

    var project_id,task_id
    var editOrAddFlag
    var tasks = {};
    $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
      // console.log('cheeelkkkk................')
      if(options.url === 'api/refresh' || options.url === '/logout' || options.refreshRequest  ){
        return ;
      }
      jqXHR.abort();
      const token = localStorage.getItem('jwt-token');
      var ttl;
      if(token){
        ttl = (new Date(parseJwt(token).exp*1000) - new Date(Date.now()))/1000;
      }
        // console.log((new Date(parseJwt(token).exp*1000) - new Date(Date.now()))/1000)
        
        if(ttl/60<15 && ttl/60>0){
          options.refreshRequest = true
          $.ajax({
            url:'api/refresh',
            headers:{'Authorization': `Bearer ${localStorage.getItem('jwt-token')}` },
            type:'get',
            success:  function (res) {
               localStorage.setItem('jwt-token',res) 
               options['headers']['Authorization']=`Bearer ${localStorage.getItem('jwt-token')}`
               $.ajax(options) 
            },
            error: function(err){}
          })
        }
        else {
          options.refreshRequest = true
            $.ajax(options)
        }
  });

    localStorage.setItem("comments",JSON.stringify([]));  //setting up temp array of comments for modal popup
    localStorage.setItem("proj_old_id",999999)
    // localStorage.setItem("status","OPEN");
    
$.ajax({
    url:'api/projects',
    data:{"member_id":localStorage.getItem('member_id')},
    headers:{'Authorization': `Bearer ${localStorage.getItem('jwt-token')}`,
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
  },
    type:'get',
    success:  function (response) { 
      if(response.length === 0){
        $('#side-bar').append(`<div id="no-projects-title"><h6  style="margin-top:150px" class="ms-2"> You don't have any Projects yet.</h6></div>`)
      }
      $.each(response,function(key,item){
            $('#side-bar').append(
                `<div onMouseOver="this.style.color='blue'"
                      onMouseOut="this.style.color='black'" id="project-`+item.id+`" data-project-id=`+item.id+` 
                        style="background-color: #e9f1f7;cursor: pointer"
                        class="project-item list-group-item list-group-item-action py-2 ripple 
                        data-mdb-toggle="tooltip" data-mdb-placement="bottom" title="Created by ${item.first_name} ${item.last_name}"
                        ">
                        <i  class="me-2 fab fa-medapps"></i><span id="project-title`+item.id+`">`+item.project_name+`</span>
                  </div>`
            )
        });
      $(`#project-${response[0].id}`).click()
    },
    error: function(x,xs,xt){
          console.log(x);
        } 
    }); //prettier 

    //WIP localStorage.getItem('page_rec')  $(this).attr('data-show-more-id')
    $(document).on('click','.show-more',function(e){
      // var pageNo = 
      let pageRec = JSON.parse(localStorage.getItem('page_rec'))

      pageRec[`${$(this).attr('data-show-more-id').replaceAll('-',' ')}`] = pageRec[`${$(this).attr('data-show-more-id').replaceAll('-',' ')}`] + 1 
      localStorage.setItem('page_rec',JSON.stringify(pageRec))
      // console.log( pageRec )
      // return
      
      $.ajax({
      url:'api/getNextTasks',
      data:{"project_id":localStorage.getItem('project_id'),"status_id":$(this).attr('data-show-more-id').replaceAll('-',' '),"pageNo":pageRec[`${$(this).attr('data-show-more-id').replaceAll('-',' ')}`]},
      headers:{'Authorization': `Bearer ${localStorage.getItem('jwt-token')}`,
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      },
        type:'get',
        success:  function (response) {
          console.log('got res ',response.tasks)
          showTask(response.tasks,response.tasks[0].status_id,response.len)
        },
        error:  function(err){}
        })
      })

  function showTask(item,key,len){
      // console.log('show task', item ,key,len)
      $.each(item,function(key2,item2){
              //  var memToSend = JSON.stringify(item2.members)
              // console.log( item2.members[0].first_name)
              var mmnt=""
              if(item2.members.length === 0){
                mmnt=`No members added in this task`
              }  
              if(item2.members.length === 1){
                mmnt=`${item2.members[0].first_name} added in this task`
              }  
              if(item2.members.length === 2){
                mmnt=`${item2.members[0].first_name} and ${item2.members[1].first_name} added in this task`
              } 
              if(item2.members.length > 2){
                mmnt=`${item2.members[0].first_name},${item2.members[1].first_name} and ${item2.members.length -2} more added in this task`
              }        
                // console.log('chec my key',key,item)
                $(`#status-${key}`).append(
                  `<x-task-list  id=${item2.id} title=${item2.title} description=${item2.description} memToShow=${mmnt}/>`)
            })
            // console.log('check it ---- ',item,key,len)
            $(`#show-more-${key}`).remove();
            if(len>0){
              $(`#status-${key}`).append(`<div style="cursor:pointer;font-size:12px;color:blue" data-show-more-id="`+key+`" id="show-more-`+key+`" class="ms-3 show-more">see `+len+` more </div>`)
            }
         }

        $(document).on('click','.project-item',function(e){
          var proj_id = $(this).attr('data-project-id')
          // localStorage.setItem("Available_Status",JSON.stringify(['OPEN','CLOSED','WIP']))
          $(`#project-title-nav`).text($(`#project-title${proj_id}`).text())
          //break this into another function
         //____________________________________________________________________________________________ 
          if(localStorage.getItem('proj_old_id')!= proj_id){
            if(document.getElementById(`project-${localStorage.getItem('proj_old_id')}`) != null){
              document.getElementById(`project-${localStorage.getItem('proj_old_id')}`).style.backgroundColor = '#e9f1f7'
            }
          }
          document.getElementById(`project-${proj_id}`).style.backgroundColor = 'white'
          localStorage.setItem('proj_old_id',proj_id) 
          localStorage.setItem("project_id", $(this).attr('data-project-id'));
          //___________________________________________________________________________________________ 
          document.getElementById("add-task").disabled = false;
          $("#search-task").prop('disabled', false);
          
              
        $.ajax({
              url:'api/getCustomStatus',
              data:{"project_id":$(this).attr('data-project-id')},
              headers:{'Authorization': `Bearer ${localStorage.getItem('jwt-token')}`,
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
              },
              type:'get',
              success:  function (res) {
                // console.log('check res', res)
                let sts=[]
                for(let i=0;i<res.length;i++){
                    sts.push(res[i].id)
                }
                console.log(sts)
                let pageRec = sts.reduce((keys, val) => ({ ...keys, [val]: 0}), {}) 
                // console.log('check my response',pageRec)
                localStorage.setItem("Available_Status",JSON.stringify(res));
                localStorage.setItem("page_rec",JSON.stringify(pageRec));
                localStorage.setItem("status",res[0].id);
                // console.log('yet this is only',localStorage.getItem('Available_Status'))
              },
              error:  function(err){}
            })

            $.ajax({
                url:'api/tasks',
                data:{"project_id":$(this).attr('data-project-id')},
                headers:{'Authorization': `Bearer ${localStorage.getItem('jwt-token')}`,
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
              },
                type:'get',
                success:  function (response) {
                    // console.log('checking ress',response)
                    $('#task-list').html("")
                    tasks=response
                    // console.log('its wrong',localStorage.getItem('Available_Status'))
                    if(response.length === 0){
                        // console.log('no task to display')
                        $('#task-list').append(`<div id="no-task-msg"><h5 style="margin-top:20px;margin-left:250px">There are no tasks in this project Yet ...</h5></div>`)  
                        return
                  }
                    $.each(response,function(key,item){
                      // console.log('pa',item)
                      $('#task-list').append(`<div id="status-`+response[key].id+`"><div class="">
                      <div style="background-color:#009999" class="badge  ms-2 mt-2 mb-1" style="width:25%" >`+response[key].status+`</div>
                      </div></div>
                      `)
                      // console.log()
                      showTask(item[item.status],response[key].id,response[key].len)
                     
                           
                    });
                  },
                  error: function(x,xs,xt){
                  }
                });
                
                })
              })
</script>
<script src="{{ asset('js/mdb.min.js') }}">
</script>

</html>