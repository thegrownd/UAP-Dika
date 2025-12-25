<template>
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
      <div class="px-8 py-10">
        <div class="text-center mb-8">
          <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Welcome back</h2>
          <p class="mt-2 text-sm text-slate-500">Sign in to your account to continue</p>
        </div>
  
        <form @submit.prevent="login" class="space-y-6">
          <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email address</label>
            <input 
              v-model="email" 
              id="email"
              type="email" 
              required 
              class="appearance-none block w-full px-4 py-3 border border-slate-300 rounded-lg placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-shadow"
              placeholder="you@example.com"
            />
          </div>
  
          <div>
            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
            <input 
              v-model="password" 
              id="password"
              type="password" 
              required 
              minlength="8" 
              class="appearance-none block w-full px-4 py-3 border border-slate-300 rounded-lg placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-shadow"
              placeholder="••••••••"
            />
          </div>
  
          <div v-if="error" class="rounded-lg bg-red-50 p-4 border border-red-100 flex items-start">
            <svg class="h-5 w-5 text-red-400 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-red-800">Login Failed</h3>
              <p class="text-sm text-red-700 mt-1">{{ error }}</p>
            </div>
          </div>
  
          <button 
            type="submit" 
            :disabled="loading"
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
          >
            <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ loading ? 'Signing in...' : 'Sign in' }}
          </button>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { api } from '../services/api';
  
  const email = ref('');
  const password = ref('');
  const error = ref('');
  const loading = ref(false);
  const emit = defineEmits(['logged-in']);
  
  const login = async () => {
    error.value = '';
    loading.value = true;
    try {
      const { data } = await api.post('/api/auth/login', { email: email.value, password: password.value });
      emit('logged-in', data.token);
    } catch (e) {
      error.value = e.response?.data?.message || 'Invalid email or password.';
    } finally {
      loading.value = false;
    }
  };
  </script>
