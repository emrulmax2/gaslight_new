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
                    $('#search-box').removeClass('hidden');
                    let innerHtml = '';
                    boilerBrandsManual.forEach(manual => {
                        innerHtml += '<a target="_blank" href="'+(manual.pdf_url != '' ? manual.pdf_url : 'javascript:void(0);')+'" id="'+manual.model+'" class="containerItems block mb-3">';
                            innerHtml += '<div class="intro-y box p-5">';
                                innerHtml += '<div class="grid grid-cols-12 gap-x-4 gap-y-2">';
                                    innerHtml += '<div class="col-span-12 sm:col-span-3">';
                                        innerHtml += '<div class="flex justify-start items-start">';
                                            innerHtml += '<span class="max-sm:hidden bg-success bg-opacity-10 inline-flex w-[45px] h-[45px] rounded-full items-center justify-center mr-3" style="flex: 0 0 auto;">';
                                                innerHtml += '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="calendar-days" class="lucide lucide-calendar-days stroke-1.5 h-5 w-5 text-success"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path><path d="M8 14h.01"></path><path d="M12 14h.01"></path><path d="M16 14h.01"></path><path d="M8 18h.01"></path><path d="M12 18h.01"></path><path d="M16 18h.01"></path></svg>';
                                            innerHtml += '</span>';
                                            innerHtml += '<div>';
                                                innerHtml += '<div class="text-gray-500 text-xs">Model</div>';
                                                innerHtml += '<div class="model-name font-medium text-base">'+manual.model+'</div>';
                                            innerHtml += '</div>';
                                        innerHtml += '</div>';
                                    innerHtml += '</div>';
                                    if(manual.year_of_manufacture != null){
                                        innerHtml += '<div class="col-span-6 sm:col-span-3">';
                                            innerHtml += '<div class="flex justify-start items-start">';
                                                innerHtml += '<span class="max-sm:hidden bg-success bg-opacity-10 inline-flex w-[45px] h-[45px] rounded-full items-center justify-center mr-3" style="flex: 0 0 auto;">';
                                                    innerHtml += '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="calendar-days" class="lucide lucide-calendar-days stroke-1.5 h-5 w-5 text-success"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path><path d="M8 14h.01"></path><path d="M12 14h.01"></path><path d="M16 14h.01"></path><path d="M8 18h.01"></path><path d="M12 18h.01"></path><path d="M16 18h.01"></path></svg>';
                                                innerHtml += '</span>';
                                                innerHtml += '<div>';
                                                    innerHtml += '<div class="text-gray-500 text-xs">Year of Manufacture</div>';
                                                    innerHtml += '<div class="model-name font-medium text-base">'+manual.year_of_manufacture+'</div>';
                                                innerHtml += '</div>';
                                            innerHtml += '</div>';
                                        innerHtml += '</div>';
                                    }
                                    if(manual.fuel_type != null){
                                        innerHtml += '<div class="col-span-6 sm:col-span-3">';
                                            innerHtml += '<div class="flex justify-start items-start">';
                                                innerHtml += '<span class="max-sm:hidden bg-success bg-opacity-10 inline-flex w-[45px] h-[45px] rounded-full items-center justify-center mr-3" style="flex: 0 0 auto;">';
                                                    innerHtml += '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="check-circle" class="lucide lucide-check-circle stroke-1.5 h-5 w-5 text-success"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>';
                                                innerHtml += '</span>';
                                                innerHtml += '<div>';
                                                    innerHtml += '<div class="text-gray-600 text-xs">Fuel Type</div>';
                                                    innerHtml += '<div class="text-base font-medium">'+manual.fuel_type+'</div>';
                                                innerHtml += '</div>';
                                            innerHtml += '</div>';
                                        innerHtml += '</div>';
                                    }
                                    if(manual.gc_no != null){
                                        innerHtml += '<div class="col-span-6 sm:col-span-3">';
                                            innerHtml += '<div class="flex justify-start items-start">';
                                                innerHtml += '<span class="max-sm:hidden bg-success bg-opacity-10 inline-flex w-[45px] h-[45px] rounded-full items-center justify-center mr-3" style="flex: 0 0 auto;">';
                                                    innerHtml += '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="check-circle" class="lucide lucide-check-circle stroke-1.5 h-5 w-5 text-success"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>';
                                                innerHtml += '</span>';
                                                innerHtml += '<div>';
                                                    innerHtml += '<div class="text-gray-600 text-xs">GC No</div>';
                                                    innerHtml += '<div class="text-base font-medium">'+manual.gc_no+'</div>';
                                                innerHtml += '</div>';
                                            innerHtml += '</div>';
                                        innerHtml += '</div>';
                                    }
                                innerHtml += '</div>';
                            innerHtml += '</div>';
                        innerHtml += '</a>';
                    });
                    boilerBrandsContainer.innerHTML += innerHtml;
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