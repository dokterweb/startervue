import { usePage } from "@inertiajs/vue3";

export function usePermission() {
    const page = usePage();

    const can = (permission) => {
        const user = page.props.auth?.user;

        if (!user) {
            return false;
        }

        // Superadmin bypass semua permission
        if (user.is_superadmin) {
            return true;
        }

        return user.permissions?.includes(permission) ?? false;
    };

    const hasRole = (role) => {
        const user = page.props.auth?.user;

        if (!user) {
            return false;
        }

        return user.roles?.includes(role) ?? false;
    };

    return {
        can,
        hasRole,
    };
}
