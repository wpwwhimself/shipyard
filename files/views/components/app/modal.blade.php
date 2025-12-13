<div id="modal" class="hidden">
    <x-shipyard.app.loader />

    <x-shipyard.app.section title="..." class="hidden">
        <x-shipyard.app.form method="POST">
            <div role="fields"></div>

            <x-slot:actions>
                <x-shipyard.ui.button
                    icon="check"
                    label="Zatwierdź"
                    action="submit"
                    class="primary"
                />
                <x-shipyard.ui.button
                    icon="close"
                    pop="Zamknij"
                    action="none"
                    onclick="closeModal()"
                    class="tertiary"
                />
            </x-slot:actions>
        </x-shipyard.app.form>
    </x-shipyard.app.section>
</div>

<script>
const modal = document.querySelector("#modal");
const loader = modal.querySelector(".loader");
const section = modal.querySelector(".section");
const form = section.querySelector("form");

const openModal = (name, defaults = {}) => {
    loader.classList.remove("hidden");
    modal.classList.remove("hidden");

    fetch(`{{ route("modals.data") }}/${name}`)
        .then(res => res.json())
        .then(data => {
            form.action = data.full_target_route;
            section.querySelector("[role='section-title']").textContent = data.heading;
            form.querySelector("[role='fields']").innerHTML = data.rendered_fields;

            Object.entries(defaults).forEach(([name, value]) => {
                if (form.querySelector(`[role='fields'] [name="${name}"]`)) {
                    form.querySelector(`[role='fields'] [name="${name}"]`).value = value;
                    form.querySelector(`[role='fields'] [name="${name}"]`).dispatchEvent(new Event("input"));
                } else {
                    form.querySelector("[role='fields']").append(fromHTML(`<input type="hidden" name="${name}" value="${value}">`));
                }
            });

            section.classList.remove("hidden");
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
    form.querySelector("[role='fields']").innerHTML = "";
    section.classList.add("hidden");
    modal.classList.add("hidden");
}
</script>
