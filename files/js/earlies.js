//? ********************************* ?//
//? scripts ran before page is loaded ?//
//? ********************************* ?//

/**
 * @param {String} HTML representing a single element.
 * @param {Boolean} flag representing whether or not to trim input whitespace, defaults to true.
 * @return {Element | HTMLCollection | null}
 */
function fromHTML(html, trim = true) {
    // Process the HTML string.
    html = trim ? html.trim() : html;
    if (!html) return null;

    // Then set up a new template element.
    const template = document.createElement('template');
    template.innerHTML = html;
    const result = template.content.children;

    // Then return either an HTMLElement or HTMLCollection,
    // based on whether the input HTML had one or more roots.
    if (result.length === 1) return result[0];
    return result;
}

// #region theme management
function toggleTheme() {
    const bodyClass = document.body.classList
    bodyClass.toggle("dark")
    localStorage.setItem("theme", bodyClass.contains("dark") ? "dark" : "light")
}
// #endregion

// #region file storage functions
function copyToClipboard(text) {
    navigator.clipboard.writeText(text)
    alert("Skopiowano do schowka.")
}

function browseFiles(url) {
    window.open(url, "_blank")
}

function selectFile(url, input_id) {
    if (window.opener) {
        window.opener.document.getElementById(input_id).value = url
        window.close()
    }
}

function initFileReplace(file_name) {
    // just trigger file upload and mention to keep the same name
    const fileInput = document.querySelector("input#files")
    const fileNameInput = document.querySelector("input[name='force_file_name']")

    fileInput.addEventListener("change", () => {
        if (!fileInput.files.length) {
            fileNameInput.value = undefined
            return
        }

        fileNameInput.value = file_name
        fileInput.form.submit()
    })

    fileInput.click()
}
// #endregion

// #region inputs
function JSONInputUpdate(input_name) {
    const input = document.querySelector(`input[name="${input_name}"]`)
    const table = document.querySelector(`table[data-name="${input_name}"]`)
    const cols = parseInt(table.getAttribute("data-columns"))
    let newValue = cols == 2 ? {} : []

    table.querySelectorAll("tbody tr").forEach((row, row_no) => {
        row.querySelectorAll("input").forEach(input => input.value = input.value?.trim())
        switch (cols) {
            case 2:
                const inputs = row.querySelectorAll("input")
                newValue[inputs[0].value] = (inputs[1].type == "checkbox" ? inputs[1].checked : inputs[1].value)
                break

            case 1:
                newValue[row_no] = (row.querySelector("input").type == "checkbox" ? row.querySelector("input").checked : row.querySelector("input").value)
                break

            default:
                newValue[row_no] = Array.from(row.querySelectorAll("input")).map(input => (input.type == "checkbox" ? input.checked : input.value) || null)
        }
    })

    input.value = JSON.stringify(newValue);
    reapplyPopper();
}

function JSONInputAddRow(input_name) {
    const table = document.querySelector(`table[data-name="${input_name}"]`)
    const newRow = table.querySelector(`tr[role="new-row"]`)

    if (Array.from(newRow.querySelectorAll("input")).map(input => !input.value).some(Boolean)) return

    newRow.querySelector("input").value.split(/,\s*/).forEach((v, i) => {
        let clonedRow = newRow.cloneNode(true)
        clonedRow.removeAttribute("role")
        clonedRow.querySelector("td:last-child .button:first-child").remove()
        clonedRow.querySelectorAll("td:last-child .button.hidden").forEach(b => b.classList.remove("hidden"))
        clonedRow.querySelector("input").value = v
        table.querySelector("tbody").appendChild(clonedRow)
    })

    newRow.querySelectorAll("input").forEach(input => input.value = (input.type == "checkbox" ? "1" : ""))
    JSONInputUpdate(input_name)
}

function JSONInputPrependRow(input_name, btn) {
    const table = document.querySelector(`table[data-name="${input_name}"]`);
    const newRow = table.querySelector(`tr[role="new-row"]`)

    newRow.querySelector("input").value.split(/,\s*/).forEach((v, i) => {
        let clonedRow = newRow.cloneNode(true);
        clonedRow.removeAttribute("role");
        clonedRow.querySelector("td:last-child .button:first-child").remove();
        clonedRow.querySelectorAll("td:last-child .button.hidden").forEach(b => b.classList.remove("hidden"));
        clonedRow.querySelector("input").value = v;
        table.querySelector("tbody").insertBefore(clonedRow, btn.closest("tr"));
    });
}

