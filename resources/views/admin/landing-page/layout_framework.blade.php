<div class="accordion-item mb-3 framework-card">
    <h2 class="accordion-header" id="heading-{{ $index }}">
        <button class="accordion-button collapsed bg-light-primary txt-primary active" type="button"
            data-bs-toggle="collapse" data-bs-target="#collapseicon-{{ $index }}" aria-expanded="false"
            aria-controls="collapseicon-{{ $index }}">
            {{ $banner['title'] ?? 'New Framework' }}
        </button>
    </h2>
    @if ($banner == null)
        <div id="collapseicon-{{ $index }}" class="accordion-collapse collapse show"
            aria-labelledby="heading-{{ $index }}" data-bs-parent="#LayoutFrameworkAccordion">
        @else
            <div id="collapseicon-{{ $index }}" class="accordion-collapse collapse"
                aria-labelledby="heading-{{ $index }}" data-bs-parent="#LayoutFrameworkAccordion">
    @endif
    <div class="accordion-body">
        <div class="form-group row">
                <label class="col-md-2 ">{{ __('Title') }}<span
                    class="text-danger">*</span></label>
                <div class="col-md-10 mb-3">
                    <input type="text" class="form-control me-2" name="page[frameworks][poster][{{ $index }}][title]" value="{{ $banner['title'] ?? '' }}" placeholder="Enter Title">
                    @error('page[frameworks][poster][{{ $index }}][title]')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>  
                    @enderror
                </div>
            </div>     
            <div class="form-group row mb-3">
                <label class="col-md-2 ">{{ __('Image') }}<span
                    class="text-danger">*</span></label>
                <div class="col-md-10">
                    <input type="file" class="form-control" name="page[frameworks][poster][{{ $index }}][image]">
                    @error('page[frameworks][poster][{{ $index }}][image]')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <span class="help-text">*Upload image
                    size 68×68px 
                    recommended</span>
                </div> 
            </div>
            @isset($content['page']['frameworks']['poster'][$index]['image'])
            <div class="form-group">
                <div class="row"> 
                    <div class="col-md-2"></div>
                    <div class="col-md-10">
                        <div class="image-list">
                            <div class="image-list-detail">
                                <div class="position-relative">
                                    <img src="{{ asset($content['page']['frameworks']['poster'][$index]['image']) }}" height="50px" alt="Poster Image" class="image-list-item">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endisset
            <div class="text-end">
                <button type="button" class="btn btn-outline-danger remove-banner">Remove</button>
            </div>
        </div>
    </div>
</div>