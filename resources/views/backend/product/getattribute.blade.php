<?php use App\Enums\AttributeType; ?>
@if ($category)
    @foreach ($category->categoryAttribute as $attr)
        @if ($attr->type == AttributeType::TEXT)
            <div class="form-group">
                <div class="row">
                    <div class="col-md-2 text-right">
                        <label>{{ $attr->name }}</label>
                        <span style="color: red;">*</span>
                    </div>
                    <div class="col-md-8">
                        <input type="text" name="{{ $attr->id }}" required
                               value="{{ isset($product) ? dataAttributeValue($attr, $product) : '' }}" class="form-control" />
                    </div>
                </div>
            </div>
        @endif
        @if ($attr->type == AttributeType::NUMBER)
            <div class="form-group">
                <div class="row">
                    <div class="col-md-2 text-right">
                        <label>{{ $attr->name }}</label>
                        <span style="color: red;">*</span>
                    </div>
                    <div class="col-md-8">
                        <input type="number" name="{{ $attr->id }}" required
                            value="{{ isset($product) ? dataAttributeValue($attr, $product) : '' }}" class="form-control" />
                    </div>
                </div>
            </div>
        @endif
        @if ($attr->type == AttributeType::NUMBER_FLOAT)
            <div class="form-group">
                <div class="row">
                    <div class="col-md-2 text-right">
                        <label>{{ $attr->name }}</label>
                        <span style="color: red;">*</span>
                    </div>
                    <div class="col-md-8">
                        <input type="number" step="any" name="{{ $attr->id }}" required
                            value="{{ isset($product) ? dataAttributeValue($attr, $product) : '' }}" class="form-control" />
                    </div>
                </div>
            </div>
        @endif
        @if ($attr->type == AttributeType::SELECT)
            <div class="form-group">
                <div class="row">
                    <div class="col-md-2 text-right">
                        <label>{{ $attr->name }}</label>
                        <span style="color: red;">*</span>
                    </div>
                    <div class="col-md-8">
                        <select name="{{ $attr->id }}" class="form-control">
                            @foreach (explode(';', $attr->value) as $value)
                                <option value="{{ $value }}"
                                    {{ isset($product) ? (checkDataAttributeValue($attr, $product, $value) ? 'selected' : '') : '' }}>
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endif
