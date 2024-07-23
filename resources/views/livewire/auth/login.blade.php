<div>
    <main class="mt-0 transition-all duration-200 ease-soft-in-out">
        <section>
            <div class="relative flex items-center p-0 overflow-hidden bg-center bg-cover min-h-75-screen">
                <div class="container z-10">
                    <div class="flex flex-wrap mt-0 -mx-3">
                        <div
                            class="flex flex-col w-full max-w-full px-3 mx-auto md:flex-0 shrink-0 md:w-6/12 lg:w-5/12 xl:w-4/12">
                            <div
                                class="relative flex flex-col min-w-0 mt-32 break-words bg-transparent border-0 shadow-none rounded-2xl bg-clip-border">
                                <div class="p-6 pb-0 mb-0 bg-transparent border-b-0 rounded-t-2xl">
                                    <span class="relative z-10 font-bold text-transparent bg-gradient-cyan bg-clip-text">{{ __("To") }}</span>
                                    <h2 class="relative z-10 font-bold text-transparent bg-gradient-cyan bg-clip-text text-5xl my-3 py-2">{{__("Seraj Carpet")}}</h2>
                                    <h3 class="relative z-10 font-bold text-transparent bg-gradient-cyan bg-clip-text">{{__("Welcome back!")}}</h3>
                                    <h1 class="mt-4 mb-0 text-slate">{{ __("Login / Register") }}</h1>
                                </div>

                                <div class="flex-auto p-6">

                                    @if (Session::has('status'))
                                        <div id="alert"
                                             class="relative p-4 pr-12 mb-4 text-white border border-solid rounded-lg bg-gradient-dark-gray border-slate-100">
                                            {{ Session::get('status') }}
                                            <button type="button" onclick="alertClose()"
                                                    class="box-content absolute top-0 right-0 p-2 text-white bg-transparent border-0 rounded w-4-em h-4-em text-size-sm z-2">
                                                <span aria-hidden="true" class="text-center cursor-pointer">&#10005;</span>
                                            </button>
                                        </div>

                                    @endif

                                    <form wire:submit="login">
                                         @if (!$codeSent)
                                        <label
                                            class="mb-2 ml-1 font-bold text-size-xs text-slate-700">{{__("Phone")}}</label>
                                        <div class="mb-4">
                                            <input wire:model.blur="phone" type="text"
                                                   class="focus:shadow-soft-primary-outline text-size-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                                                   name="phone" placeholder="09123456789" aria-label="Phone"
                                                   aria-describedby="phone-addon" required autofocus/>
                                        </div>
                                        @error('phone')
                                        <p class="text-size-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                        <div class="min-h-6 mb-0.5 block pl-12">
                                            <input dir="ltr" wire:model.live="remember_me"
                                                   class="mt-[0.135rem] rounded-[2.5rem] duration-250 ease-soft-in-out after:rounded-circle after:shadow-soft-2xl after:duration-250 checked:after:translate-x-5.25 h-[1.25em] relative float-start w-10 cursor-pointer appearance-none border border-solid border-gray-200 bg-slate-800/10 bg-none bg-contain bg-right bg-no-repeat align-top transition-all after:absolute after:top-px after:h-4 after:w-4 after:translate-x-px after:bg-white after:content-[''] checked:border-slate-800/95 checked:bg-slate-800/95 checked:bg-none checked:bg-left focus:outline-none"
                                                   type="checkbox" id="rememberMe">
                                            <label
                                                class="mb-2 ms-1 font-normal cursor-pointer select-none text-size-sm text-slate-700"
                                                for="rememberMe">{{ __("Remember me") }}</label>
                                        </div>
                                        @else
                                        <div class="text-center">
                                            <button type="button" id="send_code"
                                                    class="inline-block w-full px-6 py-3 mt-6 mb-0 font-bold text-center text-white uppercase align-middle transition-all bg-transparent border-0 rounded-lg cursor-pointer shadow-soft-md bg-x-25 bg-150 leading-pro text-size-xs ease-soft-in tracking-tight-soft bg-gradient-cyan hover:scale-102 hover:shadow-soft-xs active:opacity-85">{{__("Send Code")}}</button>
                                        </div>
                                        <label class="mb-2 ml-1 font-bold text-size-xs text-slate-700">Password</label>
                                        <div class="mb-4">
                                            <input wire:model.blur="code" type="number"
                                                   class="focus:shadow-soft-primary-outline text-size-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                                                   placeholder="Code" name="code" aria-label="Code"
                                                   aria-describedby="password-addon" required/>
                                            @error('password')
                                            <p class="text-size-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="text-center">
                                            <button type="submit"
                                                    class="inline-block w-full px-6 py-3 mt-6 mb-0 font-bold text-center text-white uppercase align-middle transition-all bg-transparent border-0 rounded-lg cursor-pointer shadow-soft-md bg-x-25 bg-150 leading-pro text-size-xs ease-soft-in tracking-tight-soft bg-gradient-cyan hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                                Sign
                                                in
                                            </button>
                                        </div>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="w-full max-w-full px-3 lg:flex-0 shrink-0 md:w-6/12">
                            <div
                                class="absolute top-0 hidden w-2/5 h-full -mr-2 overflow-hidden -skew-x-10 -left-16 rounded-br-xl md:block">
                                <div class="absolute inset-x-0 top-0 z-0 h-full -ml-16 bg-cover"
                                     style="background-image: url('{{asset("panel/img/curved-images/123.jpg")}}');background-size: cover;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        function alertClose() {
            document.getElementById("alert").style.display = "none";
        }
    </script>
</div>
