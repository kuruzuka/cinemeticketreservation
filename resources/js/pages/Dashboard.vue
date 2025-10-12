<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { Seat, Booking, Movie, BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

interface Props {
    movies: Movie[];
    bookings: Booking[];
    seats: Seat[];
}

const props = defineProps<Props>();

</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-9 px-10">
           <div class="grid grid-cols-3 gap-7">
                <div class="sm:col-span-3 md:col-span-3 lg:col-span-1 col-span-3">
                    <Card class="flex w-full h-[140px]">
                        <CardHeader>
                            <CardDescription class="text-lg">Total Movies</CardDescription>
                            <CardTitle class="text-6xl object-right">{{ props.movies.length }}</CardTitle>
                        </CardHeader>
                    </Card>
                </div>
               <div class="sm:col-span-3 md:col-span-3 lg:col-span-1 col-span-3">
                    <Card class="flex w-full h-[140px]">
                        <CardHeader>
                            <CardDescription class="text-lg">Total Bookings</CardDescription>
                            <CardTitle class="text-6xl object-right">{{ props.bookings.length }}</CardTitle>
                        </CardHeader>
                    </Card>
                </div>
                <div class="sm:col-span-3 md:col-span-3 lg:col-span-1 col-span-3">
                    <Card class="flex w-full h-[140px]">
                        <CardHeader>
                            <CardDescription class="text-lg">Total Seats Sold</CardDescription>
                            <CardTitle class="text-6xl object-right">{{ props.seats.length }}</CardTitle>
                        </CardHeader>
                    </Card>
                </div>
           </div>
        </div>
    </AppLayout>
</template>
