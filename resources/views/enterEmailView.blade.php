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
                                <button id="reset" class="btn form-control btn-primary " type="submit">Send
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
                       $('#email-span').append(`<small  style="color:red">`+JSON.parse(err.responseText)['email'][0]+`</small>`)
                    }
                if(err.responseJSON[0]){
                       $('#email-span').append(`<small  style="color:red">`+err.responseJSON[0]+`</small>`)
                    }
            }
        })
        
    })
</script>

</html>