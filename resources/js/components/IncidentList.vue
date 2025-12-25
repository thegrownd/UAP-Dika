<template>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Title</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Severity</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date</th>
            <th scope="col" class="relative px-6 py-3">
              <span class="sr-only">Actions</span>
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-slate-200">
          <tr v-if="loading">
              <td colspan="5" class="px-6 py-4 text-center text-sm text-slate-500">Loading incidents...</td>
          </tr>
          <tr v-else-if="!incidents || incidents.length === 0">
              <td colspan="5" class="px-6 py-4 text-center text-sm text-slate-500">No incidents found.</td>
          </tr>
          <tr v-else v-for="incident in incidents" :key="incident.id" class="hover:bg-slate-50 transition-colors">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-slate-900">{{ incident.title }}</div>
              <div class="text-sm text-slate-500 truncate max-w-xs">{{ incident.description }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="[
                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                severityColor(incident.severity)
              ]">
                {{ incident.severity }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span :class="[
                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                    statusColor(incident.status)
                  ]">
                    {{ incident.status.replace('_', ' ') }}
                  </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
              {{ new Date(incident.occurred_at || incident.created_at).toLocaleDateString() }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <button @click="$emit('edit', incident)" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</button>
              <button @click="$emit('delete', incident.id)" class="text-red-600 hover:text-red-900">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script setup>
  defineProps({
    incidents: Array,
    loading: Boolean
  });
  
  const severityColor = (s) => {
    switch(s) {
        case 'critical': return 'bg-red-100 text-red-800';
        case 'high': return 'bg-orange-100 text-orange-800';
        case 'medium': return 'bg-yellow-100 text-yellow-800';
        case 'low': return 'bg-green-100 text-green-800';
        default: return 'bg-slate-100 text-slate-800';
    }
  };

  const statusColor = (s) => {
      switch(s) {
          case 'open': return 'bg-blue-100 text-blue-800';
          case 'in_progress': return 'bg-purple-100 text-purple-800';
          case 'resolved': return 'bg-green-100 text-green-800';
          case 'closed': return 'bg-slate-100 text-slate-800';
          default: return 'bg-slate-100 text-slate-800';
      }
  }
  </script>
