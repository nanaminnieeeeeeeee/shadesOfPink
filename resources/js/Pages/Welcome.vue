<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, defineProps } from 'vue';

// Define the props to make these properties available in the component
defineProps({
    canLogin: {
        type: Boolean,
        required: true
    },
    canRegister: {
        type: Boolean,
        required: false,
        default: false
    },
    laravelVersion: {
        type: String,
        required: true
    },
    phpVersion: {
        type: String,
        required: true
    }
});

</script>

<template>
    <Head title="Welcome to Shades of Pink" />
    <div class="bg-pink-50 text-pink-800">
        <div
            class="relative flex min-h-screen flex-col items-center justify-center selection:bg-pink-300 selection:text-white p-8"
        >
            <header class="w-full max-w-6xl px-6">
                <nav class="flex justify-between items-center py-6">
                    <div class="text-xl font-bold">
                        <span class="text-black">Shades of </span><span class="text-pink-600">Pink</span>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center gap-2">
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="px-4 py-2 bg-pink-600 text-white rounded-lg shadow-md hover:bg-pink-700">Dashboard</Link>
                        <template v-else>
                            <Link :href="route('login')" class="px-4 py-2 text-pink-700 border border-pink-700 rounded-lg hover:bg-pink-700 hover:text-white w-full sm:w-auto text-center">Log in</Link>
                            <Link v-if="canRegister" :href="route('register')" class="sm:ml-4 px-4 py-2 text-pink-700 border border-pink-700 rounded-lg hover:bg-pink-700 hover:text-white w-full sm:w-auto text-center">Register</Link>
                        </template>
                    </div>
                </nav>
            </header>
            <main class="mt-16 w-full max-w-5xl text-center">
                <h1 class="text-4xl font-bold mb-8">Welcome to Shades of Pink</h1>
                <p class="text-lg leading-relaxed mb-12">
                    Shades of Pink is a thoughtfully crafted platform to help you explore and share your favorite shades. Whether you love pastels, brights, or everything in between, you'll find inspiration here.
                </p>
                <a
                    :href="$page.props.auth.user ? route('dashboard') : route('login')"
                    class="inline-block px-6 py-3 text-white bg-pink-500 rounded-full shadow-lg hover:bg-pink-600"
                >
                    Explore Now
                </a>
            </main>
            <footer class="mt-auto w-full py-8 text-center text-sm">
                Shades of Pink v{{ laravelVersion }} (PHP v{{ phpVersion }})
            </footer>
        </div>
    </div>
</template>
