<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>register</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4" style="margin-top:50px ">
                <h4>Member Registration</h4>
                <hr>
                <form>
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" class="form-control" id="first_name" value=""
                            placeholder="enter your first name">
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" class="form-control" id="last_name" value=""
                            placeholder="enter your last name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" id="email" value="" placeholder="enter your email">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" class="form-control" password="password" value=""
                            placeholder="enter your password">
                    </div>
                    <div class="form-group" style="margin-top:20px">
                        <button id="register" class="btn btn-block btn-primary" type="submit">Register</button>
                    </div>
                    <a href="login" style="margin-top:10px">If already registered Login here</a>
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
        $(document).on('click','#register',function(e){
        e.preventDefault()

        data={
        'email':$('#email').val(),
        'password':$('#password').val(),
        'first_name':$('#first_name').val(),
        'last_name':$('#last_name').val(),
        'is_verfied':0
        }
        console.log(data)
        $.ajax({
            url:'register',
            data:data,
            type:'post',
            success:  function (response) {
              console.log(response)
            },
            error: function(error){
                console.log(error)
            }
        })
    })
         
    })
</script>


</html>