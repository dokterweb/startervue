<script setup>
import { computed, ref, watch } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import AppLayout from "@/Layouts/AppLayout.vue";

defineOptions({
    layout: AppLayout,
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

const props = defineProps({
    roles: {
        type: Object,
        required: true,
    },

    filters: {
        type: Object,
        default: () => ({
            search: "",
        }),
    },
});

/*
|--------------------------------------------------------------------------
| State
|--------------------------------------------------------------------------
*/

const showModal = ref(false);
const isEdit = ref(false);

const search = ref(props.filters?.search ?? "");

/*
|--------------------------------------------------------------------------
| Form
|--------------------------------------------------------------------------
*/

const form = useForm({
    id: null,
    name: "",
});

/*
|--------------------------------------------------------------------------
| Modal
|--------------------------------------------------------------------------
*/

function openCreateModal() {
    isEdit.value = false;

    form.reset();
    form.clearErrors();

    showModal.value = true;
}

function openEditModal(role) {
    isEdit.value = true;

    form.clearErrors();

    form.id = role.id;
    form.name = role.name;

    showModal.value = true;
}

function closeModal() {
    if (form.processing) {
        return;
    }

    showModal.value = false;

    form.reset();
    form.clearErrors();
}

/*
|--------------------------------------------------------------------------
| Submit
|--------------------------------------------------------------------------
*/

function submit() {
    if (isEdit.value) {
        form.put(route("roles.update", form.id), {
            preserveScroll: true,

            onSuccess: () => {
                closeModal();
            },
        });

        return;
    }

    form.post(route("roles.store"), {
        preserveScroll: true,

        onSuccess: () => {
            closeModal();
        },
    });
}

/*
|--------------------------------------------------------------------------
| Delete
|--------------------------------------------------------------------------
*/

function deleteRole(role) {
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: `Role "${role.name}" akan dihapus.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus",
        cancelButtonText: "Batal",
        reverseButtons: true,
    }).then((result) => {
        if (!result.isConfirmed) {
            return;
        }

        router.delete(route("roles.destroy", role.id), {
            preserveScroll: true,
        });
    });
}

/*
|--------------------------------------------------------------------------
| Permission
|--------------------------------------------------------------------------
*/

function managePermissions(role) {
    router.visit(route("roles.permissions", role.id));
}

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

let searchTimeout = null;

watch(search, (value) => {
    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(() => {
        router.get(
            route("roles.index"),
            {
                search: value,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    }, 400);
});

/*
|--------------------------------------------------------------------------
| Pagination
|--------------------------------------------------------------------------
*/

const paginationLinks = computed(() => {
    return props.roles?.links ?? [];
});
</script>

<template>
    <div>
        <!-- Page Header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <div class="page-pretitle">Manajemen</div>

                        <h2 class="page-title">Roles</h2>
                    </div>

                    <div class="col-auto ms-auto d-print-none">
                        <button
                            v-if="can('roles.create')"
                            type="button"
                            class="btn btn-primary"
                            @click="openCreateModal"
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
                                <path d="M12 5v14" />
                                <path d="M5 12h14" />
                            </svg>

                            Tambah Role
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Body -->
        <div class="page-body">
            <div class="container-xl">
                <div class="card">
                    <!-- Card Header -->
                    <div class="card-header">
                        <div class="row w-100 align-items-center">
                            <div class="col">
                                <h3 class="card-title">Daftar Role</h3>
                            </div>

                            <div class="col-auto">
                                <div class="input-icon">
                                    <span class="input-icon-addon">
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
                                            <circle cx="11" cy="11" r="8" />
                                            <path d="m21 21-4.3-4.3" />
                                        </svg>
                                    </span>

                                    <input
                                        v-model="search"
                                        type="text"
                                        class="form-control"
                                        placeholder="Cari role..."
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th width="60">#</th>

                                    <th>Role</th>

                                    <th>Jumlah User</th>

                                    <th width="180">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    v-for="(role, index) in roles.data"
                                    :key="role.id"
                                >
                                    <td>
                                        {{
                                            (roles.current_page - 1) *
                                                roles.per_page +
                                            index +
                                            1
                                        }}
                                    </td>

                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="avatar avatar-sm me-2">
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
                                                    <path
                                                        d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z"
                                                    />
                                                    <path
                                                        d="M20 21a8 8 0 0 0-16 0"
                                                    />
                                                </svg>
                                            </span>

                                            <div>
                                                <div class="fw-bold">
                                                    {{ role.name }}
                                                </div>

                                                <div class="text-secondary">
                                                    Role
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="badge bg-blue-lt">
                                            {{ role.users_count }} User
                                        </span>
                                    </td>

                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <!-- Permission -->
                                            <button
                                                v-if="can('permissions.view')"
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                title="Manage Permission"
                                                @click="managePermissions(role)"
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
                                                    class="icon icon-sm"
                                                >
                                                    <path
                                                        d="M12 2a10 10 0 1 0 10 10"
                                                    />
                                                    <path d="M12 6v6l4 2" />
                                                </svg>
                                            </button>

                                            <!-- Edit -->
                                            <button
                                                v-if="can('roles.edit')"
                                                type="button"
                                                class="btn btn-sm btn-outline-warning"
                                                title="Edit"
                                                @click="openEditModal(role)"
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
                                                    class="icon icon-sm"
                                                >
                                                    <path d="M12 20h9" />
                                                    <path
                                                        d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"
                                                    />
                                                </svg>
                                            </button>

                                            <!-- Delete -->
                                            <button
                                                v-if="can('roles.delete')"
                                                type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Hapus"
                                                @click="deleteRole(role)"
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
                                                    class="icon icon-sm"
                                                >
                                                    <path d="M4 7h16" />
                                                    <path d="M10 11v6" />
                                                    <path d="M14 11v6" />
                                                    <path
                                                        d="M5 7l1 14h12l1-14"
                                                    />
                                                    <path d="M9 7V4h6v3" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Empty State -->
                                <tr v-if="roles.data.length === 0">
                                    <td colspan="4" class="text-center py-5">
                                        <div class="empty">
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
                                                    <circle
                                                        cx="12"
                                                        cy="12"
                                                        r="10"
                                                    />
                                                    <path d="M12 8v4" />
                                                    <path d="M12 16h.01" />
                                                </svg>
                                            </div>

                                            <p class="empty-title">
                                                Tidak ada role
                                            </p>

                                            <p
                                                class="empty-subtitle text-secondary"
                                            >
                                                Belum ada data role yang
                                                ditemukan.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div
                        v-if="roles.last_page > 1"
                        class="card-footer d-flex align-items-center"
                    >
                        <p class="m-0 text-secondary">
                            Menampilkan
                            <strong>{{ roles.from }}</strong>
                            sampai
                            <strong>{{ roles.to }}</strong>
                            dari
                            <strong>{{ roles.total }}</strong>
                            role
                        </p>

                        <ul class="pagination m-0 ms-auto">
                            <li
                                v-for="(link, index) in paginationLinks"
                                :key="index"
                                class="page-item"
                                :class="{
                                    active: link.active,
                                    disabled: !link.url,
                                }"
                            >
                                <button
                                    v-if="link.url"
                                    type="button"
                                    class="page-link"
                                    @click="
                                        router.visit(link.url, {
                                            preserveState: true,
                                            preserveScroll: true,
                                        })
                                    "
                                    v-html="link.label"
                                />

                                <span
                                    v-else
                                    class="page-link"
                                    v-html="link.label"
                                />
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create / Edit Modal -->
        <div
            v-if="showModal"
            class="modal modal-blur fade show"
            tabindex="-1"
            style="display: block"
            aria-modal="true"
            role="dialog"
        >
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form @submit.prevent="submit">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h5 class="modal-title">
                                {{ isEdit ? "Edit Role" : "Tambah Role" }}
                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                aria-label="Close"
                                @click="closeModal"
                            ></button>
                        </div>

                        <!-- Modal Body -->
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label required">
                                    Nama Role
                                </label>

                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.name,
                                    }"
                                    placeholder="Contoh: Admin"
                                    autocomplete="off"
                                />

                                <div
                                    v-if="form.errors.name"
                                    class="invalid-feedback"
                                >
                                    {{ form.errors.name }}
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn"
                                :disabled="form.processing"
                                @click="closeModal"
                            >
                                Batal
                            </button>

                            <button
                                type="submit"
                                class="btn btn-primary"
                                :disabled="form.processing"
                            >
                                <span
                                    v-if="form.processing"
                                    class="spinner-border spinner-border-sm me-2"
                                    role="status"
                                ></span>

                                {{ isEdit ? "Simpan Perubahan" : "Simpan" }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Backdrop -->
        <div v-if="showModal" class="modal-backdrop fade show"></div>
    </div>
</template>
