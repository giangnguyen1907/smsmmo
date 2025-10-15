
<script src="{{ asset('themes/frontend/js/vendor/jquery-library.js') }}"></script>
<script src="{{ asset('themes/frontend/js/vendor/bootstrap.min.js') }}"></script>
<script src="https://maps.google.com/maps/api/js?key=AIzaSyCR-KEWAVCn52mSdeVeTqZjtqbmVJyfSus&amp;language=en"></script>
<script src="{{ asset('themes/frontend/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('themes/frontend/js/jquery.vide.min.js') }}"></script>
<script src="{{ asset('themes/frontend/js/countdown.js') }}"></script>
<script src="{{ asset('themes/frontend/js/jquery-ui.js') }}"></script>
<script src="{{ asset('themes/frontend/js/parallax.js') }}"></script>
<script src="{{ asset('themes/frontend/js/countTo.js') }}"></script>
<script src="{{ asset('themes/frontend/js/appear.js') }}"></script>
<script src="{{ asset('themes/frontend/js/gmap3.js') }}"></script>
<script src="{{ asset('themes/frontend/js/main.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.6.1/toastify.min.js"></script>

<script src="{{ asset('/themes/frontend/select2/select2.full.min.js') }}"></script>
<script>
	$(".select2").select2();
</script>

{{--
<script src="{{ asset('themes/frontend/js/customer.js') }}"></script>
--}}
@isset($web_information->source_code->javascript)
  <script>
    {!! $web_information->source_code->javascript !!}
  </script>
@endisset
