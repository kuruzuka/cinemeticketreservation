<script setup lang="ts">
    import AppLayout from '@/layouts/AppLayout.vue'
    import type { BreadcrumbItem, Genre, Movie } from '@/types';
    import { Head, useForm } from '@inertiajs/vue3';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';
    import { Button } from '@/components/ui/button';
    import { toast } from 'vue-sonner';
    import { SaveIcon } from 'lucide-vue-next';
    import {
        Select,
        SelectContent,
        SelectGroup,
        SelectItem,
        SelectLabel,
        SelectTrigger,
        SelectValue,
    } from "@/components/ui/select"
import { route } from 'ziggy-js';
    

    const breadcrumbs: BreadcrumbItem[] = [
        { 
            title: 'Movies', 
            href: '/movies' 
        },
        { 
            title: 'Edit Movie', 
            href: '/movies/{$movie}/edit' 
        },
    ];

    const props = defineProps<{
        movie: Movie,
        genres: Genre[],
    }>()

    const form = useForm({
        title: props.movie.title,
        genre_id: props.movie.genre.id,
        author: props.movie.author,
        director: props.movie.director,
    })

    const handleSubmit = () => {
        form.put(route('movie.update', {movie: props.movie}), {
            preserveState: false,
            onSuccess: () => {
                toast.success('Movie updated Successfully!', {
                    description: `Your changes have been successfully saved.`,
                })
            },
            onError: () => {
                if (form.errors.title) {
                    toast.error('Seat Needed', {
                    description: form.errors.title,
                    })
                }
                if (form.errors.author) {
                    toast.error('Author Name Required', {
                    description: form.errors.author,
                    })
                }
                if (form.errors.director) {
                    toast.error('Director Name Required', {
                    description: form.errors.director,
                    })
                }
            }
        })
    }
</script>

<template>
    <Head title="Edit Movie" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div class="w-[500px]">
                <div class="mb-5">
                    <Label class="mb-1">Movie Title</Label>
                    <Input v-model="form.title" type="text" />
                </div>
                <div class="mb-5">
                    <Label class="mb-1">Genre</Label>
                    <Select v-model="form.genre_id">
                        <SelectTrigger class="">
                            <SelectValue placeholder="Select a genre" />
                        </SelectTrigger>
                        <SelectContent class="w-[480px]">
                            <SelectGroup>
                                <SelectLabel>Genre</SelectLabel>
                                <SelectItem v-for="genre in genres" :value="genre.id" :key="genre.id">
                                    {{ genre.name }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                </div>
                <div class="mb-5">
                    <Label class="mb-1">Author</Label>
                    <Input v-model="form.author" type="text" />
                </div>
                <div class="mb-5">
                    <Label class="mb-1">Director</Label>
                    <Input v-model="form.director" type="text" />
                </div>
            </div>
            <Button class="w-[100px]" 
            :disabled="form.processing"
            @click="handleSubmit">
                <SaveIcon />
                Save
            </Button>
        </div>
    </AppLayout>
</template>