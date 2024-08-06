<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import $ from 'jquery'; // Importar jQuery

const cocktails = ref([]);
const searchQuery = ref('');

const fetchCocktails = async () => {
    try {
        const response = await axios.get('https://www.thecocktaildb.com/api/json/v1/1/search.php?s=');
        cocktails.value = response.data.drinks || [];
    } catch (error) {
        console.error('Error fetching cocktails:', error);
    }
};

const saveCocktail = async (cocktail) => {
    if (!cocktail || !cocktail.idDrink) {
        console.error('Invalid cocktail data:', cocktail);
        return;
    }

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

    // jQuery search filter
    $('#search-input').on('input', function () {
        const query = $(this).val().toLowerCase();
        $('.cocktail-item').each(function () {
            const name = $(this).find('.cocktail-name').text().toLowerCase();
            $(this).toggle(name.includes(query));
        });
    });

    // jQuery event delegation for save button
    $('#cocktail-list').on('click', '.save-btn', function () {
        const cocktailId = $(this).data('id');
        const cocktail = cocktails.value.find(c => c.idDrink === cocktailId);
        saveCocktail(cocktail);
    });
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
                        <!-- Search Bar -->
                        <input id="search-input" v-model="searchQuery" type="text" placeholder="Search cocktails..."
                            class="mb-6 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition w-full" />

                        <!-- Cocktail List -->
                        <div v-if="cocktails.length">
                            <ul id="cocktail-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                <li v-for="cocktail in cocktails" :key="cocktail.idDrink"
                                    class="cocktail-item border p-4 rounded-lg shadow-lg bg-white flex flex-col items-center">
                                    <img :src="cocktail.strDrinkThumb" alt="Cocktail Image"
                                        class="w-32 h-32 mb-4 rounded-lg object-cover shadow-lg" />
                                    <h3 class="cocktail-name text-lg font-semibold mb-2">{{ cocktail.strDrink }}</h3>
                                    <p class="text-sm text-gray-600"><strong>Category:</strong> {{ cocktail.strCategory
                                        }}</p>
                                    <p class="text-sm text-gray-600"><strong>Alcoholic:</strong> {{
                                        cocktail.strAlcoholic }}</p>
                                    <p class="text-sm text-gray-600"><strong>Ingredients:</strong> {{
                                        cocktail.strIngredient1 ||
                                        'N/A' }}, {{
                                            cocktail.strIngredient2 || 'N/A' }}, {{ cocktail.strIngredient3 || 'N/A' }}</p>
                                    <button @click="saveCocktail(cocktail)"
                                        class="save-btn mt-4 bg-blue-400 text-white px-5 py-3 rounded-full shadow-lg hover:bg-blue-700 transition w-full">Save</button>
                                </li>
                            </ul>
                        </div>
                        <div v-else>
                            <p class="text-gray-600 text-center">No cocktails found.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Custom Styles */
button {
    font-size: 0.875rem;
    /* Adjust button font size */
}

button:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
}

button.save-btn {
    transition: background-color 0.3s, transform 0.3s;
}

button.save-btn:hover {
    background-color: #2b6cb0;
    /* Darker blue for hover effect */
    transform: scale(1.05);
    /* Slightly scale up on hover */
}
</style>
