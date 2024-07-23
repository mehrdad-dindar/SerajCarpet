<!-- sidenav  -->
@if (Request::is('virtual-reality'))
    <aside
        class="fixed inset-y-0 xl:animate-fade-up z-990 xl:scale-60 xl:left-[18%] flex-wrap items-center justify-between block w-full p-0 my-4 xl:ml-4 overflow-y-auto antialiased transition-transform duration-200 -translate-x-full bg-white border-0 shadow-none max-w-62.5 ease-nav-brand rounded-2xl xl:translate-x-0">
        @else
            <aside class="max-w-62.5 ease-nav-brand z-990 fixed inset-y-0 my-4 block w-full flex-wrap items-center justify-between overflow-y-auto rounded-2xl border-0 bg-white p-0 antialiased shadow-none transition-transform duration-200 {{ (true ? 'xl:right-0 mr-4 translate-x-full' : 'xl:left-0 ml-4 -translate-x-full ') }}{{--Request::is('rtl')--}} xl:translate-x-0 xl:bg-transparent
    ">

                @endif
                <div class="h-19.5">
                    <i class="absolute top-0 right-0 hidden p-4 opacity-50 cursor-pointer fas fa-times text-slate-400 xl:hidden"
                       sidenav-close></i>
                    <a class="block px-8 py-6 m-0 text-size-sm whitespace-nowrap text-slate-700"
                       href="{{ route('customer.panel.index') }}" target="_blank">
                        <img src="{{asset("panel/img/logo-ct.png")}}"
                             class="inline h-full max-w-full transition-all duration-200 ease-nav-brand max-h-8"
                             alt="main_logo"/>
                        <span
                            class="{{ (true ? 'mr-1' : 'ml-1') }} font-semibold transition-all duration-200 ease-nav-brand">Seraj Carpet</span>
                    </a>
                </div>

                <hr
                    class="h-px mt-0 bg-transparent {{ (Request::is('virtual-reality') ? 'bg-gradient-horizontal-dark' : 'via-black/40 bg-gradient-to-r from-transparent to-transparent') }} "/>

                <div class="items-center block w-auto max-h-screen overflow-auto grow basis-full">
                    <ul class="flex flex-col pl-0 mb-0">
                        <li class="mt-0.5 w-full">
                            <a class="py-2.7 text-size-sm ease-nav-brand my-0 mx-4 flex items-center gap-2 whitespace-nowrap px-4 transition-colors
                {{ (Request::is('panel') ? 'shadow-soft-xl rounded-lg bg-white font-semibold text-slate-700' : '') }}"
                               href="{{ route("customer.panel.index") }}">

                                <div
                                    class="{{ (Request::is('panel') ? ' bg-gradient-fuchsia' : '') }} shadow-soft-2xl flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                                    <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1"
                                         xmlns="http://www.w3.org/2000/svg"
                                         xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <title>shop</title>
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF"
                                               fill-rule="nonzero">
                                                <g transform="translate(1716.000000, 291.000000)">
                                                    <g transform="translate(0.000000, 148.000000)">
                                                        <path
                                                            class="{{ (Request::is('panel') ? '' : 'fill-slate-800') }} "
                                                            d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z">
                                                        </path>
                                                        <path
                                                            class="{{ (Request::is('panel') ? '' : 'fill-slate-800') }}"
                                                            d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z">
                                                        </path>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                                <span
                                    class="{{ (true ? 'mr-1' : 'ml-1') }} duration-300 opacity-100 pointer-events-none ease-soft">{{ __("Dashboard") }}</span>
                            </a>
                        </li>

                        <li class="w-full mt-4">
                            <h6
                                class="{{ (true ? 'pr-6 mr-2' : 'pl-6 ml-2') }} font-bold leading-tight uppercase text-size-xs opacity-60"></h6>
                        </li>

                        <li class="mt-0.5 w-full">
                            <a class="py-2.7 text-size-sm ease-nav-brand my-0 mx-4 flex items-center gap-2 whitespace-nowrap px-4 transition-colors
              {{ (Request::is('panel/profile') ? 'shadow-soft-xl rounded-lg bg-white font-semibold text-slate-700' : '') }}"
                               href="{{ route("customer.panel.profile") }}">
                                <div
                                    class="{{ (Request::is('panel/profile') ? ' bg-gradient-fuchsia' : '') }} shadow-soft-2xl flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">

                                    <svg width="12px" height="12px" viewBox="0 0 46 42" version="1.1"
                                         xmlns="http://www.w3.org/2000/svg"
                                         xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <title>User Profile</title>
                                        <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none"
                                           fill-rule="evenodd">
                                            <g id="Rounded-Icons" transform="translate(-1717.000000, -291.000000)"
                                               fill="#FFFFFF"
                                               fill-rule="nonzero">
                                                <g id="Icons-with-opacity"
                                                   transform="translate(1716.000000, 291.000000)">
                                                    <g id="customer-support" transform="translate(1.000000, 0.000000)">
                                                        <path
                                                            class="{{ (Request::is('panel/profile') ? '' : 'fill-slate-800') }}"
                                                            d="M45,0 L26,0 C25.447,0 25,0.447 25,1 L25,20 C25,20.379 25.214,20.725 25.553,20.895 C25.694,20.965 25.848,21 26,21 C26.212,21 26.424,20.933 26.6,20.8 L34.333,15 L45,15 C45.553,15 46,14.553 46,14 L46,1 C46,0.447 45.553,0 45,0 Z"
                                                            id="Path" opacity="0.59858631"></path>
                                                        <path
                                                            class="{{ (Request::is('panel/profile') ? '' : 'fill-slate-800') }}"
                                                            d="M22.883,32.86 C20.761,32.012 17.324,31 13,31 C8.676,31 5.239,32.012 3.116,32.86 C1.224,33.619 0,35.438 0,37.494 L0,41 C0,41.553 0.447,42 1,42 L25,42 C25.553,42 26,41.553 26,41 L26,37.494 C26,35.438 24.776,33.619 22.883,32.86 Z"
                                                            id="Path"></path>
                                                        <path
                                                            class="{{ (Request::is('panel/profile') ? '' : 'fill-slate-800') }}"
                                                            d="M13,28 C17.432,28 21,22.529 21,18 C21,13.589 17.411,10 13,10 C8.589,10 5,13.589 5,18 C5,22.529 8.568,28 13,28 Z"
                                                            id="Path"></path>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>


                                </div>
                                <span class="mr-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __("User Profile") }}</span>
                            </a>
                        </li>

                        <li class="mt-0.5 w-full">
                            <a class="py-2.7 text-size-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors {{ (Request::is('user-management') ? 'shadow-soft-xl rounded-lg bg-white font-semibold text-slate-700' : '') }}"
                               href="{{ url('user-management') }}">

                                <div
                                    class="{{ (Request::is('user-management') ? ' bg-gradient-fuchsia' : '') }} shadow-soft-2xl me-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">

                                    <svg class="fill-slate-800" xmlns="http://www.w3.org/2000/svg" width="12px" height="12px" viewBox="0 0 576 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                        <path d="M0 24C0 10.7 10.7 0 24 0L69.5 0c22 0 41.5 12.8 50.6 32l411 0c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3l-288.5 0 5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5L488 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-288.3 0c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5L24 48C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"/>
                                    </svg>
                                </div>
                                <span class="mr-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __("Orders") }}</span>
                            </a>
                        </li>
                        <li class="mt-0.5 w-full">
                            <a class="py-2.7 text-size-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors
              {{ (Request::is('billing') ? 'shadow-soft-xl rounded-lg bg-white font-semibold text-slate-700' : '') }}"
                               href="{{ url('billing') }}">
                                <div
                                    class="{{ (Request::is('billing') ? ' bg-gradient-fuchsia' : '') }} shadow-soft-2xl me-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">

                                    <svg class="fill-slate-800" xmlns="http://www.w3.org/2000/svg" width="12px" height="12px" viewBox="0 0 384 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M64 0C28.7 0 0 28.7 0 64L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-288-128 0c-17.7 0-32-14.3-32-32L224 0 64 0zM256 0l0 128 128 0L256 0zM64 80c0-8.8 7.2-16 16-16l64 0c8.8 0 16 7.2 16 16s-7.2 16-16 16L80 96c-8.8 0-16-7.2-16-16zm0 64c0-8.8 7.2-16 16-16l64 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-64 0c-8.8 0-16-7.2-16-16zm128 72c8.8 0 16 7.2 16 16l0 17.3c8.5 1.2 16.7 3.1 24.1 5.1c8.5 2.3 13.6 11 11.3 19.6s-11 13.6-19.6 11.3c-11.1-3-22-5.2-32.1-5.3c-8.4-.1-17.4 1.8-23.6 5.5c-5.7 3.4-8.1 7.3-8.1 12.8c0 3.7 1.3 6.5 7.3 10.1c6.9 4.1 16.6 7.1 29.2 10.9l.5 .1s0 0 0 0s0 0 0 0c11.3 3.4 25.3 7.6 36.3 14.6c12.1 7.6 22.4 19.7 22.7 38.2c.3 19.3-9.6 33.3-22.9 41.6c-7.7 4.8-16.4 7.6-25.1 9.1l0 17.1c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-17.8c-11.2-2.1-21.7-5.7-30.9-8.9c0 0 0 0 0 0c-2.1-.7-4.2-1.4-6.2-2.1c-8.4-2.8-12.9-11.9-10.1-20.2s11.9-12.9 20.2-10.1c2.5 .8 4.8 1.6 7.1 2.4c0 0 0 0 0 0s0 0 0 0s0 0 0 0c13.6 4.6 24.6 8.4 36.3 8.7c9.1 .3 17.9-1.7 23.7-5.3c5.1-3.2 7.9-7.3 7.8-14c-.1-4.6-1.8-7.8-7.7-11.6c-6.8-4.3-16.5-7.4-29-11.2l-1.6-.5s0 0 0 0c-11-3.3-24.3-7.3-34.8-13.7c-12-7.2-22.6-18.9-22.7-37.3c-.1-19.4 10.8-32.8 23.8-40.5c7.5-4.4 15.8-7.2 24.1-8.7l0-17.3c0-8.8 7.2-16 16-16z"/></svg>

                                </div>
                                <span
                                    class="mr-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __("Billing") }}</span>
                            </a>
                        </li>

                        <li class="w-full mt-4">
                            <h6 class="{{ (true ? 'pr-6 mr-2' : 'pl-6 ml-2') }} font-bold leading-tight uppercase text-size-xs opacity-60">
                                <hr>
                            </h6>
                        </li>

                        <li class="mt-0.5 w-full">
                            <a class="py-2.7 text-size-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors
              {{ (Request::is('tables') ? 'shadow-soft-xl rounded-lg bg-white font-semibold text-slate-700' : '') }}"
                               href="{{ url('tables') }}">
                                <div
                                    class="{{ (Request::is('tables') ? ' bg-gradient-fuchsia' : '') }} shadow-soft-2xl me-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">

                                    <svg class="fill-slate-800" xmlns="http://www.w3.org/2000/svg" width="12px" height="12px" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 48C141.1 48 48 141.1 48 256l0 40c0 13.3-10.7 24-24 24s-24-10.7-24-24l0-40C0 114.6 114.6 0 256 0S512 114.6 512 256l0 144.1c0 48.6-39.4 88-88.1 88L313.6 488c-8.3 14.3-23.8 24-41.6 24l-32 0c-26.5 0-48-21.5-48-48s21.5-48 48-48l32 0c17.8 0 33.3 9.7 41.6 24l110.4 .1c22.1 0 40-17.9 40-40L464 256c0-114.9-93.1-208-208-208zM144 208l16 0c17.7 0 32 14.3 32 32l0 112c0 17.7-14.3 32-32 32l-16 0c-35.3 0-64-28.7-64-64l0-48c0-35.3 28.7-64 64-64zm224 0c35.3 0 64 28.7 64 64l0 48c0 35.3-28.7 64-64 64l-16 0c-17.7 0-32-14.3-32-32l0-112c0-17.7 14.3-32 32-32l16 0z"/></svg>

                                </div>
                                <span
                                    class="mr-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __("Support") }}</span>
                            </a>
                        </li>

                        <li class="mt-0.5 w-full">
                            <a class="py-2.7 text-size-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors
              {{ (Request::is('virtual-reality') ? 'shadow-soft-xl rounded-lg bg-white font-semibold text-slate-700' : '') }}"
                               href="{{ url('virtual-reality') }}">
                                <div
                                    class="{{ (Request::is('virtual-reality') ? ' bg-gradient-fuchsia' : '') }} shadow-soft-2xl me-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">

                                    <svg class="fill-slate-800" xmlns="http://www.w3.org/2000/svg" width="12px" height="12px" viewBox="0 0 576 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M163.9 136.9c-29.4-29.8-29.4-78.2 0-108s77-29.8 106.4 0l17.7 18 17.7-18c29.4-29.8 77-29.8 106.4 0s29.4 78.2 0 108L310.5 240.1c-6.2 6.3-14.3 9.4-22.5 9.4s-16.3-3.1-22.5-9.4L163.9 136.9zM568.2 336.3c13.1 17.8 9.3 42.8-8.5 55.9L433.1 485.5c-23.4 17.2-51.6 26.5-80.7 26.5L192 512 32 512c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l36.8 0 44.9-36c22.7-18.2 50.9-28 80-28l78.3 0 16 0 64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0-16 0c-8.8 0-16 7.2-16 16s7.2 16 16 16l120.6 0 119.7-88.2c17.8-13.1 42.8-9.3 55.9 8.5zM193.6 384c0 0 0 0 0 0l-.9 0c.3 0 .6 0 .9 0z"/></svg>

                                </div>
                                <span
                                    class="mr-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __("Customer Club") }}</span>
                            </a>
                        </li>
                        <li class="mt-0.5 w-full">
          <span
              class="py-2.7 cursor-pointer text-size-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors hover:shadow-soft-xl hover:rounded-lg hover:bg-white hover:font-semibold hover:text-slate-700">
            <div
                class="bg-gradient-red shadow-soft-2xl me-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5 fill-white">

                <svg xmlns="http://www.w3.org/2000/svg" width="12px" height="12px" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path
                        d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 192 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l210.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128zM160 96c17.7 0 32-14.3 32-32s-14.3-32-32-32L96 32C43 32 0 75 0 128L0 384c0 53 43 96 96 96l64 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-64 0c-17.7 0-32-14.3-32-32l0-256c0-17.7 14.3-32 32-32l64 0z"/></svg>

            </div>
            <livewire:auth.logout/>
          </span>
                        </li>

                    </ul>
                </div>
            </aside>
            <!-- end sidenav -->
