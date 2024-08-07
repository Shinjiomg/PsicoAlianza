<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import $ from 'jquery';

const cocktails = ref([]);
const searchQuery = ref('');
const currentPage = ref(1);
const pageSize = 6;

const fetchCocktails = async () => {
    try {
        const response = await axios.get('https://www.thecocktaildb.com/api/json/v1/1/search.php?s=');
        cocktails.value = response.data.drinks || [];
    } catch (error) {
        console.error('Error fetching cocktails:', error);
    }
};

const paginatedCocktails = () => {
    const start = (currentPage.value - 1) * pageSize;
    const end = start + pageSize;
    return cocktails.value.slice(start, end);
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
        Swal.fire('Saved!', 'Cocktail has been saved.', 'success');
    } catch (error) {
        Swal.fire('Error!', 'There was an error saving the cocktail.', 'error');
    }
};

const showDetails = (cocktail) => {
    Swal.fire({
        title: cocktail.strDrink,
        html: `
            <div class="flex flex-col items-center">
                <img src="${cocktail.strDrinkThumb}" alt="${cocktail.strDrink}" class="w-48 h-48 rounded-lg object-cover mb-4"/>
                <div class="text-center">
                    <p class="text-md text-gray-600 mb-1"><strong>Category:</strong> ${cocktail.strCategory}</p>
                    <p class="text-md text-gray-600 mb-1"><strong>Alcoholic:</strong> ${cocktail.strAlcoholic}</p>
                    <p class="text-md text-gray-600"><strong>Ingredients</strong></p>
                        <ul>
                            <li>${cocktail.strIngredient1 || 'N/A'} - ${cocktail.strMeasure1}</li>
                            <li>${cocktail.strIngredient2 || 'N/A'} - ${cocktail.strMeasure2}</li>
                            <li>${cocktail.strIngredient3 || 'N/A'} - ${cocktail.strMeasure3}</li>
                            <li>${cocktail.strIngredient4 || 'N/A'} - ${cocktail.strMeasure4}</li>
                        </ul>
                    <p class="text-xl text-gray-600 mb-1"><strong>Instructions:</strong> ${cocktail.strInstructions}</p>
                    </div>
            </div>
        `,
        showCloseButton: true,
        confirmButtonText: 'Close',
        customClass: {
            popup: 'bg-white text-gray-800 rounded-lg shadow-lg p-4',
            title: 'text-xl font-semibold text-teal-700',
            htmlContainer: 'text-center',
            image: 'rounded-lg object-cover',
        },
    });
};

const nextPage = () => {
    if ((currentPage.value * pageSize) < cocktails.value.length) {
        currentPage.value += 1;
    }
};

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value -= 1;
    }
};

onMounted(() => {
    fetchCocktails();

    $('#search-input').on('input', function () {
        const query = $(this).val().toLowerCase();
        $('.cocktail-item').each(function () {
            const name = $(this).find('.cocktail-name').text().toLowerCase();
            $(this).toggle(name.includes(query));
        });
    });

    $('#cocktail-list').on('click', '.save-btn', function () {
        const cocktailId = $(this).data('id');
        const cocktail = cocktails.value.find(c => c.idDrink === cocktailId);
        saveCocktail(cocktail);
    });

    $('#cocktail-list').on('click', '.details-btn', function () {
        const cocktailId = $(this).data('id');
        const cocktail = cocktails.value.find(c => c.idDrink === cocktailId);
        showDetails(cocktail);
    });
});
</script>


<template>

    <Head title="Cocktails" />

    <AuthenticatedLayout>
        <div class="py-9">
            <div class="mx-4 sm:mx-6 lg:mx-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Search Bar -->
                        <input id="search-input" v-model="searchQuery" type="text" placeholder="Search cocktails..."
                            class="mb-6 px-4 py-2 border border-gray-300 rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition w-full" />

                        <!-- Cocktail List -->
                        <div v-if="cocktails.length">
                            <ul id="cocktail-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                <li v-for="cocktail in paginatedCocktails()" :key="cocktail.idDrink"
                                    class="cocktail-item  border border-gray-200 rounded-lg shadow-lg p-4 flex flex-col sm:flex-row items-center sm:items-start gap-4 transition-transform transform hover:-translate-y-2">
                                    <img :src="cocktail.strDrinkThumb" alt="Cocktail Image"
                                        class="w-32 h-32 sm:w-48 sm:h-48 rounded-lg border border-teal-200 object-cover shadow-md" />
                                    <div class="flex-1 text-center sm:text-left">
                                        <h3 class="cocktail-name text-lg sm:text-xl font-semibold mb-2 text-teal-700">
                                            {{ cocktail.strDrink }}
                                        </h3>
                                        <p class="text-sm text-gray-600"><strong>Category:</strong> {{
                                            cocktail.strCategory }}
                                        </p>
                                        <p class="text-sm text-gray-600"><strong>Alcoholic:</strong> {{
                                            cocktail.strAlcoholic }}
                                        </p>
                                        <p class="text-sm text-gray-600"><strong>Ingredients:</strong> {{
                                            cocktail.strIngredient1 || 'N/A' }}, {{ cocktail.strIngredient2 || 'N/A' }},
                                            {{
                                                cocktail.strIngredient3 || 'N/A' }}</p>
                                        <div class="flex flex-col sm:flex-row gap-4 mt-4">
                                            <button @click="saveCocktail(cocktail)"
                                                class="save-btn bg-teal-400 text-white px-5 py-3 rounded-lg shadow-lg hover:bg-teal-500 transition flex items-center justify-center w-full sm:w-2/3">
                                                <span class="material-icons">Save</span>
                                            </button>
                                            <button @click="showDetails(cocktail)"
                                                class="details-btn bg-teal-300 text-white px-5 py-3 rounded-lg shadow-lg hover:bg-teal-400 transition flex items-center justify-center w-full sm:w-2/3">
                                                <span class="material-icons">Info</span>
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <div class="flex justify-between items-center mt-6">
                                <button @click="prevPage" :disabled="currentPage === 1"
                                    class="bg-transparent border border-gray-300 text-gray-600 px-4 py-2 rounded-lg shadow-md hover:bg-gray-50 transition">
                                    Previous
                                </button>
                                <button @click="nextPage" :disabled="(currentPage * pageSize) >= cocktails.length"
                                    class="bg-transparent border border-gray-300 text-gray-600 px-4 py-2 rounded-lg shadow-md hover:bg-gray-50 transition">
                                    Next
                                </button>
                            </div>
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
.cocktail-item {
    transition: transform 0.3s, box-shadow 0.3s;
}

.cocktail-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

input[type="text"] {
    font-size: 1rem;
    border: 1px solid #ddd;
    background-color: #f9f9f9;
}

input[type="text"]:focus {
    border-color: #6b7280;
    box-shadow: 0 0 0 1px rgba(66, 153, 225, 0.5);
}
</style>
