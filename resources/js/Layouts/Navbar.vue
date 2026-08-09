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
                        <span class="avatar avatar-sm"> U </span>

                        <div class="d-none d-xl-block ps-2">
                            <div>User</div>

                            <div class="mt-1 small text-secondary">
                                Administrator
                            </div>
                        </div>
                    </a>

                    <div
                        class="dropdown-menu dropdown-menu-end dropdown-menu-arrow"
                    >
                        <a class="dropdown-item" href="#">
                            <IconUser
                                class="icon dropdown-item-icon"
                                :size="18"
                            />
                            Profile
                        </a>

                        <a class="dropdown-item" href="#">
                            <IconSettings
                                class="icon dropdown-item-icon"
                                :size="18"
                            />
                            Settings
                        </a>

                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item" href="#">
                            <IconLogout
                                class="icon dropdown-item-icon"
                                :size="18"
                            />
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>

<script setup>
import { IconUser, IconSettings, IconLogout } from "@tabler/icons-vue";
</script>
