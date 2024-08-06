<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const cocktails = ref([]);

const fetchCocktails = async () => {
    try {
        const response = await axios.get('/api/cocktails');
        cocktails.value = response.data;
    } catch (error) {
        console.error('Error fetching cocktails:', error);
    }
};

const deleteCocktail = async (id) => {
    try {
        await axios.delete(`/api/cocktails/${id}`);
        fetchCocktails(); // Refresh the list
    } catch (error) {
        console.error('Error deleting cocktail:', error);
    }
};

onMounted(() => {
    fetchCocktails();
});
</script>

<template>

    <Head title="Cocktail Manager" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Cocktails</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div v-if="cocktails.length">
                            <ul>
                                <li v-for="cocktail in cocktails" :key="cocktail.id" class="mb-4">
                                    <h3 class="text-lg font-semibold">{{ cocktail.name }}</h3>
                                    <img :src="cocktail.image_url" alt="Cocktail Image" class="w-24 h-24" />
                                    <p>{{ cocktail.alcoholic }} | {{ cocktail.category }}</p>
                                    <button @click="deleteCocktail(cocktail.id)"
                                        class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
                                </li>
                            </ul>
                        </div>
                        <div v-else>
                            <p>No cocktails available.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>