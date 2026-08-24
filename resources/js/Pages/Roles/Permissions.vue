<script setup>
import { computed } from "vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    role: {
        type: Object,
        required: true,
    },

    permissions: {
        type: Array,
        default: () => [],
    },

    rolePermissions: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();

const permissions = computed(() => {
    return page.props.auth?.permissions ?? [];
});

const isSuperadmin = computed(() => {
    return page.props.auth?.is_superadmin ?? false;
});

function can(permission) {
    return isSuperadmin.value || permissions.value.includes(permission);
}

/*
|--------------------------------------------------------------------------
| Form
|--------------------------------------------------------------------------
*/

const form = useForm({
    permissions: [...props.rolePermissions],
});

/*
|--------------------------------------------------------------------------
| Permission Group
|--------------------------------------------------------------------------
*/

const groupedPermissions = computed(() => {
    const groups = {};

    props.permissions.forEach((permission) => {
        const parts = permission.name.split(".");

        const module = parts[0] || "other";

        if (!groups[module]) {
            groups[module] = [];
        }

        groups[module].push(permission);
    });

    return groups;
});

/*
|--------------------------------------------------------------------------
| Module Label
|--------------------------------------------------------------------------
*/

function moduleLabel(module) {
    const labels = {
        dashboard: "Dashboard",
        users: "Users",
        roles: "Roles",
        settings: "Settings",
        profile: "Profile",
    };

    if (labels[module]) {
        return labels[module];
    }

    return module
        .replace(/[-_]/g, " ")
        .replace(/\b\w/g, (letter) => letter.toUpperCase());
}

/*
|--------------------------------------------------------------------------
| Permission Label
|--------------------------------------------------------------------------
*/

function permissionLabel(permission) {
    const action = permission.name.split(".").pop();

    const labels = {
        view: "Lihat",
        create: "Tambah",
        edit: "Edit",
        update: "Edit",
        delete: "Hapus",
        show: "Detail",
        export: "Export",
        import: "Import",
        approve: "Approve",
        reject: "Reject",
    };

    return labels[action] ?? action;
}

/*
|--------------------------------------------------------------------------
| Check Permission
|--------------------------------------------------------------------------
*/

function hasPermission(permissionId) {
    return form.permissions.includes(permissionId);
}

/*
|--------------------------------------------------------------------------
| Select All Module
|--------------------------------------------------------------------------
*/

function isModuleSelected(modulePermissions) {
    if (!modulePermissions.length) {
        return false;
    }

    return modulePermissions.every((permission) =>
        hasPermission(permission.id),
    );
}

/*
|--------------------------------------------------------------------------
| Toggle Module
|--------------------------------------------------------------------------
*/

function toggleModule(modulePermissions) {
    if (!can("permissions.edit")) {
        return;
    }

    const allSelected = isModuleSelected(modulePermissions);

    if (allSelected) {
        const idsToRemove = modulePermissions.map(
            (permission) => permission.id,
        );

        form.permissions = form.permissions.filter(
            (id) => !idsToRemove.includes(id),
        );

        return;
    }

    const idsToAdd = modulePermissions.map((permission) => permission.id);

    form.permissions = [...new Set([...form.permissions, ...idsToAdd])];
}

/*
|--------------------------------------------------------------------------
| Select All Permissions
|--------------------------------------------------------------------------
*/

const allPermissionsSelected = computed(() => {
    if (!props.permissions.length) {
        return false;
    }

    return props.permissions.every((permission) =>
        hasPermission(permission.id),
    );
});

function toggleAllPermissions() {
    if (!can("permissions.edit")) {
        return;
    }

    if (allPermissionsSelected.value) {
        form.permissions = [];
        return;
    }

    form.permissions = props.permissions.map((permission) => permission.id);
}

/*
|--------------------------------------------------------------------------
| Submit
|--------------------------------------------------------------------------
*/

function submit() {
    form.put(route("roles.permissions.update", props.role.id), {
        preserveScroll: true,
    });
}
</script>

