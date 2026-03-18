@extends("layouts.shipyard.admin")
@section("title", "Historia")
@section("subtitle", "Administracja | ".$meta["label"]." | ".$data)

@section("content")

@foreach ($data->audits()->with("user")->get() as $hentry)
    @php
    $events = [
        "created" => "Utworzono",
        "updated" => "Zaktualizowano",
        "deleted" => "Usunięto",
        "restored" => "Przywrócono",
    ];
    $icons = [
        "created" => "plus",
        "updated" => "pencil",
        "deleted" => "delete",
        "restored" => "restore",
    ];

    $hmeta = $hentry->getMetadata();
    @endphp

    <x-shipyard.app.section
        :title="$events[$hmeta['audit_event']]"
        :icon="$icons[$hmeta['audit_event']]"
        :extended="false"
    >
        <x-slot:actions>
            <div class="ghost">
                <x-shipyard.app.icon-label-value
                    icon="account-edit"
                    label="Data operacji"
                >
                    {{ $hmeta["user_name"] }},
                    <span {{ Popper::pop($hmeta["audit_created_at"]) }}>{{ \Carbon\Carbon::parse($hmeta["audit_created_at"])->diffForHumans() }}</span>
                </x-shipyard.app.icon-label-value>
            </div>
        </x-slot:actions>

        @foreach ($hentry->getModified() as $field_name => $change)
            <div class="grid but-mobile-down" style="--col-count: 3;">
                <div class="accent secondary">
                    <x-shipyard.app.icon :name="model_field_icon($scope, $field_name)" />
                    {{ $data::getFields()[$field_name]["label"] }}
                </div>
                <div class="ghost">
                    {{ $change["old"] ?? "—" }}
                </div>
                <div>
                    {{ $change["new"] ?? "—" }}
                </div>
            </div>
            @endforeach
    </x-shipyard.app.section>
@endforeach

<div class="flex right center middle">
    <div class="card">
        <x-shipyard.ui.button
            icon="arrow-left"
            label="Wróć"
            :action="route('admin.model.edit', ['model' => $scope, 'id' => $data->getKey()])"
        />
    </div>
</div>

@endsection
