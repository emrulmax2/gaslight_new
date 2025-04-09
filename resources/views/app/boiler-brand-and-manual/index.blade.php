@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>Dashboard - Midone - Tailwind Admin Dashboard Template</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">Search By Brand</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>
    <div class="intro-y box mt-5 p-0 border-none">
        <x-base.tom-select id="boiler-brand-select" name="role" class="col-span-12 block rounded-[1rem]  px-2 py-1.5 boiler-brand-select border-none" data-placeholder="Please Select your Brand" >
            <option value="">Please Select</option>
            @foreach($boilerBrands as $brand) 
                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
            @endforeach
        </x-base.tom-select>
    </div>

    <!-- BEGIN: HTML Table Data -->
    <div class="intro-y box mt-5 p-5 col-span-12 brandContainer hidden">
        <!--Implement a search method that get container-->
        <div id="search-box" class="hidden">
            <x-base.form-input class="mt-2 sm:mt-0 w-full" id="text-search" type="text" placeholder="Search..."/>
        </div>
        <!-- Alphabetic Index -->
        <div class="mt-5 boiler-brands-container"></div>
        <!-- Boiler Brands -->
    </div>
    <!-- END: HTML Table Data -->
@endsection

@pushOnce('styles')
    @vite('resources/css/vendors/tabulator.css')
@endPushOnce

@pushOnce('vendors')
    @vite('resources/js/vendors/tabulator.js')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/xlsx.js')
    @vite('resources/js/vendors/sign-pad.min.js')
    @vite('resources/js/vendors/axios.js')
@endPushOnce

@pushOnce('scripts')
<script type="module">
    const boilerBrandSelect = document.getElementById('boiler-brand-select');
    const brandContainer = document.querySelector('.brandContainer');
    const boilerBrandsContainer = document.querySelector('.boiler-brands-container');
    
    brandContainer.classList.add('hidden');

    boilerBrandSelect.addEventListener('change', function() {
        const brandId = this.value;
        if (brandId) {
            axios({
                method: "get",
                url: route('boiler-manuals.show', brandId),
                headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
            }).then(response => {
                boilerBrandsContainer.innerHTML = '';
                const boilerBrandsManual = response.data;

                if (boilerBrandsManual.length === 0) {
                    boilerBrandsContainer.innerHTML = `
                        <div class="col-span-12">
                            <div class="intro-y flex h-auto sm:h-10 items-center">
                                <h2 class="mr-5 truncate text-lg font-medium">No Manual Found</h2>
                            </div>
                        </div>
                    `;
                } else {
                    boilerBrandsManual.forEach(manual => {
                        $('#search-box').removeClass('hidden');
                        let theDownloadBtn = '';
                        if(manual.pdf_url != ''){
                            theDownloadBtn = '<a target="_blank" href="'+manual.pdf_url+'" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&amp;:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-success border-success bg-opacity-20 border-opacity-5 text-success dark:border-success dark:border-opacity-20 [&amp;:hover:not(:disabled)]:bg-opacity-10 [&amp;:hover:not(:disabled)]:border-opacity-10 w-auto">\
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="cloud-download" class="lucide lucide-cloud-download stroke-1.5 mr-2 h-4 w-4"><path d="M12 13v8l-4-4"></path><path d="m12 21 4-4"></path><path d="M4.393 15.269A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.436 8.284"></path></svg>\
                                                Download Manual</a>';
                        }
                        boilerBrandsContainer.innerHTML += `
                            <div id="${manual.model}" class="containerItems col-span-12 sm:col-span-6 xl:col-span-4 mb-3">
                                <div class="intro-y box p-5">
                                    <div class="grid grid-cols-12 gap-x-4 gap-y-2">
                                        <div class="col-span-12 sm:col-span-3">
                                            <div class="flex justify-start items-start">
                                                <span class="bg-success bg-opacity-10 inline-flex w-[45px] h-[45px] rounded-full items-center justify-center mr-3" style="flex: 0 0 auto;">
                                                    <x-base.lucide class="h-5 w-5 text-success" icon="calendar-days" />
                                                </span>
                                                <div>
                                                    <div class="text-gray-500 text-xs">Year: ${manual.year_of_manufacture}</div>
                                                    <div class="model-name font-medium text-base">${manual.model}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-3">
                                            <div class="flex justify-start items-start">
                                                <span class="bg-success bg-opacity-10 inline-flex w-[45px] h-[45px] rounded-full items-center justify-center mr-3" style="flex: 0 0 auto;">
                                                    <x-base.lucide class="h-5 w-5 text-success" icon="check-circle" />
                                                </span>
                                                <div>
                                                    <div class="text-gray-600 text-xs">Fuel Type</div>
                                                    <div class="text-base font-medium">${manual.fuel_type}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-3">
                                            <div class="flex justify-start items-start">
                                                <span class="bg-success bg-opacity-10 inline-flex w-[45px] h-[45px] rounded-full items-center justify-center mr-3" style="flex: 0 0 auto;">
                                                    <x-base.lucide class="h-5 w-5 text-success" icon="check-circle" />
                                                </span>
                                                <div>
                                                    <div class="text-gray-600 text-xs">GC No</div>
                                                    <div class="text-base font-medium">${manual.gc_no}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-3 text-right">
                                            ${theDownloadBtn}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }
                brandContainer.classList.remove('hidden');

                createIcons({
                    icons,
                    attrs: {
                        "stroke-width": 1.5,
                    },
                    nameAttr: "data-lucide",
                });
            })
            .catch(error => {
                console.error('There was an error fetching the data!', error);
                brandContainer.classList.add('hidden');
            });
        } else {
            brandContainer.classList.add('hidden');
            $('#search-box').addClass('hidden');
        }
    });

    const textSearch = document.getElementById('text-search');
    textSearch.addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        const boilerBrands = document.querySelectorAll('.boiler-brands-container .containerItems');
        boilerBrands.forEach(brand => {
            const brandName = brand.querySelector('.model-name').textContent.toLowerCase();
            if (brandName.includes(searchValue)) {
                brand.style.display = 'block';
            } else {
                brand.style.display = 'none';
            }
        });
    });
</script>
@endPushOnce