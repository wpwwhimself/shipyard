<div id="modal" class="hidden">
    <x-shipyard.app.loader />

    <x-shipyard.app.card title="..." title-lvl="2" id="modal-card" class="hidden">
        <x-shipyard.app.form method="POST">
            <div role="fields"></div>

            <div role="summary" class="hidden">
                <x-shipyard.app.loader />

                <div role="summary-content"></div>
            </div>

            <x-slot:actions>
                <x-shipyard.ui.button
                    icon="check"
                    label="Zatwierdź"
                    action="submit"
                    class="primary"
                />
                <x-shipyard.ui.button
                    icon="arrow-right"
                    label="Dalej"
                    action="none"
                    class="tertiary hidden"
                    role="go_to_summary"
                />
                <x-shipyard.ui.button
                    icon="arrow-left"
                    label="Wróć"
                    action="none"
                    onclick="closeSummary()"
                    class="tertiary hidden"
                    role="close_summary"
                />
                <x-shipyard.ui.button
                    icon="close"
                    pop="Zamknij"
                    action="none"
                    onclick="closeModal()"
                    class="tertiary"
                    role="close_modal"
                />
            </x-slot:actions>
        </x-shipyard.app.form>
    </x-shipyard.app.card>
</div>

<script>
const modal = document.querySelector("#modal");
const loader = modal.querySelector(".loader");
const card = modal.querySelector("#modal-card");
const form = card.querySelector("form");
const fields = form.querySelector("[role='fields']");
const summary = form.querySelector("[role='summary']");
const summary_loader = summary.querySelector(".loader");
const summary_content = summary.querySelector("[role='summary-content']");

const submit_btn = form.querySelector("button[type='submit']");
const summary_btn = form.querySelector("[role='go_to_summary']");
const summary_close_btn = form.querySelector("[role='close_summary']");
const close_modal_btn = form.querySelector("[role='close_modal']");

const openModal = (name, defaults = {}) => {
    loader.classList.remove("hidden");
    modal.classList.remove("hidden");

    fetch(`{{ route("modals.data") }}/${name}`)
        .then(res => res.json())
        .then(data => {
            form.action = data.full_target_route;
            card.querySelector("[role$='title']").textContent = data.heading;
            form.querySelector("[role='fields']").innerHTML = data.rendered_fields;

            Object.entries(defaults).forEach(([name, value]) => {
                if (form.querySelector(`[role='fields'] [name="${name}"]`)) {
                    form.querySelector(`[role='fields'] [name="${name}"]`).value = value;
                    form.querySelector(`[role='fields'] [name="${name}"]`).dispatchEvent(new Event("input"));
                } else {
                    form.querySelector("[role='fields']").append(fromHTML(`<input type="hidden" name="${name}" value="${value}">`));
                }
            });


            if (data.full_summary_route) {
                submit_btn.classList.add("hidden");
                summary_btn.classList.remove("hidden");

                summary_btn.onclick = () => {
                    summary_btn.classList.add("hidden");
                    fields.classList.add("hidden");
                    summary.classList.remove("hidden");
                    summary_loader.classList.remove("hidden");

                    fetch(data.full_summary_route, {
                        method: "POST",
                        body: new FormData(form),
                    })
                        .then(res => res.text())
                        .then(summary => {
                            summary_content.innerHTML = summary;
                        })
                        .catch(err => console.error(err))
                        .finally(() => {
                            summary_loader.classList.add("hidden");
                            submit_btn.classList.remove("hidden");
                            summary_close_btn.classList.remove("hidden");
                            close_modal_btn.classList.add("hidden");
                        });
                }
            }

            card.classList.remove("hidden");
            reapplyPopper();
            reinitSelect();
        })
        .catch(err => {
            console.error(err);
            closeModal();
        })
        .finally(() => {
            loader.classList.add("hidden");
        });
}

const closeModal = () => {
    fields.innerHTML = "";
    card.classList.add("hidden");
    modal.classList.add("hidden");

    submit_btn.classList.remove("hidden");
    summary_btn.classList.add("hidden");
    summary_close_btn.classList.add("hidden");
    close_modal_btn.classList.remove("hidden");
}

const closeSummary = () => {
    summary_content.innerHTML = "";
    fields.classList.remove("hidden");
    summary.classList.add("hidden");

    submit_btn.classList.add("hidden");
    summary_btn.classList.remove("hidden");
    summary_close_btn.classList.add("hidden");
    close_modal_btn.classList.remove("hidden");
}
</script>
