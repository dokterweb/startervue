<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

import { IconUsers, IconUserCheck, IconClock } from "@tabler/icons-vue";

defineOptions({
    layout: AppLayout,
});

const page = usePage();
</script>

<template>
    <Head title="Dashboard" />

    <!-- Page Header -->
    <div class="page-header d-print-none mb-3">
        <div class="container-fluid px-0">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">Dashboard</h2>

                    <div class="text-secondary mt-1">
                        Welcome back,
                        {{ page.props.auth?.user?.name ?? "User" }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="row row-deck row-cards">
        <!-- Total Users -->
        <div class="col-sm-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="subheader">Total Users</div>

                    <div class="h1 mb-3">0</div>

                    <div class="d-flex align-items-center text-secondary">
                        <IconUsers :size="18" class="me-2" />

                        Registered users
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="col-sm-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="subheader">Active Users</div>

                    <div class="h1 mb-3">0</div>

                    <div class="d-flex align-items-center text-secondary">
                        <IconUserCheck :size="18" class="me-2" />

                        Active accounts
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="col-sm-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="subheader">Pending</div>

                    <div class="h1 mb-3">0</div>

                    <div class="d-flex align-items-center text-secondary">
                        <IconClock :size="18" class="me-2" />

                        Pending items
                    </div>
                </div>
            </div>
        </div>

        <!-- Welcome -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Welcome to StarterVue</h3>
                </div>

                <div class="card-body">
                    <p class="text-secondary mb-0">
                        Your application is ready. From here we can start
                        building the application modules.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
