<ul class="flex justify-start">
    <li class="flex-col justify-center text-center sm:flex-row lg:justify-start">
        <a href="{{ route('customers.jobs', $customer->id) }}" class="{{ Route::currentRouteName() == 'customers.jobs' ? 'active' : '' }} block appearance-none px-5 border text-slate-700 dark:text-slate-400 [&.active]:text-slate-800 [&.active]:dark:text-white border-b-2 border-transparent dark:border-transparent [&.active]:border-b-primary [&.active]:font-medium [&.active]:dark:border-b-primary cursor-pointer py-4">
            Jobs
        </a>
    </li>
    <li class="flex-col justify-center text-center sm:flex-row lg:justify-start">
        <a href="{{ route('customers.job-addresses', $customer->id) }}" class="{{ Route::currentRouteName() == 'customers.job-addresses' ? 'active' : '' }} block appearance-none px-5 border text-slate-700 dark:text-slate-400 [&.active]:text-slate-800 [&.active]:dark:text-white border-b-2 border-transparent dark:border-transparent [&.active]:border-b-primary [&.active]:font-medium [&.active]:dark:border-b-primary cursor-pointer py-4">
            Job Addresses
        </a>
    </li>
    <li class="flex-col justify-center text-center sm:flex-row lg:justify-start">
        <a href="#" class="block appearance-none px-5 border text-slate-700 dark:text-slate-400 [&.active]:text-slate-800 [&.active]:dark:text-white border-b-2 border-transparent dark:border-transparent [&.active]:border-b-primary [&.active]:font-medium [&.active]:dark:border-b-primary cursor-pointer py-4">
            Pending Invoices
        </a>
    </li>
    <li class="flex-col justify-center text-center sm:flex-row lg:justify-start">
        <a href="#" class="block appearance-none px-5 border text-slate-700 dark:text-slate-400 [&.active]:text-slate-800 [&.active]:dark:text-white border-b-2 border-transparent dark:border-transparent [&.active]:border-b-primary [&.active]:font-medium [&.active]:dark:border-b-primary cursor-pointer py-4">
            Approved Invoices
        </a>
    </li>
    <li class="flex-col justify-center text-center sm:flex-row lg:justify-start">
        <a href="#" class="block appearance-none px-5 border text-slate-700 dark:text-slate-400 [&.active]:text-slate-800 [&.active]:dark:text-white border-b-2 border-transparent dark:border-transparent [&.active]:border-b-primary [&.active]:font-medium [&.active]:dark:border-b-primary cursor-pointer py-4">
            Archive
        </a>
    </li>
</ul>