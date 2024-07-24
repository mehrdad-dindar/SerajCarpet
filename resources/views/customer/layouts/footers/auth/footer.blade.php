<footer class="pt-4">
    <div class="w-full px-6 mx-auto">
        <div class="flex flex-wrap items-center -mx-3 lg:justify-between">
            <div class="w-full max-w-full px-3 mt-0 mb-6 shrink-0 lg:mb-0 lg:w-1/2 lg:flex-none">
                <div class="leading-normal text-center text-size-sm text-slate-500 lg:text-right">
                    Copyright ©
                    <script>
                        document.write(new Date().getFullYear() + " - " + {{verta(now())->format("Y")}});
                    </script>
                    تمامی حقوق برای <a class="font-semibold text-transparent bg-gradient-cyan bg-clip-text" href="https://serajcarpet.com" target="_blank">قالیشویی سراج</a> محفوظ است.
                    {{ __("made with")}} <i class="fa fa-heart"></i>
                </div>
            </div>
            <div class="w-full max-w-full px-3 mt-0 shrink-0 lg:w-1/2 lg:flex-none">
                <ul class="flex flex-wrap justify-center pl-0 mb-0 list-none lg:justify-end">
                    <li class="nav-item">
                        <a href="https://www.creative-tim.com"
                            class="block px-4 pt-0 pb-1 font-normal transition-colors ease-soft-in-out text-size-sm text-slate-500"
                            target="_blank">{{ __("Company") }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="https://updivision.com"
                            class="block px-4 pt-0 pb-1 font-normal transition-colors ease-soft-in-out text-size-sm text-slate-500"
                            target="_blank">{{ __("About Us") }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="https://www.creative-tim.com/presentation"
                            class="block px-4 pt-0 pb-1 font-normal transition-colors ease-soft-in-out text-size-sm text-slate-500"
                            target="_blank">{{ __("Services") }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="https://creative-tim.com/blog"
                            class="block px-4 pt-0 pb-1 font-normal transition-colors ease-soft-in-out text-size-sm text-slate-500"
                            target="_blank">{{ __("Blog") }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="https://www.creative-tim.com/license"
                            class="block px-4 pt-0 pb-1 pr-0 font-normal transition-colors ease-soft-in-out text-size-sm text-slate-500"
                            target="_blank">{{ __("Pricing") }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>
