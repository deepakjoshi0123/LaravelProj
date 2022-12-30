<!-- Modal -->
<div class="modal fade" id="shareProjModal" tabindex="-1" aria-labelledby="shareProjModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="shareProjModalLabel">Share project</h4>
                <button id="proj-share-close" type="button" class="btn-close" data-mdb-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5> Enter vaild email</h5>
                <input id="share-project-email" placeholder="Enter project name " class="form-control" />
                <span id="email-share-error"></span>
            </div>
            <div class="modal-footer">
                <button id="share-proj-email" type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script type="text/javascript">
    // var assignee = "Unassigned";  
  $(document).on('click','#share-project-button',function(){
        $('#shareProjModal').modal('toggle')
    })
  $(document).on('click','#proj-share-close',function(){
    $('#email-share-error').html('')
    $('#share-project-email').val('') 
  })
  $(document).on('click','#share-proj-email',function(){
            // console.log($('#share-project-email').val())
            $('#email-share-error').html('')
            $.ajax({
                    url:'api/shareProject',
                    data:{"email":$('#share-project-email').val(),"project_id":localStorage.getItem('project_id')},
                    type:'get',
                    success:  function (response) {
                        var message="Member Added to Project sucessfully"
                            $(`#task-list-msg-append`).prepend(`<x-action-modal  message=${message}/>`)
                            setTimeout(() => {    
                                // console.log('webapi--')
                                $('#response-message').remove()
                            }, 2000);
                        console.log(response)
                        $('#share-project-email').val('') 
                        $('#shareProjModal').modal('toggle')
                    },
                    error:    function (err) {
                    if(JSON.parse(err.responseText)['email']){
                       $('#email-share-error').append(`<span  style="color:red">`+JSON.parse(err.responseText)['email'][0]+`</span>`)
                       }
                    if(err.responseJSON[0]){
                       $('#email-share-error').append(`<span  style="color:red">`+err.responseJSON[0]+`</span>`)
                    }
                }
            })
            
    })
</script>