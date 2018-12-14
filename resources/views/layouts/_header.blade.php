<nav class="navbar navbar-default navbar-static-top">
    <div class="navbar-header">
        
        <!-- Collapsed Hamburger -->
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
            <span class="sr-only">Toggle Navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <!-- Branding Image-->
        <a class="navbar-brand" href="{{ url('/') }}">
            LaraBBS
        </a>
    </div>

    <div class="collapse navbar-collapse" id="app-navbar-collapse">
        <!-- Left Side Of Navbar -->
        <ul class="nav navbar-nav">
            <li class="{{ active_class(if_route('topics.index')) }}"><a href="{{ route('topics.index') }}">{{ $headTopicName }}</a></li>
            @foreach( $headCategories as $key => $category)
                <li class="{{ active_class((if_route('categories.show') && if_route_param('category', $category->id))) }}"><a href="{{ route('categories.show', $category->id) }}"> {{ $category->name }} </a></li>
            @endforeach
        </ul>

        <!-- Right Side Of Navbar -->
        <ul class="nav navbar-nav navbar-right">
            <!-- Authentication links-->
            @guest
                <li><a href="{{ route('login') }}">登录</a></li>            
                <li><a href="{{ route('register') }}">注册</a></li>
            @else
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <span class="user-avatar pull-left" style="margin-right:8px; margin-top:-5px;">
                            <img src="{{ !empty($user->avatar) ? $user->avatar : config('default.avatar') }}" class="img-responsive img-circle" width="30px" height="30px">
                        </span>
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">

                        <li>
                            <a href="{{ route('users.edit', Auth::id()) }}">
                                编辑资料
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                退出登陆
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>
            @endguest    
        </ul>
    </div>
</nav>