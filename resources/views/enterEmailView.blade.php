<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forget Password</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>

<body
    style="background-size: cover ;  background-image: url(https://cdn.pixabay.com/photo/2017/10/31/19/05/web-design-2906159__480.jpg)">

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-6" style="margin-top:120px; margin-left:330px">
                <div class="card" style="width: 30rem;">
                    <div class="card-body">
                        <h5 class="card-title">Reset your password</h5>
                        <hr>
                        <h6 id="success-msg" style="color:green"></h6>
                        <h6 class="card-subtitle mb-2 text-muted">Enter your user account's verified email address and
                            we will send you a password reset link.</h6>
                        <form>
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input id="email" type="text" class="form-control mt-2" name="email" value=""
                                    placeholder="enter your email">
                                <span id="email-span"></span>
                            </div>
                            <div class="form-group" style="margin-top:20px">
                                <button id="reset" class="btn btn-block btn-primary " type="submit">Send
                                    password Reset
                                    Email</button>
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
    $(document).on('click','#reset',function(e){
        e.preventDefault()
        $('#email-span').html("")
        $.ajax({
            url:'api/sendRestLink',
            data:{'email':$('#email').val()},
            type:'post',
            success:  function (res) {
                
                $('#success-msg').text('Check your email for password reset link')
                setInterval(() => {
                    window.location.href = "http://localhost:8000/login";    
                }, 4000);
                
            },
            error: function(err){
                console.log(err.responseJSON[0])
                if(JSON.parse(err.responseText)['email']){
                       $('#email-span').append(`<span  style="color:red">`+JSON.parse(err.responseText)['email'][0]+`</span>`)
                    }
                if(err.responseJSON[0]){
                       $('#email-span').append(`<span  style="color:red">`+err.responseJSON[0]+`</span>`)
                    }
            }
        })
        
    })
</script>

</html>