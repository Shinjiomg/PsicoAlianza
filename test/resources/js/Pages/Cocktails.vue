<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const cocktails = ref([]);

const fetchCocktails = async () => {
    try {
        const response = await axios.get('https://www.thecocktaildb.com/api/json/v1/1/search.php?s=');
        cocktails.value = response.data.drinks || [];
    } catch (error) {
        console.error('Error fetching cocktails:', error);
    }
};

const saveCocktail = async (cocktail) => {
    try {
        await axios.post('/api/cocktails', {
            idDrink: cocktail.idDrink,
            name: cocktail.strDrink,
            image_url: cocktail.strDrinkThumb,
            category: cocktail.strCategory,
            alcoholic: cocktail.strAlcoholic,
        });
        alert('Cocktail saved!');
    } catch (error) {
        console.error('Error saving cocktail:', error);
    }
};

onMounted(() => {
    fetchCocktails();
});
</script>

<template>

    <Head title="Cocktails" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Cocktails</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div v-if="cocktails.length">
                            <ul>
                                <li v-for="cocktail in cocktails" :key="cocktail.idDrink" class="mb-4">
                                    <h3 class="text-lg font-semibold">{{ cocktail.strDrink }}</h3>
                                    <img :src="cocktail.strDrinkThumb" alt="Cocktail Image" class="w-24 h-24" />
                                    <p>{{ cocktail.strAlcoholic }} | {{ cocktail.strCategory }}</p>
                                    <button @click="saveCocktail(cocktail)"
                                        class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                                </li>
                            </ul>
                        </div>
                        <div v-else>
                            <p>No cocktails found.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>