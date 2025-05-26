document.addEventListener("DOMContentLoaded", function(event) {
    const buttons = document.querySelectorAll("button.promocode__open-code");

    buttons.forEach(function(el, index) {
        el.addEventListener('click', (e) => {
            let eCode = el;
            do {
                eCode = eCode.nextElementSibling;
            } while (eCode != null && eCode.className != 'promocodes__code');

            if (eCode != null) {
                el.style.display = 'none';
                eCode.style.display = 'block';
            }
        });
    });


    const copyButtons = document.querySelectorAll(".promocodes__code-value");

    copyButtons.forEach(function(el, index) {
        console.log(el);
        el.addEventListener('click', (e) => {
            navigator.clipboard.writeText(el.textContent.trim());
        });
    });
});