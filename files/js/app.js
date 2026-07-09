//? ******************************** ?//
//? scripts ran after page is loaded ?//
//? ******************************** ?//

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

// #region apply visual sortables
document.querySelectorAll("th.sortable").forEach(th => {
    th.innerHTML +=
        `<span role="sortable-hint"
            data-tippy="Sortuj"
            data-tippy-arrow="true"
            data-tippy-theme="light"
            data-tippy-placement="top-end"
        >
            ↕️
        </span>`;
});
// #endregion

// #region apply codeblocks copy
document.querySelectorAll(`[role="doc-contents"] pre`).forEach(code => {
    code.addEventListener("click", () => {
        navigator.clipboard.writeText(code.innerText);
        alert("Skopiowano do schowka.");
    });
});
// #endregion

// #region initialize
reinitSelect();
reinitTableSort();
// #endregion
