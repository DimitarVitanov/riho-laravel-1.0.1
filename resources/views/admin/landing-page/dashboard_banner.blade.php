<div class="accordion-item mb-3 dash-card">
    <h2 class="accordion-header" id="heading-{{ $index }}">
        <button class="accordion-button collapsed bg-light-primary txt-primary active" type="button"
            data-bs-toggle="collapse" data-bs-target="#collapseicon-{{ $index }}" aria-expanded="false"
            aria-controls="collapseicon-{{ $index }}">
            {{ $banner['title'] ?? 'Dashboard Banner' }}
        </button>
    </h2>
    @if ($banner == null)
        <div id="collapseicon-{{ $index }}" class="accordion-collapse collapse show"
            aria-labelledby="heading-{{ $index }}" data-bs-parent="#dashBannersAccordion">
        @else
            <div id="collapseicon-{{ $index }}" class="accordion-collapse collapse"
                aria-labelledby="heading-{{ $index }}" data-bs-parent="#dashBannersAccordion">
    @endif
    <div class="accordion-body">
        <div class="form-group row mb-3">
            <label class="col-md-2" for="portfolio_url">Title<span class="text-danger">*</span></label>
            <div class="col-md-10">
                <input class="form-control" type="text" name="page[dashboard][{{ $index }}][title]"
                    value="{{ @$banner['title'] ?? old('page[dashboard][$index][title]') }}"
                    placeholder="Enter Title">
                @error('page[dashboard][{{ $index }}][title]')
                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-2" for="portfolio_text">Url<span class="text-danger">*</span></label>
            <div class="col-md-10">
                <input class="form-control" type="text" name="page[dashboard][{{ $index }}][url]"
                    value="{{ @$banner['url'] ?? old('page[dashboard][$index][url]') }}"
                    placeholder="Enter Url">
                @error('page[dashboard][{{ $index }}][url]')
                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>
        <div class="form-group row mb-3">
            <label class="col-md-2" for="portfolio_text">Image<span class="text-danger">*</span></label>
            <div class="col-md-10">
                <input type="file" class="form-control fileInput"
                    name="page[dashboard][{{ $index }}][image]">
                @error('page[dashboard][{{ $index }}][image]')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <span class="help-text">*Upload image
                    size 448×314px
                    recommended</span>
            </div>
        </div>
        <div class="form-group row mb-3">
            <div class="col-md-2"></div>
            @if (isset($banner['image']) && !empty($banner['image']))
                <div class="col-md-10">
                    <div class="image-list">
                        <div class="image-list-detail">
                            <div class="position-relative">
                                    <img height="100px" src="{{ asset(@$banner['image']) }}" alt="Current Logo">
                                </div>
                            </div>
                        </div>
                </div>
            @endif
        </div>
        <div class="text-end">
            <button type="button" class="btn btn-outline-danger remove-banner">Remove</button>
        </div>
    </div>
</div>
</div>
