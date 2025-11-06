@extends('backend.layouts.main')

@section('title', 'Create new category')
<?php

use App\Enums\PublishEnum;

?>
@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3>Create new category</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.category.store') }}" method="POST" class="col-md-10 mx-auto form-create">
                    @csrf
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2 text-right">
                                <label>Name category</label>
                                <span class="text-red">*</span>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="name"
                                       value="{{ old('name') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2 text-right">
                                <label>Attribute <span class="text-red">*</span></label>
                            </div>
                            <div class="col-md-8">
                                @foreach ($attributes as $attribute)
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="attributes[]"
                                                   value="{{ $attribute->id }}"
                                                {{ old('attributes') && in_array($attribute->id, old('attributes')) ? 'checked' : '' }}>{{ $attribute->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2 text-right"></div>
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-success btn-common float-left"
                                        style="margin-right: 2px;">
                                    Save
                                </button>
                                <a href="{{ route('admin.category.index') }}"
                                   class="btn btn-secondary btn-common">@lang('Cancel')</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
