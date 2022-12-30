<script>
import Layout from '@/Layouts/Admin.vue';
import {Link, Head, useForm} from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";

export default {
    components: {
        Head,
        Link,
        Layout,
    },

    props: {
        user: Object,
        maxStorage: Number
    },

    setup (props) {
        const form = useForm({
            storage_size: Math.round(props.user.storage_size),
            status: props.user.status,
            role: props.user.role
        });

        return { form }
    },

    data() {
        return {

        }
    },

    methods: {
        update(id) {
            Inertia.put(route('admin.users.update', { user: id }), this.form);
        },
    }
}
</script>

<template>
    <Head :title="user.email + ' - редактирование пользователя'" />

    <Layout>
        <div class="row">
            <div class="col">
                <div class="mb-3">
                    <label for="number" class="form-label">Размер хранилища, Mb</label>
                    <input v-model="form.storage_size"
                           :disabled="form.role === 1"
                           min="1"
                           :max="Math.round(maxStorage)"
                           type="number"
                           class="form-control"
                           id="number"
                           placeholder="Размер хранилища"
                    >

                    <div v-if="$page.props.errors.storage_size" class="alert alert-danger mt-1" role="alert">
                        {{ $page.props.errors.storage_size }}
                    </div>
                </div>
            </div>

            <div class="col">
                <label for="role" class="form-label">Роль</label>
                <select v-model="form.role" class="form-select" id="role" aria-label="Role">
                    <option value="0" :selected="form.role === 0">user</option>
                    <option value="1" :selected="form.role === 1">admin</option>
                </select>

                <div v-if="$page.props.errors.role" class="alert alert-danger mt-1" role="alert">
                    {{ $page.props.errors.role }}
                </div>
            </div>

            <div class="col">
                <label for="status" class="form-label">Статус</label>
                <select v-model="form.status" class="form-select" id="status" aria-label="User status">
                    <option value="0" :selected="form.status === 0">заблокирован</option>
                    <option value="1" :selected="form.status === 1">активен</option>
                </select>

                <div v-if="$page.props.errors.status" class="alert alert-danger mt-1" role="alert">
                    {{ $page.props.errors.status }}
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-primary" @click="update(user.id)">
            Сохранить
            <svg xmlns="http://www.w3.org/2000/svg" style="width: auto;" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
            </svg>
        </button>
    </Layout>
</template>
