<div x-data
     x-show="$store.confirmModal.open"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4"
     style="display: none;"
     x-cloak>
    
    <div @click.outside="$store.confirmModal.cancel()"
         class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 sm:p-7 space-y-5 border border-slate-200/80 relative overflow-hidden text-left">
        
        <!-- Header Icon & Title -->
        <div class="flex items-start gap-3.5">
            <!-- Dynamic Variant Icon -->
            <template x-if="$store.confirmModal.variant === 'danger'">
                <div class="w-11 h-11 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </template>

            <template x-if="$store.confirmModal.variant === 'warning'">
                <div class="w-11 h-11 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </template>

            <template x-if="$store.confirmModal.variant === 'info' || $store.confirmModal.variant === 'emerald'">
                <div class="w-11 h-11 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </template>

            <div>
                <h3 class="font-bold text-slate-900 text-lg sm:text-xl leading-tight" x-text="$store.confirmModal.title"></h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mt-1.5" x-text="$store.confirmModal.message"></p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
            <button type="button" 
                    @click="$store.confirmModal.cancel()" 
                    class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all cursor-pointer min-h-[40px]"
                    x-text="$store.confirmModal.cancelText">
            </button>

            <button type="button" 
                    @click="$store.confirmModal.confirm()" 
                    :class="{
                        'bg-rose-600 hover:bg-rose-700 text-white shadow-md shadow-rose-600/20': $store.confirmModal.variant === 'danger',
                        'bg-amber-600 hover:bg-amber-700 text-white shadow-md shadow-amber-600/20': $store.confirmModal.variant === 'warning',
                        'bg-emerald-600 hover:bg-emerald-700 text-white shadow-md shadow-emerald-600/20': $store.confirmModal.variant === 'info' || $store.confirmModal.variant === 'emerald'
                    }"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs transition-all cursor-pointer min-h-[40px]"
                    x-text="$store.confirmModal.confirmText">
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('confirmModal', {
            open: false,
            title: 'Konfirmasi Tindakan',
            message: 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
            confirmText: 'Ya, Lanjutkan',
            cancelText: 'Batal',
            variant: 'danger',
            onConfirm: null,

            show({ title, message, confirmText = 'Ya, Lanjutkan', cancelText = 'Batal', variant = 'danger', onConfirm = null }) {
                this.title = title || 'Konfirmasi Tindakan';
                this.message = message || 'Apakah Anda yakin ingin melanjutkan?';
                this.confirmText = confirmText;
                this.cancelText = cancelText;
                this.variant = variant;
                this.onConfirm = onConfirm;
                this.open = true;
            },

            confirm() {
                this.open = false;
                if (typeof this.onConfirm === 'function') {
                    this.onConfirm();
                }
            },

            cancel() {
                this.open = false;
            }
        });
    });

    window.confirmAction = function(options) {
        if (window.Alpine && window.Alpine.store('confirmModal')) {
            window.Alpine.store('confirmModal').show(options);
        } else {
            if (confirm(options.message || options.title)) {
                if (options.onConfirm) options.onConfirm();
            }
        }
    };
</script>
