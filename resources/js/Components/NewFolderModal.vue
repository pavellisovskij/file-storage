<script>
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import BreezeButton from '@/Components/Button';
import { useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import axios from "axios";

export default {
    components: {
        BreezeInput,
        BreezeLabel,
        BreezeButton,
        BreezeValidationErrors,
        useForm
    },

    props: {
        show: Boolean,
        currentRouteName: String,
        parentFolder: {
            type: String,
            default: null
        }
    },

    data() {
        return {
            error: null
        }
    },

    setup (props) {
        const form = useForm({
            original_name: '',
            current_folder: props.parentFolder
        })

        return { form }
    },

    methods: {
        submit() {
            let app = this;

            if (this.parentFolder === null) {
                axios.post(route('folder.store'), this.form)
                    .then(function (response) {
                        app.form.reset('original_name');
                        app.error = null;
                        Inertia.reload({ only: ['folders'] });
                        app.$emit('close');
                    })
                    .catch(function (error) {
                        app.error = error.response.status === 422 ? error.response.data.message : null;
                    });
            } else {
                axios.post(route('folder.store', { parent: this.parentFolder }), this.form)
                    .then(function (response) {
                        app.form.reset('original_name');
                        app.error = null;
                        Inertia.reload({ only: ['curFolder'] });
                        app.$emit('close');
                    })
                    .catch(function (error) {
                        app.error = error.response.status === 422 ? error.response.data.message : null;
                    });
            }
        }
    }
}
</script>

<template>
    <Transition name="modal">
        <div v-if="show" class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <div class="card" style="padding: 0;">
                        <div class="card-header">
                            <div class="d-flex">
                                <div>Новая папка</div>

                                <div class="ms-auto">
                                    <button type="button" class="btn-close" aria-label="Close" @click="$emit('close'); form.reset()"></button>
                                </div>
                            </div>
                        </div>

                        <form @submit.prevent="submit()">
                            <div class="card-body">
                                <div v-if="error" class="alert alert-danger" role="alert">
                                    {{ error }}
                                </div>

                                <div class="form-floating">
                                    <BreezeInput id="name" type="text" placeholder="Имя" v-model="form.original_name" required autocomplete="name" />
                                    <BreezeLabel for="name" value="Имя" />
                                </div>
                            </div>

                            <div class="card-footer">
                                <BreezeButton class="btn btn-primary ms-auto" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Создать
                                </BreezeButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style>
.modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: table;
    transition: opacity 0.3s ease;
}

.modal-wrapper {
    display: table-cell;
    vertical-align: middle;
}

.modal-container {
    width: 300px;
    margin: 0px auto;
    border-radius: 2px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
    transition: all 0.3s ease;
}
</style>