function JSONInputDeleteRow(input_name, btn) {
    btn.closest("tr").remove()
    JSONInputUpdate(input_name)
}

function JSONInputAutofill(input_name, ev, filled_value = null) {
    const input = ev.target.closest("td").querySelector(`input`)
    const hintBox = ev.target.closest("td").querySelector(`[role="autofill-hint"]`)
    let hints = ""

    if (filled_value) {
        input.value = filled_value
        JSONInputUpdate(input_name)
    }
    if (!hintBox) return

    if (ev.target.value && !filled_value) {
        hints = window.autofill[input_name]
            .filter(v => v.toLowerCase().startsWith(ev.target.value.toLowerCase()))
            .map(v => `<span class="interactive"
                    onclick="JSONInputAutofill('${input_name}', event, '${v}')"
                >
                    ${v}?
                </span>`)
            .join("")
    }

    hintBox.innerHTML = hints
}

function JSONInputWatchForConfirm(input_name, ev) {
    if (ev.key == "Enter" || ev.key == ",") {
        ev.stopPropagation()
        ev.preventDefault()
        JSONInputAutofill(input_name, ev, ev.target.value)
        JSONInputAddRow(input_name, ev.target.closest("tr").querySelector(".button"))
    }
}

function initSelect(name) {
    const input = document.querySelector(`[name='${name}']`);
    const dropdown = new Choices(input, {
        itemSelectText: null,
        noResultsText: "Brak wyników",
        shouldSort: false,
        removeItemButton: true,
        searchFields: ["label"],
        searchResultLimit: -1,
        fuseOptions: {
            ignoreLocation: true,
            treshold: 0,
        },
    });
}

function reinitSelect() {
    document.querySelectorAll(`.input-container select`).forEach(input => {
        if (input.classList.contains("choices__input")) {
            return;
        }
        initSelect(input.name);
    });
}

function reinitHTML() {
    window.CKEditorInit();
}

let debounce_timer;

function getIconPreview(input_name) {
    const input = document.querySelector(`input[name="${input_name}"]`)
    const icon = input.nextElementSibling.querySelector(`.icon`);

    clearTimeout(debounce_timer);
    debounce_timer = setTimeout(() => {
        icon.classList.add("ghost");

        fetch(`/front/icon/${input.value}`)
            .then(res => res.text())
            .then(html => icon.replaceWith(fromHTML(html)))
            .catch(err => console.error(err));
    }, 0.3e3);
}

function lookup(lookupUrl, input_name, query = "", other_params = {}) {
    const loader = document.querySelector(`#lookup-container[for="${input_name}"] .loader`);
    const results = document.querySelector(`#lookup-container[for="${input_name}"] [role="results"]`);

    clearTimeout(debounce_timer);
    debounce_timer = setTimeout(() => {
        loader.classList.remove("hidden");

        // actual query
        fetch(lookupUrl + "?" + new URLSearchParams({
            query: query,
            ...other_params,
        }))
            .then(res => {
                if (!res.ok) throw new Error(res.statusText);
                return res.text();
            })
            .then(html => {
                results.innerHTML = html;
            })
            .catch(err => console.error(err))
            .finally(() => {
                loader.classList.add("hidden");
                reapplyPopper();
                reinitSelect();
            });
    }, 0.3e3);
}

function lookupSelect(fieldName, value) {
    document.querySelector(`input[name="${fieldName}"][value="${value}"]`).checked = true;
}

function abcPreview(field_name, transposer = 0) {
    window.ABCJS.renderAbc(
        `abc-preview-sheet-${field_name}`,
        document.querySelector(`[name="${field_name}"]`).value,
        {
            responsive: "resize",
            add_classes: true,
            jazzchords: true,
            germanAlphabet: true,
            initialClef: true,
            visualTranspose: transposer,
        }
    );
}

function abcTransposer(field_name, mode = undefined) {
    const transposerMainBtn = document.querySelector(`.abc-preview[for="${field_name}"] .transposer-main-btn`)
    if (mode === undefined) {
        transposerMainBtn.classList.toggle("active");
        document.querySelectorAll(`.abc-preview[for="${field_name}"] .transposer-btn`).forEach(el => el.classList.toggle("hidden"));
        return;
    }

    let current_transposition = Number(transposerMainBtn.children[1].innerText.replace(/\+/, ""));
    current_transposition = mode === 0 ? 0 : current_transposition + mode;
    transposerMainBtn.children[1].innerText = current_transposition > 0 ? `+${current_transposition}` : current_transposition;
    abcPreview(field_name, current_transposition);
}
// #endregion

