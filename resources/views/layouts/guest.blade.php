<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="TAOEX Club is the online registry for club and tournament play. Register your own club to play in tournament game for TAOEX - the game that plays you.">
        <meta name="author" content="Les Romhanyi">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>TAOEX &#153; Club - Register Tournament & Club Play Online </title>
        <!-- Bootstrap core CSS-->
        <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <!-- Custom fonts for this template-->
        <link href="{{ asset('vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
        <!-- Page level plugin CSS-->
        <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet">
        <!-- Custom styles for this template-->
        <link href="{{ asset('css/sb-admin.css') }}" rel="stylesheet">
        <link href="{{ asset('css/my-style.css') }}" rel="stylesheet">
        <link href="{{ asset ('css/style.css') }}" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" />

        <!-- Cookie Consent PopUp Code (Aska)-->
        <script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
        <script>
        window.addEventListener("load", function(){
        window.cookieconsent.initialise({
        "palette": {
            "popup": {
            "background": "#000"
            },
            "button": {
            "background": "#f1d600"
            }
        },
        "content": {
            "message": "This website uses cookies to improve your experience. We'll assume you're okay with this, but you can opt-out if you wish.",
            "dismiss": "Accept",
            "link": "Read More",
            "href": "https://localhost/site/policy"
        }
        })});
        </script>
        <!-- end of Cookie Consent Code (Aska) -->
        <style>
          /* Chat containers */
.container1 {
    border: 2px solid #dedede;
    background-color: #f1f1f1;
    border-radius: 5px;
    padding: 10px;
    margin: 10px 0;
}

/* Darker chat container */
.darker {
    border-color: #ccc;
    background-color: #ddd;
}

/* Clear floats */
.container1::after {
    content: "";
    clear: both;
    display: table;
}

/* Style images */
.container1 img {
    float: left;
    max-width: 100px;
    width: 100%;
    margin-right: 20px;
    border-radius: 50%;
}

/* Style the right image */
.container1 img.right {
    float: right;
    margin-left: 20px;
    margin-right:0;
}

/* Style time text */
.time-right {
    float: right;
    color: #aaa;
}

/* Style time text */
.time-left {
    float: left;
    color: #999;
}

body {
  min-height: 75rem;
  padding-top: 4rem;
}

</style>
    </head>

    <nav class="navbar navbar-expand-md navbar-dark fixed-top" style="background-color:#231b2d">
        <a class="navbar-brand" href="/"><img src="https://taoex.org/wp-content/uploads/2017/12/taoex-logo-white.png" height="36" width="141">Club</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-md-center" id="navbarCollapse">
          <ul class="navbar-nav  ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">Login</a>
            </li>
          </ul>
        </div>
      </nav>
  
    
    <body style="background:#3e3647">
    
        <!-- where content loads -->
        
        @yield('content')
        
        <!-- Footer -->
        <footer class="page-footer white fixed-bottom" style="background-color:#0f1319; color:white; height:50px; vertical-align: center">
        <!-- Copyright -->
        <div class="container">
                <div class="text-center">
                    <small>Copyright © Taoex <?php echo date ("Y");?></small>
                    <span class="text-muted"><small><a href="/home/policy">     Privacy Policy</a></small></span>
                </div>
            </div>      
        </footer>
      
        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fa fa-angle-up"></i>
        </a>
       
        <!-- Bootstrap core JavaScript-->
        <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/popper/popper.min.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
        <!-- Core plugin JavaScript-->
        <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
        <!-- Page level plugin JavaScript-->
        <script src="{{ asset('vendor/datatables/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.js') }}"></script>
        <!-- Custom scripts for all pages-->
        <script src="{{ asset('js/sb-admin.min.js') }}"></script>
        <!-- Custom scripts for this page-->
        <script src="{{ asset('js/sb-admin-datatables.min.js') }}"></script>
        <!--<script src="{{ asset('js/calendar.js') }}"></script>-->
    </div>
</body>
</html>