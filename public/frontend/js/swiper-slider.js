(function () {
    const support_slider = new Swiper(".support-slider", {
      loop: true,
      spaceBetween: 5,
      pagination: {
        el: ".swiper-pagination",
      },
      autoplay: {
        delay: 2000,
      },
      autoHeight: true,
      navigation: {
        prevEl: ".slidePrev-btn",
        nextEl: ".slideNext-btn",
      },
      breakpoints: {
        576: {
          slidesPerView: 2,
        },
        992: {
          slidesPerView: 3,
        },
        1200: {
          slidesPerView: 4,
        },
      },
    });

    const layout_slider = new Swiper(".layout-slider", {
      loop: true,
      spaceBetween: 15,
      pagination: {
        el: ".swiper-pagination",
      },
      autoplay: {
        delay: 3000,
      },
      autoHeight: true,
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
        1282: {
          slidesPerView: 4,
        },
      },
    });
  })();
  