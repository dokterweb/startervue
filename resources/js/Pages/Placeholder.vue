<template>
    <div>
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">StarterVue</div>

                    <h2 class="page-title">
                        {{ title }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="empty">
                    <div class="empty-icon">
                        <IconInfoCircle :size="48" stroke-width="1.5" />
                    </div>

                    <p class="empty-title">Halaman {{ title }}</p>

                    <p class="empty-subtitle text-secondary">
                        Halaman ini masih berupa placeholder.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "../Layouts/AppLayout.vue";
import { IconInfoCircle } from "@tabler/icons-vue";

defineOptions({
    layout: AppLayout,
});

defineProps({
    title: {
        type: String,
        required: true,
    },
});
</script>
