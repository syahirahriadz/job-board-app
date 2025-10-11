<x-layouts.app :title="__('Dashboard')">
    <div class="h-full w-full flex-1 flex-col gap-4 rounded-lg">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            {{-- Job Cards (visible to admins and employers) --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isEmployer())
                @include('partials.total-jobs-card')
            @endif

            {{-- Admin Only Cards --}}
            @can('viewAny', \App\Models\User::class)
                @include('partials.total-users-card')
            @endcan

            {{-- Application Cards (visible to all authenticated users based on their role) --}}
            @can('viewAny', \App\Models\JobApplication::class)
                @include('partials.total-applications-card')
                @include('partials.pending-applications-card')

                @if(auth()->user()->isGuest())
                    @include('partials.approved-applications-card')
                @endif
            @endcan
        </div>

        <div class="space-y-8 mt-4">
            {{-- Job Tables (Admin sees all jobs, Employer sees only their jobs) --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isEmployer())
                @if(auth()->user()->isAdmin())
                    <livewire:job-table :use-pagination="true"/>
                @else
                    <livewire:job-table :use-pagination="true" :employer-only="true"/>
                @endif
            @endif

            {{-- Admin Only Tables --}}
            @can('viewAny', \App\Models\User::class)
                <livewire:user-list :use-pagination="true"/>
            @endcan

            {{-- Application Tables (visible to all authenticated users based on their role) --}}
            @can('viewAny', \App\Models\JobApplication::class)
                @if(auth()->user()->isAdmin())
                    <livewire:application-table :use-pagination="true"/>
                @elseif(auth()->user()->isEmployer())
                    <livewire:application-table :use-pagination="true" :employer-only="true"/>
                @else
                    <livewire:application-table :use-pagination="true" :user-only="true"/>
                @endif
            @endcan
        </div>
    </div>
</x-layouts.app>
