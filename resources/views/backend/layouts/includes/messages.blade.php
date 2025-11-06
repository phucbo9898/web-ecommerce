@if(isset($errors) && $errors->any())
    <x-utils.alert type="danger" class="header-message">
        @foreach($errors->all() as $error)
            {{ $error }}<br/>
        @endforeach
    </x-utils.alert>
@endif

@if (session()->get('success'))
    <x-utils.alert type="success" class="header-message">
        {{ session()->get('success') }}
    </x-utils.alert>
@endif
@if (session()->get('error'))
    <x-utils.alert type="danger" class="header-message">
        {{ session()->get('error') }}
    </x-utils.alert>
@endif

@if (session()->get('sameValue'))
    <x-utils.alert type="danger" class="header-message">
        {{ session()->get('sameValue') }}
    </x-utils.alert>
@endif
