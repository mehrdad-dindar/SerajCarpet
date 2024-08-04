<div class="relative flex flex-col w-full min-w-0 mb-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
    <div class="p-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
        <h6 class="mb-0">{{ __("Your Addresses") }}</h6>
    </div>
    <div class="flex-auto px-0 pt-0 pb-2">
        <div class="w-full max-w-full mb-6 md:w-6/12 md:flex-none xl:mb-0 xl:w-3/12">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="relative">
                    <a class="block shadow-xl rounded-2xl">
                        <img src="https://raw.githubusercontent.com/creativetimofficial/public-assets/master/soft-ui-dashboard/assets/img/home-decor-1.jpg" alt="img-blur-shadow" class="max-w-full shadow-soft-2xl rounded-2xl" />
                    </a>
                </div>
                <div class="flex-auto px-1 pt-6">
                    <p class="relative z-10 mb-2 leading-normal text-transparent bg-gradient-to-tl from-gray-900 to-slate-800 text-sm bg-clip-text">Project #2</p>
                    <a href="javascript:;">
                        <h5>Modern</h5>
                    </a>
                    <p class="mb-6 leading-normal text-sm">As Uber works through a huge amount of internal management turmoil.</p>
                    <div class="flex items-center justify-between">
                        <div class="mt-2">
                            <a href="javascript:;" class="relative z-20 inline-flex items-center justify-center w-6 h-6 text-white transition-all duration-200 border-2 border-white border-solid ease-soft-in-out text-xs rounded-circle hover:z-30">
                                <img class="w-full rounded-circle" alt="Image placeholder" src="https://raw.githubusercontent.com/creativetimofficial/public-assets/master/soft-ui-dashboard/assets/img/team-1.jpg" />
                            </a>
                            <a href="javascript:;" class="relative z-20 inline-flex items-center justify-center w-6 h-6 -ml-4 text-white transition-all duration-200 border-2 border-white border-solid ease-soft-in-out text-xs rounded-circle hover:z-30">
                                <img class="w-full rounded-circle" alt="Image placeholder" src="https://raw.githubusercontent.com/creativetimofficial/public-assets/master/soft-ui-dashboard/assets/img/team-2.jpg" />
                            </a>
                            <a href="javascript:;" class="relative z-20 inline-flex items-center justify-center w-6 h-6 -ml-4 text-white transition-all duration-200 border-2 border-white border-solid ease-soft-in-out text-xs rounded-circle hover:z-30">
                                <img class="w-full rounded-circle" alt="Image placeholder" src="https://raw.githubusercontent.com/creativetimofficial/public-assets/master/soft-ui-dashboard/assets/img/team-3.jpg" />
                            </a>
                            <a href="javascript:;" class="relative z-20 inline-flex items-center justify-center w-6 h-6 -ml-4 text-white transition-all duration-200 border-2 border-white border-solid ease-soft-in-out text-xs rounded-circle hover:z-30">
                                <img class="w-full rounded-circle" alt="Image placeholder" src="https://raw.githubusercontent.com/creativetimofficial/public-assets/master/soft-ui-dashboard/assets/img/team-4.jpg" />
                            </a>
                        </div>
{{--                        <x-mini-button rounded icon="home" />--}}
                        <x-button :label="__('Edit')" x-on:click="$openModal('simpleModal')" class="px-6"/>
                        <x-modal name="simpleModal" blur="xl" class="bg-gray-400">
                            <x-card title="Consent Terms" shadow="md">


                                <x-slot name="footer" class="flex justify-end gap-x-4">
                                    <x-button flat label="Cancel" x-on:click="close" />

                                    <x-button primary label="I Agree" wire:click="agree" />
                                </x-slot>
                            </x-card>
                        </x-modal>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
