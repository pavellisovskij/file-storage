<script>
import Layout from '@/Layouts/User.vue';
import { Link, Head, useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";

export default {
    components: {
        Head,
        Link,
        Layout,
    },

    props: {
        searchable: String,
        files: Array,
        folders: Array
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
            file: null,
            folder: null,
            showFileNameInput: false,
            showFolderNameInput: false,
            showShareLinkInput: false,
            shareLink: null,
            folderError: null,
            fileError: null
        }
    },

    setup (props) {
        const fileNameForm = useForm({
            original_name: '',
            current_folder: props.currentRouteName === 'storage.folder' ? props.currentFolder : null
        });

        const folderNameForm = useForm({
            original_name: '',
            current_folder: props.currentRouteName === 'storage.folder' ? props.currentFolder : null
        });

        return { fileNameForm, folderNameForm }
    },

    methods: {
        toFolder(encodedName) {
            Inertia.get(route('storage.folder', { folder: encodedName }));
        },

        selectFile(selectedFile) {
            this.file = selectedFile;
            this.fileNameForm.original_name = selectedFile ? selectedFile.original_name : '';
        },

        selectFolder(selectedFolder) {
            this.folder = selectedFolder;
            this.folderNameForm.original_name = selectedFolder ? selectedFolder.original_name : '';
        },

        updateFileName(file) {
            let app = this;

            axios.put(route('files.update', { file: file.encoded_name }), this.fileNameForm)
                .then(function (response) {
                    app.fileNameForm.reset('original_name');
                    app.fileError = null;
                    app.showFileNameInput = false;
                    Inertia.reload({ only: ['files'] });
                })
                .catch(function (error) {
                    app.fileError = error.response.status === 422 ? error.response.data.message : null;
                });
        },

        updateFolderName(folder) {
            let app = this;

            axios.put(route('folder.update', { folder: folder.encoded_name }), this.folderNameForm)
                .then(function (response) {
                    app.folderNameForm.reset('original_name');
                    app.folderError = null;
                    app.showFolderNameInput = false;
                    Inertia.reload({ only: ['folders'] });
                })
                .catch(function (error) {
                    app.folderError = error.response.status === 422 ? error.response.data.message : null;
                });
        },

        deleteFile(file) {
            let app = this;

            axios.delete(route('files.destroy', { file: file.encoded_name }))
                .then(function (response) {
                    app.fileNameForm.reset('original_name');
                    app.fileError = null;
                    app.showFileNameInput = false;
                    Inertia.reload({ only: ['files'] });
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        deleteFolder(folder) {
            let app = this;

            axios.delete(route('folder.destroy', { folder: folder.encoded_name }))
                .then(function (response) {
                    app.folderNameForm.reset('original_name');
                    app.folderError = null;
                    app.showFolderNameInput = false;
                    Inertia.reload({ only: ['folders'] });
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        getShareLink(file) {
            let app = this;

            axios.get(route('files.share', { file: file.encoded_name }))
                .then(function (response) {
                    app.shareLink = response.data.route ? response.data.route : null;
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    }
}
</script>

<template>
    <Head :title="'Результаты поиска - ' + searchable" />

    <Layout>
        <div class="row row-cols-6 gy-2 text-center">
            <div v-for="folder in folders" class="col">
                <div class="card"
                     style="cursor: pointer;"
                     @dblclick="toFolder(folder.encoded_name)"
                     @click="
                        selectFile(null);
                        selectFolder(folder);
                        fileNameForm.original_name = '';
                        showFileNameInput = false;
                        showFolderNameInput = false;
                        fileError = null;
                        folderError = null;
                        shareLink = null;
                        showShareLinkInput = false;
                     "
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="card-img-top bi bi-folder" viewBox="0 0 16 16">
                        <path d="M.54 3.87.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.826a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31zM2.19 4a1 1 0 0 0-.996 1.09l.637 7a1 1 0 0 0 .995.91h10.348a1 1 0 0 0 .995-.91l.637-7A1 1 0 0 0 13.81 4H2.19zm4.69-1.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707z"/>
                    </svg>

                    <div class="card-footer">
                        {{ folder.original_name }}
                    </div>
                </div>
            </div>

            <div v-for="file in files" class="col">
                <div class="card"
                     @click="
                        selectFile(file);
                        selectFolder(null);
                        folderNameForm.original_name = '';
                        showFolderNameInput = false;
                        showFileNameInput = false;
                        folderError = null;
                        fileError = null;
                        shareLink = null;
                        showShareLinkInput = false;
                     "
                     style="cursor: pointer;"
                >
                    <img v-if="imageExtensions.includes(file.extension)" :src="'/storage/' + $page.props.auth.user.id + '/thumbnail/' + file.encoded_name + '.' + file.extension" class="card-img-top" :alt="file.original_name">
                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="card-img-top bi bi-file-earmark" viewBox="0 0 16 16">
                        <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                    </svg>

                    <div class="card-footer">
                        {{ file.original_name }}
                    </div>
                </div>
            </div>
        </div>

        <template #info>
            <div v-if="file" class="container">
                <ul class="list-group list-group-flush text-center">
                    <li v-if="imageExtensions.includes(file.extension)" class="list-group-item">
                        <img :src="'/storage/' + $page.props.auth.user.id + '/thumbnail/' + file.encoded_name + '.' + file.extension" :alt="file.original_name">
                    </li>
                    <li class="list-group-item">{{ file.original_name }}</li>
                    <li class="list-group-item">{{ Math.round((file.size / 1024) * 100) / 100 }} Kb</li>
                    <li class="list-group-item">{{ file.created_at }}</li>
                </ul>

                <div class="btn-group mt-3" role="group">
                    <a :href="route('files.download', { file: file.encoded_name })" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width: auto;" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                            <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                        </svg>
                    </a>

                    <button type="button" class="btn btn-primary" @click="getShareLink(file); showShareLinkInput = true;">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width: auto;" width="16" height="16" fill="currentColor" class="bi bi-share" viewBox="0 0 16 16">
                            <path d="M13.5 1a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM11 2.5a2.5 2.5 0 1 1 .603 1.628l-6.718 3.12a2.499 2.499 0 0 1 0 1.504l6.718 3.12a2.5 2.5 0 1 1-.488.876l-6.718-3.12a2.5 2.5 0 1 1 0-3.256l6.718-3.12A2.5 2.5 0 0 1 11 2.5zm-8.5 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm11 5.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3z"/>
                        </svg>
                    </button>

                    <button type="button" class="btn btn-primary" @click="showFileNameInput = true; fileNameForm.original_name = file.original_name;">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width: auto;" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                            <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/>
                        </svg>
                    </button>

                    <button type="button" class="btn btn-danger" @click="deleteFile(file)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width: auto;" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                        </svg>
                    </button>
                </div>

                <div v-if="showShareLinkInput && shareLink" class="input-group mt-3">
                    <input v-model="shareLink" type="text" class="form-control" disabled readonly placeholder="Ссылка" aria-label="Link for share file with one button addon">

                    <button class="btn btn-secondary" type="button"
                            @click="
                                showShareLinkInput = false;
                                shareLink = null;
                            "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" style="width: auto;" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </button>
                </div>

                <div v-if="showFileNameInput" class="input-group mt-3">
                    <input v-model="fileNameForm.original_name" type="text" class="form-control" placeholder="Имя файла" aria-label="File name with two button addons">

                    <button class="btn btn-primary" type="button" @click="updateFileName(file)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width: auto;" width="24" height="24" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                            <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                        </svg>
                    </button>

                    <button class="btn btn-secondary" type="button"
                            @click="
                                showFileNameInput = false;
                                fileNameForm.original_name = '';
                                fileError = null;
                            "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" style="width: auto;" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </button>
                </div>

                <div v-if="fileError" class="alert alert-danger mt-1" role="alert">
                    {{ fileError }}
                </div>
            </div>

            <div v-if="folder" class="container">
                <ul class="list-group list-group-flush text-center">
                    <li class="list-group-item">{{ folder.original_name }}</li>
                    <li class="list-group-item">{{ folder.created_at }}</li>
                </ul>

                <div class="btn-group mt-3" role="group">
                    <button type="button" class="btn btn-primary" @click="showFolderNameInput = true; folderNameForm.original_name = folder.original_name;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                            <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/>
                        </svg>
                    </button>

                    <button type="button" class="btn btn-danger" @click="deleteFolder(folder)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                        </svg>
                    </button>
                </div>

                <div v-if="showFolderNameInput" class="input-group mt-3">
                    <input v-model="folderNameForm.original_name" type="text" class="form-control" placeholder="Имя файла" aria-label="File name with two button addons">

                    <button class="btn btn-primary" type="button" @click="updateFolderName(folder)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width: auto;" width="24" height="24" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                            <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                        </svg>
                    </button>

                    <button class="btn btn-secondary" type="button"
                            @click="
                                showFolderNameInput = false;
                                folderNameForm.original_name = '';
                                folderError = null;
                            "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" style="width: auto;" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </button>
                </div>

                <div v-if="folderError" class="alert alert-danger mt-1" role="alert">
                    {{ folderError }}
                </div>
            </div>
        </template>
    </Layout>
</template>
