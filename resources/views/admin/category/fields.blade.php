<div class="col-12">
    <label class="form-label" for="">Name<span class="text-danger">*</span></label>
    <input class="form-control" type="text" value="{{ isset($cat->name) ? $cat->name : old('name') }}"
        placeholder="Enter Category Name" name="name">
    @error('name')
        <span class="invalid-feedback d-block">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
<div class="col-12">
    <label class="form-label" for="">Description</label>
    <textarea class="form-control" name="description" placeholder="Enter Description">{{ isset($cat->description) ? $cat->description : old('description') }}</textarea>
    @error('description')
        <span class="invalid-feedback d-block">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
<div class="col-12">
    <label class="form-label" for="">Parent</label>
    <select class="form-select parent-category-placeholder" name="parent_id">
        <option value="" disabled hidden selected>Select Parent</option>
        @foreach ($parent as $key => $category)
            @if ($key != @$cat?->id)
                <option class="option" @selected(old('parent_id', @$cat->parent_id) == $key) value="{{ $key }}">{{ ucfirst($category) }}
                </option>
            @endif
        @endforeach
    </select>
</div>
<div class="col-12">
    <label class="form-label" for="">Meta Title<span class="text-danger">*</span></label>
    <input class="form-control" type="text"
        value="{{ isset($cat->meta_title) ? $cat->meta_title : old('meta_title') }}" placeholder="Enter Meta Title"
        name="meta_title">
    @error('meta_title')
        <span class="invalid-feedback d-block">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
<div class="col-12">
    <label class="form-label" for="">Meta Description</label>
    <textarea class="form-control" name="meta_description" placeholder="Enter Meta Description">{{ isset($cat->meta_description) ? $cat->meta_description : old('meta_description') }}</textarea>
    @error('meta_description')
        <span class="invalid-feedback d-block">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
<div class="col-sm-6">
    <label class="form-label" for="">Image</label>
    <input class="form-control" type="file" name="image">
    @isset($cat)
        @php
            $image = $cat->getFirstMedia('image');
        @endphp
        <div class="mt-3 comman-image">
            @if ($image)
                <img src="{{ $image->getUrl() }}" alt="Image" class="img-thumbnail">
            @endif
        </div>
    @endisset
    @error('image')
        <span class="invalid-feedback d-block">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
<div class="col-sm-6">
    <label class="form-label" for="">Status<span class="text-danger">*</span></label>
    <div class="d-flex flex-column-reverse">
        <select class="form-select status-placeholder" name="status">
            <option value="" selected disabled hidden></option>
            <option value="1" @if (isset($cat->status)) @if ('1' == $cat->status) selected @endif
                @endif
                @if (old('status') == '1') selected @endif>{{ __('Active') }}</option>
            <option value="0" @if (isset($cat->status)) @if ('0' == $cat->status) selected @endif
                @endif
                @if (old('status') == '0') selected @endif>{{ __('Deactive') }}</option>
        </select>
    </div>
</div>
<div class="col-12 text-end">
    <button type="submit" class="btn btn-primary spinner-btn">{{ __('Save') }}</button>
</div>
