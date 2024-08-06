<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const cocktails = ref([]);
const selectedCocktail = ref(null);
const isEditing = ref(false);

// Fetch all cocktails from the API
const fetchCocktails = async () => {
    try {
        const response = await axios.get('/api/cocktails');
        cocktails.value = response.data;
    } catch (error) {
        console.error('Error fetching cocktails:', error);
    }
};

// Delete a cocktail
const deleteCocktail = async (id) => {
    try {
        await axios.delete(`/api/cocktails/${id}`);
        fetchCocktails(); // Refresh the list
    } catch (error) {
        console.error('Error deleting cocktail:', error);
    }
};

// Initialize the form for editing a cocktail
const initForm = () => {
    selectedCocktail.value = { id: null, name: '', image_url: '', category: '', alcoholic: '' };
    isEditing.value = false;
};

// Edit a cocktail
const editCocktail = (cocktail) => {
    selectedCocktail.value = { ...cocktail };
    isEditing.value = true;
};

// Save a cocktail (update)
const saveCocktail = async () => {
    try {
        await axios.put(`/api/cocktails/${selectedCocktail.value.id}`, selectedCocktail.value);
        fetchCocktails();
        initForm();
    } catch (error) {
        console.error('Error saving cocktail:', error);
    }
};

// Close the form without saving
const cancelEdit = () => {
    initForm();
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
                        <!-- Form for editing cocktails -->
                        <div v-if="isEditing" class="mb-8">
                            <h3 class="text-lg font-semibold mb-4 text-center">Edit Cocktail</h3>
                            <form @submit.prevent="saveCocktail" class="space-y-6 max-w-lg mx-auto">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Name</label>
                                    <input v-model="selectedCocktail.name" type="text" required
                                        class="mt-1 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Image URL</label>
                                    <input v-model="selectedCocktail.image_url" type="text"
                                        class="mt-1 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Category</label>
                                    <input v-model="selectedCocktail.category" type="text"
                                        class="mt-1 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Alcoholic</label>
                                    <input v-model="selectedCocktail.alcoholic" type="text"
                                        class="mt-1 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm" />
                                </div>
                                <div class="flex gap-4 justify-center">
                                    <button type="submit"
                                        class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-blue-700 transition">Save</button>
                                    <button @click="cancelEdit" type="button"
                                        class="bg-gray-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-gray-700 transition">Cancel</button>
                                </div>
                            </form>
                        </div>

                        <!-- List of cocktails -->
                        <div v-else>
                            <div v-if="cocktails.length">
                                <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                    <li v-for="cocktail in cocktails" :key="cocktail.id"
                                        class="border p-4 rounded-lg shadow-md bg-white flex flex-col items-center">
                                        <img :src="cocktail.image_url" alt="Cocktail Image"
                                            class="w-32 h-32 mb-4 rounded-lg object-cover shadow-md" />
                                        <h3 class="text-lg font-semibold mb-2">{{ cocktail.name }}</h3>
                                        <p class="text-sm text-gray-600"><strong>Category:</strong> {{ cocktail.category
                                            }}</p>
                                        <p class="text-sm text-gray-600"><strong>Alcoholic:</strong> {{
                                            cocktail.alcoholic }}
                                        </p>
                                        <div class="flex gap-4 mt-4">
                                            <button @click="editCocktail(cocktail)"
                                                class="bg-blue-400 text-white px-4 py-2 rounded-full shadow-md hover:bg-blue-700 transition">Edit</button>
                                            <button @click="deleteCocktail(cocktail.id)"
                                                class="bg-red-400 text-white px-4 py-2 rounded-full shadow-md hover:bg-red-700 transition">Delete</button>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div v-else>
                                <p class="text-gray-600 text-center">No cocktails available.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Custom Styles */
button:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
}
</style>
