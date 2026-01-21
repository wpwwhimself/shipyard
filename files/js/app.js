//? ******************************** ?//
//? scripts ran after page is loaded ?//
//? ******************************** ?//

//#region handling toasts
const TOAST_TIMEOUT = 4000;

const toast = document.querySelector("#toast")
if(toast) {
    //appear
    setTimeout(() => {
        toast.classList.add("visible");
    }, 1);

    //allow dismissal
    toast.addEventListener("click", (ev) => ev.target.classList.remove("visible"));

    //disappear
    setTimeout(() => {
        toast.classList.remove("visible");
    }, TOAST_TIMEOUT);
}
//#endregion

//#region dangerous buttons
document.querySelectorAll("button.danger, .button.danger")
    .forEach(btn => {
        btn.addEventListener("click", (ev) => {
            if (!confirm("Ostrożnie! Czy na pewno chcesz to zrobić?")) {
                ev.preventDefault();
            }
        })
    })
//#endregion

// #region initialize
reinitSelect();
reinitTableSort();
// #endregion
