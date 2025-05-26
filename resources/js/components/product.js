document.addEventListener("DOMContentLoaded", function(event) {
    const photoContainer = document.querySelector(".product .product__gallery");

    if (photoContainer !== null) {
        const photoSlider = photoContainer.querySelector('.product__slider');
        const photos = Array.from(photoContainer.querySelectorAll(".product__slide"));

        const sliderControls = document.querySelector(".product__gallery-controls");
        const leftArrow = document.querySelector(".product__gallery-control_left");
        const rightArrow = document.querySelector(".product__gallery-control_right");

        function showPhoto(index) {
            photoSlider.style.transform = `translateX(-${index * 400}px)`;
            if (currentIndex <= 0) autoPlayDir = 1;
            if (currentIndex >= photos.length - 1) autoPlayDir = -1;
        }


        function slideLeft() {
            if (currentIndex > 0) {
                currentIndex--;
                showPhoto(currentIndex);
            }
        }

        function slideRight() {
            if (currentIndex < photos.length - 1) {
                currentIndex++;
                showPhoto(currentIndex);
            }
        }

        let autoPlayTimer;
        function startAutolay() {
            autoPlayTimer = setInterval(() => {
                currentIndex += autoPlayDir;
                showPhoto(currentIndex);
            }, 2500);
        }

        photoSlider.style.width = photoSlider.offsetWidth * photos.length + 'px';
        sliderControls.style.display = 'flex';
        sliderControls.addEventListener('mouseenter', function() {
            clearInterval(autoPlayTimer);
        });
        sliderControls.addEventListener('mouseleave', function() {
            startAutolay();
        });
        photos.forEach(el => {
            el.style.display = 'block';
        });

        let currentIndex = 0;

        let autoPlayDir = 1;

        leftArrow.addEventListener("click", slideLeft);
        rightArrow.addEventListener("click", slideRight);

        showPhoto(currentIndex);
        startAutolay();
    }
});