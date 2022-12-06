<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forget Password</title>
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
            <div class="col-md-6 col-md-offset-6" style="margin-top:120px; margin-left:330px">
                <div class="card" style="width: 30rem;">
                    <div class="card-body">
                        <h5 class="card-title">Reset your password</h5>
                        <h5 id="success-msg-password" style="color:green"></h5>
                        <hr>
                        <form>
                            <div class="form-group mt-3">
                                <label for="password">Enter New Paasword *<small style="font-size: 9px">Should be
                                        atleast 6 characters long</small></label>
                                <input id="password" type="password" class="form-control mt-2" name="email" value=""
                                    placeholder="Enter password">
                                <span id="password-span"></span>
                            </div>
                            <div class="form-group mt-3">
                                <label for="confirm-password">Confirm Password *</label>
                                <input id="cnf-password" type="password" class="form-control mt-2" name="email" value=""
                                    placeholder="Enter confirm password">
                                <span id="cnf-password-span"></span>
                            </div>
                            <div class="form-group" style="margin-top:20px">
                                <button id="reset-password" class="btn btn-primary form-control" type="submit">Change
                                    Password</button>
                            </div>
                        </form>

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
    $(document).on('click','#reset-password',function(e){
        e.preventDefault()
        $('#password-span').html('')
        $('#cnf-password-span').html('')
        
        var token = window.location.pathname.split('/')[2]
        $.ajax({
            url:'/api/changePassword',
            data:{'token':token,'password':$('#password').val(),'cnf-password':$('#cnf-password').val()},
            type:'post',
            success:  function (res) {
                $('#success-msg-password').text('your password is successfully changed')
                setInterval(() => {
                    window.location.href = "http://localhost:8000/login"; 
                }, 4000);
            },
            error: function(err){
                if(JSON.parse(err.responseText)['password']){
                       $('#password-span').append(`<span  style="color:red">`+JSON.parse(err.responseText)['password'][0]+`</span>`)
                    }
                if(JSON.parse(err.responseText)['cnf-password']){
                       $('#cnf-password-span').append(`<span  style="color:red">`+JSON.parse(err.responseText)['cnf-password'][0]+`</span>`)
                    }
            }
        })
    })
</script>

</html>