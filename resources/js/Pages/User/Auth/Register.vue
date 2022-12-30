<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <BreezeGuestLayout>
        <Head title="Register" />

        <BreezeValidationErrors class="mb-4" />

        <form @submit.prevent="submit">
            <div class="form-floating mb-3">
                <BreezeInput id="email" type="email" v-model="form.email" required autocomplete="username" placeholder="example@test.com"/>
                <BreezeLabel for="email" value="Email" />
            </div>

            <div class="form-floating mb-3">
                <BreezeInput id="password" type="password"
                             placeholder="password" v-model="form.password" required autocomplete="new-password" />
                <BreezeLabel for="password" value="Password" />
            </div>

            <div class="form-floating mb-3">
                <BreezeInput id="password_confirmation" type="password"
                             placeholder="retry password" v-model="form.password_confirmation" required autocomplete="new-password" />
                <BreezeLabel for="password_confirmation" value="Confirm Password" />
            </div>

            <div class="hstack">
                <Link :href="route('login')" class="link-primary">
                    Already registered?
                </Link>

                <BreezeButton class="btn btn-primary ms-auto" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </BreezeButton>
            </div>
        </form>
    </BreezeGuestLayout>
</template>
