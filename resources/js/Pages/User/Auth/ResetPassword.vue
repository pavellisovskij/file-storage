<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <BreezeGuestLayout>
        <Head title="Reset Password" />

        <BreezeValidationErrors class="mb-4" />

        <form @submit.prevent="submit">
            <div class="form-floating mb-3">
                <BreezeInput id="email" type="email" placeholder="example@test.test" v-model="form.email" required autofocus autocomplete="username" />
                <BreezeLabel for="email" value="Email" />
            </div>

            <div class="form-floating mb-3">
                <BreezeInput id="password" type="password" placeholder="password" v-model="form.password" required autocomplete="new-password" />
                <BreezeLabel for="password" value="Password" />
            </div>

            <div class="form-floating mb-3">
                <BreezeInput id="password_confirmation" type="password" placeholder="confirm password" v-model="form.password_confirmation" required autocomplete="new-password" />
                <BreezeLabel for="password_confirmation" value="Confirm Password" />
            </div>

            <div class="mt-4">
                <BreezeButton class="btn btn-primary" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Reset Password
                </BreezeButton>
            </div>
        </form>
    </BreezeGuestLayout>
</template>
