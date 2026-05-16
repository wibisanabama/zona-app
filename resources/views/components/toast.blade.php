<div x-data="toastManager()" 
     class="fixed bottom-4 right-4 z-50 flex flex-col gap-2"
     @notify.window="addToast($event.detail)">
    
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.visible"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-4"
             class="px-4 py-3 rounded-lg shadow-lg flex items-start gap-3 min-w-[300px] border"
             :class="{
                 'bg-[#ecfdf5] border-[#10b981] text-[#047857]': toast.type === 'success',
                 'bg-[#fef2f2] border-[#ef4444] text-[#b91c1c]': toast.type === 'error',
                 'bg-[#fffbeb] border-[#f59e0b] text-[#b45309]': toast.type === 'warning',
                 'bg-white border-mist text-forest': toast.type === 'info'
             }"
             role="alert">
             
            {{-- Icons based on type --}}
            <div class="mt-0.5">
                <template x-if="toast.type === 'success'">
                    <svg class="w-5 h-5 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </template>
                <template x-if="toast.type === 'error'">
                    <svg class="w-5 h-5 text-[#ef4444]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </template>
                <template x-if="toast.type === 'warning'">
                    <svg class="w-5 h-5 text-[#f59e0b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </template>
                <template x-if="toast.type === 'info'">
                    <svg class="w-5 h-5 text-olive" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </template>
            </div>

            <div class="flex-1">
                <p class="text-sm font-medium" x-text="toast.message"></p>
            </div>
            
            <button @click="removeToast(toast.id)" class="text-current opacity-50 hover:opacity-100 focus:outline-none" aria-label="Tutup notifikasi">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </template>

    {{-- Script to catch Laravel Session messages on load --}}
    @if(session('success'))
        <div x-init="$dispatch('notify', { message: '{{ session('success') }}', type: 'success' })"></div>
    @endif
    @if(session('error'))
        <div x-init="$dispatch('notify', { message: '{{ session('error') }}', type: 'error' })"></div>
    @endif
    @if(session('warning'))
        <div x-init="$dispatch('notify', { message: '{{ session('warning') }}', type: 'warning' })"></div>
    @endif
    @if($errors->any())
        <div x-init="$dispatch('notify', { message: 'Terdapat kesalahan pada form. Silakan periksa kembali.', type: 'error' })"></div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('toastManager', () => ({
            toasts: [],
            addToast(toast) {
                const id = Date.now();
                this.toasts.push({
                    id: id,
                    message: toast.message,
                    type: toast.type || 'info',
                    visible: true
                });

                setTimeout(() => {
                    this.removeToast(id);
                }, 5000); // 5 seconds duration
            },
            removeToast(id) {
                const index = this.toasts.findIndex(t => t.id === id);
                if (index !== -1) {
                    this.toasts[index].visible = false;
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 300); // Wait for transition
                }
            }
        }));
    });
</script>
@endpush
