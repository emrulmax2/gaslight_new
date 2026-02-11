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
            {{-- @if($previousYear)
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
            @endif --}}
        </div>
    </div>

    <!-- BEGIN: HTML Table Data -->
    <div class="intro-y mt-5">
        <x-base.table class="border-separate border-spacing-y-[10px]" id="monthlyReminderTable">
            <x-base.table.thead>
                <x-base.table.tr>
                    <x-base.table.th class="whitespace-nowrap uppercase border-b-0 pt-0">
                        Certificate
                    </x-base.table.th>
                    <x-base.table.th class="whitespace-nowrap uppercase border-b-0 pt-0">
                        Customer
                    </x-base.table.th>
                    <x-base.table.th class="whitespace-nowrap uppercase border-b-0 pt-0">
                        Expiry
                    </x-base.table.th>
                    <x-base.table.th class="whitespace-nowrap uppercase border-b-0 pt-0">
                        Email Status
                    </x-base.table.th>
                    <x-base.table.th class="whitespace-nowrap uppercase border-b-0 text-right pt-0">
                        Action
                    </x-base.table.th>
                </x-base.table.tr>
            </x-base.table.thead>
            <x-base.table.tbody>
                @if($result['count'] > 0)
                    @foreach($result['data'] as $row)
                    <x-base.table.tr class="intro-x cursor-pointer zoom-in">
                        <x-base.table.td class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                            <span class="whitespace-nowrap font-medium" >
                                {{ $row['form']->name }}
                            </span>
                        </x-base.table.td>
                        <x-base.table.td class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                            <a class="whitespace-nowrap font-medium" href="javascript:void(0);" >
                                {{ $row['customer']->full_name }}
                            </a>
                            <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
                                {{ $row['job_address'] }}
                            </div>
                        </x-base.table.td>
                        <x-base.table.td class="{{ $row['next_inspection_date'] && $row['next_inspection_date'] < date('Y-m-d') ? 'text-danger' : '' }} font-medium box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                            {{ ($row['next_inspection_date'] ? date('jS F, Y', strtotime($row['next_inspection_date'])) : '') }}
                        </x-base.table.td>
                        <x-base.table.td class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                            {!! ($row['email_sent'] ? '<span class="text-xs whitespace-nowrap font-bold bg-success text-white px-2 py-0.5">Sent</span>' : '<span class="text-xs whitespace-nowrap font-bold bg-danger text-white px-2 py-0.5">Not Yet</span>') !!}
                            @if($row['email_sent'] && $row['email_sent_date'])
                            <div class="mt-1 whitespace-nowrap font-medium text-xs text-slate-500">
                                {{ ($row['email_sent_date'] ? date('jS F, Y', strtotime($row['email_sent_date'])) : '') }}
                            </div>
                            @endif
                        </x-base.table.td>
                        <x-base.table.td @class([ 'box w-56 rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600', 'before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 before:dark:bg-darkmode-400',])>
                            <div class="flex items-center justify-end">
                                <x-base.button data-id="{{ $row['id'] }}" size="sm" class="w-auto text-white sendReminderMailBtn" type="button" variant="success">
                                    <x-base.lucide class="mr-2 h-4 w-4" icon="mail" />
                                    {{ $row['email_sent'] ? 'Resend' : 'Send' }}
                                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                                </x-base.button>
                            </div>
                        </x-base.table.td>
                    </x-base.table.tr>
                    @endforeach
                @else 
                    <x-base.table.tr class="intro-x cursor-pointer zoom-in">
                        <x-base.table.td colspan="5" class="box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600">
                            <x-base.alert class="m-0 flex items-center" variant="soft-warning" >
                                <x-base.lucide class="mr-2 h-6 w-6" icon="AlertCircle" />
                                Data not found.
                            </x-base.alert>
                        </x-base.table.td>
                    </x-base.table.tr>
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
    @vite('resources/js/app/records/inspection-show.js')
@endPushOnce