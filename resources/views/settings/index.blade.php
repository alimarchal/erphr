<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Settings" />
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Categories Management Card -->
                @can('manage correspondence categories')
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg hover:shadow-2xl transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Categories</h3>
                                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">Manage correspondence categories, subcategories, and
                                hierarchies.</p>
                            <a href="{{ route('correspondence-categories.index') }}" class="inline-block">
                                <x-button>Manage Categories</x-button>
                            </a>
                        </div>
                    </div>
                @endcan

                <!-- Users Management Card -->
                @can('manage users')
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg hover:shadow-2xl transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Users</h3>
                                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">Create, edit, and manage system users and their
                                assignments.</p>
                            <a href="{{ route('users.index') }}" class="inline-block">
                                <x-button>Manage Users</x-button>
                            </a>
                        </div>
                    </div>
                @endcan

                <!-- Roles Management Card -->
                @can('manage roles')
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg hover:shadow-2xl transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Roles</h3>
                                <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">Define roles and assign permissions to control user
                                access.</p>
                            <a href="{{ route('roles.index') }}" class="inline-block">
                                <x-button>Manage Roles</x-button>
                            </a>
                        </div>
                    </div>
                @endcan

                <!-- Permissions Management Card -->
                @can('manage permissions')
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg hover:shadow-2xl transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Permissions</h3>
                                <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">View and manage system permissions for fine-grained access
                                control.</p>
                            <a href="{{ route('permissions.index') }}" class="inline-block">
                                <x-button>Manage Permissions</x-button>
                            </a>
                        </div>
                    </div>
                @endcan

                <!-- Letter Types Management Card -->
                @can('manage letter types')
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg hover:shadow-2xl transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Letter Types</h3>
                                <svg class="w-8 h-8 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">Manage letter types and set reply requirements for
                                correspondence tracking.</p>
                            <a href="{{ route('letter-types.index') }}" class="inline-block">
                                <x-button>Manage Letter Types</x-button>
                            </a>
                        </div>
                    </div>
                @endcan

                <!-- Divisions Management Card -->
                @can('manage divisions')
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg hover:shadow-2xl transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Divisions</h3>
                                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">Manage organizational divisions for correspondence routing
                                and tracking.</p>
                            <a href="{{ route('divisions.index') }}" class="inline-block">
                                <x-button>Manage Divisions</x-button>
                            </a>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>