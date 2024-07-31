<div>
    <div class="w-full px-6 mx-auto">
        <div class="relative flex items-center p-0 mt-6 overflow-hidden bg-center bg-cover min-h-75 rounded-2xl"
             style="background-image: url('{{asset("panel/img/curved-images/curved0.jpg")}}'); background-position-y: 50%">
            <span class="absolute inset-y-0 w-full h-full bg-center bg-cover bg-gradient-fuchsia opacity-60"></span>
        </div>
        <div
            class="relative flex flex-col flex-auto min-w-0 p-4 mx-6 -mt-16 overflow-hidden break-words border-0 shadow-blur rounded-2xl bg-white/80 bg-clip-border backdrop-blur-2xl backdrop-saturate-200">
            <div class="flex flex-wrap -mx-3">
                <div class="flex-none w-auto max-w-full px-3">
                    <div
                        class="text-size-base ease-soft-in-out h-18.5 w-18.5 relative inline-flex items-center justify-center rounded-xl text-white transition-all duration-200">
                        <img src="{{ asset("panel/img/bruce-mars.jpg") }}" alt="profile_image" class="w-full shadow-soft-sm rounded-xl" />
                    </div>
                </div>
                <div class="flex-none w-auto max-w-full px-3 my-auto">
                    <div class="h-full">
                        <h5 class="mb-1">{{ auth()->user()->name ?? __("No Name") }}</h5>
                        <p class="mb-0 font-semibold leading-normal text-size-sm">{{ auth()->user()->phone }}</p>
                    </div>
                </div>
                <div class="w-full max-w-full px-3 mx-auto mt-4 sm:my-auto sm:me-0 md:w-1/2 md:flex-none lg:w-4/12">
                    <div class="relative end-0">
                        <ul class="relative flex flex-wrap p-1 list-none bg-transparent rounded-xl" nav-pills id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
                            <li class="z-30 flex-auto text-center" role="presentation">
                                <button class="z-30 flex justify-center items-center gap-1.5 w-full px-0 py-1 mb-0 transition-all border-0 rounded-lg ease-soft-in-out bg-inherit text-slate-700"
                                   nav-link type="button" active id="profile-tab" data-tabs-target="#profile" role="tab" aria-controls="profile" aria-selected="true">
                                    <i class="text-slate-600 fa fa-user"></i>
                                    <span class="ms-1">{{ __("Profile") }}</span>
                                </button>
                            </li>
                            <li class="z-30 flex-auto text-center" role="presentation">
                                <button class="z-30 flex justify-center items-center gap-1.5 w-full px-0 py-1 mb-0 transition-all border-0 rounded-lg ease-soft-in-out bg-inherit text-slate-700"
                                   nav-link type="button" id="address-tab" data-tabs-target="#address" role="tab" aria-controls="address" aria-selected="false">
                                    <i class="text-slate-600 fas fa-map-marker-alt"></i>
                                    <span class="ms-1">{{ __("Address") }}</span>
                                </button>
                            </li>
                            <li class="z-30 flex-auto text-center" role="presentation">
                                <button class="z-30 flex justify-center items-center gap-1.5 w-full px-0 py-1 mb-0 transition-colors border-0 rounded-lg ease-soft-in-out bg-inherit text-slate-700"
                                   nav-link type="button" id="setting-tab" data-tabs-target="#setting" role="tab" aria-controls="setting" aria-selected="false">
                                    <svg class="text-slate-600" width="16px" height="16px" viewBox="0 0 40 40" version="1.1"
                                         xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <title>settings</title>
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g transform="translate(-2020.000000, -442.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                <g transform="translate(1716.000000, 291.000000)">
                                                    <g transform="translate(304.000000, 151.000000)">
                                                        <polygon class="fill-slate-800" opacity="0.596981957"
                                                                 points="18.0883333 15.7316667 11.1783333 8.82166667 13.3333333 6.66666667 6.66666667 0 0 6.66666667 6.66666667 13.3333333 8.82166667 11.1783333 15.315 17.6716667">
                                                        </polygon>
                                                        <path class="fill-slate-800"
                                                              d="M31.5666667,23.2333333 C31.0516667,23.2933333 30.53,23.3333333 30,23.3333333 C29.4916667,23.3333333 28.9866667,23.3033333 28.48,23.245 L22.4116667,30.7433333 L29.9416667,38.2733333 C32.2433333,40.575 35.9733333,40.575 38.275,38.2733333 L38.275,38.2733333 C40.5766667,35.9716667 40.5766667,32.2416667 38.275,29.94 L31.5666667,23.2333333 Z"
                                                              opacity="0.596981957"></path>
                                                        <path class="fill-slate-800"
                                                              d="M33.785,11.285 L28.715,6.215 L34.0616667,0.868333333 C32.82,0.315 31.4483333,0 30,0 C24.4766667,0 20,4.47666667 20,10 C20,10.99 20.1483333,11.9433333 20.4166667,12.8466667 L2.435,27.3966667 C0.95,28.7083333 0.0633333333,30.595 0.00333333333,32.5733333 C-0.0583333333,34.5533333 0.71,36.4916667 2.11,37.89 C3.47,39.2516667 5.27833333,40 7.20166667,40 C9.26666667,40 11.2366667,39.1133333 12.6033333,37.565 L27.1533333,19.5833333 C28.0566667,19.8516667 29.01,20 30,20 C35.5233333,20 40,15.5233333 40,10 C40,8.55166667 39.685,7.18 39.1316667,5.93666667 L33.785,11.285 Z">
                                                        </path>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                    <span class="ms-1">{{ __("Settings") }}</span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full p-6 mx-auto">
        <div class="" id="default-tab-content">
            <div class="w-full max-w-full px-3 lg-max:mt-6" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div
                    class="relative flex flex-col h-full min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                        <div class="flex flex-wrap -mx-3">
                            <div class="flex items-center w-full max-w-full px-3 shrink-0 md:w-8/12 md:flex-none">
                                <h6 class="mb-0">{{__("Profile Information")}}</h6>
                            </div>
                            <div class="w-full max-w-full px-3 text-left shrink-0 md:w-4/12 md:flex-none">
                                <a href="javascript:;" data-target="tooltip_trigger" data-placement="top">
                                    <i class="leading-normal fas fa-user-edit text-size-sm text-slate-400"></i>
                                </a>
                                <div data-target="tooltip"
                                     class="hidden px-2 py-1 text-center text-white bg-black rounded-lg text-size-sm" role="tooltip">
                                    {{__("Edit Profile")}}
                                    <div
                                        class="invisible absolute h-2 w-2 bg-inherit before:visible before:absolute before:h-2 before:w-2 before:rotate-45 before:bg-inherit before:content-['']"
                                        data-popper-arrow></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-auto p-4">
                        <p class="leading-normal text-size-sm">اطلاعات شما در قالی‌شویی سراج کاملاً محرمانه و امن نگهداری می‌شود. اطمینان داشته باشید که حریم خصوصی شما اولویت ماست.
                        </p>
                        <hr class="h-px my-6 bg-transparent bg-gradient-horizontal-light" />
                        <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                            <li
                                class="relative block px-4 py-2 pt-0 pl-0 leading-normal bg-white border-0 rounded-t-lg text-size-sm text-inherit">
                                <strong class="text-slate-700">نام / نام خانوادگی:</strong> &nbsp; مهرداد دیندار</li>
                            <li
                                class="relative block px-4 py-2 pl-0 leading-normal bg-white border-0 border-t-0 text-size-sm text-inherit">
                                <strong class="text-slate-700">شماره تماس:</strong> &nbsp; 09191903665</li>
                            <li
                                class="relative block px-4 py-2 pl-0 leading-normal bg-white border-0 border-t-0 text-size-sm text-inherit">
                                <strong class="text-slate-700">آدرس فعال:</strong> &nbsp; استان قزوین - قزوین - سعدی فرعی شمالی نبش کوچه فرهمند پلاک ۳۹ واحد ۵</li>
                            {{--<li
                                class="relative block px-4 py-2 pl-0 leading-normal bg-white border-0 border-t-0 text-size-sm text-inherit">
                                <strong class="text-slate-700">Location:</strong> &nbsp; USA</li>--}}
                            {{--<li class="relative block px-4 py-2 pb-0 pl-0 bg-white border-0 border-t-0 rounded-b-lg text-inherit">
                                <strong class="leading-normal text-size-sm text-slate-700">Social:</strong> &nbsp;
                                <a class="inline-block py-0 pl-1 pr-2 mb-0 font-bold text-center text-blue-800 align-middle transition-all bg-transparent border-0 rounded-lg shadow-none cursor-pointer leading-pro text-size-xs ease-soft-in bg-none"
                                   href="javascript:;">
                                    <i class="fab fa-facebook fa-lg"></i>
                                </a>
                                <a class="inline-block py-0 pl-1 pr-2 mb-0 font-bold text-center align-middle transition-all bg-transparent border-0 rounded-lg shadow-none cursor-pointer leading-pro text-size-xs ease-soft-in bg-none text-sky-600"
                                   href="javascript:;">
                                    <i class="fab fa-twitter fa-lg"></i>
                                </a>
                                <a class="inline-block py-0 pl-1 pr-2 mb-0 font-bold text-center align-middle transition-all bg-transparent border-0 rounded-lg shadow-none cursor-pointer leading-pro text-size-xs ease-soft-in bg-none text-sky-900"
                                   href="javascript:;">
                                    <i class="fab fa-instagram fa-lg"></i>
                                </a>
                            </li>--}}
                        </ul>
                    </div>
                </div>
            </div>
            <div class="hidden w-full max-w-full px-3 lg-max:mt-6" id="address" role="tabpanel" aria-labelledby="address-tab">
            <div
                class="relative flex flex-col h-full min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                    <h6 class="mb-0">Conversations</h6>
                </div>
                <div class="w-full max-w-full">
                    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                        <livewire:customer.addresses>
                    </div>
                </div>

            </div>
        </div>
            <div class="hidden w-full max-w-full px-3" id="setting" role="tabpanel" aria-labelledby="setting-tab">
                <div
                    class="relative flex flex-col h-full min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                        <h6 class="mb-0">Platform Settings</h6>
                    </div>
                    <div class="flex-auto p-4">
                        <h6 class="font-bold leading-tight uppercase text-size-xs text-slate-500">Account</h6>
                        <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                            <li class="relative block px-0 py-2 bg-white border-0 rounded-t-lg text-inherit">
                                <div class="min-h-6 mb-0.5 block pl-0">
                                    <input id="follow"
                                           class="mt-0.54 rounded-[2.5rem] duration-250 ease-soft-in-out after:rounded-circle after:shadow-soft-2xl after:duration-250 checked:after:-translate-x-5.25 h-5 relative float-left ml-auto w-10 cursor-pointer appearance-none border border-solid border-gray-200 bg-slate-800/10 bg-none bg-contain bg-left bg-no-repeat align-top transition-all after:absolute after:top-px after:h-4 after:w-4 after:translate-x-px after:bg-white after:content-[''] checked:border-slate-800/95 checked:bg-slate-800/95 checked:bg-none checked:bg-right" type="checkbox" checked />
                                    <label for="follow"
                                           class="w-4/5 mb-0 ml-4 overflow-hidden font-normal cursor-pointer select-none text-size-sm text-ellipsis whitespace-nowrap text-slate-500">Email me when someone follows me</label>
                                </div>
                            </li>
                            <li class="relative block px-0 py-2 bg-white border-0 text-inherit">
                                <div class="min-h-6 mb-0.5 block pl-0">
                                    <input id="answer"
                                           class="mt-0.54 rounded-[2.5rem] duration-250 ease-soft-in-out after:rounded-circle after:shadow-soft-2xl after:duration-250 checked:after:-translate-x-5.25 h-5 relative float-left ml-auto w-10 cursor-pointer appearance-none border border-solid border-gray-200 bg-slate-800/10 bg-none bg-contain bg-left bg-no-repeat align-top transition-all after:absolute after:top-px after:h-4 after:w-4 after:translate-x-px after:bg-white after:content-[''] checked:border-slate-800/95 checked:bg-slate-800/95 checked:bg-none checked:bg-right"
                                           type="checkbox" />
                                    <label for="answer"
                                           class="w-4/5 mb-0 ml-4 overflow-hidden font-normal cursor-pointer select-none text-size-sm text-ellipsis whitespace-nowrap text-slate-500"
                                           for="flexSwitchCheckDefault1">Email me when someone answers on my post</label>
                                </div>
                            </li>
                            <li class="relative block px-0 py-2 bg-white border-0 rounded-b-lg text-inherit">
                                <div class="min-h-6 mb-0.5 block pl-0">
                                    <input id="mention"
                                           class="mt-0.54 rounded-[2.5rem] duration-250 ease-soft-in-out after:rounded-circle after:shadow-soft-2xl after:duration-250 checked:after:-translate-x-5.25 h-5 relative float-left ml-auto w-10 cursor-pointer appearance-none border border-solid border-gray-200 bg-slate-800/10 bg-none bg-contain bg-left bg-no-repeat align-top transition-all after:absolute after:top-px after:h-4 after:w-4 after:translate-x-px after:bg-white after:content-[''] checked:border-slate-800/95 checked:bg-slate-800/95 checked:bg-none checked:bg-right"
                                           type="checkbox" checked />
                                    <label for="mention"
                                           class="w-4/5 mb-0 ml-4 overflow-hidden font-normal cursor-pointer select-none text-size-sm text-ellipsis whitespace-nowrap text-slate-500"
                                           for="flexSwitchCheckDefault2">Email me when someone mentions me</label>
                                </div>
                            </li>
                        </ul>
                        <h6 class="mt-6 font-bold leading-tight uppercase text-size-xs text-slate-500">Application</h6>
                        <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                            <li class="relative block px-0 py-2 bg-white border-0 rounded-t-lg text-inherit">
                                <div class="min-h-6 mb-0.5 block pl-0">
                                    <input id="launches projects"
                                           class="mt-0.54 rounded-[2.5rem] duration-250 ease-soft-in-out after:rounded-circle after:shadow-soft-2xl after:duration-250 checked:after:translate-x-5.25 h-5-em relative float-left ml-auto w-10 cursor-pointer appearance-none border border-solid border-gray-200 bg-slate-800/10 bg-none bg-contain bg-left bg-no-repeat align-top transition-all after:absolute after:top-px after:h-4 after:w-4 after:translate-x-px after:bg-white after:content-[''] checked:border-slate-800/95 checked:bg-slate-800/95 checked:bg-none checked:bg-right"
                                           type="checkbox" />
                                    <label for="launches projects"
                                           class="w-4/5 mb-0 ml-4 overflow-hidden font-normal cursor-pointer select-none text-size-sm text-ellipsis whitespace-nowrap text-slate-500"
                                           for="flexSwitchCheckDefault3">New launches and projects</label>
                                </div>
                            </li>
                            <li class="relative block px-0 py-2 bg-white border-0 text-inherit">
                                <div class="min-h-6 mb-0.5 block pl-0">
                                    <input id="product updates"
                                           class="mt-0.54 rounded-[2.5rem] duration-250 ease-soft-in-out after:rounded-circle after:shadow-soft-2xl after:duration-250 checked:after:translate-x-5.25 h-5-em relative float-left ml-auto w-10 cursor-pointer appearance-none border border-solid border-gray-200 bg-slate-800/10 bg-none bg-contain bg-left bg-no-repeat align-top transition-all after:absolute after:top-px after:h-4 after:w-4 after:translate-x-px after:bg-white after:content-[''] checked:border-slate-800/95 checked:bg-slate-800/95 checked:bg-none checked:bg-right"
                                           type="checkbox" checked />
                                    <label for="product updates"
                                           class="w-4/5 mb-0 ml-4 overflow-hidden font-normal cursor-pointer select-none text-size-sm text-ellipsis whitespace-nowrap text-slate-500"
                                           for="flexSwitchCheckDefault4">Monthly product updates</label>
                                </div>
                            </li>
                            <li class="relative block px-0 py-2 pb-0 bg-white border-0 rounded-b-lg text-inherit">
                                <div class="min-h-6 mb-0.5 block pl-0">
                                    <input id="subscribe"
                                           class="mt-0.54 rounded-[2.5rem] duration-250 ease-soft-in-out after:rounded-circle after:shadow-soft-2xl after:duration-250 checked:after:translate-x-5.25 h-5-em relative float-left ml-auto w-10 cursor-pointer appearance-none border border-solid border-gray-200 bg-slate-800/10 bg-none bg-contain bg-left bg-no-repeat align-top transition-all after:absolute after:top-px after:h-4 after:w-4 after:translate-x-px after:bg-white after:content-[''] checked:border-slate-800/95 checked:bg-slate-800/95 checked:bg-none checked:bg-right"
                                           type="checkbox" />
                                    <label for="subscribe"
                                           class="w-4/5 mb-0 ml-4 overflow-hidden font-normal cursor-pointer select-none text-size-sm text-ellipsis whitespace-nowrap text-slate-500"
                                           for="flexSwitchCheckDefault5">Subscribe to newsletter</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const tabButtons = document.querySelectorAll('[data-tabs-target]');
            const tabContents = document.querySelectorAll('[role="tabpanel"]');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const target = document.querySelector(button.dataset.tabsTarget);

                    tabContents.forEach(tabContent => {
                        tabContent.classList.add('hidden');
                    });

                    tabButtons.forEach(button => {
                        button.setAttribute('aria-selected', 'false');
                    });

                    target.classList.remove('hidden');
                    button.setAttribute('aria-selected', 'true');
                });
            });
        });
        new DataTable('#datatable-search');
    </script>

</div>
