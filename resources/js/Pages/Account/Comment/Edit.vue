<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    user_comment: {
        type: Object,
    },
});

const form = useForm({
    text: usePage().props.user_comment.text,
});
</script>


<template>
    <Head title="Comments" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Comments</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Update a comment</h2>
        </header>

        <form @submit.prevent="form.post(route('account.comments.edit', {id: user_comment.id}))" class="mt-6 space-y-6">
            <div>
                <InputLabel for="text" value="Comment" />

                <TextInput
                    id="text"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.text"
                    required
                    autofocus
                />

                <InputError class="mt-2" :message="form.errors.text" />
            </div>
            <div>
                <InputLabel for="is" value="Is spam?" />
                <div>{{ user_comment.is_spam == 1 ? 'yes' : 'no' }}</div>
            </div>
            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                </Transition>
            </div>
        </form>
            </div>
        </div>
    </AuthenticatedLayout>
        
</template>
