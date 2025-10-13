<div class="intro-y box mt-2 bg-slate-200 rounded-none border-none px-2 pb-2">
    <div class="flex flex-col items-center border-b border-slate-200/60 px-2 py-3 dark:border-darkmode-400 sm:flex-row">
        <h2 class="mr-auto text-base font-medium">
            Job Details & Documents
        </h2>
    </div>
    <div class="px-2 py-3 jobSheetWrap bg-white">
        <a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer jobSheetBlock">
            <div>
                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-1 uppercase theLabel">Job Sheet</div>
                <div class="theDesc">0/9</div>
            </div>
            <span style="flex: 0 0 16px; margin-left: 20px;"><x-base.lucide class="h-4 w-4 text-success" icon="chevron-right" /></span>
        </a>
    </div>
    <div class="px-2 py-3 mt-2 jobSheetDocWrap bg-white">
        <a href="javascript:void(0);" class="flex justify-between items-center cursor-pointer jobSheetDocBlock">
            <div class="w-full">
                <div class="text-slate-500 mt-1 font-medium text-xs leading-none mb-2 uppercase theLabel">Documents</div>
                <div class="theDesc">
                    <div class="customeUploads border-2 border-dashed border-slate-500 w-full flex items-center text-center rounded-none p-[20px]">
                        <label for="job_sheet_files" class="text-center upload-message my-[1em] relative w-full cursor-pointer">
                            <div class="customeUploadsContent">
                                <span class="text-lg font-medium">
                                    Drop files here or click to upload.
                                </span><br/>
                                <span class="text-gray-600 hiddenBr">
                                    Upload maximum 10 files. Allowed file types are<br/>
                                    images, excel, document, & PDF.
                                </span><br/>
                            </div>
                            <div id="uploadedFileWrap" class="flex flex-wrap justify-center items-center" style="display: none;"></div>
                        </label>
                        <input type="file" multiple id="job_sheet_files" name="job_sheet_files[]" accept="image/*,.pdf,.xl,.xlsx,.xls,.doc,.docx,.txt,.ppt,.pptx" class="w-0 h-0 opacity-0 absolute left-0 top-0"/>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>