<template>
    <div>
        <!-- Page Header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <div class="page-pretitle">Manajemen</div>

                        <h2 class="page-title">Kelola Permission</h2>
                    </div>

                    <div class="col-auto ms-auto d-print-none">
                        <Link :href="route('roles.index')" class="btn">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon"
                            >
                                <path d="M5 12h14" />
                                <path d="m5 12 6-6" />
                                <path d="m5 12 6 6" />
                            </svg>

                            Kembali
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Body -->
        <div class="page-body">
            <div class="container-xl">
                <!-- Role Information -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="avatar avatar-lg me-3">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-lg"
                                >
                                    <path
                                        d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z"
                                    />
                                    <path d="M20 21a8 8 0 0 0-16 0" />
                                </svg>
                            </span>

                            <div>
                                <div class="text-secondary">Role</div>

                                <h2 class="mb-0">
                                    {{ role.name }}
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permission Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header">
                        <div class="row align-items-center w-100">
                            <div class="col">
                                <h3 class="card-title">Permissions</h3>

                                <div class="text-secondary mt-1">
                                    Tentukan akses yang dimiliki oleh role ini.
                                </div>
                            </div>

                            <div class="col-auto">
                                <button
                                    v-if="can('permissions.edit')"
                                    type="button"
                                    class="btn btn-outline-primary"
                                    @click="toggleAllPermissions"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon"
                                    >
                                        <path d="M9 11l3 3L22 4" />
                                        <path
                                            d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"
                                        />
                                    </svg>

                                    {{
                                        allPermissionsSelected
                                            ? "Batalkan Semua"
                                            : "Pilih Semua"
                                    }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Permission Groups -->
                    <div class="card-body p-0">
                        <div v-if="permissions.length === 0" class="empty py-5">
                            <div class="empty-icon">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-lg"
                                >
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 8v4" />
                                    <path d="M12 16h.01" />
                                </svg>
                            </div>

                            <p class="empty-title">Belum ada permission</p>

                            <p class="empty-subtitle text-secondary">
                                Silakan buat permission terlebih dahulu.
                            </p>
                        </div>

                        <div
                            v-for="(
                                modulePermissions, module
                            ) in groupedPermissions"
                            :key="module"
                            class="border-bottom"
                        >
                            <!-- Module Header -->
                            <div class="px-4 py-3 bg-light-lt">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="mb-0 text-uppercase">
                                            {{ moduleLabel(module) }}
                                        </h3>
                                    </div>

                                    <div class="col-auto">
                                        <label
                                            class="form-check form-switch mb-0"
                                        >
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                :checked="
                                                    isModuleSelected(
                                                        modulePermissions,
                                                    )
                                                "
                                                :disabled="
                                                    !can('permissions.edit')
                                                "
                                                @change="
                                                    toggleModule(
                                                        modulePermissions,
                                                    )
                                                "
                                            />

                                            <span class="form-check-label">
                                                Pilih Semua
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Permission List -->
                            <div class="p-4">
                                <div class="row row-cards">
                                    <div
                                        v-for="permission in modulePermissions"
                                        :key="permission.id"
                                        class="col-sm-6 col-lg-3"
                                    >
                                        <label class="form-check">
                                            <input
                                                v-model="form.permissions"
                                                class="form-check-input"
                                                type="checkbox"
                                                :value="permission.id"
                                                :disabled="
                                                    !can('permissions.edit')
                                                "
                                            />

                                            <span class="form-check-label">
                                                {{
                                                    permissionLabel(permission)
                                                }}
                                            </span>

                                            <span
                                                class="form-check-description"
                                            >
                                                {{ permission.name }}
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer">
                        <div
                            class="d-flex align-items-center justify-content-between"
                        >
                            <div class="text-secondary">
                                {{ form.permissions.length }}
                                permission dipilih
                            </div>

                            <div class="btn-list">
                                <Link :href="route('roles.index')" class="btn">
                                    Batal
                                </Link>

                                <button
                                    v-if="can('permissions.edit')"
                                    type="button"
                                    class="btn btn-primary"
                                    :disabled="form.processing"
                                    @click="submit"
                                >
                                    <span
                                        v-if="form.processing"
                                        class="spinner-border spinner-border-sm me-2"
                                        role="status"
                                    ></span>

                                    <svg
                                        v-else
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon"
                                    >
                                        <path
                                            d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"
                                        />
                                        <path d="M17 21v-8H7v8" />
                                        <path d="M7 3v5h8" />
                                    </svg>

                                    Simpan Permission
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
