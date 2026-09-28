<x-filament-panels::page>
    <form wire:submit="submit">
        {{ $this->form }}

        <div class="mt-6 flex items-center gap-3">
            <x-filament::button type="submit" size="lg">
                Save Settings
            </x-filament::button>

            <x-filament::button color="gray" type="button" wire:click="clearSystemCache">
                Clear All Caches
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
