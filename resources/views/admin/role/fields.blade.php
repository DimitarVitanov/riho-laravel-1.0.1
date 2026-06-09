<div class="form theme-form">
    <div class="row">
        <div class="col">
            <div class="mb-3">
                <label>Name<span class="text-danger">*</span></label>
                <input class="form-control" type="text" placeholder="Enter Role" name="name"
                    value="{{ isset($role->name) ? $role->name : old('name') }}">
                @error('name')
                    <span class="text-danger d-block">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3">
                <label class="validation m-2">Permissions<span class="text-danger">*</span></label>
                    @foreach ($modules as $key => $module)
                        <div class="mb-2 card-wrapper border rounded-3 checkbox-checked">
                            <h6 class="sub-title">{{ ucfirst($module->name) }}:</h6>
                            <div class="form-check-size rtl-input">
                                @php
                                    $permissions = @$role?->getAllPermissions()->pluck('name')->toArray() ?? [];
                                    $isAllSelected =count(array_diff(array_values($module->actions), $permissions)) === 0;
                                @endphp
                                <label class="d-block" for="all-{{ $module->name }}">
                                    <input type="checkbox"
                                        class="checkbox_animated select-all-permission select-all-for-{{ $module->name }}"
                                        id="all-{{ $module->name }}" value="{{ $module->name }}"
                                        {{ $isAllSelected ? 'checked' : '' }}>{{ __('All') }}
                                </label>
                                @foreach ($module->actions as $action => $permission)
                                    <label class="d-block" for="{{ $permission }}" data-action="{{ $action }}"
                                        data-module="{{ $module->name }}">
                                        <input type="checkbox" name="permissions[]"
                                            class="checkbox_animated module_{{ $module->name }} module_{{ $module->name }}_{{ $action }}"
                                            value="{{ $permission }}" id="{{ $permission }}"
                                            {{ in_array($permission, $permissions) ? 'checked' : '' }}>{{ ucfirst($action) }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @error('permissions')
                    <span class="text-danger d-block">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>
    <div class="card-footer text-end pb-0 px-0">
        <div class="col-sm-9 offset-sm-3">
            <button class="btn btn-primary spinner-btn" type="submit">Save</button>
        </div>
    </div>
</div>
