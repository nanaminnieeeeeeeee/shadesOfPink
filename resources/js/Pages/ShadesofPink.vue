<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
</script>

<script>
export default {
  props: {
    shades: Array,
  },
  data() {
    return {
      showModal: false,
      showDeleteModal: false,
      editMode: false,
      form: useForm({
        name: '',
        color_code: '',
      }),
      errors: {},
      shadeToDelete: null,
      searchQuery: '',
      currentPage: 1,
      perPage: 10,
    };
  },
  computed: {
    filteredShades() {
      return this.shades.filter(shade =>
        shade.name.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    },
    paginatedShades() {
      const start = (this.currentPage - 1) * this.perPage;
      const end = start + this.perPage;
      return this.filteredShades.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.filteredShades.length / this.perPage);
    }
  },
  methods: {
    formatDate(date) {
      if (date) {
        return new Date(date).toISOString().split('T')[0];
      }
      return '';
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    submit() {
      this.errors = {};
      if (this.editMode) {
        this.form.put(route('shades.update', this.form.id), {
          onSuccess: () => this.closeModal(),
          onError: (errors) => {
            this.errors = errors;
          }
        });
      } else {
        this.form.post(route('shades.store'), {
          onSuccess: () => this.closeModal(),
          onError: (errors) => {
            this.errors = errors;
          }
        });
      }
    },
    editShade(shade) {
      this.editMode = true;
      this.form = useForm({ ...shade });
      this.showModal = true;
    },
    openDeleteModal(shade) {
      console.log("Delete modal should open for shade:", shade);
      this.shadeToDelete = shade;
      this.showDeleteModal = true;
    },
    closeDeleteModal() {
      this.showDeleteModal = false;
      this.shadeToDelete = null;
    },
    confirmDelete() {
      if (this.shadeToDelete) {
        this.form.delete(route('shades.destroy', this.shadeToDelete.id), {
          onSuccess: () => this.closeDeleteModal(),
        });
      }
    },
    closeModal() {
      this.showModal = false;
      this.editMode = false;
      this.form.reset();
      this.errors = {};
    },
  },
};
</script>

<template>
  <Head title="Shades of Pink" />

  <AuthenticatedLayout>
      <template #header>
          <h2 class="text-xl font-semibold leading-tight text-gray-800">
              Shades
          </h2>
      </template>

      <div class="py-12">
          <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
              <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                  <div class="p-6 text-gray-900">
                      <div class="container mx-auto p-6">
                          <div class="flex justify-between items-center mb-6">
                              <h1 class="text-2xl font-bold">
                                  <span class="text-black">Shades of</span> <span class="text-pink-600">Pink</span>
                              </h1>
                          </div>
                          <!-- Search Input and Add Shade Button -->
                          <div class="flex flex-col sm:flex-row items-center justify-between mb-4 w-full">
                              <div class="w-full sm:w-1/2 mb-2 sm:mb-0">
                                  <input
                                      v-model="searchQuery"
                                      type="text"
                                      placeholder="Search by name"
                                      class="border border-pink-600 p-2 rounded w-full"
                                  />
                              </div>
                              <button
                                  @click="showModal = true"
                                  class="flex items-center text-black hover:text-gray-700"
                              >
                                  <i class="fas fa-plus text-pink-600 mr-2"></i> Add Shade
                              </button>
                          </div>
                          
                          <!-- Modal for Adding/Editing Shades -->
                          <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                              <div class="bg-white p-6 rounded-lg w-96">
                                  <div class="flex justify-between items-center mb-4">
                                      <h2 class="text-xl font-bold">{{ editMode ? 'Edit Shade' : 'Add Shade' }}</h2>
                                      <button @click="closeModal" class="text-gray-500 hover:text-gray-700">&times;</button>
                                  </div>
                                  <form @submit.prevent="submit">
                                      <div class="mb-4">
                                          <label for="name" class="block text-sm font-bold mb-2">Color Name:</label>
                                          <input v-model="form.name" type="text" id="name" class="border border-gray-300 p-2 rounded w-full" required />
                                          <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
                                      </div>
                                      <div class="mb-4">
                                          <label for="color_code" class="block text-sm font-bold mb-2">Color Code:</label>
                                          <input v-model="form.color_code" type="text" id="color_code" class="border border-gray-300 p-2 rounded w-full" required />
                                          <p v-if="errors.color_code" class="text-red-500 text-sm mt-1">{{ errors.color_code }}</p>
                                      </div>
                                      <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-medium py-2 px-4 rounded w-full">
                                          {{ editMode ? 'Update Shade' : 'Add Shade' }}
                                      </button>
                                  </form>
                              </div>
                          </div>

                          <!-- Confirmation Modal for Deleting a Shade -->
                          <div v-if="showDeleteModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                              <div class="bg-white p-6 rounded-lg w-96">
                                  <div class="flex justify-between items-center mb-4">
                                      <h2 class="text-xl font-bold">Delete Shade</h2>
                                  </div>
                                  <p>Are you sure you want to delete this shade?</p>
                                  <div class="flex justify-end mt-6">
                                      <button @click="closeDeleteModal" class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded mr-2">
                                          Cancel
                                      </button>
                                      <button @click="confirmDelete" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                          Delete
                                      </button>
                                  </div>
                              </div>
                          </div>

                          <!-- Shades of Pink Table -->
                          <div class="overflow-x-auto">
                              <table class="min-w-full bg-white border rounded-lg shadow-md overflow-hidden">
                                  <thead>
                                    <tr class="bg-pink-200 text-left text-gray-600 uppercase text-sm leading-normal">
                                          <th class="py-3 px-6">Name</th>
                                          <th class="py-3 px-6">Code</th>
                                          <th class="py-3 px-6">Color</th>
                                          <th class="py-3 px-6">Created By</th>
                                          <th class="py-3 px-6">Created Date</th>
                                          <th class="py-3 px-6">Updated At</th>
                                          <th class="py-3 px-6">Actions</th>
                                      </tr>
                                    </thead>
                                  <tbody class="text-gray-600 text-sm font-light">
                                      <tr v-for="shade in paginatedShades" :key="shade.id" class="border-b border-gray-200 hover:bg-pink-100">
                                          <td class="py-3 px-6">{{ shade.name }}</td>
                                          <td class="py-3 px-6">{{ shade.color_code }}</td>
                                          <td class="py-3 px-6">
                                              <div :style="{ backgroundColor: shade.color_code, width: '50px', height: '20px' }" class="rounded"></div>
                                          </td>
                                          <td class="py-3 px-6">{{ shade.created_by }}</td>
                                          <td class="py-3 px-6">{{ formatDate(shade.created_at) }}</td>
                                          <td class="py-3 px-6">{{ shade.updated_at ? formatDate(shade.updated_at) : '-' }}</td>
                                          <td class="py-3 px-6 flex items-center">
                                              <button @click="editShade(shade)" class="text-gray-500 hover:text-gray-700 mr-3">
                                                  <i class="fas fa-edit"></i>
                                              </button>
                                              <button @click="openDeleteModal(shade)" class="text-red-500 hover:text-red-700">
                                                  <i class="fas fa-trash"></i>
                                              </button>
                                          </td>
                                      </tr>
                                  </tbody>
                              </table>
                          </div>

                          <!-- Pagination Section -->
                          <div v-if="totalPages > 1" class="flex justify-end mt-4">
                              <div class="flex items-center space-x-2">
                                  <button
                                      @click="prevPage"
                                      class="text-pink-600 hover:text-pink-800"
                                      :disabled="currentPage === 1"
                                  >
                                      <i class="fas fa-arrow-left" :class="{ 'text-gray-300': currentPage === 1, 'text-pink-600': currentPage > 1 }"></i>
                                  </button>
                                  <button
                                      @click="nextPage"
                                      class="text-pink-600 hover:text-pink-800"
                                      :disabled="currentPage === totalPages"
                                  >
                                      <i class="fas fa-arrow-right" :class="{ 'text-gray-300': currentPage === totalPages, 'text-pink-600': currentPage < totalPages }"></i>
                                  </button>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </AuthenticatedLayout>
</template>