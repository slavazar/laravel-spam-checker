<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link, router } from '@inertiajs/vue3';

    defineProps({
        user_comments: {
            type: Array,
        },
    });
    
    function deleteComment(id) {
        let confirm = window.confirm('Are you sure to delete the comment?');
        if (confirm) {
            router.get(route('account.comments.delete', {id: id}));
        }
    }
</script>

<template>
    <Head title="Comments" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Comments</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="">
                    <div class="">
                        <Link :href="route('account.comments.add')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                           >Add a comment</Link>
                    </div>
                </div>
                <div>
                    <table class="table-auto w-full border-separate border-spacing-2 border border-slate-500">
                        <thead>
                            <tr>
                                <th class="text-left border border-slate-600">ID</th>
                                <th class="text-left border border-slate-600">Text</th>
                                <th class="text-left border border-slate-600">Is spam</th>
                                <th class="text-left border border-slate-600">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user_comment in user_comments">
                                <td class="border border-slate-700">{{ user_comment.id }}</td>
                                <td class="border border-slate-700">{{ user_comment.text }}</td>
                                <td class="border border-slate-700">{{ user_comment.is_spam == 1 ? 'yes' : 'no' }}</td>
                                <td class="border border-slate-700">
                                    <Link :href="route('account.comments.edit', {id: user_comment.id})">Edit</Link>
                                    <span> | </span>
                                    <a @click.prevent="deleteComment(user_comment.id)" href="#">Delete</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
