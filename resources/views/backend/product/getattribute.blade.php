<?php use App\Enums\AttributeType; ?>
@if ($category)
    @foreach ($category->categoryAttribute as $attr)
        @if ($attr->type == AttributeType::TEXT)
            <div class="form-group">
                <label class="font-weight-bold">{{ $attr->name }} <span class="text-danger">*</span></label>
                <input type="text" name="{{ $attr->id }}" required
                    value="{{ isset($product) ? dataAttributeValue($attr, $product) : '' }}"
                    class="form-control" placeholder="Nhập {{ mb_strtolower($attr->name) }}...">
            </div>
        @endif
        @if ($attr->type == AttributeType::NUMBER)
            <div class="form-group">
                <label class="font-weight-bold">{{ $attr->name }} <span class="text-danger">*</span></label>
                <input type="number" name="{{ $attr->id }}" required
                    value="{{ isset($product) ? dataAttributeValue($attr, $product) : '' }}"
                    class="form-control" placeholder="Nhập {{ mb_strtolower($attr->name) }}...">
            </div>
        @endif
        @if ($attr->type == AttributeType::NUMBER_FLOAT)
            <div class="form-group">
                <label class="font-weight-bold">{{ $attr->name }} <span class="text-danger">*</span></label>
                <input type="number" step="any" name="{{ $attr->id }}" required
                    value="{{ isset($product) ? dataAttributeValue($attr, $product) : '' }}"
                    class="form-control" placeholder="Nhập {{ mb_strtolower($attr->name) }}...">
            </div>
        @endif
        @if ($attr->type == AttributeType::SELECT)
            <div class="form-group">
                <label class="font-weight-bold">{{ $attr->name }} <span class="text-danger">*</span></label>
                <select name="{{ $attr->id }}" class="form-control">
                    @foreach (explode(';', $attr->value) as $value)
                        <option value="{{ $value }}"
                            {{ isset($product) ? (checkDataAttributeValue($attr, $product, trim($value)) ? 'selected' : '') : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
    @endforeach
@endif
