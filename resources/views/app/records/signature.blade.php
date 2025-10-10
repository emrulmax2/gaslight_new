@php 
    $inspectionDeate = date('d-m-Y');
    $nextInspectionDate = date('d-m-Y', strtotime('+1 year', strtotime($inspectionDeate)));
@endphp
<div class="intro-y box mt-2 bg-slate-200 rounded-none border-none px-2 pb-2">
    <div class="flex flex-col items-center border-b border-slate-200/60 px-2 py-3 dark:border-darkmode-400 sm:flex-row">
        <h2 class="mr-auto text-base font-medium">
            Signature
        </h2>
    </div>
    <div class="px-2 py-3 bg-white">
        <div class="flex justify-between items-center cursor-pointer todaysDateBlock">
            <div class="w-full">
                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Todays Date</div>
                <div class="theDesc w-full relative">
                    <x-base.litepicker id="inspection_date" name="inspection_date" value="{{ $inspectionDeate }}" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text" data-format="DD-MM-YYYY" data-single-mode="true" autocomplete="off"/>
                    <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="calendar" />
                </div>
            </div>
        </div>
    </div>
    <div class="px-2 py-3 bg-white mt-2">
        <div class="flex justify-between items-center cursor-pointer nextDateBlock">
            <div class="w-full">
                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Next Inspection Date</div>
                <div class="theDesc w-full relative">
                    <x-base.litepicker id="next_inspection_date" name="next_inspection_date" value="{{ $nextInspectionDate }}" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text" data-format="DD-MM-YYYY" data-single-mode="true" autocomplete="off"/>
                    <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="calendar" />
                </div>
            </div>
        </div>
    </div>
    <div class="px-2 py-3 bg-white mt-2">
        <div class="flex justify-between items-center cursor-pointer receivedByBlock">
            <div class="w-full">
                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Received By</div>
                <div class="theDesc w-full relative">
                    <x-base.form-input id="received_by" name="received_by" value="" placeholder="Mr. John Doe" class="w-full text-[14px] leading-[20px] text-[#475569] block p-0 border-none rounded-none focus:outline-none focus:shadow-none focus:ring-0 bg-transparent shadow-none" type="text" data-format="DD-MM-YYYY" data-single-mode="true" autocomplete="off"/>
                    <x-base.lucide class="h-4 w-4 text-success absolute right-0 top-0" icon="user" />
                </div>
            </div>
        </div>
    </div>
    <div class="px-2 py-3 relationWrap bg-white mt-2">
        <a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer relationBlock">
            <div>
                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Relation</div>
                <div class="theDesc">N/A</div>
            </div>
            <span style="flex: 0 0 16px; margin-left: 20px;"><x-base.lucide class="h-4 w-4 text-success" icon="chevron-right" /></span>
            <input type="hidden" id="relation_id" name="relation_id" value="0" class="theId"/>
        </a>
    </div>
    <div class="px-2 py-3 signatureImgWrap bg-white mt-2" style="display: none;">
        <a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer signatureImgBlock">
            <div>
                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Existing Signature</div>
                <div class="theDesc">
                    <img src="" alt="Existing Signature" class="theSignature h-[60px] w-auto inline-block"/>
                </div>
            </div>
            <span style="flex: 0 0 16px; margin-left: 20px;"></span>
        </a>
    </div>

    <div class="px-2 py-3 bg-white mt-2">
        <div class="flex justify-between items-center signatureBlock">
            <div class="w-full">
                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Signature</div>
                <div class="theDesc w-full relative">
                    <div class="gsfSignature border rounded-none h-auto pt-5 bg-slate-100 rounded-b-none flex justify-center items-center">
                        <x-creagia-signature-pad name='sign'
                            border-color="#e5e7eb"
                            submit-name="Save"
                            clear-name="Clear Signature"
                            submit-id="signSaveBtn"
                            clear-id="clear"
                            pad-classes="w-auto h-48 bg-white mt-0"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>