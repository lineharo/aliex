document.addEventListener("DOMContentLoaded", function() {
    let awaySeconds = 5;
    const anchor = document.querySelector('.away__link');
    const noteElement = document.querySelector('.away__note');

    function getSecondWordForm(n) {
        const lastDigit = n % 10;
        const lastTwoDigits = n % 100;

        if (lastTwoDigits >= 11 && lastTwoDigits <= 14) return 'секунд';
        if (lastDigit === 1) return 'секунду';
        if (lastDigit >= 2 && lastDigit <= 4) return 'секунды';
        return 'секунд';
    }

    if (anchor && noteElement) {
        const externalLink = anchor.href;

        const awayTimer = setInterval(function () {
            const word = getSecondWordForm(awaySeconds);
            noteElement.textContent = 'или это произойдёт автоматически через ' + awaySeconds + '\u00A0' + word;
            awaySeconds--;

            if (awaySeconds < 0) {

                if (typeof ym === 'function') {
                    ym(95477317, 'reachGoal', 'away_to_ali');
                }

                clearInterval(awayTimer);
                window.location = externalLink;
            }
        }, 1000);

        anchor.addEventListener('click', function () {
            clearInterval(awayTimer);

            if (typeof ym === 'function') {
                ym(95477317, 'reachGoal', 'away_to_ali');
            }

            window.location = externalLink;
        });
    }
});
