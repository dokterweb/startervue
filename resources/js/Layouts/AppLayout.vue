<template>
    <div class="page">
        <!-- Sidebar -->
        <Sidebar />

        <!-- Page wrapper -->
        <div class="page-wrapper">
            <!-- Navbar -->
            <Navbar />

            <!-- Main content -->
            <div class="page-body">
                <div class="container-fluid px-3">
                    <slot />
                </div>
            </div>

            <!-- Footer -->
            <Footer />
        </div>
        <ToastNotification />
    </div>
</template>

<script setup>
import ToastNotification from "@/Components/ToastNotification.vue";
import Sidebar from "./Sidebar.vue";
import Navbar from "./Navbar.vue";
import Footer from "./Footer.vue";
</script>
