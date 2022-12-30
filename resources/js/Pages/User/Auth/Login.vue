<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeCheckbox from '@/Components/Checkbox.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <BreezeGuestLayout>
        <Head title="Log in" />

        <BreezeValidationErrors class="mb-4" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div class="form-floating mb-3">
                <BreezeInput id="email" type="email" placeholder="example@test.test" v-model="form.email" required autofocus autocomplete="username" />
                <BreezeLabel for="email" value="Email" />
            </div>

            <div class="form-floating mb-3">
                <BreezeInput id="password" type="password" placeholder="password" v-model="form.password" required autocomplete="current-password" />
                <BreezeLabel for="password" value="Password" />
            </div>

            <div class="form-check" style="text-align: left !important;">
                <BreezeCheckbox name="remember" id="remember" v-model:checked="form.remember" />
                <label class="form-check-label" for="remember">
                    Remember me
                </label>
            </div>

            <div class="hstack mt-4">
                <Link v-if="canResetPassword" :href="route('password.request')" class="link-primary">
                    Forgot your password?
                </Link>

                <BreezeButton class="btn btn-primary ms-auto" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </BreezeButton>
            </div>
        </form>
    </BreezeGuestLayout>
</template>
