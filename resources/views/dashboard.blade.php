<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <!-- You can add additional elements here, like buttons or links -->
        </div>
    </x-slot>

    <div class="p-6 bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <!-- First Row: All Four Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1: Some Information -->
            <div class="border p-4 rounded-md border-gray-300 bg-blue-200">
                <h3 class="text-lg font-semibold mb-2">Card Title 1</h3>
                <p class="text-gray-700 dark:text-gray-300">Some information or statistics here.</p>
            </div>

            <!-- Card 2: User Actions -->
            <div class="border p-4 rounded-md border-gray-300 bg-green-200">
                <h3 class="text-lg font-semibold mb-2">Card Title 2</h3>
                <p class="text-gray-700 dark:text-gray-300">User actions or recent activities.</p>
            </div>

            <!-- Card 3: Notifications -->
            <div class="border p-4 rounded-md border-gray-300 bg-yellow-200">
                <h3 class="text-lg font-semibold mb-2">Card Title 3</h3>
                <p class="text-gray-700 dark:text-gray-300">Notifications or alerts go here.</p>
            </div>

            <!-- Card 4: Another Information -->
            <div class="border p-4 rounded-md border-gray-300 bg-purple-200">
                <h3 class="text-lg font-semibold mb-2">Card Title 4</h3>
                <p class="text-gray-700 dark:text-gray-300">Additional information or data.</p>
            </div>
        </div>

        <!-- Second Row: Visualization Charts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <!-- Chart 1: Visualization Chart 1 -->
            <div class="border p-4 rounded-md border-gray-300 bg-indigo-200">
                <h3 class="text-lg font-semibold mb-2">Chart Title 1</h3>
                <!-- Add your visualization chart here -->
            </div>

            <!-- Chart 2: Visualization Chart 2 -->
            <div class="border p-4 rounded-md border-gray-300 bg-pink-200">
                <h3 class="text-lg font-semibold mb-2">Chart Title 2</h3>
                <!-- Add your second visualization chart here -->
            </div>
        </div>

        <!-- Last Row: Full-width Card -->
        <div class="mt-6">
            <!-- Full-width Card Content -->
            <div class="border p-4 rounded-md border-gray-300 bg-gray-200">
                <h3 class="text-lg font-semibold mb-2">Full-width Card Title</h3>
                <p class="text-gray-700 dark:text-gray-300">Content of the full-width card.</p>
            </div>
        </div>

        <!-- Additional content can be added here as needed -->

    </div>
</x-app-layout>
