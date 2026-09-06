@if (session('success'))
    <x-banner type="success" id="successBanner">{{ session('success') }}</x-banner>
@endif

@if (session('error'))
    <x-banner type="danger" id="errorBanner">{{ session('error') }}</x-banner>
@endif

@if ($errors->any())
    <x-banner type="danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-banner>
@endif
