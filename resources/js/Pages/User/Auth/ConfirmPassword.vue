<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    })
};
</script>

<template>
    <BreezeGuestLayout>
        <Head title="Confirm Password" />

        <div class="mb-4 text-sm">
            This is a secure area of the application. Please confirm your password before continuing.
        </div>

        <BreezeValidationErrors class="mb-4" />

        <form @submit.prevent="submit">
            <div class="form-floating mb-3">
                <BreezeInput id="password" type="password" placeholder="password" v-model="form.password" required autocomplete="current-password" autofocus />
                <BreezeLabel for="password" value="Password" />
            </div>

            <div>
                <BreezeButton class="btn btn-primary ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Confirm
                </BreezeButton>
            </div>
        </form>
    </BreezeGuestLayout>
</template>
