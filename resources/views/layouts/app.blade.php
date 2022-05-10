<!DOCTYPE html>
<html lang="en">

<head>

    @include('components.head', ['title' => $title])

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        @include('components.sidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                @include('components.topbar')
                
                @yield('content')

                @include('components.footer')
            </div>
        </div>
    </div>

    @include('components.scrollToTop')
    @include('components.logoutModal')

    @include('components.scripts')
    @include('components.chartJS')
</body>
</html>
