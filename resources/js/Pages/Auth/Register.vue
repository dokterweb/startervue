<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => {
            form.reset("password", "password_confirmation");
        },
    });
};
</script>

<template>
    <Head title="Register" />

    <div class="auth-page">
        <div class="auth-container">
            <!-- Brand -->
            <div class="text-center mb-4">
                <Link href="/" class="text-decoration-none">
                    <div class="auth-brand">StarterVue</div>
                </Link>
            </div>

            <!-- Register Card -->
            <div class="card auth-card">
                <div class="card-body">
                    <h2 class="auth-title">Create new account</h2>

                    <!-- Name -->
                    <div class="mb-3">
                        <label class="form-label"> Name </label>

                        <input
                            v-model="form.name"
                            type="text"
                            class="form-control"
                            :class="{ 'is-invalid': form.errors.name }"
                            placeholder="Your name"
                            autocomplete="name"
                            autofocus
                        />

                        <div v-if="form.errors.name" class="invalid-feedback">
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
                            :class="{ 'is-invalid': form.errors.email }"
                            placeholder="name@example.com"
                            autocomplete="username"
                        />

                        <div v-if="form.errors.email" class="invalid-feedback">
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label"> Password </label>

                        <input
                            v-model="form.password"
                            type="password"
                            class="form-control"
                            :class="{ 'is-invalid': form.errors.password }"
                            placeholder="Password"
                            autocomplete="new-password"
                        />

                        <div
                            v-if="form.errors.password"
                            class="invalid-feedback"
                        >
                            {{ form.errors.password }}
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label class="form-label"> Confirm password </label>

                        <input
                            v-model="form.password_confirmation"
                            type="password"
                            class="form-control"
                            :class="{
                                'is-invalid': form.errors.password_confirmation,
                            }"
                            placeholder="Confirm password"
                            autocomplete="new-password"
                        />

                        <div
                            v-if="form.errors.password_confirmation"
                            class="invalid-feedback"
                        >
                            {{ form.errors.password_confirmation }}
                        </div>
                    </div>

                    <!-- Submit -->
                    <button
                        type="button"
                        class="btn btn-primary w-100"
                        :disabled="form.processing"
                        @click="submit"
                    >
                        <span
                            v-if="form.processing"
                            class="spinner-border spinner-border-sm me-2"
                        ></span>

                        {{
                            form.processing
                                ? "Creating account..."
                                : "Create account"
                        }}
                    </button>
                </div>
            </div>

            <!-- Login Link -->
            <div class="text-center mt-3 text-secondary">
                Already have an account?

                <Link
                    :href="route('login')"
                    class="text-primary text-decoration-none"
                >
                    Sign in
                </Link>
            </div>
        </div>
    </div>
</template>

<style scoped>
.auth-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f6f8fb;
    padding: 40px 20px;
}

.auth-container {
    width: 100%;
    max-width: 448px;
}

.auth-brand {
    font-size: 24px;
    font-weight: 600;
    color: #1f2937;
}

.auth-card {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.auth-title {
    text-align: center;
    font-size: 22px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 28px;
}

.form-label {
    font-weight: 500;
    color: #374151;
}

.form-control {
    height: 42px;
    border-radius: 6px;
}

.btn-primary {
    height: 42px;
    font-weight: 500;
}
</style>
