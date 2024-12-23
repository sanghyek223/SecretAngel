<!DOCTYPE html>
<html lang="en">

@include('layouts.components.head')

<body>

@include('layouts.include.navigation')

@include('layouts.include.header')

<!-- Main Content-->
<div class="container px-4 px-lg-5">
    <div class="row gx-4 gx-lg-5 justify-content-center">
        @yield('contents')
    </div>
</div>

@include('layouts.include.footer')

@yield('addScript')

</body>
</html>
