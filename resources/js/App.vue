<script setup>
import { ref, computed } from 'vue';
import Login from './components/Login.vue';
import Register from './components/Register.vue';
import Dashboard from './components/Dashboard.vue';
import { api, getToken, setToken, clearToken } from './services/api';

const route = ref(getToken() ? 'dashboard' : 'login');
const user = ref(null);
const isSidebarOpen = ref(false);

const isLoggedIn = computed(() => !!user.value || !!getToken());

const onLoggedIn = async (token) => {
    setToken(token);
    route.value = 'dashboard';
    try {
        const { data } = await api.get('/api/auth/me');
        user.value = data;
    } catch (e) {
        console.error('Failed to fetch user', e);
        onLoggedOut();
    }
};

const onLoggedOut = () => {
    clearToken();
    route.value = 'login';
    user.value = null;
    isSidebarOpen.value = false;
};

const go = (r) => {
    route.value = r;
    isSidebarOpen.value = false; // Close sidebar on mobile navigation
};

// Initial check
if (getToken()) {
    api.get('/api/auth/me')
        .then(({ data }) => {
            user.value = data;
        })
        .catch(() => {
            onLoggedOut();
        });
}
</script>

<template>
  <div class="min-h-screen bg-slate-50 font-sans text-slate-900">
    <!-- Mobile Header -->
    <header v-if="isLoggedIn" class="lg:hidden bg-white shadow-sm px-4 py-3 flex items-center justify-between sticky top-0 z-20">
      <div class="font-bold text-lg text-indigo-600">SecurityOps</div>
      <button @click="isSidebarOpen = !isSidebarOpen" class="text-slate-500 hover:text-indigo-600 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </header>

    <div class="flex h-screen overflow-hidden">
      <!-- Sidebar / Navigation -->
      <aside :class="[
          'fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-slate-200 transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0',
          isSidebarOpen ? 'translate-x-0' : '-translate-x-full'
        ]" v-if="isLoggedIn">
        
        <div class="h-full flex flex-col">
          <div class="h-16 flex items-center px-6 border-b border-slate-100">
            <span class="text-xl font-bold text-indigo-600 tracking-tight">SecurityOps</span>
          </div>

          <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <button @click="go('dashboard')" :class="['w-full flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors', route === 'dashboard' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900']">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
              </svg>
              Dashboard
            </button>
            
            <!-- Add more sidebar items here if needed -->
          </div>

          <div class="p-4 border-t border-slate-100">
            <div class="flex items-center mb-3 px-2">
              <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                {{ user?.name?.charAt(0).toUpperCase() || 'U' }}
              </div>
              <div class="ml-3">
                <p class="text-sm font-medium text-slate-900 truncate w-32">{{ user?.name || 'User' }}</p>
                <p class="text-xs text-slate-500 capitalize">{{ user?.role || 'Guest' }}</p>
              </div>
            </div>
            <button @click="onLoggedOut" class="w-full flex items-center justify-center px-4 py-2 border border-slate-200 rounded-lg shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
              </svg>
              Sign out
            </button>
          </div>
        </div>
      </aside>

      <!-- Overlay for mobile sidebar -->
      <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-20 lg:hidden backdrop-blur-sm transition-opacity"></div>

      <!-- Main Content -->
      <main class="flex-1 overflow-y-auto h-full bg-slate-50 relative">
        <div v-if="!isLoggedIn" class="h-full flex flex-col">
           <!-- Public Navbar -->
           <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-10 border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
              <div class="flex justify-between h-16">
                <div class="flex items-center">
                  <span class="text-xl font-bold text-indigo-600">SecurityOps</span>
                </div>
                <div class="flex items-center space-x-4">
                  <button @click="go('login')" :class="['text-sm font-medium transition-colors', route === 'login' ? 'text-indigo-600' : 'text-slate-500 hover:text-slate-900']">Log in</button>
                  <button @click="go('register')" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Get Started
                  </button>
                </div>
              </div>
            </div>
          </nav>

          <div class="flex-1 flex items-center justify-center p-4">
            <div class="w-full max-w-md">
              <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
                mode="out-in"
              >
                <Login v-if="route==='login'" @logged-in="onLoggedIn" key="login" />
                <Register v-else-if="route==='register'" @registered="onLoggedIn" key="register" />
              </Transition>
            </div>
          </div>
        </div>

        <div v-else class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">
          <Dashboard :user="user" />
        </div>
      </main>
    </div>
  </div>
</template>
