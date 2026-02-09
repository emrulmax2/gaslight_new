@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">Upcoming Inspections</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
            @if($previousYear)
            <x-base.button as="a" href="{{ route('upcoming.inspection', ['year' => $previousYear]) }}" class="ml-2 shadow-md" variant="facebook">
                <x-base.lucide
                    class="mr-2 hidden h-4 w-4 sm:block"
                    icon="move-left"
                />
                Prev Year
            </x-base.button>
            @endif
            @if($nextYear)
            <x-base.button as="a" href="{{ route('upcoming.inspection', ['year' => $nextYear]) }}" class="ml-2 shadow-md" variant="facebook">
                Next Year
                <x-base.lucide
                    class="ml-2 hidden h-4 w-4 sm:block"
                    icon="move-right"
                />
            </x-base.button>
            @endif
        </div>
    </div>

    <!-- BEGIN: HTML Table Data -->
    <div class="intro-y mt-5">
        <x-base.table class="border-separate border-spacing-y-[10px]">
            <!-- <x-base.table.thead>
                <x-base.table.tr>
                    <x-base.table.th class="whitespace-nowrap border-b-0">
                        MONTHS
                    </x-base.table.th>
                    <x-base.table.th class="whitespace-nowrap border-b-0 text-center">
                        COUNTS
                    </x-base.table.th>
                </x-base.table.tr>
            </x-base.table.thead> -->
            <x-base.table.tbody>
                @php 
                    $upcomingMonths = get_due_inspection_counts($year);
                @endphp
                @if(!empty($upcomingMonths))
                    @foreach($upcomingMonths as $m)
                        <x-base.table.tr class="intro-x cursor-pointer zoom-in">
                            <x-base.table.td class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                                <span class="whitespace-nowrap font-medium" >
                                    {{ $m['month'] }}
                                </span>
                            </x-base.table.td>
                            <x-base.table.td @class([ 'box w-56 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600', 'before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 before:dark:bg-darkmode-400',])>
                                <div class="flex items-center justify-center">
                                    <span class="text-success font-medium">{{ $m['count'] }}</span>
                                </div>
                            </x-base.table.td>
                        </x-base.table.tr>
                    @endforeach
                @endif
            </x-base.table.tbody>
        </x-base.table>
    </div>
    <!-- END: HTML Table Data -->

    @include('app.action-modals')
@endsection

@pushOnce('styles')
    @vite('resources/css/vendors/full-calendar.css')
@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/axios.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/calendar/calendar.js')
    @vite('resources/js/vendors/calendar/plugins/interaction.js')
@endPushOnce

@pushOnce('scripts')
    @vite('resources/js/app/records/inspection.js')
@endPushOnce