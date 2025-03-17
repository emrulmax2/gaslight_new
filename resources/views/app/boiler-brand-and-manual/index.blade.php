@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>Dashboard - Midone - Tailwind Admin Dashboard Template</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 ">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: General Report -->
                <div class="col-span-12 mt-4 sm:mt-8">
                    <div class="intro-y flex h-auto sm:h-10 items-center">
                        <h2 class="mr-5 truncate text-lg font-medium">Search By Brand</h2>
                        <div class="ml-auto mt-4 flex w-full sm:mt-0 sm:w-auto items-end">
                            <x-base.button as='a' href="{{ route('company.dashboard') }}" id="bck"  class="shadow-md add_btn" variant="primary" >
                                <x-base.lucide class="h-6 w-6" icon="home" />
                                
                            </x-base.button>
                        </div>
                    </div>
                    <div class="mt-3 sm:mt-5 grid grid-cols-12 gap-2 sm:gap-6">

                        <x-base.tom-select
                            class="col-span-12 block rounded-[1rem] border-slate-300/80 px-2 py-1.5 boiler-brand-select bg-slate-100 text-slate-900"
                            id="boiler-brand-select"
                            data-placeholder="Please Select your Brand"
                            name="role"
                        >
                            <option value="">Please Select</option>
                            @foreach($boilerBrands as $brand) 
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </x-base.tom-select>
                    
                    </div>
                    
                    <!-- BEGIN: HTML Table Data -->
                    <div class="intro-y box mt-5 p-5 col-span-12 brandContainer hidden">
                        <div class="grid grid-cols-12 gap-6">
                            <!--Implement a search method that get container-->
                            <div id="search-box" class="col-span-12 hidden">
                            <x-base.form-input
                                class="mt-2 sm:mt-0 w-full"
                                id="text-search"
                                type="text"
                                placeholder="Search..."
                                
                            />
                            </div>
                            <!-- Alphabetic Index -->
                            <div class="col-span-12 boiler-brands-container"></div>
                            <!-- Boiler Brands -->
                        </div>
                    </div>
                    <!-- END: HTML Table Data -->
                </div>
            </div>
        </div>
    </div>
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
                        boilerBrandsContainer.innerHTML += `
                            <a href="${manual.url}" id="${manual.model}" class="col-span-12 sm:col-span-6 xl:col-span-4 mt-6 my-3">
                                <div class="w-auto">
                                    <div class="intro-y box p-5 my-3">
                                        <div class="flex items-center">
                                            <div class="model-name font-medium text-base">${manual.model}</div>
                                        </div>
                                        <div class="mt-4">
                                            <div class="text-gray-600">Fuel Type</div>
                                            <div class="text-base font-medium">${manual.fuel_type}</div>
                                        </div>
                                        <div class="mt-4">
                                            <div class="text-gray-600">GC No</div>
                                            <div class="text-base font-medium">${manual.gc_no}</div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        `;
                    });
                }
                brandContainer.classList.remove('hidden');
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
        const boilerBrands = document.querySelectorAll('.boiler-brands-container a');
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