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

<body
    style="background-size: cover ;  background-image: url(https://cdn.pixabay.com/photo/2017/10/31/19/05/web-design-2906159__480.jpg)">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4" style="margin-top:120px; margin-left:330px">
                <div id="unauth"></div>
                <div class="card" style="width: 30rem;">
                    <div class="card-body">
                        <h3 style="color:red;margin-left:130px">{{ $errors->first('unauthorized') }}</h3>
                        <h4>Login</h4>

                        <hr>

                        <form method="post" action="{{ url('/login') }}">
                            <div class="form-group">
                                <label for="email">Email</label>
                                {{-- old('email') not working --}}
                                <input id="email" type="text" class="form-control" name="email" value="{{old('email')}}"
                                    placeholder="Enter your email">
                                <span id="email-span" style="color:red">{{ $errors->first('email') }}</span>
                            </div>
                            <div class="form-group md-2">
                                <label for="password">Password</label>
                                <input id="password" type="password" class="form-control" name="password" value=""
                                    placeholder="Enter your password">
                                <span id="email-span" style="color:red">{{ $errors->first('password') }}</span>
                            </div>
                            <a class="mt-2" href="enterEmail" style="text-decoration: none">Forget
                                Password</a>
                            <div class="form-group mt-2">
                                <button id="login" class="btn btn-block btn-primary form-control"
                                    type="submit">Login</button>
                            </div>
                            <a href="register" class="form-control btn btn-primary mt-2">New User ? Register here</a>
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
    $(document).on('click','#login',function(e){
              $.ajax({
                    url:'api/getToken',
                    data:{'email':$('#email').val(),'password':$('#password').val()},
                    type:'get',
                    success:  function (response) {
                          localStorage.setItem("jwt-token", response);
                    },
                    error: function(err){
                        console.log('err----->',err)
                    }
                    })
              })

</script>

</html>