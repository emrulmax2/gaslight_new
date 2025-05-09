@extends('../themes/' . $activeTheme . '/' . $activeLayout)

@section('subhead')
    <title>{{ $title }}</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-8 flex items-center flex-row">
        <h2 class="mr-auto text-lg font-medium">{{ $user->name.'\'s Subscription'}}</h2>
        <div class="flex mt-0 w-auto">
            <x-base.button as="a" href="{{ route('company.dashboard') }}" class="shadow-md" variant="linkedin">
                <x-base.lucide class="h-4 w-4" icon="home" />
            </x-base.button>
        </div>
    </div>

    <div class="settingsBox mt-5">
        <div class="box rounded-md p-0 overflow-hidden">
            <a href="{{ route('users.plans', $user->id) }}" class="border-b flex w-full items-center px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="eye-off" style="margin-top: -2px;" />
                <span class="font-medium text-slate-500 text-sm">View Plan</span>
            </a>
            <a href="{{ route('users.payment.history', $user->id) }}" class="border-b flex w-full items-center px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="receipt-pound-sterling" style="margin-top: -2px;" />
                <span class="font-medium text-slate-500 text-sm">Payment Histories</span>
            </a>
            @if(isset($user->userpackage->active) && $user->userpackage->active == 1)
            <a href="javascript:void(0);" data-id="{{ $user->id }}" class="cancellSubscription border-b flex w-full items-center px-5 py-3">
                <x-base.lucide class="h-4 w-4 mr-2 stroke-2 text-success" icon="x-circle" style="margin-top: -2px;" />
                <span class="font-medium text-slate-500 text-sm">Cancel Subscription</span>
            </a>
            @endif
        </div>
    </div>

    @include('app.user.modal')
    @include('app.action-modals')
@endsection


@pushOnce('vendors')
    @vite('resources/js/vendors/lucide.js')
    @vite('resources/js/vendors/axios.js')
@endPushOnce

@pushOnce('scripts')
    <script type="module">
        const successModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
        const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
        const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));


        $(document).on('click', '.cancellSubscription', function(e){
            e.preventDefault();
            let $theBtn = $(this);
            let user_id = $theBtn.attr('data-id');

            confirmModal.show();
            document.getElementById('confirmModal').addEventListener('shown.tw.modal', function(event){
                $('#confirmModal .confirmModalTitle').html('Are you sure?');
                $('#confirmModal .confirmModalDesc').html('Do you really want to cancel subscription for this user? If yes then please click on the agree btn.');
                $('#confirmModal .agreeWith').attr('data-id', user_id).attr('data-action', 'DELETESUB');
            });
        });

        $('#confirmModal .agreeWith').on('click', function(){
            let $agreeBTN = $(this);
            let row_id = $agreeBTN.attr('data-id');
            let action = $agreeBTN.attr('data-action');

            $('#confirmModal button').attr('disabled', 'disabled');
            if(action == 'DELETESUB'){
                axios({
                    method: 'POST',
                    url: route('users.cancel.subscription'),
                    data: {user_id : row_id},
                    headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
                }).then(response => {
                    if (response.status == 200) {
                        $('#confirmModal button').removeAttr('disabled');
                        confirmModal.hide();

                        successModal.show();
                        document.getElementById("successModal").addEventListener("shown.tw.modal", function (event) {
                            $("#successModal .successModalTitle").html("Congratulations!");
                            $("#successModal .successModalDesc").html(response.data.message);
                            $("#successModal .agreeWith").attr('data-action', 'RELOAD').attr('data-redirect', (response.data.red ? response.data.red : ''));
                        });

                        setTimeout(() => {
                            successModal.hide();
                            if(response.data.red){
                                window.location.href = response.data.red
                            }else{
                                window.location.reload();
                            }
                        }, 2500);
                    }
                }).catch(error =>{
                    console.log(error)
                });
            }
        });
    </script>
@endPushOnce
