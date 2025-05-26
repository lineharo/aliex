document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".tabs-container").forEach((container) => {
        const buttons = container.querySelectorAll(".tab-button");
        const panels = container.querySelectorAll(".tab-panel");

        if (buttons.length > 0 && panels.length > 0) {
            // Активируем первую вкладку по умолчанию
            buttons[0].classList.add("tab-button_active");
            panels[0].classList.add("tab-panel_active");
            panels[0].style.display = "block";
        }

        buttons.forEach((button, index) => {
            button.addEventListener("click", () => {
                // Убираем активные классы и скрываем вкладки
                buttons.forEach((btn) => btn.classList.remove("tab-button_active"));
                panels.forEach((panel) => {
                    panel.classList.remove("tab-panel_active");
                    panel.style.display = "none";
                });

                // Активируем выбранную вкладку
                button.classList.add("tab-button_active");
                panels[index].classList.add("tab-panel_active");
                panels[index].style.display = "block";
            });
        });
    });
});
