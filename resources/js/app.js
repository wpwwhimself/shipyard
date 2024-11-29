/**
 * Ukrywanie alertów
 */
const TOAST_TIMEOUT = 4000;

const alert = document.querySelector(".alert")
if(alert) {
    //appear
    setTimeout(() => {
        alert.classList.add("in");
    }, 1);

    //allow dismissal
    alert.addEventListener("click", (ev) => ev.target.classList.remove("in"));

    //disappear
    setTimeout(() => {
        alert.classList.remove("in");
    }, TOAST_TIMEOUT);
}

/**
 * Dangerous buttons
 */
document.querySelectorAll("button.danger, .button.danger")
    .forEach(btn => {
        btn.addEventListener("click", (ev) => {
            if (!confirm("Ostrożnie! Czy na pewno chcesz to zrobić?")) {
                ev.preventDefault();
            }
        })
    })

