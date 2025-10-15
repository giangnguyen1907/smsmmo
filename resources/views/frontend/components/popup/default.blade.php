@isset($popup)
  <div class="modal fade popup" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">
            {{ $popup->title ?? '' }}
          </h4>
          <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
          @isset($popup->image)
            <p class="text-center"><img src="{{ $popup->image }}" alt="" class="w-100"></p>
          @endisset
          @isset($popup->content)
            {!! $popup->content !!}
          @endisset
        </div>
      </div>
    </div>
  </div>

  <script>
    $(function() {
      $(".popup").modal('toggle');
      var counter = {{ $popup->duration ?? 999 }};
      var interval = setInterval(function() {
        counter--;
        if (counter == 0) {
          $('.popup').modal('hide');
          clearInterval(interval);
        }
      }, 1000);
    });
  </script>
@endisset
