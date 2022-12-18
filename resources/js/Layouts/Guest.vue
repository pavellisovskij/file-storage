<script setup>
import BreezeApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <div class="container text-center form-signin w-100 m-auto">
        <div class="row">
            <div class="col">
                <Link href="/">
                    <BreezeApplicationLogo class="mb-4" />
                </Link>

                <slot />
            </div>
        </div>
    </div>




<!--    <div class="text-center form-signin w-100 m-auto">-->
<!--        <Link href="/">-->
<!--            <BreezeApplicationLogo />-->
<!--        </Link>-->

<!--        <div class="">-->
<!--            <slot />-->
<!--        </div>-->
<!--    </div>-->
</template>
