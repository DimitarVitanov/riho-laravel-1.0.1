<div class="form theme-form mt-0">
    <div class="mt-3">
        <label class="form-label" for="">Tag Name<span class="text-danger">*</span></label>
        <input class="form-control" type="text" value="{{ isset($tag->name) ? $tag->name : old('name') }}"
            placeholder="Enter Tag Name" name="name">
        @error('name')
            <span class="invalid-feedback d-block">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="mt-3">
        <label class="form-label" for="">Description</label>
        <textarea class="form-control" name="description" placeholder="Enter Description">{{ isset($tag->description) ? $tag->description : old('description') }}</textarea>
        @error('description')
            <span class="invalid-feedback d-block">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="mt-3">
        <label class="form-label" for="">Status<span class="text-danger">*</span></label>
        <div class="d-flex flex-column-reverse">
            <select class="form-select status-placeholder" name="status">
                <option value="" selected disabled hidden></option>
                <option value="1" @if (isset($tag->status)) @if ('1' == $tag->status) selected @endif
                    @endif
                    @if (old('status') == '1') selected @endif>{{ __('Active') }}</option>
                <option value="0" @if (isset($tag->status)) @if ('0' == $tag->status) selected @endif
                    @endif
                    @if (old('status') == '0') selected @endif>{{ __('Deactive') }}</option>
            </select>
        </div>
    </div>
    <div class="mt-3 text-end">
        <button type="submit" class="btn btn-primary spinner-btn">{{ __('Save') }}</button>
    </div>
</div>
