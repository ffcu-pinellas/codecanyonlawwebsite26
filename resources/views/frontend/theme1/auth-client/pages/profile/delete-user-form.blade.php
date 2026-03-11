<div class="col-12">
    <div class="card py-1">

        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-4 py-5">
                    <div class="h5">{{ __('Delete Account') }}</div>
                    <p class="">{{ __('Permanently delete your account.') }}</p>
                </div>
                <div class="col-md-8 p-5">
                    <p class="w-75">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}</p>
                    <button type="button" class="btn btn-dashboard-theme rounded" id="deleteAccountBtn">{{ __('Delete Account') }}</button>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.delete-account') }}" method="post" id="deleteForm">
        @csrf
        <input type="hidden" name="password">
    </form>
</div>
