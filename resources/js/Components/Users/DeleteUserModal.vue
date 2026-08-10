<script setup>
import { computed } from "vue";
import { router } from "@inertiajs/vue3";

import { IconAlertTriangle, IconTrash } from "@tabler/icons-vue";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },

    user: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(["close"]);

const userName = computed(() => {
    return props.user?.name ?? "";
});

const close = () => {
    emit("close");
};

const deleteUser = () => {
    if (!props.user) {
        return;
    }

    router.delete(route("users.destroy", props.user.id), {
        preserveScroll: true,

        onSuccess: () => {
            emit("close");
        },
    });
};
</script>

<template>
    <Teleport to="body">
        <!-- Modal -->
        <div
            v-if="show"
            class="modal modal-blur fade show d-block"
            tabindex="-1"
            role="dialog"
            aria-modal="true"
        >
            <div
                class="modal-dialog modal-sm modal-dialog-centered"
                role="document"
            >
                <div class="modal-content">
                    <!-- Body -->
                    <div class="modal-body text-center py-4">
                        <div class="mb-3">
                            <span
                                class="avatar avatar-lg bg-danger-lt text-danger"
                            >
                                <IconAlertTriangle :size="28" />
                            </span>
                        </div>

                        <h3>Hapus User?</h3>

                        <div class="text-secondary">
                            Apakah Anda yakin ingin menghapus user
                            <strong>{{ userName }}</strong
                            >?
                        </div>

                        <div class="text-secondary small mt-2">
                            Data user yang dihapus tidak dapat dikembalikan.
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <div class="w-100">
                            <div class="row">
                                <div class="col">
                                    <button
                                        type="button"
                                        class="btn w-100"
                                        @click="close"
                                    >
                                        Batal
                                    </button>
                                </div>

                                <div class="col">
                                    <button
                                        type="button"
                                        class="btn btn-danger w-100"
                                        @click="deleteUser"
                                    >
                                        <IconTrash :size="17" class="me-1" />

                                        Ya, Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backdrop -->
        <div v-if="show" class="modal-backdrop fade show"></div>
    </Teleport>
</template>
