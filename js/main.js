        $(document).ready(function() {
            // إضافة تأثير التمرير السلس للروابط
            $('a[href^="#"]').on('click', function(event) {
                var target = $(this.getAttribute('href'));
                if (target.length) {
                    event.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 100
                    }, 1000);
                }
            });

            // إضافة تأثير ظهور تدريجي للعناصر عند التمرير
            $(window).scroll(function() {
                $('.feature-icon, .feature-title, .hero-title, .hero-subtitle').each(function() {
                    var bottom_of_object = $(this).offset().top + $(this).outerHeight();
                    var bottom_of_window = $(window).scrollTop() + $(window).height();
                    if (bottom_of_window > bottom_of_object) {
                        $(this).animate({'opacity':'1'},500);
                    }
                });
            });
        });