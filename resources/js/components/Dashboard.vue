<template>
  <div>
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Incident Dashboard</h1>
        <p class="mt-1 text-sm text-slate-500">Overview of all security incidents and their status.</p>
      </div>
      <div class="mt-4 sm:mt-0">
        <button 
          @click="showCreateModal = true"
          class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Report Incident
        </button>
      </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
      <div class="bg-white overflow-hidden shadow rounded-lg border border-slate-100">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
              <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-slate-500 truncate">Total Incidents</dt>
                <dd class="text-lg font-bold text-slate-900">{{ incidents.total || 0 }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white overflow-hidden shadow rounded-lg border border-slate-100">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
              <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-slate-500 truncate">Open / In Progress</dt>
                <dd class="text-lg font-bold text-slate-900">
                    {{ (incidents.data || []).filter(i => ['open', 'in_progress'].includes(i.status)).length }}
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
      
       <!-- Add more stats if needed -->
    </div>

    <!-- Filters & Search (Optional placeholder) -->
    <div class="bg-white shadow rounded-lg border border-slate-100 mb-6">
        <div class="p-4 sm:flex sm:items-center sm:justify-between border-b border-slate-100">
            <h3 class="text-lg leading-6 font-medium text-slate-900">Recent Incidents</h3>
             <div class="mt-3 sm:mt-0 flex space-x-2">
                 <!-- Could add filter buttons here -->
             </div>
        </div>
        
        <!-- Incident List -->
        <IncidentList 
            :incidents="incidents.data" 
            :loading="loading" 
            @edit="openEditModal" 
            @delete="confirmDelete" 
        />
        
        <!-- Pagination (Simplified) -->
        <div v-if="incidents.last_page > 1" class="bg-white px-4 py-3 flex items-center justify-between border-t border-slate-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <button @click="fetchIncidents(incidents.current_page - 1)" :disabled="incidents.current_page === 1" class="relative inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 disabled:opacity-50">Previous</button>
                <button @click="fetchIncidents(incidents.current_page + 1)" :disabled="incidents.current_page === incidents.last_page" class="ml-3 relative inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 disabled:opacity-50">Next</button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-slate-700">
                        Showing <span class="font-medium">{{ incidents.from }}</span> to <span class="font-medium">{{ incidents.to }}</span> of <span class="font-medium">{{ incidents.total }}</span> results
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <button 
                            @click="fetchIncidents(incidents.current_page - 1)" 
                            :disabled="incidents.current_page === 1"
                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-slate-300 bg-white text-sm font-medium text-slate-500 hover:bg-slate-50 disabled:opacity-50"
                        >
                            <span class="sr-only">Previous</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <button 
                             @click="fetchIncidents(incidents.current_page + 1)"
                             :disabled="incidents.current_page === incidents.last_page"
                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-slate-300 bg-white text-sm font-medium text-slate-500 hover:bg-slate-50 disabled:opacity-50"
                        >
                            <span class="sr-only">Next</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <IncidentForm 
        v-if="showCreateModal" 
        @close="showCreateModal = false" 
        @saved="onSaved"
    />
    
    <IncidentForm 
        v-if="showEditModal" 
        :incident="editingIncident"
        @close="showEditModal = false" 
        @saved="onSaved"
    />

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { api } from '../services/api';
import IncidentList from './IncidentList.vue';
import IncidentForm from './IncidentForm.vue';

const props = defineProps({
  user: Object
});

const incidents = ref({});
const loading = ref(false);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const editingIncident = ref(null);

const fetchIncidents = async (page = 1) => {
  loading.value = true;
  try {
    const { data } = await api.get(`/api/incidents?page=${page}`);
    incidents.value = data;
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
};

const openEditModal = (incident) => {
    editingIncident.value = incident;
    showEditModal.value = true;
};

const onSaved = () => {
    showCreateModal.value = false;
    showEditModal.value = false;
    editingIncident.value = null;
    fetchIncidents();
};

const confirmDelete = async (id) => {
    if (confirm('Are you sure you want to delete this incident?')) {
        try {
            await api.delete(`/api/incidents/${id}`);
            fetchIncidents();
        } catch (e) {
            alert('Failed to delete incident');
        }
    }
};

onMounted(() => {
  fetchIncidents();
});
</script>
