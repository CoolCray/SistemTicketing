<template>
  <div v-if="showModal" class="fixed inset-0 z-50 flex items-center bg-black/20 justify-center bg-opacity-50 backdrop-blur-sm px-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm transform transition-all overflow-hidden border border-gray-100 relative">
      <button @click="$emit('close')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
      </button>

      <div class="p-6">
        <div class="flex items-center justify-center w-14 h-14 mx-auto mb-4 bg-red-100 rounded-full">
          <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-center text-gray-900">{{ title || 'Konfirmasi Hapus' }}</h3>
        <p class="mt-3 text-sm text-center text-gray-600">
          {{ message || 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.' }}
        </p>
      </div>
      <div class="flex px-6 py-4 bg-gray-50 border-t border-gray-100 justify-center gap-3">
        <button @click="$emit('close')" class="px-5 py-2.5 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 transition">
          Batal
        </button>
        <button @click="$emit('confirm')" class="px-5 py-2.5 bg-rose-600 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition">
          Ya, Hapus
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  showModal: {
    type: Boolean,
    required: true
  },
  title: {
    type: String,
    default: 'Konfirmasi Hapus'
  },
  message: {
    type: String,
    default: 'Apakah Anda yakin ingin menghapus data ini? Semua data yang berelasi mungkin akan ikut terhapus.'
  }
});

defineEmits(['close', 'confirm']);
</script>