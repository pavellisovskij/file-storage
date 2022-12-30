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

                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <Link class="nav-link" :href="route('admin.users.index')">Пользователи</Link>
                    </li>
                </ul>

                <div class="hstack gap-2">
                    <div>{{ $page.props.auth.user.email }}</div>

                    <div class="vr"></div>

                    <div>
                        <Link class="btn btn-link" style="padding: 0;" :href="route('admin.logout')" method="post" as="button">
                            Выйти
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <main class="container-fluid mt-3">
            <slot />
        </main>
    </div>
</template>
