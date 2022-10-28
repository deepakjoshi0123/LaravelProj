<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>login</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4" style="margin-top:50px ">
                <div id="unauth"></div>
                <h4>Login</h4>
                <hr>
                <form method="post">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="text" class="form-control" name="email" value=""
                            placeholder="enter your email">
                        <span id="email-span"></span>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" class="form-control" name="password" value=""
                            placeholder="enter your password">
                        <span id="password-span"></span>
                    </div>
                    <div class="form-group" style="margin-top:20px">
                        <button id="login" class="btn btn-block btn-primary" type="submit">Login</button>
                    </div>
                    <a href="register" style="margin-top:10px">new user !! Register here</a>
                </form>
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
        $(document).on('click','#login',function(e){
        $('#email-span').html("")
        $('#password-span').html("")
        $('#unauth').html("")
        e.preventDefault()
        
        data={'email':$('#email').val(),
        'password':$('#password').val() 
        }

        $.ajax({
            url:'login',
            data:data,
            type:'post',
            success:  function (response) {
                console.log(response)
                $('#unauth').append(`<h4 class="ms-5" style="color:green">`+"Success"+`</h4>`)
                setTimeout(()=>{
                    window.location.href = "http://localhost:8000/dashboard";
                }, 2000);
            },
            error: function(err){
                if(err.status == 400){
                     if(JSON.parse(err.responseText)['email']){
                       $('#email-span').append(`<span class="ms-5" style="color:red">`+JSON.parse(err.responseText)['email'][0]+`</span>`)
                       }
                     if(JSON.parse(err.responseText)['password']){
                       $('#password-span').append(`<span class="ms-5" style="color:red">`+JSON.parse(err.responseText)['password'][0]+`</span>`)
                       }
                }
              if(err.status == 401){
                $('#unauth').append(`<h5 class="ms-5" style="color:red">`+JSON.parse(err.responseText)['error']+`</h5>`)
              }
            }
        })
    })
         
    })
</script>

</html>