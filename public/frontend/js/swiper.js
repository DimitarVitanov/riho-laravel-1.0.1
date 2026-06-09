(function () {
  const layout_slider = new Swiper(".layout-slider", {
    loop: true,
    spaceBetween: 15,
    pagination: {
      el: ".swiper-pagination",
      dynamicBullets: true,
    },
    autoplay: {
      delay: 3000,
    },
    autoHeight: true,
    centeredSlides: true,
    navigation: {
      prevEl: ".slidePrev-btn",
      nextEl: ".slideNext-btn",
    },
    breakpoints: {
      676: {
        slidesPerView: 2,
      },
      979: {
        slidesPerView: 3,
      },
    },
  });
})();
