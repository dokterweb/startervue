<template>
    <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
        <div class="container-fluid">
            <!-- Mobile Toggle -->
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#sidebar-menu"
                aria-controls="sidebar-menu"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Logo -->
            <h1 class="navbar-brand navbar-brand-autodark">
                <Link href="/dashboard"> StarterVue </Link>
            </h1>

            <!-- Sidebar -->
            <div class="collapse navbar-collapse" id="sidebar-menu">
                <ul class="navbar-nav pt-lg-3">
                    <!-- Dashboard -->
                    <li
                        class="nav-item"
                        :class="{
                            active: isActive('/dashboard'),
                        }"
                    >
                        <Link :href="route('dashboard')" class="nav-link">
                            <span class="nav-link-icon">
                                <IconLayoutDashboard
                                    :size="20"
                                    stroke-width="1.5"
                                />
                            </span>

                            <span class="nav-link-title"> Dashboard </span>
                        </Link>
                    </li>

                    <!-- MASTER DATA -->
                    <li class="nav-item">
                        <div class="nav-link disabled">
                            <span class="nav-link-title"> MASTER DATA </span>
                        </div>
                    </li>

                    <!-- Users -->
                    <li
                        v-if="can('users.view')"
                        class="nav-item"
                        :class="{
                            active: isActive('/users'),
                        }"
                    >
                        <Link :href="route('users.index')" class="nav-link">
                            <span class="nav-link-icon">
                                <IconUsers :size="20" stroke-width="1.5" />
                            </span>

                            <span class="nav-link-title"> Users </span>
                        </Link>
                    </li>

                    <!-- Roles -->
                    <li
                        v-if="can('roles.view')"
                        class="nav-item"
                        :class="{
                            active: isActive('/roles'),
                        }"
                    >
                        <Link :href="route('roles.index')" class="nav-link">
                            <span class="nav-link-icon">
                                <IconShieldCheck
                                    :size="20"
                                    stroke-width="1.5"
                                />
                            </span>

                            <span class="nav-link-title"> Roles </span>
                        </Link>
                    </li>

                    <!-- Permissions -->
                    <li
                        v-if="can('permissions.view')"
                        class="nav-item"
                        :class="{
                            active: isActive('/permissions'),
                        }"
                    >
                        <Link
                            :href="route('permissions.index')"
                            class="nav-link"
                        >
                            <span class="nav-link-icon">
                                <IconLock :size="20" stroke-width="1.5" />
                            </span>

                            <span class="nav-link-title"> Permissions </span>
                        </Link>
                    </li>

                    <!-- TRANSAKSI -->
                    <li class="nav-item mt-3">
                        <div class="nav-link disabled">
                            <span class="nav-link-title"> TRANSAKSI </span>
                        </div>
                    </li>

                    <!-- Transaksi -->
                    <li
                        v-if="can('transaksi.view')"
                        class="nav-item"
                        :class="{
                            active: isActive('/transaksi'),
                        }"
                    >
                        <Link :href="route('transaksi.index')" class="nav-link">
                            <span class="nav-link-icon">
                                <IconFileInvoice
                                    :size="20"
                                    stroke-width="1.5"
                                />
                            </span>

                            <span class="nav-link-title"> Transaksi </span>
                        </Link>
                    </li>

                    <!-- Pembayaran -->
                    <li
                        v-if="can('pembayaran.view')"
                        class="nav-item"
                        :class="{
                            active: isActive('/pembayaran'),
                        }"
                    >
                        <Link
                            :href="route('pembayaran.index')"
                            class="nav-link"
                        >
                            <span class="nav-link-icon">
                                <IconCash :size="20" stroke-width="1.5" />
                            </span>

                            <span class="nav-link-title"> Pembayaran </span>
                        </Link>
                    </li>

                    <!-- Laporan -->
                    <li
                        v-if="can('laporan.view')"
                        class="nav-item"
                        :class="{
                            active: isActive('/laporan'),
                        }"
                    >
                        <Link :href="route('laporan.index')" class="nav-link">
                            <span class="nav-link-icon">
                                <IconReportAnalytics
                                    :size="20"
                                    stroke-width="1.5"
                                />
                            </span>

                            <span class="nav-link-title"> Laporan </span>
                        </Link>
                    </li>

                    <!-- SYSTEM -->
                    <li class="nav-item mt-3">
                        <div class="nav-link disabled">
                            <span class="nav-link-title"> SYSTEM </span>
                        </div>
                    </li>

                    <!-- Settings -->
                    <li
                        v-if="can('settings.view')"
                        class="nav-item"
                        :class="{
                            active: isActive('/settings'),
                        }"
                    >
                        <Link :href="route('settings.index')" class="nav-link">
                            <span class="nav-link-icon">
                                <IconSettings :size="20" stroke-width="1.5" />
                            </span>

                            <span class="nav-link-title"> Settings </span>
                        </Link>
                    </li>
                </ul>
            </div>
        </div>
    </aside>
</template>

<script setup>
import { usePage, Link } from "@inertiajs/vue3";

import {
    IconLayoutDashboard,
    IconUsers,
    IconShieldCheck,
    IconLock,
    IconFileInvoice,
    IconCash,
    IconReportAnalytics,
    IconSettings,
} from "@tabler/icons-vue";

import { usePermission } from "@/Composables/usePermission";

const page = usePage();

const { can } = usePermission();

const isActive = (path) => {
    return page.url === path;
};
</script>
