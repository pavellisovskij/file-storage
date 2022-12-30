<script>
import { ref } from 'vue';
import BreezeApplicationLogo from '@/Components/ApplicationLogo.vue';
import NewFolderModal from '@/Components/NewFolderModal';
import BreezeNavLink from '@/Components/NavLink.vue';
import {Head, Link} from '@inertiajs/inertia-vue3';
import {Inertia} from "@inertiajs/inertia";

const showingNavigationDropdown = ref(false);

export default {
    components: {
        Head,
        Link,
        NewFolderModal,
        BreezeApplicationLogo,
    },

    data() {
        return {
            showModal: false,
            searchStr: null
        }
    },

    methods: {
        selectFiles() {
            this.$refs.files.click();
        },

        search() {
            if (this.searchStr.length > 0) {
                Inertia.get(route('storage.search', { search: this.searchStr }));
            } else {
                Inertia.get(route('storage.root'));
            }
        }
    },

    mounted() {
        console.log(this.$page.props.currentFolder);
    }
}
</script>

<template>
    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand bg-light">
            <div class="container-fluid">
                <Link class="navbar-brand" :href="route('storage.root')">
                    <BreezeApplicationLogo style="width: 32px;" />
                    {{ $page.props.appName }}
                </Link>

                <ul v-if="$page.props.auth.user.role === 'admin'" class="navbar-nav">
                    <li class="nav-item">
                        <Link class="nav-link active" :href="route('admin.users.index')">Панель администратора</Link>
                    </li>
                </ul>

                <div class="btn-group me-auto" role="group">
                    <button class="btn btn-primary me-auto" @click="showModal = true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-folder-plus nav-button" viewBox="0 0 16 16">
                            <path d="m.5 3 .04.87a1.99 1.99 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14H9v-1H2.826a1 1 0 0 1-.995-.91l-.637-7A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09L14.54 8h1.005l.256-2.819A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2zm5.672-1a1 1 0 0 1 .707.293L7.586 3H2.19c-.24 0-.47.042-.683.12L1.5 2.98a1 1 0 0 1 1-.98h3.672z"/>
                            <path d="M13.5 10a.5.5 0 0 1 .5.5V12h1.5a.5.5 0 1 1 0 1H14v1.5a.5.5 0 1 1-1 0V13h-1.5a.5.5 0 0 1 0-1H13v-1.5a.5.5 0 0 1 .5-.5z"/>
                        </svg>
                    </button>

                    <Link class="btn btn-primary" :href="$page.props.currentFolder ? route('files.create', { folder: $page.props.currentFolder }) : route('files.create')" method="get" as="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-plus nav-button" viewBox="0 0 16 16">
                            <path d="M8 6.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V11a.5.5 0 0 1-1 0V9.5H6a.5.5 0 0 1 0-1h1.5V7a.5.5 0 0 1 .5-.5z"/>
                            <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                        </svg>
                    </Link>
                </div>

                <div class="m-auto">
                    {{ $page.props.auth.user.role === 1 ? 'БЕЗ ЛИМИТА' : $page.props.maxStorageSpace }} / {{ $page.props.filledStorageSpace }} Mb
                </div>

                <div class="hstack gap-2">
                    <div>{{ $page.props.auth.user.email }}</div>

                    <div class="vr"></div>

                    <div>
                        <Link class="btn btn-link" style="padding: 0;" :href="route('logout')" method="post" as="button">
                            Выйти
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <div class="input-group my-3 px-3">
            <input type="text" v-model="searchStr" class="form-control" placeholder="Введите имя папки или файла для поиска" aria-label="Search" aria-describedby="search">
            <button class="btn btn-outline-primary" type="button" id="search" @click="search()">Найти</button>
        </div>

        <div class="container-fluid">
            <div class="row">
                <main class="col-9">
                    <slot />
                </main>

                <div class="col-3">
                    <slot name="info" />
                </div>
            </div>
        </div>
    </div>

    <Teleport to="body">
        <!-- use the modal component, pass in the prop -->
        <NewFolderModal :show="showModal"
                        @close="showModal = false"
                        :current-route-name="$page.props.currentRouteName"
                        :parent-folder="$page.props.currentFolder"
        />
    </Teleport>
</template>

<style>
.nav-button {
    width: 24px;
}
</style>
