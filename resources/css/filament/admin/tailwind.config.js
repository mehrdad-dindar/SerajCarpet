import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [
        preset,
        require("./../../../../vendor/wireui/wireui/tailwind.config.js")
        ],
        content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './resources/views/livewire/order-comments.blade.php',
        './resources/views/livewire/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './app/Livewire/**/*.php',
        ],
    }
