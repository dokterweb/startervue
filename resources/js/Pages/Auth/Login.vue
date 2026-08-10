<template>
    <div>
        <!-- Header -->
        <div class="text-center mb-4">
            <h2 class="h3 mb-2">Login</h2>

            <p class="text-secondary mb-0">
                Masuk ke akun Anda untuk melanjutkan
            </p>
        </div>

        <!-- Status -->
        <div v-if="status" class="alert alert-success">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label"> Email </label>

                <input
                    id="email"
                    type="email"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.email }"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Masukkan email"
                />

                <div v-if="form.errors.email" class="invalid-feedback">
                    {{ form.errors.email }}
                </div>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label"> Password </label>

                <input
                    id="password"
                    type="password"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.password }"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="Masukkan password"
                />

                <div v-if="form.errors.password" class="invalid-feedback">
                    {{ form.errors.password }}
                </div>
            </div>

            <!-- Remember + Forgot -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <label class="form-check mb-0">
                    <input
                        type="checkbox"
                        class="form-check-input"
                        v-model="form.remember"
                    />

                    <span class="form-check-label"> Ingat saya </span>
                </label>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-decoration-none small"
                >
                    Lupa password?
                </Link>
            </div>

            <!-- Login Button -->
            <button
                type="submit"
                class="btn btn-primary w-100"
                :disabled="form.processing"
            >
                <span
                    v-if="form.processing"
                    class="spinner-border spinner-border-sm me-2"
                ></span>

                {{ form.processing ? "Memproses..." : "Login" }}
            </button>
        </form>

        <!-- Register -->
        <div class="text-center text-secondary mt-4">
            Belum punya akun?

            <Link
                :href="route('register')"
                class="text-decoration-none fw-semibold"
            >
                Daftar
            </Link>
        </div>
    </div>
</template>

<script setup>
import GuestLayout from "../../Layouts/GuestLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";

defineOptions({
    layout: GuestLayout,
});

defineProps({
    canResetPassword: {
        type: Boolean,
        default: false,
    },

    status: {
        type: String,
        default: null,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => {
            form.reset("password");
        },
    });
};
</script>
