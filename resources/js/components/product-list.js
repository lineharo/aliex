document.addEventListener("DOMContentLoaded", () => {
    const selectors = [
        ".product-list .product-list__image-slider",
        ".product-h .product-h__image-slider"
    ];

    const photoContainers = document.querySelectorAll(selectors.join(", "));

    photoContainers.forEach((el) => {
        const photos = el.querySelectorAll(
            ".product-list__image-slide, .product-h__image-slide"
        );

        if (photos.length === 0) return;

        let animationFrame;

        el.addEventListener("mousemove", (e) => {
            if (animationFrame) cancelAnimationFrame(animationFrame);

            animationFrame = requestAnimationFrame(() => {
                const containerRect = el.getBoundingClientRect();
                const containerWidth = containerRect.width;
                const mouseX = e.clientX - containerRect.left;

                photos.forEach((photo, index) => {
                    const segmentStart = (containerWidth / photos.length) * index;
                    const segmentEnd = (containerWidth / photos.length) * (index + 1);

                    const isVisible = mouseX >= segmentStart && mouseX < segmentEnd;
                    photo.style.opacity = isVisible ? "1" : "0";
                    photo.style.pointerEvents = isVisible ? "auto" : "none";
                });
            });
        });
    });
});
