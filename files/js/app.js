//? ******************************** ?//
//? scripts ran after page is loaded ?//
//? ******************************** ?//

//#region handling toasts
const TOAST_TIMEOUT = 4000;

const toast = document.querySelector(".toast")
if(toast) {
    //appear
    setTimeout(() => {
        toast.classList.add("in");
    }, 1);

    //allow dismissal
    toast.addEventListener("click", (ev) => ev.target.classList.remove("in"));

    //disappear
    setTimeout(() => {
        toast.classList.remove("in");
    }, TOAST_TIMEOUT);
}
//#endregion

//#region dangerous buttons
document.querySelectorAll("button.danger, .button.danger")
    .forEach(btn => {
        btn.addEventListener("click", (ev) => {
            if (!confirm("Ostrożnie! Czy na pewno chcesz to zrobić?")) {
                ev.preventDefault();
            }
        })
    })
//#endregion

//#region compiling sass
const styleElement = document.getElementById("shipyard-styles");
Sass.compile(styleElement.innerHTML, (result) => {
    if (result.status == 1) {
        console.error(result);
        return;
    }

    styleElement.innerHTML = result.text;
    styleElement.type = "text/css";
});
//#endregion
