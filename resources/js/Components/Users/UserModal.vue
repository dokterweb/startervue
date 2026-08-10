<script setup>
import { computed, watch } from "vue";
import { useForm } from "@inertiajs/vue3";

import { IconX, IconUpload, IconUser } from "@tabler/icons-vue";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },

    mode: {
        type: String,
        default: "create",
    },

    user: {
        type: Object,
        default: null,
    },

    roles: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(["close", "success"]);

const isEdit = computed(() => {
    return props.mode === "edit";
});

const modalTitle = computed(() => {
    return isEdit.value ? "Edit User" : "Create User";
});

const submitLabel = computed(() => {
    return isEdit.value ? "Save Changes" : "Create User";
});

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    avatar: null,
    role: "",
});

const avatarPreview = computed(() => {
    if (form.avatar instanceof File) {
        return URL.createObjectURL(form.avatar);
    }

    if (isEdit.value && props.user?.avatar) {
        return `/storage/${props.user.avatar}`;
    }

    return null;
});

const resetForm = () => {
    form.reset();

    form.clearErrors();

    form.avatar = null;
    form.role = "";

    if (isEdit.value && props.user) {
        form.name = props.user.name ?? "";
        form.email = props.user.email ?? "";

        form.role = props.user.roles?.[0]?.name ?? "";
    }
};

watch(
    () => props.show,
    (value) => {
        if (value) {
            resetForm();
        }
    }
);

const close = () => {
    if (form.processing) {
        return;
    }

    emit("close");
};

const handleAvatar = (event) => {
    const file = event.target.files?.[0];

    if (!file) {
        return;
    }

    form.avatar = file;
};

const submit = () => {
    if (isEdit.value) {
        form.transform((data) => ({
            ...data,
            _method: "PUT",
        })).post(route("users.update", props.user.id), {
            forceFormData: true,

            onSuccess: () => {
                emit("success");
                emit("close");
            },
        });

        return;
    }

    form.post(route("users.store"), {
        forceFormData: true,

        onSuccess: () => {
            emit("success");
            emit("close");
        },
    });
};
</script>

<template>
    <Teleport to="body">
        <!-- Modal -->
        <div
            v-if="show"
            class="modal modal-blur fade show d-block"
            tabindex="-1"
            role="dialog"
            aria-modal="true"
        >
            <div
                class="modal-dialog modal-lg modal-dialog-centered"
                role="document"
            >
                <div class="modal-content">
                    <!-- Header -->
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ modalTitle }}
                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            aria-label="Close"
                            :disabled="form.processing"
                            @click="close"
                        ></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body">
                        <!-- Avatar -->
                        <div class="mb-4 text-center">
                            <div class="mb-3">
                                <span
                                    v-if="avatarPreview"
                                    class="avatar avatar-xl"
                                    :style="{
                                        backgroundImage: `url(${avatarPreview})`,
                                    }"
                                ></span>

                                <span v-else class="avatar avatar-xl">
                                    <IconUser :size="32" />
                                </span>
                            </div>

                            <label class="btn btn-outline-secondary btn-sm">
                                <IconUpload :size="16" class="me-1" />

                                Choose Avatar

                                <input
                                    type="file"
                                    class="d-none"
                                    accept="image/*"
                                    @change="handleAvatar"
                                />
                            </label>

                            <div class="form-hint mt-2">
                                JPG, JPEG, PNG. Maksimal 2 MB.
                            </div>

                            <div
                                v-if="form.errors.avatar"
                                class="text-danger small mt-1"
                            >
                                {{ form.errors.avatar }}
                            </div>
                        </div>

                        <!-- Name -->
                        <div class="mb-3">
                            <label class="form-label required"> Name </label>

                            <input
                                v-model="form.name"
                                type="text"
                                class="form-control"
                                :class="{
                                    'is-invalid': form.errors.name,
                                }"
                                placeholder="Enter user name"
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
                            <label class="form-label required"> Email </label>

                            <input
                                v-model="form.email"
                                type="email"
                                class="form-control"
                                :class="{
                                    'is-invalid': form.errors.email,
                                }"
                                placeholder="Enter email address"
                            />

                            <div
                                v-if="form.errors.email"
                                class="invalid-feedback"
                            >
                                {{ form.errors.email }}
                            </div>
                        </div>

                        <!-- Role -->
                        <div class="mb-3">
                            <label class="form-label required"> Role </label>

                            <select
                                v-model="form.role"
                                class="form-select"
                                :class="{
                                    'is-invalid': form.errors.role,
                                }"
                            >
                                <option value="">Select role</option>

                                <option
                                    v-for="role in roles"
                                    :key="role.id"
                                    :value="role.name"
                                >
                                    {{ role.name }}
                                </option>
                            </select>

                            <div
                                v-if="form.errors.role"
                                class="invalid-feedback"
                            >
                                {{ form.errors.role }}
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label
                                    class="form-label"
                                    :class="{
                                        required: !isEdit,
                                    }"
                                >
                                    Password

                                    <span v-if="isEdit" class="text-secondary">
                                        (optional)
                                    </span>
                                </label>

                                <input
                                    v-model="form.password"
                                    type="password"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.password,
                                    }"
                                    :placeholder="
                                        isEdit
                                            ? 'Leave blank to keep current password'
                                            : 'Enter password'
                                    "
                                />

                                <div
                                    v-if="form.errors.password"
                                    class="invalid-feedback"
                                >
                                    {{ form.errors.password }}
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label
                                    class="form-label"
                                    :class="{
                                        required: !isEdit,
                                    }"
                                >
                                    Confirm Password
                                </label>

                                <input
                                    v-model="form.password_confirmation"
                                    type="password"
                                    class="form-control"
                                    placeholder="Confirm password"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn"
                            :disabled="form.processing"
                            @click="close"
                        >
                            Cancel
                        </button>

                        <button
                            type="button"
                            class="btn btn-primary"
                            :disabled="form.processing"
                            @click="submit"
                        >
                            <span
                                v-if="form.processing"
                                class="spinner-border spinner-border-sm me-2"
                            ></span>

                            {{ form.processing ? "Saving..." : submitLabel }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backdrop -->
        <div v-if="show" class="modal-backdrop fade show"></div>
    </Teleport>
</template>
