/**
 * Card enhancement to trigger the main link whenever the card area is clicked
 * See https://css-tricks.com/block-links-the-search-for-a-perfect-solution/
 */

let cardEnhancement = function () {
    document.addEventListener('click', function (ev) {
        const card = ev.target.closest('[data-component="card"]');
        console.log('card');
        console.log(card);
        if (!card || ev.redispatched) return;
        if (ev.target.closest('[data-click]')) return;

        const mainLink = card.querySelector('.card__link');
        console.log('mainLink');
        console.log(mainLink);
        if (ev.target === mainLink) return;

        const noTextSelected = !window.getSelection().toString();
        if (noTextSelected) {
            const ev2 = new MouseEvent("click", ev);
            ev2.redispatched = true;
            mainLink.dispatchEvent(ev2);
        }
    });
};

document.addEventListener('turbo:load', cardEnhancement);