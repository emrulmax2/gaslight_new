<!-- BEGIN: Modal Content -->
<x-base.dialog id="addnew-modal"
size="xl"
staticBackdrop
>
    <x-base.dialog.panel>
        <a
            class="absolute right-0 top-0 mr-3 mt-3"
            data-tw-dismiss="modal"
            href="#"
        >
            <x-base.lucide
                class="h-8 w-8 text-slate-400"
                icon="X"
            />
        </a>
        {{-- <div class="absolute left-0 top-0 ml-3 mt-3  bg-primary text-white text-sm font-semibold px-2 py-1 rounded">
            Step <span class="current-step"> 01 </span> of <span class="total-step">02</span>
        </div> --}}
        <div class="p-5 text-center">
            <x-base.lucide
                class="mx-auto mt-3 h-8 w-8 text-primary"
                icon="CheckCircle"
            />
            <div id="titleModal" class="mt-5 text-2xl">Add An Engineer</div>
        </div>
        <div id="base-start" class=" flex flex-col px-16 pt-5 pb-16">
            <form method="POST" action="{{ route('engineer.store') }}" id="addEngineerForm" enctype="multipart/form-data">
                @csrf
            <div>
                <x-base.form-label>Email*</x-base.form-label>
                        <x-base.form-input id="email"
                            class="block rounded-[0.6rem] border-slate-300/80 px-4 py-3.5 login__input"
                            type="text"
                            placeholder="x@y.Z"
                        />
                        <div id="error-email" class="login__input-error text-danger mt-2 dark:text-orange-400 "></div>        
                        <x-base.form-label class="mt-4">Full Name*</x-base.form-label>
                        <x-base.form-input id="name"
                            class="block rounded-[0.6rem] border-slate-300/80 px-4 py-3.5"
                            type="text"
                            placeholder="John Doe"

                        />
                        <div id="error-name" class="login__input-error text-danger mt-2 dark:text-orange-400"></div>

                        <div class="mb-4">
                            <x-base.form-label class="mt-4">Signature*</x-base.form-label>
                            <x-creagia-signature-pad name='sign' 
                                border-color="#eaeaea"
                                submit-name="Save"
                            />
                        </div>
                            
            </div>
            </form>
            
            {{-- <div class="mt-6 flex border-t border-dashed border-slate-300/70 pt-5 md:justify-end ">
                <x-base.button
                    class="w-full border-primary/50 px-4 md:w-auto"
                    variant="outline-primary"
                    id="btn-step1"
                    type="submit"
                >
                    <span class="step1-text">Save and Exit</span>  <x-base.loading-icon
                                                        class="h-6 w-6 hidden step1__loading"
                                                        color="#475569"
                                                        icon="oval"
                                                    />
                </x-base.button>
            </div> --}}
        </div>

    </x-base.dialog.panel>
</x-base.dialog>
<!-- END: Modal Content -->