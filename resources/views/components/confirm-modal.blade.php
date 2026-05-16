<div x-data="confirmModal()" 
     @confirm.window="openModal($event.detail)"
     class="relative z-50"
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true"
     x-show="isOpen"
     style="display: none;">
    
    <div x-show="isOpen" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-stone/50 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="isOpen"
                 @click.away="closeModal()"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-mist">
                
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-10 sm:w-10"
                             :class="isDestructive ? 'bg-[#fef2f2] text-[#ef4444]' : 'bg-[#ecfdf5] text-[#10b981]'">
                            <template x-if="isDestructive">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </template>
                            <template x-if="!isDestructive">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                                </svg>
                            </template>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-forest" id="modal-title" x-text="title"></h3>
                            <div class="mt-2">
                                <p class="text-sm text-stone" x-text="message"></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-ash px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-mist gap-2">
                    <button type="button" 
                            @click="confirmAction()"
                            class="inline-flex w-full justify-center rounded-lg px-3 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto transition-colors"
                            :class="isDestructive ? 'bg-[#ef4444] hover:bg-[#dc2626]' : 'bg-forest hover:bg-[#1a3a29]'"
                            x-text="confirmText">
                    </button>
                    <button type="button" 
                            @click="closeModal()"
                            class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-stone shadow-sm ring-1 ring-inset ring-mist hover:bg-ash sm:mt-0 sm:w-auto transition-colors"
                            x-text="cancelText">
                    </button>
                </div>
                
                {{-- Hidden form for submissions --}}
                <form x-ref="confirmForm" method="POST" :action="actionUrl" class="hidden">
                    @csrf
                    <template x-if="method && method.toUpperCase() !== 'POST'">
                        <input type="hidden" name="_method" :value="method">
                    </template>
                </form>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('confirmModal', () => ({
            isOpen: false,
            title: '',
            message: '',
            confirmText: 'Konfirmasi',
            cancelText: 'Batal',
            actionUrl: '',
            method: 'POST',
            isDestructive: true,

            openModal(detail) {
                this.title = detail.title || 'Konfirmasi Tindakan';
                this.message = detail.message || 'Apakah Anda yakin ingin melanjutkan?';
                this.confirmText = detail.confirmText || 'Konfirmasi';
                this.cancelText = detail.cancelText || 'Batal';
                this.actionUrl = detail.actionUrl;
                this.method = detail.method || 'POST';
                this.isDestructive = detail.isDestructive !== undefined ? detail.isDestructive : true;
                this.isOpen = true;
            },
            
            closeModal() {
                this.isOpen = false;
            },
            
            confirmAction() {
                if (this.actionUrl) {
                    this.$refs.confirmForm.submit();
                }
                this.isOpen = false;
            }
        }));
    });
</script>
@endpush
