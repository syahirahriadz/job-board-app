<x-layouts.app :title="__('Dashboard')">
    <div class="h-full w-full flex-1 flex-col gap-4 rounded-lg">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            @can('viewAny', \App\Models\Job::class)
                {{-- Total Jobs --}}
                @include('partials.total-jobs-card')
            @endcan

            @can('viewAny', \App\Models\User::class)
                {{-- Total Users --}}
                @include('partials.total-users-card')
            @endcan

            {{-- Both admin and applicants can see total applications --}}
            @include('partials.total-applications-card')

            {{-- Only applicants see pending and approved applications --}}
            @if(auth()->user()->role !== 'admin')
                @include('partials.pending-applications-card')
                @include('partials.approved-applications-card')
            @endif
        </div>


        <div class="space-y-8 mt-4">
            @can('viewAny', \App\Models\Job::class)
                {{-- Job Table --}}
                <livewire:job-table :use-pagination="true"/>
            @endcan

            @can('viewAny', \App\Models\User::class)
                {{-- User Table --}}
                <livewire:user-list :use-pagination="true"/>
            @endcan

            @can('viewAny', \App\Models\JobApplication::class)
                {{-- Application Table --}}
                <livewire:application-table :use-pagination="true"/>
            @endcan
        </div>
    </div>
</x-layouts.app>
