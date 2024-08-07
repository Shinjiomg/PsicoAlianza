<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const cocktails = ref([]);
const filteredCocktails = ref([]);
const searchQuery = ref('');

const fetchCocktails = async () => {
    try {
        const response = await axios.get('/api/cocktails');
        cocktails.value = response.data;
        filteredCocktails.value = response.data;
    } catch (error) {
        console.error('Error fetching cocktails:', error);
    }
};

const filterCocktails = () => {
    if (searchQuery.value) {
        filteredCocktails.value = cocktails.value.filter(cocktail =>
            cocktail.name.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    } else {
        filteredCocktails.value = cocktails.value;
    }
};

const deleteCocktail = async (id) => {
    try {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        });

        if (result.isConfirmed) {
            await axios.delete(`/api/cocktails/${id}`);
            fetchCocktails();
            Swal.fire('Deleted!', 'Cocktail has been deleted.', 'success');
        }
    } catch (error) {
        Swal.fire('Error!', 'There was an error deleting the cocktail.', 'error');
    }
};

const showEditForm = async (cocktail) => {
    const { value: formValues } = await Swal.fire({
        title: 'Edit Cocktail',
        html: `
            <form id="edit-form">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input id="name" class="mt-1 px-4 py-2 border border-gray-300 rounded-lg shadow-sm w-full" type="text" value="${cocktail.name}" required />
                </div>
                <div class="mb-4">
                    <label for="image_url" class="block text-sm font-medium text-gray-700">Image URL</label>
                    <input id="image_url" class="mt-1 px-4 py-2 border border-gray-300 rounded-lg shadow-sm w-full" type="text" value="${cocktail.image_url}" />
                </div>
            </form>
        `,
        focusConfirm: false,
        preConfirm: () => {
            const name = Swal.getPopup().querySelector('#name').value;
            const image_url = Swal.getPopup().querySelector('#image_url').value;
            return { name, image_url };
        },
        confirmButtonText: 'Save',
        confirmButtonColor: '#3085d6',
        showCancelButton: true,
        cancelButtonText: 'Cancel',
        cancelButtonColor: '#d33',
    });

    if (formValues) {
        try {
            await axios.put(`/api/cocktails/${cocktail.id}`, formValues);
            fetchCocktails();
            Swal.fire('Updated!', 'Cocktail has been updated.', 'success');
        } catch (error) {
            Swal.fire('Error!', 'There was an error updating the cocktail.', 'error');
        }
    }
};

onMounted(() => {
    fetchCocktails();
});
</script>

<template>

    <Head title="Cocktail Manager" />
    <AuthenticatedLayout>
        <div class="py-9">
            <div class="mx-4 sm:mx-6 lg:mx-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mb-4">
                            <input v-model="searchQuery" @input="filterCocktails" type="text" placeholder="Search..."
                                class="px-4 py-2 border border-gray-300 rounded-full shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full lg:w-2/3 mx-auto sm:text-sm" />
                        </div>

                        <div v-if="filteredCocktails.length">
                            <div class="overflow-x-auto h-[34rem] lg:h-[38rem]">
                                <table class="divide-gray-200 w-2/3 mx-auto">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Image</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Name</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="cocktail in filteredCocktails" :key="cocktail.id">
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <img :src="cocktail.image_url" alt="Cocktail Image"
                                                    class="w-16 h-16 rounded-lg object-cover mx-auto" />
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                                {{ cocktail.name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                                <button @click="showEditForm(cocktail)"
                                                    class="bg-transparent border border-blue-400 text-blue-400 px-4 py-2 rounded-lg shadow-md hover:bg-blue-50 transition">Edit</button>
                                                <button @click="deleteCocktail(cocktail.id)"
                                                    class="bg-transparent border border-red-400 text-red-400 px-4 py-2 rounded-lg shadow-md hover:bg-red-50 transition ml-2">Delete</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex justify-center mt-4 items-center">
                                <button
                                    class="bg-transparent border border-gray-300 text-gray-600 px-4 py-2 rounded-lg shadow-md hover:bg-gray-50 transition">Previous</button>
                                <span class="mx-4 text-sm text-gray-600">Showing {{ filteredCocktails.length }} of
                                    {{ cocktails.length }} cocktails</span>
                                <button
                                    class="bg-transparent border border-gray-300 text-gray-600 px-4 py-2 rounded-lg shadow-md hover:bg-gray-50 transition">Next</button>
                            </div>
                        </div>
                        <div v-else>
                            <p class="text-gray-600 text-center">No cocktails available.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
thead {
    background-color: #f9fafb;
}

tbody tr:hover {
    background-color: #f3f4f6;
}

button {
    font-size: 0.875rem;
    transition: background-color 0.2s, transform 0.2s;
}

button:hover {
    transform: scale(1.05);
}
</style>
