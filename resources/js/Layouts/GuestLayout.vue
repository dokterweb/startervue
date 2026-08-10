<template>
    <div class="guest-page">
        <div class="guest-container">
            <!-- Logo -->
            <div class="text-center mb-4">
                <Link href="/" class="text-decoration-none">
                    <div class="fw-bold fs-2 text-dark">StarterVue</div>

                    <div class="text-secondary small">
                        Laravel 13 + Vue + Inertia
                    </div>
                </Link>
            </div>

            <!-- Card -->
            <div class="card shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <slot />
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center text-secondary mt-4 small">
                © {{ year }} StarterVue
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

const year = new Date().getFullYear();
</script>

<style scoped>
.guest-page {
    min-height: 100vh;
    background: #f5f7fb;

    display: flex;
    align-items: center;
    justify-content: center;

    padding: 40px 20px;
}

.guest-container {
    width: 100%;
    max-width: 440px;
}

.guest-container .card {
    border: 1px solid var(--tblr-border-color);
    border-radius: 8px;
}

@media (max-width: 576px) {
    .guest-page {
        padding: 20px 15px;
    }
}
</style>
