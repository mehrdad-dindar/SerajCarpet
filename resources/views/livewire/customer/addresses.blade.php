<div class="relative flex flex-col w-full min-w-0 mb-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
    <div class="p-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
        <h6 class="mb-0">{{ __("Your Addresses") }}</h6>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-2 p-4">
        @foreach($addresses as $address)
            <div class="w-full max-w-full mb-6">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-xl bg-clip-border">
                    <div class="relative">
                        @if($address->is_active)
                            <x-srj-mini-badge lg icon="home" rounded flat emerald class="absolute z-10 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"/>
                        @endif
                        <a class="block shadow-xl rounded-2xl">
                            <img class="w-full shadow-soft-sm rounded-xl object-cover max-h-30 blur-sm" src="{!! $address->getMapUrl() !!}"/>
                        </a>
                    </div>
                    <div class="flex-auto px-1 pt-6">
                        <p class="mb-2 leading-normal text-transparent bg-gradient-to-tl from-gray-900 to-slate-800 text-sm bg-clip-text">
                            {!! 'منطقه '.$address->municipality_zone !!}</p>
                        <a href="javascript:;">
                            <h5>{!! 'محله '.$address->neighbourhood !!}</h5>
                        </a>
                        <p class="mb-6 leading-normal text-sm">{!! $address->getFullAddress() !!}</p>
                        <div class="flex items-center justify-between">
                            {{--<x-srj-button :label="__('Edit')" x-on:click="$openModal('simpleModal-{{$address->id}}')" class="px-6"/>
                            <x-srj-modal :name="'simpleModal-'.$address->id" blur="xl" class="bg-gray-400">
                                <x-srj-card title="Consent Terms" shadow="md">


                                    <x-slot name="footer" class="flex justify-end gap-x-4">
                                        <x-srj-button flat label="Cancel" x-on:click="close" />

                                        <x-srj-button primary label="I Agree" wire:click="agree" />
                                    </x-slot>
                                </x-srj-card>
                            </x-srj-modal>--}}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
