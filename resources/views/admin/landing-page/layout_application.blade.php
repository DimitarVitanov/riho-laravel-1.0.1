<div class="accordion-item mb-3 app-card" id="banner-{{ $index }}">
    <h2 class="accordion-header" id="heading-{{ $index }}">
        <button class="accordion-button collapsed bg-light-primary txt-primary active" type="button"
            data-bs-toggle="collapse" data-bs-target="#collapseicon-{{ $index }}" aria-expanded="false"
            aria-controls="collapseicon-{{ $index }}">
            {{ $banner['title'] ?? 'Application Banner' }}
        </button>
    </h2>
    @if ($banner == null)
        <div id="collapseicon-{{ $index }}" class="accordion-collapse collapse show"
            aria-labelledby="heading-{{ $index }}" data-bs-parent="#layoutApplicationAccordion">
        @else
            <div id="collapseicon-{{ $index }}" class="accordion-collapse collapse"
                aria-labelledby="heading-{{ $index }}" data-bs-parent="#layoutApplicationAccordion">
    @endif
        <div class="accordion-body">
            <div class="form-group row mb-3">
                <label class="col-md-2 ">{{ __('Title') }}<span
                    class="text-danger">*</span></label>
                <div class="col-md-10">
                    <input type="text" class="form-control" name="page[applications][poster][{{ $index }}][title]" value="{{ $banner['title'] ?? '' }}" placeholder="Enter Title">
                </div>
            </div>

            <div class="form-group row mb-3">
                <label class="col-md-2 ">{{ __('Image') }}<span
                    class="text-danger">*</span></label>
                <div class="col-md-10 mb-3">
                    <input type="file" class="form-control" name="page[applications][poster][{{ $index }}][image]">
                    @error('page[applications][poster][{{ $index }}][image]')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <span class="help-text">*Upload image size 316×238px recommended</span>
                </div>
                @isset($content['page']['applications']['poster'][$index]['image'])
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-10">
                            <div class="image-list">
                                <div class="image-list-detail">
                                    <div class="position-relative">
                                        <img src="{{ asset($content['page']['applications']['poster'][$index]['image']) }}" height="100px" alt="Poster Image" class="image-list-item">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endisset
            </div>
            <div class="form-group row mb-3">
                <label class="col-md-2 ">{{ __('Url') }}<span
                    class="text-danger">*</span></label>
                <div class="col-md-10">
                    <input type="text" class="form-control" name="page[applications][poster][{{ $index }}][url]" value="{{ $banner['url'] ?? '' }}" placeholder="Enter Url">
                </div>
            </div>
            <div class="text-end">
                <button type="button" class="btn btn-outline-danger remove-banner">Remove</button>
            </div>
        </div>
    </div>
</div>
