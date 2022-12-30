<script>
import Layout from '@/Layouts/User.vue';
import { Head } from '@inertiajs/inertia-vue3';

export default {
    components: {
        Head,
        Layout
    },

    props: {
        folder: {
            type: Object,
            default: null
        }
    },

    data() {
        return {
            error: '',
            orderOfFiles: [],
            uploadedFiles: [],
            failedUploads: [],
            uploadProgress: 0,
            currentFileName: ''
        }
    },

    methods: {
        selectFiles() {
            this.$refs.files.click();
        },

        async fileInputChange() {
            let files = Array.from(event.target.files);
            this.orderOfFiles = files.slice();

            for (let item of files) {
                await this.uploadFile(item);
            }
        },

        async uploadFile(item) {
            let form = new FormData();

            form.append('file', item);

            await axios.post(route('files.store', this.folder ? { folder: this.folder.encoded_name } : {}), form, {
                onUploadProgress: (itemUpload) => {
                    this.uploadProgress = Math.round((itemUpload.loaded / itemUpload.total) * 100);
                    this.currentFileName = item.name + ' ' + this.uploadProgress;
                }
            })
            .then(response => {
                this.uploadProgress = 0;
                this.currentFileName = '';
                this.uploadedFiles.push(item);
                this.orderOfFiles.splice(item, 1);
            })
            .catch(error => {
                this.failedUploads.push({
                    error: error.response.status === 422 ? error.response.data.message : null,
                    file: item
                });
                this.uploadProgress = 0;
                this.currentFileName = '';
                this.orderOfFiles.splice(item, 1);
            });
        }
    }
}
</script>

<template>
    <Head :title="(folder ? folder.original_name + ' - ' : '') + 'Загрузка файлов'" />

    <Layout>
        <div class="container-fluid mt-2">
            <input type="file" name="files" id="files" ref="files" multiple hidden @change="fileInputChange">

            <button class="btn btn-primary" v-on:click="selectFiles()" style="border-top-left-radius: 0.375rem;border-bottom-left-radius: 0.375rem;">
                Выбрать файлы

                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-plus nav-button" viewBox="0 0 16 16">
                    <path d="M8 6.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V11a.5.5 0 0 1-1 0V9.5H6a.5.5 0 0 1 0-1h1.5V7a.5.5 0 0 1 .5-.5z"/>
                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                </svg>
            </button>
        </div>

        <div class="container-fluid my-2">
            <div class="progress" style="height: 40px;">
                <div class="progress-bar" role="progressbar" :style="{ width: uploadProgress + '%'}">
                    {{ currentFileName }}%
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <h4 class="text-center">Файлы в очереди: {{ orderOfFiles.length }}</h4>

                <ul class="list-group">
                    <li v-for="file in orderOfFiles" class="list-group-item">
                        {{ file.name }}
                    </li>
                </ul>
            </div>

            <div class="col-6">
                <h4 class="text-center">Загруженные файлы: {{ uploadedFiles.length }}</h4>

                <ul class="list-group">
                    <li v-for="file in uploadedFiles" class="list-group-item">
                        {{ file.name }}
                    </li>
                </ul>
            </div>
        </div>

        <template #info>
            <div v-if="failedUploads.length > 0" class="container-fluid" style="margin-top: 105px;">
                <h4 class="text-center">Ошибки загрузки: {{ failedUploads.length }}</h4>

                <ul class="list-group">
                    <li v-for="file in failedUploads" class="list-group-item">
                        {{ file.file.name }}
                        <span class="badge bg-danger">{{ file.error ? file.error : 'Ошибка' }}</span>
                    </li>
                </ul>
            </div>
        </template>
    </Layout>
</template>
