@extends('layouts.app')
@section('title')
    ROLE & PERMISSIONS EDIT
@endsection
@section('content')
    <div class="card">
        <form method="post" action="{{ route('role.update', $role->id) }}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ $role->name }}" placeholder="Enter name">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row mt-2">
                    <h5>Permissions</h5>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="accordion" id="permissionsAccordion">
                            @foreach ([
                                'bd' => $bd,
                                'pvd' => $pvd,
                                'dashboard' => $dashboard,
                                'engineering' => $engineering,
                                'ppc' => $ppc,
                                'production' => $production,
                                'oee' => $oee,
                                'wms_dashboard' => $wms_dashboard,
                                'operation' => $operation,
                                'report' => $report,
                                'hr' => $hr,
                                'administration' => $administration,
                                'database' => $database,
                                'general_setting' => $general_setting,
                                'accounting' => $accounting,
                            ] as $group => $groupPermissions)
                                @if (!empty($groupPermissions) && is_array($groupPermissions))
                                    @php $groupSlug = Str::slug($group); @endphp
                                    <div class="accordion-item">
                                        <div class="accordion-header d-flex align-items-center"
                                            id="heading-{{ $groupSlug }}">
                                            <input type="checkbox" id="checkAll-{{ $groupSlug }}"
                                                class="check-all form-check-input ms-2">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapse-{{ $groupSlug }}"
                                                aria-expanded="false" aria-controls="collapse-{{ $groupSlug }}">
                                                {{ strtoupper(str_replace('_', ' ', $group)) }}
                                            </button>
                                        </div>
                                        <div id="collapse-{{ $groupSlug }}" class="accordion-collapse collapse"
                                            aria-labelledby="heading-{{ $groupSlug }}"
                                            data-bs-parent="#permissionsAccordion">
                                            <div class="accordion-body">
                                                @if ($group == 'general_setting')
                                                    <ul class="list-unstyled">
                                                        @foreach ($groupPermissions as $permissionName)
                                                            @foreach ($permissions as $permission)
                                                                @if ($permissionName == $permission->name)
                                                                    @php
                                                                        $lastWord = Str::of($permissionName)
                                                                            ->explode(' ')
                                                                            ->last();
                                                                        if ($lastWord == 'Percentage') {
                                                                            $lastWord = 'SST Percentage';
                                                                        } elseif ($lastWord == 'Note') {
                                                                            $lastWord = 'PO Important Note';
                                                                        } elseif ($lastWord == 'Break') {
                                                                            $lastWord = 'Spec Break';
                                                                        } elseif ($lastWord == 'No') {
                                                                            $lastWord = 'Initial Ref No';
                                                                        } elseif ($lastWord == 'Approval') {
                                                                            $lastWord = 'PR Approval';
                                                                        }
                                                                    @endphp
                                                                    <li>
                                                                        <div class="form-check form-check-inline">
                                                                            <input type="checkbox"
                                                                                id="check_{{ $permission->id }}"
                                                                                name="permissions[]" @checked(in_array($permission->id, $rolePermissions))
                                                                                class="form-check-input check-single"
                                                                                value="{{ $permission->id }}">
                                                                            <label class="form-check-label"
                                                                                for="check_{{ $permission->id }}">{{ strtoupper($lastWord) }}</label>
                                                                        </div>
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    @foreach ($groupPermissions as $subgroup => $permissionss)
                                                        @php $subGroupSlug = Str::slug($subgroup); @endphp
                                                        @if (is_array($permissionss) && !empty($permissionss))
                                                            <div class="accordion-item">
                                                                <div class="accordion-header d-flex align-items-center"
                                                                    id="heading-{{ $groupSlug }}-{{ $subGroupSlug }}">
                                                                    <input type="checkbox"
                                                                        id="checkAll-{{ $groupSlug }}-{{ $subGroupSlug }}"
                                                                        class="check-all form-check-input ms-2">
                                                                    <button class="accordion-button collapsed"
                                                                        type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#collapse-{{ $groupSlug }}-{{ $subGroupSlug }}"
                                                                        aria-expanded="false"
                                                                        aria-controls="collapse-{{ $groupSlug }}-{{ $subGroupSlug }}">
                                                                        {{ strtoupper($subgroup) }}
                                                                    </button>
                                                                </div>
                                                                <div id="collapse-{{ $groupSlug }}-{{ $subGroupSlug }}"
                                                                    class="accordion-collapse collapse"
                                                                    aria-labelledby="heading-{{ $groupSlug }}-{{ $subGroupSlug }}"
                                                                    data-bs-parent="#collapse-{{ $groupSlug }}">
                                                                    <div class="accordion-body">
                                                                        <ul class="list-unstyled">
                                                                            @foreach ($permissionss as $key => $permissionName)
                                                                                @foreach ($permissions as $permission)
                                                                                    @if ($permissionName == $permission->name)
                                                                                        @php
                                                                                            $lastWord = Str::of(
                                                                                                $permissionName,
                                                                                            )
                                                                                                ->explode(' ')
                                                                                                ->last();
                                                                                        @endphp
                                                                                        <li>
                                                                                            <div
                                                                                                class="form-check form-check-inline">
                                                                                                <input type="checkbox"
                                                                                                    @checked(in_array($permission->id, $rolePermissions))
                                                                                                    id="check_{{ $permission->id }}"
                                                                                                    name="permissions[]"
                                                                                                    class="form-check-input check-single"
                                                                                                    value="{{ $permission->id }}"
                                                                                                    data-group="checkAll-{{ $groupSlug }}-{{ Str::slug($key) }}">
                                                                                                <label
                                                                                                    class="form-check-label"
                                                                                                    for="check_{{ $permission->id }}">{{ strtoupper($lastWord) }}</label>
                                                                                            </div>
                                                                                        </li>
                                                                                    @endif
                                                                                @endforeach
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex gap-2 justify-content-between">
                    <a type="button" class="btn btn-info" href="{{ route('role.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            // Initialize the check-all checkboxes
            $('input[type="checkbox"].check-all').each(function() {
                var $checkbox = $(this);
                var $checkboxes = $checkbox.closest('.accordion-item').find(
                    'input[type="checkbox"].check-single');
                var allChecked = $checkboxes.length === $checkboxes.filter(':checked').length;
                $checkbox.prop('checked', allChecked);
            });

            // Handle Check All functionality
            $('input[type="checkbox"].check-all').on('change', function() {
                var $checkboxes = $(this).closest('.accordion-item').find('input[type="checkbox"]');
                $checkboxes.prop('checked', $(this).prop('checked'));

                updateParentCheckbox($(this));
            });

            // Handle individual checkbox change
            $('input[type="checkbox"].check-single').on('change', function() {
                updateParentCheckbox($(this));
            });

            function updateParentCheckbox($checkbox) {
                var $parentAccordionBody = $checkbox.closest('.accordion-body');
                var $allCheckboxes = $parentAccordionBody.find('input[type="checkbox"].check-single');
                var $allCheckedCheckboxes = $parentAccordionBody.find(
                'input[type="checkbox"].check-single:checked');

                // Update the parent check-all checkbox
                var $parentCheckAll = $parentAccordionBody.closest('.accordion-item').find(
                    'input[type="checkbox"].check-all:first');
                $parentCheckAll.prop('checked', $allCheckboxes.length === $allCheckedCheckboxes.length);

                // Update grandparent checkboxes
                var $parentAccordionItem = $checkbox.closest('.accordion-item');
                var $grandParentAccordionBody = $parentAccordionItem.closest('.accordion-collapse').prev(
                    '.accordion-header').closest('.accordion-item').find('.accordion-body:first');

                updateGrandparentCheckbox($grandParentAccordionBody);
            }

            function updateGrandparentCheckbox($accordionBody) {
                if ($accordionBody.length) {
                    var $allCheckboxes = $accordionBody.find('input[type="checkbox"].check-single');
                    var $allCheckedCheckboxes = $accordionBody.find('input[type="checkbox"].check-single:checked');

                    // Update the grandparent check-all checkbox
                    var $grandParentCheckAll = $accordionBody.closest('.accordion-item').find(
                        'input[type="checkbox"].check-all:first');
                    $grandParentCheckAll.prop('checked', $allCheckboxes.length === $allCheckedCheckboxes.length);

                    // Recursive update for higher levels
                    var $parentAccordionItem = $grandParentCheckAll.closest('.accordion-item');
                    var $greatGrandParentAccordionBody = $parentAccordionItem.closest('.accordion-collapse').prev(
                        '.accordion-header').closest('.accordion-item').find('.accordion-body:first');

                    updateGrandparentCheckbox($greatGrandParentAccordionBody);
                }
            }
        });
    </script>
@endsection
