<script setup>
import { computed, ref } from "vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

defineOptions({
    layout: AppLayout,
});

const page = usePage();

const user = computed(() => page.props.auth?.user);

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
        default: false,
    },

    status: {
        type: String,
        default: null,
    },
});

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

const form = useForm({
    name: user.value?.name ?? "",
    email: user.value?.email ?? "",
});

const submit = () => {
    form.patch(route("profile.update"));
};

/*
|--------------------------------------------------------------------------
| Password
|--------------------------------------------------------------------------
*/

const passwordForm = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});

const passwordUpdated = ref(false);

const updatePassword = () => {
    passwordUpdated.value = false;

    passwordForm.put(route("password.update"), {
        preserveScroll: true,

        onSuccess: () => {
            passwordForm.reset();
            passwordUpdated.value = true;
        },
    });
};
</script>

<template>
    <Head title="Profile" />

    <!-- Page Header -->
    <div class="page-header d-print-none mb-3">
        <div class="container-fluid px-0">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">Profile</h2>

                    <div class="text-secondary mt-1">
                        Manage your account information.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cards">
        <!-- Profile Information -->
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Profile Information</h3>
                </div>

                <form @submit.prevent="submit">
                    <div class="card-body">
                        <!-- Name -->
                        <div class="mb-3">
                            <label class="form-label"> Name </label>

                            <input
                                v-model="form.name"
                                type="text"
                                class="form-control"
                                :class="{
                                    'is-invalid': form.errors.name,
                                }"
                            />

                            <div
                                v-if="form.errors.name"
                                class="invalid-feedback"
                            >
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label"> Email address </label>

                            <input
                                v-model="form.email"
                                type="email"
                                class="form-control"
                                :class="{
                                    'is-invalid': form.errors.email,
                                }"
                            />

                            <div
                                v-if="form.errors.email"
                                class="invalid-feedback"
                            >
                                {{ form.errors.email }}
                            </div>
                        </div>

                        <!-- Email Verification -->
                        <div
                            v-if="
                                mustVerifyEmail &&
                                user?.email_verified_at === null
                            "
                            class="alert alert-warning"
                        >
                            <div>Your email address is not verified.</div>

                            <div class="mt-2">
                                Please verify your email address.
                            </div>
                        </div>

                        <!-- Success -->
                        <div
                            v-if="status === 'profile-updated'"
                            class="alert alert-success"
                        >
                            Profile updated successfully.
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end">
                        <button
                            type="submit"
                            class="btn btn-primary"
                            :disabled="form.processing"
                        >
                            <span
                                v-if="form.processing"
                                class="spinner-border spinner-border-sm me-2"
                            ></span>

                            Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Account Information -->
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Account</h3>
                </div>

                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="avatar avatar-lg me-3">
                            {{ user?.name?.charAt(0)?.toUpperCase() ?? "U" }}
                        </span>

                        <div>
                            <div class="fw-bold">
                                {{ user?.name ?? "User" }}
                            </div>

                            <div class="text-secondary small">
                                {{ user?.email ?? "" }}
                            </div>
                        </div>
                    </div>

                    <div class="text-secondary small">
                        Update your personal information using the form.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Change Password -->
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Change Password</h3>
            </div>

            <form @submit.prevent="updatePassword">
                <div class="card-body">
                    <div v-if="passwordUpdated" class="alert alert-success">
                        Password updated successfully.
                    </div>
                    <div class="text-secondary mb-4">
                        Make sure your account is using a long, random password
                        to stay secure.
                    </div>

                    <!-- Current Password -->
                    <div class="mb-3">
                        <label class="form-label"> Current password </label>

                        <input
                            v-model="passwordForm.current_password"
                            type="password"
                            class="form-control"
                            :class="{
                                'is-invalid':
                                    passwordForm.errors.current_password,
                            }"
                            autocomplete="current-password"
                        />

                        <div
                            v-if="passwordForm.errors.current_password"
                            class="invalid-feedback"
                        >
                            {{ passwordForm.errors.current_password }}
                        </div>
                    </div>

                    <!-- New Password -->
                    <div class="mb-3">
                        <label class="form-label"> New password </label>

                        <input
                            v-model="passwordForm.password"
                            type="password"
                            class="form-control"
                            :class="{
                                'is-invalid': passwordForm.errors.password,
                            }"
                            autocomplete="new-password"
                        />

                        <div
                            v-if="passwordForm.errors.password"
                            class="invalid-feedback"
                        >
                            {{ passwordForm.errors.password }}
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-0">
                        <label class="form-label"> Confirm new password </label>

                        <input
                            v-model="passwordForm.password_confirmation"
                            type="password"
                            class="form-control"
                            :class="{
                                'is-invalid':
                                    passwordForm.errors.password_confirmation,
                            }"
                            autocomplete="new-password"
                        />

                        <div
                            v-if="passwordForm.errors.password_confirmation"
                            class="invalid-feedback"
                        >
                            {{ passwordForm.errors.password_confirmation }}
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-end">
                    <button
                        type="submit"
                        class="btn btn-primary"
                        :disabled="passwordForm.processing"
                    >
                        <span
                            v-if="passwordForm.processing"
                            class="spinner-border spinner-border-sm me-2"
                        ></span>

                        Update password
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
