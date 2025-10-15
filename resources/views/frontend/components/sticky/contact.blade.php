<style>
  /* Css All */
  .context-dark::before {
    content: "";
    position: absolute;
    background: #2A93C9;
    left: 0;
    right: 0;
    height: 100%;
    width: 100%;
    top: 0;
    bottom: 0;
    opacity: 0.54;
  }

  /* CSS for button contact */
  .bonus {
    position: fixed;
    top: 50%;
    z-index: 999;
    left: 10px;
  }

  .bonus .hotline {
    margin-bottom: 5px;
  }

  .bonus .hotline a {
    background: url("{{ asset('images/icon_phone.svg') }}") no-repeat;
    height: 40px;
    background-position: center;
    background-size: cover;
    width: 40px;
    display: block;
    margin: auto;
  }

  .bonus .zalo a {
    background: url("{{ asset('images/icon_zalo.svg') }}") no-repeat;
    background-position: center;
    background-size: cover;
  }

  .bonus .messenger a {
    background: url("{{ asset('images/icon_messenger.svg') }}") no-repeat;
    background-position: center;
    background-size: cover;
  }
</style>
<div class="bonus" aria-hidden="false">
  @if (isset($web_information->social->call_now) && $web_information->social->call_now != '')
    <div class="hotline phone">
      <a href="tel:{{ $web_information->social->call_now }}" class="hotline_qc">&nbsp;</a>
    </div>
  @endif
  @if (isset($web_information->social->zalo) && $web_information->social->zalo != '')
    <div class="hotline zalo">
      <a target="_blank" href="{{ $web_information->social->zalo }}" class="hotline_qc">&nbsp;</a>
    </div>
  @endif
  @if (isset($web_information->social->messenger) && $web_information->social->messenger != '')
    <div class="hotline messenger">
      <a target="_blank" href="{{ $web_information->social->messenger }}" class="hotline_qc">&nbsp;</a>
    </div>
  @endif
</div>
<style>
  /* Button used to open the contact form - fixed at the bottom of the page */
  .open-button {
    background-color: #2A93C9;
    color: #FFFFFF;
    padding: 8px 15px;
    border-radius: 5px 5px 0px 0px;
    cursor: pointer;
    position: fixed;
    bottom: 0px;
    left: 10px;
    /* width: 200px; */
    z-index: 2;
    font-size: 14px;
    text-transform: uppercase;
  }

  /* The popup form - hidden by default */
  .form-popup {
    display: none;
    position: fixed;
    bottom: 0;
    left: 10px;
    border: 2px solid #f1f1f1;
    border-radius: 5px 5px 0px 0px;
    z-index: 999;
    padding: 10px;
    background-color: #2A93C9;
  }

  /* Add styles to the form container */
  .form-container {
    max-width: 300px;
    padding: 10px;
    background-color: white;
  }

  /* Full-width input fields */
  .form-container input,
  .form-container textarea {
    width: 100%;
    padding: 10px;
    margin: 5px 0 10px 0;
    border: 1px solid #666666;
    background: #f1f1f1;
  }

  /* When the inputs get focus, do something */
  .form-container input:focus,
  .form-container textarea:focus {
    background-color: #ddd;
    outline: none;
  }

  /* Add some hover effects to buttons */
  .form-container .btn:hover,
  .open-button:hover {
    opacity: 0.8;
  }

  .form-container .title {
    margin: 10px 0px 20px;
    text-transform: uppercase;
  }

  .form-container .title button {
    padding: 5px 10px 8px 10px;
    line-height: 1;
  }
</style>
<button class="open-button" onclick="openForm()">@lang('Form footer name')</button>
<div class="form-popup" id="myForm">
  <form action="{{ route('frontend.contact.store') }}" method="post" class="form-container form_ajax mb-0">
    @csrf
    <div class="modal-header px-0">
      <h4 class="modal-title text-uppercase">
        @lang('Form footer name')
      </h4>
      <button type="button" class="btn-close btn-sm" onclick="closeForm()"></button>
    </div>
    <input type="text" placeholder="* Tên" name="name" autocomplete="off" required>
    <input type="text" placeholder="* Điện thoại" name="phone" autocomplete="off" required>
    <textarea rows="5" type="text" placeholder="Nội dung lời nhắn" name="content" autocomplete="off"></textarea>
    <input type="hidden" name="is_type" value="call_request">
    <button type="submit" class="btn btn-sm btn-primary mb-1">@lang('Submit')</button>
    <div class="result_message text-bold"></div>
    <div>@lang('Commitment to information security')</div>
  </form>
</div>
<script>
  function openForm() {
    document.getElementById("myForm").style.display = "block";
  }

  function closeForm() {
    document.getElementById("myForm").style.display = "none";
  }
</script>