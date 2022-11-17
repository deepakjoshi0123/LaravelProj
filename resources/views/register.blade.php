<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>register</title>
    <!-- CSS only -->
    <link href="{{ asset('css/mdb.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="shortcut icon" href="/favicon.ico">
</head>

<body
    style="background-size: cover ;  background-image: url(https://img.freepik.com/premium-photo/abstract-smooth-dark-blue-with-black-vignette-studio-well-use-as-background-business-report-digital-website-template-backdrop_1258-53251.jpg)">
    <div class="container">
        <img class="mt-2" style="height:50px;width:200px"
            src="https://upload.wikimedia.org/wikipedia/en/thumb/8/8c/Trello_logo.svg/1280px-Trello_logo.svg.png" />
        <div class="row">
            <div class="col-md-4 col-md-offset-4" style="margin-top:10px; margin-left:330px">

                <div class="card" style="width: 31rem;">
                    <div class="card-body">
                        <div id="success-msg"></div>
                        <h6>Member Registration</h6>
                        <hr>
                        <form class="ms-2 me-2">
                            <div class="row mt-3">
                                <div class="form-group col">
                                    <label for="firstName">First Name *</label>
                                    <input type="text" class="form-control" id="first_name" value=""
                                        placeholder="Enter your first name">
                                    <small id="first_name-span">
                                    </small>
                                </div>
                                <div class="form-group col">
                                    <label for="lastName">Last Name *</label>
                                    <input type="text" class="form-control" id="last_name" value=""
                                        placeholder="Enter your last name">
                                    <small id="last_name-span"></small>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label for="email">Email *</label>
                                <input type="text" class="form-control" id="email" value=""
                                    placeholder="Enter your email">
                                <small id="email-span"></small>
                            </div>
                            <div class="form-group mt-3">
                                <label for="password">Password *</label>

                                <input id="password" type="password" class="form-control" password="password" value=""
                                    placeholder="Enter your password">
                                <small id="password-span"></small>
                            </div>
                            <div class="form-group mt-3">
                                <label for="password">Confirm Password *</label>
                                <input id="cnf-password" type="password" class="form-control" password="password"
                                    value="" placeholder="Enter your password">
                                <small id="cnf-password-span"></small>
                            </div>
                            <div class="form-group mt-4">
                                <button id="register" class="btn btn-block btn-primary form-control"
                                    type="submit">Register</button>
                            </div>
                            <a href="login" class="mt-3 btn btn-primary form-control">Login</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
</script>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $.ajaxSetup({
         headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
        })
        $(document).on('click','#register',function(e){
        e.preventDefault()
        $('#email-span').html("")
        $('#first_name-span').html("")
        $('#last_name-span').html("")
        $('#password-span').html("")
        $('#cnf-password-span').html("")
        $('#success-msg').html("")
        data={
        'email':$('#email').val(),
        'password':$('#password').val(),
        'first_name':$('#first_name').val(),
        'last_name':$('#last_name').val(),
        'cnf-password':$('#cnf-password').val()
        }
        // console.log(data)
        $.ajax({
            url:'api/register',
            data:data,
            type:'post',
            success:  function (response) {
                $('#success-msg').append(`<h6 class="ms-5" style="color:green" >congratulations! check mail for verification</h6>`)
                setTimeout(()=>{
                    window.location.href = "http://localhost:8000/login";
                }, 2000);
            },
            error: function(err){
                console.log(err)
                if(err.status == 400){
                     if(JSON.parse(err.responseText)['email']){
                       $('#email-span').append(`<span  style="color:red">`+JSON.parse(err.responseText)['email'][0]+`</span>`)
                       }
                     if(JSON.parse(err.responseText)['password']){
                       $('#password-span').append(`<span  style="color:red">`+JSON.parse(err.responseText)['password'][0]+`</span>`)
                       }
                     if(JSON.parse(err.responseText)['first_name']){
                       $('#first_name-span').append(`<span  style="color:red">`+JSON.parse(err.responseText)['first_name'][0]+`</span>`)
                       }
                     if(JSON.parse(err.responseText)['last_name']){
                       $('#last_name-span').append(`<span style="color:red">`+JSON.parse(err.responseText)['last_name'][0]+`</span>`)
                       }
                     if(JSON.parse(err.responseText)['cnf-password']){
                       $('#cnf-password-span').append(`<span style="color:red">`+JSON.parse(err.responseText)['cnf-password'][0]+`</span>`)
                       }
                    if(err.responseJSON[0]){
                       $('#email-span').append(`<span  style="color:red">`+err.responseJSON[0]+`</span>`)
                    }
                }
            }
        })
    })
         
    })
</script>


</html>