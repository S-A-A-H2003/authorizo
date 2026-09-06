@props([
    'cancelHref',
    'submitLabel' => 'Save',
])

<div class="actions">
    <x-button :href="$cancelHref" variant="secondary">Cancel</x-button>
    <x-button type="submit" variant="primary">{{ $submitLabel }}</x-button>
</div>
