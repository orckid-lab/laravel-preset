export default function pageEvents() {
  $(function () {
    let header = $('#main-header');
    $(window).scroll(function () {
      let scroll = $(window).scrollTop();
      if (scroll >= 20) {
        header.addClass('scrolled');
      } else {
        header.removeClass('scrolled');
      }
    });
  });
}
