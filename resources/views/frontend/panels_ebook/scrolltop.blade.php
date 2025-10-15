<div class="scrollToTop">
  <span class="arrow" style="cursor: pointer;">&uarr;</span>
</div>
<script type="text/javascript">
    const scrollToTop = document.querySelector(".scrollToTop");
    const $rootElement = document.documentElement;
    const $body = document.body;

    window.onscroll = () => {
      if (window.scrollY > 500) {
        scrollToTop.style.display = "flex";
      } else {
        scrollToTop.style.display = "none";
      }
    };

    scrollToTop.onclick = () => {
      // $rootElement.scrollTop = $body.scrollTop = 0;
      window.scrollTo({top: 0, behavior: 'smooth'});
    };
</script>