// #region navigation
function openMenu(mode = "") {
    const menu = document.querySelector(`header [role="bottom-part"]`);

    menu.dataset.mode = mode || "pinned";

    menu.querySelectorAll(`.button`).forEach(btn => {
        btn.classList.toggle("hidden", !btn.dataset.mode.includes(mode));
    });
}

function jumpTo(selector) {
    document.querySelector(selector).scrollIntoView({
        behavior: "smooth",
        block: "center"
    });
}

function initTableSort(ev) {
    const rowValue = (tr, i) => tr.children[i].innerText || tr.children[i].textContent;
    const comparer = (i, desc) => (a, b) => (
        (v1, v2) => v1 !== "" && v2 !== "" && !isNaN(v1) && !isNaN(v2)
            ? v1 - v2
            : v1.toString().localeCompare(v2)
    )(rowValue(desc ? b : a, i), rowValue(desc ? a : b, i));

    const clickedTh = ev.target.closest("th");
    const data = ev.target.closest("table").querySelector("tbody");
    Array.from(data.querySelectorAll("tr:not(.hidden)"))
        .sort(comparer(
            Array.from(clickedTh.parentNode.children).indexOf(clickedTh),
            this.desc = !this.desc
        ))
        .forEach(tr => data.appendChild(tr));
}
// #endregion

// #region cleanup
function reinitAll() {
    reapplyPopper();
    reinitSelect();
    reinitHTML();
}

function reapplyPopper() {
    document.querySelectorAll(`[data-tippy]`).forEach(el => {
        tippy(el, {
            content: el.dataset.tippy,
        });
    });
}

function reinitTableSort() {
    document.querySelectorAll(`table`).forEach(table => {
        table.querySelectorAll(`thead th.sortable`).forEach(th => {
            th.removeEventListener("click", initTableSort);
            th.addEventListener("click", initTableSort);
        });
    });
}

function cropCounters() {
    document.querySelectorAll(`.counter svg`).forEach(el => {
        const bbox = el.getBBox();
        el.setAttribute("width", Math.max(bbox.x + bbox.width + bbox.x, 30));
        el.setAttribute("height", bbox.y + bbox.height + bbox.y);
    });
}
// #endregion

// #region sections
function openSection(btn, key) {
    const section = document.querySelector(`[data-ebid='${key}']`)
    section.querySelector(".contents").classList.toggle("hidden");
    section.querySelectorAll(`.toggles`).forEach(b => b.classList.toggle("hidden"));
}
// #endregion

// #region dynamic components fetching
/**
 * All-in-one callback for fetching a component from API
 *
 * @param {string} loader_selector - selector for the loader connected to the component
 * @param {string} url - URL to API which serves the component
 * @param {object} urlbody - object containing body of the request
 * @param {Array} targets - array of arrays containing:
 *   - selector for the target element,
 *   - key of the response object with data - this assumes to be html to replace the target
 *   - (optional) name of a component maker - if passed, instead of html, 2nd element should be the data to pass to the so-named function to build the component
 * @param {Function} afterCallback - optional callback to run after the component is fetched
 * @param {object} options - optional modifiers:
 *   - customError - html of an error message
 */
function fetchComponent(loader_selector, url, urlbody = {}, targets = [], afterCallback = (res) => {}, options = {}) {
    const loader = document.querySelector(loader_selector);

    // start the sequence
    loader.classList.remove("hidden");
    targets.forEach(tdata => {
        document.querySelector(tdata[0]).classList.add("ghost");
    });

    // call the API
    fetch(url, urlbody)
        .then(res => res.json())
        .then(res => {
            targets.forEach(tdata => {
                document.querySelector(tdata[0]).innerHTML = (tdata[2])
                    ? window[tdata[2]](res[tdata[1]])
                    : res[tdata[1]];
            });
            afterCallback(res);
        })
        .catch(err => {
            console.error(err);
            // show error message in targets
            targets.forEach(tdata => {
                document.querySelector(tdata[0]).innerHTML =
                    options.customError
                    || `<p class="accent error">Nie udało się pobrać danych. To nie Twoja wina. Już nad tym pracujemy.</p>`;
            });
        })
        .finally(() => {
            // revert loading
            loader.classList.add("hidden");
            targets.forEach(tdata => {
                document.querySelector(tdata[0]).classList.remove("ghost");
            });
        });
}
// #endregion
