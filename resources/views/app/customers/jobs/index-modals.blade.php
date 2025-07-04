<!-- BEGIN: Calender Modal Content -->
<x-base.dialog id="addJobCalenderModal" staticBackdrop>
    <x-base.dialog.panel>
        <form method="post" action="#" id="addJobCalenderForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium">Add To Calander</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description >
                <x-base.form-input name="customer_job_id" class="w-full" type="hidden" value="0" />
                <div class="mb-4">
                    <x-base.form-label for="date">Date <span class="text-danger">*</span></x-base.form-label>
                    <x-base.litepicker name="date" id="date" class="mx-auto block w-full" data-single-mode="true" data-format="DD-MM-YYYY" />
                    <div class="acc__input-error error-city text-danger text-xs mt-1"></div>
                </div>
                <div>
                    <x-base.form-label for="slot">Slot <span class="text-danger">*</span></x-base.form-label>
                    @if($slots->count() > 0)
                        @foreach($slots as $slot)
                            <x-base.form-check class="mt-{{ $loop->first ? '0' : '2' }}">
                                <x-base.form-check.input id="slot-{{ $slot->id }}" name="calendar_time_slot_id" type="radio" value="{{ $slot->id }}" />
                                <x-base.form-check.label for="slot-{{ $slot->id }}">{{ $slot->title.' '.(!empty($slot->start) ? date('H:i', strtotime($slot->start)) : '').' - '.(!empty($slot->end) ? date('H:i', strtotime($slot->end)) : '') }} </x-base.form-check.label>
                            </x-base.form-check>
                        @endforeach
                    @endif
                    <div class="acc__input-error error-city text-danger text-xs mt-1"></div>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="addCalendarBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Add To Calendar
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Calender Modal Content -->
<!-- BEGIN: Status update Modal Content -->
<x-base.dialog id="statusUpdateModal" staticBackdrop size="sm">
    <x-base.dialog.panel class="rounded-none">
        <form method="post" action="#" id="statusUpdateForm">
            <x-base.dialog.title>
                <h2 class="mr-auto text-base font-medium modalTitle">Update Job Status?</h2>
                <a class="absolute right-0 top-0 mr-3 mt-3" data-tw-dismiss="modal" href="#" ><x-base.lucide class="h-6 w-6 text-slate-400" icon="X" /></a>
            </x-base.dialog.title>
            <x-base.dialog.description class="px-5 py-2 bg-slate-100">
                <div class="bg-white">
                    <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                        <x-base.form-check.label class="font-medium ml-0 block w-full" for="status_due">Due</x-base.form-check.label>
                        <x-base.form-check.input id="status_due" name="status" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Due"/>
                    </x-base.form-check>
                    <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                        <x-base.form-check.label class="font-medium ml-0 block w-full" for="status_completed">Completed</x-base.form-check.label>
                        <x-base.form-check.input id="status_completed" name="status" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Completed"/>
                    </x-base.form-check>
                    <x-base.form-check class="cursor-pointer font-medium ml-0 px-2 py-1.5 relative border-b">
                        <x-base.form-check.label class="font-medium ml-0 block w-full" for="status_cancelled">Cancelled</x-base.form-check.label>
                        <x-base.form-check.input id="status_cancelled" name="status" class="absolute right-2 top-0 bottom-0 my-auto" type="radio" value="Cancelled"/>
                    </x-base.form-check>
                </div>
                <div class="acc__input-error error-fieldValue text-danger text-xs"></div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <x-base.button class="mr-1 w-20" data-tw-dismiss="modal" type="button" variant="outline-secondary" ><x-base.lucide class="mr-2 h-4 w-4" icon="x-circle" />Cancel </x-base.button>
                <x-base.button class="w-auto" id="updateStatusBtn" type="submit" variant="primary">
                    <x-base.lucide class="mr-2 h-4 w-4" icon="check-circle" />
                    Update
                    <x-base.loading-icon style="display: none;" class="ml-2 h-4 w-4 theLoader" color="#FFFFFF" icon="oval" />
                </x-base.button>
                <input type="hidden" name="customer_job_id" id="customer_job_id" value="" />
            </x-base.dialog.footer>
        </form>
    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Status update Modal Content -->