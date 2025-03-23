@include('template.header')
@include('template.aside')

    <main id="main" class="main p-4">
        <div class="pagetitle">
            <h1>@yield('page-title', 'Dashboard')</h1>
        </div>
        @yield('content')
    </main>
    </div>

@include('template.footer')

<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
