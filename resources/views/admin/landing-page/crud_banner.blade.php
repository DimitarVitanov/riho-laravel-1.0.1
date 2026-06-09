<div class="accordion-item mb-3 crud-card">
    <h2 class="accordion-header" id="heading-{{ $index }}">
        <button class="accordion-button collapsed bg-light-primary txt-primary active" type="button"
            data-bs-toggle="collapse" data-bs-target="#collapseicon-{{ $index }}" aria-expanded="false"
            aria-controls="collapseicon-{{ $index }}">
            {{ $banner['title'] ?? 'Crud Banner' }}
        </button>
    </h2>
    @if ($banner == null)
        <div id="collapseicon-{{ $index }}" class="accordion-collapse collapse show"
            aria-labelledby="heading-{{ $index }}" data-bs-parent="#crudBannersAccordion">
        @else
            <div id="collapseicon-{{ $index }}" class="accordion-collapse collapse"
                aria-labelledby="heading-{{ $index }}" data-bs-parent="#crudBannersAccordion">
    @endif
    <div class="accordion-body">
        <div class="form-group row">
            <label class="col-md-2" for="home[laravel_crud][crud_banners][{{ $index }}][bg_color]">Background

                Color<span class="text-danger">*</span></label>

            <div class="col-md-10 mb-3">
                <select class="form-select" name="home[laravel_crud][crud_banners][{{ $index }}][bg_color]">
                    <option value="" selected disabled hidden>Select Color
                    </option>
                    <option value="primary"
                        {{ old('home.laravel_crud.crud_banners' . $index . 'bg_color', $banner['bg_color'] ?? '') == 'primary' ? 'selected' : '' }}>
                        Primary</option>
                    <option value="secondary"
                        {{ old('home.laravel_crud.crud_banners' . $index . 'bg_color', $banner['bg_color'] ?? '') == 'secondary' ? 'selected' : '' }}>
                        Secondary</option>
                    <option value="warning"
                        {{ old('home.laravel_crud.crud_banners' . $index . 'bg_color', $banner['bg_color'] ?? '') == 'warning' ? 'selected' : '' }}>
                        Warning</option>
                </select>
                @error('home[laravel_crud][crud_banners][{{ $index }}][bg_color]')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
 
        <div class="form-group row">

            <label for="home[laravel_crud][crud_banners][{{ $index }}][title]" class="col-md-2">Title<span
                    class="text-danger">*</span></label>

            <div class="col-md-10 mb-3">
                <input class="form-control" type="text"
                    name="home[laravel_crud][crud_banners][{{ $index }}][title]"
                    id="home[laravel_crud][crud_banners][{{ $index }}][title]" placeholder="Enter Title"
                    value="{{ $banner['title'] ?? old('home[laravel_crud][crud_banners][$index][title]') }}">
                @error('home[laravel_crud][crud_banners][{{ $index }}][title]')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <label for="home[laravel_crud][crud_banners][{{ $index }}][description]"
                class="col-md-2">Description</label>
            <div class="col-md-10 mb-3">
                <textarea class="form-control" name="home[laravel_crud][crud_banners][{{ $index }}][description]"
                    id="home[laravel_crud][crud_banners][{{ $index }}][description]" placeholder="Enter Description"
                    rows="2">{{ $banner['description'] ?? old('home[laravel_crud][crud_banners][$index][description]') }}</textarea>
                @error('home[laravel_crud][crud_banners][{{ $index }}][description]')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group row">

            <label for="home[laravel_crud][crud_banners][{{ $index }}][image]" class="col-md-2">Image<span
                    class="text-danger">*</span></label>

            <div class="col-md-10 mb-3">
                <input class="form-control" type="file"
                    id="home[laravel_crud][crud_banners][{{ $index }}][image]"
                    name="home[laravel_crud][crud_banners][{{ $index }}][image]">
                @error('home[laravel_crud][crud_banners][{{ $index }}][image]')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <span class="help-text">*Upload image
                    size 692×500px
                    recommended</span>
            </div>
        </div>
        @isset($banner['image'])
            <div class="form-group">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-10 mb-3">
                        <div class="image-list">
                            <div class="image-list-detail">
                                <div class="position-relative">
                                    <img src="{{ asset($banner['image']) }}" height="100px" id="{{ $banner['image'] }}"
                                        alt="crudImage" class="image-list-item">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endisset

        <div class="form-group row">
            <label for="home[laravel_crud][crud_banners][{{ $index }}][url]" class="col-md-2">Url<span
                    class="text-danger">*</span></label>
            <div class="col-md-10 mb-3">
                <input class="form-control" type="text"
                    name="home[laravel_crud][crud_banners][{{ $index }}][url]"
                    id="home[laravel_crud][crud_banners][{{ $index }}][url]" placeholder="Enter Url"
                    value="{{ $banner['url'] ?? old('home[laravel_crud][crud_banners][$index][url]') }}">
                @error('home[laravel_crud][crud_banners][{{ $index }}][url]')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div> 
        <div class="text-end">
            <button type="button" class="btn btn-outline-danger remove-banner">Remove</button>
        </div>
    </div>
</div>
</div>
