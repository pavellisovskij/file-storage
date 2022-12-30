<script>
import Layout from '@/Layouts/File.vue';
import { Link, Head } from '@inertiajs/inertia-vue3';

export default {
    components: {
        Head,
        Link,
        Layout,
    },

    props: {
        file: Object,
        downloadLink: Object
    },

    data() {
        return {
            imageExtensions: [
                'jpg',
                'jpeg',
                'png',
                'gif',
                'bmp'
            ],
        }
    },
}
</script>

<template>
    <Head :title="file.original_name" />

    <Layout>
        <div class="card border-0" style="width: 20rem;">
            <img v-if="imageExtensions.includes(file.extension)"
                 :src="'/storage/' + file.user.id + '/thumbnail/' + file.encoded_name + '.' + file.extension"
                 class="card-img-top"
                 :alt="file.original_name + '.' + file.extension"
            >

            <div class="card-body">
                <h5 class="card-title">{{ file.original_name + '.' + file.extension }}</h5>
            </div>

            <ul class="list-group list-group-flush">
                <li class="list-group-item">Размер: {{ file.size }} Кб</li>
                <li class="list-group-item">MIME: {{ file.mime }}</li>
            </ul>

            <div class="card-body">
                <a :href="downloadLink" class="card-link">Скачать</a>
            </div>
        </div>
    </Layout>
</template>
