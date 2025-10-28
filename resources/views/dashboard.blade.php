<x-layouts.app :title="__('Dashboard')">
    <div class="h-full w-full flex-1 flex-col gap-4 rounded-lg">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            {{-- Job Cards (visible to admins and employers) --}}
            @can('viewDashboardCards', \App\Models\Job::class)
                @include('partials.total-jobs-card')
            @endcan

            {{-- Pending Payment Card (visible to admins and employers) --}}
            @can('viewDashboardCards', \App\Models\Job::class)
                @include('partials.jobs-pending-payment-card')
            @endcan

            {{-- Admin Only Cards --}}
            @can('viewAny', \App\Models\User::class)
                @include('partials.total-users-card')
            @endcan

            {{-- Application Cards (visible to all authenticated users based on their role) --}}
            @can('viewAny', \App\Models\JobApplication::class)
                @can('viewTotalApplicationsCard', \App\Models\JobApplication::class)
                    @include('partials.total-applications-card')
                @endcan

                @include('partials.pending-applications-card')

                @can('viewApprovedApplicationsCard', \App\Models\JobApplication::class)
                    @include('partials.approved-applications-card')
                @endcan
            @endcan
        </div>

        <div class="space-y-8 mt-4">
            {{-- Job Tables (Admin sees all jobs, Employer sees only their jobs) --}}
            @can('viewAllJobs', \App\Models\Job::class)
                <livewire:job-table :use-pagination="true"/>
            @endcan

            @can('viewOwnJobs', \App\Models\Job::class)
                <livewire:job-table :use-pagination="true" :employer-only="true"/>
            @endcan

            {{-- Admin Only Tables --}}
            @can('viewAny', \App\Models\User::class)
                <livewire:user-list :use-pagination="true"/>
            @endcan

            {{-- Application Tables (visible to all authenticated users based on their role) --}}
            @can('viewAllApplications', \App\Models\JobApplication::class)
                <livewire:application-table :use-pagination="true"/>
            @endcan

            @can('viewEmployerApplications', \App\Models\JobApplication::class)
                <livewire:application-table :use-pagination="true" :employer-only="true"/>
            @endcan

            @can('viewUserApplications', \App\Models\JobApplication::class)
                <livewire:application-table :use-pagination="true" :user-only="true"/>
            @endcan
        </div>
    </div>
</x-layouts.app>
