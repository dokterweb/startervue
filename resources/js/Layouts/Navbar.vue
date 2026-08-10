<template>
    <header class="navbar navbar-expand-md d-print-none">
        <div class="container-fluid">
            <!-- Mobile sidebar button -->
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

            <!-- Right menu -->
            <div class="navbar-nav flex-row order-md-last ms-auto">
                <div class="nav-item dropdown">
                    <a
                        href="#"
                        class="nav-link d-flex lh-1 text-reset p-0"
                        data-bs-toggle="dropdown"
                        aria-label="Open user menu"
                    >
                        <!-- Avatar -->
                        <span class="avatar avatar-sm">
                            {{ user?.name?.charAt(0)?.toUpperCase() ?? "U" }}
                        </span>

                        <!-- User information -->
                        <div class="d-none d-xl-block ps-2">
                            <div>
                                {{ user?.name ?? "User" }}
                            </div>

                            <div class="mt-1 small text-secondary">
                                {{ user?.email ?? "" }}
                            </div>
                        </div>
                    </a>

                    <!-- Dropdown -->
                    <div
                        class="dropdown-menu dropdown-menu-end dropdown-menu-arrow"
                    >
                        <!-- Profile -->
                        <Link
                            :href="route('profile.edit')"
                            class="dropdown-item"
                        >
                            <IconUser
                                class="icon dropdown-item-icon"
                                :size="18"
                            />

                            Profile
                        </Link>

                        <!-- Settings -->
                        <Link
                            :href="route('settings.index')"
                            class="dropdown-item"
                        >
                            <IconSettings
                                class="icon dropdown-item-icon"
                                :size="18"
                            />

                            Settings
                        </Link>

                        <div class="dropdown-divider"></div>

                        <!-- Logout -->
                        <button
                            type="button"
                            class="dropdown-item"
                            @click="logout"
                        >
                            <IconLogout
                                class="icon dropdown-item-icon"
                                :size="18"
                            />

                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>

<script setup>
import { computed } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";

import { IconUser, IconSettings, IconLogout } from "@tabler/icons-vue";

const page = usePage();

const user = computed(() => page.props.auth?.user);

const logout = () => {
    router.post(route("logout"));
};
</script>
