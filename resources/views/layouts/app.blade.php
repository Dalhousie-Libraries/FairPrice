<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name = "format-detection" content = "telephone=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body style="overflow-x: hidden">
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top" style='position:fixed;top:0;width:100%'>

            <div class="container" style="width: 100%">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">

                    <!-- Left Side Of Navbar -->
                <a class="navbar-brand" href="/" style="padding-top: 0px; padding-bottom: 0px;">
                    <img src='{{asset("images/dal_lib_logo_white.png")}}' height="51" style="padding-top: 0px;padding-bottom: 0px;">
                </a>

                    <ul class="nav navbar-nav">
                        <li>
                            <a href="{{route('home')}}">Home</a>
                        </li>
                        <li>
                            <a href="{{ route('journals') }}">
                                View All Journals
                            </a>
                        </li>
                       
                        @if(Auth::user())
                        <?php $election = App\Election::whereDate('end_date', '>=', Carbon\Carbon::now()->toDateString())->orWhere('end_date', null)->orderBy('start_date', 'desc')->first();
                                if($election) {
                                    $audit = App\ElectionAudit::where('election_id', $election->id)->where('banner_id', Auth::user()->email)->first();
                                } else {
                                    $audit = null;
                                }
                                if($election) {
                                $vote = App\Vote::where("election_id", $election->id)->where("user_id", auth()->user()->id);
                        }?>
                            @if($election)
                                @if(!$audit)
                                    @if($vote->exists())
                                    <li>
                                        <a href="{{route('vote', ['election' => $election->id])}}">
                                            Review my Recommendations
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('vote', ['election' => $election->id, 'finalize' => 'true'])}}">
                                            Finalize and Submit
                                        </a>
                                    </li>
                                    @endif
                                @endif
                            @endif
                        @endif
                        
                        @if(Auth::user() && Auth::user()->isAdmin)
                                    <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        Admin
                                    </a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="{{ route('report') }}">Run Report</a></li>
<!--                                        <li><a href="{{ route('import') }}">(EXPERIMENTAL) Import</a></li> -->
                                    </ul>
                                </li>
                        @endif  
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
							<li><a href="{{ route('help') }}">Help</a></li>
                            <li><a href="{{ route('login') }}">Login</a></li>
                        @else
                            <li><a href="{{ route('help') }}">Help</a></li>  
							<li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">Logout</a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <div class='row' style='margin-top:60px;'>
<!--            <div class='col-xs-12 col-md-4' style='text-align:center'>
                    <img src='{{asset("images/dal_logo.png")}}' style='height:4em'>
            </div> -->
            <div class='col-xs-12 col-md-12' style="text-align:center;">
                
                    @if(Agent::isDesktop())
                        <div style='padding-right:10px;'>
                            <h2>
                                {{ config('app.name', 'Laravel') }} 
                                @if(Auth::user())
                                    @if(Auth::user()->isLibrarian) 
                                        - Librarian
                                    @endif
                                    @if(Auth::user()->isResourceTeam) 
                                        - Resource Team
                                    @endif
                                    @if(Auth::user()->isAdmin) 
                                        - Admin
                                    @endif
                                @endif
                            </h2>
                        </div>
                    @else
                        <h3>
                            {{ config('app.name', 'Laravel') }} 
                            @if(Auth::user())
                                @if(Auth::user()->isLibrarian) 
                                    - Librarian
                                @endif
                                @if(Auth::user()->isResourceTeam) 
                                    - Resource Team
                                @endif
                                @if(Auth::user()->isAdmin) 
                                    - Admin
                                @endif
                            @endif
                        </h3>
                    @endif
            </div>
        </div>
        <div class='row'>
            <div class='col-md-4 col-md-offset-4'>
                <sessioncountdown expiry='{{(Carbon\Carbon::parse("now")->addMinutes(Config::get("session.lifetime")-1)->timestamp) * 1000}}'></sessioncountdown>
            </div>
        </div>
        @yield('content')
    </div>
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip(); 
        });
    </script>

</body>
</html>
