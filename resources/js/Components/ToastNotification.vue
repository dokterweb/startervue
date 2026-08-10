<script setup>
import { ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";

import { IconCheck, IconX, IconAlertCircle } from "@tabler/icons-vue";

const page = usePage();

const visible = ref(false);
const type = ref("success");
const message = ref("");

let timeout = null;

const showToast = (toastType, toastMessage) => {
    if (!toastMessage) {
        return;
    }

    type.value = toastType;
    message.value = toastMessage;
    visible.value = true;

    clearTimeout(timeout);

    timeout = setTimeout(() => {
        visible.value = false;
    }, 3500);
};

const closeToast = () => {
    visible.value = false;
    clearTimeout(timeout);
};

watch(
    () => page.props.flash?.success,
    (value) => {
        if (value) {
            showToast("success", value);
        }
    }
);

watch(
    () => page.props.flash?.error,
    (value) => {
        if (value) {
            showToast("error", value);
        }
    }
);
</script>

<template>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <Transition name="toast">
            <div
                v-if="visible"
                class="toast show"
                role="alert"
                aria-live="assertive"
                aria-atomic="true"
            >
                <div class="toast-header">
                    <!-- Success -->
                    <IconCheck
                        v-if="type === 'success'"
                        :size="20"
                        class="me-2 text-success"
                    />

                    <!-- Error -->
                    <IconX v-else :size="20" class="me-2 text-danger" />

                    <strong class="me-auto">
                        {{ type === "success" ? "Berhasil" : "Gagal" }}
                    </strong>

                    <button
                        type="button"
                        class="btn-close"
                        aria-label="Close"
                        @click="closeToast"
                    ></button>
                </div>

                <div class="toast-body">
                    <IconAlertCircle
                        :size="18"
                        class="me-1"
                        v-if="type === 'error'"
                    />

                    {{ message }}
                </div>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
.toast-enter-active,
.toast-leave-active {
    transition: opacity 0.25s ease, transform 0.25s ease;
}

.toast-enter-from,
.toast-leave-to {
    opacity: 0;
    transform: translateX(30px);
}
</style>
