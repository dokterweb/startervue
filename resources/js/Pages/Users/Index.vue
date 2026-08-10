<script setup>
import { computed, ref, watch } from "vue";
import { Link, router } from "@inertiajs/vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import UserModal from "@/Components/Users/UserModal.vue";
import DeleteUserModal from "@/Components/Users/DeleteUserModal.vue";

import {
    IconPlus,
    IconSearch,
    IconEdit,
    IconTrash,
    IconUser,
} from "@tabler/icons-vue";

import { usePermission } from "@/Composables/usePermission";

defineOptions({
    layout: AppLayout,
});

const { can } = usePermission();

/*
|--------------------------------------------------------------------------
| Props
|--------------------------------------------------------------------------
*/

const props = defineProps({
    users: {
        type: Object,
        required: true,
    },

    roles: {
        type: Array,
        default: () => [],
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
| Search
|--------------------------------------------------------------------------
*/

const search = ref(props.filters.search ?? "");

let searchTimeout = null;

watch(search, (value) => {
    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(() => {
        router.get(
            route("users.index"),
            {
                search: value || undefined,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            }
        );
    }, 400);
});

/*
|--------------------------------------------------------------------------
| Users
|--------------------------------------------------------------------------
*/

const hasUsers = computed(() => {
    return props.users.data?.length > 0;
});

/*
|--------------------------------------------------------------------------
| User Modal
|--------------------------------------------------------------------------
*/

const showUserModal = ref(false);

const modalMode = ref("create");

const selectedUser = ref(null);

const showDeleteModal = ref(false);

const userToDelete = ref(null);

const openCreateModal = () => {
    modalMode.value = "create";

    selectedUser.value = null;

    showUserModal.value = true;
};

const openEditModal = (user) => {
    modalMode.value = "edit";

    selectedUser.value = user;

    showUserModal.value = true;
};

const closeUserModal = () => {
    showUserModal.value = false;

    selectedUser.value = null;
};

/*
|--------------------------------------------------------------------------
| Delete User
|--------------------------------------------------------------------------
*/

const openDeleteModal = (user) => {
    userToDelete.value = user;
    showDeleteModal.value = true;
};
const closeDeleteModal = () => {
    showDeleteModal.value = false;
    userToDelete.value = null;
};
/*
|--------------------------------------------------------------------------
| Avatar
|--------------------------------------------------------------------------
*/

const avatarUrl = (avatar) => {
    if (!avatar) {
        return null;
    }

    return `/storage/${avatar}`;
};
</script>

<template>
    <!-- ================================================================
         PAGE HEADER
    ================================================================= -->

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <!-- Title -->
                <div class="col">
                    <div class="page-pretitle">MASTER DATA</div>

                    <h2 class="page-title">Users</h2>
                </div>

                <!-- Add User -->
                <div
                    v-if="can('users.create')"
                    class="col-auto ms-auto d-print-none"
                >
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="openCreateModal"
                    >
                        <IconPlus :size="18" class="me-1" />

                        Add User
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================================================================
         PAGE BODY
    ================================================================= -->

    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <!-- ====================================================
                     SEARCH
                ===================================================== -->

                <div class="card-header">
                    <div class="row w-100">
                        <div class="col-md-6">
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                    <IconSearch :size="18" />
                                </span>

                                <input
                                    v-model="search"
                                    type="text"
                                    class="form-control"
                                    placeholder="Search name or email..."
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ====================================================
                     TABLE
                ===================================================== -->

                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>User</th>

                                <th>Email</th>

                                <th>Role</th>

                                <th class="w-1 text-nowrap">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <!-- ==================================================
                                 USER ROW
                            =================================================== -->

                            <tr v-for="user in users.data" :key="user.id">
                                <!-- User -->
                                <td>
                                    <div class="d-flex align-items-center">
                                        <!-- Avatar -->
                                        <span
                                            v-if="user.avatar"
                                            class="avatar me-2"
                                            :style="{
                                                backgroundImage: `url(${avatarUrl(
                                                    user.avatar
                                                )})`,
                                            }"
                                        ></span>

                                        <!-- Default Avatar -->
                                        <span v-else class="avatar me-2">
                                            <IconUser :size="20" />
                                        </span>

                                        <!-- Name -->
                                        <div>
                                            <div class="fw-bold">
                                                {{ user.name }}
                                            </div>

                                            <div class="text-secondary">
                                                #{{ user.id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Email -->
                                <td>
                                    <span>
                                        {{ user.email }}
                                    </span>
                                </td>

                                <!-- Role -->
                                <td>
                                    <span
                                        v-for="role in user.roles"
                                        :key="role.id"
                                        class="badge bg-blue-lt me-1"
                                    >
                                        {{ role.name }}
                                    </span>

                                    <span
                                        v-if="!user.roles?.length"
                                        class="text-secondary"
                                    >
                                        No Role
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <!-- Edit -->
                                        <button
                                            v-if="can('users.edit')"
                                            type="button"
                                            class="btn btn-sm"
                                            title="Edit"
                                            @click="openEditModal(user)"
                                        >
                                            <IconEdit :size="16" />
                                        </button>

                                        <!-- Delete -->
                                        <button
                                            v-if="
                                                can('users.delete') &&
                                                user.id !==
                                                    $page.props.auth?.user?.id
                                            "
                                            type="button"
                                            class="btn btn-sm btn-danger"
                                            title="Delete"
                                            @click="openDeleteModal(user)"
                                        >
                                            <IconTrash :size="16" />
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- ==================================================
                                 EMPTY DATA
                            =================================================== -->

                            <tr v-if="!hasUsers">
                                <td
                                    colspan="4"
                                    class="text-center text-secondary py-5"
                                >
                                    No users found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- ====================================================
                     PAGINATION
                ===================================================== -->

                <div
                    v-if="users.links?.length > 3"
                    class="card-footer d-flex align-items-center"
                >
                    <!-- Summary -->
                    <p class="m-0 text-secondary">
                        Showing

                        <span class="fw-bold">
                            {{ users.from ?? 0 }}
                        </span>

                        to

                        <span class="fw-bold">
                            {{ users.to ?? 0 }}
                        </span>

                        of

                        <span class="fw-bold">
                            {{ users.total }}
                        </span>

                        users
                    </p>

                    <!-- Pagination -->
                    <ul class="pagination m-0 ms-auto">
                        <li
                            v-for="link in users.links"
                            :key="link.label"
                            class="page-item"
                            :class="{
                                active: link.active,
                                disabled: !link.url,
                            }"
                        >
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                class="page-link"
                                preserve-scroll
                                preserve-state
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

    <!-- ================================================================
         USER MODAL
    ================================================================= -->

    <UserModal
        :show="showUserModal"
        :mode="modalMode"
        :user="selectedUser"
        :roles="props.roles"
        @close="closeUserModal"
    />

    <DeleteUserModal
        :show="showDeleteModal"
        :user="userToDelete"
        @close="closeDeleteModal"
    />
</template>
