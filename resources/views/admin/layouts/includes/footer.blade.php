<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                {{ date('Y') }} &copy; <a href="{{ URL::to('https://softgiantbd.com/') }}" target="_blank">Soft Giant BD</a>. All Rights Reserved.
            </div>
            <div class="col-md-6">
                <div class="text-md-end footer-links d-none d-md-block">
                    <a href="{{ URL::to('https://softgiantbd.com/about') }}" target="_blank">About</a>
                    {{-- <a href="javascript: void(0);">Support</a> --}}
                    <a href="{{ URL::to('https://softgiantbd.com/contact') }}" target="_blank">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</footer>
