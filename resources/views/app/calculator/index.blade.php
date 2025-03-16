@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">Calculator</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin" >
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <!-- BEGIN: HTML Table Data -->
    <div class="intro-y box mt-5 p-3 sm:p-5">
        <div class="flex flex-col items-center justify-center">
            <h1 class="text-3xl font-bold mb-4 sm:mb-8">Gas Rate Calculator</h1>
            <div class="controls mb-4 sm:mb-8 flex space-x-4">
                <div>
                    <x-base.tom-select class="w-36 sm:w-56" id="type" name="type" data-placeholder="Please Select">
                        <option value="natural_gas">Natural Gas</option>
                        <option value="lpg">LPG</option>
                    </x-base.tom-select>
                </div>
                <div>
                    <x-base.tom-select class="w-36 sm:w-56" id="measurement" name="measurement" data-placeholder="Please Select">
                        <option value="metric">Metric</option>
                        <option value="imperial">Imperial</option>
                    </x-base.tom-select>
                </div>
            </div>
            <div class="flex space-x-4 mb-4 sm:mb-8" id="reading_input">
                <div class="">
                    <x-base.form-input step="any" name="initial_reading" id="initial_reading" class="w-36 sm:w-56" type="number" placeholder="Initial Reading" />
                </div>
                <div class="">
                    <x-base.form-input step="any" name="final_reading" id="final_reading" class="w-36 sm:w-56 hidden" type="number" placeholder="Final Reading" />
                </div>
            </div>
            <div class="buttons flex mb-8">
                <x-base.button id="reset" class="mb-2 mr-2 sm:mr-3 w-24 text-white" variant="warning">Reset</x-base.button>
                <x-base.button id="start" class="mb-2 mr-2 sm:mr-3 w-24 text-white" variant="success">Start</x-base.button>
                <x-base.button id="calculate" class="mb-2 mr-1 w-24 text-white hidden" variant="primary">Calculate</x-base.button>
            </div>
            <div class="base-timer relative w-60 h-60 mb-8">
                <svg class="base-timer__svg w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <g class="base-timer__circle">
                        <circle class="base-timer__path-elapsed stroke-gray-300 stroke-[5px] fill-none" cx="50" cy="50" r="45"></circle>
                        <path id="base-timer-path-remaining" 
                              stroke-dasharray="283" 
                              class="base-timer__path-remaining arc stroke-[5px] stroke-cyan-900 fill-none"
                              d="M 50, 50 m -45, 0 a 45,45 0 1,0 90,0 a 45,45 0 1,0 -90,0">
                        </path>
                    </g>
                </svg>
                <div id="base-timer-label" class="base-timer__label absolute inset-0 flex items-center justify-center text-4xl font-bold">
                    <span class="imperial_timer">0:00</span>
                    <x-base.form-select id="metric_timer" class="w-24 hidden" data-placeholder="Please Select">
                        <option value="60">01.00</option>
                        <option value="120">02.00</option>
                    </x-base.form-select>
                    <span class="timeLabel"></span>
                </div>
            </div>
         
            <div class="results flex sm:flex-row justify-center gap-2 sm:gap-8 mb-8 w-full">
                <div class="text-center p-2 sm:p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 w-full sm:w-64 box">
                    <div class="mb-4">
                        <x-base.lucide class="mx-auto transform hover:scale-105 transition-transform duration-300 h-[30px] w-[30px] sm:h-[50px] sm:w-[50px]"  icon="flame" />
                    </div>
                    <div class="space-y-2">
                        <p class="text-gray-700 font-medium">GAS RATE
                            <span class="text-gray-500 text-sm block">M<sup>3</sup>/HR</span>
                        </p>
                        <p id="gas_rate" class="text-lg sm:text-3xl font-bold text-gray-800">0.00</p>
                    </div>
                </div>
            
                <div class="text-center p-2 sm:p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 w-full sm:w-64 box">
                    <div class="mb-4">
                        <x-base.lucide class="mx-auto transform hover:scale-105 transition-transform duration-300 h-[30px] w-[30px] sm:h-[50px] sm:w-[50px]"  icon="plus-circle" />
                    </div>
                    <div class="space-y-2">
                        <p class="text-gray-700 font-medium">GROSS
                            <span class="text-gray-500 text-sm block">KW</span>
                        </p>
                        <p id="gross" class="text-lg sm:text-3xl font-bold text-gray-800">0.00</p>
                    </div>
                </div>
            
                <div class="text-center p-2 sm:p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 w-full sm:w-64 box">
                    <div class="mb-4">
                        <x-base.lucide class="mx-auto transform hover:scale-105 transition-transform duration-300 h-[30px] w-[30px] sm:h-[50px] sm:w-[50px]"  icon="minus-circle" />
                    </div>
                    <div class="space-y-2">
                        <p class="text-gray-700 font-medium">NET
                            <span class="text-gray-500 text-sm block">KW</span>
                        </p>
                        <p id="net" class="text-lg sm:text-3xl font-bold text-gray-800">0.00</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: HTML Table Data -->

    @include('app.action-modals')
@endsection

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/calendar/calendar.js')
    @vite('resources/js/vendors/calendar/plugins/interaction.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/calculator/calculator.js')
@endPushOnce


   
    